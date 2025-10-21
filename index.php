<?php
require_once 'config.php';
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Blood Bank Management System</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <h1>Blood Bank Management System</h1>
    <?php if (is_logged_in()): ?>
      <p>Welcome, <?=htmlspecialchars($_SESSION['user']['name'])?> (<?=htmlspecialchars($_SESSION['user']['role'])?>)</p>
      <nav>
        <a href="donor.php">Donors</a>
        <a href="receiver.php">Receivers</a>
        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
          <a href="admin.php">Admin Panel</a>
        <?php endif; ?>
        <a href="logout.php">Logout</a>
      </nav>
    <?php else: ?>
      <nav>
        <a href="register.php">Sign Up</a>
        <a href="login.php">Login</a>
      </nav>
      <p>This is a simple sample Blood Bank Management System. Register as a donor or receiver.</p>
    <?php endif; ?>
  </div>
</body>
</html>
