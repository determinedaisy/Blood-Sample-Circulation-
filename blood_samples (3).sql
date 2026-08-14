-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 14, 2026 at 01:31 PM
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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blood_samples`
--
ALTER TABLE `blood_samples`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
