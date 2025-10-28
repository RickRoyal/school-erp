<?php
session_start();
require_once 'include/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Check email from DB
    $sql = "SELECT student_id, password_hash, first_name FROM students WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Validate password
        if (password_verify($password, $user['password_hash'])) {
            // Create session
            $_SESSION['student_id'] = $user['student_id'];
            $_SESSION['student_name'] = $user['first_name'];

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Email not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | School ERP</title>
  <link rel="stylesheet" href="css/login.css">
</head>
<body>
  <div class="left-text">
    <h1>A Leading University in Technological Innovation, Research, and Training</h1>
  </div>

  <div class="login-container">
    <h2>Login</h2>

    <?php if (!empty($error)): ?>
      <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <form action="" method="POST">
      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" required>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>

      <button type="submit" class="login-btn">Login</button>
      <p class="signup-link">Don't have an account? <a href="signup.php">Sign up here</a></p>
    </form>
  </div>
</body>
</html>