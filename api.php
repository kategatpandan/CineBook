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

try {
    // Create DSN
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    
    // For TiDB Cloud (requires SSL)
    if ($host !== 'localhost' && strpos($host, 'tidbcloud.com') !== false) {
        // TiDB Cloud specific options
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            // SSL options for TiDB Cloud
            PDO::MYSQL_ATTR_SSL_CA => null,  // Let the system use default CA
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
        ];
    } else {
        // Local development - no SSL
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
    }
    
    $pdo = new PDO($dsn, $username, $password, $options);
    
    // Create tables if they don't exist
    createTables($pdo);
    
} catch(PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

function createTables($pdo) {
    // Create users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        points INT DEFAULT 0,
        total_points_earned INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_username (username)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    // Create movies table
    $pdo->exec("CREATE TABLE IF NOT EXISTS movies (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        genre VARCHAR(100),
        duration VARCHAR(50),
        price DECIMAL(10,2) DEFAULT 350.00,
        cast TEXT,
        rating VARCHAR(10),
        release_date DATE,
        trailer_url VARCHAR(255),
        poster VARCHAR(500),
        is_coming_soon TINYINT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_title (title),
        INDEX idx_is_coming_soon (is_coming_soon)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    // Create bookings table
    $pdo->exec("CREATE TABLE IF NOT EXISTS bookings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        booking_reference VARCHAR(50) UNIQUE NOT NULL,
        user_id INT,
        movie_id INT,
        movie_title VARCHAR(100),
        show_date DATE NOT NULL,
        show_time VARCHAR(20) NOT NULL,
        seats TEXT NOT NULL,
        total_price DECIMAL(10,2) NOT NULL,
        points_earned INT DEFAULT 0,
        booking_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE SET NULL,
        INDEX idx_user_id (user_id),
        INDEX idx_movie_id (movie_id),
        INDEX idx_booking_reference (booking_reference)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    // Create promos table
    $pdo->exec("CREATE TABLE IF NOT EXISTS promos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        points_required INT NOT NULL,
        discount_amount DECIMAL(10,2),
        discount_percentage DECIMAL(5,2),
        icon VARCHAR(50) DEFAULT 'fa-gift',
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_points_required (points_required)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    // Create redeemed_promos table
    $pdo->exec("CREATE TABLE IF NOT EXISTS redeemed_promos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        promo_id INT NOT NULL,
        points_spent INT NOT NULL,
        ticket_data TEXT,
        redeemed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (promo_id) REFERENCES promos(id) ON DELETE CASCADE,
        INDEX idx_user_id (user_id),
        INDEX idx_promo_id (promo_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    // Create seat_config table
    $pdo->exec("CREATE TABLE IF NOT EXISTS seat_config (
        id INT AUTO_INCREMENT PRIMARY KEY,
        movie_key VARCHAR(255) NOT NULL,
        seat_id VARCHAR(10) NOT NULL,
        status VARCHAR(20) DEFAULT 'available',
        booked_by INT DEFAULT NULL,
        booking_reference VARCHAR(50) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY unique_seat (movie_key, seat_id),
        INDEX idx_movie_key (movie_key),
        INDEX idx_booked_by (booked_by),
        INDEX idx_status (status)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    // Insert default admin if not exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = 'admin'");
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
        $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
        $pdo->prepare("INSERT INTO users (username, password, points, total_points_earned) VALUES ('admin', ?, 0, 0)")->execute([$admin_password]);
    }
    
    // Insert sample promos if none exist
    $stmt = $pdo->query("SELECT COUNT(*) FROM promos");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO promos (title, description, points_required, discount_amount, discount_percentage, icon, is_active) VALUES
            ('Free Popcorn', 'Get a free regular popcorn with any ticket purchase', 50, 150.00, NULL, 'fa-birthday-cake', TRUE),
            ('Combo Meal', 'Free drink and popcorn combo', 80, 250.00, NULL, 'fa-utensils', TRUE),
            ('20% Discount', 'Get 20% off on your next ticket purchase', 100, NULL, 20.00, 'fa-percent', TRUE),
            ('Free Ticket', 'Redeem for one free movie ticket', 200, 350.00, NULL, 'fa-ticket-alt', TRUE)");
    }
}

$action = $_GET['action'] ?? '';

switch($action) {
    
    // ===== USER SIDE APIS =====
    
    case 'get_movies':
        try {
            $stmt = $pdo->query("SELECT id, title, description, genre, duration, price, cast, rating, release_date, poster, trailer_url, is_coming_soon FROM movies ORDER BY is_coming_soon ASC, id DESC");
            $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Make sure all fields are included
            foreach ($movies as &$movie) {
                if (!isset($movie['cast']) || $movie['cast'] === null) {
                    $movie['cast'] = '';
                }
                if (!isset($movie['release_date']) || $movie['release_date'] === null) {
                    $movie['release_date'] = '';
                }
                if (!isset($movie['trailer_url']) || $movie['trailer_url'] === null) {
                    $movie['trailer_url'] = '';
                }
                if (!isset($movie['poster']) || $movie['poster'] === null) {
                    $movie['poster'] = '';
                }
            }
            
            echo json_encode($movies);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;
        
    case 'get_promos':
        try {
            $stmt = $pdo->query("SELECT * FROM promos WHERE is_active = 1 ORDER BY points_required ASC");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch(Exception $e) {
            echo json_encode([]);
        }
        break;
        
    case 'get_bookings':
        $user_id = $_GET['user_id'] ?? 0;
        if (!$user_id) {
            echo json_encode([]);
            break;
        }
        
        try {
            $stmt = $pdo->prepare("
                SELECT b.*, m.title as movie_name 
                FROM bookings b 
                LEFT JOIN movies m ON b.movie_id = m.id 
                WHERE b.user_id = ? 
                ORDER BY b.booking_time DESC
            ");
            $stmt->execute([$user_id]);
            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Format for display - add 'movie' and 'total' fields for compatibility
            foreach ($bookings as &$booking) {
                $booking['movie'] = $booking['movie_name'] ?? $booking['movie_title'] ?? 'Unknown Movie';
                $booking['total'] = floatval($booking['total_price'] ?? 0);
                // Ensure all fields are properly typed
                $booking['points_earned'] = intval($booking['points_earned'] ?? 0);
                $booking['booking_id'] = $booking['id'];
            }
            
            echo json_encode($bookings);
        } catch(Exception $e) {
            error_log('Error getting bookings: ' . $e->getMessage());
            echo json_encode([]);
        }
        break;
        
    case 'get_user_points':
        $user_id = $_GET['user_id'] ?? 0;
        if (!$user_id) {
            echo json_encode(['points' => 0, 'error' => 'User ID required']);
            break;
        }
        
        try {
            $stmt = $pdo->prepare("SELECT points, total_points_earned FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                echo json_encode([
                    'points' => (int)$user['points'],
                    'total_points_earned' => (int)$user['total_points_earned']
                ]);
            } else {
                echo json_encode(['points' => 0, 'total_points_earned' => 0]);
            }
        } catch(Exception $e) {
            error_log('Error getting user points: ' . $e->getMessage());
            echo json_encode(['points' => 0, 'error' => $e->getMessage()]);
        }
        break;
        
    case 'get_redeemed':
        $user_id = $_GET['user_id'] ?? 0;
        if (!$user_id) {
            echo json_encode([]);
            break;
        }
        
        try {
            $stmt = $pdo->prepare("
                SELECT r.*, p.title as promo_title, p.description as promo_description, 
                       p.points_required, p.discount_amount, p.discount_percentage
                FROM redeemed_promos r 
                JOIN promos p ON r.promo_id = p.id 
                WHERE r.user_id = ? 
                ORDER BY r.redeemed_at DESC
            ");
            $stmt->execute([$user_id]);
            $redeemed = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Parse ticket_data if it exists
            foreach ($redeemed as &$item) {
                if (isset($item['ticket_data']) && $item['ticket_data']) {
                    $item['ticket_data'] = json_decode($item['ticket_data'], true);
                }
            }
            
            echo json_encode($redeemed);
        } catch(Exception $e) {
            error_log('Error getting redeemed promos: ' . $e->getMessage());
            echo json_encode([]);
        }
        break;
        
    case 'get_occupied_seats':
        $movie = $_GET['movie'] ?? '';
        $date = $_GET['date'] ?? '';
        $time = $_GET['time'] ?? '';
        $key = $movie . '|' . $date . '|' . $time;
        
        try {
            // Get detailed seat information from bookings
            $stmt = $pdo->prepare("
                SELECT seats, booking_reference, user_id 
                FROM bookings 
                WHERE movie_title = ? AND show_date = ? AND show_time = ?
            ");
            $stmt->execute([$movie, $date, $time]);
            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $occupiedSeats = [];
            $seatDetails = [];
            
            foreach($bookings as $booking) {
                $seats = explode(',', $booking['seats']);
                foreach($seats as $seat) {
                    $seat = trim($seat);
                    $occupiedSeats[] = $seat;
                    $seatDetails[$seat] = [
                        'status' => 'booked',
                        'booking_reference' => $booking['booking_reference'],
                        'user_id' => $booking['user_id']
                    ];
                }
            }
            
            // Get from seat_config (for blocked seats)
            $stmt = $pdo->prepare("SELECT seat_id, status FROM seat_config WHERE movie_key = ? AND status IN ('booked', 'blocked')");
            $stmt->execute([$key]);
            $configSeats = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach($configSeats as $config) {
                if (!in_array($config['seat_id'], $occupiedSeats)) {
                    $occupiedSeats[] = $config['seat_id'];
                    $seatDetails[$config['seat_id']] = [
                        'status' => $config['status'],
                        'booking_reference' => null,
                        'user_id' => null
                    ];
                }
            }
            
            // Return both the list and details
            echo json_encode([
                'occupied_seats' => array_values($occupiedSeats),
                'seat_details' => $seatDetails
            ]);
        } catch(Exception $e) {
            error_log('Error getting occupied seats: ' . $e->getMessage());
            echo json_encode(['occupied_seats' => [], 'seat_details' => []]);
        }
        break;
        
    case 'get_show_seats':
        $movie = $_GET['movie'] ?? '';
        $date = $_GET['date'] ?? '';
        $time = $_GET['time'] ?? '';
        
        if (!$movie || !$date || !$time) {
            echo json_encode(['success' => false, 'message' => 'Missing parameters']);
            break;
        }
        
        try {
            // Get all bookings for this show
            $stmt = $pdo->prepare("
                SELECT b.*, u.username 
                FROM bookings b 
                LEFT JOIN users u ON b.user_id = u.id 
                WHERE b.movie_title = ? AND b.show_date = ? AND b.show_time = ?
                ORDER BY b.seats ASC
            ");
            $stmt->execute([$movie, $date, $time]);
            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get blocked seats from seat_config
            $key = $movie . '|' . $date . '|' . $time;
            $stmt = $pdo->prepare("SELECT sc.*, u.username as blocked_by_username 
                                   FROM seat_config sc 
                                   LEFT JOIN users u ON sc.booked_by = u.id 
                                   WHERE sc.movie_key = ? AND sc.status IN ('blocked', 'booked')");
            $stmt->execute([$key]);
            $configSeats = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Compile seat information
            $seatInfo = [];
            
            foreach($bookings as $booking) {
                $seats = explode(',', $booking['seats']);
                foreach($seats as $seat) {
                    $seat = trim($seat);
                    $seatInfo[$seat] = [
                        'status' => 'booked',
                        'booking_reference' => $booking['booking_reference'],
                        'user_id' => $booking['user_id'],
                        'username' => $booking['username'] ?? 'Unknown',
                        'total_price' => $booking['total_price'],
                        'points_earned' => $booking['points_earned'],
                        'booking_time' => $booking['booking_time']
                    ];
                }
            }
            
            foreach($configSeats as $config) {
                if (!isset($seatInfo[$config['seat_id']])) {
                    $seatInfo[$config['seat_id']] = [
                        'status' => $config['status'],
                        'booking_reference' => $config['booking_reference'],
                        'user_id' => $config['booked_by'],
                        'username' => $config['blocked_by_username'] ?? 'System'
                    ];
                } else {
                    // Update existing seat info with config data if needed
                    $seatInfo[$config['seat_id']]['config_status'] = $config['status'];
                }
            }
            
            echo json_encode([
                'success' => true,
                'movie' => $movie,
                'date' => $date,
                'time' => $time,
                'total_bookings' => count($bookings),
                'total_seats' => count($seatInfo),
                'seat_info' => $seatInfo,
                'bookings' => $bookings,
                'config_seats' => $configSeats
            ]);
            
        } catch(Exception $e) {
            error_log('Error getting show seats: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    // ===== AUTHENTICATION APIS =====
    
    case 'signup':
        $data = json_decode(file_get_contents('php://input'), true);
        $username = $data['username'];
        $password = $data['password'];
        
        try {
            // Check if username exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            
            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => false, 'message' => 'Username already exists']);
                break;
            }
            
            // Hash password and insert - set points to 0 for new users
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, points, total_points_earned) VALUES (?, ?, 0, 0)");
            $stmt->execute([$username, $hashed_password]);
            
            echo json_encode(['success' => true, 'message' => 'Account created successfully']);
            
        } catch(Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
        break;
        
    case 'login':
        $data = json_decode(file_get_contents('php://input'), true);
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';
        
        if (empty($username) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Username and password required']);
            break;
        }
        
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['password'])) {
                // Set session
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
                echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
            }
        } catch(Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
        break;
        
    case 'logout':
        session_destroy();
        echo json_encode(['success' => true]);
        break;
        
    // ===== SAVE OPERATIONS =====
    case 'save_booking':
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Log received data for debugging
        error_log('save_booking received: ' . json_encode($data));
        
        // Validate required fields
        if (!isset($data['user_id']) || !$data['user_id']) {
            echo json_encode(['success' => false, 'message' => 'User ID is required']);
            break;
        }
        
        if (!isset($data['booking_reference']) || !$data['booking_reference']) {
            echo json_encode(['success' => false, 'message' => 'Booking reference is required']);
            break;
        }
        
        try {
            // Start transaction
            $pdo->beginTransaction();
            
            // Check if booking already exists
            $checkStmt = $pdo->prepare("SELECT id FROM bookings WHERE booking_reference = ?");
            $checkStmt->execute([$data['booking_reference']]);
            
            if ($checkStmt->rowCount() > 0) {
                $pdo->rollBack();
                echo json_encode(['success' => true, 'message' => 'Booking already exists']);
                break;
            }
            
            // Get movie_id if available
            $movie_id = null;
            if (isset($data['movie_id']) && $data['movie_id']) {
                $movie_id = $data['movie_id'];
            } else {
                // Try to find movie_id by title
                $movieTitle = $data['movie_title'] ?? $data['movie'] ?? '';
                if ($movieTitle) {
                    $movieStmt = $pdo->prepare("SELECT id FROM movies WHERE title = ?");
                    $movieStmt->execute([$movieTitle]);
                    $movie = $movieStmt->fetch(PDO::FETCH_ASSOC);
                    $movie_id = $movie ? $movie['id'] : null;
                }
            }
            
            // Ensure numeric values are properly typed
            $total_price = floatval($data['total_price'] ?? $data['total'] ?? 0);
            $points_earned = intval($data['points_earned'] ?? 0);
            
            // Insert the booking
            $stmt = $pdo->prepare("INSERT INTO bookings 
                (booking_reference, user_id, movie_id, movie_title, show_date, show_time, seats, total_price, points_earned, booking_time) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $result = $stmt->execute([
                $data['booking_reference'],
                $data['user_id'],
                $movie_id,
                $data['movie_title'] ?? $data['movie'] ?? 'Unknown Movie',
                $data['show_date'],
                $data['show_time'],
                $data['seats'],
                $total_price,
                $points_earned,
                $data['booking_time']
            ]);
            
            if ($result) {
                // Get current user points before update
                $beforeStmt = $pdo->prepare("SELECT points, total_points_earned FROM users WHERE id = ? FOR UPDATE");
                $beforeStmt->execute([$data['user_id']]);
                $before = $beforeStmt->fetch(PDO::FETCH_ASSOC);
                
                error_log("User {$data['user_id']} points before: {$before['points']}, adding: {$points_earned}");
                
                // Update user points - ADD points for booking
                $updateStmt = $pdo->prepare("UPDATE users SET points = points + ?, total_points_earned = total_points_earned + ? WHERE id = ?");
                $updateStmt->execute([$points_earned, $points_earned, $data['user_id']]);
                
                // Get updated user points
                $afterStmt = $pdo->prepare("SELECT points FROM users WHERE id = ?");
                $afterStmt->execute([$data['user_id']]);
                $after = $afterStmt->fetch(PDO::FETCH_ASSOC);
                
                error_log("User {$data['user_id']} points after: {$after['points']}");
                
                // Get movie title for seat key
                $movieTitle = $data['movie_title'] ?? $data['movie'] ?? 'Unknown Movie';
                
                // Save occupied seats with user info
                $key = $movieTitle . '|' . $data['show_date'] . '|' . $data['show_time'];
                $seats = explode(',', $data['seats']);
                
                // Make sure seat_config table exists and update with user info
                try {
                    $seatStmt = $pdo->prepare("INSERT INTO seat_config (movie_key, seat_id, status, booked_by, booking_reference) 
                                               VALUES (?, ?, 'booked', ?, ?)
                                               ON DUPLICATE KEY UPDATE 
                                               status = 'booked', 
                                               booked_by = VALUES(booked_by),
                                               booking_reference = VALUES(booking_reference),
                                               updated_at = CURRENT_TIMESTAMP");
                    foreach($seats as $seat) {
                        $seatStmt->execute([$key, trim($seat), $data['user_id'], $data['booking_reference']]);
                    }
                } catch(Exception $e) {
                    error_log('Error saving seat config: ' . $e->getMessage());
                    // Continue even if seat config fails - it's not critical
                }
                
                // Commit transaction
                $pdo->commit();
                
                echo json_encode([
                    'success' => true, 
                    'message' => 'Booking saved successfully', 
                    'booking_id' => $pdo->lastInsertId(),
                    'new_points' => $after['points']
                ]);
            } else {
                $pdo->rollBack();
                echo json_encode(['success' => false, 'message' => 'Failed to save booking']);
            }
        } catch(Exception $e) {
            $pdo->rollBack();
            error_log('Error saving booking: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
        
    case 'redeem_promo':
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Log received data for debugging
        error_log('redeem_promo received: ' . json_encode($data));
        
        // Validate required fields
        if (!isset($data['user_id']) || !$data['user_id']) {
            echo json_encode(['success' => false, 'message' => 'User ID is required']);
            break;
        }
        
        if (!isset($data['promo_id']) || !$data['promo_id']) {
            echo json_encode(['success' => false, 'message' => 'Promo ID is required']);
            break;
        }
        
        if (!isset($data['points_spent']) || !$data['points_spent']) {
            echo json_encode(['success' => false, 'message' => 'Points spent is required']);
            break;
        }
        
        try {
            // Start transaction
            $pdo->beginTransaction();
            
            // Check if user has enough points
            $userStmt = $pdo->prepare("SELECT points FROM users WHERE id = ? FOR UPDATE");
            $userStmt->execute([$data['user_id']]);
            $user = $userStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                $pdo->rollBack();
                echo json_encode(['success' => false, 'message' => 'User not found']);
                break;
            }
            
            if ($user['points'] < $data['points_spent']) {
                $pdo->rollBack();
                echo json_encode(['success' => false, 'message' => 'Insufficient points']);
                break;
            }
            
            // Check if promo exists and is active
            $promoStmt = $pdo->prepare("SELECT * FROM promos WHERE id = ? AND is_active = 1");
            $promoStmt->execute([$data['promo_id']]);
            $promo = $promoStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$promo) {
                $pdo->rollBack();
                echo json_encode(['success' => false, 'message' => 'Promo not found or inactive']);
                break;
            }
            
            // Check if promo points required match
            if ($promo['points_required'] != $data['points_spent']) {
                $pdo->rollBack();
                echo json_encode(['success' => false, 'message' => 'Points required mismatch']);
                break;
            }
            
            // Check if user already redeemed this promo (optional - remove if you allow multiple redemptions)
            $checkRedeemedStmt = $pdo->prepare("SELECT id FROM redeemed_promos WHERE user_id = ? AND promo_id = ?");
            $checkRedeemedStmt->execute([$data['user_id'], $data['promo_id']]);
            if ($checkRedeemedStmt->rowCount() > 0) {
                $pdo->rollBack();
                echo json_encode(['success' => false, 'message' => 'You have already redeemed this promo']);
                break;
            }
            
            // Insert redeemed promo record
            $stmt = $pdo->prepare("INSERT INTO redeemed_promos (user_id, promo_id, points_spent, ticket_data, redeemed_at) VALUES (?, ?, ?, ?, NOW())");
            $ticketData = isset($data['ticket_data']) ? json_encode($data['ticket_data']) : null;
            
            $stmt->execute([
                $data['user_id'],
                $data['promo_id'],
                $data['points_spent'],
                $ticketData
            ]);
            
            $redeemedId = $pdo->lastInsertId();
            
            // Deduct points from user
            $updateStmt = $pdo->prepare("UPDATE users SET points = points - ? WHERE id = ?");
            $updateStmt->execute([$data['points_spent'], $data['user_id']]);
            
            // Get updated user points
            $newPointsStmt = $pdo->prepare("SELECT points FROM users WHERE id = ?");
            $newPointsStmt->execute([$data['user_id']]);
            $newPoints = $newPointsStmt->fetch(PDO::FETCH_ASSOC);
            
            // Commit transaction
            $pdo->commit();
            
            echo json_encode([
                'success' => true, 
                'message' => 'Promo redeemed successfully',
                'new_points' => $newPoints['points'],
                'redeemed_id' => $redeemedId
            ]);
            
        } catch(Exception $e) {
            // Rollback transaction on error
            $pdo->rollBack();
            error_log('Error redeeming promo: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
        break;
        
    // ===== ADMIN APIS =====
    case 'admin_get_all':
        // Check if admin
        if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }
        
        try {
            // Get all movies
            $movies = $pdo->query("SELECT * FROM movies ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
            
            // Get all users with accurate points info
            $users = $pdo->query("
                SELECT id, username, points, total_points_earned, 
                       DATE_FORMAT(created_at, '%Y-%m-%d %H:%i:%s') as created_at 
                FROM users 
                WHERE username != 'admin' 
                ORDER BY id DESC
            ")->fetchAll(PDO::FETCH_ASSOC);
            
            // Calculate total points earned from bookings for each user
            foreach ($users as &$user) {
                // Get total points from bookings
                $pointsStmt = $pdo->prepare("SELECT COALESCE(SUM(points_earned), 0) as total FROM bookings WHERE user_id = ?");
                $pointsStmt->execute([$user['id']]);
                $bookingPoints = $pointsStmt->fetch(PDO::FETCH_ASSOC);
                
                // Get total points spent on promos
                $spentStmt = $pdo->prepare("SELECT COALESCE(SUM(points_spent), 0) as total FROM redeemed_promos WHERE user_id = ?");
                $spentStmt->execute([$user['id']]);
                $spentPoints = $spentStmt->fetch(PDO::FETCH_ASSOC);
                
                // Add these stats for debugging/info
                $user['booking_points_total'] = intval($bookingPoints['total']);
                $user['spent_points_total'] = intval($spentPoints['total']);
            }
            
            // Get all bookings with user information and movie details
            $bookings = $pdo->query("
                SELECT b.*, u.username, m.title as movie_name
                FROM bookings b 
                LEFT JOIN users u ON b.user_id = u.id 
                LEFT JOIN movies m ON b.movie_id = m.id
                ORDER BY b.booking_time DESC
            ")->fetchAll(PDO::FETCH_ASSOC);
            
            // Format for display
            foreach ($bookings as &$booking) {
                $booking['movie'] = $booking['movie_name'] ?? $booking['movie_title'] ?? 'Unknown Movie';
                $booking['total'] = floatval($booking['total_price'] ?? 0);
                $booking['points_earned'] = intval($booking['points_earned'] ?? 0);
                // Ensure user_id is set
                if (!isset($booking['user_id']) || !$booking['user_id']) {
                    $booking['username'] = 'Unknown User';
                }
            }
            
            // Get all promos
            $promos = $pdo->query("SELECT * FROM promos ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
            
            // Get all redeemed promos with usernames and promo titles
            $redeemed = $pdo->query("
                SELECT r.*, u.username, p.title as promo_title, p.points_required
                FROM redeemed_promos r 
                LEFT JOIN users u ON r.user_id = u.id 
                LEFT JOIN promos p ON r.promo_id = p.id 
                ORDER BY r.redeemed_at DESC
            ")->fetchAll(PDO::FETCH_ASSOC);
            
            // Get all shows with seat information for admin dashboard
            $shows = $pdo->query("
                SELECT DISTINCT 
                    b.movie_title as movie,
                    b.show_date as date,
                    b.show_time as time,
                    COUNT(DISTINCT b.id) as total_bookings,
                    SUM(LENGTH(b.seats) - LENGTH(REPLACE(b.seats, ',', '')) + 1) as total_seats_booked
                FROM bookings b
                GROUP BY b.movie_title, b.show_date, b.show_time
                ORDER BY b.show_date DESC, b.show_time DESC
                LIMIT 20
            ")->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true,
                'movies' => $movies,
                'users' => $users,
                'bookings' => $bookings,
                'promos' => $promos,
                'redeemed' => $redeemed,
                'shows' => $shows
            ]);
        } catch(Exception $e) {
            error_log('Error in admin_get_all: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    case 'admin_get_shows':
        // Check if admin
        if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            break;
        }
        
        try {
            // Get all unique shows with booking counts
            $stmt = $pdo->query("
                SELECT 
                    b.movie_title as movie,
                    b.show_date as date,
                    b.show_time as time,
                    COUNT(DISTINCT b.id) as total_bookings,
                    SUM(LENGTH(b.seats) - LENGTH(REPLACE(b.seats, ',', '')) + 1) as seats_booked,
                    GROUP_CONCAT(DISTINCT b.seats) as all_seats
                FROM bookings b
                GROUP BY b.movie_title, b.show_date, b.show_time
                ORDER BY b.show_date DESC, b.show_time DESC
            ");
            
            $shows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get blocked seats from seat_config
            foreach ($shows as &$show) {
                $key = $show['movie'] . '|' . $show['date'] . '|' . $show['time'];
                
                $blockStmt = $pdo->prepare("SELECT COUNT(*) as blocked FROM seat_config WHERE movie_key = ? AND status = 'blocked'");
                $blockStmt->execute([$key]);
                $blocked = $blockStmt->fetch(PDO::FETCH_ASSOC);
                
                $show['blocked_seats'] = intval($blocked['blocked']);
            }
            
            echo json_encode(['success' => true, 'shows' => $shows]);
            
        } catch(Exception $e) {
            error_log('Error getting shows: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
     
    case 'admin_update_seat':
        // Check if admin
        if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            break;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        $movie = $data['movie'] ?? '';
        $date = $data['date'] ?? '';
        $time = $data['time'] ?? '';
        $seat_id = $data['seat_id'] ?? '';
        $action = $data['action'] ?? ''; // 'block' or 'unblock'
        
        if (!$movie || !$date || !$time || !$seat_id || !$action) {
            echo json_encode(['success' => false, 'message' => 'Missing parameters']);
            break;
        }
        
        $key = $movie . '|' . $date . '|' . $time;
        
        try {
            if ($action === 'block') {
                // Check if seat is already booked
                $checkStmt = $pdo->prepare("SELECT id FROM bookings WHERE movie_title = ? AND show_date = ? AND show_time = ? AND FIND_IN_SET(?, REPLACE(seats, ' ', ''))");
                $checkStmt->execute([$movie, $date, $time, trim($seat_id)]);
                
                if ($checkStmt->rowCount() > 0) {
                    echo json_encode(['success' => false, 'message' => 'Cannot block a seat that is already booked']);
                    break;
                }
                
                // Block the seat with admin info
                $stmt = $pdo->prepare("INSERT INTO seat_config (movie_key, seat_id, status, booked_by) 
                                       VALUES (?, ?, 'blocked', ?)
                                       ON DUPLICATE KEY UPDATE 
                                       status = 'blocked', 
                                       booked_by = VALUES(booked_by),
                                       booking_reference = NULL,
                                       updated_at = CURRENT_TIMESTAMP");
                $stmt->execute([$key, trim($seat_id), $_SESSION['user_id']]);
                
                echo json_encode(['success' => true, 'message' => 'Seat blocked successfully']);
                
            } else if ($action === 'unblock') {
                // Unblock the seat
                $stmt = $pdo->prepare("DELETE FROM seat_config WHERE movie_key = ? AND seat_id = ? AND status = 'blocked'");
                $stmt->execute([$key, trim($seat_id)]);
                
                echo json_encode(['success' => true, 'message' => 'Seat unblocked successfully']);
                
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
            }
            
        } catch(Exception $e) {
            error_log('Error updating seat: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    case 'admin_get_seat_history':
        // Check if admin
        if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            break;
        }
        
        $movie = $_GET['movie'] ?? '';
        $date = $_GET['date'] ?? '';
        $time = $_GET['time'] ?? '';
        
        if (!$movie || !$date || !$time) {
            echo json_encode(['success' => false, 'message' => 'Missing parameters']);
            break;
        }
        
        try {
            $key = $movie . '|' . $date . '|' . $time;
            
            // Get booking history
            $stmt = $pdo->prepare("
                SELECT 
                    'booking' as type,
                    b.booking_reference,
                    b.seats,
                    b.total_price,
                    b.points_earned,
                    b.booking_time,
                    u.username,
                    b.user_id
                FROM bookings b
                LEFT JOIN users u ON b.user_id = u.id
                WHERE b.movie_title = ? AND b.show_date = ? AND b.show_time = ?
                ORDER BY b.booking_time DESC
            ");
            $stmt->execute([$movie, $date, $time]);
            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get seat config history (blocks/unblocks)
            $stmt2 = $pdo->prepare("
                SELECT 
                    'config' as type,
                    sc.seat_id,
                    sc.status,
                    sc.created_at as action_time,
                    u.username,
                    sc.booking_reference
                FROM seat_config sc
                LEFT JOIN users u ON sc.booked_by = u.id
                WHERE sc.movie_key = ?
                ORDER BY sc.updated_at DESC
            ");
            $stmt2->execute([$key]);
            $configs = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true,
                'bookings' => $bookings,
                'configs' => $configs
            ]);
            
        } catch(Exception $e) {
            error_log('Error getting seat history: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    case 'admin_add_movie':
        // Check if admin
        if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            break;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $movie = $data['movie'];
        
        try {
            $stmt = $pdo->prepare("INSERT INTO movies (title, description, genre, duration, price, cast, rating, release_date, trailer_url, poster, is_coming_soon) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $movie['title'],
                $movie['description'],
                $movie['genre'],
                $movie['duration'],
                $movie['price'] ?? 0,
                $movie['cast'],
                $movie['rating'],
                $movie['release_date'],
                $movie['trailer_url'] ?? '',
                $movie['poster'] ?? '',
                $movie['is_coming_soon'] ?? 0
            ]);
            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
        } catch(Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
        
    case 'admin_update_movie':
        // Check if admin
        if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            break;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $movie = $data['movie'];
        
        try {
            // Build dynamic update query
            $fields = [];
            $params = [];
            
            $allowedFields = ['title', 'description', 'genre', 'duration', 'price', 'cast', 'rating', 'release_date', 'trailer_url', 'poster', 'is_coming_soon'];
            
            foreach ($allowedFields as $field) {
                if (isset($movie[$field])) {
                    $fields[] = "$field = ?";
                    $params[] = $movie[$field];
                }
            }
            
            $params[] = $movie['id'];
            
            $sql = "UPDATE movies SET " . implode(", ", $fields) . " WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            echo json_encode(['success' => true]);
        } catch(Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
        
    case 'admin_delete_movie':
        // Check if admin
        if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            break;
        }
        
        $id = $_GET['id'] ?? 0;
        try {
            $stmt = $pdo->prepare("DELETE FROM movies WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
        } catch(Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
        
    case 'admin_add_promo':
        // Check if admin
        if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            break;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $promo = $data['promo'];
        
        try {
            $stmt = $pdo->prepare("INSERT INTO promos (title, description, points_required, discount_amount, discount_percentage, icon, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $promo['title'],
                $promo['description'],
                $promo['points_required'],
                $promo['discount_amount'],
                $promo['discount_percentage'],
                $promo['icon'] ?? 'fa-gift',
                $promo['is_active'] ? 1 : 0
            ]);
            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
        } catch(Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
        
    case 'admin_update_promo':
        // Check if admin
        if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            break;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $promo = $data['promo'];
        
        try {
            $stmt = $pdo->prepare("UPDATE promos SET title = ?, description = ?, points_required = ?, discount_amount = ?, discount_percentage = ?, icon = ?, is_active = ? WHERE id = ?");
            $stmt->execute([
                $promo['title'],
                $promo['description'],
                $promo['points_required'],
                $promo['discount_amount'],
                $promo['discount_percentage'],
                $promo['icon'] ?? 'fa-gift',
                $promo['is_active'] ? 1 : 0,
                $promo['id']
            ]);
            echo json_encode(['success' => true]);
        } catch(Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
        
    case 'admin_delete_promo':
        // Check if admin
        if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            break;
        }
        
        $id = $_GET['id'] ?? 0;
        try {
            $stmt = $pdo->prepare("DELETE FROM promos WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
        } catch(Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
        
    default:
        echo json_encode(['error' => 'Invalid action: ' . $action]);
}
?>
