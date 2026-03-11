-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 11, 2026 at 09:30 AM
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
-- Database: `doctorappointmentweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'Admin',
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `role`, `reset_token`, `reset_token_expiry`) VALUES
(1, 'Ronila', 'ronila@gmail.com', '$2y$10$c6vNRbGBHXHZahO5kdrUv.xb1D1V7neKQfTWsFGQbcOQuMA1ArvZ6', 'admin', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`id`, `patient_id`, `doctor_id`, `appointment_date`, `appointment_time`, `description`, `status`) VALUES
(6, 2, 3, '2026-02-23', '13:30:00', 'skin redness', 'Rescheduled'),
(7, 2, 1, '2026-02-21', '15:00:00', 'pain around heart', 'Approved'),
(8, 2, 3, '2026-02-25', '13:00:00', 'gfgfgggf', 'Pending'),
(9, 2, 3, '2026-03-11', '13:30:00', 'redness over body', 'Rescheduled');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `specialization` varchar(100) NOT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `experience` int(11) DEFAULT NULL,
  `available_from` time DEFAULT NULL,
  `available_to` time DEFAULT NULL,
  `available_days` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`id`, `name`, `email`, `password`, `specialization`, `profile_pic`, `experience`, `available_from`, `available_to`, `available_days`) VALUES
(1, 'Deep Karki', 'deep@gmail.com', '$2y$10$LlNktI59cE40iQxaZ1juceG3qD8nR7jPPh62plDiQd6Fme0uNUBhq', 'Cardiologist', '../uploads/doctors/1770476105_doctor2.jpg.jpg', 10, '13:00:00', '17:00:00', 'Sunday-Friday'),
(3, 'Alinor Koirala', 'alinor@gmail.com', '$2y$10$w6I7RGFYkAZV5vk7ixM38OHpXPWJa02pKYU0Ncpuu25goPYiLVNza', 'Dermatologist', '../uploads/doctors/1770477881_doctor1.jpg.jpg', 5, '13:00:00', '17:00:00', 'Sunday-Friday');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `user_type` enum('patient','doctor') NOT NULL,
  `message` varchar(255) NOT NULL,
  `seen` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `appointment_id`, `user_type`, `message`, `seen`, `created_at`) VALUES
(1, 7, 'doctor', 'Patient rescheduled appointment #7 to 2026-02-24 at 14:11', 1, '2026-02-21 09:26:49'),
(2, 7, 'doctor', 'Ronish Dhital rescheduled appointment #7 to 2026-02-21 at 15:00', 1, '2026-02-21 09:47:42'),
(3, 7, 'doctor', 'Ronish Dhital rescheduled appointment #7 to 2026-02-21 at 15:00', 1, '2026-02-21 10:04:25'),
(4, 7, 'patient', 'Your appointment has been approved', 0, '2026-02-21 10:05:11'),
(5, 7, 'doctor', 'Ronish Dhital rescheduled appointment #7 to 2026-02-21 at 15:00', 1, '2026-02-21 10:05:20'),
(6, 7, 'patient', 'Your appointment has been approved', 0, '2026-02-21 10:05:50'),
(7, 7, 'patient', 'Your appointment has been approved', 0, '2026-02-21 10:05:51'),
(8, 7, 'patient', 'Your appointment has been approved', 0, '2026-02-21 10:07:57'),
(9, 7, 'patient', 'Your appointment has been pending', 0, '2026-02-21 10:08:02'),
(10, 7, 'patient', 'Your appointment has been pending', 0, '2026-02-21 10:08:03'),
(11, 7, 'patient', 'Your appointment has been approved', 0, '2026-02-21 10:08:13'),
(12, 6, 'doctor', 'Ronish Dhital rescheduled appointment #6 to 2026-02-23 at 13:30', 1, '2026-02-22 06:19:47'),
(13, 8, 'doctor', 'Ronish Dhital booked a new appointment on 2026-02-25 at 13:00', 1, '2026-02-24 07:40:12'),
(14, 9, 'doctor', 'Ronish Dhital booked a new appointment on 2026-03-15 at 13:00', 1, '2026-03-11 07:48:11'),
(15, 9, 'doctor', 'Ronish Dhital rescheduled appointment #9 to 2026-03-11 at 13:30', 1, '2026-03-11 08:21:09');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`id`, `name`, `email`, `password`) VALUES
(1, 'Ronila Dhital', 'ronilaadhital@gmail.com', '$2y$10$G38hJGvAqbqy1xwdxYLnwOYPGzBzA0TBaPbcbrUKjUALZhWJAH9Y2'),
(2, 'Ronish Dhital', 'ronish@gmail.com', '$2y$10$IBmXjWJbNBlKxbcw6JPMoe7oKcvIfm7XftDDPr97U9vWGTdiMXITu');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_id` (`appointment_id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointment_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointment` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
