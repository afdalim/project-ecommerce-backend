#!/bin/bash

# E-Commerce Platform Setup Script

echo "================================"
echo "E-Commerce Platform Setup"
echo "================================"
echo ""

# Check if composer is installed
if ! command -v composer &> /dev/null; then
    echo "❌ Composer is not installed. Please install Composer first."
    exit 1
fi

echo "✅ Composer found"
echo ""

# Copy .env file
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
    echo "✅ .env file created"
else
    echo "⚠️  .env file already exists, skipping..."
fi

echo ""
echo "📦 Installing dependencies..."
composer install
echo "✅ Dependencies installed"

echo ""
echo "🔑 Generating application key..."
php artisan key:generate
echo "✅ Application key generated"

echo ""
echo "🔐 Generating JWT secret..."
php artisan jwt:secret
echo "✅ JWT secret generated"

echo ""
echo "📊 Running migrations..."
php artisan migrate
echo "✅ Migrations completed"

echo ""
echo "🌱 Seeding database..."
php artisan db:seed
echo "✅ Database seeded"

echo ""
echo "================================"
echo "✅ Setup Complete!"
echo "================================"
echo ""
echo "📌 Next Steps:"
echo "1. Update your database credentials in .env if needed"
echo "2. Configure Stripe/PayPal keys in .env"
echo "3. Run: php artisan serve"
echo "4. Visit: http://localhost:8000"
echo ""
echo "🔐 Test Credentials:"
echo "Admin: admin@ecommerce.com / password123"
echo "Customer: customer@ecommerce.com / password123"
echo ""
