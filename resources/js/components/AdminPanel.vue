<template>
  <div class="admin-panel">
    <h2>Admin Panel</h2>
    <p>Use the dashboard to manage products, stock, and returns.</p>
    <dashboard-view />
  </div>
</template>

<script>
import Dashboard from './Dashboard.vue';
export default { components: { Dashboard } };
</script>

<style scoped>
.admin-panel { padding: 12px; }
</style>
