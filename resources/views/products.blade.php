@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">Our Products</h1>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Search products..." id="search">
            <button class="btn btn-primary" onclick="location.href='/products'">Clear</button>
        </div>
    </div>
</div>

<div id="products" class="row">
    <p>Loading products...</p>
</div>
@endsection

@section('scripts')
<script>
    // Vue ProductList component will be mounted here
    document.addEventListener('DOMContentLoaded', () => {
        fetch('/api/products')
            .then(r => r.json())
            .then(data => {
                const html = (data.data || data).map(p => `
                    <div class="col-md-4 mb-4">
                        <div class="card product-card">
                            <img src="${p.image_url || '/images/default.png'}" class="card-img-top">
                            <div class="card-body">
                                <h5 class="card-title">${p.name}</h5>
                                <p class="card-text">${p.short_description}</p>
                                <p><strong>$${p.price}</strong></p>
                                <button class="btn btn-sm btn-primary" onclick="addCart(${p.id})">Add to Cart</button>
                                <a href="/products/${p.id}" class="btn btn-sm btn-outline-primary">Details</a>
                            </div>
                        </div>
                    </div>
                `).join('');
                document.getElementById('products').innerHTML = html;
            });
    });

    function addCart(id) {
        const token = localStorage.getItem('token');
        fetch('/api/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({ product_id: id, quantity: 1 })
        }).then(r => r.json())
          .then(d => alert('Added to cart!'))
          .catch(() => alert('Login required'));
    }
</script>
@endsection
