-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2025 at 03:04 AM
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
-- Database: `staywhere_db`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteOldReservations` ()   BEGIN
  DELETE FROM reservations 
  WHERE check_out < CURDATE(); -- Deletes reservations where checkout date has passed
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `amenities`
--

CREATE TABLE `amenities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `amenities`
--

INSERT INTO `amenities` (`id`, `name`, `icon_url`, `created_at`, `updated_at`) VALUES
(19, 'Free Wi-Fi', 'fas fa-wifi text-orange-500', '2025-05-19 16:50:13', '2025-05-19 16:50:13'),
(20, 'Free Parking', 'fas fa-parking text-orange-500', '2025-05-19 16:50:13', '2025-05-19 16:50:13'),
(21, 'Swimming Pool', 'fas fa-swimming-pool text-orange-500', '2025-05-19 16:50:13', '2025-05-19 16:50:13'),
(22, 'Air Conditioning', 'fas fa-snowflake text-orange-500', '2025-05-19 16:50:13', '2025-05-19 16:50:13'),
(23, 'Washing Machine', 'fas fa-tint text-orange-500', '2025-05-19 16:50:13', '2025-05-19 16:50:13'),
(24, 'Flat-screen TV', 'fas fa-tv text-orange-500', '2025-05-19 16:50:13', '2025-05-19 16:50:13'),
(25, 'Heating', 'fas fa-thermometer-half text-orange-500', '2025-05-19 16:50:13', '2025-05-19 16:50:13'),
(26, 'Coffee Maker', 'fas fa-mug-hot text-orange-500', '2025-05-19 16:50:13', '2025-05-19 16:50:13'),
(27, 'Hair Dryer', 'fas fa-wind text-orange-500', '2025-05-19 16:50:13', '2025-05-19 16:50:13'),
(28, 'Smoke Alarm', 'fas fa-bell text-orange-500', '2025-05-19 16:50:13', '2025-05-19 16:50:13'),
(29, 'First Aid Kit', 'fas fa-briefcase-medical text-orange-500', '2025-05-19 16:50:13', '2025-05-19 16:50:13'),
(30, 'Iron', 'fas fa-tshirt text-orange-500', '2025-05-19 16:50:13', '2025-05-19 16:50:13'),
(31, 'Pet Friendly', 'fas fa-paw text-orange-500', '2025-05-19 16:50:13', '2025-05-19 16:50:13'),
(32, 'Books & Games', 'fas fa-book text-orange-500', '2025-05-19 16:50:13', '2025-05-19 16:50:13'),
(33, '24/7 Support', 'fas fa-headset text-orange-500', '2025-05-19 16:50:13', '2025-05-19 16:50:13');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`id`, `name`, `icon_url`, `created_at`, `updated_at`) VALUES
(4, 'Bedrooms', 'fas fa-bed text-orange-500', '2025-05-19 16:48:22', '2025-05-19 16:48:22'),
(5, 'Kitchen', 'fas fa-utensils text-orange-500', '2025-05-19 16:48:22', '2025-05-19 16:48:22'),
(6, 'Bathrooms', 'fas fa-bath text-orange-500', '2025-05-19 16:48:22', '2025-05-19 16:48:22'),
(7, 'Living Room', 'fas fa-couch text-orange-500', '2025-05-19 16:48:22', '2025-05-19 16:48:22'),
(8, 'Balcony with Ocean View', 'fas fa-umbrella-beach text-orange-500', '2025-05-19 16:48:22', '2025-05-19 16:48:22'),
(9, 'Dining Area', 'fas fa-chair text-orange-500', '2025-05-19 16:48:22', '2025-05-19 16:48:22'),
(10, 'Private Garden', 'fas fa-seedling text-orange-500', '2025-05-19 16:48:22', '2025-05-19 16:48:22'),
(11, 'Garage', 'fas fa-warehouse text-orange-500', '2025-05-19 16:48:22', '2025-05-19 16:48:22'),
(12, 'Fireplace', 'fas fa-fire text-orange-500', '2025-05-19 16:48:22', '2025-05-19 16:48:22'),
(13, 'Workspace', 'fas fa-laptop-house text-orange-500', '2025-05-19 16:48:22', '2025-05-19 16:48:22'),
(14, 'Accessible Entrance', 'fas fa-wheelchair text-orange-500', '2025-05-19 16:48:22', '2025-05-19 16:48:22');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stay_id` bigint(20) UNSIGNED NOT NULL,
  `url` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_thumbnail` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `stay_id`, `url`, `created_at`, `updated_at`, `is_thumbnail`) VALUES
