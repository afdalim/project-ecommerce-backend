# 📚 API Documentation - CORAL DAISY E-Commerce

## Base URL
```
http://localhost:8000/api
```

## Authentication
All protected endpoints require JWT Bearer token:
```
Authorization: Bearer {token}
```

---

## 🔐 Authentication Endpoints

### Register New User
```
POST /auth/register
```
**Request:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "SecurePass123",
  "password_confirmation": "SecurePass123"
}
```
**Response:** `201 Created`
```json
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "token_type": "bearer",
  "expires_in": 3600,
  "user": { "id": 1, "name": "John Doe", "email": "john@example.com", "is_admin": false }
}
```

### Login User
```
POST /auth/login
```
**Request:**
```json
{
  "email": "john@example.com",
  "password": "SecurePass123"
}
```
**Response:** `200 OK`
```json
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "token_type": "bearer",
  "expires_in": 3600,
  "user": { "id": 1, "name": "John Doe", "email": "john@example.com", "is_admin": false }
}
```

### Get Current User
```
GET /auth/me
Authorization: Bearer {token}
```
**Response:** `200 OK`
```json
{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "is_admin": false,
  "created_at": "2024-01-15T10:30:00Z"
}
```

### Refresh Token
```
POST /auth/refresh
Authorization: Bearer {token}
```
**Response:** `200 OK`
```json
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "token_type": "bearer",
  "expires_in": 3600
}
```

### Logout
```
POST /auth/logout
Authorization: Bearer {token}
```
**Response:** `200 OK`
```json
{
  "message": "Successfully logged out"
}
```

---

## 📦 Product Endpoints

### List All Products
```
GET /products?page=1&per_page=12&category=1&min_price=10&max_price=100
```
**Query Parameters:**
- `page` - Page number (default: 1)
- `per_page` - Items per page (default: 12)
- `category` - Filter by category ID
- `min_price` - Minimum price filter
- `max_price` - Maximum price filter

**Response:** `200 OK`
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "name": "Coral Dress",
      "description": "Beautiful coral colored dress",
      "short_description": "Coral dress for summer",
      "price": 79.99,
      "image_url": "https://...",
      "category": { "id": 1, "name": "Dresses" },
      "average_rating": 4.5,
      "review_count": 12
    }
  ],
  "per_page": 12,
  "total": 45
}
```

### Get Product Details
```
GET /products/{id}
```
**Response:** `200 OK`
```json
{
  "id": 1,
  "name": "Coral Dress",
  "description": "Beautiful coral colored dress...",
  "price": 79.99,
  "image_url": "https://...",
  "category": { "id": 1, "name": "Dresses" },
  "stock": { "quantity": 50, "reserved_quantity": 5 },
  "reviews": [
    {
      "id": 1,
      "rating": 5,
      "comment": "Great quality!",
      "user": { "id": 2, "name": "Jane Smith" }
    }
  ]
}
```

### Search Products
```
GET /products/search/{query}
```
**Response:** `200 OK` (Same format as product list)

### List Categories
```
GET /categories
```
**Response:** `200 OK`
```json
{
  "data": [
    { "id": 1, "name": "Dresses", "description": "Women dresses" },
    { "id": 2, "name": "Accessories", "description": "Fashion accessories" }
  ]
}
```

---

## 🛒 Shopping Cart Endpoints

### Get Cart
```
GET /cart
Authorization: Bearer {token}
```
**Response:** `200 OK`
```json
{
  "id": 1,
  "user_id": 1,
  "total_price": 159.98,
  "items": [
    {
      "id": 1,
      "product": {
        "id": 1,
        "name": "Coral Dress",
        "price": 79.99,
        "image_url": "https://..."
      },
      "quantity": 2,
      "price": 159.98
    }
  ]
}
```

### Add to Cart
```
POST /cart/add
Authorization: Bearer {token}
Content-Type: application/json
```
**Request:**
```json
{
  "product_id": 1,
  "quantity": 2
}
```
**Response:** `201 Created`
```json
{
  "message": "Item added to cart",
  "cart": { ... }
}
```

### Update Cart Item Quantity
```
PUT /cart/items/{item_id}
Authorization: Bearer {token}
Content-Type: application/json
```
**Request:**
```json
{
  "quantity": 3
}
```
**Response:** `200 OK`
```json
{
  "message": "Cart item updated",
  "cart": { ... }
}
```

