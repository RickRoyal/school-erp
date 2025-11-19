<?php
session_start();


require_once 'config.php';

try {
    $dept_stmt = $pdo->query("SELECT department_id, department_name FROM departments ORDER BY department_name");
    $departments = $dept_stmt->fetchAll();
} catch (PDOException $e) {
    $departments = [];
}


$error = $_SESSION['error'] ?? null;
$success = $_SESSION['success'] ?? null;
unset($_SESSION['error'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | School ERP</title>
    <link rel="stylesheet" href="css/signup.css">
</head>
<body>
    <div class="left-text">
        <h1>Join Our Community of Innovators and Leaders</h1>
    </div>
    <div class="signup-container">
        <h2>Create an Account</h2>
        
        <?php if ($error): ?>
            <p style="color:red; background-color: #fee2e2; padding: 0.75rem; border-radius: 8px; margin-bottom: 1rem;">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <p style="color:green; background-color: #d1fae5; padding: 0.75rem; border-radius: 8px; margin-bottom: 1rem;">
                <?= htmlspecialchars($success) ?>
            </p>
        <?php endif; ?>
        
        <form action="signup_process.php" method="POST">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" placeholder="Enter your first name" required>
            </div>
            
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" placeholder="Enter your last name" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            
            <div class="form-group">
                <label for="program">Program/Course</label>
                <select id="program" name="department_id" required style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                    <option value="">-- Select Your Program --</option>
                    <?php if (empty($departments)): ?>
                        <option value="" disabled>No programs available. Contact admin.</option>
                    <?php else: ?>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?= $dept['department_id'] ?>">
                                BSc <?= htmlspecialchars($dept['department_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Create a password" required minlength="6">
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
            </div>
            
            <button type="submit" class="signup-btn">Sign Up</button>
            <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
        </form>
    </div>
</body>
</html>