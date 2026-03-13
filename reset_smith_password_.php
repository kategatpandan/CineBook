<?php
// reset_smith_password.php
require_once 'api.php';

echo "<h2>Reset Password for Smith@gmail.com</h2>";

try {
    // New password: 'password123'
    $new_password = password_hash('password123', PASSWORD_DEFAULT);
    
    // Update Smith's password
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = 'Smith@gmail.com'");
    $stmt->execute([$new_password]);
    
    if ($stmt->rowCount() > 0) {
        echo "<p style='color:green'>✅ Password reset successfully for Smith@gmail.com</p>";
        echo "<p>New password: <strong>password123</strong></p>";
    } else {
        echo "<p style='color:orange'>⚠️ User Smith@gmail.com not found</p>";
    }
    
    // Show all users
    $users = $pdo->query("SELECT id, username, points FROM users")->fetchAll(PDO::FETCH_ASSOC);
    echo "<h3>Current Users:</h3>";
    echo "<pre>";
    print_r($users);
    echo "</pre>";
    
} catch(Exception $e) {
    echo "<p style='color:red'>❌ Error: " . $e->getMessage() . "</p>";
}
?>
