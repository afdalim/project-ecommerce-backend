# Complete Installation & Setup Guide

## 🚀 Windows Installation (XAMPP)

This guide will help you set up the Laravel E-Commerce Platform on Windows with XAMPP.

### Prerequisites
- **XAMPP** installed with PHP 8.1+, MySQL, Apache
- **Composer** installed globally
- **Git** (optional)

### Step-by-Step Setup

#### 1️⃣ Create Database

Open phpMyAdmin (http://localhost/phpmyadmin) or use command line:

```bash
mysql -u root
CREATE DATABASE ecommerce_db;
EXIT;
```

#### 2️⃣ Configure Environment

Edit `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_db
DB_USERNAME=root
DB_PASSWORD=

JWT_SECRET=your-secret-key-here
JWT_ALGORITHM=HS256
JWT_TTL=60

STRIPE_PUBLIC_KEY=pk_test_your_key
STRIPE_SECRET_KEY=sk_test_your_key
```

#### 3️⃣ Run Setup

**Option A: Automatic Setup (Windows)**
```bash
setup.bat
```

**Option B: Manual Setup**
```bash
# Install dependencies
composer install

# Generate application key
php artisan key:generate

# Generate JWT secret
php artisan jwt:secret

# Run migrations
php artisan migrate

# Seed database with test data
php artisan db:seed
```

#### 4️⃣ Start the Server

```bash
php artisan serve
```

The application will be available at: **http://localhost:8000**

---

## 📱 API Testing

### Using Postman

1. **Set Base URL**: `http://localhost:8000/api`

2. **Login to get token**:
   - POST `/auth/login`
   - Email: `admin@ecommerce.com`
   - Password: `password123`
   - Copy the token from response

3. **Set Authorization Header**:
   - Header: `Authorization`
   - Value: `Bearer {your_token}`

### Using cURL

```bash
# Register
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name":"Test User",
    "email":"test@example.com",
    "password":"password123",
    "password_confirmation":"password123",
    "phone":"08123456789"
  }'

# Login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email":"admin@ecommerce.com",
    "password":"password123"
  }'

# Get Products (requires token)
curl -X GET http://localhost:8000/api/products \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## 🎯 Quick Feature Test

### 1. Test Product Browsing

```bash
GET /api/products
GET /api/products/featured
GET /api/products/1
GET /api/products?search=CORALDAISY&category_id=3
```

### 2. Test Shopping Cart

```bash
POST /api/cart/add
{
  "product_id": 1,
  "quantity": 2
}

GET /api/cart

PUT /api/cart/items/1
{
  "quantity": 3
}

DELETE /api/cart/items/1
```

### 3. Test Checkout & Orders

```bash
POST /api/orders
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

GET /api/orders
GET /api/orders/1
```

### 4. Test Admin Features

```bash
GET /api/admin/dashboard
GET /api/admin/analytics?period=month

# Create product
POST /api/admin/products
{
  "name": "New Product",
  "description": "Description",
  "price": 99.99,
  "sku": "NEW-001",
  "category_id": 1,
  "quantity": 100
}

# Update stock
PUT /api/admin/stock/1
{
  "quantity": 150
}

# Get returns
GET /api/admin/returns
```

---

## 🔧 Troubleshooting

### Issue: "Database not found"
```bash
# Create database
mysql -u root -e "CREATE DATABASE ecommerce_db;"

# Run migrations
php artisan migrate
```

### Issue: "JWT secret not set"
```bash
php artisan jwt:secret
```

### Issue: "CORS error"
Check `config/cors.php` and ensure your frontend domain is allowed.

### Issue: "Port 8000 already in use"
```bash
php artisan serve --port=8001
```

### Issue: "Composer command not found"
- Install Composer from https://getcomposer.org
- Add to System PATH

---

## 📊 Database Tables Overview

| Table | Purpose |
|-------|---------|
| users | Customer & admin accounts |
| products | Product catalog |
| categories | Product categories |
| stock | Inventory tracking |
| carts | Shopping carts |
| cart_items | Items in cart |
| orders | Customer orders |
| order_items | Order line items |
| payments | Payment transactions |
| returns | Return requests |
| reviews | Product reviews |

---

## 🔐 API Authentication

All API endpoints (except register/login) require JWT token.

**Token Headers:**
```
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...
```

**Token Expiration:** 60 minutes (configurable in `.env` with `JWT_TTL`)

**Refresh Token:**
```bash
POST /api/auth/refresh
Authorization: Bearer {old_token}
```

---

## 📈 Performance Tips

1. **Cache Configuration**
   ```bash
   php artisan config:cache
   php artisan route:cache
   ```

2. **Database Optimization**
   - Ensure indexes are created (migrations handle this)
   - Monitor slow queries in logs

3. **API Response Optimization**
   - Use pagination (default: 15 per page)
   - Filter results with search/category

---

## 🎉 You're All Set!

Your e-commerce platform is ready for:
- ✅ Development & Testing
- ✅ Company Presentations
- ✅ Production Deployment
- ✅ Feature Expansion

---

**For more help, refer to:**
- Laravel Docs: https://laravel.com/docs
- API Docs: Check README.md in project root
- JWT Auth: https://github.com/tymondesigns/jwt-auth

**Happy Coding! 🚀**