(2, 3, 'images/stays/seaside_1.jpg', NULL, NULL, 1),
(3, 3, 'images/stays/seaside_2.jpg', NULL, NULL, 0),
(4, 3, 'images/stays/seaside_3.jpg', NULL, NULL, 0),
(5, 4, 'images/stays/downtown_1.jpg', NULL, NULL, 1),
(6, 4, 'images/stays/downtown_2.jpg', NULL, NULL, 0),
(7, 4, 'images/stays/downtown_3.jpg', NULL, NULL, 0),
(8, 5, 'images/stays/cabin_1.jpg', NULL, NULL, 1),
(9, 5, 'images/stays/cabin_2.jpg', NULL, NULL, 0),
(10, 5, 'images/stays/cabin_3.jpg', NULL, NULL, 0),
(11, 7, 'uploads/stay_images/stay_7_6832351553d0b_1.jpg', NULL, NULL, 0),
(12, 7, 'uploads/stay_images/stay_7_68323515741eb_2.jpg', NULL, NULL, 0),
(13, 7, 'uploads/stay_images/stay_7_683235157c49d_3.jpg', NULL, NULL, 0),
(14, 7, 'uploads/stay_images/stay_7_683235159dc66_4.jpg', NULL, NULL, 0),
(15, 7, 'uploads/stay_images/stay_7_68323515beefa_5.jpg', NULL, NULL, 0),
(16, 7, 'uploads/stay_images/stay_7_68323515e8bed_6.jpg', NULL, NULL, 0),
(23, 8, 'uploads/stay_images/stay_8_6832460be4953_', NULL, NULL, 0),
(24, 8, 'uploads/stay_images/stay_8_6832460beaf12_', NULL, NULL, 0),
(25, 8, 'uploads/stay_images/stay_8_6832460bf3032_', NULL, NULL, 0),
(26, 8, 'uploads/stay_images/stay_8_6832460cec9d2_', NULL, NULL, 0),
(30, 8, 'uploads/stay_images/stay_8_683247357f255_melting-brain-concept-allan-swart.jpg', NULL, NULL, 0),
(31, 12, 'uploads/stay_images/stay_12_683250dd3a2b2_2c2c0fca593c9a30a41d2d1a74d4976e.jpg', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2025_05_14_002425_create_users_table', 1),
(4, '2025_05_14_002514_create_stays_table', 1),
(5, '2025_05_14_002520_create_images_table', 1),
(6, '2025_05_14_002527_create_reservations_table', 1),
(7, '2025_05_14_002554_create_reviews_table', 1),
(8, '2025_05_14_002559_create_amenities_table', 1),
(9, '2025_05_14_002625_create_facilities_table', 1),
(10, '2025_05_14_002635_create_wishlists_table', 1),
(11, '2025_05_14_002642_create_stay_amenities_table', 1),
(12, '2025_05_14_002725_create_stay_facilities_table', 1),
(13, '2025_05_14_002741_create_wishlist_stays_table', 1),
(14, '2025_05_15_182823_add_count_to_stay_facility_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `stay_id` bigint(20) UNSIGNED NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','cancelled','declined') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `stay_id`, `check_in`, `check_out`, `total_price`, `status`, `created_at`, `updated_at`) VALUES
(4, 3, 5, '2025-05-25', '2025-05-30', 600.00, 'declined', '2025-05-24 16:33:23', '2025-05-24 16:33:23'),
(9, 4, 12, '2025-05-26', '2025-05-27', 12.00, 'confirmed', '2025-05-25 00:31:13', '2025-05-25 00:31:13'),
(10, 11, 3, '2025-05-28', '2025-05-29', 150.00, 'cancelled', '2025-05-25 00:49:06', '2025-05-25 00:49:06'),
(11, 11, 5, '2025-05-26', '2025-05-27', 120.00, 'pending', '2025-05-25 00:56:47', '2025-05-25 00:56:47');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `stay_id` bigint(20) UNSIGNED NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stays`
--

CREATE TABLE `stays` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `map_url` varchar(255) NOT NULL,
  `iframe_embed` text DEFAULT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `max_guests` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `available` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stays`
