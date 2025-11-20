<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';

echo "<h2>Testing Signup Database Connection</h2>";

// Test if PDO exists
if (isset($pdo)) {
    echo "✓ PDO connection exists<br><br>";
    
    // Try a simple insert
    try {
        $test_insert = "INSERT INTO students 
            (first_name, last_name, email, password_hash, department_id, enrollment_date) 
            VALUES ('Test', 'User', 'test@example.com', 'hashedpassword', 1, CURDATE())";
        
        $result = $pdo->exec($test_insert);
        
        if ($result) {
            echo "✓ Test insert successful!<br>";
            echo "Student ID: " . $pdo->lastInsertId() . "<br>";
            
            // Clean up
            $pdo->exec("DELETE FROM students WHERE email = 'test@example.com'");
            echo "<br>✓ Test complete - signup should work!";
        } else {
            echo "✗ Insert failed but no error thrown<br>";
        }
        
    } catch (PDOException $e) {
        echo "✗ Error: " . $e->getMessage();
    }
    
} else {
    echo "✗ PDO connection NOT found!<br>";
    echo "Config.php is not creating the PDO connection.";
}
?>