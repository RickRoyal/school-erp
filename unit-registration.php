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
$initials = strtoupper(substr($first_name, 0, 1) . substr($last_name, 0, 1));

// Get current academic year and semester
$current_year = date('Y');
$current_semester = 1; // You can make this dynamic

// Handle unit registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register_units'])) {
    $selected_units = $_POST['units'] ?? [];
    
    if (!empty($selected_units)) {
        try {
            $pdo->beginTransaction();
            
            foreach ($selected_units as $unit_id) {
                // Check if already registered
                $check_stmt = $pdo->prepare("SELECT registration_id FROM registrations 
                    WHERE student_id = :student_id AND unit_id = :unit_id 
                    AND academic_year = :year AND semester = :semester");
                $check_stmt->execute([
                    'student_id' => $student_id,
                    'unit_id' => $unit_id,
                    'year' => $current_year,
                    'semester' => $current_semester
                ]);
                
                if ($check_stmt->rowCount() == 0) {
                    // Register the unit
                    $insert_stmt = $pdo->prepare("INSERT INTO registrations 
                        (student_id, unit_id, academic_year, semester, registration_date, status) 
                        VALUES (:student_id, :unit_id, :year, :semester, CURDATE(), 'registered')");
                    $insert_stmt->execute([
                        'student_id' => $student_id,
                        'unit_id' => $unit_id,
                        'year' => $current_year,
                        'semester' => $current_semester
                    ]);
                }
            }
            
            $pdo->commit();
            $success = "Units registered successfully!";
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = "Registration failed: " . $e->getMessage();
        }
    } else {
        $error = "Please select at least one unit.";
    }
}

// Get student's department
$dept_stmt = $pdo->prepare("SELECT department_id FROM students WHERE student_id = :student_id");
$dept_stmt->execute(['student_id' => $student_id]);
$student_dept = $dept_stmt->fetch();
$department_id = $student_dept['department_id'] ?? 1;

// Get available units for this department and semester
$units_stmt = $pdo->prepare("SELECT * FROM units 
    WHERE department_id = :dept_id AND semester = :semester 
    ORDER BY unit_code");
$units_stmt->execute([
    'dept_id' => $department_id,
    'semester' => $current_semester
]);
$available_units = $units_stmt->fetchAll();

// Get already registered units
$registered_stmt = $pdo->prepare("SELECT unit_id FROM registrations 
    WHERE student_id = :student_id AND academic_year = :year AND semester = :semester");
$registered_stmt->execute([
    'student_id' => $student_id,
    'year' => $current_year,
    'semester' => $current_semester
]);
$registered_units = $registered_stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unit Registration - College ERP</title>
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
        <!-- Include the same sidebar from dashboard -->
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
                <h2>Unit Registration</h2>
                <p>Register for your courses and units for the current semester.</p>
                
                <?php if (isset($success)): ?>
                    <div style="padding: 1rem; background-color: #d1fae5; border-left: 4px solid #10b981; border-radius: 8px; margin: 1rem 0; color: #065f46;">
                        ✓ <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                    <div style="padding: 1rem; background-color: #fee2e2; border-left: 4px solid #ef4444; border-radius: 8px; margin: 1rem 0; color: #991b1b;">
                        ✗ <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <div style="margin: 1.5rem 0; padding: 1rem; background-color: #eff6ff; border-left: 4px solid #3b82f6; border-radius: 8px;">
                    <strong style="color: #1e40af;">Academic Year:</strong> <?php echo ($current_year-1) . '/' . $current_year; ?><br>
                    <strong style="color: #1e40af;">Semester:</strong> <?php echo $current_semester; ?><br>
                    <strong style="color: #1e40af;">Registration Period:</strong> Open
                </div>

                <form method="POST">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 60px;">Select</th>
                                <th>Unit Code</th>
                                <th>Unit Name</th>
                                <th>Credits</th>
                                <th>Lecturer</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($available_units)): ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 2rem; color: #64748b;">
                                        No units available for registration. Please contact administration.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($available_units as $unit): ?>
                                    <?php $is_registered = in_array($unit['unit_id'], $registered_units); ?>
                                    <tr>
                                        <td style="text-align: center;">
                                            <input type="checkbox" 
                                                   name="units[]" 
                                                   value="<?php echo $unit['unit_id']; ?>"
                                                   <?php echo $is_registered ? 'checked disabled' : ''; ?>>
                                        </td>
                                        <td><?php echo htmlspecialchars($unit['unit_code']); ?></td>
                                        <td><?php echo htmlspecialchars($unit['unit_name']); ?></td>
                                        <td><?php echo $unit['credits']; ?></td>
                                        <td><?php echo htmlspecialchars($unit['lecturer']); ?></td>
                                        <td>
                                            <?php if ($is_registered): ?>
                                                <span style="color: #10b981; font-weight: 600;">✓ Registered</span>
                                            <?php else: ?>
                                                <span style="color: #3b82f6; font-weight: 600;">Available</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <?php if (!empty($available_units)): ?>
                        <div style="margin-top: 2rem; padding: 1.5rem; background-color: #f8fafc; border-radius: 8px;">
                            <div style="display: flex; gap: 1rem;">
                                <button type="submit" name="register_units" style="flex: 1; padding: 0.875rem; background-color: #2563eb; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#1e40af'" onmouseout="this.style.backgroundColor='#2563eb'">
                                    Register Selected Units
                                </button>
                                <a href="dashboard.php" style="flex: 1; padding: 0.875rem; background-color: white; color: #64748b; border: 2px solid #e2e8f0; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s; text-align: center; text-decoration: none; display: block;" onmouseover="this.style.borderColor='#cbd5e1'; this.style.color='#475569'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.color='#64748b'">
                                    Back to Dashboard
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </main>
    </div>

    <script src="script.js"></script>
</body>
</html>