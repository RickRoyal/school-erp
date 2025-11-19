<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $department_id = intval($_POST['department_id']);
    

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: signup.php");
        exit();
    }
    
    
    if (strlen($password) < 6) {
        $_SESSION['error'] = "Password must be at least 6 characters long!";
        header("Location: signup.php");
        exit();
    }
    
    
    if ($department_id <= 0) {
        $_SESSION['error'] = "Please select a valid program!";
        header("Location: signup.php");
        exit();
    }
    

    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    try {
   
        $check_sql = "SELECT student_id FROM students WHERE email = :email";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->execute(['email' => $email]);
        
        if ($check_stmt->rowCount() > 0) {
            $_SESSION['error'] = "Email already registered!";
            header("Location: signup.php");
            exit();
        }
        
       
        $insert_sql = "INSERT INTO students 
            (first_name, last_name, email, password_hash, department_id, enrollment_date) 
            VALUES (:first_name, :last_name, :email, :password_hash, :department_id, CURDATE())";
        
        $insert_stmt = $pdo->prepare($insert_sql);
        $insert_stmt->execute([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'password_hash' => $password_hash,
            'department_id' => $department_id
        ]);
        
        $_SESSION['success'] = "Registration successful! Please login.";
        header("Location: login.php");
        exit();
        
    } catch (PDOException $e) {
        $_SESSION['error'] = "Registration failed: " . $e->getMessage();
        header("Location: signup.php");
        exit();
    }
} else {
    header("Location: signup.php");
    exit();
}
?>