-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2025 at 07:35 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `academic_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(100) NOT NULL,
  `department_code` varchar(10) NOT NULL,
  `faculty` varchar(100) DEFAULT NULL,
  `head_of_department` varchar(100) DEFAULT NULL,
  `established_date` date DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `grade_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `academic_year` year(4) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `marks` decimal(5,2) DEFAULT NULL CHECK (`marks` between 0 and 100),
  `grade` varchar(2) DEFAULT NULL,
  `grade_points` decimal(3,2) DEFAULT NULL,
  `status` enum('pass','fail','incomplete') DEFAULT 'pass',
  `assessment_date` date DEFAULT NULL,
  `lecturer_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` date DEFAULT curdate(),
  `payment_method` enum('credit_card','bank_transfer','cash','online') DEFAULT 'online',
  `transaction_id` varchar(100) DEFAULT NULL,
  `academic_year` year(4) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `payment_type` enum('tuition','registration','library','other') DEFAULT 'tuition',
  `status` enum('pending','completed','failed','refunded') DEFAULT 'completed',
  `balance` decimal(10,2) DEFAULT 0.00,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `registration_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `academic_year` year(4) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `registration_date` date DEFAULT curdate(),
  `status` enum('registered','completed','dropped') DEFAULT 'registered'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `gender` enum('male','female','other') DEFAULT 'other',
  `date_of_birth` date DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `employment_type` enum('full-time','part-time','contract') DEFAULT 'full-time',
  `hire_date` date DEFAULT curdate(),
  `salary` decimal(10,2) DEFAULT NULL,
  `status` enum('active','on_leave','resigned','retired') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `enrollment_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `unit_id` int(11) NOT NULL,
  `unit_code` varchar(20) NOT NULL,
  `unit_name` varchar(100) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `credits` int(11) NOT NULL,
  `semester` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `lecturer` varchar(100) DEFAULT NULL,
  `prerequisites` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `user_role` enum('admin','lecturer','staff') DEFAULT 'lecturer',
  `department_id` int(11) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`),
  ADD UNIQUE KEY `department_code` (`department_code`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`grade_id`),
  ADD UNIQUE KEY `unique_grade` (`student_id`,`unit_id`,`academic_year`,`semester`),
  ADD KEY `idx_grades_student` (`student_id`),
  ADD KEY `idx_grades_unit` (`unit_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `idx_payments_student` (`student_id`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`registration_id`),
  ADD UNIQUE KEY `unique_registration` (`student_id`,`unit_id`,`academic_year`,`semester`),
  ADD KEY `idx_registrations_student` (`student_id`),
  ADD KEY `idx_registrations_unit` (`unit_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_students_email` (`email`),
  ADD KEY `idx_students_department` (`department_id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`unit_id`),
  ADD UNIQUE KEY `unit_code` (`unit_code`),
  ADD KEY `idx_units_department` (`department_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `idx_users_role` (`user_role`);

--
-- AUTO_INCREMENT for dumped tables
-- departmennts
INSERT INTO departments (department_name, department_code, faculty, head_of_department, established_date) VALUES
('Computer Science', 'CS', 'School of Computing & IT', 'Dr. James Mwangi', '2010-01-15'),
('Information Technology', 'IT', 'School of Computing & IT', 'Prof. Mary Njeri', '2012-03-20'),
('Software Engineering', 'SE', 'School of Computing & IT', 'Dr. Peter Kamau', '2015-06-10'),
('Business Administration', 'BA', 'School of Business', 'Prof. Jane Wanjiru', '2008-09-01'),
('Electrical Engineering', 'EE', 'School of Engineering', 'Dr. John Ochieng', '2005-04-12');
--

INSERT INTO units (unit_code, unit_name, department_id, credits, semester, description, lecturer, prerequisites) VALUES
-- Computer Science Year 1 Semester 1
('COMP101', 'Introduction to Programming', 1, 4, 1, 'Fundamentals of programming using Python', 'Dr. Jane Smith', NULL),
('MATH102', 'Calculus I', 1, 4, 1, 'Differential and integral calculus', 'Prof. John Doe', NULL),
('ENG103', 'Technical Writing', 1, 3, 1, 'Professional communication skills', 'Dr. Mary Johnson', NULL),
('PHYS104', 'Physics for Engineers', 1, 4, 1, 'Mechanics, heat, and electricity', 'Dr. Robert Brown', NULL),
('COMP105', 'Computer Systems', 1, 3, 1, 'Computer architecture and organization', 'Dr. Alice Wong', NULL),

-- Computer Science Year 1 Semester 2
('COMP201', 'Data Structures & Algorithms', 1, 4, 2, 'Arrays, lists, trees, graphs', 'Dr. Jane Smith', 'COMP101'),
('MATH202', 'Calculus II', 1, 4, 2, 'Multivariable calculus', 'Prof. John Doe', 'MATH102'),
('COMP203', 'Object Oriented Programming', 1, 4, 2, 'Java programming and OOP concepts', 'Dr. David Lee', 'COMP101'),
('STAT204', 'Statistics & Probability', 1, 3, 2, 'Statistical analysis and inference', 'Dr. Sarah Chen', NULL),
('COMP205', 'Web Technologies', 1, 3, 2, 'HTML, CSS, JavaScript basics', 'Dr. Michael Kim', NULL),

-- Computer Science Year 2 Semester 1
('COMP301', 'Database Systems', 1, 4, 1, 'SQL, normalization, database design', 'Dr. Patricia Mills', 'COMP201'),
('COMP302', 'Operating Systems', 1, 4, 1, 'Process management, memory, file systems', 'Dr. Thomas Garcia', 'COMP105'),
('COMP303', 'Software Engineering', 1, 4, 1, 'SDLC, design patterns, testing', 'Dr. Lisa Anderson', 'COMP203'),
('COMP304', 'Computer Networks', 1, 3, 1, 'TCP/IP, routing, network security', 'Dr. James Wilson', 'COMP105'),
('MATH305', 'Discrete Mathematics', 1, 3, 1, 'Logic, sets, graph theory', 'Prof. Emma Thompson', 'MATH102');

-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `grade_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `registration_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grades_ibfk_2` FOREIGN KEY (`unit_id`) REFERENCES `units` (`unit_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `registrations_ibfk_2` FOREIGN KEY (`unit_id`) REFERENCES `units` (`unit_id`);

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `staff_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`);

--
-- Constraints for table `units`
--
ALTER TABLE `units`
  ADD CONSTRAINT `units_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;