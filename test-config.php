<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing config.php...<br><br>";

require_once 'config.php';

echo "Config loaded!<br><br>";

if (isset($conn)) {
    echo "✓ \$conn exists (MySQLi)<br>";
} else {
    echo "✗ \$conn NOT found<br>";
}

if (isset($pdo)) {
    echo "✓ \$pdo exists (PDO)<br>";
} else {
    echo "✗ \$pdo NOT found<br>";
}
?>