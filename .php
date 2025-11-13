<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
session_start();
require_once 'config.php';

if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

$student_id = $_SESSION['student_id'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];
$full_name = $first_name . ' ' . $last_name;
$initials = strtoupper(substr($first_name, 0, 1) . substr($last_name, 0, 1));

// Initialize default values
$course = 'Computer Science';
$total_paid = 0;
$current_gpa = '0.00';

try {
    // Get department
    $dept_stmt = $pdo->prepare("SELECT d.department_name FROM departments d INNER JOIN students s ON s.department_id = d.department_id WHERE s.student_id = :student_id");
    $dept_stmt->execute(['student_id' => $student_id]);
    $dept_data = $dept_stmt->fetch();
    $course = $dept_data['department_name'] ?? 'Computer Science';

    // Calculate fee balance
    $payment_stmt = $pdo->prepare("SELECT SUM(amount) as total_paid FROM payments WHERE student_id = :student_id AND status = 'completed'");
    $payment_stmt->execute(['student_id' => $student_id]);
    $payment_data = $payment_stmt->fetch();
    $total_paid = $payment_data['total_paid'] ?? 0;

    // Calculate GPA
    $gpa_stmt = $pdo->prepare("SELECT AVG(grade_points) as avg_gpa FROM grades WHERE student_id = :student_id");
    $gpa_stmt->execute(['student_id' => $student_id]);
    $gpa_data = $gpa_stmt->fetch();
    $current_gpa = $gpa_data['avg_gpa'] ? number_format($gpa_data['avg_gpa'], 2) : '0.00';
} catch (PDOException $e) {
    // Log error but don't break the page
    error_log("Dashboard Error: " . $e->getMessage());
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$semester_fee = 70000;
$fee_balance = $semester_fee - $total_paid;
$current_year = date('Y');
$current_semester = 1;
?>
