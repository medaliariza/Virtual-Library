<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

$userId = $_SESSION['user_id'];
$bookId = intval($_POST['book_id']);

// Check if already exists
$stmt = $mysqli->prepare("SELECT id FROM reading_list WHERE user_id=? AND book_id=?");
$stmt->bind_param("ii", $userId, $bookId);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    // Remove
    $mysqli->query("DELETE FROM reading_list WHERE user_id=$userId AND book_id=$bookId");
} else {
    // Add
    $stmt = $mysqli->prepare("INSERT INTO reading_list (user_id, book_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $userId, $bookId);
    $stmt->execute();
}

header("Location: book_viewer.php?id=".$bookId);
exit;
