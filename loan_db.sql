-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2021 at 06:39 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `loan_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`) VALUES
(1, 'College Of Business Management and Accountancy'),
(3, 'Bachelor of Science in Information Technology');

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id` int(11) NOT NULL,
  `loan_number` varchar(50) NOT NULL,
  `membership_number` varchar(50) NOT NULL,
  `amount` double NOT NULL,
  `term` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `department_id` int(11) NOT NULL,
  `loan_type_id` int(11) NOT NULL,
  `total_amount` double NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `loans`
--

INSERT INTO `loans` (`id`, `loan_number`, `membership_number`, `amount`, `term`, `status`, `department_id`, `loan_type_id`, `total_amount`, `user_id`, `created_at`) VALUES
(1, 'LOAN-2021-7', 'MEM-2021-7', 1000, '6', 'pending', 1, 1, 1060, 7, '2021-03-22 01:48:05'),
(2, 'LOAN-2021-7', 'MEM-2021-7', 60000, '12', 'pending', 1, 3, 74400, 7, '2021-03-22 01:48:38'),
(3, 'LOAN-2021-7', 'MEM-2021-7', 50000, '6', 'pending', 1, 1, 53000, 7, '2021-03-22 02:06:44'),
(4, 'LOAN-2021-15', 'MEM-2021-15', 70000, '6', 'pending', 1, 1, 74200, 15, '2021-03-22 02:19:30'),
(5, 'LOAN-2021-15', 'MEM-2021-15', 80000, '12', 'active', 1, 1, 89600, 15, '2021-03-22 02:31:45'),
(6, 'LOAN-2021-7', 'MEM-2021-7', 1000, '5', 'active', 3, 1, 1050, 7, '2021-03-22 05:06:37');

-- --------------------------------------------------------

--
-- Table structure for table `loan_types`
--

CREATE TABLE `loan_types` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `interest` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `loan_types`
--

INSERT INTO `loan_types` (`id`, `name`, `interest`) VALUES
(1, 'regular', 1),
(3, 'character', 2);

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'customer'),
(3, 'president'),
(4, 'treasurer'),
(5, 'assistant treasurer'),
(6, 'membership committee'),
(7, 'financial commitee'),
(8, 'comaker');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `contact_number` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `position_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `gender`, `contact_number`, `email`, `password`, `active`, `position_id`, `created_at`) VALUES
(1, 'wendell', 'suazo', 'male', '09959768531', 'wendellchansuazo11@gmail.com', '405c71b3a5f67c7bacf39a2820ec686a', 1, 3, '2021-03-18 07:53:47'),
(2, 'wendell', 'suazo', 'male', '90827432864', 'wendellchansuazo111@gmail.com', '405c71b3a5f67c7bacf39a2820ec686a', 1, 4, '2021-03-18 09:40:44'),
(3, 'wendell', 'suazo', 'male', '90827432864', 'wendellchansuazo1111@gmail.com', '405c71b3a5f67c7bacf39a2820ec686a', 0, 5, '2021-03-18 09:41:16'),
(4, 'wendell', 'suazo', 'male', '90827432864', 'wendellchansuazo11111@gmail.com', '405c71b3a5f67c7bacf39a2820ec686a', 0, 6, '2021-03-18 09:41:33'),
(5, 'wendell', 'suazo', 'male', '90827432864', 'wendellchansuazasdfsafo11@gmail.com', '405c71b3a5f67c7bacf39a2820ec686a', 1, 7, '2021-03-18 09:42:47'),
(6, 'test', 'test', 'male', '90827432864', 'test@gmail.com', '405c71b3a5f67c7bacf39a2820ec686a', 1, 2, '2021-03-18 09:49:57'),
(7, 'wendell', 'suazo', 'male', '09959768531', 'admin@gmail.com', '21232f297a57a5a743894a0e4a801fc3', 1, 1, '2021-03-19 01:42:38'),
(9, 'test', 'user', 'female', '09959768531', 'testuser@gmail.com', '405c71b3a5f67c7bacf39a2820ec686a', 0, 2, '2021-03-19 03:13:19'),
(11, 'smaple', 'skafjaks', 'female', '09959768531', 'asd@gmail.com', '405c71b3a5f67c7bacf39a2820ec686a', 0, 2, '2021-03-19 04:48:26'),
(12, 'Wendell', 'Suazo', 'male', '+639959768531', 'newemail@gmail.com', '405c71b3a5f67c7bacf39a2820ec686a', 1, 2, '2021-03-19 05:09:52'),
(14, 'pikachu', 'ketchup', 'male', '09959768531', 'pikachu@gmail.com', '405c71b3a5f67c7bacf39a2820ec686a', 1, 1, '2021-03-19 05:18:37'),
(15, 'julia', 'de jesus', 'male', '09959768531', 'julia@gmail.com', '405c71b3a5f67c7bacf39a2820ec686a', 1, 2, '2021-03-22 02:17:49'),
(16, 'micaella', 'gadaza', 'female', '09959768531', 'micaella@gmail.com', 'dcdf9193da491f3ab559f2e15224e828', 1, 8, '2021-03-22 02:48:54'),
(17, 'rhoiven', 'delacueva', 'male', '+639959768531', 'rhoiven@gmail.com', '5fef7427226a52e5adf66652a5e15ad8', 1, 8, '2021-03-22 02:53:33'),
(18, 'john', 'doe', 'male', '09959768531', 'john@gmail.com', '405c71b3a5f67c7bacf39a2820ec686a', 1, 8, '2021-03-22 05:09:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loans_department_id_foreign` (`department_id`),
  ADD KEY `loans_loan_types_id_foreign` (`loan_type_id`),
  ADD KEY `loans_user_id_foreign` (`user_id`);

--
-- Indexes for table `loan_types`
--
ALTER TABLE `loan_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_position_id_foreign` (`position_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `loan_types`
--
ALTER TABLE `loan_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `loans_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  ADD CONSTRAINT `loans_loan_types_id_foreign` FOREIGN KEY (`loan_type_id`) REFERENCES `loan_types` (`id`),
  ADD CONSTRAINT `loans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
