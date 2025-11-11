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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timetable - College ERP</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .timetable-tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .tab-btn {
            padding: 1rem 2rem;
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            color: #64748b;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 1rem;
        }
        
        .tab-btn:hover {
            color: #2563eb;
            background-color: #f8fafc;
        }
        
        .tab-btn.active {
            color: #2563eb;
            border-bottom-color: #2563eb;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        
        .timetable-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        .timetable-table th {
            background-color: #2563eb;
            color: white;
            padding: 1rem;
            text-align: center;
            font-weight: 600;
        }
        
        .timetable-table td {
            padding: 1rem;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        
        .time-slot {
            background-color: #f8fafc;
            font-weight: 600;
            color: #475569;
            text-align: center;
        }
        
        .class-block {
            background-color: #eff6ff;
            padding: 0.75rem;
            border-radius: 6px;
            border-left: 4px solid #2563eb;
            margin-bottom: 0.5rem;
        }
        
        .class-block:last-child {
            margin-bottom: 0;
        }
        
        .class-code {
            font-weight: 700;
            color: #1e40af;
            font-size: 0.875rem;
        }
        
        .class-name {
            font-weight: 500;
            color: #1e293b;
            margin: 0.25rem 0;
        }
        
        .class-venue {
            font-size: 0.875rem;
            color: #64748b;
        }
        
        .exam-block {
            background-color: #fef3c7;
            padding: 1rem;
            border-radius: 6px;
            border-left: 4px solid #f59e0b;
            margin-bottom: 1rem;
        }
        
        .exam-date {
            font-weight: 700;
            color: #92400e;
            font-size: 1rem;
        }
    </style>
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
                <h2>Academic Timetable</h2>
                <p>View your class schedule and examination timetable for the current semester.</p>
                
                
                <div class="timetable-tabs">
                    <button class="tab-btn active" onclick="switchTab('coursework')">
                        📚 Coursework Timetable
                    </button>
                    <button class="tab-btn" onclick="switchTab('exam')">
                        📝 Exam Timetable
                    </button>
                </div>

               
                <div id="coursework-tab" class="tab-content active">
                    <div style="margin-bottom: 1.5rem; padding: 1rem; background-color: #eff6ff; border-left: 4px solid #3b82f6; border-radius: 8px;">
                        <strong style="color: #1e40af;">Semester:</strong> 2025/2026 - Semester 1<br>
                        <strong style="color: #1e40af;">Effective:</strong> October 1 - December 20, 2025
                    </div>

                    <table class="timetable-table">
                        <thead>
                            <tr>
                                <th style="width: 120px;">Time</th>
                                <th>Monday</th>
                                <th>Tuesday</th>
                                <th>Wednesday</th>
                                <th>Thursday</th>
                                <th>Friday</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="time-slot">8:00 - 10:00</td>
                                <td>
                                    <div class="class-block">
                                        <div class="class-code">COMP101</div>
                                        <div class="class-name">Introduction to Programming</div>
                                        <div class="class-venue">📍 Lab 1 | Dr. Jane Smith</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="class-block">
                                        <div class="class-code">MATH102</div>
                                        <div class="class-name">Calculus I</div>
                                        <div class="class-venue">📍 Room 204 | Prof. John Doe</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="class-block">
                                        <div class="class-code">COMP101</div>
                                        <div class="class-name">Introduction to Programming</div>
                                        <div class="class-venue">📍 Lab 1 | Dr. Jane Smith</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="class-block">
                                        <div class="class-code">PHYS104</div>
                                        <div class="class-name">Physics for Engineers</div>
                                        <div class="class-venue">📍 Lab 3 | Dr. Robert Brown</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="class-block">
                                        <div class="class-code">ENG103</div>
                                        <div class="class-name">Technical Writing</div>
                                        <div class="class-venue">📍 Room 101 | Dr. Mary Johnson</div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="time-slot">10:00 - 10:30</td>
                                <td colspan="5" style="background-color: #f8fafc; text-align: center; color: #64748b; font-weight: 500;">☕ Break</td>
                            </tr>
                            <tr>
                                <td class="time-slot">10:30 - 12:30</td>
                                <td>
                                    <div class="class-block">
                                        <div class="class-code">MATH102</div>
                                        <div class="class-name">Calculus I</div>
                                        <div class="class-venue">📍 Room 204 | Prof. John Doe</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="class-block">
                                        <div class="class-code">ENG103</div>
                                        <div class="class-name">Technical Writing</div>
                                        <div class="class-venue">📍 Room 101 | Dr. Mary Johnson</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="class-block">
                                        <div class="class-code">MATH102</div>
                                        <div class="class-name">Calculus I</div>
                                        <div class="class-venue">📍 Room 204 | Prof. John Doe</div>
                                    </div>
                                </td>
                                <td></td>
                                <td>
                                    <div class="class-block">
                                        <div class="class-code">PHYS104</div>
                                        <div class="class-name">Physics for Engineers</div>
                                        <div class="class-venue">📍 Room 305 | Dr. Robert Brown</div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="time-slot">12:30 - 2:00</td>
                                <td colspan="5" style="background-color: #f8fafc; text-align: center; color: #64748b; font-weight: 500;">🍽️ Lunch Break</td>
                            </tr>
                            <tr>
                                <td class="time-slot">2:00 - 4:00</td>
                                <td></td>
                                <td>
                                    <div class="class-block">
                                        <div class="class-code">PHYS104</div>
                                        <div class="class-name">Physics Lab</div>
                                        <div class="class-venue">📍 Lab 3 | Dr. Robert Brown</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="class-block">
                                        <div class="class-code">COMP101</div>
                                        <div class="class-name">Programming Lab</div>
                                        <div class="class-venue">📍 Lab 1 | Dr. Jane Smith</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="class-block">
                                        <div class="class-code">ENG103</div>
                                        <div class="class-name">Technical Writing</div>
                                        <div class="class-venue">📍 Room 101 | Dr. Mary Johnson</div>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>

                    <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                        <button style="padding: 0.875rem 1.5rem; background-color: #2563eb; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#1e40af'" onmouseout="this.style.backgroundColor='#2563eb'">
                            Download Timetable
                        </button>
                        <button style="padding: 0.875rem 1.5rem; background-color: white; color: #64748b; border: 2px solid #e2e8f0; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.borderColor='#cbd5e1'; this.style.color='#475569'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.color='#64748b'">
                            Print Timetable
                        </button>
                    </div>
                </div>

                <div id="exam-tab" class="tab-content">
                    <div style="margin-bottom: 1.5rem; padding: 1rem; background-color: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 8px;">
                        <strong style="color: #92400e;">Examination Period:</strong> December 1 - December 15, 2025<br>
                        <strong style="color: #92400e;">Important:</strong> Arrive 30 minutes before exam time
                    </div>

                    <div class="exam-block">
                        <div class="exam-date">Monday, December 1, 2025</div>
                        <div style="margin-top: 0.75rem;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <div>
                                    <strong style="color: #1e293b;">COMP101 - Introduction to Programming</strong><br>
                                    <span style="color: #64748b; font-size: 0.875rem;">Dr. Jane Smith</span>
                                </div>
                                <div style="text-align: right;">
                                    <strong style="color: #1e293b;">9:00 AM - 12:00 PM</strong><br>
                                    <span style="color: #64748b; font-size: 0.875rem;">📍 Exam Hall A</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="exam-block">
                        <div class="exam-date">Wednesday, December 3, 2025</div>
                        <div style="margin-top: 0.75rem;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <div>
                                    <strong style="color: #1e293b;">MATH102 - Calculus I</strong><br>
                                    <span style="color: #64748b; font-size: 0.875rem;">Prof. John Doe</span>
                                </div>
                                <div style="text-align: right;">
                                    <strong style="color: #1e293b;">9:00 AM - 12:00 PM</strong><br>
                                    <span style="color: #64748b; font-size: 0.875rem;">📍 Exam Hall B</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="exam-block">
                        <div class="exam-date">Friday, December 5, 2025</div>
                        <div style="margin-top: 0.75rem;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <div>
                                    <strong style="color: #1e293b;">ENG103 - Technical Writing</strong><br>
                                    <span style="color: #64748b; font-size: 0.875rem;">Dr. Mary Johnson</span>
                                </div>
                                <div style="text-align: right;">
                                    <strong style="color: #1e293b;">2:00 PM - 4:00 PM</strong><br>
                                    <span style="color: #64748b; font-size: 0.875rem;">📍 Exam Hall A</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="exam-block">
                        <div class="exam-date">Monday, December 8, 2025</div>
                        <div style="margin-top: 0.75rem;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <div>
                                    <strong style="color: #1e293b;">PHYS104 - Physics for Engineers</strong><br>
                                    <span style="color: #64748b; font-size: 0.875rem;">Dr. Robert Brown</span>
                                </div>
                                <div style="text-align: right;">
                                    <strong style="color: #1e293b;">9:00 AM - 12:00 PM</strong><br>
                                    <span style="color: #64748b; font-size: 0.875rem;">📍 Exam Hall C</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 2rem; padding: 1.5rem; background-color: #fef2f2; border-left: 4px solid #ef4444; border-radius: 8px;">
                        <h4 style="color: #991b1b; margin-bottom: 0.75rem; font-size: 1rem;">📋 Exam Regulations</h4>
                        <ul style="color: #7f1d1d; line-height: 1.8; margin-left: 1.5rem;">
                            <li>Students must carry their valid student ID card</li>
                            <li>No electronic devices allowed except approved calculators</li>
                            <li>Arrive at least 30 minutes before the exam starts</li>
                            <li>Late arrivals after 30 minutes will not be permitted</li>
                            <li>No borrowing of stationery during the examination</li>
                        </ul>
                    </div>

                    <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                        <button style="padding: 0.875rem 1.5rem; background-color: #2563eb; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#1e40af'" onmouseout="this.style.backgroundColor='#2563eb'">
                            Download Exam Timetable
                        </button>
                        <button style="padding: 0.875rem 1.5rem; background-color: white; color: #64748b; border: 2px solid #e2e8f0; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.borderColor='#cbd5e1'; this.style.color='#475569'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.color='#64748b'">
                            Print Exam Timetable
                        </button>
                    </div>
                </div>
            </div>
        </main>
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