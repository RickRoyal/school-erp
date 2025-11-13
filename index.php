<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College ERP - Streamline Campus Life</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        /* Navigation */
        nav {
            background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
            text-decoration: none;
        }

        .logo svg {
            width: 40px;
            height: 40px;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.3s;
        }

        .nav-links a:hover {
            opacity: 0.8;
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.6rem 1.5rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-login {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-login:hover {
            background: white;
            color: #0891b2;
        }

        .btn-signup {
            background: #1e293b;
            color: white;
        }

        .btn-signup:hover {
            background: #0f172a;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #1e3a5f 0%, #2d5a7b 100%);
            padding: 8rem 2rem 6rem;
            text-align: center;
            color: white;
            margin-top: 70px;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.05" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,165.3C1248,171,1344,149,1392,138.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: cover;
            opacity: 0.3;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }

        .btn-cta {
            background: #06b6d4;
            color: white;
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            display: inline-block;
        }

        .btn-cta:hover {
            background: #0891b2;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(8, 145, 178, 0.4);
        }

        /* Features Section */
        .features {
            padding: 5rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .feature-card {
            background: white;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
            transition: all 0.3s;
            border: 1px solid #e5e7eb;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.15);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .feature-icon svg {
            width: 40px;
            height: 40px;
            color: white;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #1e293b;
        }

        .feature-card p {
            color: #64748b;
            line-height: 1.8;
        }

        
        .key-features {
            background: #f8fafc;
            padding: 5rem 2rem;
        }

        .key-features-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .key-features h2 {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 3rem;
            color: #1e293b;
        }

        .features-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .feature-check {
            width: 24px;
            height: 24px;
            background: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 3px;
        }

        .feature-check svg {
            width: 16px;
            height: 16px;
            color: white;
        }

        .feature-item h4 {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            color: #1e293b;
        }

        .feature-item p {
            color: #64748b;
            font-size: 0.95rem;
        }

        
        .cta-section {
            background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
            padding: 5rem 2rem;
            text-align: center;
            color: white;
        }

        .cta-section h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .cta-section p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }

        .btn-cta-white {
            background: white;
            color: #0891b2;
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            display: inline-block;
        }

        .btn-cta-white:hover {
            background: #f0f9ff;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 255, 255, 0.3);
        }

        
        footer {
            background: #1e293b;
            color: white;
            padding: 3rem 2rem 1.5rem;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 3rem;
            margin-bottom: 2rem;
        }

        .footer-section h3 {
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 0.5rem;
        }

        .footer-section a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-section a:hover {
            color: #06b6d4;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            background: #334155;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .social-links a:hover {
            background: #06b6d4;
            transform: translateY(-3px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid #334155;
            color: #94a3b8;
        }

        
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 1.1rem;
            }

            .features-grid,
            .features-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
   
    <nav>
        <div class="nav-container">
            <a href="#" class="logo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                </svg>
                <span>College ERP</span>
            </a>
            
            <div class="nav-links">
                <a href="#features">Features</a>
                <a href="#about">About</a>
                <a href="#contact">Contact</a>
            </div>

            <div class="nav-buttons">
                <a href="login.php" class="btn btn-login">Login</a>
                <a href="signup.php" class="btn btn-signup">Sign Up</a>
            </div>
        </div>
    </nav>

    
    <section class="hero">
        <div class="hero-content">
            <h1>Streamline Campus Life with Smart Technology</h1>
            <p>Empower your institution with our comprehensive ERP solution. Manage academics, finances, and student services all in one place.</p>
            <a href="signup.php" class="btn btn-cta">Get Started Today</a>
        </div>
    </section>

    
    <section class="features" id="features">
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3>Academic Management</h3>
                <p>Streamline course registration, grade tracking, and academic performance monitoring. Complete control over your educational journey.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3>Financial Oversight</h3>
                <p>Track tuition payments, manage fee structures, and monitor outstanding balances with real-time financial reporting and alerts.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h3>Student Hub</h3>
                <p>Personalized dashboard for each student with quick access to grades, schedules, payments, and important announcements.</p>
            </div>
        </div>
    </section>


    <section class="key-features">
        <div class="key-features-container">
            <h2>Key Features</h2>
            <div class="features-list">
                <div class="feature-item">
                    <div class="feature-check">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <h4>Secure Login & Registration</h4>
                        <p>Industry-standard authentication with encrypted password storage</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-check">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <h4>Unit Registration & Grades</h4>
                        <p>Easy course enrollment and comprehensive grade management</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-check">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <h4>Fee Structure & Payments</h4>
                        <p>Transparent fee breakdown with detailed payment history</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-check">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <h4>Student Dashboard with GPA</h4>
                        <p>Real-time academic performance tracking and GPA calculation</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-check">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <h4>Real-Time Fee Alerts</h4>
                        <p>Never miss a payment deadline with automated balance notifications</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-check">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <h4>Timetable Management</h4>
                        <p>View and organize class schedules with ease</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <section class="cta-section">
        <h2>Ready to Transform Your Campus?</h2>
        <p>Join hundreds of institutions already using our ERP system</p>
        <a href="signup.php" class="btn btn-cta-white">Get Started Today</a>
    </section>

    
    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h3>College ERP</h3>
                <p>Streamlining campus management with smart technology solutions.</p>
                <div class="social-links">
                    <a href="#" aria-label="Twitter">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/>
                        </svg>
                    </a>
                    <a href="#" aria-label="Facebook">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
                        </svg>
                    </a>
                    <a href="#" aria-label="LinkedIn">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"/>
                            <circle cx="4" cy="4" r="2"/>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="footer-section">
                <h3>Features</h3>
                <ul>
                    <li><a href="#features">Academic Management</a></li>
                    <li><a href="#features">Financial Oversight</a></li>
                    <li><a href="#features">Student Dashboard</a></li>
                    <li><a href="#features">Timetable</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Company</h3>
                <ul>
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#contact">Contact</a></li>
                    <li><a href="#troubleshooting">Troubleshooting</a></li>
                    <li><a href="#security">Security</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Support</h3>
                <ul>
                    <li><a href="#help">Help Center</a></li>
                    <li><a href="#docs">Documentation</a></li>
                    <li><a href="#terms">Terms of Service</a></li>
                    <li><a href="#privacy">Privacy Policy</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2025 College ERP System. All rights reserved. Built with ❤️ for educational institutions.</p>
        </div>
    </footer>
</body>
</html>