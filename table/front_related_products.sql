-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 04, 2024 at 02:25 PM
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
-- Table structure for table `front_related_products`
--

CREATE TABLE `front_related_products` (
  `id` int NOT NULL,
  `product_id` int DEFAULT '0',
  `parent_id` int NOT NULL DEFAULT '0',
  `status` tinyint DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `front_related_products`
--

INSERT INTO `front_related_products` (`id`, `product_id`, `parent_id`, `status`, `created_at`, `updated_at`) VALUES
(4, 55, 56, 0, '2022-07-18 07:35:38', '2022-07-18 07:35:38'),
(5, 45, 56, 0, '2022-07-18 07:35:44', '2022-07-18 07:35:44'),
(6, 54, 56, 0, '2022-07-18 07:35:52', '2022-07-18 07:35:52'),
(7, 73, 75, 0, '2023-03-12 21:08:19', '2023-03-12 21:08:19'),
(8, 74, 75, 0, '2023-03-12 21:08:19', '2023-03-12 21:08:19'),
(9, 190, 191, 0, '2023-11-04 19:04:57', '2023-11-04 19:04:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `front_related_products`
--
ALTER TABLE `front_related_products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `front_related_products`
--
ALTER TABLE `front_related_products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
