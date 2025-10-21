<?php
require_once 'config.php';
require_login();
if ($_SESSION['user']['role'] !== 'admin'){
    die('Access denied - admin only');
}
$users = $mysqli->query('SELECT id,name,email,role,created_at FROM users ORDER BY id DESC');
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Admin Panel</title><link rel="stylesheet" href="styles.css"></head>
<body>
<div class="container">
  <h2>Admin Panel</h2>
  <h3>Users</h3>
  <table>
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Joined</th></tr>
    <?php while($u = $users->fetch_assoc()): ?>
      <tr>
        <td><?=htmlspecialchars($u['id'])?></td>
        <td><?=htmlspecialchars($u['name'])?></td>
        <td><?=htmlspecialchars($u['email'])?></td>
        <td><?=htmlspecialchars($u['role'])?></td>
        <td><?=htmlspecialchars($u['created_at'])?></td>
      </tr>
    <?php endwhile; ?>
  </table>
  <p><a href="index.php">Back</a></p>
</div>
</body></html>
