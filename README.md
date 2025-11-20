# 🎓 College ERP System

A comprehensive web-based ERP system for managing students, academics, and finances in educational institutions.


![html,css,php,mysql,github](https://skillicons.dev/icons?i=html,css,php,mysql,github)

[![Live Site](https://img.shields.io/badge/Visit-Live%20Website-brightgreen)](https://campusflows.infinityfreeapp.com/)

## ✨ Features

- 🔐 Secure login and registration system
- 📚 Unit registration and grades management
- 💰 Fee structure and payment tracking
- 📊 Student dashboard with GPA calculation
- 📅 Timetable management
- 🎯 Real-time fee balance alerts

## 🚀 Quick Start

### Requirements
- XAMPP/LAMPP (Apache + MySQL + PHP 7.4+)
- Modern web browser

### Installation

1. **Start XAMPP**
```bash
sudo /opt/lampp/lampp start
```

2. **Setup Database**
- Open `http://localhost/phpmyadmin`
- Create database: `academic_management`
- Import: `academic_management.sql`

3. **Configure Database**

Edit `db_config.php`:
```php
<?php
$host = 'localhost';
$dbname = 'academic_management';
$username = 'root';
$password = ''; 
?>
```

4. **Access the System**
- Open: `http://localhost/school-erp/`
- Register a new account or login

## 📁 Project Structure

```
school-erp/
├── css/                    # Stylesheets
├── images/                 # Image assets
├── include/                # Helper files
├── dashboard.php           # Main dashboard
├── login.php              # Login page
├── signup.php             # Registration page
├── grades.php             # View grades
├── fee-statement.php      # Fee statement
├── unit-registration.php  # Register units
├── timetable.php          # Class schedule
└── db_config.php          # Database config
```

## 👤 Default Usage

1. **Register**: `signup.php` - Create your student account
2. **Login**: `login.php` - Access your dashboard
3. **Dashboard**: View your academic info, fees, and GPA
4. **Quick Actions**: Register units, view grades, check fees

## 🔧 Troubleshooting

**500 Error?**
```bash

tail -f /opt/lampp/logs/error_log


chmod -R 755 /opt/lampp/htdocs/school-erp
```

**Database Connection Failed?**
- Verify `db_config.php` credentials
- Ensure MySQL is running: `sudo /opt/lampp/lampp status`

## 🔒 Security

- Password hashing with bcrypt
- SQL injection prevention (PDO prepared statements)
- XSS protection
- Session-based authentication

## 📝 License

MIT License - Feel free to use and modify

## 👨‍💻 Author

Built with ❤️ for educational institutions

---

⭐ **Star this repo if you find it useful!**