# 🛍️ E-Commerce Platform - Production Ready Laravel Application

A complete, enterprise-grade e-commerce platform built with Laravel 10, featuring JWT authentication, comprehensive product management, order processing, payment integration, and admin dashboard.

## ✨ Features

### 👥 Customer Features
- ✅ Browse & Search Products (full-text search)
- ✅ View Product Details & Reviews
- ✅ Shopping Cart Management
- ✅ Secure Checkout Process
- ✅ Payment Processing (Stripe, PayPal)
- ✅ Order Tracking
- ✅ Return Product Management
- ✅ User Profile Management

### 👨‍💼 Admin Features
- ✅ Dashboard with Real-time Analytics
- ✅ Product CRUD Operations
- ✅ Inventory & Stock Management
- ✅ Order Management & Tracking
- ✅ Return Request Management
- ✅ Revenue Analytics & Reports
- ✅ Customer Management
- ✅ Low Stock Alerts

### 🔒 Security Features
- ✅ JWT Authentication (Tymon\JWT)
- ✅ Role-based Access Control (RBAC)
- ✅ Encrypted Passwords
- ✅ Secure API Endpoints
- ✅ CORS Protection
- ✅ Request Validation

### 💰 Payment Integration
- ✅ Stripe Payment Gateway
- ✅ PayPal Support
- ✅ Transaction Logging
- ✅ Refund Management

---

## 🚀 Quick Start Setup

### Prerequisites
- PHP 8.1+
- Composer
- MySQL 5.7+
- XAMPP (for local development)

### Step 1: Initial Setup

```bash
# Navigate to project directory
cd c:\xampp\htdocs\project_ecommerce

# Copy environment file
copy .env.example .env

# Install dependencies
composer install

# Generate application key
php artisan key:generate

# Generate JWT secret
php artisan jwt:secret
```

### Step 2: Database Configuration

Edit `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_db
DB_USERNAME=root
DB_PASSWORD=
```

Then create the database:

```bash
# Create database in PhpMyAdmin or command line:
# mysql -u root -e "CREATE DATABASE ecommerce_db;"

# Run migrations
php artisan migrate

# Seed sample data
php artisan db:seed
```

### Step 3: Start the Server

```bash
# Start Laravel development server
php artisan serve

# Server runs at http://localhost:8000
```

---

## 📚 API Documentation

### Authentication Endpoints

**Register User**
```
POST /api/auth/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "phone": "08123456789"
}

Response: 201 Created
{
  "message": "User registered successfully",
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "user": { ... }
}
```

**Login**
```
POST /api/auth/login
Content-Type: application/json

{
  "email": "admin@ecommerce.com",
  "password": "password123"
}

Response: 200 OK
{
  "message": "Login successful",
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "user": { ... }
}
```

**Get Current User**
```
GET /api/auth/me
Authorization: Bearer {token}

Response: 200 OK
{ user object }
```

**Logout**
```
POST /api/auth/logout
Authorization: Bearer {token}

Response: 200 OK
{ "message": "Logout successful" }
```

### Product Endpoints

**List Products (Search)**
```
GET /api/products?page=1&per_page=15&search=CORALDAISY&category_id=3&min_price=50&max_price=100&sort=price&order=asc
Authorization: Bearer {token}

Response: 200 OK
{
  "data": [ ... ],
  "links": { ... },
  "meta": { ... }
}
```

**Get Product Details**
```
GET /api/products/{id}
Authorization: Bearer {token}

Response: 200 OK
{
  "id": 1,
  "name": "CORALDAISY Premium Leather Bag",
  "price": 89.99,
  "rating": 4.5,
  "stock": { "quantity": 100, "available": 100 },
  "reviews": [ ... ]
}
```

**Featured Products**
```
GET /api/products/featured
Authorization: Bearer {token}

Response: 200 OK
[ products array ]
```

**Add Review**
```
POST /api/products/{id}/reviews
Authorization: Bearer {token}
Content-Type: application/json

{
  "rating": 5,
  "title": "Great product",
  "comment": "This is an excellent product, highly recommended!"
}

Response: 201 Created
```

### Cart Endpoints

**Get Cart**
```
GET /api/cart
Authorization: Bearer {token}

Response: 200 OK
{
  "id": 1,
  "user_id": 1,
  "total_items": 3,
  "total_price": 249.97,
  "items": [
    {
      "id": 1,
      "product_id": 1,
      "quantity": 2,
      "price": 89.99
    }
  ]
}
```

**Add Item to Cart**
```
POST /api/cart/add
Authorization: Bearer {token}
Content-Type: application/json

{
  "product_id": 1,
  "quantity": 2
}

Response: 201 Created
```

