-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 02, 2026 at 11:47 AM
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
-- Database: `thesis_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `ip_address`, `user_agent`, `created_at`) VALUES
(39, 1, 'LOGOUT', 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-02 10:09:06'),
(40, 2, 'LOGIN', 'User logged in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-02 10:09:15'),
(41, 2, 'LOGOUT', 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-02 10:24:53'),
(42, 1, 'LOGIN', 'User logged in', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-02 10:41:42'),
(43, 1, 'LOGOUT', 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-02 10:41:55');

-- --------------------------------------------------------

--
-- Table structure for table `approvals`
--

CREATE TABLE `approvals` (
  `id` int(11) NOT NULL,
  `thesis_id` int(11) NOT NULL,
  `approver_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `comments` text DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Computer Science', 'Department of Computer Science', '2025-12-21 10:37:51', '2025-12-21 10:37:51'),
(2, 'Engineering', 'Department of Engineering', '2025-12-21 10:37:51', '2025-12-21 10:37:51'),
(3, 'Business Administration', 'Department of Business Administration', '2025-12-21 10:37:51', '2025-12-21 10:37:51'),
(4, 'College of Computing', 'College of Computing', '2025-12-21 10:37:51', '2025-12-21 10:37:51'),
(5, 'Department of Architecture', 'Department of Architecture', '2025-12-21 10:37:51', '2025-12-21 10:37:51');

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `department_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'Bachelor of Science in Computer Science', 'Undergraduate program in Computer Science', '2025-12-21 10:37:51', '2025-12-21 10:37:51'),
(2, 1, 'Master of Science in Computer Science', 'Graduate program in Computer Science', '2025-12-21 10:37:51', '2025-12-21 10:37:51'),
(3, 2, 'Bachelor of Science in Civil Engineering', 'Undergraduate program in Civil Engineering', '2025-12-21 10:37:51', '2025-12-21 10:37:51'),
(4, 2, 'Bachelor of Science in Computer Engineering', 'Undergraduate program in Computer Engineering', '2025-12-21 10:37:51', '2025-12-21 10:37:51'),
(5, 2, 'Bachelor of Science in Mechanical Engineering', 'Undergraduate program in Mechanical Engineering', '2025-12-21 10:37:51', '2025-12-21 10:37:51'),
(6, 4, 'Bachelor of Science in Information Technology', 'Undergraduate program in Information Technology', '2025-12-21 10:37:51', '2025-12-21 10:37:51'),
(7, 4, 'Bachelor of Science in Mathematics', 'Undergraduate program in Mathematics', '2025-12-21 10:37:51', '2025-12-21 10:37:51'),
(8, 5, 'Bachelor of Science in Architecture', 'Undergraduate program in Architecture', '2025-12-21 10:37:51', '2025-12-21 10:37:51');

-- --------------------------------------------------------

--
-- Table structure for table `review_logs`
--

CREATE TABLE `review_logs` (
  `id` int(11) NOT NULL,
  `thesis_id` int(11) NOT NULL,
  `reviewer_id` int(11) NOT NULL,
  `comments` text NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `review_logs`
--

INSERT INTO `review_logs` (`id`, `thesis_id`, `reviewer_id`, `comments`, `rating`, `created_at`) VALUES
(1, 4, 3, 'The files is duplicate', NULL, '2025-12-21 13:51:24');

-- --------------------------------------------------------

--
-- Table structure for table `theses`
--

CREATE TABLE `theses` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `abstract` text NOT NULL,
  `keywords` text DEFAULT NULL,
  `author_id` int(11) NOT NULL,
  `adviser_id` int(11) DEFAULT NULL,
  `department_id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `year` year(4) NOT NULL,
  `status` enum('draft','submitted','under_review','approved','rejected') DEFAULT 'draft',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `theses`
--

INSERT INTO `theses` (`id`, `title`, `abstract`, `keywords`, `author_id`, `adviser_id`, `department_id`, `program_id`, `year`, `status`, `submitted_at`, `approved_at`, `created_at`, `updated_at`) VALUES
(4, 'CITYINSIGHT: WEB AND MOBILE-BASED PERSONAL SAFETY COMPANION', 'Crime and flooding are significant concerns in Urdaneta City, affecting\r\npublic safety and navigation. This study presents CityInsight, a comprehensive web and\r\nmobile-based application designed to provide real-time alerts on hazards and emergency\r\nservices', 'CITYINSIGHT', 2, 3, 4, 6, '2024', 'approved', NULL, '2025-12-21 13:51:24', '2025-12-21 13:00:36', '2025-12-21 13:51:24');

