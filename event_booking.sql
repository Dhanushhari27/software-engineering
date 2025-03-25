-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 25, 2025 at 08:33 AM
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
-- Database: `event_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `name`, `date`, `location`) VALUES
(3, 'GQ Fundraising', '2025-03-27', 'GQ Hall'),
(4, 'AU', '2025-03-28', 'NY Towers');

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

CREATE TABLE `seats` (
  `id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `seat_number` varchar(10) DEFAULT NULL,
  `is_booked` tinyint(1) DEFAULT 0,
  `booked_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seats`
--

INSERT INTO `seats` (`id`, `event_id`, `seat_number`, `is_booked`, `booked_by`) VALUES
(52, 3, 'S01', 1, 2),
(53, 3, 'S02', 1, 2),
(54, 3, 'S03', 1, 2),
(55, 3, 'S04', 1, 2),
(56, 3, 'S05', 0, NULL),
(57, 3, 'S06', 0, NULL),
(58, 3, 'S07', 0, NULL),
(59, 3, 'S08', 0, NULL),
(60, 3, 'S09', 0, NULL),
(61, 3, 'S10', 0, NULL),
(62, 3, 'S11', 0, NULL),
(63, 3, 'S12', 0, NULL),
(64, 3, 'S13', 0, NULL),
(65, 3, 'S14', 0, NULL),
(66, 3, 'S15', 0, NULL),
(67, 3, 'S16', 0, NULL),
(68, 3, 'S17', 0, NULL),
(69, 3, 'S18', 0, NULL),
(70, 3, 'S19', 0, NULL),
(71, 3, 'S20', 0, NULL),
(72, 3, 'S21', 0, NULL),
(73, 3, 'S22', 0, NULL),
(74, 3, 'S23', 0, NULL),
(75, 3, 'S24', 0, NULL),
(76, 3, 'S25', 0, NULL),
(77, 3, 'S26', 0, NULL),
(78, 3, 'S27', 0, NULL),
(79, 3, 'S28', 0, NULL),
(80, 3, 'S29', 0, NULL),
(81, 3, 'S30', 0, NULL),
(82, 4, 'S01', 1, 2),
(83, 4, 'S02', 1, 2),
(84, 4, 'S03', 0, NULL),
(85, 4, 'S04', 0, NULL),
(86, 4, 'S05', 0, NULL),
(87, 4, 'S06', 0, NULL),
(88, 4, 'S07', 0, NULL),
(89, 4, 'S08', 0, NULL),
(90, 4, 'S09', 0, NULL),
(91, 4, 'S10', 0, NULL),
(92, 4, 'S11', 0, NULL),
(93, 4, 'S12', 0, NULL),
(94, 4, 'S13', 0, NULL),
(95, 4, 'S14', 0, NULL),
(96, 4, 'S15', 0, NULL),
(97, 4, 'S16', 0, NULL),
(98, 4, 'S17', 0, NULL),
(99, 4, 'S18', 0, NULL),
(100, 4, 'S19', 0, NULL),
(101, 4, 'S20', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(2, 'Collins', '', '$2y$10$jAVxJp6cFGiAcWbWG9qhLOYaLDgE70XkfwfwwlyMAcpXNz9mQ7tpy', 'user', '2025-03-24 17:35:37'),
(5, 'admin', 'admin@example.com', '$2y$10$KUz31kyHn69vXKG.7ihbZuYMbA/46A6QdAqqvNi2vIx.tjXG/Jz66', 'admin', '2025-03-24 17:46:28'),
(9, 'john', 'john@gmail.com', '$2y$10$uWEHxnGdb4gmYQBxE0.afut7V5y610xq8UJMW1BixytLUel045vnW', 'user', '2025-03-24 18:08:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seats`
--
ALTER TABLE `seats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

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
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `seats`
--
ALTER TABLE `seats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `seats`
--
ALTER TABLE `seats`
  ADD CONSTRAINT `seats_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