--

INSERT INTO `stays` (`id`, `user_id`, `title`, `description`, `location`, `map_url`, `iframe_embed`, `price_per_night`, `max_guests`, `created_at`, `updated_at`, `available`) VALUES
(2, 2, 'Beachfront Villa', 'Enjoy the ocean breeze from this stunning beachfront villa.', 'Agadir', 'https://www.google.com/maps/place/Agadir/@30.4277557,-9.5981073,12z/data=!3m1!4b1!4m6!3m5!1s0xd1a2c8f8e8e8e8e:0x8e8e8e8e8e8e8e8!8m2!3d30.4277557!4d-9.5981073!16s%2Fg%2F11b6g6g6g6?entry=ttu', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d215.09433885851269!2d-9.5158490560296!3d30.39328545578988!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sma!4v1747425041363!5m2!1sen!2sma\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 150.00, 6, '2025-05-16 19:57:53', '2025-05-16 19:57:53', 1),
(3, 5, 'Seaside Retreat', 'A beautiful house with ocean views and direct beach access.', '101 Ocean Drive, Nice, France', 'https://maps.app.goo.gl/m94SxPKbJcs2rQ1E7', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d207.78631140735277!2d-7.600353!3d33.5642616!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xda7cd40996974d1%3A0x17c0ad828a470afc!2sMarimel!5e0!3m2!1sen!2sma!4v1747784495042!5m2!1sen!2sma\" width=\"940\" height=\"400\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 150.00, 6, '2025-05-19 19:14:48', '2025-05-19 19:14:48', 1),
(4, 10, 'Downtown Modern Apartment', 'Stylish and cozy apartment in the heart of the city.', '202 Central Ave, Berlin, Germany', 'https://maps.app.goo.gl/y2kuxHefk6L3QpiN7', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d215.09433885851269!2d-9.5158490560296!3d30.39328545578988!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sma!4v1747425041363!5m2!1sen!2sma\" width=\"100%\" height=\"400\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 100.00, 3, '2025-05-19 19:14:48', '2025-05-19 19:14:48', 1),
(5, 5, 'Mountain Cabin Escape', 'Rustic cabin surrounded by nature, perfect for hiking lovers.', '303 Forest Rd, Aspen, USA', 'https://maps.app.goo.gl/Z5W9ZQ4TSUT69DUj8', NULL, 120.00, 4, '2025-05-19 19:14:48', '2025-05-19 19:14:48', 1),
(7, 10, 'Entire rental unit in Minato City, Tokyo, Japan', 'This spacious room is located in a relatively quiet place in Shirokane, Minato-ku, Tokyo, and is recommended for couples or solo travelers who want to relax and unwind.\r\n\r\n7-minute walk from Shirokane★ Shirokane Takanawa Station, a longing Minato-ku.There is Shirokane Shopping Street within a 1-minute walk, with restaurants and cafes, a grocery store that is indispensable for daily life, a fish shop, a bakery, a 100-unit convenience store, and a convenience store.', 'Minato City, Tokyo, Japan', 'https://maps.app.goo.gl/t3rJwwPGT9tqMoze8', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d202.64030318986678!2d139.72992696640657!3d35.6463386163746!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60188b004dfd288b%3A0x6e0d441b6b1c679!2sShirokane%20Apartment!5e0!3m2!1sen!2sma!4v1748119826220!5m2!1sen!2sma\" width=\"100%\" height=\"400\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 132.00, 3, '2025-05-24 21:07:31', NULL, 0),
(8, 10, '123azertyuiolkh', '°+£%µAZERTYUIOPMLKJHGFDSQWXCVBN?./§ù¨PO9876543ZDFGHJO87654345678', '456zertyujk', 'https://2maps.app.goo.gl/t3rJwwPGT9tqMoze8', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3036.9587743463153!2d116.56779997539101!3d40.43191175471614!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x35f121d7687f2ccf%3A0xd040259b950522df!2sGreat%20Wall%20of%20China!5e0!3m2!1sen!2sma!4v1748121216568!5m2!1sen!2sma\" width=\"100%\" height=\"400\" style=\"border:1;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 1.00, 11, '2025-05-24 21:15:03', NULL, 0),
(12, 10, '&é\"\'(-è_çà', 'AZER123AZER456789SDFGHJKLM%µ£¨+°09', '&é\"\'(-è_çà', 'https://www.google.com/maps/place/Great+Wall+of+China/@40.4319118,116.5678,17z/data=!3m1!4b1!4m6!3m5!1s0x35f121d7687f2ccf:0xd040259b950522df!8m2!3d40.4319077!4d116.5703749!16zL20vMGQyZHEw?hl=en&entry=ttu&g_ep=EgoyMDI1MDUyMS4wIKXMDSoASAFQAw%3', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3036.9587743463153!2d116.56779997539101!3d40.43191175471614!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x35f121d7687f2ccf%3A0xd040259b950522df!2sGreat%20Wall%20of%20China!5e0!3m2!1sen!2sma!4v1748121216568!5m2!1sen!2sma\" width=\"100%\" height=\"400\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 12.00, 3, '2025-05-24 23:06:03', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `stay_amenity`
--

CREATE TABLE `stay_amenity` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stay_id` bigint(20) UNSIGNED NOT NULL,
  `amenity_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stay_amenity`
--

INSERT INTO `stay_amenity` (`id`, `stay_id`, `amenity_id`, `created_at`, `updated_at`) VALUES
(3, 3, 19, NULL, NULL),
(4, 3, 21, NULL, NULL),
(5, 3, 23, NULL, NULL),
(9, 5, 20, NULL, NULL),
(10, 5, 22, NULL, NULL),
(11, 5, 23, NULL, NULL),
(107, 8, 33, NULL, NULL),
(108, 8, 22, NULL, NULL),
(109, 8, 25, NULL, NULL),
(110, 8, 30, NULL, NULL),
(111, 8, 31, NULL, NULL),
(112, 8, 28, NULL, NULL),
(113, 8, 21, NULL, NULL),
(114, 8, 23, NULL, NULL),
(129, 7, 33, NULL, NULL),
(130, 7, 22, NULL, NULL),
(131, 7, 26, NULL, NULL),
(132, 7, 29, NULL, NULL),
(133, 7, 24, NULL, NULL),
(134, 7, 20, NULL, NULL),
(135, 7, 19, NULL, NULL),
(136, 7, 27, NULL, NULL),
(137, 7, 30, NULL, NULL),
(138, 7, 23, NULL, NULL),
(153, 4, 22, NULL, NULL),
(154, 4, 20, NULL, NULL),
(155, 4, 19, NULL, NULL),
(156, 12, 22, NULL, NULL),
(157, 12, 32, NULL, NULL),
(158, 12, 26, NULL, NULL),
(159, 12, 29, NULL, NULL),
(160, 12, 24, NULL, NULL),
(161, 12, 20, NULL, NULL),
(162, 12, 19, NULL, NULL),
(163, 12, 27, NULL, NULL),
(164, 12, 25, NULL, NULL),
(165, 12, 30, NULL, NULL),
(166, 12, 31, NULL, NULL),
(167, 12, 28, NULL, NULL),
(168, 12, 21, NULL, NULL),
(169, 12, 23, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stay_facility`
--

CREATE TABLE `stay_facility` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stay_id` bigint(20) UNSIGNED NOT NULL,
  `facility_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `count` int(10) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stay_facility`
--

INSERT INTO `stay_facility` (`id`, `stay_id`, `facility_id`, `created_at`, `updated_at`, `count`) VALUES
(39, 3, 4, NULL, NULL, 1),
(40, 3, 6, NULL, NULL, 1),
(41, 3, 8, NULL, NULL, 1),
(45, 5, 5, NULL, NULL, 1),
(46, 5, 7, NULL, NULL, 1),
(47, 5, 8, NULL, NULL, 1),
(152, 8, 14, NULL, NULL, 2),
(153, 8, 8, NULL, NULL, 2),
(154, 8, 6, NULL, NULL, 3),
(155, 8, 4, NULL, NULL, 19),
(156, 8, 9, NULL, NULL, 4),
(157, 8, 12, NULL, NULL, 3),
(158, 8, 11, NULL, NULL, 2),
(159, 8, 5, NULL, NULL, 3),
(160, 8, 7, NULL, NULL, 3),
(161, 8, 10, NULL, NULL, 10),
(162, 8, 13, NULL, NULL, 1),
(170, 7, 14, NULL, NULL, 1),
(171, 7, 6, NULL, NULL, 1),
(172, 7, 4, NULL, NULL, 2),
(173, 7, 9, NULL, NULL, 1),
(174, 7, 11, NULL, NULL, 1),
(175, 7, 5, NULL, NULL, 1),
(176, 7, 7, NULL, NULL, 1),
(184, 4, 4, NULL, NULL, 1),
(185, 4, 5, NULL, NULL, 1),
(186, 4, 7, NULL, NULL, 1),
(187, 12, 6, NULL, NULL, 2),
(188, 12, 4, NULL, NULL, 3),
(189, 12, 9, NULL, NULL, 1),
(190, 12, 12, NULL, NULL, 1),
(191, 12, 11, NULL, NULL, 1),
(192, 12, 5, NULL, NULL, 1),
(193, 12, 10, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `about_me` text DEFAULT NULL,
  `role` enum('guest','host','admin') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `telephone`, `password`, `profile_picture`, `about_me`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@staywhere.com', '(212)649-442921', '$2y$12$FziP6RZRwHI1wwn09tdkPeIhnRXQwn/TwNuf8XJunMtqG3eeu2Rui', NULL, NULL, 'admin', '2025-05-16 19:57:52', '2025-05-16 19:57:52'),
(2, 'Host User', 'host@staywhere.com', '', '$2y$12$l9W.7.0zD0okkw2cM3mM1e0HLptnxmlEWm31yDfOio2PQ/tlklvCG', NULL, NULL, 'host', '2025-05-16 19:57:52', '2025-05-16 19:57:52'),
(3, 'Guest User #', 'guest@staywhere.com', '(212)626332862', '$2y$12$9wePyLwG9aRHuCcTUW8ORuZNuGJ3mkjSu9OqMpas6/TjHgsVsQzFq', 'uploads/profile_pics/user_3.jpg', 'I\'m a guest, IF YOU DON\'T MIND.', 'guest', '2025-05-16 19:57:53', '2025-05-16 19:57:53'),
(4, 'Aya Rajany', 'ayatyhany@gmail.com', '(212)626332862', '$2y$10$sLwN4Iadc31OLHdHE3M0/eFD2PGO1DAfmgC1r/nIq9/kOrL.xwgOO', 'uploads/profile_pics/user_4.jpeg', 'Hey, I like travelling. よろしくお願いします ！', 'guest', NULL, NULL),
(5, 'El Hajj', 'elhajj@mdiq.com', '(212)649-442921', '$2y$10$sf2suqgRmfDKtN7OGr1G.uMlUkKAmNd0qATAurDXJjUqJyD4I.Ii2', 'uploads/profile_pics/user_5.jpeg', 'Salam, ana un senior, 3aych fi lmdiq, nkri lnnas lli yjiw fssif, kankheddem radio spania mn sbah tallil 3la 9bel lvibe ou dakchi hhhhh.\r\nEnjoy ur stay in M\'diq.', 'host', NULL, NULL),
(6, 'Admin', 'admin2@staywhere.com', '0000000000', '$2y$10$TexH/9N64Ff/b3K19tL/fulCnLhDMym96sWAPDkbYw9XfzkKQRiWm', NULL, NULL, 'admin', NULL, NULL),
(10, 'HOST #2', 'host2@staywhere.com', '(33)600-112233', '$2y$10$VimbnekGo2vo7P6E9W5pluzns3nKx7yOScWe0QIJZezoUEH8oaY2m', NULL, 'No bio yet, coming soon...', 'host', '2025-05-24 18:07:49', NULL),
(11, 'New 1', 'new@gmail.com', '234 456787654', '$2y$10$BS1wW4WGEsOsrBc/iFkI0eK6PgpYOfeB3Qt3VerKxbp58pFsXmhvq', NULL, NULL, 'guest', '2025-05-25 00:42:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 3, '2025-05-21 21:36:37', '2025-05-21 21:36:37'),
(2, 4, '2025-05-21 21:36:37', '2025-05-21 21:36:37'),
(3, 11, '2025-05-25 00:48:12', '2025-05-25 00:48:12');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist_stay`
--

CREATE TABLE `wishlist_stay` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `wishlist_id` bigint(20) UNSIGNED NOT NULL,
  `stay_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wishlist_stay`
--

INSERT INTO `wishlist_stay` (`id`, `wishlist_id`, `stay_id`, `created_at`, `updated_at`) VALUES
(20, 1, 4, '2025-05-24 16:33:37', '2025-05-24 16:33:37'),
(21, 2, 4, '2025-05-24 23:13:29', '2025-05-24 23:13:29'),
(22, 2, 12, '2025-05-25 00:30:05', '2025-05-25 00:30:05'),
(24, 3, 5, '2025-05-25 00:56:18', '2025-05-25 00:56:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `amenities`
--
ALTER TABLE `amenities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `amenities_name_unique` (`name`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `facilities_name_unique` (`name`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `images_stay_id_foreign` (`stay_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservations_user_id_foreign` (`user_id`),
  ADD KEY `reservations_stay_id_foreign` (`stay_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_user_id_foreign` (`user_id`),
  ADD KEY `reviews_stay_id_foreign` (`stay_id`);

--
-- Indexes for table `stays`
--
ALTER TABLE `stays`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stays_user_id_foreign` (`user_id`);

--
-- Indexes for table `stay_amenity`
--
ALTER TABLE `stay_amenity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stay_amenity_stay_id_foreign` (`stay_id`),
  ADD KEY `stay_amenity_amenity_id_foreign` (`amenity_id`);

--
-- Indexes for table `stay_facility`
--
ALTER TABLE `stay_facility`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stay_facility_stay_id_foreign` (`stay_id`),
  ADD KEY `stay_facility_facility_id_foreign` (`facility_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wishlists_user_id_unique` (`user_id`);

--
-- Indexes for table `wishlist_stay`
--
ALTER TABLE `wishlist_stay`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wishlist_stay_wishlist_id_foreign` (`wishlist_id`),
  ADD KEY `wishlist_stay_stay_id_foreign` (`stay_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `amenities`
--
ALTER TABLE `amenities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stays`
--
ALTER TABLE `stays`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `stay_amenity`
--
ALTER TABLE `stay_amenity`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT for table `stay_facility`
--
ALTER TABLE `stay_facility`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=194;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wishlist_stay`
--
ALTER TABLE `wishlist_stay`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_stay_id_foreign` FOREIGN KEY (`stay_id`) REFERENCES `stays` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_stay_id_foreign` FOREIGN KEY (`stay_id`) REFERENCES `stays` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_stay_id_foreign` FOREIGN KEY (`stay_id`) REFERENCES `stays` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stays`
--
ALTER TABLE `stays`
  ADD CONSTRAINT `stays_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stay_amenity`
--
ALTER TABLE `stay_amenity`
  ADD CONSTRAINT `stay_amenity_amenity_id_foreign` FOREIGN KEY (`amenity_id`) REFERENCES `amenities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stay_amenity_stay_id_foreign` FOREIGN KEY (`stay_id`) REFERENCES `stays` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stay_facility`
--
ALTER TABLE `stay_facility`
  ADD CONSTRAINT `stay_facility_facility_id_foreign` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stay_facility_stay_id_foreign` FOREIGN KEY (`stay_id`) REFERENCES `stays` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist_stay`
--
ALTER TABLE `wishlist_stay`
  ADD CONSTRAINT `wishlist_stay_stay_id_foreign` FOREIGN KEY (`stay_id`) REFERENCES `stays` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_stay_wishlist_id_foreign` FOREIGN KEY (`wishlist_id`) REFERENCES `wishlists` (`id`) ON DELETE CASCADE;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `AutoDeleteReservations` ON SCHEDULE EVERY 1 DAY STARTS '2025-05-26 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO CALL DeleteOldReservations()$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
