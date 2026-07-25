-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 25, 2026 at 12:32 PM
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
-- Database: `enterprise_saas`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `ip_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `admin_id`, `action`, `subject_type`, `subject_id`, `description`, `properties`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, 1, 'update', NULL, NULL, 'Updated Platform Settings', NULL, '127.0.0.1', '2026-07-14 18:19:12', '2026-07-14 18:19:12'),
(2, NULL, 'tenant_created', NULL, NULL, 'Created tenant: test with owner: test@gmail.com', NULL, NULL, '2026-07-15 18:29:14', '2026-07-15 18:29:14'),
(3, NULL, 'tenant_deleted', NULL, NULL, 'Deleted tenant: test', NULL, NULL, '2026-07-15 18:35:39', '2026-07-15 18:35:39'),
(4, NULL, 'tenant_created', NULL, NULL, 'Created tenant: Test with owner: test@gmail.com', NULL, NULL, '2026-07-15 18:41:26', '2026-07-15 18:41:26'),
(5, NULL, 'tenant_deleted', NULL, NULL, 'Deleted tenant: Test', NULL, NULL, '2026-07-15 19:00:01', '2026-07-15 19:00:01'),
(6, NULL, 'tenant_deleted', NULL, NULL, 'Deleted tenant: Shaukat Khanum', NULL, NULL, '2026-07-15 19:50:09', '2026-07-15 19:50:09'),
(7, NULL, 'tenant_deleted', NULL, NULL, 'Deleted tenant: Hexsolz', NULL, NULL, '2026-07-15 19:50:24', '2026-07-15 19:50:24'),
(8, NULL, 'tenant_created', NULL, NULL, 'Created tenant: Hexsolz', NULL, NULL, '2026-07-15 20:22:48', '2026-07-15 20:22:48'),
(9, NULL, 'tenant_updated', NULL, NULL, 'Updated tenant: Hexsolz', NULL, NULL, '2026-07-15 20:23:29', '2026-07-15 20:23:29'),
(10, NULL, 'tenant_status_toggled', NULL, NULL, 'suspended tenant: Hexsolz', NULL, NULL, '2026-07-16 12:34:37', '2026-07-16 12:34:37'),
(11, NULL, 'tenant_status_toggled', NULL, NULL, 'activated tenant: Hexsolz', NULL, NULL, '2026-07-16 12:34:41', '2026-07-16 12:34:41'),
(12, NULL, 'tenant_status_toggled', NULL, NULL, 'suspended tenant: Hexsolz', NULL, NULL, '2026-07-16 16:11:51', '2026-07-16 16:11:51'),
(13, NULL, 'tenant_status_toggled', NULL, NULL, 'activated tenant: Hexsolz', NULL, NULL, '2026-07-16 16:11:53', '2026-07-16 16:11:53'),
(14, NULL, 'tenant_updated', NULL, NULL, 'Updated tenant: optimos', NULL, NULL, '2026-07-18 19:23:17', '2026-07-18 19:23:17'),
(15, NULL, 'tenant_status_toggled', NULL, NULL, 'suspended tenant: optimos', NULL, NULL, '2026-07-18 19:37:57', '2026-07-18 19:37:57'),
(16, NULL, 'tenant_status_toggled', NULL, NULL, 'activated tenant: optimos', NULL, NULL, '2026-07-18 19:47:17', '2026-07-18 19:47:17'),
(17, NULL, 'tenant_created', NULL, NULL, 'Created tenant: Shaukat Khanum', NULL, NULL, '2026-07-18 20:17:45', '2026-07-18 20:17:45'),
(18, NULL, 'tenant_updated', NULL, NULL, 'Updated tenant: Shaukat Khanum', NULL, NULL, '2026-07-18 20:30:00', '2026-07-18 20:30:00'),
(19, NULL, 'tenant_updated', NULL, NULL, 'Updated tenant: optimos', NULL, NULL, '2026-07-19 17:50:32', '2026-07-19 17:50:32');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `consultation_fee` decimal(8,2) NOT NULL DEFAULT 0.00,
  `phone` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `daily_patient_limit` int(11) NOT NULL DEFAULT 30,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `tenant_id`, `branch_id`, `name`, `specialization`, `consultation_fee`, `phone`, `is_active`, `created_at`, `updated_at`, `daily_patient_limit`, `deleted_at`) VALUES
(4, 11, NULL, 'Dr. Ahmed Khan', 'General Physician', 0.00, NULL, 1, '2026-07-24 05:53:42', '2026-07-24 05:53:42', 30, NULL),
(5, 11, NULL, 'Dr. Sara Ali', 'Dermatologist', 0.00, NULL, 1, '2026-07-24 05:53:42', '2026-07-24 05:53:42', 30, NULL),
(6, 11, NULL, 'Dr. Usman Tariq', 'Cardiologist', 0.00, NULL, 1, '2026-07-24 05:53:42', '2026-07-24 05:53:42', 30, NULL),
(7, 11, NULL, 'Dr. Fatima Noor', 'Pediatrician', 0.00, NULL, 1, '2026-07-24 05:53:42', '2026-07-24 05:53:42', 30, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `domains`
--

CREATE TABLE `domains` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `domain` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `domains`
--

INSERT INTO `domains` (`id`, `tenant_id`, `domain`, `created_at`, `updated_at`) VALUES
(8, 11, 'hexsolz.yoursaas.com', '2026-07-15 20:22:47', '2026-07-15 20:23:29'),
(9, 12, 'hexsolz2.yoursaas.com', '2026-07-16 16:10:15', '2026-07-16 16:10:15'),
(10, 13, 'hns.yoursaas.com', '2026-07-18 14:32:23', '2026-07-18 14:32:23'),
(11, 14, 'imtiaz.yoursaas.com', '2026-07-18 18:44:13', '2026-07-18 18:44:13'),
(12, 15, 'optimos.yoursaas.com', '2026-07-18 18:50:14', '2026-07-19 17:50:32'),
(13, 16, 'test.yoursaas.com', '2026-07-18 20:17:45', '2026-07-18 20:30:00'),
(14, 17, 'aptech.yoursaas.com', '2026-07-20 09:35:19', '2026-07-20 09:35:19');

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
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `token_id` bigint(20) UNSIGNED NOT NULL,
  `doctor_fee` decimal(8,2) NOT NULL DEFAULT 0.00,
  `service_fee` decimal(8,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `status` enum('paid','unpaid','partial') NOT NULL DEFAULT 'unpaid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"906b2834-a539-4588-879d-7fe527576158\",\"displayName\":\"App\\\\Notifications\\\\TenantPasswordResetNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:18;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:49:\\\"App\\\\Notifications\\\\TenantPasswordResetNotification\\\":2:{s:5:\\\"token\\\";s:64:\\\"02a0adcb940b8e12e484a4899d42afcdaea290ec1b6f26cdaf14fb97722079d3\\\";s:2:\\\"id\\\";s:36:\\\"037fc6e1-3c07-4b71-bb8a-762a501f432d\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\",\"batchId\":null},\"createdAt\":1784501471,\"delay\":null}', 0, NULL, 1784501471, 1784501471);

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
-- Table structure for table `login_logs`
--

CREATE TABLE `login_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tenant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text DEFAULT NULL,
  `device_type` varchar(20) DEFAULT NULL,
  `browser` varchar(50) DEFAULT NULL,
  `browser_version` varchar(20) DEFAULT NULL,
  `os` varchar(50) DEFAULT NULL,
  `os_version` varchar(20) DEFAULT NULL,
  `status` enum('success','failed') NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `login_logs`
--

