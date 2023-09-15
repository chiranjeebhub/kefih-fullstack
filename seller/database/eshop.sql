-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 05, 2019 at 06:45 AM
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
(3, 'Apple', '', '', '2019-07-25 07:18:35', '2019-08-02 09:28:55', 0),
(4, 'Micromax', NULL, NULL, '2019-08-02 09:29:06', NULL, 0),
(5, 'Lenovo', NULL, NULL, '2019-08-02 09:29:25', NULL, 0),
(6, 'Lava', NULL, NULL, '2019-08-02 09:29:25', NULL, 0);

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
  `parent_id` int(11) DEFAULT 0,
  `status` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `isdeleted` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `logo`, `banner_image`, `cat_url`, `parent_id`, `status`, `created_at`, `updated_at`, `isdeleted`) VALUES
(1, 'Root', NULL, NULL, NULL, 0, 0, '2019-07-31 13:34:05', '2019-07-31 13:34:43', 0),
(27, 'TV', '', '', 'tv', 1, 0, '2019-08-01 07:23:45', '2019-08-01 07:23:45', 0),
(28, 'Laptop', '', '', 'laptop', 1, 0, '2019-08-01 07:24:04', '2019-08-01 07:24:04', 0),
(29, 'Cloth', '', '', 'cloth', 1, 0, '2019-08-01 07:24:19', '2019-08-01 07:24:19', 0),
(30, 'Micromax-tv', '', '', 'micromax-tv', 27, 0, '2019-08-01 07:24:42', '2019-08-01 07:24:42', 0),
(31, 'HP Laptop', '', '', 'hp-laptop', 28, 0, '2019-08-01 07:25:01', '2019-08-01 07:25:22', 0),
(32, 'Lenovo laptop', '', '', 'lenovo-laptop', 28, 0, '2019-08-01 07:25:53', '2019-08-01 07:25:53', 0),
(33, 'Acer Laptop', '', '', 'acer-laptop', 28, 0, '2019-08-01 07:26:12', '2019-08-01 07:26:12', 0),
(34, 'Dell laptop', '', '', 'dell-laptop', 28, 0, '2019-08-01 07:26:30', '2019-08-01 07:26:30', 0),
(35, 'Mens', '', '', 'mens', 29, 0, '2019-08-01 07:26:46', '2019-08-01 07:26:46', 0),
(36, 'women', '', '', 'women', 29, 0, '2019-08-01 07:27:00', '2019-08-01 07:27:00', 0),
(37, 'childs', '', '', 'childs', 29, 0, '2019-08-01 07:27:15', '2019-08-01 07:27:15', 0),
(38, 'Intex TV', '', '', 'Intex-tv-smart', 27, 0, '2019-08-01 07:31:54', '2019-08-01 07:32:52', 0),
(39, 'Intex smart tv', '', '', 'intex-smart-tv', 38, 0, '2019-08-01 08:01:40', '2019-08-01 08:01:40', 0),
(40, 'Intex smart dual atmos', '', '', 'intex-smart-dual-atmos', 39, 0, '2019-08-01 08:02:27', '2019-08-01 08:02:27', 0),
(41, 'mircomax simple', '', '', 'micromax-simple', 30, 0, '2019-08-01 08:04:14', '2019-08-01 08:04:14', 0),
(42, 'micromax smart tv', '', '', 'micromax-smart-tv', 30, 0, '2019-08-01 08:04:54', '2019-08-01 08:04:54', 0),
(43, 'micromax smart android tv', '', '', 'micromax-smart-android-tv', 42, 0, '2019-08-01 08:05:52', '2019-08-01 08:05:52', 0),
(44, 'micromax smart android tv version 2', '', '', 'micromax-smart-android-tv-version-2', 43, 0, '2019-08-01 08:06:14', '2019-08-01 08:06:14', 0),
(45, 'micromax simple with atmos', '', '', 'micromax-simple-with-atmos', 41, 0, '2019-08-01 08:06:50', '2019-08-01 08:06:50', 0),
(46, 'micromax simple with atmos dolby', '', '', 'micromax-simple-with-atmos-dolby', 45, 0, '2019-08-01 08:07:17', '2019-08-01 08:07:17', 0);

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
(3, 'blue', '2019-07-20 13:17:15', NULL, 0),
(5, 'magenta', '2019-07-26 05:13:08', NULL, 0),
(6, 'purple', '2019-07-26 05:13:08', NULL, 0),
(7, 'lime', '2019-07-26 05:14:07', NULL, 0),
(8, 'colasn', '2019-07-26 05:14:07', '2019-07-25 23:49:43', 0);

