-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2021 at 10:56 AM
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
(1, 'COLLEGE OF BUSINESS MANAGEMENT AND ACCOUNTANCY'),
(4, 'COLLEGE OF ENGINEERING'),
(5, 'COLLEGE OF AGRICULTURE'),
(6, 'COLLEGE OF ARTS AND SCIENCES'),
(7, 'COLLEGE OF CRIMINAL JUSTICE EDUCATION'),
(8, 'COLLEGE OF COMPUTER STUDIES'),
(9, 'COLLEGE OF HOSPITALITY MANAGEMENT AND TOURISM'),
(10, 'COLLEGE OF TEACHER EDUCATION');

-- --------------------------------------------------------

--
-- Table structure for table `fixed_deposits`
--

CREATE TABLE `fixed_deposits` (
  `id` int(11) NOT NULL,
  `reference_number` varchar(50) DEFAULT NULL,
  `payment_by` varchar(100) NOT NULL,
  `amount` double NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id` int(11) NOT NULL,
  `transaction_id` varchar(50) NOT NULL,
  `loan_number` varchar(50) NOT NULL,
  `membership_number` varchar(50) NOT NULL,
  `amount` double NOT NULL,
  `amount_per_kinsenas` double NOT NULL,
  `amount_per_month` double NOT NULL,
  `interest_amount` double NOT NULL,
  `interest_amount_per_kinsenas` double NOT NULL,
  `interest_amount_per_month` double NOT NULL,
  `total_amount` double NOT NULL,
  `term` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `loan_type_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comaker1_id` int(11) DEFAULT NULL,
  `comaker2_id` int(11) DEFAULT NULL,
  `approved_by_c1` tinyint(1) NOT NULL DEFAULT 0,
  `approved_by_c2` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved_at` date DEFAULT NULL,
  `approved_by_f1` int(11) DEFAULT NULL,
  `approved_by_f2` int(11) DEFAULT NULL,
  `approved_by_f3` int(11) DEFAULT NULL,
  `approved_by_president` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loan_comakers`
--

CREATE TABLE `loan_comakers` (
  `id` int(11) NOT NULL,
  `loan_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loan_penalties`
--

CREATE TABLE `loan_penalties` (
  `id` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `amount` double NOT NULL,
  `service_fee` double DEFAULT NULL,
  `loan_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `reference_number` varchar(50) NOT NULL,
  `payment_by` varchar(50) DEFAULT NULL,
  `payment_amount` double DEFAULT NULL,
  `payment_saving` double DEFAULT NULL,
  `payment_fixed_deposit` double DEFAULT NULL,
  `payment_saving_withdraw` double DEFAULT NULL,
  `loan_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `has_penalty` tinyint(1) DEFAULT 0,
  `paid_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `loan_type_id` int(11) DEFAULT NULL,
  `interest_amount` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(2, 'member'),
(3, 'president'),
(4, 'treasurer'),
(5, 'assistant treasurer'),
(6, 'membership committee'),
(7, 'financial commitee');

-- --------------------------------------------------------

--
-- Table structure for table `savings`
--

CREATE TABLE `savings` (
  `id` int(11) NOT NULL,
  `reference_number` varchar(50) DEFAULT NULL,
  `payment_by` varchar(100) NOT NULL,
  `amount` double NOT NULL,
  `user_id` int(11) NOT NULL,
  `withdraw_amount` double DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `account_number` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `home_address` varchar(255) NOT NULL,
  `permanent_address` varchar(255) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `birth_date` varchar(255) DEFAULT NULL,
  `contact_number` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `position_id` int(11) DEFAULT NULL,
  `sg` int(11) NOT NULL,
  `employment_status` varchar(50) NOT NULL,
  `department_id` int(11) NOT NULL,
  `name_of_spouse` varchar(100) NOT NULL,
  `fathers_name` varchar(100) NOT NULL,
  `mothers_maiden_name` varchar(50) NOT NULL,
  `beneficiary` varchar(255) NOT NULL,
  `paid_membership` tinyint(1) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `reason1` text DEFAULT NULL,
  `reason2` text DEFAULT NULL,
  `reason3` text DEFAULT NULL,
  `approved_by_m1` int(11) DEFAULT NULL,
  `approved_by_m2` int(11) DEFAULT NULL,
  `approved_by_m3` int(11) DEFAULT NULL,
  `approved_by_president` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `account_number`, `firstname`, `middlename`, `lastname`, `home_address`, `permanent_address`, `gender`, `birth_date`, `contact_number`, `email`, `password`, `position_id`, `sg`, `employment_status`, `department_id`, `name_of_spouse`, `fathers_name`, `mothers_maiden_name`, `beneficiary`, `paid_membership`, `active`, `reason1`, `reason2`, `reason3`, `approved_by_m1`, `approved_by_m2`, `approved_by_m3`, `approved_by_president`, `created_at`) VALUES
(61, '1618534842192', 'micaella', 'middlename', 'gadaza', '123 main st.', '123 main st.', 'female', '1999-01-13', '099588278272', 'admin@gmail.com', '21232f297a57a5a743894a0e4a801fc3', 1, 123, 'regular', 4, '', 'johnny doe sr.', 'melissa Yveth', 'melissa Yveth', 1, 1, 'asdafsdafsa', 'sdafsafa', 'dafdsaf', 63, 67, 74, 1, '2021-04-16 01:00:42'),
(63, '1618535620256', 'john', 'doe', 'suazo', '123 main st.', '123 main st.', 'male', '2021-04-20', '09959768531', 'membership1@gmail.com', '5f4dcc3b5aa765d61d8327deb882cf99', 6, 123, 'regular', 6, '', 'enrico suazo', 'chuchie chan', 'chuchie chan', 1, 1, 'akjhfdjadfahfdajfh', 'hjahsdjfahfdsja', 'sajdhfajfhsajsjfs', 63, 67, 67, 1, '2021-04-16 01:13:40'),
(67, '16185463592127', 'john', 'middlename', 'cena', 'Cheriffer', '123 main st.', 'female', '2021-04-13', '090283924823', 'membership2@gmail.com', '5f4dcc3b5aa765d61d8327deb882cf99', 6, 123, 'regular', 7, '', 'johnny doe sr.', 'melissa Yveth', 'melissa Yveth', 1, 1, 'aaaaaaaaaaaaaaaaa', 'bbbbbbbbbbbbb', 'ccccccccccccccc', 63, 67, 74, 1, '2021-04-16 04:12:39'),
(69, '16207201413505', 'julia', 'de jesus', 'de jesus', 'CHERIFFER', '123 main st.', 'female', '2021-04-28', '973287482', 'president@gmail.com', '5f4dcc3b5aa765d61d8327deb882cf99', 3, 123, 'regular', 6, '', 'enrico suazo', 'chuchie chan', 'chuchie chan', 1, 1, '1212', '1221', '1212', 63, 67, 74, 1, '2021-05-11 08:02:21'),
(74, '1620785424769', 'Gemma', 'George Mitchell', 'Walton', 'Est architecto nostr', 'Voluptatibus dicta i', 'male', '16-Feb-1985', '77', 'membership3@gmail.com', '5f4dcc3b5aa765d61d8327deb882cf99', 6, 62, 'job_order', 1, 'Vera Velazquez', 'Francesca Hartman', 'Silas Hayes', 'Commodi sunt aliquid', 1, 1, 'Fugiat quaerat est', 'Quia occaecat eiusmo', 'Sit corrupti volupt', 63, 67, 74, 1, '2021-05-12 02:10:24'),
(78, '16208744364997', 'Forrest', 'Lysandra Hooper', 'Goodwin', 'Libero alias qui ad ', 'Sit veniam fugit e', 'female', '11-Nov-2008', '539', 'financial1@gmail.com', '5f4dcc3b5aa765d61d8327deb882cf99', 7, 16, 'regular', 1, 'Hasad Larsen', 'Britanney Flynn', 'Aurora Wilcox', 'Eos excepteur esse s', 1, 1, 'Quia omnis autem eli', 'Atque qui atque est ', 'Doloremque ea conseq', 63, 67, 74, 1, '2021-05-13 02:53:56'),
(79, '16208744623124', 'Kay', 'Emery Jarvis', 'Booth', 'Quam in aliquam accu', 'Ut voluptate dolorem', 'male', '01-Oct-1976', '688', 'financial2@gmail.com', '5f4dcc3b5aa765d61d8327deb882cf99', 7, 4, 'regular', 4, 'Ursula Watts', 'Baxter Pacheco', 'Galvin Wallace', 'Quis laboris occaeca', 1, 1, 'Sit officia ex qui ', 'Enim voluptatem rat', 'Incidunt culpa atq', 63, 67, 74, 1, '2021-05-13 02:54:22'),
(80, '162087448481', 'Guinevere', 'Lillith Mitchell', 'Dillon', 'Quasi ut et et volup', 'Maiores quam perspic', 'male', '01-Jan-1976', '556', 'financial3@gmail.com', '5f4dcc3b5aa765d61d8327deb882cf99', 7, 29, 'regular', 9, 'Melinda Day', 'Shana Hamilton', 'Jolene Lawson', 'Maxime quidem occaec', 1, 1, 'Sint quisquam vero r', 'Ratione beatae cupid', 'Mollit consectetur ', 63, 67, 74, 1, '2021-05-13 02:54:44'),
(81, '16210403791219', 'Hanna', 'Chastity Dickerson', 'Mitchell', 'Laboris veniam vero', 'Aut aut sit velit n', 'female', '30-Sep-1977', '152', 'treasurer@gmail.com', '5f4dcc3b5aa765d61d8327deb882cf99', 4, 44, 'job_order', 4, 'Virginia Hardy', 'Elton Estrada', 'Claire Beasley', 'Cillum excepteur sus', 1, 1, 'Facilis amet obcaec', 'Incididunt aute dign', 'Commodi deleniti seq', 63, 67, 74, 1, '2021-05-15 00:59:39'),
(83, '1621649608733', 'pikachu', 'Chaney Hale', 'Spencer', 'Est soluta sint dolo', 'Reprehenderit velit', 'male', '12-Jan-1972', '312', 'pikachu@gmail.com', '5f4dcc3b5aa765d61d8327deb882cf99', 2, 43, 'job_order', 8, 'Amela Matthews', 'Benjamin Lancaster', 'Quyn Vang', 'Aut voluptas non arc', 1, 1, 'Pariatur Facere lau', 'Sed quos et labore q', 'Explicabo Libero ex', 63, 67, 74, 1, '2021-05-22 02:13:28'),
(84, '16216625941991', 'wendell', 'chan', 'suazo', 'Doloremque tempore ', 'Hic dolores in volup', 'male', '13-Jan-1978', '293', 'wendellchansuazo11@gmail.com', '5f4dcc3b5aa765d61d8327deb882cf99', 2, 13, 'regular', 4, 'Lois Wolf', 'Blaine Hamilton', 'Fuller Albert', 'Corrupti architecto', 1, 1, 'Eos odio sed nostrum', 'Dolores dolor impedi', 'Aut possimus est i', 63, 67, 74, 1, '2021-05-22 05:49:54');

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int(11) NOT NULL,
  `receipt_number` varchar(255) NOT NULL,
  `voucher_category_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `care_of` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `voucher_categories`
--

CREATE TABLE `voucher_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `voucher_categories`
--

INSERT INTO `voucher_categories` (`id`, `name`) VALUES
(3, 'FEA EXPENSES'),
(4, 'SUPPORT TO FEA MEMBER'),
(5, 'REGULAR LOAN'),
(6, 'CHARACTER LOAN'),
(7, 'SAVINGS WITHDRAW'),
(8, 'FIXED DEPOSIT WITHDRAW');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fixed_deposits`
--
ALTER TABLE `fixed_deposits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fixed_deposits_users_id_foreign` (`user_id`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loans_loan_types_id_foreign` (`loan_type_id`),
  ADD KEY `loans_user_id_foreign` (`user_id`);

--
-- Indexes for table `loan_comakers`
--
ALTER TABLE `loan_comakers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_penalties`
--
ALTER TABLE `loan_penalties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_types`
--
ALTER TABLE `loan_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_loan_id_foreign` (`loan_id`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `savings`
--
ALTER TABLE `savings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_position_id_foreign` (`position_id`),
  ADD KEY `users_department_id_foreign` (`department_id`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voucher_categories`
--
ALTER TABLE `voucher_categories`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `fixed_deposits`
--
ALTER TABLE `fixed_deposits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `loan_comakers`
--
ALTER TABLE `loan_comakers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `loan_penalties`
--
ALTER TABLE `loan_penalties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `loan_types`
--
ALTER TABLE `loan_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `savings`
--
ALTER TABLE `savings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `voucher_categories`
--
ALTER TABLE `voucher_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fixed_deposits`
--
ALTER TABLE `fixed_deposits`
  ADD CONSTRAINT `fixed_deposits_users_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `loans_loan_types_id_foreign` FOREIGN KEY (`loan_type_id`) REFERENCES `loan_types` (`id`),
  ADD CONSTRAINT `loans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_loan_id_foreign` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  ADD CONSTRAINT `users_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