### Remove from Cart
```
DELETE /cart/items/{item_id}
Authorization: Bearer {token}
```
**Response:** `200 OK`
```json
{
  "message": "Item removed from cart",
  "cart": { ... }
}
```

### Clear Cart
```
DELETE /cart
Authorization: Bearer {token}
```
**Response:** `200 OK`
```json
{
  "message": "Cart cleared"
}
```

---

## 📋 Order Endpoints

### List User Orders
```
GET /orders
Authorization: Bearer {token}
```
**Response:** `200 OK`
```json
{
  "data": [
    {
      "id": 1,
      "order_number": "ORD-2024-001",
      "total_amount": 159.98,
      "final_amount": 159.98,
      "status": "completed",
      "payment_status": "paid",
      "created_at": "2024-01-15T10:30:00Z",
      "items": [
        {
          "id": 1,
          "product": { "id": 1, "name": "Coral Dress" },
          "quantity": 2,
          "price": 79.99
        }
      ]
    }
  ]
}
```

### Create Order
```
POST /orders
Authorization: Bearer {token}
Content-Type: application/json
```
**Request:**
```json
{
  "shipping_address": {
    "raw": "123 Main Street, City, State 12345"
  },
  "billing_address": {
    "raw": "123 Main Street, City, State 12345"
  },
  "shipping_method": "standard"
}
```
**Response:** `201 Created`
```json
{
  "order": {
    "id": 1,
    "order_number": "ORD-2024-001",
    "total_amount": 159.98,
    "status": "pending",
    "payment_status": "unpaid"
  },
  "message": "Order created successfully"
}
```

### Get Order Details
```
GET /orders/{id}
Authorization: Bearer {token}
```
**Response:** `200 OK` (Same format as order in list)

### Cancel Order
```
PUT /orders/{id}/cancel
Authorization: Bearer {token}
```
**Response:** `200 OK`
```json
{
  "message": "Order cancelled",
  "order": { ... }
}
```

---

## 💳 Payment Endpoints

### Process Payment (Stripe)
```
POST /payments/process
Authorization: Bearer {token}
Content-Type: application/json
```
**Request:**
```json
{
  "order_id": 1,
  "payment_method": "stripe",
  "token": "tok_visa"
}
```
**Response:** `200 OK`
```json
{
  "success": true,
  "message": "Payment processed successfully",
  "payment": {
    "id": 1,
    "order_id": 1,
    "amount": 159.98,
    "payment_method": "stripe",
    "status": "completed",
    "transaction_id": "ch_1234567890"
  }
}
```

### Get Payment Details
```
GET /payments/{id}
Authorization: Bearer {token}
```
**Response:** `200 OK`
```json
{
  "id": 1,
  "order_id": 1,
  "amount": 159.98,
  "payment_method": "stripe",
  "status": "completed",
  "transaction_id": "ch_1234567890",
  "created_at": "2024-01-15T10:35:00Z"
}
```

---

## ⭐ Review Endpoints

### Add Product Review
```
POST /reviews
Authorization: Bearer {token}
Content-Type: application/json
```
**Request:**
```json
{
  "product_id": 1,
  "rating": 5,
  "comment": "Great product!"
}
```
**Response:** `201 Created`
```json
{
  "id": 1,
  "product_id": 1,
  "user_id": 1,
  "rating": 5,
  "comment": "Great product!",
  "created_at": "2024-01-15T10:40:00Z"
}
```

### Get Product Reviews
```
GET /products/{product_id}/reviews
```
**Response:** `200 OK`
```json
{
  "data": [
    {
      "id": 1,
      "rating": 5,
      "comment": "Great product!",
      "user": { "id": 1, "name": "John Doe" },
      "created_at": "2024-01-15T10:40:00Z"
    }
  ]
}
```

---

## 🔄 Return Endpoints

### Request Return
```
POST /returns
Authorization: Bearer {token}
Content-Type: application/json
```
**Request:**
```json
{
  "order_id": 1,
  "reason": "Product arrived damaged"
}
```
**Response:** `201 Created`
```json
{
  "id": 1,
  "order_id": 1,
  "reason": "Product arrived damaged",
  "status": "pending",
  "created_at": "2024-01-15T10:45:00Z"
}
```

