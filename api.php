<?php
// api.php
header('Content-Type: application/json');
session_start();

// Use environment variables if available, otherwise fallback to localhost
$host = getenv('DB_HOST') ?: 'localhost';
$port = getenv('DB_PORT') ?: '3306';
$dbname = getenv('DB_NAME') ?: 'cinebook_mobiledb';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';

// For TiDB Cloud, we need to use SSL
try {
    // Create DSN with SSL parameters
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    
    // TiDB Cloud requires SSL with specific options
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        // SSL options for TiDB Cloud - THIS IS THE KEY!
        PDO::MYSQL_ATTR_SSL_CA => null,  // Use system CA
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false, // Disable verification for testing
        PDO::MYSQL_ATTR_SSL_CIPHER => 'AES256-SHA', // Specific cipher for TiDB
        PDO::MYSQL_ATTR_SSL_KEY => null,
        PDO::MYSQL_ATTR_SSL_CERT => null
    ];
    
    // Attempt connection
    $pdo = new PDO($dsn, $username, $password, $options);
    
    // Test connection
    $pdo->query("SELECT 1");
    error_log("✅ Database connected successfully to TiDB Cloud");
    
    // Create tables if they don't exist
    createTables($pdo);
    
} catch(PDOException $e) {
    error_log("❌ Database connection failed: " . $e->getMessage());
    
    // Return detailed error for debugging
    echo json_encode([
        'error' => 'Database connection failed',
        'message' => $e->getMessage(),
        'host' => $host,
        'dbname' => $dbname,
        'username' => $username
    ]);
    exit;
}
