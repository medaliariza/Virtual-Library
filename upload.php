<?php
session_start(); 
require_once 'config.php';

if (!isset($_SESSION['user_id'])) { 
    header('Location: login.php'); 
    exit; 
}

// Check if user is admin
$uid = intval($_SESSION['user_id']);
$stmt = $mysqli->prepare("SELECT is_admin FROM users WHERE id=?");
$stmt->bind_param('i', $uid);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
if (!$user || !$user['is_admin']) {
    die("Access denied: only admins can upload books.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $genre = trim($_POST['genre']);
    $desc = trim($_POST['description']);

    if (!isset($_FILES['pdf']) || $_FILES['pdf']['error'] !== UPLOAD_ERR_OK) {
        die('File upload error');
    }

    $f = $_FILES['pdf'];
    if ($f['type'] !== 'application/pdf') {
        die('Only PDF files allowed.');
    }

    $uploads_dir = __DIR__ . '/books';
    if (!is_dir($uploads_dir)) mkdir($uploads_dir, 0755, true);

    $safeName = time() . '_' . preg_replace('/[^A-Za-z0-9\\.\\-_]/', '_', basename($f['name']));
    $dest = $uploads_dir . '/' . $safeName;

    if (move_uploaded_file($f['tmp_name'], $dest)) {
        $stmt = $mysqli->prepare("INSERT INTO books (title, author, genre, description, filename, uploaded_by) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssssi', $title, $author, $genre, $desc, $safeName, $uid);
        $stmt->execute();
        header('Location: dashboard.php'); 
        exit;
    } else {
        die('Could not move uploaded file.');
    }
}
?>
