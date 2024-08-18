<?php
// Load environment variables
require_once __DIR__ . '/vendor/autoload.php'; // Correct path to autoload.php

use Dotenv\Dotenv;

// Initialize Dotenv and load environment variables
$dotenv = Dotenv::createImmutable(__DIR__,'server_envt_var.env'); // Adjusted path for Dotenv
$dotenv->load();

// Test if environment variables are loaded
echo $_ENV['DB_HOST'];

try {
    // Create a new PDO instance
    $pdo = new PDO(
        'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASS']
    );
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection errors
    die('Database connection failed: ' . $e->getMessage());
}
?>
