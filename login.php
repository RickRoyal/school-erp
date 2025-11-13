<?php
session_start();
require_once 'config.php'; // Use PDO connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    try {
        // Check email from DB using PDO
        $sql = "SELECT student_id, password_hash, first_name, last_name FROM students WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        
        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Validate password
            if (password_verify($password, $user['password_hash'])) {
                // Create session with ALL required variables
                $_SESSION['logged_in'] = true;
                $_SESSION['student_id'] = $user['student_id'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                
                // Redirect to dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Incorrect password!";
            }
        } else {
            $error = "Email not found!";
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
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
            <p style="color:red;"><?= htmlspecialchars($error) ?></p>
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