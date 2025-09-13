<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$stmt = $mysqli->prepare("SELECT is_admin FROM users WHERE id=?");
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$is_admin = $stmt->get_result()->fetch_assoc()['is_admin'];
if (!$is_admin) die("Admin only");

$id = intval($_GET['id']);
$res = $mysqli->query("SELECT * FROM books WHERE id=$id");
$book = $res->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'];
  $author = $_POST['author'];
  $genre = $_POST['genre'];
  $desc = $_POST['description'];

  $stmt = $mysqli->prepare("UPDATE books SET title=?, author=?, genre=?, description=? WHERE id=?");
  $stmt->bind_param("ssssi", $title, $author, $genre, $desc, $id);
  $stmt->execute();
  header("Location: admin.php"); exit;
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Edit Book</title></head>
<body>
<h2>Edit Book</h2>
<form method="post">
  <label>Title <input name="title" value="<?php echo esc($book['title']); ?>"></label>
  <label>Author <input name="author" value="<?php echo esc($book['author']); ?>"></label>
  <label>Genre <input name="genre" value="<?php echo esc($book['genre']); ?>"></label>
  <label>Description <textarea name="description"><?php echo esc($book['description']); ?></textarea></label>
  <button type="submit">Save</button>
</form>
</body></html>
