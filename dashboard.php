<?php
session_start(); 
require_once 'config.php';

if (!isset($_SESSION['user_id'])) { 
    header('Location: login.php'); 
    exit; 
}

$userId = intval($_SESSION['user_id']);
$stmt = $mysqli->prepare("SELECT is_admin FROM users WHERE id=?");
$stmt->bind_param('i', $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$is_admin = $user['is_admin'] ?? 0;

// Fetch books uploaded by this user (for "My Uploads")
$stmt = $mysqli->prepare("SELECT id, title, author, genre, filename FROM books WHERE uploaded_by=? ORDER BY uploaded_at DESC");
$stmt->bind_param('i', $userId); 
$stmt->execute(); 
$mybooks = $stmt->get_result();

// Fetch some featured/latest books (any user, for dashboard showcase)
$featured = $mysqli->query("SELECT id, title, author, genre FROM books ORDER BY uploaded_at DESC LIMIT 6");
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    .catalog {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
      gap: 16px;
      margin-top: 20px;
    }
    .book-card {
      background: #fff;
      padding: 12px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,.1);
      text-align: center;
    }
    .book-card img {
      width: 100%;
      max-height: 200px;
      object-fit: cover;
      border-radius: 6px;
    }
    .book-card h3 { margin: 8px 0 4px; font-size: 1.1em; }
    .book-card p { margin: 2px 0; }
    .book-card .actions a {
      display: inline-block;
      margin: 4px;
      padding: 6px 10px;
      background: #003366;
      color: #fff;
      border-radius: 4px;
      text-decoration: none;
    }
    .book-card .actions a:hover { background: #0055aa; }
  </style>
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
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="login.php">Login</a>
      <a href="register.php">Sign up</a>
    <?php endif; ?>
  </nav>
</header>

<div class="layout">
  <aside class="sidebar">
    <h3>Categories</h3>
    <ul>
      <li><a href="index.php?genre=Science">Science</a></li>
      <li><a href="index.php?genre=History">History</a></li>
      <li><a href="index.php?genre=Technology">Technology</a></li>
      <li><a href="index.php?genre=Fiction">Fiction</a></li>
    </ul>

    <h3>Reading List</h3>
<ul>
<?php
$res = $mysqli->prepare("SELECT b.id, b.title FROM reading_list rl 
                         JOIN books b ON rl.book_id=b.id 
                         WHERE rl.user_id=?");
$res->bind_param("i", $userId);
$res->execute();
$rl = $res->get_result();
while($row = $rl->fetch_assoc()):
?>
  <li><a href="book_viewer.php?id=<?php echo $row['id']; ?>"><?php echo esc($row['title']); ?></a></li>
<?php endwhile; ?>
</ul>
<h3>Bookmarks</h3>
<ul>
<?php
$res = $mysqli->prepare("SELECT b.id, b.title, bm.page FROM bookmarks bm 
                         JOIN books b ON bm.book_id=b.id 
                         WHERE bm.user_id=? ORDER BY bm.created_at DESC");
$res->bind_param("i", $userId);
$res->execute();
$bm = $res->get_result();
while($row = $bm->fetch_assoc()):
?>
  <li>
    <a href="book_viewer.php?id=<?php echo $row['id']; ?>">
      <?php echo esc($row['title']); ?> (Page <?php echo $row['page']; ?>)
    </a>
  </li>
<?php endwhile; ?>
</ul>

  </aside>

  <main class="content">
    <h2>✨ Featured Books</h2>
    <section class="catalog">
      <?php while ($row = $featured->fetch_assoc()): ?>
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
</div>

</body>
</html>
