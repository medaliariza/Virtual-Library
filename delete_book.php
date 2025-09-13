<?php
session_start(); require_once 'config.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
// check admin
$stmt = $mysqli->prepare('SELECT is_admin FROM users WHERE id=?'); $stmt->bind_param('i', $_SESSION['user_id']); $stmt->execute(); $r = $stmt->get_result()->fetch_assoc();
if (!$r || !$r['is_admin']) die('Admin only');
$id = isset($_GET['id'])?intval($_GET['id']):0;
if ($id) {
    $stmt = $mysqli->prepare('SELECT filename FROM books WHERE id=?'); $stmt->bind_param('i',$id); $stmt->execute(); $row = $stmt->get_result()->fetch_assoc();
    if ($row) {
        @unlink(__DIR__.'/books/'.$row['filename']);
        $stmt = $mysqli->prepare('DELETE FROM books WHERE id=?'); $stmt->bind_param('i',$id); $stmt->execute();
    }
}
header('Location: admin.php'); exit;
?>
