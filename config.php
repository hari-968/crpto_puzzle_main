<?php
// Database configuration
$host = 'localhost';        // Your database host (usually localhost)
$user = 'root';             // Your MySQL username
$password = '';             // Your MySQL password
$database = 'crypto_puzzle_db'; // Your database name

// Create a connection
$conn = new mysqli($host, $user, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
