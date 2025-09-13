<header class="top-nav">
  <h1>📚 Virtual Library</h1>
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
      <!-- Query user’s saved books -->
      <?php /* loop over reading_list */ ?>
    </ul>

    <h3>Bookmarks</h3>
    <ul>
      <!-- Query user’s bookmarks -->
    </ul>
  </aside>

  <main class="content">
    <!-- Book catalog or book viewer -->
  </main>
</div>
