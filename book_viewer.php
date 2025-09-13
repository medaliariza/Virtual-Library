<?php
session_start();
require_once 'config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $mysqli->prepare("SELECT * FROM books WHERE id=? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
if (!$book = $res->fetch_assoc()) { die('Book not found'); }

$filePath = 'books/' . $book['filename'];
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title><?php echo esc($book['title']); ?></title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<h2><?php echo esc($book['title']); ?></h2>
<p>Author: <?php echo esc($book['author']); ?></p>

<!-- ✅ Action buttons -->
<?php if(isset($_SESSION['user_id'])): ?>
  <form method="post" action="toggle_reading_list.php" style="display:inline;">
    <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
    <button type="submit">➕ Add to Reading List</button>
  </form>

  <form method="post" action="add_bookmark.php" style="display:inline;">
    <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
    <!-- For now we just save page=1 -->
    <input type="hidden" name="page" value="1">
    <button type="submit">🔖 Add Bookmark</button>
  </form>
<?php endif; ?>

<div class="pdf-viewer" style="margin-top:20px;">
  <iframe src="<?php echo esc($filePath); ?>" width="100%" height="700px"></iframe>
</div>
</body>
</html>
