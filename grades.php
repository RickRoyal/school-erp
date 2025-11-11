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

// Fetch grades
$stmt = $pdo->prepare("
    SELECT g.*, u.unit_name, u.unit_code, u.credits 
    FROM grades g 
    INNER JOIN units u ON g.unit_id = u.unit_id 
    WHERE g.student_id = :student_id 
    ORDER BY g.academic_year DESC, g.semester DESC
");
$stmt->execute(['student_id' => $student_id]);
$grades = $stmt->fetchAll();

// Calculate GPA
$gpa_stmt = $pdo->prepare("SELECT AVG(grade_points) as avg_gpa FROM grades WHERE student_id = :student_id AND status = 'pass'");
$gpa_stmt->execute(['student_id' => $student_id]);
$gpa_data = $gpa_stmt->fetch();
$current_gpa = $gpa_data['avg_gpa'] ? number_format($gpa_data['avg_gpa'], 2) : '0.00';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grades - College ERP</title>
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
                <a href="#" class="nav-item logout">
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
                <h2>Academic Results & Grades</h2>
                <p>View your examination and coursework results for all semesters.</p>
                
             
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin: 2rem 0;">
                    <div style="padding: 1.5rem; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 12px; color: white;">
                        <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.5rem;">Cumulative GPA</div>
                        <div style="font-size: 2.5rem; font-weight: 700;">3.45</div>
                    </div>
                    <div style="padding: 1.5rem; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 12px; color: white;">
                        <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.5rem;">Semester GPA</div>
                        <div style="font-size: 2.5rem; font-weight: 700;">3.67</div>
                    </div>
                    <div style="padding: 1.5rem; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 12px; color: white;">
                        <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.5rem;">Total Credits</div>
                        <div style="font-size: 2.5rem; font-weight: 700;">48</div>
                    </div>
                </div>

                
                <h3 style="margin-top: 2rem; margin-bottom: 1rem; color: #1e293b; font-size: 1.25rem;">Semester 1, 2025/2026</h3>

                <div class="content-card">
    <h2>Academic Performance</h2>
    <p><strong>Current GPA:</strong> <?php echo $current_gpa; ?></p>
    
    <table>
        <thead>
            <tr>
                <th>Unit Code</th>
                <th>Unit Name</th>
                <th>Credits</th>
                <th>Marks</th>
                <th>Grade</th>
                <th>Points</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($grades)): ?>
                <tr>
                    <td colspan="7" style="text-align: center;">No grades available yet</td>
                </tr>
            <?php else: ?>
                <?php foreach ($grades as $grade): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($grade['unit_code']); ?></td>
                        <td><?php echo htmlspecialchars($grade['unit_name']); ?></td>
                        <td><?php echo $grade['credits']; ?></td>
                        <td><?php echo $grade['marks']; ?></td>
                        <td><strong><?php echo $grade['grade']; ?></strong></td>
                        <td><?php echo $grade['grade_points']; ?></td>
                        <td>
                            <span style="padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 0.85rem;
                                  background-color: <?php echo $grade['status'] == 'pass' ? '#d1fae5' : '#fee'; ?>;
                                  color: <?php echo $grade['status'] == 'pass' ? '#059669' : '#c33'; ?>;">
                                <?php echo ucfirst($grade['status']); ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

                <!-- Actions -->
                <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                    <button style="padding: 0.875rem 1.5rem; background-color: #2563eb; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#1e40af'" onmouseout="this.style.backgroundColor='#2563eb'">
                        Download Transcript
                    </button>
                    <button style="padding: 0.875rem 1.5rem; background-color: white; color: #64748b; border: 2px solid #e2e8f0; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.borderColor='#cbd5e1'; this.style.color='#475569'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.color='#64748b'">
                        Print Results
                    </button>
                </div>
            </div>
        </main>
    </div>

    <script src="script.js"></script>
</body>
</html>