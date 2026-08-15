-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 15, 2026 at 09:35 PM
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
-- Database: `blood_sample_circulation`
--

-- --------------------------------------------------------

--
-- Table structure for table `blood_samples`
--

CREATE TABLE `blood_samples` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sample_code` varchar(255) NOT NULL,
  `blood_type` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'collected',
  `patient_id` bigint(20) UNSIGNED DEFAULT NULL,
  `collected_by` bigint(20) UNSIGNED DEFAULT NULL,
  `sample_type` varchar(255) DEFAULT NULL,
  `collected_at` timestamp NULL DEFAULT NULL,
  `quality_checks` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`quality_checks`)),
  `rejection_reason` text DEFAULT NULL,
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blood_samples`
--

INSERT INTO `blood_samples` (`id`, `created_at`, `updated_at`, `sample_code`, `blood_type`, `status`, `patient_id`, `collected_by`, `sample_type`, `collected_at`, `quality_checks`, `rejection_reason`, `reviewed_by`, `reviewed_at`) VALUES
(1, '2026-08-08 11:06:56', '2026-08-13 15:51:51', 'SMP-001', 'A+', 'rejected', 56, 44, 'Whole Blood', '2026-08-09 05:18:53', '{\"correct_labeling\":\"1\",\"sufficient_volume\":\"1\",\"no_leakage\":\"1\",\"proper_container\":\"1\"}', 'sample is problematic', 34, '2026-08-09 08:18:53'),
(2, '2026-08-08 11:06:56', '2026-08-13 15:51:51', 'SMP-002', 'B+', 'rejected', 54, 48, 'Whole Blood', '2026-08-12 12:51:51', '{\"correct_labeling\":false,\"sufficient_volume\":false,\"no_leakage\":true,\"proper_container\":true}', 'Sample failed laboratory quality review.', 7, '2026-08-12 15:51:51'),
(4, '2026-08-08 11:06:56', '2026-08-13 14:51:19', 'SMP-004', 'AB+', 'accepted', 30, 48, 'Whole Blood', '2026-08-04 14:51:19', '{\"correct_labeling\":\"0\",\"sufficient_volume\":\"0\",\"no_leakage\":\"0\",\"proper_container\":\"0\"}', NULL, 3, '2026-08-09 10:47:38'),
(5, '2026-08-08 11:06:56', '2026-08-13 15:32:31', 'SMP-005', 'A-', 'pending', 34, NULL, 'Whole Blood', NULL, NULL, NULL, NULL, NULL),
(6, '2026-08-08 11:06:56', '2026-08-13 14:51:19', 'SMP-006', 'B-', 'accepted', 22, 43, 'Whole Blood', '2026-08-06 14:51:19', '{\"correct_labeling\":\"1\",\"sufficient_volume\":\"1\",\"no_leakage\":\"1\",\"proper_container\":\"1\"}', NULL, 2, '2026-08-12 11:15:21'),
(7, '2026-08-08 11:06:56', '2026-08-13 14:51:19', 'SMP-007', 'O-', 'accepted', 21, 50, 'Whole Blood', '2026-08-05 14:51:19', '{\"correct_labeling\":true,\"sufficient_volume\":true,\"no_clotting\":true,\"proper_container\":true}', NULL, 37, '2026-08-11 14:51:19'),
(8, '2026-08-08 11:06:56', '2026-08-13 15:32:38', 'SMP-008', 'AB-', 'pending', 56, NULL, 'Whole Blood', NULL, NULL, NULL, NULL, NULL),
(9, '2026-08-08 11:06:56', '2026-08-13 13:33:24', 'SMP-009', 'A+', 'pending', 38, NULL, 'Whole Blood', NULL, NULL, NULL, NULL, NULL),
(10, '2026-08-08 11:06:56', '2026-08-13 13:33:24', 'SMP-010', 'O+', 'pending', 22, NULL, 'Whole Blood', NULL, NULL, NULL, NULL, NULL),
(11, '2026-08-09 08:35:52', '2026-08-13 15:51:51', 'BS-INM8ZRUH', NULL, 'rejected', 28, 42, 'Whole Blood', '2026-08-09 05:38:34', '{\"correct_labeling\":\"1\",\"sufficient_volume\":\"1\",\"no_leakage\":\"1\",\"proper_container\":\"1\"}', 'not perfect', 31, '2026-08-09 08:38:34'),
(12, '2026-08-12 11:57:38', '2026-08-13 14:51:19', 'SMP-964', 'O+', 'accepted', 53, 48, 'Whole Blood', '2026-08-12 11:57:38', '{\"correct_labeling\":true,\"sufficient_volume\":true,\"no_clotting\":true,\"proper_container\":true}', NULL, 31, '2026-08-13 14:51:19'),
(13, '2026-08-12 11:57:38', '2026-08-13 14:51:19', 'SMP-830', 'O+', 'accepted', 29, 44, 'Whole Blood', '2026-08-12 11:57:38', '{\"correct_labeling\":true,\"sufficient_volume\":true,\"no_clotting\":true,\"proper_container\":true}', NULL, 36, '2026-08-13 14:51:19'),
(14, '2026-08-12 11:57:38', '2026-08-13 14:51:19', 'SMP-614', 'O-', 'accepted', 53, 48, 'Whole Blood', '2026-08-12 11:57:38', '{\"correct_labeling\":true,\"sufficient_volume\":true,\"no_clotting\":true,\"proper_container\":true}', NULL, 31, '2026-08-13 14:51:19'),
(15, '2026-08-12 11:57:38', '2026-08-13 14:51:19', 'SMP-641', 'O-', 'accepted', 53, 46, 'Whole Blood', '2026-08-12 11:57:38', '{\"correct_labeling\":true,\"sufficient_volume\":true,\"no_clotting\":true,\"proper_container\":true}', NULL, 38, '2026-08-12 14:51:19'),
(16, '2026-08-12 11:57:38', '2026-08-13 14:51:19', 'SMP-817', 'B+', 'accepted', 55, 47, 'Whole Blood', '2026-08-12 11:57:38', '{\"correct_labeling\":true,\"sufficient_volume\":true,\"no_clotting\":true,\"proper_container\":true}', NULL, 38, '2026-08-12 14:51:19'),
(17, '2026-08-12 11:57:38', '2026-08-13 14:51:19', 'SMP-309', 'AB+', 'accepted', 25, 44, 'Whole Blood', '2026-08-12 11:57:38', '{\"correct_labeling\":true,\"sufficient_volume\":true,\"no_clotting\":true,\"proper_container\":true}', NULL, 1, '2026-08-13 14:51:19'),
(18, '2026-08-12 11:57:38', '2026-08-13 14:51:19', 'SMP-612', 'B+', 'accepted', 22, 46, 'Whole Blood', '2026-08-12 11:57:38', '{\"correct_labeling\":true,\"sufficient_volume\":true,\"no_clotting\":true,\"proper_container\":true}', NULL, 4, '2026-08-13 14:51:19'),
(19, '2026-08-12 11:57:38', '2026-08-13 14:51:19', 'SMP-893', 'B-', 'accepted', 56, 41, 'Whole Blood', '2026-08-12 11:57:38', '{\"correct_labeling\":true,\"sufficient_volume\":true,\"no_clotting\":true,\"proper_container\":true}', NULL, 35, '2026-08-13 14:51:19'),
(20, '2026-08-12 11:57:38', '2026-08-13 14:51:19', 'SMP-263', 'O-', 'accepted', 54, 48, 'Whole Blood', '2026-08-12 11:57:38', '{\"correct_labeling\":true,\"sufficient_volume\":true,\"no_clotting\":true,\"proper_container\":true}', NULL, 7, '2026-08-11 14:51:19'),
(21, '2026-08-12 11:57:38', '2026-08-13 14:51:19', 'SMP-444', 'O-', 'accepted', 27, 49, 'Whole Blood', '2026-08-12 11:57:38', '{\"correct_labeling\":true,\"sufficient_volume\":true,\"no_clotting\":true,\"proper_container\":true}', NULL, 32, '2026-08-11 14:51:19');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `collection_centers`
--

