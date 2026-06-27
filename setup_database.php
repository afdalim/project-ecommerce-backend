<?php
/**
 * Quick Setup Script for CORAL DAISY E-Commerce
 * This file creates database tables and seed data directly
 */

// Load environment variables
$env_file = __DIR__ . '/.env';
if (!file_exists($env_file)) {
    die("❌ .env file not found!");
}

// Parse .env
$env = [];
foreach (file($env_file) as $line) {
    if (strpos(trim($line), '#') === 0 || trim($line) === '') continue;
    if (strpos($line, '=') !== false) {
        list($key, $value) = explode('=', $line, 2);
        $env[trim($key)] = trim($value);
    }
}

// Database configuration
$host = $env['DB_HOST'] ?? '127.0.0.1';
$database = $env['DB_DATABASE'] ?? 'project_ecommerce';
$username = $env['DB_USERNAME'] ?? 'root';
$password = $env['DB_PASSWORD'] ?? '';

echo "===============================================\n";
echo "🚀 CORAL DAISY Quick Setup\n";
echo "===============================================\n\n";

// Connect to database
echo "Connecting to database: $database...\n";
try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connected!\n\n";
} catch (PDOException $e) {
    die("❌ Connection failed: " . $e->getMessage());
}

// Create tables
echo "Creating database tables...\n";

$tables = [
    // Users table
    "CREATE TABLE IF NOT EXISTS users (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        is_admin BOOLEAN DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",

    // Categories table
    "CREATE TABLE IF NOT EXISTS categories (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description LONGTEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",

    // Products table
    "CREATE TABLE IF NOT EXISTS products (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description LONGTEXT,
        short_description VARCHAR(255),
        price DECIMAL(10, 2) NOT NULL,
        image_url VARCHAR(255),
        category_id BIGINT UNSIGNED,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (category_id) REFERENCES categories(id)
    )",

    // Carts table
    "CREATE TABLE IF NOT EXISTS carts (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT UNSIGNED NOT NULL,
        total_price DECIMAL(10, 2) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )",

    // Cart items table
    "CREATE TABLE IF NOT EXISTS cart_items (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        cart_id BIGINT UNSIGNED NOT NULL,
        product_id BIGINT UNSIGNED NOT NULL,
        quantity INT NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (cart_id) REFERENCES carts(id),
        FOREIGN KEY (product_id) REFERENCES products(id)
    )",

    // Orders table
    "CREATE TABLE IF NOT EXISTS orders (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        order_number VARCHAR(255) UNIQUE NOT NULL,
        user_id BIGINT UNSIGNED NOT NULL,
        total_amount DECIMAL(10, 2) NOT NULL,
        final_amount DECIMAL(10, 2) NOT NULL,
        status VARCHAR(255) DEFAULT 'pending',
        payment_status VARCHAR(255) DEFAULT 'unpaid',
        shipping_address LONGTEXT,
        billing_address LONGTEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )",

    // Order items table
    "CREATE TABLE IF NOT EXISTS order_items (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        order_id BIGINT UNSIGNED NOT NULL,
        product_id BIGINT UNSIGNED NOT NULL,
        quantity INT NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (order_id) REFERENCES orders(id),
        FOREIGN KEY (product_id) REFERENCES products(id)
    )",

    // Payments table
    "CREATE TABLE IF NOT EXISTS payments (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        order_id BIGINT UNSIGNED NOT NULL,
        amount DECIMAL(10, 2) NOT NULL,
        payment_method VARCHAR(255),
        transaction_id VARCHAR(255),
        status VARCHAR(255) DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (order_id) REFERENCES orders(id)
    )",

    // Stock table
    "CREATE TABLE IF NOT EXISTS stock (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        product_id BIGINT UNSIGNED UNIQUE NOT NULL,
        quantity INT NOT NULL DEFAULT 0,
        reserved_quantity INT NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (product_id) REFERENCES products(id)
    )",

    // Reviews table
    "CREATE TABLE IF NOT EXISTS reviews (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        product_id BIGINT UNSIGNED NOT NULL,
        user_id BIGINT UNSIGNED NOT NULL,
        rating INT NOT NULL,
        comment LONGTEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (product_id) REFERENCES products(id),
        FOREIGN KEY (user_id) REFERENCES users(id)
    )",

    // Returns table
    "CREATE TABLE IF NOT EXISTS returns (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        order_id BIGINT UNSIGNED NOT NULL,
        user_id BIGINT UNSIGNED NOT NULL,
        reason VARCHAR(255),
        status VARCHAR(255) DEFAULT 'pending',
        amount DECIMAL(10, 2),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (order_id) REFERENCES orders(id),
        FOREIGN KEY (user_id) REFERENCES users(id)
    )",

    // Password resets table
    "CREATE TABLE IF NOT EXISTS password_resets (
        email VARCHAR(255) PRIMARY KEY,
        token VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )"
];

