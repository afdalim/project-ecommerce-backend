<template>
  <div class="admin-dashboard">
    <h2>Admin Dashboard</h2>
    <div v-if="loading">Loading...</div>
    <div v-else>
      <div class="stats">
        <div>Total Orders: {{ stats.total_orders }}</div>
        <div>Total Revenue: {{ stats.total_revenue }}</div>
        <div>Total Customers: {{ stats.total_customers }}</div>
      </div>
      <h3>Recent Orders</h3>
      <ul>
        <li v-for="o in recent" :key="o.id">{{ o.order_number }} - {{ o.final_amount }}</li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  data() { return { stats: {}, recent: [], loading: false }; },
  mounted() { this.fetch(); },
  methods: {
    async fetch() {
      this.loading = true;
      try {
        const res = await this.$axios.get('/api/admin/dashboard');
        this.stats = res.data.stats;
        this.recent = res.data.recent_orders;
      } catch (e) { console.error(e); }
      this.loading = false;
    }
  }
};
</script>

<style scoped>
.stats { display:flex; gap:16px; }
</style>
