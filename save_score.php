<?php
include 'config.php';  // Include database connection

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Debug: Print received data
print_r($_POST);
echo "<br>";

// Check database connection
if (!$conn) {
    die("❌ Database connection failed: " . mysqli_connect_error());
} else {
    echo "✅ Database connected!<br>";
}

// Validate POST data
if (!isset($_POST['username']) || !isset($_POST['score'])) {
    die("❌ Invalid request! Missing username or score.");
}

// Sanitize input
$username = trim($_POST['username']);
$score = (int) $_POST['score'];

// Debug: Show received data
echo "Debug: Username: $username, Score: $score<br>";

// Check if user exists
$checkUserSql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($checkUserSql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User exists, update score
    $updateSql = "UPDATE users SET score = ? WHERE username = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("is", $score, $username);
    if ($stmt->execute()) {
        echo "✅ Score updated successfully!";
    } else {
        echo "❌ SQL Error (Update): " . $stmt->error;
    }
} else {
    // New user, insert data
    $insertSql = "INSERT INTO users (username, score) VALUES (?, ?)";
    $stmt = $conn->prepare($insertSql);
    $stmt->bind_param("si", $username, $score);
    if ($stmt->execute()) {
        echo "✅ New user and score saved!";
    } else {
        echo "❌ SQL Error (Insert): " . $stmt->error;
    }
}

$conn->close();
?>
