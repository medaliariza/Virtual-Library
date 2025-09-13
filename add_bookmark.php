<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

$userId = $_SESSION['user_id'];
$bookId = intval($_POST['book_id']);
$page   = intval($_POST['page']); // for now static = 1

$stmt = $mysqli->prepare("INSERT INTO bookmarks (user_id, book_id, page) VALUES (?, ?, ?)");
$stmt->bind_param("iii", $userId, $bookId, $page);
$stmt->execute();

header("Location: book_viewer.php?id=".$bookId);
exit;
