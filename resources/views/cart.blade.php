@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <h1 class="mb-4">Shopping Cart</h1>
        <div id="cart-content">Loading...</div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function loadCart() {
        const token = localStorage.getItem('token');
        if (!token) {
            document.getElementById('cart-content').innerHTML = '<p>Please <a href="/login">login</a> first</p>';
            return;
        }

        fetch('/api/cart', {
            headers: { 'Authorization': `Bearer ${token}` }
        }).then(r => r.json())
          .then(cart => {
              if (!cart.items || cart.items.length === 0) {
                  document.getElementById('cart-content').innerHTML = '<p>Cart is empty</p>';
                  return;
              }
              const html = `
                  <table class="table">
                      <thead><tr><th>Product</th><th>Qty</th><th>Price</th><th></th></tr></thead>
                      <tbody>
                          ${cart.items.map(item => `
                              <tr>
                                  <td>${item.product?.name || 'Product'}</td>
                                  <td><input type="number" value="${item.quantity}" min="1" onchange="updateCart(${item.id}, this.value)"></td>
                                  <td>$${item.price}</td>
                                  <td><button class="btn btn-sm btn-danger" onclick="removeCart(${item.id})">Remove</button></td>
                              </tr>
                          `).join('')}
                      </tbody>
                  </table>
                  <hr>
                  <h4>Total: $${cart.total_price || 0}</h4>
                  <a href="/checkout" class="btn btn-primary btn-lg">Proceed to Checkout</a>
              `;
              document.getElementById('cart-content').innerHTML = html;
          });
    }

    function updateCart(itemId, qty) {
        const token = localStorage.getItem('token');
        fetch(`/api/cart/items/${itemId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({ quantity: qty })
        }).then(() => loadCart());
    }

    function removeCart(itemId) {
        const token = localStorage.getItem('token');
        fetch(`/api/cart/items/${itemId}`, {
            method: 'DELETE',
            headers: { 'Authorization': `Bearer ${token}` }
        }).then(() => loadCart());
    }

    loadCart();
</script>
@endsection
