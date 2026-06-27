@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <h1 class="mb-4">Checkout</h1>
        <form id="checkoutForm">
            <div class="card mb-3">
                <div class="card-header">Shipping Address</div>
                <div class="card-body">
                    <textarea class="form-control" id="shipping" required placeholder="Enter your shipping address"></textarea>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">Billing Address</div>
                <div class="card-body">
                    <textarea class="form-control" id="billing" required placeholder="Enter your billing address"></textarea>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">Payment Method</div>
                <div class="card-body">
                    <p><strong>Test Card:</strong> 4242 4242 4242 4242</p>
                    <p><strong>Expiry:</strong> Any future date | <strong>CVC:</strong> Any 3 digits</p>
                    <div id="card-element" style="border: 1px solid #ddd; padding: 10px; border-radius: 4px; margin-bottom: 12px;"></div>
                    <input type="hidden" id="token" />
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg">Place Order</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('pk_test_YOUR_STRIPE_KEY'); // Replace with your key
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    
    cardElement.mount('#card-element');
    cardElement.on('change', (event) => {
        if (event.error) console.log(event.error.message);
    });

    document.getElementById('checkoutForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const token = localStorage.getItem('token');
        if (!token) {
            alert('Please login first');
            window.location.href = '/login';
            return;
        }

        // Create order
        const orderRes = await fetch('/api/orders', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({
                shipping_address: { raw: document.getElementById('shipping').value },
                billing_address: { raw: document.getElementById('billing').value },
                shipping_method: 'standard'
            })
        });

        const orderData = await orderRes.json();
        if (!orderData.order) {
            alert('Failed to create order');
            return;
        }

        // Create payment token
        const { token: cardToken } = await stripe.createToken(cardElement);
        if (cardToken.error) {
            alert('Card error: ' + cardToken.error.message);
            return;
        }

        // Process payment
        const paymentRes = await fetch('/api/payments/process', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({
                order_id: orderData.order.id,
                payment_method: 'stripe',
                token: cardToken.id
            })
        });

        const paymentData = await paymentRes.json();
        if (paymentData.success) {
            alert('Order placed successfully!');
            window.location.href = '/';
        } else {
            alert('Payment failed: ' + paymentData.message);
        }
    });
</script>
@endsection
