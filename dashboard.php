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



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College ERP - Student Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Student-specific customizations */
        .student-welcome {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2.5rem;
            border-radius: 12px;
            color: white;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .student-welcome h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .student-name {
            color: #fbbf24;
            font-weight: 800;
        }

        .student-welcome p {
            font-size: 1rem;
            opacity: 0.95;
            margin-bottom: 0.5rem;
        }

        .student-id {
            font-size: 0.9rem;
            opacity: 0.85;
            font-weight: 500;
        }

        /* Fee Balance Alert */
        .fee-balance-alert {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            padding: 1.5rem;
            border-radius: 12px;
            color: white;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(245, 87, 108, 0.3);
            animation: pulse-glow 2s ease-in-out infinite;
        }

        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 4px 12px rgba(245, 87, 108, 0.3); }
            50% { box-shadow: 0 6px 20px rgba(245, 87, 108, 0.5); }
        }

        .fee-balance-content h3 {
            font-size: 1rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
            opacity: 0.95;
        }

        .fee-amount {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.25rem;
        }

        .fee-status {
            font-size: 0.85rem;
            opacity: 0.9;
        }

        .pay-now-btn {
            background-color: white;
            color: #f5576c;
            border: none;
            padding: 0.875rem 1.75rem;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.95rem;
            text-decoration: none;
        }

        .pay-now-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        /* Student Info Grid */
        .student-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-card {
            background-color: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            border-left: 4px solid;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .info-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.12);
        }

        .info-card.course {
            border-left-color: #3b82f6;
        }

        .info-card.semester {
            border-left-color: #10b981;
        }

        .info-card.attendance {
            border-left-color: #f59e0b;
        }

        .info-card.gpa {
            border-left-color: #8b5cf6;
        }

        .info-card-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .info-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .course .info-icon {
            background-color: #dbeafe;
            color: #3b82f6;
        }

        .semester .info-icon {
            background-color: #d1fae5;
            color: #10b981;
        }

        .attendance .info-icon {
            background-color: #fed7aa;
            color: #f59e0b;
        }

        .gpa .info-icon {
            background-color: #ede9fe;
            color: #8b5cf6;
        }

        .info-label {
            font-size: 0.875rem;
            color: #64748b;
            font-weight: 500;
        }

        .info-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .info-detail {
            font-size: 0.8rem;
            color: #64748b;
        }

        /* Quick Actions */
        .quick-actions {
            background-color: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
        }

        .quick-actions h2 {
            color: #1e293b;
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
            border-radius: 8px;
            border: 2px solid #e2e8f0;
            background-color: white;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            color: #334155;
            font-weight: 500;
        }

        .action-btn:hover {
            border-color: #2563eb;
            background-color: #eff6ff;
            transform: translateY(-2px);
        }

        .action-btn svg {
            width: 24px;
            height: 24px;
            color: #2563eb;
        }

        /* Recent Activity */
        .recent-activity {
            background-color: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }

        .recent-activity h2 {
            color: #1e293b;
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .activity-item {
            display: flex;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
            transition: background-color 0.2s;
        }

        .activity-item:hover {
            background-color: #f8fafc;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.25rem;
        }

        .activity-icon.exam { background-color: #fef3c7; }
        .activity-icon.assignment { background-color: #dbeafe; }
        .activity-icon.grade { background-color: #d1fae5; }
        .activity-icon.payment { background-color: #fce7f3; }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .activity-time {
            font-size: 0.8rem;
            color: #64748b;
        }
    </style>
</head>
<body>
    <!-- Header -->
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

    <div class="container">
        <!-- Sidebar -->
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

        <!-- Main Content -->
        <main class="main-content">
            <!-- Student Welcome Section -->
            <section class="student-welcome">
                <div>
                    <h1>Welcome back, <span class="student-name"><?php echo htmlspecialchars(strtoupper($full_name)); ?></span>! 👋</h1>
                    <p>Here's what's happening with your academic journey today</p>
                    <p class="student-id">Student ID: <strong>STU/<?php echo $current_year; ?>/<?php echo str_pad($student_id, 6, '0', STR_PAD_LEFT); ?></strong> | <strong><?php echo htmlspecialchars($course); ?></strong></p>
                </div>
            </section>

            <!-- Fee Balance Alert -->
            <section class="fee-balance-alert">
                <div class="fee-balance-content">
                    <h3>💳 Outstanding Fee Balance</h3>
                    <div class="fee-amount">KES <?php echo number_format($fee_balance, 0); ?></div>
                    <div class="fee-status">Due Date: December 15, 2025 | Semester <?php echo $current_semester; ?>, <?php echo $current_year; ?></div>
                </div>
                <a href="fee-statement.php" class="pay-now-btn">VIEW STATEMENT</a>
            </section>

            <!-- Student Info Grid -->
            <section class="student-info-grid">
                <div class="info-card course">
                    <div class="info-card-header">
                        <div class="info-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                            </svg>
                        </div>
                        <span class="info-label">Current Course</span>
                    </div>
                    <div class="info-value"><?php echo htmlspecialchars($course); ?></div>
                    <div class="info-detail">Year 2 • Full Time</div>
                </div>

                <div class="info-card semester">
                    <div class="info-card-header">
                        <div class="info-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                        </div>
                        <span class="info-label">Current Semester</span>
                    </div>
                    <div class="info-value">Semester <?php echo $current_semester; ?></div>
                    <div class="info-detail">Academic Year <?php echo ($current_year-1) . '/' . $current_year; ?></div>
                </div>

                <div class="info-card attendance">
                    <div class="info-card-header">
                        <div class="info-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                        </div>
                        <span class="info-label">Attendance Rate</span>
                    </div>
                    <div class="info-value">92%</div>
                    <div class="info-detail">Excellent Performance</div>
                </div>

                <div class="info-card gpa">
                    <div class="info-card-header">
                        <div class="info-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                        <span class="info-label">Current GPA</span>
                    </div>
                    <div class="info-value"><?php echo $current_gpa; ?></div>
                    <div class="info-detail">First Class Honours</div>
                </div>
            </section>

            <!-- Quick Actions -->
            <section class="quick-actions">
                <h2>Quick Actions</h2>
                <div class="action-buttons">
                    <a href="unit-registration.php" class="action-btn">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Register Units</span>
                    </a>
                    <a href="grades.php" class="action-btn">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <span>View Grades</span>
                    </a>
                    <a href="fee-statement.php" class="action-btn">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                            <line x1="1" y1="10" x2="23" y2="10"/>
                        </svg>
                        <span>Fee Statement</span>
                    </a>
                    <a href="timetable.php" class="action-btn">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                        </svg>
                        <span>View Timetable</span>
                    </a>
                </div>
            </section>

            <!-- Recent Activity -->
            <section class="recent-activity">
                <h2>Recent Activity</h2>
                <div class="activity-item">
                    <div class="activity-icon exam">📝</div>
                    <div class="activity-content">
                        <div class="activity-title">Upcoming Exam: Database Systems</div>
                        <div class="activity-time">Scheduled for November 15, 2025 at 9:00 AM</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon assignment">📄</div>
                    <div class="activity-content">
                        <div class="activity-title">Assignment Submitted: Data Structures Project</div>
                        <div class="activity-time">2 days ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon grade">⭐</div>
                    <div class="activity-content">
                        <div class="activity-title">New Grade Posted: Web Development (A-)</div>
                        <div class="activity-time">5 days ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon payment">💳</div>
                    <div class="activity-content">
                        <div class="activity-title">Payment Received: KES 25,000</div>
                        <div class="activity-time">1 week ago</div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="script.js"></script>
</body>
</html>