-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: danielbeck.dk.mysql.service.one.com:3306
-- Generation Time: Jun 10, 2021 at 03:59 PM
-- Server version: 10.3.29-MariaDB-1:10.3.29+maria~focal
-- PHP Version: 7.2.24-0ubuntu0.18.04.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `danielbeck_dkpurplescout`
--

-- --------------------------------------------------------

--
-- Table structure for table `addons`
--

CREATE TABLE `addons` (
  `addon_id` tinyint(6) NOT NULL,
  `addon_price` double NOT NULL,
  `addon_name` varchar(50) NOT NULL,
  `addon_description` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `addons`
--

INSERT INTO `addons` (`addon_id`, `addon_price`, `addon_name`, `addon_description`) VALUES
(1, 30, '3D style', 'An extra 3D style modul'),
(2, 10, 'Style variation', 'Add an extra variation to an existing style. This could be a new color or texture');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` tinyint(6) NOT NULL,
  `customer_first_name` varchar(50) NOT NULL,
  `customer_last_name` varchar(50) NOT NULL,
  `customer_company_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `customer_password` varchar(100) NOT NULL,
  `customer_company_cvr` varchar(20) NOT NULL,
  `customer_city` varchar(100) DEFAULT NULL,
  `customer_address` varchar(100) DEFAULT NULL,
  `customer_country` varchar(100) DEFAULT NULL,
  `customer_postcode` varchar(100) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `customer_confirm_code` varchar(100) NOT NULL,
  `customer_confirmed` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `customer_first_name`, `customer_last_name`, `customer_company_name`, `customer_email`, `customer_password`, `customer_company_cvr`, `customer_city`, `customer_address`, `customer_country`, `customer_postcode`, `customer_phone`, `customer_confirm_code`, `customer_confirmed`) VALUES
(109, 'Søren', 'Rembøll', 'KEA', 'Soren@remboll.dk', '$2y$10$ExNmPaucnqhTlEl.5T5USuWm/ZlLrHxyUsr73llvipVRRdEGsyaH2', '44444444', 'Brøndby', 'Brøndbyvestervej 62A, St3', 'Denmark', '2605', '+4522258809', '2950a482129f27d727e7a4b4b7e1b4f4cd8d27ac541b785e447b4c81b32e8a89', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customer_addons`
--

CREATE TABLE `customer_addons` (
  `customer_addon_id` tinyint(6) NOT NULL,
  `customer_id` tinyint(6) NOT NULL,
  `addon_id` tinyint(6) NOT NULL,
  `addon_amount` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `customer_products`
--

CREATE TABLE `customer_products` (
  `customer_products_id` tinyint(6) NOT NULL,
  `customer_id` tinyint(6) NOT NULL,
  `product_id` tinyint(6) NOT NULL,
  `subscription_start` int(100) NOT NULL,
  `subscription_total_length` int(50) NOT NULL,
  `subscription_end` int(100) NOT NULL,
  `subscription_active` tinyint(1) NOT NULL,
  `subscription_autorenew` tinyint(1) NOT NULL,
  `api_key` varchar(200) NOT NULL,
  `embed_link` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer_products`
--

INSERT INTO `customer_products` (`customer_products_id`, `customer_id`, `product_id`, `subscription_start`, `subscription_total_length`, `subscription_end`, `subscription_active`, `subscription_autorenew`, `api_key`, `embed_link`) VALUES
(94, 109, 1, 1623340461, 31556916, 1654897377, 1, 1, '4f1e1abcec7d0b788c9e4f0b303f02c579f8f476742e0aaa66f0caae7e3500cf', '<iframe src=\'https://danielbeck.dk/purple-scout/product.php?key=INSERT KEY HERE\' frameborder=\'0\'></iframe>');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` tinyint(6) NOT NULL,
  `customer_id` tinyint(6) NOT NULL,
  `order_date` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `order_date`) VALUES
(56, 109, 1623340461);

-- --------------------------------------------------------

--
-- Table structure for table `order_addons`
--

CREATE TABLE `order_addons` (
  `order_addons_id` tinyint(6) NOT NULL,
  `order_id` tinyint(6) NOT NULL,
  `addon_id` tinyint(6) NOT NULL,
  `order_addon_payed_price` varchar(20) NOT NULL,
  `addon_amount` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `order_products`
--

CREATE TABLE `order_products` (
  `order_products_id` tinyint(6) NOT NULL,
  `order_id` tinyint(6) NOT NULL,
  `product_id` tinyint(6) NOT NULL,
  `subscription_id` tinyint(6) NOT NULL,
  `order_products_payed_price` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_products`
--

INSERT INTO `order_products` (`order_products_id`, `order_id`, `product_id`, `subscription_id`, `order_products_payed_price`) VALUES
(45, 56, 1, 3, '2900');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` tinyint(6) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `product_price` varchar(20) NOT NULL,
  `product_image_url` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_price`, `product_image_url`) VALUES