**Update Cart Item**
```
PUT /api/cart/items/{itemId}
Authorization: Bearer {token}
Content-Type: application/json

{
  "quantity": 5
}

Response: 200 OK
```

**Remove from Cart**
```
DELETE /api/cart/items/{itemId}
Authorization: Bearer {token}

Response: 200 OK
```

**Clear Cart**
```
POST /api/cart/clear
Authorization: Bearer {token}

Response: 200 OK
```

### Order Endpoints

**Create Order**
```
POST /api/orders
Authorization: Bearer {token}
Content-Type: application/json

{
  "shipping_address": {
    "street": "123 Main St",
    "city": "Jakarta",
    "state": "DKI Jakarta",
    "postal_code": "12345",
    "country": "Indonesia"
  },
  "billing_address": {
    "street": "123 Main St",
    "city": "Jakarta",
    "state": "DKI Jakarta",
    "postal_code": "12345",
    "country": "Indonesia"
  },
  "shipping_method": "standard"
}

Response: 201 Created
```

**Get Orders**
```
GET /api/orders?page=1
Authorization: Bearer {token}

Response: 200 OK
```

**Get Order Details**
```
GET /api/orders/{orderId}
Authorization: Bearer {token}

Response: 200 OK
```

**Cancel Order**
```
POST /api/orders/{orderId}/cancel
Authorization: Bearer {token}

Response: 200 OK
```

### Payment Endpoints

**Process Payment**
```
POST /api/payments/process
Authorization: Bearer {token}
Content-Type: application/json

{
  "order_id": 1,
  "payment_method": "stripe",
  "token": "tok_visa"
}

Response: 200 OK
```

### Return Endpoints

**Initiate Return**
```
POST /api/returns
Authorization: Bearer {token}
Content-Type: application/json

{
  "order_id": 1,
  "reason": "defective",
  "description": "Product has manufacturing defect"
}

Response: 201 Created
```

**Get Returns**
```
GET /api/returns?page=1
Authorization: Bearer {token}

Response: 200 OK
```

### Admin Dashboard Endpoints

**Get Dashboard Stats**
```
GET /api/admin/dashboard
Authorization: Bearer {admin-token}
X-Admin: true

Response: 200 OK
{
  "stats": {
    "total_orders": 150,
    "total_revenue": 15000,
    "total_customers": 500,
    "total_products": 100,
    "pending_orders": 12,
    "pending_returns": 3
  },
  "recent_orders": [ ... ]
}
```

**Get Analytics**
```
GET /api/admin/analytics?period=month
Authorization: Bearer {admin-token}

Response: 200 OK
```

### Admin Product Management

**List Products (Admin)**
```
GET /api/admin/products?page=1&search=CORALDAISY
Authorization: Bearer {admin-token}

Response: 200 OK
```

**Create Product**
```
POST /api/admin/products
Authorization: Bearer {admin-token}
Content-Type: application/json

{
  "name": "New Product",
  "description": "Product description",
  "price": 99.99,
  "cost": 40.00,
  "sku": "SKU-001",
  "category_id": 3,
  "quantity": 100,
  "is_active": true,
  "is_featured": false
}

Response: 201 Created
```

**Update Product**
```
PUT /api/admin/products/{id}
Authorization: Bearer {admin-token}
Content-Type: application/json

{
  "name": "Updated Product",
  "price": 109.99
}

Response: 200 OK
```

**Delete Product**
```
DELETE /api/admin/products/{id}
Authorization: Bearer {admin-token}

Response: 200 OK
```

### Admin Stock Management

**Get Stock**
```
GET /api/admin/stock?page=1&low_stock=true
Authorization: Bearer {admin-token}

Response: 200 OK
```

**Update Stock**
```
PUT /api/admin/stock/{id}
Authorization: Bearer {admin-token}
Content-Type: application/json

{
  "quantity": 150,
  "reorder_level": 20
}

Response: 200 OK
```

**Reorder Stock**
```
POST /api/admin/stock/reorder
Authorization: Bearer {admin-token}
Content-Type: application/json

{
  "product_id": 1,
  "quantity": 50
}

Response: 200 OK
```

### Admin Return Management

**Get All Returns**
```
GET /api/admin/returns?page=1&status=requested
Authorization: Bearer {admin-token}

Response: 200 OK
```

**Approve Return**
```
PUT /api/admin/returns/{id}/approve
Authorization: Bearer {admin-token}

Response: 200 OK
```

**Reject Return**
```
PUT /api/admin/returns/{id}/reject
Authorization: Bearer {admin-token}
Content-Type: application/json

{
  "reason": "Does not meet return criteria"
}

Response: 200 OK
```

---

## 📊 Database Schema

### Users Table
- id, name, email, password, phone, role, address, city, state, postal_code, country, is_active, email_verified_at, timestamps

