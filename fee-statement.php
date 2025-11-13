<?php
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


$student_stmt = $pdo->prepare("
    SELECT s.*, d.department_name 
    FROM students s 
    LEFT JOIN departments d ON s.department_id = d.department_id 
    WHERE s.student_id = :student_id
");
$student_stmt->execute(['student_id' => $student_id]);
$student_data = $student_stmt->fetch();


$program = $student_data['department_name'] ?? 'Not Assigned';


$current_year = date('Y');
$enrollment_year = $student_data['enrollment_date'] ? date('Y', strtotime($student_data['enrollment_date'])) : $current_year;
$admission_number = "STM/" . $enrollment_year . "/" . str_pad($student_id, 4, '0', STR_PAD_LEFT);


$years_enrolled = $current_year - $enrollment_year;
$year_of_study = "Year " . max(1, $years_enrolled + 1);


$stmt = $pdo->prepare("SELECT * FROM payments WHERE student_id = :student_id ORDER BY payment_date DESC");
$stmt->execute(['student_id' => $student_id]);
$payments = $stmt->fetchAll();


$total_paid = 0;
foreach ($payments as $payment) {
    if ($payment['status'] == 'completed') {
        $total_paid += $payment['amount'];
    }
}

$semester_fee = 70000;
$balance = $semester_fee - $total_paid;


$semesters_enrolled = max(1, ($years_enrolled * 2) + 1);
$total_charged = $semester_fee * $semesters_enrolled;


$success_message = $_SESSION['success'] ?? null;
unset($_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Statement - College ERP</title>
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
            <div class="user-profile">
                <div class="user-avatar"><?php echo $initials; ?></div>
                <div class="user-info">
                    <span class="user-name"><?php echo strtoupper($first_name); ?></span>
                    <span class="user-role">Student</span>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
    
        <aside class="sidebar">
            <nav class="nav-menu">
                <a href="dashboard.php" class="nav-item">
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
                        <path d="M4.293 5.293a1 1 0 011.414 0L8 7.586l2.293-2.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"/>
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
                        <path d="M4.293 5.293a1 1 0 011.414 0L8 7.586l2.293-2.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"/>
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
                <a href="logout.php" class="nav-item logout">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        
        <main class="main-content">
            <div class="content-card">
                <h2>Fee Statement</h2>
                <p>Your current fee balance and complete payment history.</p>
                
                <?php if ($success_message): ?>
                    <div style="padding: 1rem; background-color: #d1fae5; border-left: 4px solid #10b981; border-radius: 8px; margin: 1rem 0; color: #065f46;">
                        ✓ <?php echo htmlspecialchars($success_message); ?>
                    </div>
                <?php endif; ?>
                
                
                <div style="margin: 1.5rem 0; padding: 1rem; background-color: #f8fafc; border-radius: 8px;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        <div>
                            <strong style="color: #64748b; font-size: 0.875rem;">Student Name:</strong><br>
                            <span style="color: #1e293b; font-weight: 500;"><?php echo htmlspecialchars(strtoupper($full_name)); ?></span>
                        </div>
                        <div>
                            <strong style="color: #64748b; font-size: 0.875rem;">Admission Number:</strong><br>
                            <span style="color: #1e293b; font-weight: 500;"><?php echo $admission_number; ?></span>
                        </div>
                        <div>
                            <strong style="color: #64748b; font-size: 0.875rem;">Program:</strong><br>
                            <span style="color: #1e293b; font-weight: 500;">BSc <?php echo htmlspecialchars($program); ?></span>
                        </div>
                        <div>
                            <strong style="color: #64748b; font-size: 0.875rem;">Year of Study:</strong><br>
                            <span style="color: #1e293b; font-weight: 500;"><?php echo $year_of_study; ?></span>
                        </div>
                    </div>
                </div>

                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin: 2rem 0;">
                    <div style="padding: 2rem; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 12px; color: white; box-shadow: 0 4px 6px rgba(239, 68, 68, 0.2);">
                        <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.5rem;">Current Balance</div>
                        <div style="font-size: 2.5rem; font-weight: 700; margin-bottom: 0.5rem;">KES <?php echo number_format($balance, 0); ?></div>
                        <div style="font-size: 0.875rem; opacity: 0.9;">Due: November 30, <?php echo $current_year; ?></div>
                    </div>
                    
                    <div style="padding: 2rem; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 12px; color: white; box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2);">
                        <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.5rem;">Total Paid</div>
                        <div style="font-size: 2.5rem; font-weight: 700; margin-bottom: 0.5rem;">KES <?php echo number_format($total_paid, 0); ?></div>
                        <div style="font-size: 0.875rem; opacity: 0.9;">All time payments</div>
                    </div>
                    
                    <div style="padding: 2rem; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 12px; color: white; box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2);">
                        <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.5rem;">Total Charged</div>
                        <div style="font-size: 2.5rem; font-weight: 700; margin-bottom: 0.5rem;">KES <?php echo number_format($total_charged, 0); ?></div>
                        <div style="font-size: 0.875rem; opacity: 0.9;"><?php echo $semesters_enrolled; ?> semester<?php echo $semesters_enrolled > 1 ? 's' : ''; ?></div>
                    </div>
                </div>

                <!-- Transaction History -->
                <h3 style="margin-top: 2.5rem; margin-bottom: 1rem; color: #1e293b; font-size: 1.25rem;">Transaction History</h3>
                
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($payments)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 2rem; color: #64748b;">
                                    No payments recorded yet
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($payments as $payment): ?>
                                <tr>
                                    <td><?php echo date('d M Y', strtotime($payment['payment_date'])); ?></td>
                                    <td><?php echo htmlspecialchars($payment['description'] ?? 'Tuition Fee'); ?></td>
                                    <td>KES <?php echo number_format($payment['amount'], 2); ?></td>
                                    <td><?php echo ucfirst(str_replace('_', ' ', $payment['payment_method'])); ?></td>
                                    <td>
                                        <span style="padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 0.85rem; 
                                              background-color: <?php echo $payment['status'] == 'completed' ? '#d1fae5' : '#fee2e2'; ?>;
                                              color: <?php echo $payment['status'] == 'completed' ? '#059669' : '#dc2626'; ?>;">
                                            <?php echo ucfirst($payment['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

                <?php if ($balance > 0): ?>
                
                <div style="margin-top: 2rem; padding: 1.5rem; background-color: #fef2f2; border-left: 4px solid #ef4444; border-radius: 8px;">
                    <h4 style="color: #991b1b; margin-bottom: 0.75rem; font-size: 1.1rem;">⚠️ Payment Reminder</h4>
                    <p style="color: #7f1d1d; line-height: 1.6;">
                        You have an outstanding balance of <strong>KES <?php echo number_format($balance, 0); ?></strong> due by <strong>November 30, <?php echo $current_year; ?></strong>. 
                        Please make payment to avoid late payment penalties and ensure uninterrupted access to academic services.
                    </p>
                </div>
                <?php endif; ?>

                
                <div style="margin-top: 2rem; display: flex; gap: 1rem; flex-wrap: wrap;">
                    <button 
                        style="padding: 0.875rem 1.5rem; background-color: #10b981; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background-color 0.2s;"
                        onmouseover="this.style.backgroundColor='#059669'" 
                        onmouseout="this.style.backgroundColor='#10b981'" 
                        onclick="window.location.href='make-payment.php'">
                        Make Payment
                    </button>

                    <button style="padding: 0.875rem 1.5rem; background-color: #2563eb; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background-color 0.2s;" 
                            onmouseover="this.style.backgroundColor='#1e40af'" 
                            onmouseout="this.style.backgroundColor='#2563eb'"
                            onclick="window.print()">
                        Download Statement
                    </button>
                    <button style="padding: 0.875rem 1.5rem; background-color: white; color: #64748b; border: 2px solid #e2e8f0; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s;" 
                            onmouseover="this.style.borderColor='#cbd5e1'; this.style.color='#475569'" 
                            onmouseout="this.style.borderColor='#e2e8f0'; this.style.color='#64748b'"
                            onclick="window.print()">
                        Print Statement
                    </button>
                </div>
            </div>
        </main>
    </div>

    <script src="script.js"></script>
</body>
</html>