CREATE TABLE `collection_centers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `collection_centers`
--

INSERT INTO `collection_centers` (`id`, `name`, `address`, `phone`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Dhanmondi Collection Center', 'Dhanmondi, Dhaka', '01710000001', 1, '2026-08-09 09:20:11', '2026-08-09 09:20:11'),
(2, 'Uttara Collection Center', 'Uttara, Dhaka', '01710000002', 1, '2026-08-09 09:20:11', '2026-08-09 09:20:11'),
(3, 'Mirpur Collection Center', 'Mirpur, Dhaka', '01710000003', 1, '2026-08-09 09:20:11', '2026-08-09 09:20:11'),
(4, 'Banani Collection Center', 'Banani, Dhaka', '01710000004', 1, '2026-08-09 09:20:11', '2026-08-09 09:20:11'),
(5, 'Mohakhali Collection Center', 'Mohakhali, Dhaka', '01710000005', 1, '2026-08-09 09:20:11', '2026-08-09 09:20:11');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventories`
--

CREATE TABLE `inventories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `blood_sample_id` bigint(20) UNSIGNED NOT NULL,
  `refrigerator` varchar(255) NOT NULL,
  `shelf` varchar(255) NOT NULL,
  `rack` varchar(255) NOT NULL,
  `storage_location` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventories`
--

