-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 27, 2019 at 05:53 PM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mycart2door`
--
CREATE DATABASE IF NOT EXISTS `mycart2door` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `mycart2door`;

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `stream` varchar(50) NOT NULL,
  `role` varchar(1) NOT NULL,
  `status` int(1) NOT NULL,
  `date_reg` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `firstname`, `lastname`, `username`, `password`, `stream`, `role`, `status`, `date_reg`) VALUES
(1, 'Kayode', 'Kumapayi', 'kaykums', 'c5ea5a09e446c7a106a5b1397ab0ae6a', 'Admin', 'A', 1, '2019-05-20'),
(2, 'Olayinka', 'Olayinka', 'olayinka', 'de2ac121e912071fa2ba40991e2cc7b7', 'Custom Order', 'U', 1, '2019-05-24');

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

CREATE TABLE `currency` (
  `id` int(11) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `rate` double NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `currency`
--

INSERT INTO `currency` (`id`, `currency`, `rate`) VALUES
(1, 'GBP', 450),
(2, 'EUR', 410),
(3, 'USD', 360);

-- --------------------------------------------------------

--
-- Table structure for table `custom_order`
--

CREATE TABLE `custom_order` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `store` varchar(100) NOT NULL,
  `product` varchar(100) NOT NULL,
  `product_url` varchar(255) NOT NULL,
  `color` varchar(50) NOT NULL,
  `size` int(3) NOT NULL,
  `item_id` varchar(255) NOT NULL,
  `quantity` int(3) NOT NULL,
  `shipping_locale` varchar(50) NOT NULL,
  `shipping_address` varchar(255) NOT NULL,
  `order_date` date NOT NULL,
  `status` varchar(10) NOT NULL,
  `personel` varchar(20) DEFAULT NULL,
  `currency` int(1) NOT NULL,
  `rate` double NOT NULL,
  `src_price` double NOT NULL,
  `calculated_price` double NOT NULL,
  `vat` double NOT NULL,
  `shipping` double NOT NULL,
  `handling` double NOT NULL,
  `total_cost` double NOT NULL,
  `delivery` int(11) NOT NULL,
  `date_treated` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `custom_order`
--

INSERT INTO `custom_order` (`id`, `user_id`, `store`, `product`, `product_url`, `color`, `size`, `item_id`, `quantity`, `shipping_locale`, `shipping_address`, `order_date`, `status`, `personel`, `currency`, `rate`, `src_price`, `calculated_price`, `vat`, `shipping`, `handling`, `total_cost`, `delivery`, `date_treated`) VALUES
(204, 1, 'Jumia', 'Smart TV', 'https://jumia.com/electronics/tv/smart/pannasonic-12345', 'Black', 60, 'PAN-12345', 1, 'Agege Lagos Nigeria', 'sdvbsmndvbsmndsdv', '2019-07-12', 'Treated', '1', 3, 360, 10, 3600, 5, 2500, 3600, 9880, 7, '2019-07-15'),
(202, 2, 'Sketchers', 'Snickers', 'https://sketchers.com/mens-wear/shoes/snickers', 'blue', 43, 'SNK100', 1, 'Ajeromi-Ifelodun Lagos Nigeria', '11, Engr. Obaalaye Street, Hilltop Estate, Aboru, Iyana-Ipaja', '2019-07-11', 'Treated', '1', 3, 360, 150, 54000, 5, 3200, 3600, 63500, 14, '2019-07-12');

-- --------------------------------------------------------

--
-- Table structure for table `shipping`
--

CREATE TABLE `shipping` (
  `id` int(11) NOT NULL,
  `country` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `lga` varchar(50) NOT NULL,
  `amount` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `shipping`
--

INSERT INTO `shipping` (`id`, `country`, `state`, `lga`, `amount`) VALUES
(22, 'Nigeria', 'Lagos', 'Ajeromi-Ifelodun', 3200),
(21, 'Nigeria', 'Lagos', 'Agege', 2500);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `title` varchar(20) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `ref_email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `country` varchar(100) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `state` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `comment` text NOT NULL,
  `subscribe` int(1) NOT NULL,
  `date_reg` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `title`, `firstname`, `lastname`, `email`, `ref_email`, `password`, `country`, `mobile`, `state`, `city`, `comment`, `subscribe`, `date_reg`) VALUES
(1, 'Mr.', 'Olayinka', 'Olayinka', 'iamyinkaolayinka@gmail.com', 'iamolayinka@yahoo.com', 'de2ac121e912071fa2ba40991e2cc7b7', 'Nigeria', '08055276169', 'Lagos', 'Iyana-Ipaja', 'Internet Search', 1, '2019-04-16'),
(2, 'Mr', 'Kayode', 'Kumapayi', 'kaykums@gmail.com', 'kayode.kumapayi@yahoo.com', 'c5ea5a09e446c7a106a5b1397ab0ae6a', 'Nigeria', '08000044442', 'Lagos', 'Ikeja', 'Nice', 1, '2019-05-25');

-- --------------------------------------------------------

--
-- Table structure for table `wallet`
--

CREATE TABLE `wallet` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `wallet_id` varchar(18) NOT NULL,
  `amount` double NOT NULL DEFAULT '0',
  `date_created` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wallet`
--

INSERT INTO `wallet` (`id`, `user_id`, `wallet_id`, `amount`, `date_created`) VALUES
(6, 1, 'FcmkRrg7bJ8QYdLF', 0, '2019-05-13'),
(7, 2, '8CjKf2KGD41Dft0n', 0, '2019-06-06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_order`
--
ALTER TABLE `custom_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shipping`
--
ALTER TABLE `shipping`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wallet`
--
ALTER TABLE `wallet`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `wallet_id` (`wallet_id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `currency`
--
ALTER TABLE `currency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `custom_order`
--
ALTER TABLE `custom_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=209;
--
-- AUTO_INCREMENT for table `shipping`
--
ALTER TABLE `shipping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `wallet`
--
ALTER TABLE `wallet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
