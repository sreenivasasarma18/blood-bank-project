<?php
require_once 'config.php';
require_login();
$uid = $_SESSION['user']['id'];
$role = $_SESSION['user']['role'];
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_donor'])){
    $blood_group = $_POST['blood_group'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $city = $_POST['city'] ?? '';
    $last = $_POST['last_donation'] ?? null;
    $stmt = $mysqli->prepare('INSERT INTO donors (user_id,blood_group,phone,city,last_donation) VALUES (?,?,?,?,?)');
    $stmt->bind_param('issss',$uid,$blood_group,$phone,$city,$last);
    if ($stmt->execute()) $msg = 'Donor info saved';
    else $msg = 'Failed to save';
}
if ($role === 'admin'){
    $res = $mysqli->query('SELECT d.*, u.name, u.email FROM donors d JOIN users u ON d.user_id=u.id ORDER BY d.created_at DESC');
} else {
    $stmt = $mysqli->prepare('SELECT d.*, u.name, u.email FROM donors d JOIN users u ON d.user_id=u.id WHERE d.user_id=? ORDER BY d.created_at DESC');
    $stmt->bind_param('i',$uid);
    $stmt->execute();
    $res = $stmt->get_result();
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Donors</title><link rel="stylesheet" href="styles.css"></head>
<body>
<div class="container">
  <h2>Donors</h2>
  <p><?=htmlspecialchars($msg)?></p>
  <form method="post">
    <h3>Add / Update Donor Info</h3>
    <label>Blood Group<input name="blood_group" required></label>
    <label>Phone<input name="phone"></label>
    <label>City<input name="city"></label>
    <label>Last Donation Date<input type="date" name="last_donation"></label>
    <button name="add_donor" type="submit">Save</button>
  </form>
  <h3>List</h3>
  <table>
    <tr><th>Name</th><th>Email</th><th>Blood Group</th><th>Phone</th><th>City</th><th>Last Donation</th></tr>
    <?php while($row = $res->fetch_assoc()): ?>
      <tr>
        <td><?=htmlspecialchars($row['name'])?></td>
        <td><?=htmlspecialchars($row['email'])?></td>
        <td><?=htmlspecialchars($row['blood_group'])?></td>
        <td><?=htmlspecialchars($row['phone'])?></td>
        <td><?=htmlspecialchars($row['city'])?></td>
        <td><?=htmlspecialchars($row['last_donation'])?></td>
      </tr>
    <?php endwhile; ?>
  </table>
  <p><a href="index.php">Back</a></p>
</div>
</body></html>
