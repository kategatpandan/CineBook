<?php
// api.php
header('Content-Type: application/json');
session_start();

// Use environment variables
$host = getenv('DB_HOST') ?: 'localhost';
$port = getenv('DB_PORT') ?: '3306';
$dbname = getenv('DB_NAME') ?: 'cinebook_mobiledb';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';

try {
    // For TiDB Cloud, we need to enable SSL
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    
    // Enable SSL for TiDB Cloud
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // These SSL options are required for TiDB Cloud
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
        PDO::MYSQL_ATTR_SSL_KEY => null,
        PDO::MYSQL_ATTR_SSL_CERT => null,
        PDO::MYSQL_ATTR_SSL_CA => null
    ];
    
    $pdo = new PDO($dsn, $username, $password, $options);
    
    // Test connection
    $pdo->query("SELECT 1");
    error_log("✅ Connected to TiDB Cloud");
    
    // Create tables if they don't exist
    createTables($pdo);
    
} catch(PDOException $e) {
    error_log("❌ Connection failed: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed',
        'error' => $e->getMessage()
    ]);
    exit;
}
