<?php
session_start();
require_once 'config.php';
// Simple home page: list books + search
$search = isset($_GET['q']) ? "%".$mysqli->real_escape_string($_GET['q'])."%" : "%";
$stmt = $mysqli->prepare("SELECT id, title, author, genre, filename FROM books WHERE title LIKE ? OR author LIKE ? OR genre LIKE ? ORDER BY uploaded_at DESC");
$stmt->bind_param('sss', $search, $search, $search);
$stmt->execute();
$books = $stmt->get_result();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Virtual Library</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <header class="top-nav">
  <h1>📚 Virtual Library</h1>
   <form id="searchForm" method="get" action="index.php">
    <input type="search" name="q" placeholder="Search by title, author, genre" value="<?php echo isset($_GET['q'])?esc($_GET['q']):''; ?>">
    <button type="submit">Search</button>
  </form>
  <nav class="right">
    <?php if(isset($_SESSION['user_id'])): ?>
      <a href="dashboard.php">Dashboard</a>
      <a href="logout.php">Logout</a>
       <?php else: ?>
      <a href="login.php">Login</a>
      <a href="register.php">Sign up</a>
    <?php endif; ?>
  </nav>
 
     
</header>
 <main>
  <section class="catalog">
    <?php while ($row = $books->fetch_assoc()): ?>
      <article class="book-card">
        <img src="assets/img/download.jpg" alt="cover">
        <h3><?php echo esc($row['title']); ?></h3>
        <p><?php echo esc($row['author']); ?></p>
        <p class="genre"><?php echo esc($row['genre']); ?></p>
        <div class="actions">
          <a href="book_viewer.php?id=<?php echo $row['id']; ?>" target="_blank">Read</a>
          <a href="download.php?id=<?php echo $row['id']; ?>">Download</a>
        </div>
      </article>
    <?php endwhile; ?>
  </section>
</main>
<script src="assets/js/main.js"></script>
</body>
</html>
