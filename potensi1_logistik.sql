-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 05, 2026 at 10:23 PM
-- Server version: 8.0.46
-- PHP Version: 8.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `potensi1_logistik`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('logistik-web-cache-budi@gmail.con|182.9.34.122', 'i:2;', 1788079654),
('logistik-web-cache-budi@gmail.con|182.9.34.122:timer', 'i:1788079654;', 1788079654);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `customer_id`, `name`, `position`, `phone`, `email`, `is_primary`, `created_at`, `updated_at`) VALUES
(8, 6, 'Hariyadi Kurniawan', 'Supervisor CTO', '082157891900', NULL, 0, '2026-09-01 09:58:35', '2026-09-01 09:58:35'),
(9, 7, 'Sahru Ananda', 'PIC', '085161221285', NULL, 0, '2026-09-01 09:59:03', '2026-09-01 09:59:03'),
(10, 8, 'Juni Priyanto', 'PIC', '088970443821', NULL, 0, '2026-09-01 09:59:34', '2026-09-01 09:59:34');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `company_name`, `phone`, `email`, `address`, `city`, `province`, `postal_code`, `notes`, `created_at`, `updated_at`) VALUES
(6, 'Hariyadi Kurniawan', 'PT Cargill Indonesia', '082157891900', NULL, 'Wisma 86 Kota BNI Lt. 26, Jl. Jendral Sudirman Kav. 1 Karet Tengsing, Tanah Abang, Jakarta Pusat', 'Jakarta', 'DKI Jakarta', '10220', NULL, '2026-09-01 09:51:11', '2026-09-01 09:51:11'),
(7, 'Sahru Ananda', 'PT Anugerah Sarana Hayati', '085161221285', 'ash@saraswanti.com', 'Gudang Fumyco, Dagang Kelambir, Kec. Tanjung Morawa', 'Deli Serdang', 'Sumatera Utara', '20362', NULL, '2026-09-01 09:55:22', '2026-09-01 09:55:22'),
(8, 'Juni Priyanto', 'PT Emado\'s Kebab Indonesia', '088970443821', 'info@emados.co.id', 'Jl. W. R. Supratman Ruko No.70, Cempata Putih, Kecamatan Ciputat Timur', 'Tangerang Selatan', 'Banten', '15412', NULL, '2026-09-01 09:57:18', '2026-09-01 09:57:18');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` bigint UNSIGNED NOT NULL,
  `shipment_id` bigint UNSIGNED DEFAULT NULL,
  `tracking_update_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` int NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'OTHER',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `license_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`id`, `name`, `phone`, `license_number`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Driver AEI', '081361812109', NULL, 'ACTIVE', '2026-09-01 10:03:43', '2026-09-01 10:03:43');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_08_12_051016_create_customers_table', 1),
(5, '2026_08_12_051035_create_contacts_table', 1),
(6, '2026_08_12_051044_create_products_table', 1),
(7, '2026_08_12_051055_create_vehicles_table', 1),
(8, '2026_08_12_051102_create_drivers_table', 1),
(9, '2026_08_12_051351_create_orders_table', 1),
(10, '2026_08_12_051402_create_order_items_table', 1),
(11, '2026_08_12_051411_create_shipments_table', 1),
(12, '2026_08_12_051423_create_shipment_items_table', 1),
(13, '2026_08_12_051431_create_routes_table', 1),
(14, '2026_08_12_051435_create_route_items_table', 1),
(15, '2026_08_12_051446_create_tracking_updates_table', 1),
(16, '2026_08_12_051455_create_documents_table', 1),
(17, '2026_08_12_053127_add_role_and_customer_id_to_users_table', 1),
(18, '2026_09_02_000001_add_invoice_payment_fields_to_shipments_table', 2),
(19, '2026_09_03_000001_add_location_details_to_tracking_updates_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `order_date` date NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `customer_id`, `order_date`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(7, 'ORD-20260901-001', 8, '2026-07-24', 'PROCESSING', NULL, '2026-09-01 10:04:27', '2026-09-01 10:04:27'),
(8, 'ORD-20260901-002', 8, '2026-07-25', 'PROCESSING', NULL, '2026-09-01 10:05:07', '2026-09-01 10:05:07'),
(9, 'ORD-20260901-003', 6, '2026-08-27', 'PROCESSING', NULL, '2026-09-01 10:06:11', '2026-09-01 10:06:11'),
(10, 'ORD-20260901-004', 7, '2026-08-31', 'PROCESSING', NULL, '2026-09-01 10:06:36', '2026-09-01 10:06:36');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `weight` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `weight`, `unit`, `notes`, `created_at`, `updated_at`) VALUES
(10, 7, 8, 1.00, 102.00, 'Kg', NULL, '2026-09-01 10:04:27', '2026-09-01 10:05:25'),
(11, 8, 9, 1.00, 100.00, 'Liter', NULL, '2026-09-01 10:05:07', '2026-09-01 10:05:17'),
(12, 8, 10, 1.00, 20.00, 'Liter', NULL, '2026-09-01 10:05:07', '2026-09-01 10:05:17'),
(13, 9, 6, 44956.00, 19.00, 'Box', NULL, '2026-09-01 10:06:11', '2026-09-01 10:06:11'),
(14, 10, 7, 282.00, 7050.00, 'Kg', NULL, '2026-09-01 10:06:36', '2026-09-01 10:06:36');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pcs',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `sku`, `name`, `description`, `unit`, `created_at`, `updated_at`) VALUES
(6, '008/JKT/LOG-AYU/VIII/2026', 'Kecambah Kelapa Sawit', '44.956 Butir', 'Box', '2026-09-01 10:00:26', '2026-09-01 10:00:26'),
(7, 'ASHA/MAR/VIII/26/127', 'Pupuk', '282 Karung', 'Kg', '2026-09-01 10:00:46', '2026-09-01 10:00:46'),
(8, '2-032', 'Cheese Sauce', NULL, 'Kg', '2026-09-01 10:01:37', '2026-09-01 10:02:26'),
(9, '1-083', 'Nobla All in Cleaner', NULL, 'Liter', '2026-09-01 10:02:12', '2026-09-01 10:02:12'),
(10, '2-138', 'Liquid Grill', NULL, 'Liter', '2026-09-01 10:02:58', '2026-09-01 10:02:58');

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `id` bigint UNSIGNED NOT NULL,
  `shipment_id` bigint UNSIGNED NOT NULL,
  `distance` decimal(10,2) DEFAULT NULL,
  `duration` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `route_points`
