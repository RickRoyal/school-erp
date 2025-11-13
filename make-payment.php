<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

$student_id = $_SESSION['student_id'];

// Handle payment submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['make_payment'])) {
    $amount = floatval($_POST['amount']);
    $payment_method = $_POST['payment_method'];
    $transaction_id = $_POST['transaction_id'] ?? '';
    $current_year = date('Y');
    
    try {
        $stmt = $pdo->prepare("INSERT INTO payments 
            (student_id, amount, payment_date, payment_method, transaction_id, 
             academic_year, semester, payment_type, status, description) 
            VALUES (:student_id, :amount, CURDATE(), :method, :trans_id, 
                    :year, 1, 'tuition', 'completed', 'Semester Fee Payment')");
        
        $stmt->execute([
            'student_id' => $student_id,
            'amount' => $amount,
            'method' => $payment_method,
            'trans_id' => $transaction_id,
            'year' => $current_year
        ]);
        
        $_SESSION['success'] = "Payment of KES " . number_format($amount, 2) . " recorded successfully!";
        header('Location: fee-statement.php');
        exit();
        
    } catch (PDOException $e) {
        $error = "Payment failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Make Payment</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Add similar header and sidebar -->
    <div class="content-card" style="max-width: 600px; margin: 2rem auto;">
        <h2>Make Payment</h2>
        
        <?php if (isset($error)): ?>
            <div style="padding: 1rem; background-color: #fee2e2; border-radius: 8px; margin-bottom: 1rem; color: #991b1b;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Amount (KES)</label>
                <input type="number" name="amount" required min="100" step="0.01" 
                       style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 8px;">
            </div>
            
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Payment Method</label>
                <select name="payment_method" required 
                        style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="online">M-Pesa</option>
                    <option value="cash">Cash</option>
                    <option value="credit_card">Credit Card</option>
                </select>
            </div>
            
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Transaction ID (Optional)</label>
                <input type="text" name="transaction_id" 
                       style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 8px;">
            </div>
            
            <button type="submit" name="make_payment" 
                    style="width: 100%; padding: 1rem; background-color: #10b981; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                Submit Payment
            </button>
        </form>
    </div>
</body>
</html>