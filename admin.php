<?php
session_start(); require_once 'config.php';
// Lightweight admin area: requires is_admin flag in users table
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$stmt = $mysqli->prepare('SELECT is_admin FROM users WHERE id=?'); $stmt->bind_param('i', $_SESSION['user_id']); $stmt->execute(); $r = $stmt->get_result()->fetch_assoc();
if (!$r || !$r['is_admin']) { die('Admin access only'); }
// Admin actions: list users and books
$users = $mysqli->query('SELECT id, name, email, is_admin, created_at FROM users ORDER BY created_at DESC');
$books = $mysqli->query('SELECT b.id, b.title, b.author, b.filename, u.name as uploader FROM books b LEFT JOIN users u ON b.uploaded_by = u.id ORDER BY b.uploaded_at DESC');
?>
<!doctype html><html><head><meta charset="utf-8"><title>Admin</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
<h2>Admin Panel</h2>
<section>
  <h3>Users</h3>
  <table>
    <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Admin</th><th>Actions</th></tr></thead>
    <tbody>
    <?php while($u = $users->fetch_assoc()): ?>
      <tr>
        <td><?php echo $u['id']; ?></td>
        <td><?php echo esc($u['name']); ?></td>
        <td><?php echo esc($u['email']); ?></td>
        <td><?php echo $u['is_admin']? 'Yes':'No'; ?></td>
        <td><a href="delete_user.php?id=<?php echo $u['id']; ?>" onclick="return confirm('Delete user?');">Delete</a></td>
        <td><a href="edit_book.php?id=<?php echo $b['id']; ?>">Edit</a> | 
        <a href="delete_book.php?id=<?php echo $b['id']; ?>" onclick="return confirm('Delete book?');">Delete</a></td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</section>
<section>
  <h3>Books</h3>
  <table>
    <thead><tr><th>ID</th><th>Title</th><th>Author</th><th>Uploader</th><th>Actions</th></tr></thead>
    <tbody>
    <?php while($b = $books->fetch_assoc()): ?>
      <tr>
        <td><?php echo $b['id']; ?></td>
        <td><?php echo esc($b['title']); ?></td>
        <td><?php echo esc($b['author']); ?></td>
        <td><?php echo esc($b['uploader']); ?></td>
        <td><a href="delete_book.php?id=<?php echo $b['id']; ?>" onclick="return confirm('Delete book?');">Delete</a></td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</section>
</body></html>
