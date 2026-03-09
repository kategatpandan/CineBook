<?php
// test_upload_exists.php
echo "<h2>Testing upload.php location</h2>";

$upload_php_path = __DIR__ . '/upload.php';
echo "Looking for upload.php at: " . $upload_php_path . "<br>";

if (file_exists($upload_php_path)) {
    echo "✅ upload.php exists!<br>";
    echo "File size: " . filesize($upload_php_path) . " bytes<br>";
    echo "Last modified: " . date("Y-m-d H:i:s", filemtime($upload_php_path)) . "<br>";
} else {
    echo "❌ upload.php does NOT exist at this location!<br>";
}

echo "<br>Current directory: " . __DIR__ . "<br>";
echo "Document root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
?>