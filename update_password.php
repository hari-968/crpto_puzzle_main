<?php
include "config.php"; // Ensure database connection

$username = "user";  // Change this
$newPassword = "user123"; // Change this

// Hash the new password
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

// Update password in the database
$sql = "UPDATE users SET password = ? WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $hashedPassword, $username);

if ($stmt->execute()) {
    echo "Password updated successfully!";
} else {
    echo "Error updating password: " . $stmt->error;
}

$conn->close();
?>
