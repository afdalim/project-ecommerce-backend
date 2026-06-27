import { createApp } from 'vue';
import axios from 'axios';

import ProductList from './components/ProductList.vue';
import Cart from './components/Cart.vue';
import Checkout from './components/Checkout.vue';
import Dashboard from './components/Dashboard.vue';
import AdminPanel from './components/AdminPanel.vue';

const app = createApp({});
app.component('product-list', ProductList);
app.component('cart-view', Cart);
app.component('checkout-view', Checkout);
app.component('dashboard-view', Dashboard);
app.component('admin-panel', AdminPanel);

app.config.globalProperties.$axios = axios;

app.mount('#app');
