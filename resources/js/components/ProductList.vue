<template>
  <div class="product-list">
    <h2>Products</h2>
    <div v-if="loading">Loading...</div>
    <div v-else>
      <div v-for="product in products" :key="product.id" class="product-item">
        <img :src="product.image_url || '/images/products/default.png'" alt="" />
        <h3>{{ product.name }}</h3>
        <p>{{ product.short_description }}</p>
        <p>Price: {{ product.price }}</p>
        <button @click="addToCart(product.id)">Add to cart</button>
      </div>
      <button v-if="pagination.next_page_url" @click="loadMore">Load more</button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      products: [],
      loading: false,
      pagination: {},
    };
  },
  mounted() {
    this.fetchProducts();
  },
  methods: {
    async fetchProducts() {
      this.loading = true;
      try {
        const res = await this.$axios.get('/api/products');
        this.products = res.data.data || res.data;
        this.pagination = res.data.meta || {};
      } catch (e) {
        console.error(e);
      }
      this.loading = false;
    },
    async addToCart(productId) {
      try {
        await this.$axios.post('/api/cart/add', { product_id: productId, quantity: 1 });
        alert('Added to cart');
      } catch (e) {
        alert('Failed to add to cart');
      }
    },
    async loadMore() {
      if (!this.pagination.next_page_url) return;
      try {
        const res = await this.$axios.get(this.pagination.next_page_url);
        this.products.push(...(res.data.data || res.data));
        this.pagination = res.data.meta || {};
      } catch (e) {
        console.error(e);
      }
    },
  },
};
</script>

<style scoped>
.product-item { border: 1px solid #eee; padding: 12px; margin: 8px 0; }
.product-item img { width: 120px; height: 120px; object-fit: cover; }
</style>
