<?php
// tidb_config.php
class TiDBConnection {
    private static $instance = null;
    private $pdo;
    
    private function __construct() {
        $host = getenv('DB_HOST') ?: 'gateway01.ap-southeast-1.prod.aws.tidbcloud.com';
        $port = getenv('DB_PORT') ?: '3306';
        $dbname = getenv('DB_NAME') ?: 'cinebook_mobiledb';
        $username = getenv('DB_USER') ?: '2dXB39N8LmFqFs.root';
        $password = getenv('DB_PASSWORD') ?: '8LmH20DzY1P9SkIv';
        
        try {
            // Create SSL context
            $sslContext = stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ]);
            
            // Set SSL context for PDO
            $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
            
            // Important: Use these exact SSL options
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_TIMEOUT => 30,
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
                PDO::MYSQL_ATTR_SSL_KEY => null,
                PDO::MYSQL_ATTR_SSL_CERT => null,
                PDO::MYSQL_ATTR_SSL_CA => null,
                PDO::MYSQL_ATTR_SSL_CAPATH => null,
                PDO::MYSQL_ATTR_SSL_CIPHER => null,
                PDO::MYSQL_ATTR_SSL_CA => null,
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
                PDO::MYSQL_ATTR_MULTI_STATEMENTS => false
            ];
            
            $this->pdo = new PDO($dsn, $username, $password, $options);
            
            // Test connection
            $this->pdo->query("SELECT 1");
            error_log("✅ TiDB Cloud connected successfully");
            
        } catch(PDOException $e) {
            error_log("❌ TiDB Cloud connection failed: " . $e->getMessage());
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new TiDBConnection();
        }
        return self::$instance->pdo;
    }
}

// Usage: $pdo = TiDBConnection::getInstance();
?>
