@echo off
title CORAL DAISY - Development Server
cd /d C:\xampp\htdocs\project_ecommerce
echo.
echo ============================================
echo  CORAL DAISY E-Commerce Platform
echo  Development Server
echo ============================================
echo.
C:\xampp\php\php.exe artisan serve --host=0.0.0.0 --port=8000
pause
