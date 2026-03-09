<?php
// test_login.php
session_start();

$host = 'localhost';
$dbname = 'cinebook_mobiledb';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>🔍 Login Debug Tool</h2>";
    
    // Check if users table exists
    $tables = $pdo->query("SHOW TABLES LIKE 'users'")->rowCount();
    echo "Users table exists: " . ($tables > 0 ? "✅ YES" : "❌ NO") . "<br><br>";
    
    // Get all users
    $users = $pdo->query("SELECT id, username, points, created_at FROM users")->fetchAll(PDO::FETCH_ASSOC);
    echo "📋 Users in database:<br>";
    echo "<pre>";
    print_r($users);
    echo "</pre>";
    
    // Test admin login
    $test_username = 'admin';
    $test_password = 'admin123';
    
    echo "<h3>Testing login with: username='$test_username', password='$test_password'</h3>";
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$test_username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "✅ User 'admin' found in database<br>";
        echo "Stored password hash: " . $user['password'] . "<br>";
        
        // Test password verification
        if (password_verify($test_password, $user['password'])) {
            echo "✅ Password verification SUCCESSFUL!<br>";
            echo "The password 'admin123' matches the hash!<br>";
        } else {
            echo "❌ Password verification FAILED<br>";
            echo "The password 'admin123' does NOT match the stored hash.<br>";
            echo "You need to update the password hash.<br>";
        }
    } else {
        echo "❌ User 'admin' NOT found in database<br>";
        echo "You need to create the admin user first.<br>";
    }
    
    // If verification failed, offer to fix it
    if ($user && !password_verify($test_password, $user['password'])) {
        echo "<br>---<br>";
        echo "<h3>🔧 Fix the admin password</h3>";
        $new_hash = password_hash('admin123', PASSWORD_DEFAULT);
        echo "New hash for 'admin123': " . $new_hash . "<br>";
        echo "<br>Run this SQL to fix:<br>";
        echo "<code>UPDATE users SET password = '$new_hash' WHERE username = 'admin';</code><br>";
        
        // Option to auto-fix
        echo '<form method="post">';
        echo '<input type="submit" name="fix" value="Auto-fix Admin Password" style="padding:10px;background:#ff8c42;color:white;border:none;border-radius:5px;cursor:pointer;">';
        echo '</form>';
        
        if (isset($_POST['fix'])) {
            $update = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
            $update->execute([$new_hash, 'admin']);
            echo "<p style='color:green;'>✅ Admin password updated! <a href='test_login.php'>Refresh</a></p>";
        }
    }
    
    // If user doesn't exist, offer to create it
    if (!$user) {
        echo "<br>---<br>";
        echo "<h3>🔧 Create admin user</h3>";
        $new_hash = password_hash('admin123', PASSWORD_DEFAULT);
        
        echo '<form method="post">';
        echo '<input type="submit" name="create" value="Create Admin User" style="padding:10px;background:#ff8c42;color:white;border:none;border-radius:5px;cursor:pointer;">';
        echo '</form>';
        
        if (isset($_POST['create'])) {
            $insert = $pdo->prepare("INSERT INTO users (username, password, points, total_points_earned, created_at) VALUES (?, ?, 0, 0, NOW())");
            $insert->execute(['admin', $new_hash]);
            echo "<p style='color:green;'>✅ Admin user created! <a href='test_login.php'>Refresh</a></p>";
        }
    }
    
} catch(PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage();
}
?>