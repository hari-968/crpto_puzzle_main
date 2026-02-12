<?php
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit();
}

// Continue fetching questions from DB

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

include "config.php"; // Database connection

// Helper function to send JSON response
function sendJsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

// 🔹 Check if user is logged in
if (!isset($_SESSION['username'])) {
    sendJsonResponse(["error" => "Unauthorized"], 401);
}

$username = $_SESSION['username'];

// 🔹 Fetch user score & correct answers
$userQuery = "SELECT score, correct_answers FROM users WHERE username = ?";
$userStmt = $conn->prepare($userQuery);
$userStmt->bind_param("s", $username);
$userStmt->execute();
$userResult = $userStmt->get_result();
$userData = $userResult->fetch_assoc();

if (!$userData) {
    sendJsonResponse(["error" => "User not found"], 404);
}

// 🔹 Fetch total number of questions in the database
$totalQuestionsQuery = "SELECT COUNT(*) as total FROM questions";
$totalQuestionsResult = $conn->query($totalQuestionsQuery);
$totalQuestions = $totalQuestionsResult->fetch_assoc()['total'] ?? 0;

// 🔹 Fetch random questions from the database
$questionQuery = "SELECT id, question, answer, difficulty FROM questions ORDER BY RAND()";
$questionResult = $conn->query($questionQuery);

$questions = [];
if ($questionResult) {
    while ($row = $questionResult->fetch_assoc()) {
        $questions[] = $row;
    }
} else {
    sendJsonResponse(["error" => "Query failed: " . $conn->error], 500);
}

// 🔹 Return JSON response with user data, total questions, and selected questions
sendJsonResponse([
    "username" => $username,
    "score" => $userData["score"],
    "correct_answers" => $userData["correct_answers"],
    "total_questions" => $totalQuestions,
    "questions" => $questions
]);

$conn->close();
?>