-- --------------------------------------------------------

--
-- Table structure for table `email_verification`
--

CREATE TABLE `email_verification` (
  `id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `email_verification`
--

INSERT INTO `email_verification` (`id`, `email`, `code`, `created_at`) VALUES
(2, 'yogendra@b2cmarketing.in', 'lbcDdGtkjlrGR26OlOxt', '2019-08-01 08:11:46');

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
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `isdeleted` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `name`, `created_at`, `updated_at`, `isdeleted`) VALUES
(1, 'View Customer', '2019-07-18 09:47:54', '2019-08-01 09:14:18', 0),
(2, 'View Products', '2019-07-18 09:47:54', '2019-08-01 09:14:24', 0);

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
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `user_role_id` int(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `add_permission` int(11) DEFAULT 0,
  `edit_permission` int(11) DEFAULT 0,
  `delete_permission` int(11) DEFAULT 0,
  `view_permission` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `isdeleted` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `user_role_id`, `module_id`, `add_permission`, `edit_permission`, `delete_permission`, `view_permission`, `created_at`, `updated_at`, `isdeleted`) VALUES
(1, 1, 1, 0, 0, 0, 0, '2019-08-01 09:15:20', NULL, 0),
(2, 1, 2, 0, 0, 0, 0, '2019-08-01 09:15:20', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `phone_otp`
--

CREATE TABLE `phone_otp` (
  `id` int(11) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `otp` varchar(255) DEFAULT NULL,
  `expired_on` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phone_otp`
--

INSERT INTO `phone_otp` (`id`, `phone`, `otp`, `expired_on`) VALUES
(6, '7017734526', '909416', '2019-08-01 08:21:46'),
(7, '9876543210', '933239', '2019-07-31 10:54:29');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `long_description` mediumtext DEFAULT NULL,
  `sku` varchar(25) DEFAULT NULL,
  `weight` varchar(25) DEFAULT NULL,
  `hsn_code` varchar(20) DEFAULT NULL,
  `prd_sts` int(11) DEFAULT NULL,
  `tax` varchar(20) DEFAULT NULL,
  `price` varchar(25) DEFAULT NULL,
  `spcl_price` varchar(25) DEFAULT NULL,
  `spcl_from_date` date DEFAULT NULL,
  `spcl_to_date` date DEFAULT NULL,
  `tax_class` int(11) DEFAULT NULL,
  `meta_title` varchar(50) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  `qty` varchar(25) DEFAULT NULL,
  `manage_stock` int(11) DEFAULT NULL,
  `qty_out` varchar(255) DEFAULT NULL,
  `stock_availability` int(11) DEFAULT NULL,
  `product_brand` int(11) DEFAULT NULL,
  `material` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `isdeleted` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `short_description`, `long_description`, `sku`, `weight`, `hsn_code`, `prd_sts`, `tax`, `price`, `spcl_price`, `spcl_from_date`, `spcl_to_date`, `tax_class`, `meta_title`, `meta_description`, `qty`, `manage_stock`, `qty_out`, `stock_availability`, `product_brand`, `material`, `status`, `created_at`, `updated_at`, `isdeleted`) VALUES