--

CREATE TABLE `route_points` (
  `id` bigint UNSIGNED NOT NULL,
  `route_id` bigint UNSIGNED NOT NULL,
  `sequence` int NOT NULL,
  `location_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `estimated_arrival` datetime DEFAULT NULL,
  `actual_arrival` datetime DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipments`
--

CREATE TABLE `shipments` (
  `id` bigint UNSIGNED NOT NULL,
  `shipment_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `vehicle_id` bigint UNSIGNED DEFAULT NULL,
  `driver_id` bigint UNSIGNED DEFAULT NULL,
  `origin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `destination` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `departure_date` datetime DEFAULT NULL,
  `estimated_arrival` datetime DEFAULT NULL,
  `actual_arrival` datetime DEFAULT NULL,
  `total_weight` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `invoice_payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Belum Dibayar',
  `invoice_payment_date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipments`
--

INSERT INTO `shipments` (`id`, `shipment_number`, `order_id`, `customer_id`, `vehicle_id`, `driver_id`, `origin`, `destination`, `departure_date`, `estimated_arrival`, `actual_arrival`, `total_weight`, `status`, `invoice_payment_status`, `invoice_payment_date`, `notes`, `created_at`, `updated_at`) VALUES
(5, 'SHP-20260901-001', 7, 8, 2, 2, 'Jawa Tengah', 'Jakarta', '2026-07-24 08:00:00', '2026-09-12 17:00:00', NULL, 102.00, 'IN_TRANSIT', 'Belum Dibayar', NULL, NULL, '2026-09-01 10:08:30', '2026-09-01 10:08:30'),
(6, 'SHP-20260901-002', 8, 8, 2, 2, 'Jakarta', 'Jawa Timur', '2026-07-25 08:00:00', '2026-09-12 17:00:00', NULL, 120.00, 'IN_TRANSIT', 'Belum Dibayar', NULL, NULL, '2026-09-01 10:09:43', '2026-09-01 10:09:43'),
(7, 'SHP-20260901-003', 9, 6, 2, 2, 'Pekanbaru', 'Kalimantan', '2026-08-27 08:00:00', '2026-09-21 17:00:00', NULL, 19.00, 'IN_TRANSIT', 'Belum Dibayar', NULL, NULL, '2026-09-01 10:10:41', '2026-09-01 10:10:41'),
(8, 'SHP-20260901-004', 10, 7, 2, 2, 'Deli Serdang', 'Palembang', '2026-08-31 08:00:00', '2026-09-20 17:00:00', NULL, 7050.00, 'IN_TRANSIT', 'Belum Dibayar', NULL, NULL, '2026-09-01 10:11:38', '2026-09-01 10:11:38');

-- --------------------------------------------------------

--
-- Table structure for table `shipment_items`
--

CREATE TABLE `shipment_items` (
  `id` bigint UNSIGNED NOT NULL,
  `shipment_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `weight` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipment_items`
--

INSERT INTO `shipment_items` (`id`, `shipment_id`, `product_id`, `quantity`, `weight`, `unit`, `notes`, `created_at`, `updated_at`) VALUES
(6, 5, 8, 1.00, 102.00, 'Kg', NULL, '2026-09-01 10:08:30', '2026-09-01 10:08:30'),
(7, 6, 9, 1.00, 100.00, 'Liter', NULL, '2026-09-01 10:09:43', '2026-09-01 10:09:43'),
(8, 6, 10, 1.00, 20.00, 'Liter', NULL, '2026-09-01 10:09:43', '2026-09-01 10:09:43'),
(9, 7, 6, 44956.00, 19.00, 'Box', NULL, '2026-09-01 10:10:41', '2026-09-01 10:10:41'),
(10, 8, 7, 282.00, 7050.00, 'Kg', NULL, '2026-09-01 10:11:38', '2026-09-01 10:11:38');

-- --------------------------------------------------------

--
-- Table structure for table `tracking_updates`
--

CREATE TABLE `tracking_updates` (
  `id` bigint UNSIGNED NOT NULL,
  `shipment_id` bigint UNSIGNED NOT NULL,
  `route_point_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `tracked_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nama_kolom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kolom_lain` decimal(10,7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'CUSTOMER',
  `customer_id` bigint UNSIGNED DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `customer_id`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'adminlogistik1@gmail.com', 'ADMIN', NULL, NULL, '$2y$12$TIEHHxYo2U5pUCKyqs.b5OSTOEEi2ODDD9KNoHS9.H0AYlJ21212y', 'irvjSHFWQ8XpsZJthP41JUAK5Dxm1JpWChW0Fhf9r7D0nMexj9RXwzUeMMUi', '2026-08-30 22:18:11', '2026-08-30 22:18:11');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` bigint UNSIGNED NOT NULL,
  `plate_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicle_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'AVAILABLE',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `plate_number`, `vehicle_type`, `brand`, `capacity`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(2, 'BK 1234 ###', 'Truck', NULL, 10000.00, 'IN_USE', NULL, '2026-09-01 10:03:18', '2026-09-01 10:11:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contacts_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customers_name_index` (`name`),
  ADD KEY `customers_company_name_index` (`company_name`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documents_tracking_update_id_foreign` (`tracking_update_id`),
  ADD KEY `documents_user_id_foreign` (`user_id`),
  ADD KEY `documents_shipment_id_index` (`shipment_id`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

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
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_customer_id_foreign` (`customer_id`),
  ADD KEY `orders_status_index` (`status`),
  ADD KEY `orders_order_date_index` (`order_date`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `routes_shipment_id_unique` (`shipment_id`);

--
-- Indexes for table `route_points`
--
ALTER TABLE `route_points`
  ADD PRIMARY KEY (`id`),
  ADD KEY `route_points_route_id_sequence_index` (`route_id`,`sequence`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shipments_shipment_number_unique` (`shipment_number`),
  ADD KEY `shipments_order_id_foreign` (`order_id`),
  ADD KEY `shipments_customer_id_foreign` (`customer_id`),
  ADD KEY `shipments_vehicle_id_foreign` (`vehicle_id`),
  ADD KEY `shipments_driver_id_foreign` (`driver_id`),
  ADD KEY `shipments_status_index` (`status`),
  ADD KEY `shipments_shipment_number_index` (`shipment_number`);

--
-- Indexes for table `shipment_items`
--
ALTER TABLE `shipment_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shipment_items_shipment_id_foreign` (`shipment_id`),
  ADD KEY `shipment_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `tracking_updates`
--
ALTER TABLE `tracking_updates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tracking_updates_route_point_id_foreign` (`route_point_id`),
  ADD KEY `tracking_updates_user_id_foreign` (`user_id`),
  ADD KEY `tracking_updates_shipment_id_index` (`shipment_id`),
  ADD KEY `tracking_updates_tracked_at_index` (`tracked_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vehicles_plate_number_unique` (`plate_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `route_points`
--
ALTER TABLE `route_points`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipments`
--
ALTER TABLE `shipments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `shipment_items`
--
ALTER TABLE `shipment_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tracking_updates`
--
ALTER TABLE `tracking_updates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_shipment_id_foreign` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `documents_tracking_update_id_foreign` FOREIGN KEY (`tracking_update_id`) REFERENCES `tracking_updates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `documents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `routes`
--
ALTER TABLE `routes`
  ADD CONSTRAINT `routes_shipment_id_foreign` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `route_points`
--
ALTER TABLE `route_points`
  ADD CONSTRAINT `route_points_route_id_foreign` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shipments`
--
ALTER TABLE `shipments`
  ADD CONSTRAINT `shipments_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `shipments_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `shipments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `shipments_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `shipment_items`
--
ALTER TABLE `shipment_items`
  ADD CONSTRAINT `shipment_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `shipment_items_shipment_id_foreign` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tracking_updates`
--
ALTER TABLE `tracking_updates`
  ADD CONSTRAINT `tracking_updates_route_point_id_foreign` FOREIGN KEY (`route_point_id`) REFERENCES `route_points` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tracking_updates_shipment_id_foreign` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tracking_updates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
