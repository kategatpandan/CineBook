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
    // Very simple connection string
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    
    // Minimal options
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];
    
    // Try to connect
    $pdo = new PDO($dsn, $username, $password, $options);
    
    // Test connection
    $pdo->query("SELECT 1");
    
    // Create tables
    createTables($pdo);
    
} catch(PDOException $e) {
    error_log("Connection failed: " . $e->getMessage());
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}
