<?php
// diagnose.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔧 System Diagnosis</h1>";
echo "<pre>";

// PHP Version
echo "PHP Version: " . phpversion() . "\n";

// Check extensions
echo "\n--- Extensions ---\n";
$extensions = ['pdo_mysql', 'mysqli', 'openssl', 'pdo'];
foreach($extensions as $ext) {
    echo "$ext: " . (extension_loaded($ext) ? '✅' : '❌') . "\n";
}

// Check SSL
echo "\n--- SSL Info ---\n";
if (function_exists('openssl_get_cert_locations')) {
    $ssl = openssl_get_cert_locations();
    echo "Default CA path: " . ($ssl['default_cert_dir'] ?? 'unknown') . "\n";
    echo "Default CA file: " . ($ssl['default_cert_file'] ?? 'unknown') . "\n";
}

// Environment variables
echo "\n--- Environment ---\n";
$envVars = ['DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER'];
foreach($envVars as $var) {
    $value = getenv($var);
    echo "$var: " . ($value ? $value : '❌ NOT SET') . "\n";
}
echo "DB_PASSWORD: " . (getenv('DB_PASSWORD') ? '✅ SET' : '❌ NOT SET') . "\n";

// Connection test
echo "\n--- Connection Test ---\n";
try {
    require_once 'tidb_config.php';
    $pdo = TiDBConnection::getInstance();
    echo "✅ Connection successful!\n";
    
    // Test query
    $result = $pdo->query("SELECT 1 as test")->fetch();
    echo "Test query: " . print_r($result, true) . "\n";
    
} catch(Exception $e) {
    echo "❌ Connection failed: " . $e->getMessage() . "\n";
}

echo "</pre>";
?>
