<?php
// Database connection credentials
$dsn = 'pgsql:host=localhost;port=5433;dbname=user_data';
$username = 'postgres';
$password = 'shili';

try {
    // Create a new PDO instance
    $pdo = new PDO($dsn, $username, $password);

    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optional: Set the default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Optional: Set the character encoding to UTF-8
    $pdo->exec("SET NAMES 'utf8'");
} catch (PDOException $e) {
    // Handle connection errors
    die('Connection failed: ' . $e->getMessage());
}
?>