(1, 'In-store Kiosk', '2900', './assets/images/product-1.jpg'),
(2, 'Mobile App', '1500', './assets/images/product-2.jpg'),
(3, 'Webcam', '999', './assets/images/product-3.png');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `subscription_id` tinyint(6) NOT NULL,
  `subscription_name` varchar(100) NOT NULL,
  `subscription_length` int(20) NOT NULL,
  `subscription_price` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`subscription_id`, `subscription_name`, `subscription_length`, `subscription_price`) VALUES
(1, '1-Month', 2629743, '30'),
(2, '6-Months', 15778458, '20'),
(3, '12-Months', 31556916, '10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addons`
--
ALTER TABLE `addons`
  ADD PRIMARY KEY (`addon_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `customer_cvr` (`customer_company_cvr`),
  ADD UNIQUE KEY `customer_email` (`customer_email`);

--
-- Indexes for table `customer_addons`
--
ALTER TABLE `customer_addons`
  ADD PRIMARY KEY (`customer_addon_id`),
  ADD KEY `addon_id` (`addon_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `customer_products`
--
ALTER TABLE `customer_products`
  ADD PRIMARY KEY (`customer_products_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_addons`
--
ALTER TABLE `order_addons`
  ADD PRIMARY KEY (`order_addons_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `addon_id` (`addon_id`);

--
-- Indexes for table `order_products`
--
ALTER TABLE `order_products`
  ADD PRIMARY KEY (`order_products_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `subscription_id` (`subscription_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`subscription_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addons`
--
ALTER TABLE `addons`
  MODIFY `addon_id` tinyint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` tinyint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `customer_addons`
--
ALTER TABLE `customer_addons`
  MODIFY `customer_addon_id` tinyint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `customer_products`
--
ALTER TABLE `customer_products`
  MODIFY `customer_products_id` tinyint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` tinyint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `order_addons`
--
ALTER TABLE `order_addons`
  MODIFY `order_addons_id` tinyint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `order_products`
--
ALTER TABLE `order_products`
  MODIFY `order_products_id` tinyint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` tinyint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `subscription_id` tinyint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer_addons`
--
ALTER TABLE `customer_addons`
  ADD CONSTRAINT `customer_addons_ibfk_1` FOREIGN KEY (`addon_id`) REFERENCES `addons` (`addon_id`),
  ADD CONSTRAINT `customer_addons_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`);

--
-- Constraints for table `customer_products`
--
ALTER TABLE `customer_products`
  ADD CONSTRAINT `customer_products_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `customer_products_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`);

--
-- Constraints for table `order_addons`
--
ALTER TABLE `order_addons`
  ADD CONSTRAINT `order_addons_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_addons_ibfk_2` FOREIGN KEY (`addon_id`) REFERENCES `addons` (`addon_id`);

--
-- Constraints for table `order_products`
--
ALTER TABLE `order_products`
  ADD CONSTRAINT `order_products_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_products_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `order_products_ibfk_3` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`subscription_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
