<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!$name || !$email || !$password) {
        $error = 'Please fill all fields.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $name, $email, $hash);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $stmt->insert_id;
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Email may already be registered.';
        }
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Register</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="top-nav">
  <h1>📚 Virtual Library</h1>

  <!-- ✅ SEARCH FORM -->
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
      <a href="login.php">Login</a>
    <?php endif; ?>
  </nav>
</header>

<h2 style="text-align:center">Sign up</h2>
<?php if(isset($error)) echo '<p class="error">'.esc($error).'</p>'; ?>

<!-- ✅ REGISTRATION FORM -->
<form method="post" action="">
  <section class="post">
    <label>Name <input type="text" name="name" required></label><br><br>
    <label>Email <input type="email" name="email" required></label><br><br>
    <label>Password <input type="password" name="password" required></label><br><br>
    <button type="submit">Register</button>
  </section>
</form>

</body>
</html>
