<?php
require_once 'config.php';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!$email || !$password) $err = 'Email and password required';
    else {
        $stmt = $mysqli->prepare('SELECT id,name,email,password,role FROM users WHERE email=?');
        $stmt->bind_param('s',$email);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()){
            if (password_verify($password, $row['password'])){
                unset($row['password']);
                $_SESSION['user'] = $row;
                header('Location: index.php');
                exit;
            } else $err = 'Invalid credentials';
        } else $err = 'No user found';
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Login</title><link rel="stylesheet" href="styles.css"></head>
<body>
<div class="container">
  <h2>Login</h2>
  <?php if($err): ?><div class="error"><?=htmlspecialchars($err)?></div><?php endif; ?>
  <form method="post">
    <label>Email<input name="email" type="email" required></label>
    <label>Password<input name="password" type="password" required></label>
    <button type="submit">Login</button>
  </form>
  <p><a href="register.php">Create an account</a></p>
</div>
</body>
</html>
