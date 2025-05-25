-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2025 at 08:34 PM
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
(11, 7, 'uploads/stay_images/stay_7_6832351553d0b_1.jpg', NULL, NULL, 0),
(12, 7, 'uploads/stay_images/stay_7_68323515741eb_2.jpg', NULL, NULL, 0),
(13, 7, 'uploads/stay_images/stay_7_683235157c49d_3.jpg', NULL, NULL, 0),
(14, 7, 'uploads/stay_images/stay_7_683235159dc66_4.jpg', NULL, NULL, 0),
(15, 7, 'uploads/stay_images/stay_7_68323515beefa_5.jpg', NULL, NULL, 0),
(16, 7, 'uploads/stay_images/stay_7_68323515e8bed_6.jpg', NULL, NULL, 0),
(32, 3, 'uploads/stay_images/stay_3_683287907ad88_1.jpg', NULL, NULL, 0),
(33, 3, 'uploads/stay_images/stay_3_683287907d869_1a6e9ac6-794c-4fe3-a99b-d92e2ef2235b.jpg', NULL, NULL, 0),
(34, 3, 'uploads/stay_images/stay_3_6832879082f56_4213e7e5-1487-4c6d-a706-0cf9963e8034.jpg', NULL, NULL, 0),
(35, 3, 'uploads/stay_images/stay_3_6832879085a56_aac4507d-920c-4ba0-aeb8-0cb6a6688e37.jpg', NULL, NULL, 0),
(36, 3, 'uploads/stay_images/stay_3_6832879090472_b3f8b066-d1b0-491a-8a8e-6cfb7a0d18ab.jpg', NULL, NULL, 0),
(37, 3, 'uploads/stay_images/stay_3_6832879093214_b9c1d333-ce4b-4591-a6a9-6765cf3b3231.jpg', NULL, NULL, 0),
(38, 3, 'uploads/stay_images/stay_3_6832879098735_bc1a992b-d7d1-4b68-9791-d2e6d3e73ae5.jpg', NULL, NULL, 0),
(39, 3, 'uploads/stay_images/stay_3_68328790a37d8_c5c6dce1-bd35-4397-9f6d-f9d51a72b5ed.jpg', NULL, NULL, 0),
(40, 5, 'uploads/stay_images/stay_5_683288f19bf9b_4ecf0dcb-3cda-49e1-9c1e-01c177d9d39a.jpg', NULL, NULL, 0),
(41, 5, 'uploads/stay_images/stay_5_683288f19e975_53df719f-e529-471a-8bec-f2b91cbf2843.jpg', NULL, NULL, 0),
(42, 5, 'uploads/stay_images/stay_5_683288f1a69c7_cc1a3fc0-32ee-4117-a473-f943f7d046d0.jpg', NULL, NULL, 0),
(43, 5, 'uploads/stay_images/stay_5_683288f1ac36c_e8ab1ba9-5a99-4fb0-a873-0382f5eb4265.jpg', NULL, NULL, 0),
(44, 12, 'uploads/stay_images/stay_12_683290feda1d9_1.jpg', NULL, NULL, 0),
(45, 12, 'uploads/stay_images/stay_12_683290ff0127b_2.jpg', NULL, NULL, 0),
(46, 12, 'uploads/stay_images/stay_12_683290ff1c448_3.jpg', NULL, NULL, 0),
(47, 12, 'uploads/stay_images/stay_12_683290ff218d2_4.jpg', NULL, NULL, 0),
(48, 2, 'uploads/stay_images/stay_2_68334735dbcf4_1.jpg', NULL, NULL, 0),
(49, 2, 'uploads/stay_images/stay_2_68334735eecc6_14e9d489-9d1c-4ce8-87a0-4885b65d7487.jpg', NULL, NULL, 0),
(50, 2, 'uploads/stay_images/stay_2_6833473602a4b_b60c8829-f7be-40a2-ae9c-0829b8bb373a.jpg', NULL, NULL, 0),
(51, 2, 'uploads/stay_images/stay_2_6833473608449_bb90e24c-b9ba-479f-9b25-2ee50c585d93.jpg', NULL, NULL, 0),
(52, 2, 'uploads/stay_images/stay_2_683347360d87e_f44b9780-5396-4d9d-a52e-2e5b725b5557.jpg', NULL, NULL, 0),
(53, 13, 'uploads/stay_images/stay_13_6833549e0efaf_1.jpg', NULL, NULL, 0),
(54, 13, 'uploads/stay_images/stay_13_6833549e14049_2.jpg', NULL, NULL, 0),
(55, 13, 'uploads/stay_images/stay_13_6833549e29e8a_31aa4558-5dd1-4d21-9677-953231b5a453.jpg', NULL, NULL, 0),
(56, 13, 'uploads/stay_images/stay_13_6833549e2f380_83a6197b-86f0-4b01-9e31-8e4975885b08.jpg', NULL, NULL, 0),
(57, 13, 'uploads/stay_images/stay_13_6833549e45105_ac6388ae-e546-4925-b71a-a78cb73ea982.jpg', NULL, NULL, 0),
(58, 13, 'uploads/stay_images/stay_13_6833549e5d97d_d8a7c080-aacc-469d-912d-410e57e040db.jpg', NULL, NULL, 0),
(59, 14, 'uploads/stay_images/stay_14_68335d57ea7aa_300x150.png', NULL, NULL, 0);

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
(9, 4, 12, '2025-05-26', '2025-05-27', 12.00, 'confirmed', '2025-05-25 00:31:13', '2025-05-25 00:31:13'),
(10, 11, 3, '2025-05-28', '2025-05-29', 150.00, 'cancelled', '2025-05-25 00:49:06', '2025-05-25 00:49:06'),
(11, 11, 5, '2025-05-26', '2025-05-27', 120.00, 'pending', '2025-05-25 00:56:47', '2025-05-25 00:56:47'),
(13, 3, 14, '2025-05-25', '2025-05-26', 12.00, 'confirmed', '2025-05-25 18:14:14', '2025-05-25 18:14:14');

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
(2, 2, 'Private room in hut in Agadir', 'Escape to the ultimate chill beach getaway in our cozy room, designed with simplicity and natural materials and Moroccan tiled floor. It\'s the perfect spot to unwind and relax. Breakfast is included from 8:30 am to 10.30 am.\r\n\r\nLocated right in front of a campground surf camp and nestled next to a beautiful nature reserve, this place offers an incredible ocean view that will blow your mind. And the best part? You\'ll have super easy access to the beach 5 minutes walking distance.', 'Agadir, Morocco', 'https://maps.app.goo.gl/w4XVoQ2HDMdV82hDA', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d214.0913878828553!2d-9.820694449298722!3d30.845731991180255!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xdb25f001a27750b%3A0x949556ecebd238c!2sImsouane%20Supermarket!5e0!3m2!1sen!2sma!4v1748190896057!5m2!1sen!2sma\" width=\"100%\" height=\"400\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 42.00, 2, '2025-05-16 19:57:53', '2025-05-16 19:57:53', 1),
(3, 5, 'Nice apartment in the center of M\'diq', 'A beautiful house with ocean views and direct beach access.', 'Mdiq, Morocco', 'https://maps.app.goo.gl/nD8kewU3dxnr8f9D6', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d202.54686347867198!2d-5.32254975514352!3d35.683161816750136!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd0b5c6e7668b281%3A0x1c0e670bd1e812a9!2s6%20Av.%20Mohamed%20V%2C%20M&#39;diq!5e0!3m2!1sen!2sma!4v1748141881601!5m2!1sen!2sma\" width=\"100%\" height=\"400\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 45.00, 5, '2025-05-19 19:14:48', '2025-05-19 19:14:48', 1),
(5, 5, 'Entire rental unit in M\'diq', 'Ocean view apartment, perfect for small families. It has one room with a queen-size bed and a couch in the living room for two additional people. The room and living room have large windows and a balcony with panoramic views of the sea and the city. Equipped kitchen and modern bathroom. Located in a safe and central building with easy access to restaurants, shops and tourist attractions. Book now and enjoy  an unforgettable stay!', 'Mdiq, Morocco', 'https://maps.app.goo.gl/bPukoeEsg51171gM7', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d202.54980626181614!2d-5.321587262973015!3d35.68200261237229!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd0b5c6c4a830501%3A0x4c982508781b0d0a!2sAv.%20Kadi%20Ayad%2C%20M&#39;diq!5e0!3m2!1sen!2sma!4v1748142267244!5m2!1sen!2sma\" width=\"100%\" height=\"400\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 41.00, 4, '2025-05-19 19:14:48', '2025-05-19 19:14:48', 1),
(7, 10, 'Entire rental unit in Minato City, Tokyo, Japan', 'This spacious room is located in a relatively quiet place in Shirokane, Minato-ku, Tokyo, and is recommended for couples or solo travelers who want to relax and unwind.\r\n\r\n7-minute walk from Shirokane★ Shirokane Takanawa Station, a longing Minato-ku.There is Shirokane Shopping Street within a 1-minute walk, with restaurants and cafes, a grocery store that is indispensable for daily life, a fish shop, a bakery, a 100-unit convenience store, and a convenience store.', 'Minato City, Tokyo, Japan', 'https://maps.app.goo.gl/t3rJwwPGT9tqMoze8', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d202.64030318986678!2d139.72992696640657!3d35.6463386163746!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60188b004dfd288b%3A0x6e0d441b6b1c679!2sShirokane%20Apartment!5e0!3m2!1sen!2sma!4v1748119826220!5m2!1sen!2sma\" width=\"100%\" height=\"400\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 132.00, 3, '2025-05-24 21:07:31', NULL, 0),
(12, 10, 'Entire rental unit in Shibuya, Japan', '10 minutes on foot from JR Shibuya Station on foot.\r\nAccess from Tokyo Metro Line to Tokyo, Tokyu Toyoko Line, and Yokohama is also good for business\r\nIt is very convenient for sightseeing.\r\n\r\nThe nearest convenience store is also a 1-minute walk away.', 'Near Shibuya JR Station, Japan', 'https://maps.app.goo.gl/wvkcYuz6gBNeTBN77', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d202.61155524030627!2d139.69466484706132!3d35.65767126859031!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60188caa735a2865%3A0x7ee011d25d6da5af!2s7-5%20Maruyamach%C5%8D%2C%20Shibuya%2C%20Tokyo%20150-0044%2C%20Japan!5e0!3m2!1sen!2sma!4v1748144316458!5m2!1sen!2sma\" width=\"100%\" height=\"400\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 57.00, 2, '2025-05-24 23:06:03', NULL, 0),
(13, 12, 'Entire rental unit in Málaga', 'This apartment is special for its location and the BEACH just 10 SECONDS WALKING and many restaurants around and cafes Justo alado del apartamento !\r\nThis apartment is located on 1 FLOOR .\r\n\r\nThe apartment consists of a bedroom with a double bed of 135 and a sofa bed , when opened it is 135 as is the bed .\r\n\r\nThe photos of the ocean view do not give Right in the apartment . It is to see its exact location where the apartment is located .', 'Málaga, Spain', 'https://maps.app.goo.gl/tgNDRKYcyZ8FXzbV6', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d199.8848113243056!2d-4.367082677868573!3d36.71879091613667!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd7259004b6cee51%3A0xbabc2b07d31742e4!2sPasarela%20de%20los%20Jabegotes!5e0!3m2!1sen!2sma!4v1748194396253!5m2!1sen!2sma\" width=\"100%\" height=\"400\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 115.00, 4, '2025-05-25 17:34:20', NULL, 1),
(14, 5, 'AZERTYUIOP', 'AZERTYUIOP¨MLKJGFDSQWXCVBN?', 'AZERTYUIOP', 'https://maps.app.goo.gl/y2kuxHefk6L3QpiN7', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d202.64030318986678!2d139.72992696640657!3d35.6463386163746!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60188b004dfd288b%3A0x6e0d441b6b1c679!2sShirokane%20Apartment!5e0!3m2!1sen!2sma!4v1748119826220!5m2!1sen!2sma\" width=\"100%\" height=\"400\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 12.00, 34, '2025-05-25 18:11:34', NULL, 0);

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
(170, 3, 33, NULL, NULL),
(171, 3, 22, NULL, NULL),
(172, 3, 29, NULL, NULL),
(173, 3, 24, NULL, NULL),
(174, 3, 20, NULL, NULL),
(175, 3, 30, NULL, NULL),
(176, 3, 21, NULL, NULL),
(177, 3, 23, NULL, NULL),
(178, 5, 33, NULL, NULL),
(179, 5, 22, NULL, NULL),
(180, 5, 26, NULL, NULL),
(181, 5, 24, NULL, NULL),
(182, 5, 20, NULL, NULL),
(183, 5, 23, NULL, NULL),
(184, 12, 33, NULL, NULL),
(185, 12, 22, NULL, NULL),
(186, 12, 26, NULL, NULL),
(187, 12, 24, NULL, NULL),
(188, 12, 19, NULL, NULL),
(189, 12, 27, NULL, NULL),
(190, 12, 30, NULL, NULL),
(191, 12, 28, NULL, NULL),
(192, 12, 23, NULL, NULL),
(193, 2, 33, NULL, NULL),
(194, 2, 20, NULL, NULL),
(195, 2, 19, NULL, NULL),
(196, 2, 31, NULL, NULL),
(197, 2, 23, NULL, NULL),
(198, 13, 33, NULL, NULL),
(199, 13, 22, NULL, NULL),
(200, 13, 26, NULL, NULL),
(201, 13, 24, NULL, NULL),
(202, 13, 20, NULL, NULL),
(203, 13, 19, NULL, NULL),
(204, 13, 25, NULL, NULL),
(205, 13, 30, NULL, NULL),
(206, 13, 28, NULL, NULL),
(207, 13, 23, NULL, NULL),
(208, 14, 26, NULL, NULL),
(209, 14, 24, NULL, NULL),
(210, 14, 20, NULL, NULL),
(211, 14, 19, NULL, NULL),
(212, 14, 27, NULL, NULL);

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
(170, 7, 14, NULL, NULL, 1),
(171, 7, 6, NULL, NULL, 1),
(172, 7, 4, NULL, NULL, 2),
(173, 7, 9, NULL, NULL, 1),
(174, 7, 11, NULL, NULL, 1),
(175, 7, 5, NULL, NULL, 1),
(176, 7, 7, NULL, NULL, 1),
(194, 3, 8, NULL, NULL, 1),
(195, 3, 6, NULL, NULL, 1),
(196, 3, 4, NULL, NULL, 2),
(197, 3, 9, NULL, NULL, 1),
(198, 3, 5, NULL, NULL, 1),
(199, 3, 7, NULL, NULL, 1),
(200, 5, 8, NULL, NULL, 1),
(201, 5, 6, NULL, NULL, 1),
(202, 5, 4, NULL, NULL, 2),
(203, 5, 9, NULL, NULL, 1),
(204, 5, 5, NULL, NULL, 1),
(205, 5, 7, NULL, NULL, 1),
(206, 12, 6, NULL, NULL, 1),
(207, 12, 4, NULL, NULL, 1),
(208, 12, 9, NULL, NULL, 1),
(209, 12, 5, NULL, NULL, 1),
(210, 12, 13, NULL, NULL, 1),
(211, 2, 14, NULL, NULL, 1),
(212, 2, 8, NULL, NULL, 1),
(213, 2, 6, NULL, NULL, 1),
(214, 2, 4, NULL, NULL, 1),
(215, 13, 6, NULL, NULL, 1),
(216, 13, 4, NULL, NULL, 1),
(217, 13, 9, NULL, NULL, 1),
(218, 13, 5, NULL, NULL, 1),
(219, 14, 6, NULL, NULL, 1),
(220, 14, 4, NULL, NULL, 1);

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
(2, 'Brahim Ait Tayeb', 'aittayeb@gmail.com', '+212 623-232862', '$2y$12$l9W.7.0zD0okkw2cM3mM1e0HLptnxmlEWm31yDfOio2PQ/tlklvCG', 'uploads/profile_pics/user_2.jpg', 'Marhba everyone in Agadir, i host rooms and experiences around the area.', 'host', '2025-05-16 19:57:52', '2025-05-16 19:57:52'),
(3, 'Guest User #', 'guest@staywhere.com', '(212)626332862', '$2y$12$9wePyLwG9aRHuCcTUW8ORuZNuGJ3mkjSu9OqMpas6/TjHgsVsQzFq', 'uploads/profile_pics/user_3.jpg', 'I\'m a guest, IF YOU DON\'T MIND.', 'guest', '2025-05-16 19:57:53', '2025-05-16 19:57:53'),
(4, 'Aya Rajany', 'ayatyhany@gmail.com', '(212)626332862', '$2y$10$sLwN4Iadc31OLHdHE3M0/eFD2PGO1DAfmgC1r/nIq9/kOrL.xwgOO', 'uploads/profile_pics/user_4.jpeg', 'Hey, I like travelling. よろしくお願いします ！', 'guest', '2025-05-22 17:22:29', NULL),
(5, 'El Hajj', 'elhajj@mdiq.com', '(212)649-442921', '$2y$10$sf2suqgRmfDKtN7OGr1G.uMlUkKAmNd0qATAurDXJjUqJyD4I.Ii2', 'uploads/profile_pics/user_5.jpeg', 'Salam, ana un senior, 3aych fi lmdiq, nkri lnnas lli yjiw fssif, kankheddem radio spania mn sbah tallil 3la 9bel lvibe ou dakchi hhhhh.\r\nEnjoy ur stay in M\'diq.', 'host', '2025-05-23 16:49:17', NULL),
(6, 'Admin', 'admin2@staywhere.com', '0000000000', '$2y$10$TexH/9N64Ff/b3K19tL/fulCnLhDMym96sWAPDkbYw9XfzkKQRiWm', NULL, NULL, 'admin', '2025-05-21 17:22:17', NULL),
(10, 'Yui', 'yui.ishost@gmail.com', '(81)-345-6789', '$2y$10$VimbnekGo2vo7P6E9W5pluzns3nKx7yOScWe0QIJZezoUEH8oaY2m', 'uploads/profile_pics/user_10.jpg', 'I host apartments in Tokyo.\r\nEnjoy your stay in Japan :)', 'host', '2025-05-24 18:07:49', NULL),
(11, 'Rachid Foulan', 'rachidfoul@gmail.com', '+212 620-605467', '$2y$10$BS1wW4WGEsOsrBc/iFkI0eK6PgpYOfeB3Qt3VerKxbp58pFsXmhvq', 'uploads/profile_pics/user_11.jpeg', 'Je passe mes vacances d\'été toujours au nord du Maroc.', 'guest', '2025-05-25 00:42:36', NULL),
(12, 'Lola', 'lolademalaga@gmail.com', '+34 612-345678', '$2y$10$kBljZ/mB0yRwQHe8f.odtu5GPyivAhTTNTy2PTn3CMJqKhdaWcM3u', 'uploads/profile_pics/user_12.jpeg', '¡Bienvenido a málaga!', 'host', '2025-05-25 17:27:59', NULL);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stays`
--
ALTER TABLE `stays`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `stay_amenity`
--
ALTER TABLE `stay_amenity`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=213;

--
-- AUTO_INCREMENT for table `stay_facility`
--
ALTER TABLE `stay_facility`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=221;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
