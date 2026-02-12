<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.html"); // Redirect to login page
    exit();
}

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'test';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, question, answer, difficulty FROM questions";
$result = $conn->query($sql);

$questions = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
}

echo json_encode($questions);

$conn->close();
?>