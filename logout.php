<?php
session_start();
include "config.php"; // Ensure database connection

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Set user as logged out in the database
    $query = "UPDATE users SET is_logged_in = 0 WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
}

// Destroy session and log out (DO NOT reset start_time)
session_destroy();
echo json_encode(["status" => "success", "message" => "Logged out successfully!"]);
?>
