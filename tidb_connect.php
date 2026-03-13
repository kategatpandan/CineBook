<?php
// tidb_connect.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔧 TiDB Cloud Connection Test</h2>";
echo "<pre>";

$host = 'gateway01.ap-southeast-1.prod.aws.tidbcloud.com';
$port = 3306;
$dbname = 'cinebook_mobiledb';
$username = '2dXB39N8LmFqFs.root';
$password = '8LmH20DzY1P9SkIv';

echo "Host: $host\n";
echo "Port: $port\n";
echo "Database: $dbname\n";
echo "Username: $username\n\n";

// Method 1: PDO with SSL
try {
    echo "Method 1: PDO with SSL enabled...\n";
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
    ];
    
    $pdo = new PDO($dsn, $username, $password, $options);
    $pdo->query("SELECT 1");
    echo "✅ CONNECTION SUCCESSFUL!\n\n";
    
    // Show tables
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables in database:\n";
    print_r($tables);
    
    // Show users
    $users = $pdo->query("SELECT id, username, points FROM users")->fetchAll(PDO::FETCH_ASSOC);
    echo "\nUsers in database:\n";
    print_r($users);
    
} catch(PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage() . "\n\n";
}

// Method 2: MySQLi with SSL
try {
    echo "Method 2: MySQLi with SSL...\n";
    $mysqli = mysqli_init();
    $mysqli->options(MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false);
    $mysqli->real_connect($host, $username, $password, $dbname, $port);
    
    if ($mysqli->connect_error) {
        echo "❌ MySQLi failed: " . $mysqli->connect_error . "\n";
    } else {
        echo "✅ MySQLi connection successful!\n";
        $result = $mysqli->query("SELECT COUNT(*) as count FROM users");
        $row = $result->fetch_assoc();
        echo "Total users: " . $row['count'] . "\n";
        $mysqli->close();
    }
} catch(Exception $e) {
    echo "❌ MySQLi failed: " . $e->getMessage() . "\n";
}

echo "</pre>";
?>
