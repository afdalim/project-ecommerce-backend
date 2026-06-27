<template>
  <div class="cart">
    <h2>Your Cart</h2>
    <div v-if="loading">Loading...</div>
    <div v-else>
      <div v-if="!cart || cart.items.length === 0">Cart is empty</div>
      <div v-else>
        <div v-for="item in cart.items" :key="item.id" class="cart-item">
          <h4>{{ item.product.name }}</h4>
          <p>Quantity: <input type="number" v-model.number="item.quantity" @change="update(item)" /></p>
          <p>Price: {{ item.price }}</p>
          <button @click="remove(item.id)">Remove</button>
        </div>
        <p>Total: {{ cart.total_price }}</p>
        <button @click="checkout">Proceed to Checkout</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return { cart: null, loading: false };
  },
  mounted() {
    this.fetchCart();
  },
  methods: {
    async fetchCart() {
      this.loading = true;
      try {
        const res = await this.$axios.get('/api/cart');
        this.cart = res.data;
      } catch (e) {
        console.error(e);
      }
      this.loading = false;
    },
    async update(item) {
      try {
        await this.$axios.put(`/api/cart/items/${item.id}`, { quantity: item.quantity });
        this.fetchCart();
      } catch (e) { console.error(e); }
    },
    async remove(itemId) {
      try {
        await this.$axios.delete(`/api/cart/items/${itemId}`);
        this.fetchCart();
      } catch (e) { console.error(e); }
    },
    checkout() {
      window.location.href = '/checkout';
    }
  }
};
</script>

<style scoped>
.cart-item { border-bottom: 1px solid #ddd; padding: 8px 0; }
</style>
