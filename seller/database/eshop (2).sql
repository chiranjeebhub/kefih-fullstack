-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 25, 2019 at 11:28 AM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `isdeleted` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `logo`, `banner_image`, `created_at`, `updated_at`, `isdeleted`) VALUES
(1, 'Apple', 'apple_logo.png', 'apple_banner.jpg', '2019-07-17 09:54:20', '2019-07-17 11:03:07', 0),
(2, 'Samsung', 'apple_logo.png', 'apple_banner.jpg', '2019-07-17 10:30:35', '2019-07-17 11:03:03', 0),
(3, 'Lenovo', 'apple_logo.png', 'apple_banner.jpg', '2019-07-17 10:30:35', '2019-07-18 09:16:14', 1),
(4, 'mi', 'brandlogo20190718093212.jpg', 'brandbanner20190717124543.png', '2019-07-17 07:15:43', '2019-07-18 04:02:12', 0),
(5, 'Xolo', 'brandlogo20190717124926.png', 'brandbanner20190717124926.png', '2019-07-17 07:19:26', '2019-07-17 07:19:26', 0),
(6, 'Nokia', 'brandlogo20190717124945.png', 'brandbanner20190717124945.png', '2019-07-17 07:19:45', '2019-07-17 07:19:45', 0),
(7, 'Nike', 'brandlogo20190717125158.png', 'brandbanner20190717125158.png', '2019-07-17 07:21:58', '2019-07-17 07:21:58', 0),
(8, 'Oneplus', 'brandlogo20190717125303.png', 'brandbanner20190717125303.jpg', '2019-07-17 07:23:03', '2019-07-17 07:23:03', 0),
(9, 'intex', 'brandlogo20190717125328.jpg', 'brandbanner20190717125328.jpg', '2019-07-17 07:23:28', '2019-07-17 07:23:28', 0),
(10, 'inext', 'brandlogo20190717125347.jpg', 'brandbanner20190717125347.jpg', '2019-07-17 07:23:47', '2019-07-17 07:23:47', 0),
(11, 'Microsoft', 'brandlogo20190717125414.jpg', 'brandbanner20190717125414.jpg', '2019-07-17 07:24:14', '2019-07-17 07:24:14', 0),
(12, 'Dell', 'brandlogo20190717125433.jpg', 'brandbanner20190717125433.jpg', '2019-07-17 07:24:33', '2019-07-17 07:24:33', 0),
(13, 'Asus', 'brandlogo20190717125453.jpg', 'brandbanner20190717125453.jpg', '2019-07-17 07:24:53', '2019-07-17 07:24:53', 0),
(14, 'Vivo', 'brandlogo20190717125515.jpg', 'brandbanner20190717125515.jpg', '2019-07-17 07:25:15', '2019-07-17 07:25:15', 0),
(15, 'Nova', 'brandlogo20190719081302.jpg', 'brandbanner20190719081302.jpg', '2019-07-19 02:43:02', '2019-07-19 02:43:02', 0),
(16, 'Anurag Kumar', 'brandlogo20190725052418.jpg', 'brandbanner20190725052418.jpg', '2019-07-24 23:54:18', '2019-07-24 23:54:18', 0);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `cat_url` varchar(255) DEFAULT NULL,
  `root_cat_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `isdeleted` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `logo`, `banner_image`, `cat_url`, `root_cat_id`, `status`, `created_at`, `updated_at`, `isdeleted`) VALUES
(1, 'Smart Tv', 'categorylogo20190720061940.jpg', 'categorybanner20190720061940.jpg', 'Intex-tv-HD', 5, 1, '2019-07-20 00:49:40', '2019-07-20 08:10:39', 0),
(2, 'LED Tv', 'categorylogo20190720061940.jpg', 'categorybanner20190720061940.jpg', 'LED TV', 5, 1, '2019-07-20 00:49:40', '2019-07-20 08:10:39', 0),
(3, 'HD LED Tv', 'categorylogo20190720061940.jpg', 'categorybanner20190720061940.jpg', 'HD LED TV', 5, 1, '2019-07-20 00:49:40', '2019-07-20 08:10:39', 0);

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

CREATE TABLE `colors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `isdeleted` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`id`, `name`, `created_at`, `updated_at`, `isdeleted`) VALUES
(1, 'RED', '2019-07-20 13:17:15', NULL, 0),
(2, 'Green', '2019-07-20 13:17:15', NULL, 0),
(3, 'blue', '2019-07-20 13:17:15', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `isdeleted` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `name`, `value`, `created_at`, `updated_at`, `isdeleted`) VALUES
(1, 'View Customer', 1, '2019-07-18 09:47:54', NULL, 0),
(2, 'Add Customer', 2, '2019-07-18 09:47:54', '2019-07-18 09:48:03', 0),
(3, 'Edit Customer', 3, '2019-07-18 09:48:28', NULL, 0),
(4, 'Delete Customer', 4, '2019-07-18 09:48:28', NULL, 0),
(5, 'View Product', 1, '2019-07-18 09:49:13', NULL, 0),
(6, 'Add Product', 2, '2019-07-18 09:49:13', NULL, 0),
(7, 'Edit Product', 3, '2019-07-18 09:49:35', NULL, 0),
(8, 'Delete Product', 4, '2019-07-18 09:49:35', '2019-07-19 05:03:13', 0);

