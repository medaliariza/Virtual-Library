<?php
require_once 'config.php';

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function current_user() {
    global $mysqli;
    if (!is_logged_in()) return null;
    $id = intval($_SESSION['user_id']);
    $stmt = $mysqli->prepare("SELECT id, name, email, is_admin FROM users WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res->fetch_assoc();
}
?>