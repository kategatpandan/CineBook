<?php
// reset_password_final.php
require_once 'tidb_config.php';

echo "<h2>🔑 Reset Password</h2>";

try {
    $pdo = TiDBConnection::getInstance();
    
    // Check if user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute(['Smith@gmail.com']);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "<p>✅ Found user: Smith@gmail.com</p>";
        echo "<p>Current points: " . $user['points'] . "</p>";
        
        // Reset password to 'password123'
        $new_password = password_hash('password123', PASSWORD_DEFAULT);
        $update = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
        $update->execute([$new_password, 'Smith@gmail.com']);
        
        echo "<p style='color:green'>✅ Password reset to: <strong>password123</strong></p>";
        
    } else {
        echo "<p style='color:red'>❌ User Smith@gmail.com not found!</p>";
        
        // Create the user if not exists
        $new_password = password_hash('password123', PASSWORD_DEFAULT);
        $insert = $pdo->prepare("INSERT INTO users (username, password, points) VALUES (?, ?, 100)");
        $insert->execute(['Smith@gmail.com', $new_password]);
        
        echo "<p style='color:green'>✅ Created new user with password: password123</p>";
    }
    
    // Show all users
    $users = $pdo->query("SELECT id, username, points FROM users")->fetchAll();
    echo "<h3>Current Users:</h3>";
    echo "<pre>";
    print_r($users);
    echo "</pre>";
    
} catch(Exception $e) {
    echo "<p style='color:red'>❌ Error: " . $e->getMessage() . "</p>";
}
?>
