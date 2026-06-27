@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">Welcome to CORAL DAISY</h1>
        <p class="lead">Premium E-Commerce Platform with JWT Authentication & Stripe Payment</p>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">🔒 Secure</h5>
                <p class="card-text">JWT token-based authentication for maximum security</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">💳 Payment Ready</h5>
                <p class="card-text">Integrated Stripe payment gateway for seamless checkout</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">📦 Full Featured</h5>
                <p class="card-text">Products, cart, orders, returns, admin dashboard, and more</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-12">
        <a href="/products" class="btn btn-primary btn-lg">Shop Now</a>
    </div>
</div>
@endsection
