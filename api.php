<?php
// api.php - ULTRA SIMPLE VERSION
header('Content-Type: application/json');
session_start();

// Hardcode credentials for testing (REMOVE after success)
$host = 'gateway01.ap-southeast-1.prod.aws.tidbcloud.com';
$port = 4000;
$dbname = 'cinebook_mobiledb';
$username = '2dX839M3BLmFqF5.root';
$password = '8LmH20DzY1P9SkIv';

try {
    // SUPER SIMPLE - no options at all
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Test connection
    $pdo->query("SELECT 1");
    
    // Return success for testing
    if ($_GET['action'] === 'test') {
        echo json_encode(['success' => true, 'message' => 'Connected!']);
        exit;
    }
    
} catch(PDOException $e) {
    error_log("Connection failed: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Connection failed: ' . $e->getMessage()
    ]);
    exit;
}

// Simple login handler
if ($_GET['action'] === 'login') {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['points'] = $user['points'];
            
            echo json_encode([
                'success' => true,
                'user_id' => $user['id'],
                'username' => $user['username'],
                'points' => $user['points']
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid username or password'
            ]);
        }
    } catch(Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
    exit;
}

echo json_encode(['error' => 'Invalid action']);
?>
