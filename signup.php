<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | School ERP</title>
    <link rel="stylesheet" href="css/signup.css">
</head>
<body>

    <div class="signup-container">
        <h2>Create an Account</h2>

        <form action="signup_process.php" method="POST">
            <input type="text" name="first_name" placeholder="First Name" required><br>
            <input type="text" name="last_name" placeholder="Last Name" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
            <button type="submit">Sign Up</button>
        </form>

        <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
    </div>

</body>
</html>
