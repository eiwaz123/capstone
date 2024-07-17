-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2024 at 11:31 AM
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
-- Database: `dbhofin`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_face`
--

CREATE TABLE `tbl_face` (
  `face_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `face_pic` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_face`
--

INSERT INTO `tbl_face` (`face_id`, `user_id`, `face_pic`) VALUES
(1, 1, 'face/labels/Jet Dela Cruz Sebastian/'),
(3, 2, 'face/labels/Henry Q. Factura/');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_property`
--

CREATE TABLE `tbl_property` (
  `property_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `id_no` varchar(255) DEFAULT NULL,
  `blk_no` int(11) DEFAULT NULL,
  `lot_no` int(11) DEFAULT NULL,
  `homelot_area` int(11) DEFAULT NULL,
  `open_space` int(11) DEFAULT NULL,
  `sharein_loan` int(11) DEFAULT NULL,
  `principal_interest` int(11) DEFAULT NULL,
  `MRI` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_transaction`
--

CREATE TABLE `tbl_transaction` (
  `transac_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `balance_debt` int(11) DEFAULT NULL,
  `transc_type` varchar(255) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `is_verified` varchar(10) NOT NULL DEFAULT 'no',
  `code` varchar(255) DEFAULT NULL,
  `proof` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_useracc`
--

CREATE TABLE `tbl_useracc` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `is_admin` varchar(10) NOT NULL DEFAULT 'no',
  `is_deleted` varchar(10) NOT NULL DEFAULT 'no',
  `is_verified` varchar(10) NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_useracc`
--

INSERT INTO `tbl_useracc` (`user_id`, `username`, `password`, `email`, `is_admin`, `is_deleted`, `is_verified`) VALUES
(1, '20240001', '$2y$10$i39AYN.OEx4KSE1cidzwx.bVccvE1GZGd3lum9Kysg8U1DrNBHzIa', 'jetsebastian4@gmail.com', 'no', 'no', 'no'),
(2, '20240002', '$2y$10$i39AYN.OEx4KSE1cidzwx.bVccvE1GZGd3lum9Kysg8U1DrNBHzIa', 'jetsebastian4@gmail.com', 'no', 'no', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_userinfo`
--

CREATE TABLE `tbl_userinfo` (
  `userinfo_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `given_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `bday` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_userinfo`
--

INSERT INTO `tbl_userinfo` (`userinfo_id`, `user_id`, `given_name`, `middle_name`, `last_name`, `gender`, `bday`) VALUES
(1, 1, 'Jet', 'Dela Cruz', 'Sebastian', 'Male', '2002-01-02'),
(2, 2, 'Henry', 'Q', 'Factura', 'Male', '2002-01-02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_face`
--
ALTER TABLE `tbl_face`
  ADD PRIMARY KEY (`face_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_property`
--
ALTER TABLE `tbl_property`
  ADD PRIMARY KEY (`property_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_transaction`
--
ALTER TABLE `tbl_transaction`
  ADD PRIMARY KEY (`transac_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_useracc`
--
ALTER TABLE `tbl_useracc`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `tbl_userinfo`
--
ALTER TABLE `tbl_userinfo`
  ADD PRIMARY KEY (`userinfo_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_face`
--
ALTER TABLE `tbl_face`
  MODIFY `face_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_property`
--
ALTER TABLE `tbl_property`
  MODIFY `property_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_transaction`
--
ALTER TABLE `tbl_transaction`
  MODIFY `transac_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_useracc`
--
ALTER TABLE `tbl_useracc`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_userinfo`
--
ALTER TABLE `tbl_userinfo`
  MODIFY `userinfo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_face`
--
ALTER TABLE `tbl_face`
  ADD CONSTRAINT `tbl_face_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_useracc` (`user_id`);

--
-- Constraints for table `tbl_property`
--
ALTER TABLE `tbl_property`
  ADD CONSTRAINT `tbl_property_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_useracc` (`user_id`);

--
-- Constraints for table `tbl_transaction`
--
ALTER TABLE `tbl_transaction`
  ADD CONSTRAINT `tbl_transaction_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_useracc` (`user_id`);

--
-- Constraints for table `tbl_userinfo`
--
ALTER TABLE `tbl_userinfo`
  ADD CONSTRAINT `tbl_userinfo_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_useracc` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
