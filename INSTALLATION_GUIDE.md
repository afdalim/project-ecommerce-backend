# 🚀 CORAL DAISY E-Commerce Platform - Quick Start Guide

## 📋 Prerequisites

Before starting, ensure you have installed:
- **XAMPP** (with PHP 8.1+, MySQL, Apache) - https://www.apachefriends.org/
- **Composer** - https://getcomposer.org/download/
- **Node.js & npm** - https://nodejs.org/

## ⚡ Quick Setup (5 minutes)

### Step 1: Navigate to Project Directory
```bash
cd c:\xampp\htdocs\project_ecommerce
```

### Step 2: Install Dependencies
```bash
composer install
npm install
```

### Step 3: Generate Application Keys
```bash
php artisan key:generate
php artisan jwt:secret --force
```

### Step 4: Configure Database

**Option A: Using phpMyAdmin (Recommended for beginners)**
1. Open http://localhost/phpmyadmin
2. Click "New" on the left sidebar
3. Create database named: `project_ecommerce`
4. Click Create

**Option B: Using Command Line**
```bash
mysql -u root -p
CREATE DATABASE project_ecommerce;
EXIT;
```

### Step 5: Update .env File

Edit `.env` file in the project root:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=project_ecommerce
DB_USERNAME=root
DB_PASSWORD=
```

### Step 6: Run Migrations & Seeders

```bash
php artisan migrate
php artisan db:seed
```

### Step 7: Build Frontend Assets

```bash
npm run dev
```

Or for production:
```bash
npm run build
```

### Step 8: Start the Development Server

```bash
php artisan serve
```

Visit: **http://localhost:8000**

---

## 🔐 Default Credentials

### Admin Account
- **Email:** admin@coraldaisy.com
- **Password:** Admin@123456

### Test Customer Account
- **Email:** customer@test.com
- **Password:** Password@123

---

## 🧪 Testing the API

### 1. Login (Get JWT Token)

```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@coraldaisy.com",
    "password": "Admin@123456"
  }'
```

Response:
```json
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "token_type": "bearer",
  "expires_in": 3600
}
```

### 2. Get Products

```bash
curl http://localhost:8000/api/products
```

### 3. Get Protected Route (with token)

```bash
curl http://localhost:8000/api/user \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

### 4. Add to Cart

```bash
curl -X POST http://localhost:8000/api/cart/add \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "product_id": 1,
    "quantity": 2
  }'
```

---

## 📂 Project Structure

```
project_ecommerce/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # API controllers
│   │   ├── Middleware/           # JWT & Admin middleware
│   │   └── Requests/             # Form validation
│   ├── Models/                   # Eloquent models (12 models)
│   └── Services/                 # Business logic (Order, Payment services)
├── database/
│   ├── migrations/               # Database tables
│   └── seeders/                  # Sample data
├── resources/
│   ├── js/
│   │   ├── app.js                # Vue app entry
│   │   └── components/           # Vue components
│   └── views/                    # Blade templates
├── routes/
│   ├── api.php                   # API endpoints
│   └── web.php                   # Web routes
├── config/
│   ├── jwt.php                   # JWT configuration
│   ├── payment.php               # Payment settings
│   └── auth.php                  # Authentication
├── .env                          # Environment variables
├── composer.json                 # PHP dependencies
├── package.json                  # Node.js dependencies
└── README.md                     # Documentation
```

---

## 🛠️ Features Implemented

### ✅ Customer Features
- View products with search & filters
- Add products to cart
- Checkout with Stripe payment
- Order tracking
- Product reviews
- Return requests
- User dashboard

### ✅ Admin Features
- Dashboard with analytics
- Product management (CRUD)
- Stock management
- Order management
- Return management
- Customer management
- Revenue analytics

### ✅ Security Features
- JWT token-based authentication
- Admin role verification
- Password hashing
- Protected API endpoints
- CORS configuration

---

## 🔑 API Endpoints

### Authentication
- `POST /api/auth/register` - Register new user
- `POST /api/auth/login` - Login user
- `POST /api/auth/logout` - Logout (requires token)
- `POST /api/auth/refresh` - Refresh JWT token
- `GET /api/auth/me` - Get current user

### Products
- `GET /api/products` - List all products (paginated)
- `GET /api/products/{id}` - Get product details
- `GET /api/categories` - List categories
- `GET /api/products/search/{query}` - Search products

### Shopping Cart
- `GET /api/cart` - Get cart (requires token)
- `POST /api/cart/add` - Add item to cart (requires token)
- `PUT /api/cart/items/{id}` - Update cart item quantity
- `DELETE /api/cart/items/{id}` - Remove item from cart
- `DELETE /api/cart` - Clear cart

### Orders
- `GET /api/orders` - Get user orders (requires token)
- `POST /api/orders` - Create order (requires token)
- `GET /api/orders/{id}` - Get order details
- `PUT /api/orders/{id}/cancel` - Cancel order

### Payments
- `POST /api/payments/process` - Process payment (Stripe)
- `GET /api/payments/{id}` - Get payment details

### Admin Routes (requires admin token)
- `GET /api/admin/dashboard` - Dashboard statistics
- `GET /api/admin/products` - All products
- `POST /api/admin/products` - Create product
- `PUT /api/admin/products/{id}` - Update product
- `DELETE /api/admin/products/{id}` - Delete product
- `GET /api/admin/stock` - Stock management
- `PUT /api/admin/stock/{id}` - Update stock
- `GET /api/admin/returns` - Manage returns
- `PUT /api/admin/returns/{id}` - Update return status

---

## 🚨 Troubleshooting

### Issue: "Class 'JWTAuth' not found"
```bash
php artisan jwt:secret
```

### Issue: "MySQL connection refused"
- Ensure XAMPP MySQL service is running
- Check DB credentials in `.env`

### Issue: "Composer not recognized"
- Add Composer to PATH or use full path: `php composer.phar install`

### Issue: "npm command not found"
- Install Node.js from https://nodejs.org/

### Issue: Port 8000 already in use
```bash
php artisan serve --port=8001
```

---

## 📚 Database Schema

**Users Table**
- id, name, email, password, is_admin, created_at, updated_at

**Products Table**
- id, name, description, short_description, price, image_url, category_id, created_at, updated_at

**Orders Table**
- id, order_number, user_id, total_amount, final_amount, status, payment_status, shipping_address, billing_address, created_at, updated_at

**Payments Table**
- id, order_id, amount, payment_method, transaction_id, status, created_at, updated_at

**Stock Table**
- id, product_id, quantity, reserved_quantity, created_at, updated_at

**Reviews Table**
- id, product_id, user_id, rating, comment, created_at, updated_at

**Returns Table**
- id, order_id, user_id, reason, status, amount, created_at, updated_at

---

## 🎯 Next Steps

1. ✅ Install dependencies
2. ✅ Setup database
3. ✅ Configure JWT & Stripe
4. 🎮 Test the API endpoints
5. 🌐 Build and deploy frontend
6. 📊 Monitor analytics

---

## 📞 Support

For issues or questions:
1. Check the troubleshooting section above
2. Review Laravel documentation: https://laravel.com/docs
3. Check JWT documentation: https://jwt-auth.readthedocs.io/

---

**Happy Coding! 🚀**
