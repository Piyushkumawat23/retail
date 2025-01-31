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
-- Table structure for table `salesperson_order_products`
--

CREATE TABLE `salesperson_order_products` (
  `id` int NOT NULL,
  `customer_id` int NOT NULL DEFAULT '0',
  `category_id` int NOT NULL DEFAULT '0',
  `product_id` int NOT NULL DEFAULT '0',
  `qty` double(20,2) DEFAULT '0.00',
  `description` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `price` double(20,2) DEFAULT '0.00',
  `salesperson_id` int NOT NULL DEFAULT '0',
  `status` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `product_name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `customer_name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `category_name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `salesperson_order_products`
--

INSERT INTO `salesperson_order_products` (`id`, `customer_id`, `category_id`, `product_id`, `qty`, `description`, `price`, `salesperson_id`, `status`, `product_name`, `customer_name`, `category_name`, `created_at`, `updated_at`) VALUES
(1, 14, 4, 43, 150.00, 'add new', 14.00, 10, 'Confirmed', NULL, NULL, NULL, '2022-06-15 04:58:41', '2022-06-15 09:48:18'),
(2, 14, 4, 43, 150.00, 'sdfsf', 14.00, 10, 'Pending', 'VI Tool', 'new customer', 'Rings', '2022-06-17 03:58:56', '2022-06-17 03:58:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `salesperson_order_products`
--
ALTER TABLE `salesperson_order_products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `salesperson_order_products`
--
ALTER TABLE `salesperson_order_products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