(3, 'New Product', 'this is the short desciption', 'long description', '12', '345', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2019-08-02 06:06:11', '2019-08-02 06:14:11', 0),
(4, 'Apple', 'fefefe', 'efereggrgrrgrgrergrg', '1232', '465464556', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2019-08-02 06:09:43', '2019-08-02 06:14:06', 0),
(5, 'efr', 'rgrgrgr', 'rgrg', 'rgr', '345', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2019-08-02 06:10:42', '2019-08-02 06:14:15', 0),
(6, 'decfffef', 'eeeff', 'tbtbtbt', '232', '4434', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2019-08-02 06:14:52', '2019-08-02 06:14:52', 0),
(7, 'Phone12', 'effeeggrgrgreg', 'grgrgregre', 'rrr', 'eff', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2019-08-02 06:16:30', '2019-08-02 06:16:30', 0),
(8, '001apjain@gmail.comfefe', 'fegrgrgrg', 'tgtgtgtgt', '334434', '455', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2019-08-02 06:19:48', '2019-08-02 06:19:48', 0),
(9, '234324324', '43f4f4f4f', 'vtvtrvt', '3234', '334234', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2019-08-02 06:20:30', '2019-08-02 06:20:30', 0),
(10, '001apjain@gmail.comdew', 'wrrr34r', 'ffrfrrg', 'frfef', '3r334', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2019-08-02 06:22:17', '2019-08-02 06:22:17', 0),
(11, 'Test Product', 'this is the short description', 'this is the long desciption', '30', '1000', '123', 0, '0', '2321', '23', '2019-08-04', '2019-08-23', 0, 'this is the meta title is changed', 'this is the meta description changed description from edit method', '231', 1, '5', 1, 3, 1, 0, '2019-08-02 06:57:51', '2019-08-03 10:11:42', 0),
(12, 'Test product 2 name', 'this is short description', 'this is the long description', '12', '213', '123', 1, NULL, '23', '23', '2019-08-21', '2019-08-28', 1, 'this is the meta title', 'this is meta description', '12', 1, '2', 1, 3, 1, 0, '2019-08-03 11:26:02', '2019-08-03 11:28:49', 0);

-- --------------------------------------------------------

--
-- Table structure for table `product_attributes`
--

CREATE TABLE `product_attributes` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `size_id` int(11) DEFAULT NULL,
  `color_id` int(11) DEFAULT NULL,
  `qty` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_attributes`
--

INSERT INTO `product_attributes` (`id`, `product_id`, `size_id`, `color_id`, `qty`) VALUES
(14, 11, 1, 1, '123'),
(15, 11, 2, 2, '10'),
(16, 11, 3, 3, '34'),
(17, 12, 1, 1, '34'),
(18, 12, 2, 2, '45');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `cat_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `product_id`, `cat_id`) VALUES
(52, 11, 27),
(53, 11, 28),
(54, 11, 29),
(55, 12, 28),
(56, 12, 29);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image`) VALUES
(77, 11, '1564837059-874.png'),
(78, 11, '1564838321-789.jpg'),
(79, 11, '1564838321-109.jpg'),
(80, 11, '1564838322-857.jpg'),
(81, 11, '1564838322-446.jpg'),
(82, 11, '1564838322-853.jpg'),
(83, 11, '1564838323-248.jpg'),
(84, 11, '1564838323-142.jpg'),
(85, 11, '1564838323-561.jpg');

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
(7, '34', '2019-07-20 13:23:28', NULL, 0),
(8, '78', '2019-07-26 05:28:51', NULL, 0),
(9, '123', '2019-07-26 05:29:02', NULL, 0),
(10, '98762', '2019-07-26 05:29:02', '2019-07-26 00:00:43', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_permission`
--

CREATE TABLE `tbl_permission` (
  `fld_permission_id` int(11) NOT NULL,
  `fld_admin_users_id` int(11) NOT NULL,
  `fld_modules_id` int(11) NOT NULL,
  `fld_permission_view` tinyint(1) NOT NULL DEFAULT 0,
  `fld_permission_add` tinyint(1) NOT NULL DEFAULT 0,
  `fld_permission_edit` tinyint(1) NOT NULL DEFAULT 0,
  `fld_permission_delete` tinyint(1) NOT NULL DEFAULT 0,
  `fld_permission_approval` tinyint(1) NOT NULL DEFAULT 0,
  `fld_permission_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_permission`
--

INSERT INTO `tbl_permission` (`fld_permission_id`, `fld_admin_users_id`, `fld_modules_id`, `fld_permission_view`, `fld_permission_add`, `fld_permission_edit`, `fld_permission_delete`, `fld_permission_approval`, `fld_permission_date`) VALUES
(99, 3, 20, 1, 1, 1, 1, 1, '2019-03-01 18:32:39'),
(98, 3, 17, 1, 1, 1, 1, 1, '2019-03-01 18:32:39'),
(97, 3, 16, 1, 1, 1, 1, 1, '2019-03-01 18:32:39'),
(96, 3, 15, 1, 1, 1, 1, 1, '2019-03-01 18:32:39'),
(95, 3, 14, 1, 1, 1, 1, 1, '2019-03-01 18:32:39'),
(94, 3, 13, 1, 1, 1, 1, 1, '2019-03-01 18:32:39'),
(93, 3, 12, 1, 1, 1, 1, 1, '2019-03-01 18:32:39'),
(92, 3, 11, 1, 1, 1, 1, 1, '2019-03-01 18:32:39'),
(91, 3, 10, 1, 1, 1, 1, 1, '2019-03-01 18:32:39'),
(90, 3, 9, 1, 1, 1, 1, 1, '2019-03-01 18:32:39'),
(89, 3, 8, 1, 1, 1, 1, 1, '2019-03-01 18:32:39'),
(88, 3, 7, 1, 1, 1, 1, 1, '2019-03-01 18:32:39'),
(87, 3, 6, 1, 1, 1, 1, 1, '2019-03-01 18:32:39'),
(86, 3, 5, 1, 1, 1, 1, 1, '2019-03-01 18:32:39'),
(85, 3, 4, 1, 1, 1, 1, 1, '2019-03-01 18:32:39'),
(84, 3, 3, 1, 1, 1, 1, 1, '2019-03-01 18:32:39'),
(83, 3, 2, 1, 1, 1, 1, 1, '2019-03-01 18:32:39'),
(82, 3, 1, 1, 1, 1, 1, 1, '2019-03-01 18:32:39'),
(100, 3, 21, 1, 1, 1, 1, 1, '2019-03-01 18:32:39'),
(131, 4, 20, 1, 1, 1, 1, 0, '2019-03-07 00:38:38'),
(130, 4, 17, 1, 0, 0, 0, 0, '2019-03-07 00:38:38'),
(118, 1, 18, 1, 1, 1, 1, 1, '2019-03-01 18:34:38'),
(117, 1, 17, 1, 1, 1, 1, 1, '2019-03-01 18:34:38'),
(116, 1, 16, 1, 1, 1, 1, 1, '2019-03-01 18:34:38'),
(115, 1, 15, 1, 1, 1, 1, 1, '2019-03-01 18:34:38'),
(114, 1, 14, 1, 1, 1, 1, 1, '2019-03-01 18:34:38'),
(113, 1, 13, 1, 1, 1, 1, 1, '2019-03-01 18:34:38'),
(112, 1, 12, 1, 1, 1, 1, 1, '2019-03-01 18:34:38'),
(111, 1, 11, 1, 1, 1, 1, 1, '2019-03-01 18:34:38'),
(110, 1, 10, 1, 1, 1, 1, 1, '2019-03-01 18:34:38'),
(109, 1, 9, 1, 1, 1, 1, 1, '2019-03-01 18:34:38'),
(108, 1, 8, 1, 1, 1, 1, 1, '2019-03-01 18:34:38'),
(107, 1, 7, 1, 1, 1, 1, 1, '2019-03-01 18:34:38'),
(106, 1, 6, 1, 1, 1, 1, 1, '2019-03-01 18:34:38'),
(105, 1, 5, 1, 1, 1, 1, 1, '2019-03-01 18:34:38'),
(104, 1, 4, 1, 1, 1, 1, 1, '2019-03-01 18:34:38'),
(103, 1, 3, 1, 1, 1, 1, 1, '2019-03-01 18:34:38'),
(102, 1, 2, 1, 1, 1, 1, 1, '2019-03-01 18:34:38'),
(101, 1, 1, 1, 1, 1, 1, 1, '2019-03-01 18:34:38'),
(119, 1, 19, 1, 1, 1, 1, 1, '2019-03-01 18:34:38'),
(120, 1, 20, 1, 1, 1, 1, 1, '2019-03-01 18:34:38'),
(121, 1, 21, 1, 1, 1, 1, 1, '2019-03-01 18:34:38'),
(122, 1, 22, 1, 1, 1, 1, 1, '2019-03-01 18:34:38'),
(129, 4, 12, 1, 0, 0, 0, 0, '2019-03-07 00:38:38'),
(128, 4, 11, 1, 0, 0, 0, 0, '2019-03-07 00:38:38');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `f_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `l_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_role` int(11) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isdeleted` int(11) NOT NULL DEFAULT 0,
  `is_phone_verify` int(11) NOT NULL DEFAULT 0,
  `is_email_verify` int(11) NOT NULL DEFAULT 0,
  `application_level` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `f_name`, `l_name`, `username`, `email`, `phone`, `password`, `user_role`, `remember_token`, `isdeleted`, `is_phone_verify`, `is_email_verify`, `application_level`, `created_at`, `updated_at`) VALUES
(2, 'yogendra', 'verma', '', 'test@test.com', NULL, '$2y$10$t5uA3.UElaanfqI2RTCARuIsbxP12G9RNV4UiUFpwaMC1STC178QS', 0, NULL, 0, 0, 0, 0, '2019-07-17 02:51:29', '2019-07-17 02:51:29'),
(4, 'Anurag', 'Kumar', 'anr', 'anurag@b2cmarketing.in', NULL, '$2y$10$HqKOTpyV4cwhi.PHawTeN.OWaccmBElUf.mapWLVpkn8hMB/wAVmm', 2, NULL, 0, 1, 1, 0, '2019-07-29 07:48:53', '2019-07-29 07:48:53'),
(14, NULL, NULL, 'yogendra325', 'yogendra@b2cmarketing.in', '7017734526', '$2y$10$iRKkC1lL5pVCJM9UtVpvVOTEqdQzLmVoif1M.JWW1uwGqMxH/.3XO', 2, NULL, 0, 1, 1, 1, '2019-08-01 08:11:46', '2019-08-01 08:11:46');

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

-- --------------------------------------------------------

--
-- Table structure for table `vendor_info`
--

CREATE TABLE `vendor_info` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `selected_cats` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vendor_info`
--

INSERT INTO `vendor_info` (`id`, `vendor_id`, `selected_cats`) VALUES
(1, 14, '27,30,38,39,40,41,42,43,44,45,46');

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
-- Indexes for table `email_verification`
--
ALTER TABLE `email_verification`
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
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `phone_otp`
--
ALTER TABLE `phone_otp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_attributes`
--
ALTER TABLE `product_attributes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_permission`
--
ALTER TABLE `tbl_permission`
  ADD PRIMARY KEY (`fld_permission_id`);

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
-- Indexes for table `vendor_info`
--
ALTER TABLE `vendor_info`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `email_verification`
--
ALTER TABLE `email_verification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `phone_otp`
--
ALTER TABLE `phone_otp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_attributes`
--
ALTER TABLE `product_attributes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `sizes`
--
ALTER TABLE `sizes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `user_role_type`
--
ALTER TABLE `user_role_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vendor_info`
--
ALTER TABLE `vendor_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
