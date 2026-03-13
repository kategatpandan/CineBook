<?php
// simple_test.php
$host = 'gateway01.ap-southeast-1.prod.aws.tidbcloud.com';
$port = '3306';  // Changed to 3306
$dbname = 'cinebook_mobiledb';
$username = '2dXB39N8LmFqFs.root';
$password = '8LmH20DzY1P9SkIv';

echo "<h2>Simple Connection Test</h2>";
echo "<pre>";

echo "Connecting to: $host:$port\n";
echo "Database: $dbname\n";
echo "Username: $username\n\n";

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ CONNECTED SUCCESSFULLY!\n\n";
    
    // Show tables
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables: " . implode(", ", $tables) . "\n";
    
} catch(PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage() . "\n";
}
echo "</pre>";
?>
