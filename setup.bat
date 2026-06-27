@echo off
REM E-Commerce Platform Setup Script for Windows

echo.
echo ================================
echo E-Commerce Platform Setup
echo ================================
echo.

REM Check if composer is installed
where composer >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo Error: Composer is not installed or not in PATH
    exit /b 1
)

echo [OK] Composer found
echo.

REM Copy .env file
if not exist ".env" (
    echo Creating .env file...
    copy .env.example .env
    echo [OK] .env file created
) else (
    echo [WARNING] .env file already exists, skipping...
)

echo.
echo Installing dependencies...
composer install
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] Composer install failed
    exit /b 1
)
echo [OK] Dependencies installed

echo.
echo Generating application key...
php artisan key:generate
echo [OK] Application key generated

echo.
echo Generating JWT secret...
php artisan jwt:secret
echo [OK] JWT secret generated

echo.
echo Running migrations...
php artisan migrate
echo [OK] Migrations completed

echo.
echo Seeding database...
php artisan db:seed
echo [OK] Database seeded

echo.
echo ================================
echo [OK] Setup Complete!
echo ================================
echo.
echo Next Steps:
echo 1. Update your database credentials in .env if needed
echo 2. Configure Stripe/PayPal keys in .env
echo 3. Run: php artisan serve
echo 4. Visit: http://localhost:8000
echo.
echo Test Credentials:
echo Admin: admin@ecommerce.com / password123
echo Customer: customer@ecommerce.com / password123
echo.
pause
