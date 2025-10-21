<?php
require_once 'config.php';
require_login();
$uid = $_SESSION['user']['id'];
$role = $_SESSION['user']['role'];
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_receiver'])){
    $blood_group = $_POST['blood_group'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $city = $_POST['city'] ?? '';
    $needed = $_POST['needed_by'] ?? null;
    $stmt = $mysqli->prepare('INSERT INTO receivers (user_id,blood_group,phone,city,needed_by) VALUES (?,?,?,?,?)');
    $stmt->bind_param('issss',$uid,$blood_group,$phone,$city,$needed);
    if ($stmt->execute()) $msg = 'Receiver info saved';
    else $msg = 'Failed to save';
}
if ($role === 'admin'){
    $res = $mysqli->query('SELECT r.*, u.name, u.email FROM receivers r JOIN users u ON r.user_id=u.id ORDER BY r.created_at DESC');
} else {
    $stmt = $mysqli->prepare('SELECT r.*, u.name, u.email FROM receivers r JOIN users u ON r.user_id=u.id WHERE r.user_id=? ORDER BY r.created_at DESC');
    $stmt->bind_param('i',$uid);
    $stmt->execute();
    $res = $stmt->get_result();
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Receivers</title><link rel="stylesheet" href="styles.css"></head>
<body>
<div class="container">
  <h2>Receivers</h2>
  <p><?=htmlspecialchars($msg)?></p>
  <form method="post">
    <h3>Add Receiver Request</h3>
    <label>Blood Group<input name="blood_group" required></label>
    <label>Phone<input name="phone"></label>
    <label>City<input name="city"></label>
    <label>Needed By<input type="date" name="needed_by"></label>
    <button name="add_receiver" type="submit">Save</button>
  </form>
  <h3>List</h3>
  <table>
    <tr><th>Name</th><th>Email</th><th>Blood Group</th><th>Phone</th><th>City</th><th>Needed By</th></tr>
    <?php while($row = $res->fetch_assoc()): ?>
      <tr>
        <td><?=htmlspecialchars($row['name'])?></td>
        <td><?=htmlspecialchars($row['email'])?></td>
        <td><?=htmlspecialchars($row['blood_group'])?></td>
        <td><?=htmlspecialchars($row['phone'])?></td>
        <td><?=htmlspecialchars($row['city'])?></td>
        <td><?=htmlspecialchars($row['needed_by'])?></td>
      </tr>
    <?php endwhile; ?>
  </table>
  <p><a href="index.php">Back</a></p>
</div>
</body></html>