INSERT INTO `inventories` (`id`, `blood_sample_id`, `refrigerator`, `shelf`, `rack`, `storage_location`, `created_at`, `updated_at`) VALUES
(2, 4, '677897Y', 'U908-0-', '9', '89789789', '2026-08-12 11:36:28', '2026-08-12 11:36:28'),
(3, 2, '677897Y', 'U908-0-', '9', 'j9ojo', '2026-08-12 11:36:51', '2026-08-12 11:36:51'),
(6, 8, '677897Y', 'U908-0-', '2', 'e', '2026-08-12 11:42:41', '2026-08-12 11:42:41'),
(7, 4, '677897Y', 'n=k', 'jjojj', 'j9ojo', '2026-08-12 11:49:18', '2026-08-12 11:49:18');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
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
-- Table structure for table `laboratories`
--

CREATE TABLE `laboratories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `daily_capacity` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `laboratories`
--

INSERT INTO `laboratories` (`id`, `name`, `address`, `phone`, `daily_capacity`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Central Diagnostic Laboratory', 'Dhanmondi, Dhaka', '01810000001', 100, 1, '2026-08-09 09:20:12', '2026-08-09 09:20:12'),
(2, 'Uttara Medical Laboratory', 'Uttara, Dhaka', '01810000002', 80, 1, '2026-08-09 09:20:12', '2026-08-09 09:20:12'),
(3, 'Mirpur Diagnostic Laboratory', 'Mirpur, Dhaka', '01810000003', 70, 1, '2026-08-09 09:20:12', '2026-08-09 09:20:12'),
(4, 'Banani Medical Laboratory', 'Banani, Dhaka', '01810000004', 60, 1, '2026-08-09 09:20:12', '2026-08-09 09:20:12');

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
(5, '2026_08_07_202155_create_test_table', 2),
(6, '2026_08_07_203534_create_blood_samples_table', 3),
(7, '2026_08_07_212611_add_role_to_users_table', 4),
(8, '2026_08_08_165421_add_sample_details_to_blood_samples_table', 5),
(9, '2026_08_08_165604_create_sample_reviews_table', 6),
(10, '2026_08_08_165335_add_sample_details_to_blood_samples_table', 7),
(11, '2026_08_08_174539_create_notifications_table', 7),
(12, '2026_08_09_150927_create_collection_centers_table', 8),
(13, '2026_08_09_150933_create_laboratories_table', 8),
(14, '2026_08_09_150938_create_sample_transportations_table', 8),
(15, '2026_08_08_202130_create_inventories_table', 9);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('ed805530-f42a-4ce1-9a3e-941d38d80bc6', 'App\\Notifications\\BloodSampleRejectedNotification', 'App\\Models\\User', 28, '{\"title\":\"Blood Sample Rejected\",\"message\":\"Your blood sample BS-INM8ZRUH has been rejected.\",\"sample_code\":\"BS-INM8ZRUH\",\"reason\":\"perfect\",\"reviewed_at\":\"2026-08-09 14:38:34\"}', NULL, '2026-08-09 08:38:36', '2026-08-09 08:38:36');

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
-- Table structure for table `sample_reviews`
--

CREATE TABLE `sample_reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `blood_sample_id` bigint(20) UNSIGNED NOT NULL,
  `reviewed_by` bigint(20) UNSIGNED NOT NULL,
  `decision` varchar(255) NOT NULL,
  `quality_checks` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`quality_checks`)),
  `rejection_reason` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `reviewed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sample_reviews`
--

INSERT INTO `sample_reviews` (`id`, `blood_sample_id`, `reviewed_by`, `decision`, `quality_checks`, `rejection_reason`, `notes`, `reviewed_at`, `created_at`, `updated_at`) VALUES
(1, 2, 52, 'rejected', '[\"correct_labeling\",\"no_leakage\"]', 'ld', 'qdw', '2026-08-08 11:14:59', '2026-08-08 11:14:59', '2026-08-08 11:14:59');

-- --------------------------------------------------------

--
-- Table structure for table `sample_transportations`
--

CREATE TABLE `sample_transportations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `blood_sample_id` bigint(20) UNSIGNED NOT NULL,
  `collection_center_id` bigint(20) UNSIGNED NOT NULL,
  `laboratory_id` bigint(20) UNSIGNED NOT NULL,
  `transported_by` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `departure_time` timestamp NULL DEFAULT NULL,
  `arrival_time` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sample_transportations`
--