INSERT INTO `login_logs` (`id`, `user_id`, `tenant_id`, `email`, `ip_address`, `user_agent`, `device_type`, `browser`, `browser_version`, `os`, `os_version`, `status`, `reason`, `created_at`) VALUES
(1, NULL, NULL, 'test@test.com', '127.0.0.1', 'CLI/Tinker', 'desktop', 'Unknown', '', 'Unknown', '', 'failed', 'invalid_credentials', '2026-07-16 17:08:56'),
(2, 16, 13, 'hamiznadeem13@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'desktop', 'Chrome', '150.0.0.0', 'Windows', '10.0', 'success', NULL, '2026-07-18 18:18:27'),
(3, 18, 15, 'optimos@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'desktop', 'Chrome', '150.0.0.0', 'Windows', '10.0', 'success', NULL, '2026-07-18 19:38:24'),
(4, 18, 15, 'optimos@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'desktop', 'Chrome', '150.0.0.0', 'Windows', '10.0', 'failed', 'tenant_suspended', '2026-07-18 19:47:01'),
(5, 18, 15, 'optimos@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'desktop', 'Chrome', '150.0.0.0', 'Windows', '10.0', 'success', NULL, '2026-07-18 19:47:22'),
(6, 18, 15, 'optimos@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'desktop', 'Chrome', '150.0.0.0', 'Windows', '10.0', 'success', NULL, '2026-07-18 19:48:48'),
(7, 18, 15, 'optimos@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'desktop', 'Chrome', '150.0.0.0', 'Windows', '10.0', 'failed', 'invalid_credentials', '2026-07-18 19:50:16'),
(8, 18, 15, 'optimos@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'desktop', 'Chrome', '150.0.0.0', 'Windows', '10.0', 'success', NULL, '2026-07-18 19:50:38'),
(9, 18, 15, 'optimos@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'desktop', 'Chrome', '150.0.0.0', 'Windows', '10.0', 'failed', 'invalid_credentials', '2026-07-18 19:54:36'),
(10, 18, 15, 'optimos@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'desktop', 'Chrome', '150.0.0.0', 'Windows', '10.0', 'success', NULL, '2026-07-18 19:54:40'),
(11, 18, 15, 'optimos@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'desktop', 'Chrome', '150.0.0.0', 'Windows', '10.0', 'success', NULL, '2026-07-18 19:57:23'),
(12, 18, 15, 'optimos@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'desktop', 'Chrome', '150.0.0.0', 'Windows', '10.0', 'success', NULL, '2026-07-19 15:28:30'),
(13, 18, 15, 'optimos@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'desktop', 'Chrome', '150.0.0.0', 'Windows', '10.0', 'success', NULL, '2026-07-19 16:22:47'),
(14, 18, 15, 'hamiznadeem54@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'desktop', 'Chrome', '150.0.0.0', 'Windows', '10.0', 'success', NULL, '2026-07-19 18:26:02'),
(15, 18, 15, 'hamiznadeem54@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'desktop', 'Chrome', '150.0.0.0', 'Windows', '10.0', 'success', NULL, '2026-07-19 18:32:56'),
(16, 18, 15, 'hamiznadeem54@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'desktop', 'Chrome', '150.0.0.0', 'Windows', '10.0', 'failed', 'invalid_credentials', '2026-07-20 16:36:37'),
(17, 18, 15, 'hamiznadeem54@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'desktop', 'Chrome', '150.0.0.0', 'Windows', '10.0', 'success', NULL, '2026-07-20 16:38:46'),
(18, 18, 15, 'hamiznadeem54@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'desktop', 'Chrome', '150.0.0.0', 'Windows', '10.0', 'success', NULL, '2026-07-23 04:03:36'),
(19, 18, 15, 'hamiznadeem54@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'desktop', 'Chrome', '150.0.0.0', 'Windows', '10.0', 'success', NULL, '2026-07-24 04:39:23'),
(20, 18, 15, 'hamiznadeem54@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'desktop', 'Chrome', '150.0.0.0', 'Windows', '10.0', 'success', NULL, '2026-07-24 04:55:28'),
(21, 18, 15, 'hamiznadeem54@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'desktop', 'Chrome', '150.0.0.0', 'Windows', '10.0', 'success', NULL, '2026-07-24 05:22:19'),
(22, 18, 15, 'hamiznadeem54@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'desktop', 'Chrome', '150.0.0.0', 'Windows', '10.0', 'success', NULL, '2026-07-24 06:10:52');

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `brand_name` varchar(255) DEFAULT NULL,
  `generic_name` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `sale_price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `purchase_price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `expiry_date` date DEFAULT NULL,
  `batch_number` varchar(255) DEFAULT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `unit_name` varchar(255) NOT NULL DEFAULT 'Unit',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`id`, `tenant_id`, `branch_id`, `name`, `brand_name`, `generic_name`, `category`, `stock_quantity`, `sale_price`, `purchase_price`, `expiry_date`, `batch_number`, `barcode`, `is_active`, `created_at`, `updated_at`, `unit_name`, `deleted_at`) VALUES
(5, 11, NULL, 'Paracetamol 500mg', 'Panadol', 'Paracetamol', 'Analgesic', 500, 50.00, 30.00, '2026-12-31', 'B-001', NULL, 1, '2026-07-24 05:57:33', '2026-07-24 05:57:33', 'Tablet', NULL),
(6, 11, NULL, 'Amoxicillin 250mg', 'Amoxil', 'Amoxicillin', 'Antibiotic', 200, 120.00, 80.00, '2026-10-15', 'B-002', NULL, 1, '2026-07-24 05:57:33', '2026-07-24 05:57:33', 'Capsule', NULL),
(7, 11, NULL, 'Omeprazole 20mg', 'Losec', 'Omeprazole', 'Antacid', 300, 80.00, 50.00, '2027-03-01', 'B-003', NULL, 1, '2026-07-24 05:57:33', '2026-07-24 05:57:33', 'Capsule', NULL),
(8, 11, NULL, 'Cetirizine 10mg', 'Zyrtec', 'Cetirizine', 'Antihistamine', 8, 35.00, 20.00, '2026-09-30', 'B-004', NULL, 1, '2026-07-24 05:57:33', '2026-07-24 05:57:33', 'Tablet', NULL),
(9, 11, NULL, 'Metformin 500mg', 'Glucophage', 'Metformin', 'Antidiabetic', 5, 90.00, 60.00, '2026-11-20', 'B-005', NULL, 1, '2026-07-24 05:57:33', '2026-07-24 05:57:33', 'Tablet', NULL),
(10, 11, NULL, 'Ibuprofen 400mg', 'Brufen', 'Ibuprofen', 'NSAID', 400, 65.00, 40.00, '2027-01-15', 'B-006', NULL, 1, '2026-07-24 05:57:33', '2026-07-24 05:57:33', 'Tablet', NULL),
(11, 11, NULL, 'Azithromycin 500mg', 'Zithromax', 'Azithromycin', 'Antibiotic', 150, 180.00, 120.00, '2026-08-10', 'B-007', NULL, 1, '2026-07-24 05:57:33', '2026-07-24 05:57:33', 'Tablet', NULL),
(12, 11, NULL, 'Losartan 50mg', 'Cozaar', 'Losartan', 'Antihypertensive', 250, 110.00, 70.00, '2027-05-01', 'B-008', NULL, 1, '2026-07-24 05:57:33', '2026-07-24 05:57:33', 'Tablet', NULL);

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_07_07_213156_create_tenants_table', 2),
(5, '2026_07_07_214011_create_domains_table', 3),
(6, '2026_07_07_214754_add_tenant_id_to_users_table', 4),
(7, '2026_07_08_225232_add_trial_ends_at_to_tenants_table', 5),
(8, '2026_07_08_233248_create_patients_table', 6),
(9, '2026_07_09_220827_create_doctors_table', 7),
(10, '2026_07_09_221554_create_services_table', 8),
(11, '2026_07_09_222012_create_tokens_table', 9),
(12, '2026_07_09_224325_add_daily_patient_limit_to_doctors_table', 10),
(13, '2026_07_10_004014_add_doctor_id_to_users_table', 11),
(14, '2026_07_10_015519_create_invoices_table', 12),
(15, '2026_07_10_023237_create_medicines_table', 13),
(16, '2026_07_10_023903_create_prescriptions_table', 14),
(17, '2026_07_10_024517_create_prescription_items_table', 14),
(18, '2026_07_10_205347_add_unit_name_to_medicines_table', 15),
(19, '2026_07_10_210440_create_sales_table', 16),
(20, '2026_07_10_212959_create_sale_items_table', 17),
(21, '2026_07_11_211638_add_business_type_to_tenants_table', 18),
(22, '2026_07_11_212716_add_outlets_to_tenants_table', 19),
(23, '2026_07_11_232957_add_brand_and_barcode_to_medicines_table', 20),
(24, '2026_07_12_230228_create_permission_tables', 21),
(25, '2026_07_13_011113_add_is_active_to_users_table', 21),
(26, '2026_07_14_174051_create_platform_admins_table', 22),
(27, '2026_07_14_183107_add_plan_fields_to_tenants_table', 0),
(28, '2026_07_14_183107_create_plans_table', 23),
(29, '2026_07_14_192334_add_owner_fields_to_tenants_table', 24),
(30, '2026_07_14_202832_add_enabled_modules_to_tenants_table', 25),
(31, '2026_07_14_203700_create_tenant_subscriptions_table', 26),
(32, '2026_07_14_205714_create_tenant_subscriptions2_table', 27),
(33, '2026_07_14_213703_create_platform_invoices_table', 28),
(34, '2026_07_14_230827_create_audit_logs_table', 29),
(35, '2026_07_14_230827_create_platform_settings_table', 29),
(36, '2026_07_15_010229_create_platform_sales_table', 30),
(37, '2026_07_15_205258_create_permission_tables', 31),
(38, '2026_07_15_232051_alter_tenants_database_nullable', 32),
(39, '2026_07_15_235529_add_deleted_at_to_tenants_table', 33),
(40, '2026_07_16_003415_add_properties_to_audit_logs_table', 34),
(41, '2026_07_16_012205_fix_tenants_domain_unique_index', 35),
(42, '2026_07_16_204643_add_trial_fields_to_tenants_table', 36),
(43, '2026_07_16_215331_create_login_logs_table', 37),
(44, '2026_07_16_215354_create_user_branches_table', 37),
(45, '2026_07_16_222131_add_account_lock_fields_to_users_table', 38),
(46, '2026_07_16_225826_add_auth_fields_to_platform_admins_table', 39),
(47, '2026_07_16_225900_create_platform_password_history_table', 39),
(48, '2026_07_16_225948_create_platform_password_resets_table', 39),
(49, '2026_07_19_211418_create_tenant_activity_logs_table', 40),
(50, '2026_07_24_092935_add_two_factor_columns_to_users_table', 41),
(51, '2026_07_24_102829_add_password_changed_at_to_users_table', 42),
(52, '2026_07_24_124703_create_branches_table', 43),
(53, '2026_07_24_124747_add_branch_id_to_user_branches_table', 43),
(54, '2026_07_24_124809_add_branch_id_to_business_tables', 43),
(55, '2026_07_24_124905_add_auth_enhancements_to_users_table', 43),
(56, '2026_07_24_125000_add_last_login_at_to_platform_admins_table', 43),
(57, '2026_07_24_125024_add_missing_indexes', 44),
(58, '2026_07_24_125131_add_soft_deletes_to_key_tables', 44),
(59, '2026_07_24_125150_create_personal_access_tokens_table', 44),
(60, '2026_07_24_125230_create_user_password_history_table', 44),
(61, '2026_07_24_125256_add_audit_columns_to_business_tables', 44);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(2, 'App\\Models\\User', 12),
(2, 'App\\Models\\User', 13),
(2, 'App\\Models\\User', 14),
(2, 'App\\Models\\User', 15),
(2, 'App\\Models\\User', 16),
(2, 'App\\Models\\User', 17),
(2, 'App\\Models\\User', 18),
(2, 'App\\Models\\User', 19),
(2, 'App\\Models\\User', 20),
(8, 'App\\Models\\PlatformAdmin', 1),
(9, 'App\\Models\\PlatformAdmin', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `cnic` varchar(255) DEFAULT NULL,
  `age` varchar(255) NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `address` text DEFAULT NULL,
  `emergency_contact` varchar(255) DEFAULT NULL,
  `blood_group` varchar(255) DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `medical_history` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `tenant_id`, `branch_id`, `name`, `phone`, `cnic`, `age`, `gender`, `address`, `emergency_contact`, `blood_group`, `allergies`, `medical_history`, `created_at`, `updated_at`, `deleted_at`) VALUES
(5, 15, NULL, 'hamiz', '0311-9886963', '655765757688', '20', 'male', NULL, NULL, NULL, NULL, NULL, '2026-07-19 16:24:15', '2026-07-19 16:24:15', NULL),
(6, 11, NULL, 'Muhammad Hamiz', '0300-1111111', NULL, '22', 'male', 'Street 5, Lahore', NULL, 'B+', NULL, NULL, '2026-07-24 05:57:33', '2026-07-24 05:57:33', NULL),
(7, 11, NULL, 'Ayesha Siddiqui', '0312-2222222', NULL, '30', 'female', 'Block C, Karachi', NULL, 'O+', NULL, NULL, '2026-07-24 05:57:33', '2026-07-24 05:57:33', NULL),
(8, 11, NULL, 'Ali Raza', '0333-3333333', NULL, '45', 'male', 'Model Town, Lahore', NULL, 'A+', NULL, NULL, '2026-07-24 05:57:33', '2026-07-24 05:57:33', NULL),
(9, 11, NULL, 'Zainab Khan', '0345-4444444', NULL, '28', 'female', 'Gulberg, Islamabad', NULL, 'AB+', NULL, NULL, '2026-07-24 05:57:33', '2026-07-24 05:57:33', NULL),
(10, 11, NULL, 'Hassan Mehmood', '0301-5555555', NULL, '55', 'male', 'DHA Phase 5, Karachi', NULL, 'O-', NULL, NULL, '2026-07-24 05:57:33', '2026-07-24 05:57:33', NULL),
(11, 11, NULL, 'Sana Fatima', '0321-6666666', NULL, '35', 'female', 'F-10, Islamabad', NULL, 'B-', NULL, NULL, '2026-07-24 05:57:33', '2026-07-24 05:57:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'dashboard view', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(2, 'patient view', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(3, 'patient create', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(4, 'patient edit', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(5, 'patient delete', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(6, 'token view', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(7, 'token create', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(8, 'token manage', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(9, 'doctor view', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(10, 'doctor create', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(11, 'doctor edit', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(12, 'doctor delete', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(13, 'prescription view', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(14, 'prescription create', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(15, 'invoice view', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(16, 'invoice manage', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(17, 'pos view', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(18, 'pos manage', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(19, 'pharmacy view', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(20, 'pharmacy manage', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(21, 'staff view', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(22, 'staff create', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(23, 'staff edit', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(24, 'role view', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(25, 'role manage', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(26, 'dashboard.view', 'platform', '2026-07-19 15:38:14', '2026-07-19 15:38:14'),
(27, 'tenants.view', 'platform', '2026-07-19 15:38:14', '2026-07-19 15:38:14'),
(28, 'tenants.create', 'platform', '2026-07-19 15:38:14', '2026-07-19 15:38:14'),
(29, 'tenants.edit', 'platform', '2026-07-19 15:38:14', '2026-07-19 15:38:14'),
(30, 'tenants.delete', 'platform', '2026-07-19 15:38:14', '2026-07-19 15:38:14'),
(31, 'tenants.renew', 'platform', '2026-07-19 15:38:14', '2026-07-19 15:38:14'),
(32, 'tenants.suspend', 'platform', '2026-07-19 15:38:14', '2026-07-19 15:38:14'),
(33, 'plans.view', 'platform', '2026-07-19 15:38:14', '2026-07-19 15:38:14'),
(34, 'plans.create', 'platform', '2026-07-19 15:38:14', '2026-07-19 15:38:14'),
(35, 'plans.edit', 'platform', '2026-07-19 15:38:14', '2026-07-19 15:38:14'),
(36, 'plans.delete', 'platform', '2026-07-19 15:38:14', '2026-07-19 15:38:14'),
(37, 'invoices.view', 'platform', '2026-07-19 15:38:14', '2026-07-19 15:38:14'),
(38, 'audit-logs.view', 'platform', '2026-07-19 15:38:14', '2026-07-19 15:38:14'),
(39, 'settings.view', 'platform', '2026-07-19 15:38:14', '2026-07-19 15:38:14'),
(40, 'settings.update', 'platform', '2026-07-19 15:38:14', '2026-07-19 15:38:14'),
(41, 'roles.view', 'platform', '2026-07-19 15:38:14', '2026-07-19 15:38:14'),
(42, 'roles.create', 'platform', '2026-07-19 15:38:14', '2026-07-19 15:38:14'),
(43, 'roles.edit', 'platform', '2026-07-19 15:38:14', '2026-07-19 15:38:14'),
(44, 'roles.delete', 'platform', '2026-07-19 15:38:14', '2026-07-19 15:38:14'),
(45, 'sessions.view', 'platform', '2026-07-19 15:38:14', '2026-07-19 15:38:14'),
(46, 'sessions.delete', 'platform', '2026-07-19 15:38:14', '2026-07-19 15:38:14'),
(47, 'patients.view', 'web', '2026-07-20 20:25:56', '2026-07-20 20:25:56'),
(48, 'patients.create', 'web', '2026-07-20 20:25:56', '2026-07-20 20:25:56'),
(49, 'patients.edit', 'web', '2026-07-20 20:25:56', '2026-07-20 20:25:56'),
(50, 'patients.delete', 'web', '2026-07-20 20:25:56', '2026-07-20 20:25:56'),
(51, 'tokens.view', 'web', '2026-07-20 20:25:56', '2026-07-20 20:25:56'),
(52, 'tokens.create', 'web', '2026-07-20 20:25:56', '2026-07-20 20:25:56'),
(53, 'tokens.manage', 'web', '2026-07-20 20:25:56', '2026-07-20 20:25:56'),
(54, 'doctors.view', 'web', '2026-07-20 20:25:56', '2026-07-20 20:25:56'),
(55, 'doctors.create', 'web', '2026-07-20 20:25:56', '2026-07-20 20:25:56'),
(56, 'doctors.edit', 'web', '2026-07-20 20:25:56', '2026-07-20 20:25:56'),
(57, 'doctors.delete', 'web', '2026-07-20 20:25:56', '2026-07-20 20:25:56'),
(58, 'prescriptions.view', 'web', '2026-07-20 20:25:56', '2026-07-20 20:25:56'),
(59, 'prescriptions.create', 'web', '2026-07-20 20:25:56', '2026-07-20 20:25:56'),
(60, 'invoices.view', 'web', '2026-07-20 20:25:56', '2026-07-20 20:25:56'),
(61, 'invoices.create', 'web', '2026-07-20 20:25:56', '2026-07-20 20:25:56'),
(62, 'invoices.manage', 'web', '2026-07-20 20:25:56', '2026-07-20 20:25:56'),
(63, 'pos.view', 'web', '2026-07-20 20:25:56', '2026-07-20 20:25:56'),
(64, 'pos.manage', 'web', '2026-07-20 20:25:56', '2026-07-20 20:25:56'),
(65, 'pharmacy.view', 'web', '2026-07-20 20:25:56', '2026-07-20 20:25:56'),
(66, 'pharmacy.manage', 'web', '2026-07-20 20:25:56', '2026-07-20 20:25:56'),
(67, 'staff.view', 'web', '2026-07-20 20:25:56', '2026-07-20 20:25:56'),
(68, 'staff.create', 'web', '2026-07-20 20:25:56', '2026-07-20 20:25:56'),
(69, 'staff.edit', 'web', '2026-07-20 20:25:56', '2026-07-20 20:25:56'),
(70, 'staff.delete', 'web', '2026-07-20 20:25:56', '2026-07-20 20:25:56'),
(71, 'reports.view', 'web', '2026-07-20 20:25:56', '2026-07-20 20:25:56'),
(72, 'settings.view', 'web', '2026-07-20 20:25:56', '2026-07-20 20:25:56'),
(73, 'settings.update', 'web', '2026-07-20 20:25:56', '2026-07-20 20:25:56');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `billing_cycle` varchar(255) NOT NULL DEFAULT 'monthly',
  `trial_days` int(11) NOT NULL DEFAULT 0,
  `limits` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`limits`)),
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`id`, `name`, `slug`, `description`, `price`, `billing_cycle`, `trial_days`, `limits`, `features`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'M', 'm', NULL, 1000.00, 'monthly', 0, '{\"branches\":\"1\",\"users\":\"3\",\"products\":\"500\"}', NULL, 1, '2026-07-14 14:19:19', '2026-07-14 14:19:19'),
(3, 'Free Trial', 'free-trial', NULL, 0.00, 'one-time', 14, '{\"terminals\":1,\"products\":200,\"users\":3}', '{\"clinic\":false,\"pos\":true,\"pharmacy\":false,\"restaurant\":false,\"retail\":true}', 1, '2026-07-16 16:08:01', '2026-07-16 16:08:01');

-- --------------------------------------------------------

--
-- Table structure for table `platform_admins`
--

CREATE TABLE `platform_admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'platform_admin',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `login_attempts` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `locked_until` timestamp NULL DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `platform_admins`
--

INSERT INTO `platform_admins` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `is_active`, `login_attempts`, `locked_until`, `last_login_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Platform Owner', 'owner@saas.com', NULL, '$2y$12$R.n9Tq4OLl5g5ZPJILa/COdPWEpCwM49drq0aRcQ5318ebnp89HWO', 'platform_owner', 1, 0, NULL, NULL, NULL, '2026-07-14 12:53:27', '2026-07-16 19:56:22'),
(2, 'Support Staff', 'support@saas.com', NULL, '$2y$12$BeFnqsfTo2N.kglOZxRPM.DILg3YA2vtN3I3t.i.wkSQDkxpNW1oe', 'support', 1, 0, NULL, NULL, NULL, '2026-07-19 16:06:56', '2026-07-19 16:06:56');

-- --------------------------------------------------------

--
-- Table structure for table `platform_invoices`
--

CREATE TABLE `platform_invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `subscription_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'paid',
  `due_date` datetime DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `platform_password_history`
--

CREATE TABLE `platform_password_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `platform_admin_id` bigint(20) UNSIGNED NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `platform_password_resets`
--

CREATE TABLE `platform_password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `platform_sales`
--

CREATE TABLE `platform_sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `platform_invoice_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` varchar(255) NOT NULL DEFAULT 'completed',
  `payment_method` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `platform_settings`
--

CREATE TABLE `platform_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `platform_settings`
--

INSERT INTO `platform_settings` (`id`, `key`, `value`, `group`, `created_at`, `updated_at`) VALUES
(1, 'app_name', 'Hexsolz', 'general', '2026-07-14 18:19:12', '2026-07-14 18:19:12'),
(2, 'currency', 'PKR', 'general', '2026-07-14 18:19:12', '2026-07-14 18:19:12'),
(3, 'default_language', 'ur', 'general', '2026-07-14 19:15:32', '2026-07-14 19:15:32'),
(4, 'timezone', 'UTC', 'general', '2026-07-14 19:15:32', '2026-07-14 19:15:32'),
(5, 'smtp_host', NULL, 'smtp', '2026-07-14 19:15:32', '2026-07-14 19:15:32'),
(6, 'smtp_port', '587', 'smtp', '2026-07-14 19:15:32', '2026-07-14 19:15:32'),
(7, 'smtp_username', NULL, 'smtp', '2026-07-14 19:15:32', '2026-07-14 19:15:32'),
(8, 'smtp_encryption', NULL, 'smtp', '2026-07-14 19:15:32', '2026-07-14 19:15:32'),
(9, 'smtp_from_address', 'noreply@yoursaas.com', 'smtp', '2026-07-14 19:15:32', '2026-07-14 19:15:32'),
(10, 'sms_provider', NULL, 'sms', '2026-07-14 19:15:32', '2026-07-20 19:45:57'),
(11, 'sms_api_key', NULL, 'sms', '2026-07-14 19:15:32', '2026-07-14 19:15:32'),
(12, 'sms_sender', NULL, 'sms', '2026-07-14 19:15:32', '2026-07-14 19:15:32'),
(13, 'maintenance_message', 'We are doing some maintenance. Please try again in 10 minutes.', 'system', '2026-07-14 19:15:32', '2026-07-14 19:15:32');

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `token_id` bigint(20) UNSIGNED NOT NULL,
  `diagnosis` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prescription_items`
--

CREATE TABLE `prescription_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `prescription_id` bigint(20) UNSIGNED NOT NULL,
  `medicine_id` bigint(20) UNSIGNED NOT NULL,
  `dosage` varchar(255) NOT NULL DEFAULT '1-1-1',
  `days` int(11) NOT NULL DEFAULT 3,
  `instructions` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super-admin', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(2, 'owner', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(3, 'manager', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(4, 'receptionist', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(5, 'doctor', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(6, 'cashier', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(7, 'pharmacist', 'web', '2026-07-15 15:56:44', '2026-07-15 15:56:44'),
(8, 'super-admin', 'platform', '2026-07-19 15:38:14', '2026-07-19 15:38:14'),
(9, 'support', 'platform', '2026-07-19 15:38:14', '2026-07-19 15:38:14'),
(10, 'finance', 'platform', '2026-07-19 15:38:15', '2026-07-19 15:38:15'),
(11, 'admin', 'platform', '2026-07-19 15:59:37', '2026-07-19 15:59:37'),
(12, 'viewer', 'platform', '2026-07-20 20:25:56', '2026-07-20 20:25:56');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 3),
(1, 7),
(2, 1),
(2, 3),
(3, 1),
(3, 3),
(4, 1),
(4, 3),
(5, 1),
(5, 3),
(6, 1),
(6, 3),
(7, 1),
(7, 3),
(8, 1),
(8, 3),
(9, 1),
(9, 3),
(10, 1),
(10, 3),
(11, 1),
(11, 3),
(12, 1),
(12, 3),
(13, 1),
(13, 3),
(13, 7),
(14, 1),
(14, 3),
(15, 1),
(15, 3),
(16, 1),
(16, 3),
(17, 1),
(17, 3),
(17, 7),
(18, 1),
(18, 3),
(18, 7),
(19, 1),
(19, 3),
(19, 7),
(20, 1),
(20, 3),
(20, 7),
(21, 1),
(21, 3),
(22, 1),
(22, 3),
(23, 1),
(23, 3),
(24, 1),
(24, 3),
(25, 1),
(25, 3),
(26, 8),
(26, 9),
(26, 10),
(26, 11),
(26, 12),
(27, 8),
(27, 9),
(27, 10),
(27, 11),
(27, 12),
(28, 8),
(28, 11),
(29, 8),
(29, 11),
(30, 8),
(30, 11),
(31, 8),
(31, 10),
(31, 11),
(32, 8),
(32, 11),
(33, 8),
(33, 10),
(33, 11),
(33, 12),
(34, 8),
(34, 11),
(35, 8),
(35, 11),
(36, 8),
(36, 11),
(37, 8),
(37, 9),
(37, 10),
(37, 12),
(38, 8),
(38, 9),
(38, 12),
(39, 8),
(40, 8),
(41, 8),
(42, 8),
(43, 8),
(44, 8),
(45, 8),
(46, 8),
(47, 2),
(47, 3),
(47, 4),
(47, 5),
(47, 6),
(48, 2),
(48, 3),
(48, 4),
(49, 2),
(49, 3),
(49, 4),
(50, 2),
(50, 3),
(51, 2),
(51, 3),
(51, 4),
(51, 5),
(52, 2),
(52, 3),
(52, 4),
(53, 2),
(53, 3),
(53, 4),
(53, 5),
(54, 2),
(54, 3),
(54, 4),
(55, 2),
(55, 3),
(56, 2),
(56, 3),
(57, 2),
(57, 3),
(58, 2),
(58, 3),
(58, 5),
(59, 2),
(59, 3),
(59, 5),
(60, 2),
(60, 3),
(60, 4),
(60, 6),
(61, 2),
(61, 3),
(61, 4),
(62, 2),
(62, 3),
(62, 4),
(62, 6),
(63, 2),
(63, 3),
(63, 6),
(64, 2),
(64, 3),
(64, 6),
(65, 2),
(65, 3),
(65, 5),
(66, 2),
(66, 3),
(67, 2),
(67, 3),
(68, 2),
(68, 3),
(69, 2),
(69, 3),
(70, 2),
(70, 3),
(71, 2),
(71, 3),
(72, 2),
(73, 2);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `patient_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `sale_number` varchar(255) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(255) NOT NULL DEFAULT 'cash',
  `status` varchar(255) NOT NULL DEFAULT 'completed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `itemable_type` varchar(255) NOT NULL,
  `itemable_id` bigint(20) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `unit_name` varchar(255) NOT NULL DEFAULT 'Unit',
  `quantity` int(11) NOT NULL DEFAULT 1,
  `total_price` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `fee` decimal(8,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('a8NYrrElsq9NLl2XkCu203Mn3cFhoons5rjSWmit', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'ZXlKcGRpSTZJbFZtZDFKU2EzaHFiV2xVU1dKelJERnJNbWMzUldjOVBTSXNJblpoYkhWbElqb2lWMVpEVjI0NVNrOUtkMWhPTm5sd1dHdEtLM1pCYW5BelIyODBaVzFQUjNCS2RVVlNRMUJuWTIxNGJHRnRSMHhIY1dkc1pWSk5SeTlZYmxoWWJHUnpZekpCT1V0NllpdHVURkpCTnpaaFkyTklWVUZGWW5STlFWUjZkWFpUT0hwRldWRlNOR1ptTTIxTVN6UlVjVGxEYTJOemFVTXJSR2RTV1cxblNtMTRWMjlrYVhaM1JtNUVSazlPWXpVMUwxVkNSR2REY2s1SlYwcFpWWEpxWlhOWVQzTm5URGxVUlZwalkyaFVRemRVVW10YWJuSm9SWEY1WlcxellXVjFNRkZDWm5kUE9FeFNZVVZsVmxRMWRWQXlORmxTV205bVRVTjZXRk5pUVhaUlJUWlljbEoyUjI5V2NEQXhWRlJsTVZCUWFtUnhPRkJqT1c1RVR6SkJSMVE1ZVdORGVUa3ZTV3BXVTJ4dlEyNVFVRUZHTXl0R00zVjZhbEoyYmxVck1HWkVRVlZ0UTJsdmJWRkVOMk41TXpkWkt6VmFWVmt5Y1ZOSmExTkxjR2xFTkdvcmNVZFRkVGxtZDJaUWJVSklMMFpRVGxWQ1pHOVJQVDBpTENKdFlXTWlPaUptTkRreFpUSXlOamswWkdWaFkyRXdZV1F4TVRkbE9EWmpNbUl4WldSaVlqQTBNMkV4WXpsalkyUTJaVFU1WldNd1pERTFNMlJtTm1Wak9HTXdPREl5SWl3aWRHRm5Jam9pSW4wPQ==', 1784885358),
('itHo7fHc2FsX0tHPubHUkzagJBaKMxEQS700GoKB', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'ZXlKcGRpSTZJbkpVU1ZCQ1ZEQjRlVTVUYzBWTGQzZzFSako1VEdjOVBTSXNJblpoYkhWbElqb2lVWGhFYUZwdVlreHZWekZ2U1VrelNTOUdlSFZ3TTFoSlZWZHpTblJSYUZSVmNXdDRaMFJaYTNwbmVYVTBkV1JrVjJ3M1ZtVnNjMlEzV0ZZeU4zbGFaamh3WjA5SGQwOXpLM0Z6TDBkUFF6RmhVVGRSY0V0M1pYbE5jMlJRYldkWE0wOXFlVUZGWm1kQ1YzWllOR3htVXpoMFFqSkNNRVY2UWpOMGJWaElhVkpEU0V0S1RsaGhhVlpUUVdJM1F6Z3lVa2RZT1VNMk56RmxZV3BSVXpkMWMyWnJRbTF3VUVsaE5tOHlNSGQ1UW13NE1WQmFlWFZVVFZCUmRISkhLM1Z4ZUdsVmMyOWFkMGt5TUhsek1YTjJjM3BZUjI1cVpWaGpWMUYzVkVob2JFbElaV2MyVjBneWNWVkRSVVl6V21kb2RsTnhhMGsxV0RCV1VYQmxaVFJvTkhnNVZHbEpNMkpwWnk5UmFYbE1NakJwYldSQ2RtWmpjbU5GVFUwMllqaHBSMGhzYlVaSk1HUjZWWGRHTkhwVmNuUnVTa3BsUlhBdmJDOW9NamxNTW1NaUxDSnRZV01pT2lKaU4ySmhNalkwTmpsa09XVXhOV0pqTnpKaU1XUXlOamcxT0dVeFlqa3lPVGt5T1dNM1l6TmlPREk0TkRoaU9ERTRPRGN6T0RObE5qTTJOVEJqTkRNeklpd2lkR0ZuSWpvaUluMD0=', 1784975486),
('QBBqcDXvH2nZJXeCwGLMKt4MpkpvO7F1zOBMvaBx', 18, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'ZXlKcGRpSTZJbk52WjBKS1QyRlNWR1pJVlRoQ1dIbzFPR2xVWjNjOVBTSXNJblpoYkhWbElqb2ljV3hhZUd0NVFuSnFjR0p5Ylc0dmNuQndhV1ZZYkdSUVVYQjRRelI0TnpjNFlUVmtVbEpqYWtSUVpFcDFVRWxWV1VOeldISmxjV3BNT1VONFIwNVVUR3RHY21vNFdEVk9hbE0xZGxKcFVVUXdaalpSVEhoVGRITlpaSEZxVUZoSFV6Um1UblZOYkhaQ1RWZHRkVzUwYXpKcmIwVTJUa2RUV1hsaVJGSnRaMU54T0cxR2NITkJZVWsxVWxaV2FWVjVZaTlZZW1KaVlURmFVRVp4YVZWck1UQlpTWFI1TUVwMmFFRnZURUZZZHpKSE0yZElVVWxXZFRSeldXVnFVSFZVTVc5SGFVOURXVTVaYkVOWFJHeDZZMWMzZDNnelkzRnZRVTQwYkdoSUszYzFTbE5IV1hKeE0wRnVRalYyV2xVeVlYZDVjMFJ4VFd0R04wdHROemxDWnk5T1pHUm5SelJOYzB0RVMwUldZbkpRZVM4ckwxcGxhblZsT0dSa1RuTmFRMUJYZFZwdGQyaHJZWGxhWW1aMVNWQkVXa3BoYmxSV1UxUjZObVJJYmxWQ1ZrOVlRMWQyWjJGS2IxcDFTRGhKU1RCbVlWQkZkM2RYV0dwU1VDOXpTSEZDUVVZdmRsWnZjR2RzTldoTlprWndiSFpzUlZaa1NEWlFWSEpyWnpaMlprZ3JlV3RaVldzeGF6TnBTMjE1VEVka1ZsUm9ZMjF2YmpaWldERnRiME1yVldZeFVrOXlVMnRyYkNzM2QwOXJNRTVEWW1OTFRVWk9TVWsyYW04NFNDSXNJbTFoWXlJNkltTTBOV00zTUdKbU1tTXlZakF3Tm1KaU5tUTJOV1F3WVdReU9UbGtOVEJoTjJZMk56SmpabVE0TUdWaFlqWm1aamRpWVROalpURTFNemN4TVRSbE9ERWlMQ0owWVdjaU9pSWlmUT09', 1784899632),
('r71FpYe7SmsgoCA0uP82AhLObghVNkjLbtor90iE', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.130.0 Chrome/148.0.7778.280 Electron/42.6.0 Safari/537.36', 'ZXlKcGRpSTZJbmh1VkVKek9XYzBWMEpoVWxCcGJuSkNhWFozYWxFOVBTSXNJblpoYkhWbElqb2labFJhTUZCMmNXaG5ZVVlyVXpoc1lqTXhTVkpGUlVaeUt6bFdiV2hJYjIxWWNYQlVNbUpDT0ZSQ1dWaHFaMWMxWkhwV1ZURmpMekZqU1RsSU0zQkdkQ3RGYzJ0bGRXVmxiREZHY1VWREt6Tm9VMlozU0RSblltVk1XVU5tV0daWGNEaExiM0ZtZEVoYVQwaEVNVVV5Ums4MVdtNVNMMUJSZVcxaEsyY3lOSFkwZGtaT0szSnRORzlCV0RGS01XaFBkMUprWXk4d1NWVkpkMmxUYUhGcE5WWkhjVWxNTmt0RmEwbG5XbFJuYzJ0SVVWbFhSeTluZVZKWEt5OVVUR2RGYkVoMlJUZERNblJvVEVOdk9FUnpUVk50VEdnMWR6QnJkbUpFYm5sTGJtNW5VazlDVkRaaFdrZHJjVkJ6Y0U1bmR6Rk9Wa3hLUVRCdllsSjZRelZTYUhwS1NURk5kWEp1TTI0eGVWQmFjM05qVHpGaVQxSldWV3MwTTBOYWJUVjRTVWR3UzFKQ09UQnZLMmM5SWl3aWJXRmpJam9pTldVNFlqVmpNVEJrWW1ZNU9XRmhPV0kwT0dRNE5EQmhNVE13T1dVM01UVmpNVE5qWWpGaE9HWXdNalV4TVRWaE1EUmpaV1ZrT0RabU0yRTFNMlkyWXlJc0luUmhaeUk2SWlKOQ==', 1784975433);

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `domain` varchar(255) NOT NULL,
  `database` varchar(255) DEFAULT NULL,
  `owner_name` varchar(255) DEFAULT NULL,
  `owner_email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `web_access_url` varchar(255) DEFAULT NULL,
  `plan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'trial',
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `business_type` varchar(255) NOT NULL DEFAULT 'clinic',
  `outlets` varchar(255) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `will_expire_at` datetime DEFAULT NULL,
  `enabled_modules` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`enabled_modules`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`id`, `name`, `domain`, `database`, `owner_name`, `owner_email`, `phone`, `city`, `location`, `web_access_url`, `plan_id`, `status`, `trial_ends_at`, `business_type`, `outlets`, `is_active`, `will_expire_at`, `enabled_modules`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4, 'Hexsolz', 'hexsolz', 'tenant_hexsolz', 'Hamiz', 'Info@hexsolz.com', NULL, NULL, NULL, NULL, 2, 'active', NULL, 'clinic', '1', 1, '2026-09-13 01:42:48', NULL, '2026-07-14 20:42:48', '2026-07-15 19:50:24', '2026-07-15 19:50:24'),
(5, 'Shaukat Khanum', 'intellicoreagency', 'tenant_shaukat_khanum', 'Hamiz', 'hamiz@gmail.com', NULL, NULL, NULL, NULL, 2, 'active', NULL, 'clinic', '1', 1, '2026-08-14 02:14:36', '{\"pos\":true,\"clinic\":true,\"hr\":true}', '2026-07-14 21:14:36', '2026-07-15 19:50:09', '2026-07-15 19:50:09'),
(9, 'Test', 'test', NULL, 'TEST', 'test@gmail.com', NULL, NULL, NULL, NULL, 2, 'active', NULL, 'clinic', '1', 1, '2026-08-14 23:41:25', '{\"clinic\":true,\"pos\":true,\"pharmacy\":true,\"hr\":true,\"crm\":true}', '2026-07-15 18:41:25', '2026-07-15 19:00:01', '2026-07-15 19:00:01'),
(11, 'Hexsolz', 'hexsolz', NULL, 'Ahmed', 'Info@hexsolz.com', NULL, NULL, NULL, NULL, 2, 'active', NULL, 'clinic', '1', 1, '2026-08-15 01:22:47', '{\"clinic\":true,\"pos\":true,\"pharmacy\":true}', '2026-07-15 20:22:47', '2026-07-16 12:34:41', NULL),
(12, 'Hexsolz', 'hexsolz2', NULL, 'Hamiz', 'hamiznadeem137@gmail.com', NULL, NULL, NULL, NULL, 3, 'active', '2026-07-30 16:10:15', 'mart', '1', 1, '2026-07-30 21:10:15', '{\"clinic\":false,\"pos\":true,\"pharmacy\":false,\"restaurant\":false,\"retail\":true}', '2026-07-16 16:10:15', '2026-07-16 16:11:53', NULL),
(13, 'hn', 'hns', NULL, 'Ahmed', 'hamiznadeem13@gmail.com', NULL, NULL, NULL, NULL, 3, 'trial', '2026-08-01 14:32:23', 'mart', '1', 1, '2026-08-01 19:32:23', '{\"clinic\":false,\"pos\":true,\"pharmacy\":false,\"restaurant\":false,\"retail\":true}', '2026-07-18 14:32:23', '2026-07-18 14:32:23', NULL),
(14, 'imtiaz', 'imtiaz', NULL, 'talha', 'imtiaz@gmail.com', NULL, NULL, NULL, NULL, 3, 'trial', '2026-08-01 18:44:13', 'retail', '1', 1, '2026-08-01 23:44:13', '{\"clinic\":false,\"pos\":true,\"pharmacy\":false,\"restaurant\":false,\"retail\":true}', '2026-07-18 18:44:13', '2026-07-18 18:44:13', NULL),
(15, 'optimos', 'optimos', NULL, 'optimos', 'hamiznadeem54@gmail.com', '03132110255', 'Ottawa', 'clifton block 8', NULL, 2, 'active', NULL, 'clinic', '2-5', 1, '2026-08-18 00:23:17', '{\"clinic\":false,\"pos\":true,\"pharmacy\":false,\"restaurant\":false,\"retail\":true}', '2026-07-18 18:50:14', '2026-07-19 17:50:32', NULL),
(16, 'Shaukat Khanum', 'test', NULL, 'TEST', 'test@gmail.com', '0311-9886963', 'karachi', 'clifton block 8', NULL, 3, 'trial', '2026-08-01 20:17:45', 'clinic', '1', 1, '2026-08-02 01:17:45', '{\"clinic\":false,\"pos\":true,\"pharmacy\":false,\"restaurant\":false,\"retail\":true}', '2026-07-18 20:17:45', '2026-07-18 20:30:00', NULL),
(17, 'aptech', 'aptech', NULL, 'sherry', 'aptech@gmail.com', '03132110256', 'Karachi', 'clifton block 8', 'aptech.yoursaas.com', 3, 'trial', '2026-08-03 09:35:19', 'restaurant', '2-5', 1, '2026-08-03 14:35:19', '{\"clinic\":false,\"pos\":true,\"pharmacy\":false,\"restaurant\":false,\"retail\":true}', '2026-07-20 09:35:19', '2026-07-20 09:35:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tenant_activity_logs`
--

CREATE TABLE `tenant_activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tenant_activity_logs`
--

INSERT INTO `tenant_activity_logs` (`id`, `tenant_id`, `user_id`, `action`, `description`, `subject_type`, `subject_id`, `ip_address`, `user_agent`, `properties`, `created_at`, `updated_at`) VALUES
(1, 15, 18, 'patient.create', 'Created patient: hamiz', 'App\\Models\\Patient', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-19 16:24:15', '2026-07-19 16:24:15'),
(2, 15, 18, 'logout', 'User logged out', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-19 17:10:05', '2026-07-19 17:10:05'),
(3, 15, 18, 'logout', 'User logged out', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-19 18:32:45', '2026-07-19 18:32:45'),
(4, 15, 18, 'login', 'User logged in', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-19 18:32:56', '2026-07-19 18:32:56'),
(5, 15, 18, 'login', 'User logged in', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-20 16:38:46', '2026-07-20 16:38:46'),
(6, 15, 18, 'login', 'User logged in', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-23 04:03:36', '2026-07-23 04:03:36'),
(7, 15, 18, 'logout', 'User logged out', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-23 05:26:46', '2026-07-23 05:26:46'),
(8, 15, 18, 'logout', 'User logged out', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-23 05:27:17', '2026-07-23 05:27:17'),
(9, 15, 18, 'login', 'User logged in', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-24 04:39:23', '2026-07-24 04:39:23'),
(10, 15, 18, 'logout', 'User logged out', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-24 04:55:19', '2026-07-24 04:55:19'),
(11, 15, 18, 'login', 'User logged in', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-24 04:55:28', '2026-07-24 04:55:28'),
(12, 15, 18, 'logout', 'User logged out', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-24 05:22:11', '2026-07-24 05:22:11'),
(13, 15, 18, 'login', 'User logged in', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-24 05:22:19', '2026-07-24 05:22:19'),
(14, 15, 18, 'logout', 'User logged out', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-24 06:10:04', '2026-07-24 06:10:04'),
(15, 15, 18, 'login', 'User logged in', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', NULL, '2026-07-24 06:11:05', '2026-07-24 06:11:05');

-- --------------------------------------------------------

--
-- Table structure for table `tenant_subscriptions`
--

CREATE TABLE `tenant_subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `plan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'trial',
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `starts_at` datetime DEFAULT NULL,
  `ends_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tenant_subscriptions`
--

INSERT INTO `tenant_subscriptions` (`id`, `tenant_id`, `plan_id`, `type`, `amount`, `notes`, `starts_at`, `ends_at`, `created_at`, `updated_at`) VALUES
(9, 11, 2, 'trial', 0.00, NULL, '2026-07-16 01:22:48', '2026-08-15 01:22:47', '2026-07-15 20:22:48', '2026-07-15 20:22:48'),
(10, 12, 3, 'trial', 0.00, NULL, '2026-07-16 21:10:16', '2026-07-30 21:10:15', '2026-07-16 16:10:16', '2026-07-16 16:10:16'),
(11, 13, 3, 'trial', 0.00, NULL, '2026-07-18 19:32:23', '2026-08-01 19:32:23', '2026-07-18 14:32:23', '2026-07-18 14:32:23'),
(12, 14, 3, 'trial', 0.00, NULL, '2026-07-18 23:44:13', '2026-08-01 23:44:13', '2026-07-18 18:44:13', '2026-07-18 18:44:13'),
(13, 15, 3, 'trial', 0.00, NULL, '2026-07-18 23:50:14', '2026-08-01 23:50:14', '2026-07-18 18:50:14', '2026-07-18 18:50:14'),
(14, 16, 3, 'trial', 0.00, NULL, '2026-07-19 01:17:45', '2026-08-02 01:17:45', '2026-07-18 20:17:45', '2026-07-18 20:17:45'),
(15, 17, 3, 'trial', 0.00, NULL, '2026-07-20 14:35:19', '2026-08-03 14:35:19', '2026-07-20 09:35:19', '2026-07-20 09:35:19');

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `token_number` varchar(255) NOT NULL,
  `status` enum('waiting','in-progress','completed','cancelled') NOT NULL DEFAULT 'waiting',
  `is_walk_in` tinyint(1) NOT NULL DEFAULT 1,
  `called_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `password_changed_at` timestamp NULL DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tenant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `two_factor_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `two_factor_method` varchar(20) DEFAULT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_remember_token` varchar(255) DEFAULT NULL,
  `login_attempts` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `locked_until` timestamp NULL DEFAULT NULL,
  `doctor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `password_changed_at`, `last_login_at`, `remember_token`, `created_at`, `updated_at`, `tenant_id`, `role`, `is_active`, `two_factor_enabled`, `two_factor_method`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_remember_token`, `login_attempts`, `locked_until`, `doctor_id`, `deleted_at`) VALUES
(14, 'Ahmed', NULL, 'Info@hexsolz.com', NULL, '$2y$12$jG1.Gk9qrQWBnkKtsRANV.ohrPdkr.F1XSCXe/7kWsFCOZXq4Npfu', NULL, NULL, NULL, '2026-07-15 20:22:48', '2026-07-16 17:33:45', 11, 'owner', 0, 0, NULL, NULL, NULL, NULL, 10, NULL, NULL, NULL),
(15, 'Hamiz', NULL, 'hamiznadeem137@gmail.com', NULL, '$2y$12$1FWkDz4TQH1bb.YTFFds2..VQ3aIvXizOFrRNlDzM18nxBsRgzb1y', NULL, NULL, NULL, '2026-07-16 16:10:16', '2026-07-16 16:10:16', 12, 'owner', 1, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(16, 'Ahmed', NULL, 'hamiznadeem13@gmail.com', NULL, '$2y$10$/ToW.B/g7SkJF6/k4FLX2eXb/Y6SiZ0ghi9DP/hw7E/3CGUEpdg.W', NULL, NULL, NULL, '2026-07-18 14:32:23', '2026-07-18 14:32:23', 13, 'owner', 1, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(17, 'talha', NULL, 'imtiaz@gmail.com', NULL, '$2y$12$MINAl7lVsdfrAPFCQyvhaOshmQXeHzY3GK0Vifvipz26893FqgVZS', NULL, NULL, NULL, '2026-07-18 18:44:13', '2026-07-18 18:44:13', 14, 'owner', 1, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(18, 'optimos', NULL, 'hamiznadeem54@gmail.com', '2026-07-23 06:31:09', '$2y$12$himFL2ifx7Vu.0i15AOZ2O39OrQ5X964j1Bi34yE4xKoYuglWjKbq', NULL, NULL, 'yCpriSFOtMoflBRSl7zoEvtBcAb4gQXzi0Kna11Jnklt7DIpWgwJc6oOODtT', '2026-07-18 18:50:14', '2026-07-24 04:56:13', 15, 'owner', 1, 0, NULL, NULL, NULL, NULL, 3, NULL, NULL, NULL),
(19, 'TEST', NULL, 'test@gmail.com', NULL, '$2y$12$kLpWAdYHqTHF8rX3uwljnuHo9mmGeapeK7KuEtGQb51y7Lqo3wE7u', NULL, NULL, NULL, '2026-07-18 20:17:45', '2026-07-18 20:17:45', 16, 'owner', 1, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(20, 'sherry', NULL, 'aptech@gmail.com', NULL, '$2y$12$mxWh1jufQaTTX2NgKJ1HO.KsdDBBvgFyW3ZAdTfFHf9g5vYQEkNlq', NULL, NULL, NULL, '2026-07-20 09:35:19', '2026-07-20 09:35:19', 17, 'owner', 1, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_branches`
--

CREATE TABLE `user_branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `branch_name` varchar(255) DEFAULT NULL,
  `branch_code` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_branches`
--

INSERT INTO `user_branches` (`id`, `user_id`, `tenant_id`, `branch_name`, `branch_code`, `address`, `phone`, `is_default`, `is_active`, `created_at`, `updated_at`, `branch_id`) VALUES
(1, 14, 11, 'Main Branch', 'MB-001', NULL, NULL, 1, 1, '2026-07-16 17:12:59', '2026-07-16 17:12:59', NULL),
(5, 18, 15, 'Main Branch', 'TB-001', NULL, NULL, 1, 1, '2026-07-24 05:20:28', '2026-07-24 05:20:28', NULL),
(6, 18, 15, 'Second Branch', 'SB-001', NULL, NULL, 0, 1, '2026-07-24 05:20:46', '2026-07-24 05:20:46', NULL),
(7, 14, 11, 'Downtown Branch', 'DB-001', '456 Health Avenue', '0300-7654321', 0, 1, '2026-07-24 05:52:09', '2026-07-24 05:52:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_password_history`
--

CREATE TABLE `user_password_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_admin_id_foreign` (`admin_id`),
  ADD KEY `audit_logs_subject_type_subject_id_index` (`subject_type`,`subject_id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `branches_tenant_id_code_unique` (`tenant_id`,`code`),
  ADD UNIQUE KEY `branches_code_unique` (`code`);

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
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctors_tenant_id_foreign` (`tenant_id`),
  ADD KEY `doctors_branch_id_index` (`branch_id`),
  ADD KEY `doctors_is_active_index` (`is_active`),
  ADD KEY `doctors_specialization_index` (`specialization`);

--
-- Indexes for table `domains`
--
ALTER TABLE `domains`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `domains_domain_unique` (`domain`),
  ADD KEY `domains_tenant_id_foreign` (`tenant_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoices_tenant_id_foreign` (`tenant_id`),
  ADD KEY `invoices_patient_id_foreign` (`patient_id`),
  ADD KEY `invoices_token_id_foreign` (`token_id`),
  ADD KEY `invoices_branch_id_index` (`branch_id`),
  ADD KEY `invoices_status_index` (`status`);

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
-- Indexes for table `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `login_logs_user_id_foreign` (`user_id`),
  ADD KEY `login_logs_tenant_id_foreign` (`tenant_id`),
  ADD KEY `login_logs_email_index` (`email`),
  ADD KEY `login_logs_ip_address_index` (`ip_address`),
  ADD KEY `login_logs_device_type_index` (`device_type`),
  ADD KEY `login_logs_status_index` (`status`),
  ADD KEY `login_logs_reason_index` (`reason`),
  ADD KEY `login_logs_created_at_index` (`created_at`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medicines_tenant_id_foreign` (`tenant_id`),
  ADD KEY `medicines_barcode_index` (`barcode`),
  ADD KEY `medicines_branch_id_index` (`branch_id`),
  ADD KEY `medicines_generic_name_index` (`generic_name`),
  ADD KEY `medicines_name_index` (`name`),
  ADD KEY `medicines_active_stock_index` (`is_active`,`stock_quantity`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `patients_phone_unique` (`phone`),
  ADD KEY `patients_tenant_id_foreign` (`tenant_id`),
  ADD KEY `patients_branch_id_index` (`branch_id`),
  ADD KEY `patients_phone_index` (`phone`),
  ADD KEY `patients_cnic_index` (`cnic`),
  ADD KEY `patients_name_index` (`name`),
  ADD KEY `patients_gender_index` (`gender`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plans_slug_unique` (`slug`);

--
-- Indexes for table `platform_admins`
--
ALTER TABLE `platform_admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `platform_admins_email_unique` (`email`);

--
-- Indexes for table `platform_invoices`
--
ALTER TABLE `platform_invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `platform_invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `platform_invoices_tenant_id_foreign` (`tenant_id`),
  ADD KEY `platform_invoices_subscription_id_foreign` (`subscription_id`);

--
-- Indexes for table `platform_password_history`
--
ALTER TABLE `platform_password_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `platform_password_history_platform_admin_id_foreign` (`platform_admin_id`);

--
-- Indexes for table `platform_password_resets`
--
ALTER TABLE `platform_password_resets`
  ADD KEY `platform_password_resets_email_index` (`email`);

--
-- Indexes for table `platform_sales`
--
ALTER TABLE `platform_sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `platform_sales_tenant_id_foreign` (`tenant_id`),
  ADD KEY `platform_sales_platform_invoice_id_foreign` (`platform_invoice_id`);

--
-- Indexes for table `platform_settings`
--
ALTER TABLE `platform_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `platform_settings_key_unique` (`key`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prescriptions_tenant_id_foreign` (`tenant_id`),
  ADD KEY `prescriptions_patient_id_foreign` (`patient_id`),
  ADD KEY `prescriptions_doctor_id_foreign` (`doctor_id`),
  ADD KEY `prescriptions_token_id_foreign` (`token_id`),
  ADD KEY `prescriptions_branch_id_index` (`branch_id`);

--
-- Indexes for table `prescription_items`
--
ALTER TABLE `prescription_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prescription_items_tenant_id_foreign` (`tenant_id`),
  ADD KEY `prescription_items_prescription_id_foreign` (`prescription_id`),
  ADD KEY `prescription_items_medicine_id_foreign` (`medicine_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sales_sale_number_unique` (`sale_number`),
  ADD KEY `sales_tenant_id_foreign` (`tenant_id`),
  ADD KEY `sales_patient_id_foreign` (`patient_id`),
  ADD KEY `sales_user_id_foreign` (`user_id`),
  ADD KEY `sales_branch_id_index` (`branch_id`),
  ADD KEY `sales_status_index` (`status`),
  ADD KEY `sales_payment_method_index` (`payment_method`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_items_tenant_id_foreign` (`tenant_id`),
  ADD KEY `sale_items_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_items_itemable_type_itemable_id_index` (`itemable_type`,`itemable_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `services_tenant_id_foreign` (`tenant_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tenants_domain_deleted_at_unique` (`domain`,`deleted_at`);

--
-- Indexes for table `tenant_activity_logs`
--
ALTER TABLE `tenant_activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenant_activity_logs_tenant_id_created_at_index` (`tenant_id`,`created_at`),
  ADD KEY `tenant_activity_logs_user_id_index` (`user_id`),
  ADD KEY `tenant_activity_logs_action_index` (`action`),
  ADD KEY `tenant_activity_logs_created_at_index` (`created_at`),
  ADD KEY `tal_tenant_action_index` (`tenant_id`,`action`),
  ADD KEY `tal_tenant_user_index` (`tenant_id`,`user_id`);

--
-- Indexes for table `tenant_subscriptions`
--
ALTER TABLE `tenant_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenant_subscriptions_tenant_id_foreign` (`tenant_id`),
  ADD KEY `tenant_subscriptions_plan_id_foreign` (`plan_id`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tokens_tenant_id_foreign` (`tenant_id`),
  ADD KEY `tokens_patient_id_foreign` (`patient_id`),
  ADD KEY `tokens_service_id_foreign` (`service_id`),
  ADD KEY `tokens_branch_id_index` (`branch_id`),
  ADD KEY `tokens_status_index` (`status`),
  ADD KEY `tokens_token_number_index` (`token_number`),
  ADD KEY `tokens_doctor_id_status_index` (`doctor_id`,`status`),
  ADD KEY `tokens_status_created_at_index` (`status`,`created_at`),
  ADD KEY `tokens_doctor_status_index` (`doctor_id`,`status`),
  ADD KEY `tokens_status_created_index` (`status`,`created_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_tenant_id_username_unique` (`tenant_id`,`username`),
  ADD KEY `users_doctor_id_foreign` (`doctor_id`);

--
-- Indexes for table `user_branches`
--
ALTER TABLE `user_branches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_tenant_branch_unique` (`user_id`,`tenant_id`,`branch_name`),
  ADD UNIQUE KEY `user_branches_branch_code_unique` (`branch_code`),
  ADD KEY `user_branches_tenant_id_user_id_index` (`tenant_id`,`user_id`),
  ADD KEY `user_branches_tenant_id_is_active_index` (`tenant_id`,`is_active`),
  ADD KEY `user_branches_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `user_password_history`
--
ALTER TABLE `user_password_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_password_history_user_id_index` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `domains`
--
ALTER TABLE `domains`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `platform_admins`
--
ALTER TABLE `platform_admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `platform_invoices`
--
ALTER TABLE `platform_invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `platform_password_history`
--
ALTER TABLE `platform_password_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `platform_sales`
--
ALTER TABLE `platform_sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `platform_settings`
--
ALTER TABLE `platform_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `prescription_items`
--
ALTER TABLE `prescription_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tenant_activity_logs`
--
ALTER TABLE `tenant_activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tenant_subscriptions`
--
ALTER TABLE `tenant_subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `user_branches`
--
ALTER TABLE `user_branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_password_history`
--
ALTER TABLE `user_password_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `platform_admins` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `branches`
--
ALTER TABLE `branches`
  ADD CONSTRAINT `branches_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `doctors_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `domains`
--
ALTER TABLE `domains`
  ADD CONSTRAINT `domains_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoices_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoices_token_id_foreign` FOREIGN KEY (`token_id`) REFERENCES `tokens` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `login_logs`
--
ALTER TABLE `login_logs`
  ADD CONSTRAINT `login_logs_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `login_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `medicines`
--
ALTER TABLE `medicines`
  ADD CONSTRAINT `medicines_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `medicines_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `patients_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `platform_invoices`
--
ALTER TABLE `platform_invoices`
  ADD CONSTRAINT `platform_invoices_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `tenant_subscriptions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `platform_invoices_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `platform_password_history`
--
ALTER TABLE `platform_password_history`
  ADD CONSTRAINT `platform_password_history_platform_admin_id_foreign` FOREIGN KEY (`platform_admin_id`) REFERENCES `platform_admins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `platform_sales`
--
ALTER TABLE `platform_sales`
  ADD CONSTRAINT `platform_sales_platform_invoice_id_foreign` FOREIGN KEY (`platform_invoice_id`) REFERENCES `platform_invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `platform_sales_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `prescriptions_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `prescriptions_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescriptions_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescriptions_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescriptions_token_id_foreign` FOREIGN KEY (`token_id`) REFERENCES `tokens` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prescription_items`
--
ALTER TABLE `prescription_items`
  ADD CONSTRAINT `prescription_items_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescription_items_prescription_id_foreign` FOREIGN KEY (`prescription_id`) REFERENCES `prescriptions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescription_items_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sales_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sales_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_items_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tenant_activity_logs`
--
ALTER TABLE `tenant_activity_logs`
  ADD CONSTRAINT `tenant_activity_logs_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tenant_activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tenant_subscriptions`
--
ALTER TABLE `tenant_subscriptions`
  ADD CONSTRAINT `tenant_subscriptions_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tenant_subscriptions_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tokens`
--
ALTER TABLE `tokens`
  ADD CONSTRAINT `tokens_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tokens_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tokens_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tokens_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tokens_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_branches`
--
ALTER TABLE `user_branches`
  ADD CONSTRAINT `user_branches_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `user_branches_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_branches_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_password_history`
--
ALTER TABLE `user_password_history`
  ADD CONSTRAINT `user_password_history_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
