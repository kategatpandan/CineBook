<?php
// TEMPORARY version for testing - NO DATABASE REQUIRED
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Comment out database for now
/*
$host = 'localhost';
$dbname = 'cinebook';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
*/

// For now, just continue without database
// Your app will show "No movies available" but should load
?>
