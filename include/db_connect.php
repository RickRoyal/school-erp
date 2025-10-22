<?php
$host = "localhost"; // your server
$user = "root"; // default XAMPP username
$pass = ""; // default XAMPP password is empty
$dbname = "academic_management"; // your database name

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
