<?php
session_start();
include "config.php"; // Ensure database connection

if (!isset($_POST['username']) || !isset($_POST['password'])) {
    die("error: Missing username or password");
}

$username = trim($_POST['username']);
$password = $_POST['password']; // User's entered password

if ($username === "" || $password === "") {
    die("error: Username and password are required");
}

// Fetch user from the database
$query = "SELECT username, password FROM users WHERE username = ?";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    http_response_code(500);
    die("error: Login query preparation failed: " . $conn->error);
}

$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($dbUsername, $dbPassword);


if ($stmt->num_rows > 0 && $stmt->fetch()) {
    // Verify password using password_verify()
    if (password_verify($password, $dbPassword)) {
        $_SESSION['username'] = $username;
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid login credentials!"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid login credentials!"]);
}

$conn->close();