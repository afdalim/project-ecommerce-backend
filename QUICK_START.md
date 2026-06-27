# 🚀 QUICK REFERENCE - Get Started in 5 Minutes

## Step 1: Navigate to Project (30 seconds)
```bash
cd c:\xampp\htdocs\project_ecommerce
```

## Step 2: Install Dependencies (2-3 minutes)
```bash
composer install
npm install
```

## Step 3: Generate Keys (30 seconds)
```bash
php artisan key:generate
php artisan jwt:secret --force
```

## Step 4: Setup Database (1 minute)

**Option A: phpMyAdmin (Easiest)**
1. Go to http://localhost/phpmyadmin
2. Click "New"
3. Create database: `project_ecommerce`
4. Click Create

**Option B: Command Line**
```bash
mysql -u root
CREATE DATABASE project_ecommerce;
EXIT;
```

## Step 5: Configure .env (30 seconds)
```env
DB_DATABASE=project_ecommerce
DB_USERNAME=root
DB_PASSWORD=
```

## Step 6: Run Setup (1 minute)
```bash
php artisan migrate
php artisan db:seed
npm run dev
```

## Step 7: Start Server (30 seconds)
```bash
php artisan serve
```

## ✅ Done! Visit: http://localhost:8000

---

## 🔐 Test Login Credentials

### Admin
- Email: `admin@coraldaisy.com`
- Password: `Admin@123456`

### Customer
- Email: `customer@test.com`
- Password: `Password@123`

---

## 🧪 Test Payment (Stripe)

**Card:** 4242 4242 4242 4242
**Expiry:** Any future date
**CVC:** Any 3 digits

---

## 📚 Documentation Files

| File | Purpose |
|------|---------|
| INSTALLATION_GUIDE.md | Complete setup steps |
| API_DOCUMENTATION.md | All API endpoints |
| PROJECT_SUMMARY.md | What was built |
| DIRECTORY_STRUCTURE.md | File organization |
| IMPLEMENTATION_CHECKLIST.md | Verification |

---

## 🛠️ Useful Commands

```bash
# Start development server
php artisan serve

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Build frontend
npm run dev
npm run build

# Restart database (caution!)
php artisan migrate:reset
php artisan migrate
php artisan db:seed

# Check logs
tail -f storage/logs/laravel.log
```

---

## 🔑 Important Files to Edit

- `.env` - Database & API credentials
- `config/jwt.php` - JWT settings
- `config/payment.php` - Stripe keys
- `.env` line "STRIPE_PUBLIC_KEY" and "STRIPE_SECRET_KEY" - Add your Stripe test keys

---

## 📞 API Base URL
```
http://localhost:8000/api
```

---

## 🚨 Common Issues

**Composer not found?**
→ Install from https://getcomposer.org/

**PHP not found?**
→ Add XAMPP PHP to Windows PATH

**MySQL not running?**
→ Start XAMPP MySQL service

**Port 8000 already in use?**
→ Run: `php artisan serve --port=8001`

---

**Happy Coding! 🎉**
