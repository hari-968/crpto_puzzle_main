<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');  // Ensure response is JSON

include "config.php"; // Ensure database connection

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

// ✅ Sanitize session username
$username = trim(htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'));

// 🔹 Fetch user score & correct answers securely
$userQuery = "SELECT score, correct_answers FROM users WHERE username = ?";
$userStmt = $conn->prepare($userQuery);

if (!$userStmt) {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $conn->error]);
    exit;
}

$userStmt->bind_param("s", $username);
$userStmt->execute();
$userResult = $userStmt->get_result();

if (!$userResult) {
    http_response_code(500);
    echo json_encode(["error" => "Error fetching user data"]);
    exit;
}

$userData = $userResult->fetch_assoc();
$userStmt->close();

if (!$userData) {
    http_response_code(404);
    echo json_encode(["error" => "User not found"]);
    exit;
}

// 🔹 Fetch random questions securely (Better performance than ORDER BY RAND())
$questionQuery = "SELECT id, question, answer, difficulty FROM questions";

$questionStmt = $conn->prepare($questionQuery);

if (!$questionStmt) {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $conn->error]);
    exit;
}

$questionStmt->execute();
$questionResult = $questionStmt->get_result();

$questions = [];
while ($row = $questionResult->fetch_assoc()) {
    $questions[] = $row;
}
$questionStmt->close();

// 🔹 Return questions + user data
$response = [
    "username" => $username,
    "score" => $userData["score"],
    "correct_answers" => $userData["correct_answers"] ?? 0,  // Default to 0 if not found
    "questions" => $questions
];

echo json_encode($response);
$conn->close();

?>
