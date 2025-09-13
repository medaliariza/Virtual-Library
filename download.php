<?php
require_once 'config.php';
$id = isset($_GET['id'])?intval($_GET['id']):0;
$stmt = $mysqli->prepare("SELECT filename, title FROM books WHERE id=? LIMIT 1");
$stmt->bind_param('i',$id); $stmt->execute(); $res = $stmt->get_result();
if (!$row = $res->fetch_assoc()) { die('Not found'); }
$path = __DIR__ . '/books/' . $row['filename'];
if (!file_exists($path)) die('File missing');
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="'.basename($row['filename']).'"');
readfile($path);
exit;
?>