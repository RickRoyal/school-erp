<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

$student_id = $_SESSION['student_id'];

// Handle payment submission na the delivery
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

<header class="header">
        <div class="header-left">
            <div class="logo">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                    <rect width="40" height="40" rx="8" fill="#2563eb"/>
                    <text x="20" y="28" text-anchor="middle" fill="white" font-size="20" font-weight="bold">SM</text>
                </svg>
                <span>College ERP</span>
            </div>
        </div>
        <div class="header-right">
            <div class="notification-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
                <span class="badge">3</span>
            </div>
            <div class="user-avatar"><?php echo $initials; ?></div>
            <div class="user-info">
                <span class="user-name"><?php echo htmlspecialchars(strtoupper($first_name)); ?></span>
                <span class="user-role">Student</span>
            </div>
        </div>
    </header>


<aside class="sidebar">
            <nav class="nav-menu">
                <a href="dashboard.php" class="nav-item active">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <div class="nav-item dropdown-toggle" onclick="toggleDropdown('academics')">
                    <div class="dropdown-header">
                        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                        </svg>
                        <span>Academics</span>
                    </div>
                    <svg class="arrow" width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                    </svg>
                </div>
                <div id="academics-dropdown" class="dropdown-content">
                    <a href="unit-registration.php" class="dropdown-item">Unit Registration</a>
                    <a href="grades.php" class="dropdown-item">Grades</a>
                </div>

                <div class="nav-item dropdown-toggle" onclick="toggleDropdown('financials')">
                    <div class="dropdown-header">
                        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                            <line x1="1" y1="10" x2="23" y2="10"/>
                        </svg>
                        <span>Financials</span>
                    </div>
                    <svg class="arrow" width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                    </svg>
                </div>
                <div id="financials-dropdown" class="dropdown-content">
                    <a href="fee-structure.php" class="dropdown-item">Fee Structure</a>
                    <a href="fee-statement.php" class="dropdown-item">Fee Statement</a>
                </div>

                <a href="timetable.php" class="nav-item">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    <span>Timetable</span>
                </a>

                <a href="#" class="nav-item">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                    </svg>
                    <span>Settings</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="login.php" class="nav-item logout">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

    
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
</div>
    <script src="script.js"></script>
    <script>
        
        function switchTab(tabName) {
            
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => {
                content.classList.remove('active');
            });

            
            const tabButtons = document.querySelectorAll('.tab-btn');
            tabButtons.forEach(btn => {
                btn.classList.remove('active');
            });

            
            document.getElementById(tabName + '-tab').classList.add('active');
            
            
            event.target.classList.add('active');
        }
    </script>
</body>
</html>