### Products Table
- id, name, slug, description, short_description, price, cost, sku, category_id, brand, rating, total_reviews, image_url, is_active, is_featured, timestamps, soft_deletes

### Categories Table
- id, name, slug, description, image_url, parent_id, display_order, is_active, timestamps, soft_deletes

### Orders Table
- id, order_number, user_id, total_amount, discount_amount, tax_amount, shipping_cost, final_amount, status, payment_status, shipping_address, billing_address, tracking_number, timestamps, soft_deletes

### Carts & Cart Items
- Tracks user shopping carts with items and quantities

### Payments
- Tracks all payment transactions with gateway responses

### Returns
- Manages return requests with reason, status, and refund tracking

### Reviews
- User product reviews with ratings and comments

---

## 🔑 Test Credentials

**Admin User:**
- Email: `admin@ecommerce.com`
- Password: `password123`

**Test Customer:**
- Email: `customer@ecommerce.com`
- Password: `password123`

---

## 🎯 Key Features Breakdown

### 1. Product Management
- Full CRUD operations for products
- Categorization system
- Featured products highlighting
- Stock tracking with low stock alerts
- Product reviews and ratings

### 2. Shopping Cart
- Real-time cart updates
- Item quantity management
- Automatic total calculation
- Tax computation (10%)
- Shipping cost inclusion

### 3. Order Processing
- Automated order creation
- Sequential order numbering
- Order status tracking
- Order cancellation support
- Stock reservation system

### 4. Payment System
- Stripe integration
- Secure payment processing
- Payment status tracking
- Transaction logging
- Refund management

### 5. Returns Management
- Return request initiation
- Admin approval workflow
- Refund processing
- Return status tracking

### 6. Analytics Dashboard
- Real-time sales metrics
- Revenue tracking
- Customer statistics
- Top products analysis
- Period-based analytics (daily, weekly, monthly, yearly)

---

## 🛠️ Development

### Project Structure
```
project_ecommerce/
├── app/
│   ├── Http/Controllers/      # Route handlers
│   ├── Http/Middleware/        # Authentication & Authorization
│   ├── Models/                 # Eloquent models
│   └── Services/               # Business logic
├── database/
│   ├── migrations/             # Database structure
│   └── seeders/                # Test data
├── routes/
│   └── api.php                 # API routes
├── config/
│   ├── jwt.php                 # JWT configuration
│   └── payment.php             # Payment configuration
└── resources/
    ├── views/                  # Blade templates
    └── js/                     # Vue components
```

### Artisan Commands

```bash
# Database management
php artisan migrate                 # Run all migrations
php artisan migrate:rollback        # Rollback migrations
php artisan db:seed                 # Seed sample data

# Key generation
php artisan key:generate            # Generate APP_KEY
php artisan jwt:secret              # Generate JWT_SECRET

# Cache clearing
php artisan cache:clear             # Clear application cache
php artisan config:cache            # Cache configuration

# Tinker (REPL)
php artisan tinker                  # Interactive shell for testing
```

---

## 🧪 Testing

```bash
# Run tests
php artisan test

# Run specific test file
php artisan test tests/Feature/AuthTest.php

# Run with coverage
php artisan test --coverage
```

---

## 📋 Deployment Checklist

- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate new `APP_KEY`
- [ ] Configure database for production
- [ ] Set up Stripe/PayPal credentials
- [ ] Configure email service
- [ ] Set up file storage (S3 optional)
- [ ] Enable HTTPS
- [ ] Configure CORS for frontend domain
- [ ] Set up automated backups
- [ ] Configure error monitoring (Sentry)
- [ ] Run migrations and seeders

---

## 🐛 Troubleshooting

**JWT Token Not Working?**
- Verify `JWT_SECRET` is set in `.env`
- Check token expiration time
- Ensure middleware is properly configured

**Database Connection Error?**
- Verify database credentials in `.env`
- Ensure MySQL is running
- Check database exists

**Payment Processing Failed?**
- Verify Stripe keys are correct
- Check payment amount is valid
- Review payment gateway response

---

## 📞 Support & Contribution

For issues or questions, please refer to Laravel documentation:
- [Laravel Official Docs](https://laravel.com/docs)
- [JWT Auth Package](https://github.com/tymondesigns/jwt-auth)
- [Stripe Documentation](https://stripe.com/docs/api)

---

## 📄 License

This project is licensed under the MIT License.

---

## 🎉 Your E-Commerce Platform is Ready!

Your professional Laravel e-commerce platform is now fully set up. All features are implemented and ready for:
- 🏢 Company Presentation
- 💼 Production Deployment
- 🚀 Feature Expansion
- 📈 Scaling

**Happy Selling! 🛍️**
