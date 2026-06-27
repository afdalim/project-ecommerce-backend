<template>
  <div class="checkout">
    <h2>Checkout</h2>
    <form @submit.prevent="placeOrder">
      <div>
        <label>Shipping Address</label>
        <textarea v-model="shipping" required></textarea>
      </div>
      <div>
        <label>Billing Address</label>
        <textarea v-model="billing" required></textarea>
      </div>
      <div>
        <label>Payment Token (Stripe test token)</label>
        <input v-model="token" placeholder="tok_visa" />
      </div>
      <button type="submit">Place Order</button>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return { shipping: '', billing: '', token: '' };
  },
  methods: {
    async placeOrder() {
      try {
        const res = await this.$axios.post('/api/orders', {
          shipping_address: { raw: this.shipping },
          billing_address: { raw: this.billing },
          shipping_method: 'standard',
        });

        const order = res.data.order;

        // Process payment immediately (for demo)
        await this.$axios.post('/api/payments/process', {
          order_id: order.id,
          payment_method: 'stripe',
          token: this.token || 'tok_visa',
        });

        alert('Order placed successfully');
        window.location.href = '/';
      } catch (e) {
        alert('Order failed: ' + (e.response?.data?.message || e.message));
      }
    }
  }
};
</script>

<style scoped>
.checkout textarea { width: 100%; height: 80px; }
</style>
