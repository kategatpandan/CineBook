<?php
// simple_connect.php - ULTRA SIMPLE CONNECTION
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔌 Ultra Simple Connection Test</h2>";
echo "<pre>";

$host = 'gateway01.ap-southeast-1.prod.aws.tidbcloud.com';
$port = 4000;  // Using port 4000 (non-SSL)
$dbname = 'cinebook_mobiledb';
$username = '2dX839M3BLmFqF5.root';
$password = '8LmH20DzY1P9SkIv';

echo "Host: $host\n";
echo "Port: $port\n";
echo "Database: $dbname\n";
echo "Username: $username\n";
echo "Password: " . ($password ? '[SET]' : '[NOT SET]') . "\n\n";

// METHOD 1: Simplest possible connection
try {
    echo "Method 1: Basic connection (no SSL)...\n";
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Test query
    $result = $pdo->query("SELECT 1 as test")->fetch();
    echo "✅ CONNECTION SUCCESSFUL!\n";
    echo "Test result: " . print_r($result, true) . "\n";
    
} catch(PDOException $e) {
    echo "❌ Failed: " . $e->getMessage() . "\n\n";
}

// METHOD 2: With SSL disabled
try {
    echo "Method 2: With SSL disabled...\n";
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
    ];
    $pdo = new PDO($dsn, $username, $password, $options);
    
    echo "✅ CONNECTION SUCCESSFUL!\n";
    
} catch(PDOException $e) {
    echo "❌ Failed: " . $e->getMessage() . "\n";
}

echo "</pre>";
?>
