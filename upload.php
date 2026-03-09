<?php
// upload.php
header('Content-Type: application/json');
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log the request
error_log("Upload request received at " . date('Y-m-d H:i:s'));

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized - Please login as admin']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method. Use POST.']);
    exit;
}

if (!isset($_FILES['image'])) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded - image field not found']);
    exit;
}

$file = $_FILES['image'];

// Log file info
error_log("File info: " . print_r($file, true));

// Check for errors
if ($file['error'] !== UPLOAD_ERR_OK) {
    $error_message = '';
    switch ($file['error']) {
        case UPLOAD_ERR_INI_SIZE:
            $error_message = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
            break;
        case UPLOAD_ERR_FORM_SIZE:
            $error_message = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
            break;
        case UPLOAD_ERR_PARTIAL:
            $error_message = 'The uploaded file was only partially uploaded';
            break;
        case UPLOAD_ERR_NO_FILE:
            $error_message = 'No file was uploaded';
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            $error_message = 'Missing a temporary folder';
            break;
        case UPLOAD_ERR_CANT_WRITE:
            $error_message = 'Failed to write file to disk';
            break;
        case UPLOAD_ERR_EXTENSION:
            $error_message = 'A PHP extension stopped the file upload';
            break;
        default:
            $error_message = 'Unknown upload error';
    }
    echo json_encode(['success' => false, 'message' => 'Upload failed: ' . $error_message]);
    exit;
}

// Validate file type
$allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime_type = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!in_array($mime_type, $allowed_types)) {
    echo json_encode(['success' => false, 'message' => 'Only JPG, PNG, GIF, and WEBP images are allowed. Got: ' . $mime_type]);
    exit;
}

// Validate file size (max 5MB)
if ($file['size'] > 5 * 1024 * 1024) {
    echo json_encode(['success' => false, 'message' => 'File size must be less than 5MB. Got: ' . round($file['size'] / 1024 / 1024, 2) . 'MB']);
    exit;
}

// Create uploads directory if it doesn't exist
$upload_dir = 'uploads/';
if (!file_exists($upload_dir)) {
    if (!mkdir($upload_dir, 0777, true)) {
        echo json_encode(['success' => false, 'message' => 'Failed to create uploads directory']);
        exit;
    }
}

// Check if directory is writable
if (!is_writable($upload_dir)) {
    echo json_encode(['success' => false, 'message' => 'Uploads directory is not writable']);
    exit;
}

// Generate unique filename
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = uniqid() . '_' . time() . '.' . $extension;
$filepath = $upload_dir . $filename;

// Move uploaded file
if (move_uploaded_file($file['tmp_name'], $filepath)) {
    echo json_encode([
        'success' => true,
        'filepath' => $filepath,
        'filename' => $filename,
        'message' => 'File uploaded successfully'
    ]);
} else {
    $error = error_get_last();
    echo json_encode(['success' => false, 'message' => 'Failed to save file: ' . ($error['message'] ?? 'Unknown error')]);
}
?>