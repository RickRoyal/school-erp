<?php
session_start();
require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        die("Passwords do not match!");
    }

    $stmt = $pdo->prepare("SELECT student_id FROM students WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        die("Email already exists!");
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO students (first_name, last_name, email, password_hash) VALUES (?, ?, ?, ?)");
    
    if ($stmt->execute([$first_name, $last_name, $email, $password_hash])) {
        echo "Account created successfully! <a href='login.php'>Login here</a>";
    } else {
        echo "Registration failed. Please try again.";
    }
}
?>