@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">Admin Dashboard</h1>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Orders</h5>
                <p id="total-orders" class="h3">--</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Revenue</h5>
                <p id="total-revenue" class="h3">$--</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Customers</h5>
                <p id="total-customers" class="h3">--</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Low Stock Products</h5>
                <p id="low-stock" class="h3">--</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Recent Orders</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="orders-table">
                        <tr><td colspan="6">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <a href="/admin/products" class="btn btn-primary">Manage Products</a>
        <a href="/admin/stock" class="btn btn-primary">Manage Stock</a>
        <a href="/admin/returns" class="btn btn-primary">Manage Returns</a>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function loadDashboard() {
        const token = localStorage.getItem('token');
        if (!token) {
            window.location.href = '/login';
            return;
        }

        fetch('/api/admin/dashboard', {
            headers: { 'Authorization': `Bearer ${token}` }
        }).then(r => r.json())
          .then(data => {
              document.getElementById('total-orders').textContent = data.stats?.total_orders || 0;
              document.getElementById('total-revenue').textContent = '$' + (data.stats?.total_revenue || 0);
              document.getElementById('total-customers').textContent = data.stats?.total_customers || 0;
              document.getElementById('low-stock').textContent = data.stats?.low_stock_count || 0;

              const rows = (data.recent_orders || []).map(o => `
                  <tr>
                      <td>${o.order_number}</td>
                      <td>${o.user?.name || 'N/A'}</td>
                      <td>$${o.final_amount}</td>
                      <td><span class="badge bg-info">${o.status}</span></td>
                      <td>${new Date(o.created_at).toLocaleDateString()}</td>
                      <td><a href="/admin/orders/${o.id}" class="btn btn-sm btn-outline-primary">View</a></td>
                  </tr>
              `).join('');
              document.getElementById('orders-table').innerHTML = rows || '<tr><td colspan="6">No orders</td></tr>';
          })
          .catch(() => alert('Error loading dashboard'));
    }

    loadDashboard();
</script>
@endsection
