-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2025 at 01:12 PM
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
-- Database: `enrollment_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `class_schedules`
--

CREATE TABLE `class_schedules` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `class_code` varchar(20) NOT NULL,
  `unit_hours` varchar(20) NOT NULL,
  `time_from` time NOT NULL,
  `time_to` time NOT NULL,
  `days` varchar(10) NOT NULL,
  `room` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_schedules`
--

INSERT INTO `class_schedules` (`id`, `subject_id`, `class_code`, `unit_hours`, `time_from`, `time_to`, `days`, `room`) VALUES
(26, 1, '10114', '2.00/1.00', '11:00:00', '17:00:00', 'HM', 'AB1-207/AB1-206'),
(27, 3, '10113', '2.00/1.00', '11:00:00', '16:00:00', 'FT', 'AB1-206/TBA AB1-206'),
(28, 5, '10066', '2.00/1.00', '07:00:00', '15:00:00', 'HF', 'AB1-204'),
(29, 9, '10065', '2.00/1.00', '02:00:00', '15:00:00', 'WT', 'AB1-206');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `enrollment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('enrolled','completed','dropped') DEFAULT 'enrolled',
  `grade` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `student_id`, `subject_id`, `enrollment_date`, `status`, `grade`) VALUES
(21, 2, 1, '2025-12-14 10:16:17', 'enrolled', NULL),
(22, 2, 3, '2025-12-14 10:16:17', 'enrolled', NULL),
(23, 2, 5, '2025-12-14 10:16:17', 'enrolled', NULL),
(24, 2, 9, '2025-12-14 10:16:17', 'enrolled', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `faculty_assignments`
--

CREATE TABLE `faculty_assignments` (
  `id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `assignment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_assignments`
--

INSERT INTO `faculty_assignments` (`id`, `faculty_id`, `subject_id`, `assignment_date`) VALUES
(1, 4, 5, '2025-12-14 10:38:51'),
(3, 6, 1, '2025-12-14 10:49:31'),
(4, 7, 9, '2025-12-14 10:49:39'),
(5, 8, 3, '2025-12-14 11:37:59');

-- --------------------------------------------------------

--
-- Table structure for table `prerequisites`
--

CREATE TABLE `prerequisites` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `prerequisite_subject_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subject_code` varchar(20) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `credits` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject_code`, `subject_name`, `credits`, `description`, `created_at`) VALUES
(1, 'URD_SIA101_IT', 'COI PRE-REQUISITE [URD_CC105_IT/URD_IM102_IT/URD_WD101]]', 3, 'System Integration and Architecture', '2025-12-14 09:26:53'),
(3, 'URD_SA101_IT', 'COI PRE-REQUISITE [URD_IAS101]', 3, 'System Administration and Maintenance', '2025-12-14 09:26:54'),
(5, 'URD_MD101_IT', 'COI PRE-REQUISITE [URD_IPT101]', 3, 'Mobile App Development 1', '2025-12-14 09:26:54'),
(9, 'URD_WS101_IT', 'COI PRE-REQUISITE [URD_CC105_IT/URD_IM102_IT/URD_WD101]]', 3, 'Web System and Technology 1', '2025-12-14 09:50:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `role` enum('student','faculty','admin') NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `first_name`, `last_name`, `role`, `profile_picture`, `signature`, `created_at`) VALUES
(1, 'admin', '$2y$10$9pkNHov9NgBA5M5JUR9kHeU4cgswrbaJobpMZcogHDuMhdRwi/GJq', 'admin@enrollmentsystem.com', 'System', 'Administrator', 'admin', NULL, NULL, '2025-12-14 09:23:14'),
(2, 'student1', '$2y$10$Nch1hYZ/Hyw5wiIevqv2SuyjNAhQyoJJBNhkDHvkUJ.Y9.QVtFTh6', 'student1@example.com', 'Rusty', 'Lopez', 'student', 'assets/uploads/profiles/693e879095556_rusty.jpg', 'assets/uploads/signatures/693e8796e0a76_signature.png', '2025-12-14 09:29:03'),
(4, 'faculty1', '$2y$10$852uGZHDjCd0sU7dKx2fI.rmgMURPHzs1X82Z0R80OJcvogssNekO', 'faculty1@example.com', 'Arni-Rie', 'Tamayo', 'faculty', NULL, NULL, '2025-12-14 10:11:25'),
(6, 'faculty3', '$2y$10$97oK2Z7KeUdhBrxgf/unmuJazn8Fg1dOJnmmb5yN4k8x6h1fAteMa', 'faculty3@example.com', 'Kathleen', 'Steves', 'faculty', NULL, NULL, '2025-12-14 10:47:56'),
(7, 'faculty4', '$2y$10$wDIQe69eK6m8xRXkS17aHegUxpITHK6EOBVd2VpSoRzlLokQgjh9W', 'faculty4@example.com', 'Leo Gabriel', 'Villanueva', 'faculty', NULL, NULL, '2025-12-14 10:48:43'),
(8, 'faculty2', '$2y$10$QyIjUcFsTN13XUQQ3SrvH.pmCgD69LLwZcEaOOLQh4ONctz29vXOm', 'faculty2@example.com', 'Paul Andrew', 'Roa', 'faculty', NULL, NULL, '2025-12-14 11:37:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `class_schedules`
--
ALTER TABLE `class_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_enrollment` (`student_id`,`subject_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `faculty_assignments`
--
ALTER TABLE `faculty_assignments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_assignment` (`faculty_id`,`subject_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `prerequisites`
--
ALTER TABLE `prerequisites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_prerequisite` (`subject_id`,`prerequisite_subject_id`),
  ADD KEY `prerequisite_subject_id` (`prerequisite_subject_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subject_code` (`subject_code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `class_schedules`
--
ALTER TABLE `class_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `faculty_assignments`
--
ALTER TABLE `faculty_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `prerequisites`
--
ALTER TABLE `prerequisites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `class_schedules`
--
ALTER TABLE `class_schedules`
  ADD CONSTRAINT `class_schedules_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `faculty_assignments`
--
ALTER TABLE `faculty_assignments`
  ADD CONSTRAINT `faculty_assignments_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `faculty_assignments_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prerequisites`
--
ALTER TABLE `prerequisites`
  ADD CONSTRAINT `prerequisites_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prerequisites_ibfk_2` FOREIGN KEY (`prerequisite_subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