### Get User Returns
```
GET /returns
Authorization: Bearer {token}
```
**Response:** `200 OK`
```json
{
  "data": [
    {
      "id": 1,
      "order_id": 1,
      "reason": "Product arrived damaged",
      "status": "pending",
      "amount": 159.98,
      "created_at": "2024-01-15T10:45:00Z"
    }
  ]
}
```

---

## 👨‍💼 Admin Endpoints

### Admin Dashboard
```
GET /admin/dashboard
Authorization: Bearer {admin_token}
```
**Response:** `200 OK`
```json
{
  "stats": {
    "total_orders": 125,
    "total_revenue": 15000.50,
    "total_customers": 89,
    "low_stock_count": 3
  },
  "recent_orders": [
    {
      "id": 1,
      "order_number": "ORD-2024-001",
      "final_amount": 159.98,
      "status": "completed"
    }
  ]
}
```

### List All Products (Admin)
```
GET /admin/products
Authorization: Bearer {admin_token}
```
**Response:** `200 OK` (Same format as public product list)

### Create Product
```
POST /admin/products
Authorization: Bearer {admin_token}
Content-Type: application/json
```
**Request:**
```json
{
  "name": "New Product",
  "description": "Product description",
  "short_description": "Short desc",
  "price": 99.99,
  "category_id": 1,
  "image_url": "https://...",
  "quantity": 100
}
```
**Response:** `201 Created`
```json
{
  "id": 5,
  "name": "New Product",
  "price": 99.99,
  "message": "Product created successfully"
}
```

### Update Product
```
PUT /admin/products/{id}
Authorization: Bearer {admin_token}
Content-Type: application/json
```
**Request:** (Same fields as create)

**Response:** `200 OK`
```json
{
  "message": "Product updated successfully",
  "product": { ... }
}
```

### Delete Product
```
DELETE /admin/products/{id}
Authorization: Bearer {admin_token}
```
**Response:** `200 OK`
```json
{
  "message": "Product deleted successfully"
}
```

### Manage Stock
```
PUT /admin/stock/{product_id}
Authorization: Bearer {admin_token}
Content-Type: application/json
```
**Request:**
```json
{
  "quantity": 150
}
```
**Response:** `200 OK`
```json
{
  "message": "Stock updated",
  "stock": { "product_id": 1, "quantity": 150 }
}
```

### Manage Returns (Admin)
```
GET /admin/returns
Authorization: Bearer {admin_token}
```
**Response:** `200 OK` (List of all returns)

### Update Return Status
```
PUT /admin/returns/{id}
Authorization: Bearer {admin_token}
Content-Type: application/json
```
**Request:**
```json
{
  "status": "approved"
}
```
**Response:** `200 OK`
```json
{
  "message": "Return status updated",
  "return": { ... }
}
```

---

## ❌ Error Responses

### Validation Error (400)
```json
{
  "message": "Validation error",
  "errors": {
    "email": ["The email field is required"]
  }
}
```

### Unauthorized (401)
```json
{
  "message": "Unauthorized"
}
```

### Forbidden (403)
```json
{
  "message": "This action is unauthorized"
}
```

### Not Found (404)
```json
{
  "message": "Resource not found"
}
```

### Server Error (500)
```json
{
  "message": "Internal server error"
}
```

---

## 🧪 Test Stripe Cards

| Card Number | Expiry | CVC | Result |
|---|---|---|---|
| 4242 4242 4242 4242 | Any future | Any 3 digits | Success |
| 4000 0000 0000 0002 | Any future | Any 3 digits | Declined |
| 5555 5555 5555 4444 | Any future | Any 3 digits | MasterCard |

---

## 📝 Rate Limiting

Currently no rate limiting. Consider adding for production:
- Authentication: 10 requests per minute
- API endpoints: 100 requests per minute

---

## 🔒 Security Notes

1. Always use HTTPS in production
2. Store JWT tokens securely in localStorage or httpOnly cookies
3. Never expose API keys in client-side code
4. Use environment variables for sensitive data
5. Implement CORS properly for production domains

---

**Documentation Last Updated:** May 28, 2024
**API Version:** 1.0.0
