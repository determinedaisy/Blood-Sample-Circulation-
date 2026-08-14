-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 14, 2026 at 01:32 PM
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

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sample_transportations`
--
ALTER TABLE `sample_transportations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

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
