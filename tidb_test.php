<?php
// tidb_test.php - TiDB Cloud specific connection test
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔧 TiDB Cloud Connection Test</h2>";

$host = getenv('DB_HOST') ?: 'gateway01.ap-southeast-1.prod.aws.tidbcloud.com';
$port = getenv('DB_PORT') ?: '4000';
$dbname = getenv('DB_NAME') ?: 'cinebook_mobiledb';
$username = getenv('DB_USER') ?: '2dX839N18LmFqF5.root';
$password = getenv('DB_PASSWORD') ?: '8LmH20DzY1P9SkIv';

echo "<pre>";
echo "Host: $host\n";
echo "Port: $port\n";
echo "Database: $dbname\n";
echo "Username: $username\n";
echo "Password: " . ($password ? '[SET]' : '[NOT SET]') . "\n\n";

// Check if PDO MySQL is available
if (!extension_loaded('pdo_mysql')) {
    echo "❌ PDO MySQL extension is NOT loaded!\n";
} else {
    echo "✅ PDO MySQL extension is loaded\n";
}

// Check OpenSSL
if (!extension_loaded('openssl')) {
    echo "❌ OpenSSL extension is NOT loaded!\n";
} else {
    echo "✅ OpenSSL extension is loaded\n";
    echo "OpenSSL version: " . OPENSSL_VERSION_TEXT . "\n";
}

echo "\n--- Attempting connection ---\n\n";

try {
    // Method 1: Standard connection with SSL
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
        PDO::MYSQL_ATTR_SSL_CA => null
    ];
    
    $pdo = new PDO($dsn, $username, $password, $options);
    
    echo "✅ CONNECTION SUCCESSFUL!\n\n";
    
    // Test query
    $result = $pdo->query("SELECT 1 as test")->fetch();
    echo "Test query result: " . print_r($result, true) . "\n\n";
    
    // Check tables
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables in database:\n";
    print_r($tables);
    
} catch(PDOException $e) {
    echo "❌ Connection failed:\n";
    echo "Error code: " . $e->getCode() . "\n";
    echo "Error message: " . $e->getMessage() . "\n\n";
    
    // Try alternative connection method
    echo "--- Trying alternative method ---\n\n";
    
    try {
        // Method 2: Using mysqli with SSL
        $mysqli = mysqli_init();
        $mysqli->ssl_set(null, null, null, null, null);
        $mysqli->real_connect($host, $username, $password, $dbname, $port);
        
        if ($mysqli->connect_error) {
            echo "❌ mysqli also failed: " . $mysqli->connect_error . "\n";
        } else {
            echo "✅ mysqli connection successful!\n";
            $result = $mysqli->query("SELECT 1");
            echo "Test query result: " . print_r($result->fetch_assoc(), true) . "\n";
            $mysqli->close();
        }
    } catch(Exception $e2) {
        echo "❌ mysqli failed: " . $e2->getMessage() . "\n";
    }
}

echo "</pre>";
?>
