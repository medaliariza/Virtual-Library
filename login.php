<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $mysqli->prepare("SELECT id, password FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($user = $res->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: dashboard.php');
            exit;
        }
    }
    $error = 'Invalid credentials.';
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="top-nav">
  <h1>📚 Virtual Library</h1>

  <!-- ✅ Search form closed properly -->
  <form id="searchForm" method="get" action="index.php" style="display:inline;">
    <input type="search" name="q" placeholder="Search by title, author, genre" 
           value="<?php echo isset($_GET['q'])?esc($_GET['q']):''; ?>">
    <button type="submit">Search</button>
  </form>

  <nav class="right">
    <?php if(isset($_SESSION['user_id'])): ?>
      <a href="dashboard.php">Dashboard</a>
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="index.php">Home</a>
      <a href="register.php">Sign up</a>
    <?php endif; ?>
  </nav>
</header>

<h2 style="text-align:center">Login</h2>
<?php if(isset($error)) echo '<p class="error">'.esc($error).'</p>'; ?>

<!-- ✅ Proper login form -->
<form method="post" action="">
  <section class="post">
    <label>Email <input type="email" name="email" required></label><br><br>
    <label>Password <input type="password" name="password" required></label><br><br>
    <button type="submit">Login</button>
  </section>
</form>

</body>
</html>