INSERT INTO `sample_transportations` (`id`, `blood_sample_id`, `collection_center_id`, `laboratory_id`, `transported_by`, `status`, `departure_time`, `arrival_time`, `notes`, `created_at`, `updated_at`) VALUES
(2, 4, 2, 2, 48, 'delivered', '2026-08-09 08:25:37', '2026-08-09 09:57:06', 'Sample is currently being transported to the laboratory.', '2026-08-09 09:25:37', '2026-08-13 15:32:59'),
(5, 7, 2, 3, 50, 'delivered', '2026-08-09 10:50:58', '2026-08-09 10:51:08', NULL, '2026-08-09 10:49:46', '2026-08-13 15:33:08'),
(7, 6, 1, 1, 43, 'delivered', '2026-08-06 14:51:19', '2026-08-12 10:15:21', 'Historical dummy transportation record.', '2026-08-13 15:18:13', '2026-08-13 15:18:13'),
(8, 12, 1, 1, 48, 'delivered', '2026-08-12 11:57:38', '2026-08-13 13:51:19', 'Historical dummy transportation record.', '2026-08-13 15:18:13', '2026-08-13 15:18:13'),
(9, 13, 1, 2, 44, 'delivered', '2026-08-12 11:57:38', '2026-08-13 13:51:19', 'Historical dummy transportation record.', '2026-08-13 15:18:13', '2026-08-13 15:18:13'),
(10, 14, 1, 1, 48, 'delivered', '2026-08-12 11:57:38', '2026-08-13 13:51:19', 'Historical dummy transportation record.', '2026-08-13 15:18:13', '2026-08-13 15:18:13'),
(11, 15, 1, 1, 46, 'delivered', '2026-08-12 11:57:38', '2026-08-12 13:51:19', 'Historical dummy transportation record.', '2026-08-13 15:18:13', '2026-08-13 15:18:13'),
(12, 16, 1, 3, 47, 'delivered', '2026-08-12 11:57:38', '2026-08-12 13:51:19', 'Historical dummy transportation record.', '2026-08-13 15:18:13', '2026-08-13 15:18:13'),
(13, 17, 1, 1, 44, 'delivered', '2026-08-12 11:57:38', '2026-08-13 13:51:19', 'Historical dummy transportation record.', '2026-08-13 15:18:13', '2026-08-13 15:18:13'),
(14, 18, 1, 1, 46, 'delivered', '2026-08-12 11:57:38', '2026-08-13 13:51:19', 'Historical dummy transportation record.', '2026-08-13 15:18:13', '2026-08-13 15:18:13'),
(15, 19, 1, 1, 41, 'delivered', '2026-08-12 11:57:38', '2026-08-13 13:51:19', 'Historical dummy transportation record.', '2026-08-13 15:18:13', '2026-08-13 15:18:13'),
(16, 20, 1, 3, 48, 'delivered', '2026-08-11 12:51:19', '2026-08-11 13:51:19', 'Historical dummy transportation record.', '2026-08-13 15:18:13', '2026-08-13 15:18:13'),
(17, 21, 1, 1, 49, 'delivered', '2026-08-11 12:51:19', '2026-08-11 13:51:19', 'Historical dummy transportation record.', '2026-08-13 15:18:13', '2026-08-13 15:18:13'),
(18, 1, 1, 1, 44, 'delivered', '2026-08-09 06:18:53', '2026-08-09 07:18:53', 'Historical transportation completed before laboratory rejection.', '2026-08-13 15:51:51', '2026-08-13 15:51:51'),
(19, 2, 1, 1, 48, 'delivered', '2026-08-12 13:51:51', '2026-08-12 14:51:51', 'Historical transportation completed before laboratory rejection.', '2026-08-13 15:51:51', '2026-08-13 15:51:51'),
(20, 11, 1, 1, 42, 'delivered', '2026-08-09 06:38:34', '2026-08-09 07:38:34', 'Historical transportation completed before laboratory rejection.', '2026-08-13 15:51:51', '2026-08-13 15:51:51'),
(21, 5, 4, 2, 46, 'pending', NULL, NULL, NULL, '2026-08-13 15:59:34', '2026-08-13 15:59:34'),
(22, 8, 4, 3, 43, 'pending', NULL, NULL, NULL, '2026-08-13 16:06:21', '2026-08-13 16:06:21');

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
('UxaY4B8lA5o8mvXegL45yn1UqXSjNZ3IlXMM8Vw5', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJZcG44a1g1cFZoYkNwZXJRa1JabThjazhwUnlDZ01SMVZIclozZFVpIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL3RyYW5zcG9ydGF0aW9uIiwicm91dGUiOiJ0cmFuc3BvcnRhdGlvbi5pbmRleCJ9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6Mn0=', 1786660101);

-- --------------------------------------------------------

--
-- Table structure for table `test_table`
--

CREATE TABLE `test_table` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `message` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `test_table`
--

INSERT INTO `test_table` (`id`, `message`, `created_at`, `updated_at`) VALUES
(1, 'Hello', NULL, NULL),
(2, 'Blood sample received', NULL, NULL),
(3, 'Sample dispatched', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'patient'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`) VALUES
(1, 'System Admin', 'admin1@bloodsystem.com', NULL, '$2y$12$wUh2b/6eMKZ7XqKgF6qTc.8oGp5rdmq9BuPQBm93h1ijNb9mpxlIm', NULL, NULL, NULL, 'admin'),
(2, 'Nusrat Jahan', 'nusrat.admin@bloodsystem.com', NULL, '$2y$12$c.Rksp0Fjke3D6vTzNbVUedPKF0B9lfNXtcrM4slVBP36Td/Zn82.', NULL, NULL, NULL, 'admin'),
(3, 'Rakib Hasan', 'rakib.admin@bloodsystem.com', NULL, '$2y$12$Npa61gH3LStR9840MA0Oyu7meXgHRz553MBenWrvrgeM1NQo84jve', NULL, NULL, NULL, 'admin'),
(4, 'Farzana Islam', 'farzana.admin@bloodsystem.com', NULL, '$2y$12$uFQsy1zyV.OrXeU2g/QY1OjDyxBP7Zw5RCwJZV.7sIlfjz2FAFQMq', NULL, NULL, NULL, 'admin'),
(5, 'Sabbir Ahmed', 'sabbir.admin@bloodsystem.com', NULL, '$2y$12$wxVPwOgE2SXb52GJO1i8RemAOpZTyEzV5w53JUGPM1Uxz/AnVL.4q', NULL, NULL, NULL, 'admin'),
(6, 'Mehedi Karim', 'mehedi.admin@bloodsystem.com', NULL, '$2y$12$eqv6WfFWGnJkGHpjxk4KTuwLthCv9lP8iI.PINFQy4Rv53BNeBRkC', NULL, NULL, NULL, 'admin'),
(7, 'Tania Rahman', 'tania.admin@bloodsystem.com', NULL, '$2y$12$.MQcyRjmnbLf8dIW/kJ/A.1pPtkyi4P0s6YgkPXKpFcv05wSIcPeO', NULL, NULL, NULL, 'admin'),
(8, 'Imran Hossain', 'imran.admin@bloodsystem.com', NULL, '$2y$12$i3.PB93ioQiKWcrgKFOQA./iFfxUBYizW5oJWr7ap3Px./5g2NwtK', NULL, NULL, NULL, 'admin'),
(9, 'Sadia Akter', 'sadia.admin@bloodsystem.com', NULL, '$2y$12$c6/R2eMWf2FWYoQfTmVNkej99C5h2x5dp0qyxZfR405KXkyMQxeXi', NULL, NULL, NULL, 'admin'),
(10, 'Arif Chowdhury', 'arif.admin@bloodsystem.com', NULL, '$2y$12$eyykL7uoXOjbXzzyiifdMO/unPIh71jkpQXoWH/cE9OoLKfFqVio2', NULL, NULL, NULL, 'admin'),
(11, 'Dr. Ahmed Rahman', 'ahmed.doctor@bloodsystem.com', NULL, '$2y$12$UPKe7ptFqUvIdj5NBxfq8ees5/U6T4oapcfI797brvJPutMJAOyR.', NULL, NULL, NULL, 'doctor'),
(12, 'Dr. Sara Islam', 'sara.doctor@bloodsystem.com', NULL, '$2y$12$qKIXqQ8q26XkBCWy5RyFouvTBSh2vir5C.HNS9y56u7ddWQT941SW', NULL, NULL, NULL, 'doctor'),
(13, 'Dr. Mahmud Hasan', 'mahmud.doctor@bloodsystem.com', NULL, '$2y$12$0VQwNb6DMr.9VHLkWiDOo.jJ/NaO.Y1yasd4DCIcuM3ySqDuYEhPq', NULL, NULL, NULL, 'doctor'),
(14, 'Dr. Rafiq Karim', 'rafiq.doctor@bloodsystem.com', NULL, '$2y$12$PwkxozMHFMvS7L/Ul5WoUek9mPF0GaH6wct8Q/LBdfws7v.ejFOUe', NULL, NULL, NULL, 'doctor'),
(15, 'Dr. Jannatul Ferdous', 'jannatul.doctor@bloodsystem.com', NULL, '$2y$12$ufCK6Rqz9sBbbh0JCEbFPuLA7KA5ZaUSfdGVgec8N9QG55/GjU7xW', NULL, NULL, NULL, 'doctor'),
(16, 'Dr. Tanvir Ahmed', 'tanvir.doctor@bloodsystem.com', NULL, '$2y$12$DGbBOFlzNoCwCDP3aZUKmuFtBTp1zQDODdhuwZXKh.lu/c4SRoiMS', NULL, NULL, NULL, 'doctor'),
(17, 'Dr. Nusrat Sultana', 'nusrat.doctor@bloodsystem.com', NULL, '$2y$12$3HkLCYa5yQYuRaX46gbawO17OrMRHBFfO81iIoqDR2lvURUd8AP9.', NULL, NULL, NULL, 'doctor'),
(18, 'Dr. Faisal Khan', 'faisal.doctor@bloodsystem.com', NULL, '$2y$12$5RqGNqb0uowYekA6OkUtv.ah5lKEPooMVHKE3CR8Ypo0nQaHioDiW', NULL, NULL, NULL, 'doctor'),
(19, 'Dr. Sharmeen Akter', 'sharmeen.doctor@bloodsystem.com', NULL, '$2y$12$2gCob4UqF.qDo0vq01EiweYLUQ5mI7mNTivROgDAXPMIWS4bK/7aS', NULL, NULL, NULL, 'doctor'),
(20, 'Dr. Kamal Hossain', 'kamal.doctor@bloodsystem.com', NULL, '$2y$12$.cDBbtl6Jnqdp2TYj/Dha.lPORE./7aSZmVMeQu3JtbhPAPLL5f4a', NULL, NULL, NULL, 'doctor'),
(21, 'Rahim Uddin', 'rahim.patient@gmail.com', NULL, '$2y$12$oy4jR2jJpwkkyPIiMAyrFOXVzBnQIX./B4bDBeQlsdUAXtICu98va', NULL, NULL, NULL, 'patient'),
(22, 'Karim Mia', 'karim.patient@gmail.com', NULL, '$2y$12$lJvtG80tQA1HtgdyRx5szObfZQ49rvG7HYCB034.OFP/gMhEM9Qd2', NULL, NULL, NULL, 'patient'),
(23, 'Sumi Akter', 'sumi.patient@gmail.com', NULL, '$2y$12$/6yop27PBVxTpAwx4Ud1Eu7.5DJg7C9fOenajA9Jag2cefVriNPmi', NULL, NULL, NULL, 'patient'),
(24, 'Rohan Ahmed', 'rohan.patient@gmail.com', NULL, '$2y$12$pWNo6uzBPPxra4MwAoN6WOc5aPVAf.wAUgIvSIqDRc9zmDOS3Ryz6', NULL, NULL, NULL, 'patient'),
(25, 'Mim Rahman', 'mim.patient@gmail.com', NULL, '$2y$12$xQ/0g0HOVpgPC6/7POF.R./cSCXzLP/CIcV31KAWK1gb7WfnwothO', NULL, NULL, NULL, 'patient'),
(26, 'Hasan Ali', 'hasan.patient@gmail.com', NULL, '$2y$12$7kQYR6IunqGyo6vx3NSSR.tITYv9XSpHvTYJK19h7TVZ3JCWPJOhy', NULL, NULL, NULL, 'patient'),
(27, 'Tania Islam', 'tania.patient@gmail.com', NULL, '$2y$12$HwgQgXw.hzoWswcR5vVZ2OKT3d9OCfxApXKP0xXeeLfytH0AUMz12', NULL, NULL, NULL, 'patient'),
(28, 'Jubayer Khan', 'jubayer.patient@gmail.com', NULL, '$2y$12$BPi0AjuoisV6kJL3aGTRw.Khew4qXri0HjkjH5u34q5KFZ03XXahe', NULL, NULL, NULL, 'patient'),
(29, 'Nabila Noor', 'nabila.patient@gmail.com', NULL, '$2y$12$W21k998ZVue/voRBUh.T5OatB/m.RvUGVayPv8XX8g5bQx.nv1WWG', NULL, NULL, NULL, 'patient'),
(30, 'Fahim Hossain', 'fahim.patient@gmail.com', NULL, '$2y$12$PMW8EsdT6Si8y6/cwrl5cObvcBRo4YTMchfvbjQj2SY9vY/CPVQ9u', NULL, NULL, NULL, 'patient'),
(31, 'Hasan Lab Technician', 'hasan.lab@gmail.com', NULL, '$2y$12$sQ3CXONchRlt06grMcyrd.Lc001DGSQV.ZjlZ.oAmvzl1A.67LizW', NULL, NULL, NULL, 'lab_staff'),
(32, 'Rasel Ahmed', 'rasel.lab@gmail.com', NULL, '$2y$12$rNdmwR8rk7CJxyfBEuoqZucrz2n4O2k7MS7Av66OzuR/87FVR3C0S', NULL, NULL, NULL, 'lab_staff'),
(33, 'Mitu Sarker', 'mitu.lab@gmail.com', NULL, '$2y$12$.91Zjw6ayb9Np7ITKx3Nb.zVla5pcZ/cEfdobRGxqf.uISrjybMxC', NULL, NULL, NULL, 'lab_staff'),
(34, 'Shakil Khan', 'shakil.lab@gmail.com', NULL, '$2y$12$eBUrQ/OUz.qrbL.p84jQROqQtKZEctcEzwN37p4r8Bz/.rbmfWt.a', NULL, NULL, NULL, 'lab_staff'),
(35, 'Priya Das', 'priya.lab@gmail.com', NULL, '$2y$12$PDxjW974FteOMuMia9M9fe0314B413oEbooHwgnGWWBVp20l0WFMe', NULL, NULL, NULL, 'lab_staff'),
(36, 'Nayeem Hasan', 'nayeem.lab@gmail.com', NULL, '$2y$12$EOnbSzaRqfxfCvWZZQ6jzO7ZoloiAAe/bM9PWqfoPhsjauLeYV7ka', NULL, NULL, NULL, 'lab_staff'),
(37, 'Ruma Akter', 'ruma.lab@gmail.com', NULL, '$2y$12$4pCor5TxB.89mBEQbYuznu5/gMjSb5hlK6/a6RQzSIW/HuWFbKqoK', NULL, NULL, NULL, 'lab_staff'),
(38, 'Bashir Ahmed', 'bashir.lab@gmail.com', NULL, '$2y$12$KN6i7e8g8UfQye8Tms.U0Oyobs.dBUfwwopBBxSVAlvUF1shwaO.a', NULL, NULL, NULL, 'lab_staff'),
(39, 'Sakib Islam', 'sakib.lab@gmail.com', NULL, '$2y$12$ZUl2Nz83Na/Goc74fSdNhewiMT.LCDAUEDIetcg04pPQa5GWG1qaq', NULL, NULL, NULL, 'lab_staff'),
(40, 'Morshed Karim', 'morshed.lab@gmail.com', NULL, '$2y$12$FQNxHteDkzd0UAbNDpFzpePVq4rgQ2ssWLHYkeoxlLnKz1wS9.eAq', NULL, NULL, NULL, 'lab_staff'),
(41, 'Karim Hossain', 'karim.collector@gmail.com', NULL, '$2y$12$F3lJsRT15g2OsB3sb6yFMOZ/s2BxeY1b.gkGN6b3TaLn86jIP9YoK', NULL, NULL, NULL, 'sample_collector'),
(42, 'Rony Mia', 'rony.collector@gmail.com', NULL, '$2y$12$AtpzXSB5Tuli2O0c9dZcD.8kFC5.YkHaBrrIDraBkJkclAqHV5ykC', NULL, NULL, NULL, 'sample_collector'),
(43, 'Babul Ahmed', 'babul.collector@gmail.com', NULL, '$2y$12$yYxDeK7FpYWjOmRwlt6qveGu8/ryxyTURXdAu2sCFWFe2xicyQIyO', NULL, NULL, NULL, 'sample_collector'),
(44, 'Sohan Rahman', 'sohan.collector@gmail.com', NULL, '$2y$12$j4nJZtWLwxRLBpKIqwbRhOZV0tuLzWVxKGgpeNi2TMqkRbA8T3PkG', NULL, NULL, NULL, 'sample_collector'),
(45, 'Nadim Khan', 'nadim.collector@gmail.com', NULL, '$2y$12$T3a.KWaErPFdc8BZV1fWe.qy0vxwtXfMfWp/iGliaRiHKcP3QQmAm', NULL, NULL, NULL, 'sample_collector'),
(46, 'Rafi Islam', 'rafi.collector@gmail.com', NULL, '$2y$12$CB0.mUAzoGX6Dy6FIdUlSu5iFAi1b/SSV96TzrNzvAeOGWwYlmPvW', NULL, NULL, NULL, 'sample_collector'),
(47, 'Shuvo Das', 'shuvo.collector@gmail.com', NULL, '$2y$12$ZwUTDaNZTkBow7WADbcHeevO0vO3z8vm5gp70aZErNf8/ij3iaNdy', NULL, NULL, NULL, 'sample_collector'),
(48, 'Masud Rana', 'masud.collector@gmail.com', NULL, '$2y$12$msb53tnrD0TvfsISz2oPZ.CHQQ894otN/z/VHXwGgGtc0rX7V3i7a', NULL, NULL, NULL, 'sample_collector'),
(49, 'Anik Hasan', 'anik.collector@gmail.com', NULL, '$2y$12$3ZltJXRi1Muad00XYmrtou4MsX7/94FqZML8XKriQVKYTSoiiBqYa', NULL, NULL, NULL, 'sample_collector'),
(50, 'Jewel Ahmed', 'jewel.collector@gmail.com', NULL, '$2y$12$bBwAMVtZPIcPr22piqf3A.XJj0pENBwfCJcsyjL2NUm4FDZiFAxV2', NULL, NULL, NULL, 'sample_collector'),
(51, 'Labiba', 'labiba@gmail.com', NULL, '$2y$12$RgsGLYlKBKc5zvAxmB4B/OLbod4hn7FmkEWjlhwydpxlxuUQ1WjKm', NULL, '2026-08-08 09:01:54', '2026-08-08 09:01:54', 'patient'),
(52, 'Labiba Binte Arif', 'labibadoctor@gmail.com', NULL, '$2y$12$ThgQ0ujsJZnF/o12aWa6ZOE3qWNff7D8L1Toqmu23QLr2Dru1JN0a', NULL, '2026-08-08 09:20:13', '2026-08-08 09:20:13', 'patient'),
(53, 'labiba@yahoo.com', 'labiba@yahoo.com', NULL, '$2y$12$1v0a4Soy3ff565zFRe7ka.zo6JMkjhGp4Mv37wo4ICxMvOnPBWNgS', NULL, '2026-08-08 11:10:55', '2026-08-08 11:10:55', 'patient'),
(54, 'admin', 'admin@gmail.com', NULL, '$2y$12$kc1xSknqSWN5ljclkDbof.bpdUd7ta.haX7huvr09kGMtg/zHB052', NULL, '2026-08-09 08:02:17', '2026-08-09 08:02:17', 'patient'),
(55, 'sabbir hossain', 'collector@gmail.com', NULL, '$2y$12$8BLHbJSS.Hlkv8zZLSKX1OV0n5K4zCnEYneHg2tzBdv7B4xUs0tTm', NULL, '2026-08-09 08:07:15', '2026-08-09 08:07:15', 'patient'),
(56, 'sabbir hossain', 'labcollector@gmail.com', NULL, '$2y$12$O1khKhgmYFn50OvsFNZD9OdwxA4v58Zhlmvx3gB0.A4QFF2Tm6EO2', NULL, '2026-08-09 08:07:32', '2026-08-09 08:07:32', 'patient');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blood_samples`
--
ALTER TABLE `blood_samples`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blood_samples_sample_code_unique` (`sample_code`),
  ADD KEY `blood_samples_status_index` (`status`),
  ADD KEY `blood_samples_patient_id_foreign` (`patient_id`),
  ADD KEY `blood_samples_collected_by_foreign` (`collected_by`),
  ADD KEY `blood_samples_reviewed_by_foreign` (`reviewed_by`);

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
-- Indexes for table `collection_centers`
--
ALTER TABLE `collection_centers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `inventories`
--
ALTER TABLE `inventories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventories_blood_sample_id_foreign` (`blood_sample_id`);

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
-- Indexes for table `laboratories`
--
ALTER TABLE `laboratories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sample_reviews`
--
ALTER TABLE `sample_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sample_reviews_blood_sample_id_foreign` (`blood_sample_id`),
  ADD KEY `sample_reviews_reviewed_by_foreign` (`reviewed_by`);

--
-- Indexes for table `sample_transportations`
--
ALTER TABLE `sample_transportations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sample_transportations_blood_sample_id_foreign` (`blood_sample_id`),
  ADD KEY `sample_transportations_collection_center_id_foreign` (`collection_center_id`),
  ADD KEY `sample_transportations_laboratory_id_foreign` (`laboratory_id`),
  ADD KEY `sample_transportations_transported_by_foreign` (`transported_by`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `test_table`
--
ALTER TABLE `test_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blood_samples`
--
ALTER TABLE `blood_samples`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `collection_centers`
--
ALTER TABLE `collection_centers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventories`
--
ALTER TABLE `inventories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laboratories`
--
ALTER TABLE `laboratories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `sample_reviews`
--
ALTER TABLE `sample_reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sample_transportations`
--
ALTER TABLE `sample_transportations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `test_table`
--
ALTER TABLE `test_table`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blood_samples`
--
ALTER TABLE `blood_samples`
  ADD CONSTRAINT `blood_samples_collected_by_foreign` FOREIGN KEY (`collected_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `blood_samples_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `blood_samples_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `inventories`
--
ALTER TABLE `inventories`
  ADD CONSTRAINT `inventories_blood_sample_id_foreign` FOREIGN KEY (`blood_sample_id`) REFERENCES `blood_samples` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sample_reviews`
--
ALTER TABLE `sample_reviews`
  ADD CONSTRAINT `sample_reviews_blood_sample_id_foreign` FOREIGN KEY (`blood_sample_id`) REFERENCES `blood_samples` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sample_reviews_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `sample_transportations`
--
ALTER TABLE `sample_transportations`
  ADD CONSTRAINT `sample_transportations_blood_sample_id_foreign` FOREIGN KEY (`blood_sample_id`) REFERENCES `blood_samples` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sample_transportations_collection_center_id_foreign` FOREIGN KEY (`collection_center_id`) REFERENCES `collection_centers` (`id`),
  ADD CONSTRAINT `sample_transportations_laboratory_id_foreign` FOREIGN KEY (`laboratory_id`) REFERENCES `laboratories` (`id`),
  ADD CONSTRAINT `sample_transportations_transported_by_foreign` FOREIGN KEY (`transported_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
