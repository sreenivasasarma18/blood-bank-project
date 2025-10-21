<?php
require_once 'config.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = ($_POST['role'] === 'receiver') ? 'receiver' : 'donor';

    if (!$name || !$email || !$password) $errors[] = 'All fields required';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email';

    if (empty($errors)){
        $stmt = $mysqli->prepare('SELECT id FROM users WHERE email=?');
        $stmt->bind_param('s',$email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows){
            $errors[] = 'Email already exists';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $mysqli->prepare('INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)');
            $ins->bind_param('ssss',$name,$email,$hash,$role);
            if ($ins->execute()){
                header('Location: login.php');
                exit;
            } else {
                $errors[] = 'Failed to register';
            }
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Register</title><link rel="stylesheet" href="styles.css"></head>
<body>
<div class="container">
  <h2>Sign Up</h2>
  <?php if($errors): foreach($errors as $e): ?><div class="error"><?=htmlspecialchars($e)?></div><?php endforeach; endif; ?>
  <form method="post">
    <label>Name<input name="name" required></label>
    <label>Email<input name="email" type="email" required></label>
    <label>Password<input name="password" type="password" required></label>
    <label>Role
      <select name="role">
        <option value="donor">Donor</option>
        <option value="receiver">Receiver</option>
      </select>
    </label>
    <button type="submit">Register</button>
  </form>
  <p><a href="login.php">Already have an account? Login</a></p>
</div>
</body>
</html>