-- --------------------------------------------------------

--
-- Table structure for table `modules_rights`
--

CREATE TABLE `modules_rights` (
  `id` int(11) NOT NULL,
  `user_role_id` int(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `isdeleted` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `modules_rights`
--

INSERT INTO `modules_rights` (`id`, `user_role_id`, `module_id`, `created_at`, `updated_at`, `isdeleted`) VALUES
(22, 1, 1, '2019-07-19 07:35:34', NULL, 0),
(23, 1, 6, '2019-07-19 07:35:34', NULL, 0),
(24, 1, 7, '2019-07-19 07:35:34', NULL, 0),
(25, 1, 4, '2019-07-19 07:35:34', NULL, 0),
(26, 1, 5, '2019-07-19 07:35:34', NULL, 0),
(27, 1, 2, '2019-07-19 07:35:34', NULL, 0),
(28, 1, 3, '2019-07-19 07:35:34', NULL, 0),
(29, 1, 8, '2019-07-19 07:35:34', NULL, 0),
(34, 2, 1, '2019-07-19 07:37:18', NULL, 0),
(35, 2, 2, '2019-07-19 07:37:18', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `root_categories`
--

CREATE TABLE `root_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `cat_url` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `isdeleted` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `root_categories`
--

INSERT INTO `root_categories` (`id`, `name`, `logo`, `banner_image`, `cat_url`, `status`, `created_at`, `updated_at`, `isdeleted`) VALUES
(1, 'Cloth For man', 'categorylogo20190719095914.png', 'categorybanner20190719095914.png', NULL, 0, '2019-07-19 04:22:12', '2019-07-19 07:25:01', 0),
(2, 'Phone1', 'categorylogo20190719122647.jpg', 'categorybanner20190719122647.jpg', NULL, 0, '2019-07-19 04:24:12', '2019-07-19 07:24:28', 0),
(3, 'Shoe', 'categorylogo20190719122809.jpg', 'categorybanner20190719122752.jpg', NULL, 1, '2019-07-19 06:57:52', '2019-07-20 00:16:23', 0),
(4, 'App', 'categorylogo20190720050020.jpg', 'categorybanner20190720050020.jpg', 'App-new-tab-is-the-new-url', 1, '2019-07-19 23:30:20', '2019-07-20 00:48:03', 0),
(5, 'TV', 'categorylogo20190720053734.png', 'root_category_banner20190720053942.jpg', 'Intex-tv', 1, '2019-07-20 00:07:34', '2019-07-20 08:10:39', 0);

--
-- Triggers `root_categories`
--
DELIMITER $$
CREATE TRIGGER `updateOnroot` AFTER UPDATE ON `root_categories` FOR EACH ROW UPDATE `categories` 
SET 
`status`= NEW.`status`,
`isdeleted`= NEW.`isdeleted`
WHERE `root_cat_id`=NEW.`id`
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--

CREATE TABLE `sizes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `isdeleted` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sizes`
--

INSERT INTO `sizes` (`id`, `name`, `created_at`, `updated_at`, `isdeleted`) VALUES
(1, '2', '2019-07-20 07:22:11', '2019-07-20 12:55:45', 0),
(2, '23', '2019-07-20 13:22:02', NULL, 0),
(3, '45', '2019-07-20 13:22:02', NULL, 0),
(4, '56', '2019-07-20 13:22:02', NULL, 0),
(6, '12', '2019-07-20 13:23:28', NULL, 0),
(7, '34', '2019-07-20 13:23:28', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE `sub_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `cat_url` varchar(255) DEFAULT NULL,
  `root_cat_id` int(11) DEFAULT NULL,
  `cat_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `isdeleted` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sub_categories`
--

INSERT INTO `sub_categories` (`id`, `name`, `logo`, `banner_image`, `cat_url`, `root_cat_id`, `cat_id`, `status`, `created_at`, `updated_at`, `isdeleted`) VALUES
(1, 'SEMI HD TV', 'sub_category_logo20190720095605.png', 'sub_category_banner20190720103020.png', 'SEMI-HD-TV', 5, 1, 1, '2019-07-20 04:26:05', '2019-07-20 10:59:25', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sub_sub_categories`
--

CREATE TABLE `sub_sub_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `cat_url` varchar(255) DEFAULT NULL,
  `root_cat_id` int(11) DEFAULT NULL,
  `cat_id` int(11) DEFAULT NULL,
  `sub_cat_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `isdeleted` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sub_sub_categories`
--

INSERT INTO `sub_sub_categories` (`id`, `name`, `logo`, `banner_image`, `cat_url`, `root_cat_id`, `cat_id`, `sub_cat_id`, `status`, `created_at`, `updated_at`, `isdeleted`) VALUES
(1, 'Nano TV', 'sub_category_logo20190720110428.png', 'sub_category_banner20190720110428.png', 'nano-tv-url-curved-mode', 5, 1, 1, 0, '2019-07-20 05:34:28', '2019-07-20 05:51:50', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'yogendra', 'yogendraverma325@gmail.com', NULL, '$2y$10$WC6EGm9/CHJmu7UfGrIBVuSlmB/DeGzYlLgtpcT9eW4InffQZl/Ny', NULL, '2019-07-17 01:22:41', '2019-07-17 01:22:41'),
(2, 'yogendra', 'test@test.com', NULL, '$2y$10$t5uA3.UElaanfqI2RTCARuIsbxP12G9RNV4UiUFpwaMC1STC178QS', NULL, '2019-07-17 02:51:29', '2019-07-17 02:51:29');

-- --------------------------------------------------------

--
-- Table structure for table `user_role_type`
--

CREATE TABLE `user_role_type` (
  `id` int(11) NOT NULL,
  `user_role_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `isdeleted` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_role_type`
--

INSERT INTO `user_role_type` (`id`, `user_role_name`, `created_at`, `updated_at`, `isdeleted`) VALUES
(1, 'Admin', '2019-07-18 10:28:31', NULL, 0),
(2, 'Vendor', '2019-07-18 10:28:31', NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name_2` (`name`),
  ADD KEY `name` (`name`),
  ADD KEY `name_3` (`name`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modules_rights`
--
ALTER TABLE `modules_rights`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `root_categories`
--
ALTER TABLE `root_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_sub_categories`
--
ALTER TABLE `sub_sub_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_role_type`
--
ALTER TABLE `user_role_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `modules_rights`
--
ALTER TABLE `modules_rights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `root_categories`
--
ALTER TABLE `root_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sizes`
--
ALTER TABLE `sizes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sub_sub_categories`
--
ALTER TABLE `sub_sub_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_role_type`
--
ALTER TABLE `user_role_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