foreach ($tables as $sql) {
    try {
        $pdo->exec($sql);
    } catch (PDOException $e) {
        echo "⚠️ " . $e->getMessage() . "\n";
    }
}

echo "✅ Tables created!\n\n";

// Seed data
echo "Seeding sample data...\n";

// Create admin user
$adminPassword = password_hash('Admin@123456', PASSWORD_BCRYPT);
$pdo->exec("INSERT INTO users (name, email, password, is_admin) VALUES 
    ('Admin User', 'admin@coraldaisy.com', '$adminPassword', 1)
    ON DUPLICATE KEY UPDATE is_admin = 1");

// Create test customer
$customerPassword = password_hash('Password@123', PASSWORD_BCRYPT);
$pdo->exec("INSERT INTO users (name, email, password, is_admin) VALUES 
    ('Test Customer', 'customer@test.com', '$customerPassword', 0)
    ON DUPLICATE KEY UPDATE password = '$customerPassword'");

// Create categories
$pdo->exec("INSERT INTO categories (name, description) VALUES 
    ('Dresses', 'Beautiful dresses for every occasion'),
    ('Accessories', 'Fashion accessories and jewelry'),
    ('Shoes', 'Comfortable and stylish shoes'),
    ('Bags', 'Elegant bags and purses')
    ON DUPLICATE KEY UPDATE name = name");

// Create sample products
$products = [
    ['Coral Dress', 'A beautiful coral colored dress perfect for summer', 'Coral summer dress', 79.99, 1],
    ['Blue Blouse', 'Elegant blue blouse for office wear', 'Blue office blouse', 49.99, 1],
    ['Summer Skirt', 'Light and comfortable summer skirt', 'Yellow summer skirt', 59.99, 1],
    ['Pearl Necklace', 'Elegant pearl necklace for special occasions', 'Luxury pearl necklace', 89.99, 2],
    ['Diamond Ring', 'Gorgeous diamond ring', 'Diamond engagement ring', 299.99, 2],
    ['Gold Bracelet', 'Elegant gold bracelet', 'Gold fashion bracelet', 69.99, 2],
    ['High Heels', 'Comfortable high heels in multiple colors', 'Black high heels', 119.99, 3],
    ['Casual Sneakers', 'Comfortable casual sneakers', 'White canvas sneakers', 69.99, 3],
    ['Leather Boots', 'Stylish leather boots', 'Brown leather boots', 149.99, 3],
    ['Leather Handbag', 'Premium leather handbag', 'Brown leather bag', 199.99, 4],
    ['Crossbody Bag', 'Convenient crossbody bag', 'Black crossbody bag', 89.99, 4],
    ['Wallet', 'Elegant wallet with many compartments', 'Leather wallet', 49.99, 4],
    ['Floral Dress', 'Beautiful floral pattern dress', 'Multicolor floral dress', 69.99, 1],
    ['White Blouse', 'Classic white blouse', 'White formal blouse', 59.99, 1],
    ['Evening Gown', 'Stunning evening gown', 'Black evening gown', 299.99, 1],
];

foreach ($products as [$name, $description, $short_desc, $price, $category_id]) {
    $stmt = $pdo->prepare("INSERT INTO products (name, description, short_description, price, category_id) 
                          VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE name = name");
    $stmt->execute([$name, $description, $short_desc, $price, $category_id]);
    $productId = $pdo->lastInsertId();
    
    // Create stock for product
    $pdo->prepare("INSERT INTO stock (product_id, quantity) VALUES (?, ?) ON DUPLICATE KEY UPDATE quantity = ?")->execute([$productId, 50, 50]);
}

echo "✅ Data seeded!\n\n";

echo "===============================================\n";
echo "✅ Setup Complete!\n";
echo "===============================================\n\n";

echo "Default Credentials:\n";
echo "📧 Admin Email: admin@coraldaisy.com\n";
echo "🔑 Admin Password: Admin@123456\n\n";

echo "💻 Customer Account:\n";
echo "📧 Email: customer@test.com\n";
echo "🔑 Password: Password@123\n\n";

echo "Next steps:\n";
echo "1. Generate app key: php artisan key:generate\n";
echo "2. Generate JWT secret: php artisan jwt:secret\n";
echo "3. Build frontend: npm install && npm run dev\n";
echo "4. Start server: php artisan serve\n";
echo "5. Visit: http://localhost:8000\n\n";

$pdo = null;
?>