-- --------------------------------------------------------

--
-- Table structure for table `thesis_files`
--

CREATE TABLE `thesis_files` (
  `id` int(11) NOT NULL,
  `thesis_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` int(11) DEFAULT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `thesis_files`
--

INSERT INTO `thesis_files` (`id`, `thesis_id`, `file_name`, `file_path`, `file_size`, `mime_type`, `uploaded_at`) VALUES
(4, 4, '6947ef7497f63_ARTICLE_OF_CITYINSIGHT_EXTENDED_ABSTRACT_FINAL (1).pdf', '../assets/uploads/6947ef7497f63_ARTICLE_OF_CITYINSIGHT_EXTENDED_ABSTRACT_FINAL (1).pdf', 555647, 'application/pdf', '2025-12-21 13:00:36'),
(5, 4, '6947f1a04d180_CITYINSIGHT-HARDBOUND-FINAL.pdf', '../assets/uploads/6947f1a04d180_CITYINSIGHT-HARDBOUND-FINAL.pdf', 4599793, 'application/pdf', '2025-12-21 13:09:52');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','adviser','admin') NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `student_id` varchar(20) DEFAULT NULL,
  `program_id` int(11) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `first_name`, `last_name`, `middle_name`, `student_id`, `program_id`, `profile_picture`, `signature`, `is_active`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@university.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Admin', 'User', NULL, NULL, NULL, NULL, NULL, 1, '2026-01-02 10:41:42', '2025-12-21 10:37:51', '2026-01-02 10:41:42'),
(2, 'student', 'student@university.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'Jordan', 'Cuison', NULL, '20190001', 6, '', '', 1, '2026-01-02 10:09:15', '2025-12-21 10:37:51', '2026-01-02 10:09:15'),
(3, 'faculty', 'faculty@university.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'adviser', 'Leo Gabriel', 'Villanueva', NULL, NULL, NULL, '../uploads/profile_pictures/profile_3_1766318380.jpg', '../uploads/signatures/signature_3_1766318387.png', 1, '2026-01-02 09:59:24', '2025-12-21 10:37:51', '2026-01-02 09:59:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `approvals`
--
ALTER TABLE `approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `thesis_id` (`thesis_id`),
  ADD KEY `approver_id` (`approver_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `review_logs`
--
ALTER TABLE `review_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `thesis_id` (`thesis_id`),
  ADD KEY `reviewer_id` (`reviewer_id`);

--
-- Indexes for table `theses`
--
ALTER TABLE `theses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `adviser_id` (`adviser_id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `thesis_files`
--
ALTER TABLE `thesis_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `thesis_id` (`thesis_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD KEY `program_id` (`program_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `approvals`
--
ALTER TABLE `approvals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `review_logs`
--
ALTER TABLE `review_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `theses`
--
ALTER TABLE `theses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `thesis_files`
--
ALTER TABLE `thesis_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `approvals`
--
ALTER TABLE `approvals`
  ADD CONSTRAINT `approvals_ibfk_1` FOREIGN KEY (`thesis_id`) REFERENCES `theses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `approvals_ibfk_2` FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `programs`
--
ALTER TABLE `programs`
  ADD CONSTRAINT `programs_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `review_logs`
--
ALTER TABLE `review_logs`
  ADD CONSTRAINT `review_logs_ibfk_1` FOREIGN KEY (`thesis_id`) REFERENCES `theses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `review_logs_ibfk_2` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `theses`
--
ALTER TABLE `theses`
  ADD CONSTRAINT `theses_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `theses_ibfk_2` FOREIGN KEY (`adviser_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `theses_ibfk_3` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `theses_ibfk_4` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `thesis_files`
--
ALTER TABLE `thesis_files`
  ADD CONSTRAINT `thesis_files_ibfk_1` FOREIGN KEY (`thesis_id`) REFERENCES `theses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
