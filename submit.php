<?php
session_start();
include "config.php"; // Database connection

if (!isset($_SESSION['username'])) {
    echo "User not logged in!";
    exit();
}

$username = $_SESSION['username'];
$question_id = $_POST['question_id'];
$answer = $_POST['answer'];

// Validate the answer (assuming you have a questions table)
$query = "SELECT correct_answer FROM questions WHERE id = '$question_id'";
$result = mysqli_query($conn, $query);
$question = mysqli_fetch_assoc($result);

if ($question) {
    if ($answer == $question['correct_answer']) {
        // ✅ Correct answer → Update user's answered count
        $updateQuery = "UPDATE users SET questions_answered = questions_answered + 1 WHERE username = '$username'";
        mysqli_query($conn, $updateQuery);

        echo "Correct! Your progress has been updated.";
    } else {
        echo "Wrong answer. Try again!";
    }
} else {
    echo "Invalid question.";
}
?>
