<?php
// simple_upload_test.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_image'])) {
    $upload_dir = 'uploads/';
    
    // Create uploads directory if it doesn't exist
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $file = $_FILES['test_image'];
    $filename = time() . '_' . $file['name'];
    $filepath = $upload_dir . $filename;
    
    echo "<h3>Upload Debug Info:</h3>";
    echo "<pre>";
    echo "File name: " . $file['name'] . "\n";
    echo "File size: " . $file['size'] . " bytes\n";
    echo "File type: " . $file['type'] . "\n";
    echo "Temp file: " . $file['tmp_name'] . "\n";
    echo "Error code: " . $file['error'] . "\n";
    echo "</pre>";
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        echo "<p style='color:green;'>✅ File uploaded successfully!</p>";
        echo "<p>Saved to: $filepath</p>";
        echo "<img src='$filepath' style='max-width:300px;'><br>";
        echo "<a href='$filepath' target='_blank'>View Image</a>";
    } else {
        echo "<p style='color:red;'>❌ Upload failed!</p>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Simple Upload Test</title>
</head>
<body>
    <h2>Simple Upload Test</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="test_image" required>
        <button type="submit">Upload</button>
    </form>
</body>
</html>