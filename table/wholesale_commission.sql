-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 04, 2024 at 02:24 PM
-- Server version: 8.0.36-cll-lve
-- PHP Version: 8.1.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stonesti_pandb_imports`
--

-- --------------------------------------------------------

--
-- Table structure for table `wholesale_commission`
--

CREATE TABLE `wholesale_commission` (
  `id` int NOT NULL,
  `min_amount` float DEFAULT NULL,
  `max_amount` float DEFAULT NULL,
  `discount_amount` float DEFAULT NULL,
  `discount_percentage` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `wholesale_commission`
--

INSERT INTO `wholesale_commission` (`id`, `min_amount`, `max_amount`, `discount_amount`, `discount_percentage`, `created_at`, `updated_at`) VALUES
(1, 130, 5000, 100, 20, '2022-06-13 08:13:26', '2022-06-13 08:13:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wholesale_commission`
--
ALTER TABLE `wholesale_commission`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wholesale_commission`
--
ALTER TABLE `wholesale_commission`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
