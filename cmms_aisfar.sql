-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 08, 2026 at 05:22 PM
-- Server version: 8.4.3
-- PHP Version: 8.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cmms_aisfar`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint UNSIGNED NOT NULL,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint UNSIGNED DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(1, 'default', 'created', 'App\\Models\\User', 'created', 1, NULL, NULL, '{\"attributes\": {\"email\": \"admin@cmms-aisfar.com\", \"status\": \"active\", \"role_id\": null, \"jabatan_id\": null, \"nama_lengkap\": \"Super Administrator\", \"department_id\": null}}', NULL, '2026-08-04 04:58:53', '2026-08-04 04:58:53'),
(2, 'default', 'created', 'App\\Models\\Department', 'created', 1, 'App\\Models\\User', 1, '{\"attributes\": {\"nama_department\": \"Plant\"}}', NULL, '2026-08-04 05:36:30', '2026-08-04 05:36:30'),
(3, 'default', 'created', 'App\\Models\\Department', 'created', 2, 'App\\Models\\User', 1, '{\"attributes\": {\"nama_department\": \"Logistik\"}}', NULL, '2026-08-04 05:36:49', '2026-08-04 05:36:49'),
(4, 'default', 'created', 'App\\Models\\Department', 'created', 3, 'App\\Models\\User', 1, '{\"attributes\": {\"nama_department\": \"Produksi\"}}', NULL, '2026-08-04 05:36:58', '2026-08-04 05:36:58'),
(5, 'default', 'created', 'App\\Models\\Jabatan', 'created', 1, 'App\\Models\\User', 1, '{\"attributes\": {\"nama_jabatan\": \"Mekanik\"}}', NULL, '2026-08-04 05:37:14', '2026-08-04 05:37:14'),
(6, 'default', 'created', 'App\\Models\\Jabatan', 'created', 2, 'App\\Models\\User', 1, '{\"attributes\": {\"nama_jabatan\": \"Foreman\"}}', NULL, '2026-08-04 05:37:24', '2026-08-04 05:37:24'),
(7, 'default', 'updated', 'App\\Models\\User', 'updated', 1, 'App\\Models\\User', 1, '{\"old\": {\"department_id\": null}, \"attributes\": {\"department_id\": 1}}', NULL, '2026-08-04 05:38:43', '2026-08-04 05:38:43'),
(8, 'default', 'created', 'App\\Models\\AppSetting', 'created', 1, 'App\\Models\\User', 1, '{\"attributes\": {\"key\": \"app_name\", \"value\": \"CMMS Aisfar\"}}', NULL, '2026-08-04 05:44:00', '2026-08-04 05:44:00'),
(9, 'default', 'created', 'App\\Models\\AppSetting', 'created', 2, 'App\\Models\\User', 1, '{\"attributes\": {\"key\": \"app_address\", \"value\": null}}', NULL, '2026-08-04 05:44:00', '2026-08-04 05:44:00'),
(10, 'default', 'created', 'App\\Models\\AppSetting', 'created', 3, 'App\\Models\\User', 1, '{\"attributes\": {\"key\": \"app_logo\", \"value\": \"1785851040.png\"}}', NULL, '2026-08-04 05:44:00', '2026-08-04 05:44:00'),
(11, 'default', 'created', 'App\\Models\\User', 'created', 2, NULL, NULL, '{\"attributes\": {\"email\": \"admin02@cmms-aisfar.com\", \"status\": \"pending\", \"role_id\": null, \"jabatan_id\": null, \"nama_lengkap\": \"Admin\", \"department_id\": null}}', NULL, '2026-08-04 07:43:04', '2026-08-04 07:43:04'),
(12, 'default', 'updated', 'App\\Models\\User', 'updated', 2, 'App\\Models\\User', 1, '{\"old\": {\"status\": \"pending\"}, \"attributes\": {\"status\": \"active\"}}', NULL, '2026-08-04 07:44:28', '2026-08-04 07:44:28'),
(13, 'default', 'created', 'App\\Models\\Jabatan', 'created', 3, 'App\\Models\\User', 1, '{\"attributes\": {\"nama_jabatan\": \"Supervisor\"}}', NULL, '2026-08-05 03:04:15', '2026-08-05 03:04:15'),
(14, 'default', 'created', 'App\\Models\\Jabatan', 'created', 4, 'App\\Models\\User', 1, '{\"attributes\": {\"nama_jabatan\": \"Admin\"}}', NULL, '2026-08-05 03:04:36', '2026-08-05 03:04:36'),
(15, 'default', 'created', 'App\\Models\\Jabatan', 'created', 5, 'App\\Models\\User', 1, '{\"attributes\": {\"nama_jabatan\": \"Maintenance Planner\"}}', NULL, '2026-08-05 03:04:52', '2026-08-05 03:04:52'),
(16, 'default', 'created', 'App\\Models\\Jabatan', 'created', 6, 'App\\Models\\User', 1, '{\"attributes\": {\"nama_jabatan\": \"Manager\"}}', NULL, '2026-08-05 03:05:07', '2026-08-05 03:05:07'),
(17, 'default', 'created', 'App\\Models\\Jabatan', 'created', 7, 'App\\Models\\User', 1, '{\"attributes\": {\"nama_jabatan\": \"Helper\"}}', NULL, '2026-08-05 03:05:20', '2026-08-05 03:05:20'),
(18, 'default', 'updated', 'App\\Models\\AppSetting', 'updated', 1, 'App\\Models\\User', 1, '{\"old\": {\"value\": \"CMMS Aisfar\"}, \"attributes\": {\"value\": \"PT MAM\"}}', NULL, '2026-08-05 03:09:31', '2026-08-05 03:09:31'),
(19, 'default', 'created', 'App\\Models\\AppSetting', 'created', 4, 'App\\Models\\User', 1, '{\"attributes\": {\"key\": \"mail_host\", \"value\": null}}', NULL, '2026-08-05 03:09:31', '2026-08-05 03:09:31'),
(20, 'default', 'created', 'App\\Models\\AppSetting', 'created', 5, 'App\\Models\\User', 1, '{\"attributes\": {\"key\": \"mail_port\", \"value\": \"587\"}}', NULL, '2026-08-05 03:09:31', '2026-08-05 03:09:31'),
(21, 'default', 'created', 'App\\Models\\AppSetting', 'created', 6, 'App\\Models\\User', 1, '{\"attributes\": {\"key\": \"mail_username\", \"value\": \"admin@cmms-aisfar.com\"}}', NULL, '2026-08-05 03:09:31', '2026-08-05 03:09:31'),
(22, 'default', 'created', 'App\\Models\\AppSetting', 'created', 7, 'App\\Models\\User', 1, '{\"attributes\": {\"key\": \"mail_password\", \"value\": \"password\"}}', NULL, '2026-08-05 03:09:31', '2026-08-05 03:09:31'),
(23, 'default', 'created', 'App\\Models\\AppSetting', 'created', 8, 'App\\Models\\User', 1, '{\"attributes\": {\"key\": \"mail_encryption\", \"value\": \"tls\"}}', NULL, '2026-08-05 03:09:31', '2026-08-05 03:09:31'),
(24, 'default', 'created', 'App\\Models\\AppSetting', 'created', 9, 'App\\Models\\User', 1, '{\"attributes\": {\"key\": \"mail_from_name\", \"value\": \"CMMS Aisfar\"}}', NULL, '2026-08-05 03:09:31', '2026-08-05 03:09:31'),
(25, 'default', 'created', 'App\\Models\\AppSetting', 'created', 10, 'App\\Models\\User', 1, '{\"attributes\": {\"key\": \"mail_from_address\", \"value\": null}}', NULL, '2026-08-05 03:09:31', '2026-08-05 03:09:31'),
(26, 'default', 'created', 'App\\Models\\User', 'created', 3, NULL, NULL, '{\"attributes\": {\"email\": \"bbe@cmms-aisfar.com\", \"status\": \"pending\", \"role_id\": null, \"jabatan_id\": null, \"nama_lengkap\": \"Admin BBE\", \"department_id\": null}}', NULL, '2026-08-05 06:57:17', '2026-08-05 06:57:17'),
(27, 'default', 'updated', 'App\\Models\\User', 'updated', 3, 'App\\Models\\User', 1, '{\"old\": {\"status\": \"pending\", \"jabatan_id\": null, \"department_id\": null}, \"attributes\": {\"status\": \"active\", \"jabatan_id\": 4, \"department_id\": 1}}', NULL, '2026-08-05 06:58:34', '2026-08-05 06:58:34'),
(28, 'default', 'updated', 'App\\Models\\AppSetting', 'updated', 2, 'App\\Models\\User', 1, '{\"old\": {\"value\": null}, \"attributes\": {\"value\": \"PT Mitra Abadi Mahakam\"}}', NULL, '2026-08-05 21:49:26', '2026-08-05 21:49:26'),
(29, 'default', 'updated', 'App\\Models\\AppSetting', 'updated', 2, 'App\\Models\\User', 1, '{\"old\": {\"value\": \"PT Mitra Abadi Mahakam\"}, \"attributes\": {\"value\": \"Jl. A. Wahab Syahranie Gg. Walet 2 No.818, Sempaja Sel., Kec. Samarinda Utara, Kota Samarinda, Kalimantan Timur 75119\"}}', NULL, '2026-08-06 00:41:31', '2026-08-06 00:41:31'),
(30, 'default', 'updated', 'App\\Models\\AppSetting', 'updated', 1, 'App\\Models\\User', 1, '{\"old\": {\"value\": \"PT MAM\"}, \"attributes\": {\"value\": \"PT Mitra Abadi Mahakam\"}}', NULL, '2026-08-06 00:41:49', '2026-08-06 00:41:49'),
(31, 'default', 'created', 'App\\Models\\User', 'created', 4, NULL, NULL, '{\"attributes\": {\"email\": \"spvbbe@cmms-aisfar.com\", \"status\": \"pending\", \"role_id\": null, \"jabatan_id\": null, \"nama_lengkap\": \"Supervisor BBE\", \"department_id\": null}}', NULL, '2026-08-06 07:44:43', '2026-08-06 07:44:43'),
(32, 'default', 'updated', 'App\\Models\\User', 'updated', 4, 'App\\Models\\User', 1, '{\"old\": {\"status\": \"pending\", \"jabatan_id\": null, \"department_id\": null}, \"attributes\": {\"status\": \"active\", \"jabatan_id\": 3, \"department_id\": 1}}', NULL, '2026-08-06 07:48:21', '2026-08-06 07:48:21'),
(33, 'default', 'created', 'App\\Models\\Far', 'created', 1, 'App\\Models\\User', 1, '{\"attributes\": {\"no_far\": \"FAR-2608-0001\", \"status\": \"Draft\", \"site_id\": 1, \"background\": null, \"conclusion\": null, \"reported_by\": 1, \"date_reported\": \"2026-08-07\", \"last_comp_smu\": null, \"last_oil_eval\": null, \"work_order_id\": 5, \"last_comp_date\": null, \"master_unit_id\": 54, \"smu_at_failure\": null, \"date_of_failure\": \"2026-08-07\", \"failure_outline\": null, \"failure_analysis\": null, \"component_part_no\": null, \"hours_of_component\": null, \"last_oil_date_sent\": null, \"last_oil_date_taken\": null, \"component_description\": null, \"last_oil_date_received\": null, \"part_no_causing_failure\": null}}', NULL, '2026-08-06 22:14:00', '2026-08-06 22:14:00'),
(34, 'default', 'created', 'App\\Models\\Far', 'created', 2, 'App\\Models\\User', 1, '{\"attributes\": {\"no_far\": \"FAR-2608-0002\", \"status\": \"Draft\", \"site_id\": 1, \"background\": null, \"conclusion\": null, \"reported_by\": 1, \"date_reported\": \"2026-08-07\", \"last_comp_smu\": null, \"last_oil_eval\": null, \"work_order_id\": 5, \"last_comp_date\": null, \"master_unit_id\": 54, \"smu_at_failure\": null, \"date_of_failure\": \"2026-08-07\", \"failure_outline\": null, \"failure_analysis\": null, \"component_part_no\": null, \"hours_of_component\": null, \"last_oil_date_sent\": null, \"last_oil_date_taken\": null, \"component_description\": null, \"last_oil_date_received\": null, \"part_no_causing_failure\": null}}', NULL, '2026-08-06 22:34:38', '2026-08-06 22:34:38'),
(35, 'default', 'updated', 'App\\Models\\Far', 'updated', 2, 'App\\Models\\User', 1, '{\"old\": {\"component_part_no\": null, \"component_description\": null}, \"attributes\": {\"component_part_no\": \"15W-40\", \"component_description\": \"Oil Engine\"}}', NULL, '2026-08-06 22:35:15', '2026-08-06 22:35:15'),
(36, 'work_order', 'Work Order WO-08-26-0005 has been created', 'App\\Models\\WorkOrder', 'created', 7, 'App\\Models\\User', 1, '{\"attributes\": {\"no_wo\": \"WO-08-26-0005\", \"site_id\": null, \"tipe_wo\": \"BD\", \"waktu_bd\": null, \"status_wo\": \"Open\", \"waktu_rfu\": null, \"created_by\": 1, \"hours_meter\": null, \"opportunity\": false, \"downtime_code\": \"Unschedule\", \"master_unit_id\": 83, \"pm_schedule_id\": null, \"lokasi_kerusakan\": null, \"wo_category_1_id\": 2, \"wo_category_2_id\": null, \"wo_category_3_id\": null, \"wo_category_4_id\": null, \"wo_category_5_id\": null, \"breakdown_type_id\": null, \"component_group_id\": null}}', NULL, '2026-08-08 04:45:30', '2026-08-08 04:45:30');

-- --------------------------------------------------------

--
-- Table structure for table `approval_matrices`
--

CREATE TABLE `approval_matrices` (
  `id` bigint UNSIGNED NOT NULL,
  `module_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sequence` int NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_settings`
--

CREATE TABLE `app_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `app_settings`
--

INSERT INTO `app_settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'app_name', 'PT Mitra Abadi Mahakam', '2026-08-04 05:44:00', '2026-08-06 00:41:49'),
(2, 'app_address', 'Jl. A. Wahab Syahranie Gg. Walet 2 No.818, Sempaja Sel., Kec. Samarinda Utara, Kota Samarinda, Kalimantan Timur 75119', '2026-08-04 05:44:00', '2026-08-06 00:41:31'),
(3, 'app_logo', '1785851040.png', '2026-08-04 05:44:00', '2026-08-04 05:44:00'),
(4, 'mail_host', NULL, '2026-08-05 03:09:31', '2026-08-05 03:09:31'),
(5, 'mail_port', '587', '2026-08-05 03:09:31', '2026-08-05 03:09:31'),
(6, 'mail_username', 'admin@cmms-aisfar.com', '2026-08-05 03:09:31', '2026-08-05 03:09:31'),
(7, 'mail_password', 'password', '2026-08-05 03:09:31', '2026-08-05 03:09:31'),
(8, 'mail_encryption', 'tls', '2026-08-05 03:09:31', '2026-08-05 03:09:31'),
(9, 'mail_from_name', 'CMMS Aisfar', '2026-08-05 03:09:31', '2026-08-05 03:09:31'),
(10, 'mail_from_address', NULL, '2026-08-05 03:09:31', '2026-08-05 03:09:31');

-- --------------------------------------------------------

--
-- Table structure for table `breakdown_types`
--

CREATE TABLE `breakdown_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `breakdown_types`
--

INSERT INTO `breakdown_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Contoh Tipe Breakdown', '2026-08-04 21:09:23', '2026-08-04 21:09:23');

-- --------------------------------------------------------

--
-- Table structure for table `component_groups`
--

CREATE TABLE `component_groups` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `component_groups`
--

INSERT INTO `component_groups` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Engine', '2026-08-04 21:42:47', '2026-08-04 21:42:47'),
(2, 'Undercarriage', '2026-08-06 02:41:04', '2026-08-06 02:41:04');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_department` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `nama_department`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Plant', '2026-08-04 05:36:30', '2026-08-04 05:36:30', NULL),
(2, 'Logistik', '2026-08-04 05:36:49', '2026-08-04 05:36:49', NULL),
(3, 'Produksi', '2026-08-04 05:36:58', '2026-08-04 05:36:58', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `document_signatures`
--

CREATE TABLE `document_signatures` (
  `id` bigint UNSIGNED NOT NULL,
  `document_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_id` bigint UNSIGNED NOT NULL,
  `sign_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `role_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `document_signatures`
--

INSERT INTO `document_signatures` (`id`, `document_type`, `document_id`, `sign_type`, `user_id`, `role_name`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\WorkOrder', 6, 'ditinjau', 4, 'Supervisor', '2026-08-06 08:13:26', '2026-08-06 08:13:26');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fars`
--

CREATE TABLE `fars` (
  `id` bigint UNSIGNED NOT NULL,
  `no_far` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `site_id` bigint UNSIGNED NOT NULL,
  `work_order_id` bigint UNSIGNED DEFAULT NULL,
  `master_unit_id` bigint UNSIGNED DEFAULT NULL,
  `reported_by` bigint UNSIGNED DEFAULT NULL,
  `date_reported` date DEFAULT NULL,
  `date_of_failure` date DEFAULT NULL,
  `smu_at_failure` int DEFAULT NULL,
  `component_part_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `component_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `part_no_causing_failure` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_comp_date` date DEFAULT NULL,
  `last_comp_smu` int DEFAULT NULL,
  `hours_of_component` int DEFAULT NULL,
  `last_oil_date_taken` date DEFAULT NULL,
  `last_oil_date_sent` date DEFAULT NULL,
  `last_oil_date_received` date DEFAULT NULL,
  `last_oil_eval` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `failure_outline` text COLLATE utf8mb4_unicode_ci,
  `background` text COLLATE utf8mb4_unicode_ci,
  `failure_analysis` text COLLATE utf8mb4_unicode_ci,
  `conclusion` text COLLATE utf8mb4_unicode_ci,
  `status` enum('Draft','Submitted','Approved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fars`
--

INSERT INTO `fars` (`id`, `no_far`, `site_id`, `work_order_id`, `master_unit_id`, `reported_by`, `date_reported`, `date_of_failure`, `smu_at_failure`, `component_part_no`, `component_description`, `part_no_causing_failure`, `last_comp_date`, `last_comp_smu`, `hours_of_component`, `last_oil_date_taken`, `last_oil_date_sent`, `last_oil_date_received`, `last_oil_eval`, `failure_outline`, `background`, `failure_analysis`, `conclusion`, `status`, `created_at`, `updated_at`) VALUES
(1, 'FAR-2608-0001', 1, 5, 54, 1, '2026-08-07', '2026-08-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Draft', '2026-08-06 22:14:00', '2026-08-06 22:14:00'),
(2, 'FAR-2608-0002', 1, 5, 54, 1, '2026-08-07', '2026-08-07', NULL, '15W-40', 'Oil Engine', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Draft', '2026-08-06 22:34:38', '2026-08-06 22:35:15');

-- --------------------------------------------------------

--
-- Table structure for table `far_attachments`
--

CREATE TABLE `far_attachments` (
  `id` bigint UNSIGNED NOT NULL,
  `far_id` bigint UNSIGNED NOT NULL,
  `component` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observation` text COLLATE utf8mb4_unicode_ci,
  `photo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `far_attachments`
--

INSERT INTO `far_attachments` (`id`, `far_id`, `component`, `observation`, `photo_path`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, NULL, 'far_attachments/EY3z0JCeDcqqTEycDQmonHdmLmnpy9VjNgOarQMY.jpg', '2026-08-06 22:34:38', '2026-08-06 22:34:38');

-- --------------------------------------------------------

--
-- Table structure for table `hour_meters`
--

CREATE TABLE `hour_meters` (
  `id` bigint UNSIGNED NOT NULL,
  `site_id` bigint UNSIGNED DEFAULT NULL,
  `master_unit_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `hm` decimal(10,1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hse_jsas`
--

CREATE TABLE `hse_jsas` (
  `id` bigint UNSIGNED NOT NULL,
  `work_order_id` bigint UNSIGNED NOT NULL,
  `site_id` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `document_scan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hse_jsas`
--

INSERT INTO `hse_jsas` (`id`, `work_order_id`, `site_id`, `created_by`, `approved_by`, `status`, `document_scan`, `notes`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 6, NULL, 1, 1, 'Approved', NULL, NULL, '2026-08-06 22:43:58', '2026-08-06 22:43:58', '2026-08-06 22:43:58');

-- --------------------------------------------------------

--
-- Table structure for table `hse_jsa_steps`
--

CREATE TABLE `hse_jsa_steps` (
  `id` bigint UNSIGNED NOT NULL,
  `hse_jsa_id` bigint UNSIGNED NOT NULL,
  `job_step` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `potential_hazard` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `control_measure` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hse_jsa_steps`
--

INSERT INTO `hse_jsa_steps` (`id`, `hse_jsa_id`, `job_step`, `potential_hazard`, `control_measure`, `created_at`, `updated_at`) VALUES
(1, 1, 'Jika anu', 'Mati', 'Diam', '2026-08-06 22:43:58', '2026-08-06 22:43:58');

-- --------------------------------------------------------

--
-- Table structure for table `hse_lotos`
--

CREATE TABLE `hse_lotos` (
  `id` bigint UNSIGNED NOT NULL,
  `work_order_id` bigint UNSIGNED NOT NULL,
  `site_id` bigint UNSIGNED DEFAULT NULL,
  `isolation_point` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lock_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tag_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `applied_by` bigint UNSIGNED NOT NULL,
  `applied_at` timestamp NULL DEFAULT NULL,
  `removed_by` bigint UNSIGNED DEFAULT NULL,
  `removed_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hse_lotos`
--

INSERT INTO `hse_lotos` (`id`, `work_order_id`, `site_id`, `isolation_point`, `lock_number`, `tag_number`, `applied_by`, `applied_at`, `removed_by`, `removed_at`, `status`, `created_at`, `updated_at`) VALUES
(1, 6, NULL, 'Batterai', NULL, NULL, 1, '2026-08-06 22:44:44', NULL, NULL, 'Active', '2026-08-06 22:44:44', '2026-08-06 22:44:44');

-- --------------------------------------------------------

--
-- Table structure for table `hse_ptws`
--

CREATE TABLE `hse_ptws` (
  `id` bigint UNSIGNED NOT NULL,
  `work_order_id` bigint UNSIGNED NOT NULL,
  `site_id` bigint UNSIGNED DEFAULT NULL,
  `permit_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valid_from` timestamp NULL DEFAULT NULL,
  `valid_to` timestamp NULL DEFAULT NULL,
  `applicant_id` bigint UNSIGNED NOT NULL,
  `approver_id` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `document_scan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hse_ptws`
--

INSERT INTO `hse_ptws` (`id`, `work_order_id`, `site_id`, `permit_type`, `valid_from`, `valid_to`, `applicant_id`, `approver_id`, `status`, `document_scan`, `notes`, `created_at`, `updated_at`) VALUES
(1, 6, NULL, 'Hot Work', '2026-08-07 06:44:00', '2026-08-08 06:44:00', 1, 1, 'Approved', NULL, 'Panas', '2026-08-06 22:44:27', '2026-08-06 22:44:27');

-- --------------------------------------------------------

--
-- Table structure for table `incident_reports`
--

CREATE TABLE `incident_reports` (
  `id` bigint UNSIGNED NOT NULL,
  `tool_transaction_id` bigint UNSIGNED NOT NULL,
  `mechanic_id` bigint UNSIGNED NOT NULL,
  `kronologi` text COLLATE utf8mb4_unicode_ci,
  `status_approval` enum('Pending','Approved','Rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jabatans`
--

CREATE TABLE `jabatans` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_jabatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jabatans`
--

INSERT INTO `jabatans` (`id`, `nama_jabatan`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Mekanik', '2026-08-04 05:37:14', '2026-08-04 05:37:14', NULL),
(2, 'Foreman', '2026-08-04 05:37:24', '2026-08-04 05:37:24', NULL),
(3, 'Supervisor', '2026-08-05 03:04:15', '2026-08-05 03:04:15', NULL),
(4, 'Admin', '2026-08-05 03:04:36', '2026-08-05 03:04:36', NULL),
(5, 'Maintenance Planner', '2026-08-05 03:04:52', '2026-08-05 03:04:52', NULL),
(6, 'Manager', '2026-08-05 03:05:06', '2026-08-05 03:05:06', NULL),
(7, 'Helper', '2026-08-05 03:05:20', '2026-08-05 03:05:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jwos`
--

CREATE TABLE `jwos` (
  `id` bigint UNSIGNED NOT NULL,
  `no_jwo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `work_order_id` bigint UNSIGNED DEFAULT NULL,
  `unit_id` bigint UNSIGNED DEFAULT NULL,
  `component_group_id` bigint UNSIGNED DEFAULT NULL,
  `part_id` bigint UNSIGNED DEFAULT NULL,
  `problem_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_action` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Progress Site',
  `date_sent` date DEFAULT NULL,
  `date_expected` date DEFAULT NULL,
  `date_returned` date DEFAULT NULL,
  `cost` decimal(15,2) DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `photo_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_id` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jwos`
--

INSERT INTO `jwos` (`id`, `no_jwo`, `vendor_id`, `work_order_id`, `unit_id`, `component_group_id`, `part_id`, `problem_description`, `request_action`, `status`, `date_sent`, `date_expected`, `date_returned`, `cost`, `remarks`, `photo_1`, `photo_2`, `site_id`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'JWO-08-26-0001', 1, 5, 6, 1, 3, 'Rusak', 'Diperbaiki', 'Progress Site', '2026-08-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-08-06 08:26:10', '2026-08-06 08:34:10'),
(2, 'JWO-08-26-0002', 1, 2, 82, 2, 3, 'Rusak', 'Di perbaiki dong', 'Progress Site', NULL, NULL, NULL, NULL, NULL, 'ZRB694EwOTAYeWSSm8dqlDY9XM1fffIkjmdxsOBZ.jpg', 'Q76zzMflP4ceIIfbfIs6qwjQE0lMvcPDOQbqJ5F7.jpg', NULL, 1, '2026-08-06 09:35:49', '2026-08-06 10:18:31');

-- --------------------------------------------------------

--
-- Table structure for table `master_units`
--

CREATE TABLE `master_units` (
  `id` bigint UNSIGNED NOT NULL,
  `nomor_unit` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_type_id` bigint UNSIGNED NOT NULL,
  `unit_model_id` bigint UNSIGNED DEFAULT NULL,
  `sn_chassis` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `engine_model` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sn_engine` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `engine_make` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacity` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_polisi` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachments` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kw` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `perakitan` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_receive` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dari` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `site` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `site_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `master_units`
--

INSERT INTO `master_units` (`id`, `nomor_unit`, `unit_type_id`, `unit_model_id`, `sn_chassis`, `engine_model`, `sn_engine`, `engine_make`, `capacity`, `no_polisi`, `attachments`, `hp`, `kw`, `perakitan`, `date_receive`, `dari`, `location`, `remarks`, `service`, `active`, `site`, `created_at`, `updated_at`, `site_id`) VALUES
(2, 'A-07', 3, 2, 'MMBJJKL10KH059167', 'Triton 2.4L DC GLS 4x4 MT', NULL, 'MITSUBISHI', NULL, 'KT 8276 NP', 'DOUBLE CABIN', NULL, NULL, '2021', NULL, 'SAMARINDA', 'HW', 'BRCM', 0, 1, 'HW', '2026-08-05 10:54:55', '2026-08-05 10:54:55', 2),
(3, 'A-08', 3, 3, 'MMBJNKL30MH038632', 'Triton 2.5L DC HDX-L 4x4 MT', NULL, 'MITSUBISHI', NULL, 'KT 8355 NR', 'DOUBLE CABIN', NULL, NULL, '2021', NULL, 'SAMARINDA', 'HW', 'BRCM', 0, 1, 'HW', '2026-08-05 10:54:55', '2026-08-05 10:54:55', 2),
(4, 'B-02', 3, 2, 'MMBJJKL10PH071906', 'Triton 2.4L DC GLS 4x4 MT', NULL, 'MITSUBISHI', NULL, 'KT 8235 NY', 'DOUBLE CABIN', NULL, NULL, '2023', NULL, 'SAMARINDA', 'HW', 'BRCM', 0, 1, 'HW', '2026-08-05 10:54:55', '2026-08-05 10:54:55', 2),
(5, 'B-10', 3, 2, 'MMBJNKL30NH088352', 'Triton 2.5L DC HDX-L 4x4 MT', NULL, 'MITSUBISHI', NULL, 'KT 8594 NY', 'DOUBLE CABIN', NULL, NULL, '2024', NULL, 'SAMARINDA', 'HW', 'BRCM', 0, 1, 'HW', '2026-08-05 10:54:55', '2026-08-05 10:54:55', 2),
(6, 'B-16', 3, 2, 'MMBJNKL30NH077761', 'Triton 2.5L DC HDX-L 4x4 MT', NULL, 'MITSUBISHI', NULL, 'KT 8438 NT', 'DOUBLE CABIN', NULL, NULL, '2022', NULL, 'SAMARINDA', 'HW', 'BRCM', 0, 1, 'HW', '2026-08-05 10:54:55', '2026-08-05 10:54:55', 2),
(7, 'D-09', 3, 2, 'MMBJNKL30NH077883', 'Triton 2.5L DC HDX-L 4x4 MT', NULL, 'MITSUBISHI', NULL, 'KT 8028 NV', 'DOUBLE CABIN', NULL, NULL, '2021', NULL, 'SAMARINDA', 'HW', 'BRCM', 0, 1, 'HW', '2026-08-05 10:54:55', '2026-08-05 10:54:55', 2),
(8, 'D-19', 3, 2, 'MMBJNKL30MH029279', 'Triton 2.5L DC HDX-L 4x4 MT', '4D56UBD5208', 'MITSUBISHI', NULL, 'KT 8545 NQ', 'DOUBLE CABIN', NULL, NULL, '2021', NULL, 'SAMARINDA', 'HW', 'BBE', 0, 1, 'HW', '2026-08-05 10:54:55', '2026-08-05 10:54:55', 2),
(9, 'D-21', 3, 2, 'MMBJNKL30NH086156', 'Triton 2.5L DC HDX-L 4x4 MT', NULL, 'MITSUBISHI', NULL, 'KT 8627 NV', 'DOUBLE CABIN', NULL, NULL, '2021', NULL, 'SAMARINDA', 'HW', 'BRCM', 0, 1, 'HW', '2026-08-05 10:54:55', '2026-08-05 10:54:55', 2),
(10, 'E-02', 3, 2, 'MMBJNKL30MH030047', 'Triton 2.5L DC HDX-L 4x4 MT', '4D56UBD5401', 'MITSUBISHI', NULL, 'KT 8548 NQ', 'DOUBLE CABIN', NULL, NULL, '2021', NULL, 'SAMARINDA', 'HW', 'BBE', 0, 1, 'HW', '2026-08-05 10:54:55', '2026-08-05 10:54:55', 2),
(11, 'F-05', 3, 2, 'MMBJNKL30NH088362', 'Triton 2.5L DC HDX-H 4x4 MT', NULL, 'MITSUBISHI', NULL, 'KT 8596 NY', 'DOUBLE CABIN', NULL, NULL, '2023', NULL, 'SAMARINDA', 'HW', 'BRCM', 0, 1, 'HW', '2026-08-05 10:54:55', '2026-08-05 10:54:55', 2),
(12, 'G-03', 3, 4, 'MHKV5EA1JGJ006725', 'Xenia 1.3 x MT ( F653RV-GMRFJ)', '1NRF139257', 'DAIHATSU', NULL, 'KT 1954 NS', 'MINIBUS', NULL, NULL, '2016', NULL, 'SAMARINDA', 'HW', 'BRCM', 0, 1, 'HW', '2026-08-05 10:54:55', '2026-08-05 10:54:55', 2),
(13, 'G-05', 3, 2, 'MMBJNKL30JH066140', 'Triton 2.5L DC HDX-L 4x4 MT', '4D56UAV1701', 'MITSUBISHI', NULL, 'KT B 9671 SBC', 'DOUBLE CABIN', NULL, NULL, '2018', NULL, 'SAMARINDA', 'HW', 'HRGA', 0, 1, 'HW', '2026-08-05 10:54:55', '2026-08-05 10:54:55', 2),
(14, 'H-02', 3, 2, 'MMBJNKL30GH048676', 'Triton 2.5 DC GLS 4x4 MT', '4D56UAE6146', 'MITSUBISHI', NULL, 'KT 8762 NG', 'DOUBLE CABIN', NULL, NULL, '2016', NULL, 'SAMARINDA', 'HW', 'BRCM', 0, 1, 'HW', '2026-08-05 10:54:55', '2026-08-05 10:54:55', 2),
(15, 'MB001', 4, 5, 'MJEC1JG43D5091844', NULL, NULL, 'Hino', '29', NULL, 'Bus', NULL, NULL, '2019', NULL, NULL, 'HW', 'BBE', 0, 1, 'HW', '2026-08-05 10:54:55', '2026-08-05 10:54:55', 2),
(16, 'MB002', 4, 6, 'MJEFB2WGLKJE1529', NULL, NULL, 'Hino', '29', NULL, 'Bus', NULL, NULL, '2022', NULL, NULL, 'HW', 'BBE', 0, 1, 'HW', '2026-08-05 10:54:55', '2026-08-05 10:54:55', 2),
(17, 'MCM007', 5, 7, 'TF155A', NULL, NULL, NULL, NULL, NULL, 'AIR COMPRESSOR', NULL, NULL, '2022', NULL, NULL, 'HW', 'BRCM', 0, 1, 'HW', '2026-08-05 10:54:55', '2026-08-05 10:54:55', 2),
(18, 'MCP001', 6, 8, 'CAT0CS11LRK800901', 'C4.4', 'MFX10985', 'CATERPILLAR', NULL, NULL, 'DRUM VIBRO', NULL, '78,5', '2021', '2021-08-05 00:00:00', 'TU SAMARINDA', 'Workshop', 'B/down major', 0, 0, 'BBE', '2026-08-05 10:54:55', '2026-08-05 10:54:55', 1),
(19, 'MCP002', 6, 8, 'CAT0CS11ARK800900', 'C4.4', 'MFX10987', 'CATERPILLAR', NULL, NULL, 'DRUM VIBRO', NULL, '78,5', '2021', NULL, 'TU SAMARINDA', 'Workshop', 'OPERASI', 0, 1, 'BBE', '2026-08-05 10:54:55', '2026-08-05 07:14:57', 1),
(20, 'MCP003', 6, 9, 'YZ1522CC1260', 'WP6G200E331', '6P22A000034', 'SANY', NULL, NULL, 'VIBRATORY', NULL, '147', '2022', '2022-11-01 00:00:00', 'Sany Makmur Perkasa', 'PIT A9', 'OPERASI', 0, 1, 'HW', '2026-08-05 10:54:55', '2026-08-05 10:54:55', 2),
(21, 'MCP006', 6, 9, 'YZ1522CC3259', 'WP6G200E331', '6P22H044488', 'SANY', NULL, NULL, 'VIBRATORY', NULL, '147', '1905-07-15 00:00:00', '2023-05-10 00:00:00', 'Sany Makmur Perkasa', '45056', NULL, 0, 1, 'HW', '2026-08-05 10:54:55', '2026-08-05 10:54:55', 2),
(22, 'MCP007', 6, 10, 'YZ1826CD2986', 'D07S3-245E0', 'DL07017229', 'SANY', NULL, NULL, 'VIBRATORY', '180.0', '230', '2023', '2024-10-25 00:00:00', 'Sany Makmur Perkasa', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(23, 'MCT001', 7, 11, 'MJEFM8JNKEJM42859', 'KD-MGJ', 'JCSEUFJ64077', 'HINO', '10 TON', NULL, 'CRANE', NULL, NULL, '2017', NULL, 'SAMARINDA', 'HW', 'BBE L2', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(24, 'MD026', 8, 12, 'KMT0D035VMXJ19596', 'SGD125E-2', '122680', 'KOMATSU', '6,8 M³', NULL, 'BLADE', '149.0', '200', '2022', NULL, 'SAMARINDA', 'WORKSHOP', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(25, 'MD027', 8, 12, 'KMT0D035KMXJ19724', 'SGD125E-2', '122969', 'KOMATSU', '6,8 M³', NULL, 'BLADE', '149.0', '200', '2022', NULL, '-', 'WORKSHOP', 'B/down major', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(26, 'MD028', 8, 12, 'KMT0D035VMXJ19824', 'SGD125E-2', '122995', 'KOMATSU', '6,8 M³', NULL, 'BLADE', '149.0', '200', '2022', NULL, 'SAMARINDA', 'WORKSHOP', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(27, 'MD029', 9, 13, '65996', 'D375A-6R/S1', '65996', 'KOMATSU', '18,5 M³', NULL, 'BLADE & RIPPER', '474.0', '636', '2022', '2022-10-31 00:00:00', 'UT JAKARTA', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(28, 'MD030', 8, 12, 'KMT0D035AMXJ20071', 'SGD125E-2', '123175', 'KOMATSU', '6,8 M³', NULL, 'BLADE', '149.0', '200', '2022', '2022-12-27 00:00:00', 'UT JAKARTA', 'WORKSHOP', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(29, 'MD031', 8, 12, 'KMT0D035VMXJ20120', 'SGD125E-2', '123605', 'KOMATSU', '6,8 M³', NULL, 'BLADE', '149.0', '200', '2022', '2022-12-27 00:00:00', 'UT JAKARTA', 'WORKSHOP', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(30, 'MD032', 8, 12, 'KMT0D035VMXJ20148', 'SGD125E-2', '123654', 'KOMATSU', '6,8 M³', NULL, 'BLADE', '149.0', '200', '2022', '2023-04-01 00:00:00', 'UT JAKARTA', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(31, 'MD033', 8, 12, 'KMT0D035VMXJ20436', 'SGD125E-2', '123739', 'KOMATSU', '6,8 M³', NULL, 'BLADE', '149.0', '200', '2022', '2023-04-14 00:00:00', 'UT JAKARTA', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(32, 'MD035', 9, 13, 'KMT0D115LPC066079', 'D375A-6R/S1', '616154', 'KOMATSU', '18,5 M³', NULL, 'BLADE & RIPPER', '474.0', '636', '2023', '2023-06-01 00:00:00', 'UT JAKARTA', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(33, 'MD036', 8, 12, 'KMT0D035TMXJ20501', 'SGD125E-2', '615418', 'KOMATSU', '6,8 M³', NULL, 'BLADE + CABIN', '149.0', '200', '1905-07-15 00:00:00', '2023-06-01 00:00:00', 'UT JAKARTA', '45078', 'UT JAKARTA', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(34, 'MD037', 8, 12, 'J20571', 'SGD125E-2', '124020', 'KOMATSU', '6,8 M³', NULL, 'BLADE + CABIN', '149.0', '200', '1905-07-15 00:00:00', '2023-07-12 00:00:00', 'UT JAKARTA', '45119', 'UT JAKARTA', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(35, 'MD038', 8, 12, 'J20676', 'SGD125E-2', '124360', 'KOMATSU', '6,8 M³', NULL, 'BLADE', '149.0', '200', '2023', '2023-09-12 00:00:00', 'UT JAKARTA', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(36, 'MD039', 8, 12, 'J20787', 'SGD125E-2', '124705', 'KOMATSU', '6,8 M³', NULL, 'BLADE', '149.0', '200', '2023', '2023-09-28 00:00:00', 'UT JAKARTA', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(37, 'MD040', 8, 12, 'J20788', 'SGD125E-2', '124710', 'KOMATSU', '6,8 M³', NULL, 'BLADE', '149.0', '200', '2023', '2023-09-28 00:00:00', 'UT JAKARTA', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(38, 'MD041', 8, 12, 'J20947', 'SGD125E-2', '124796', 'KOMATSU', '6,8 M³', NULL, 'BLADE + CABIN', '149.0', '200', '1905-07-16 00:00:00', '2024-08-28 00:00:00', 'UT JAKARTA', '45532', 'UT JAKARTA', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(39, 'MD042', 8, 12, 'J20951', 'SGD125E-2', '124852', 'KOMATSU', '6,8 M³', NULL, 'BLADE + CABIN', '149.0', '200', '1905-07-16 00:00:00', '2024-08-28 00:00:00', 'UT JAKARTA', '45532', 'UT JAKARTA', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(40, 'MD043', 8, 12, 'J21149', 'SGD125E-2', '125737', 'KOMATSU', '6,8 M³', NULL, 'BLADE + CABIN', '215.0', '1950', '1905-07-16 00:00:00', '2024-09-21 00:00:00', 'UT JAKARTA', '45556', 'UT JAKARTA', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(41, 'MD045', 8, 12, 'J21117', 'SGD125E-2', '125516', 'KOMATSU', '6,8 M³', NULL, 'BLADE + CABIN', '215.0', '1950', '1905-07-16 00:00:00', '2024-09-21 00:00:00', 'UT JAKARTA', '45556', 'UT JAKARTA', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(42, 'MD046', 8, 12, 'J20040', 'SGD125E-2', '123363', 'KOMATSU', '6,8 M³', NULL, 'BLADE + CABIN', NULL, NULL, NULL, '2024-09-24 00:00:00', 'PT. TSR', '45559', 'PT. TSR', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(43, 'MD047', 8, 12, 'J21094', 'SGD125E-2', NULL, 'KOMATSU', '6,8 M³', NULL, 'BLADE + CABIN', NULL, NULL, NULL, '2024-09-24 00:00:00', 'PT BKK', 'HW', 'PT BKK', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(44, 'MD048', 8, 12, 'J21106', 'SGD125E-2', NULL, 'KOMATSU', '6,8 M³', NULL, 'BLADE + CABIN', NULL, NULL, NULL, '2024-09-24 00:00:00', 'PT BKK', 'HW', 'PT BKK', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(45, 'MDR001', 10, 14, 'CNN15SED0059', 'C9.3B', 'REH05736', 'CATERPILLAR', NULL, NULL, 'DRILL', '380.0', '228', '2020', '2020-06-05 00:00:00', 'SAMARINDA', 'Workshop', 'B/down major', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(46, 'MDR002', 10, 14, 'CNN21SED0160', 'C9.3B', 'NGL00573', 'CATERPILLAR', NULL, NULL, 'DRILL', '380.0', '228', '2021', '2021-11-03 00:00:00', 'SAMARINDA', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(47, 'MDR003', 10, 14, 'CNN22SED0200/8992402869', 'C9.3B', 'NRE6V09.3NZA', 'CATERPILLAR', NULL, NULL, 'DRILL', '380.0', '228', '2022', '2022-12-20 00:00:00', 'SAMARINDA', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(48, 'MDR005', 10, 15, '1730500011', 'C9.3B', 'NGLO01422', 'CATERPILLAR', NULL, NULL, 'DRILL', '380.0', '228', '2023', '2023-06-01 00:00:00', 'SAMARINDA', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(49, 'MDR006', 10, 15, '1730500007', 'C9.3B', 'NGLO00972', 'CATERPILLAR', NULL, NULL, 'DRILL', '380.0', '228', '2023', '2023-09-07 00:00:00', 'SAMARINDA', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(50, 'MDT006', 11, 16, 'MEC 2432BJMP110994', 'KD-MGJ', '400951D0114310', 'Mercedes Benz', '8,1 TON', NULL, 'VESSEL DUMP', '280.0', NULL, '1905-07-13 00:00:00', '2021-04-02 00:00:00', 'SAMARINDA', '44288', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(51, 'MDT009', 11, 16, 'MEC 2432BJMP111193', 'KD-MGJ', '400951D0114720', 'Mercedes Benz', '8,1 TON', NULL, 'VESSEL DUMP', '280.0', NULL, '1905-07-13 00:00:00', '2021-04-02 00:00:00', 'SAMARINDA', '44288', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(52, 'MDT012', 11, 16, 'MEC 2432BANP114224', 'KD-MGJ', '400951D0119162', 'Mercedes Benz', '8,1 TON', NULL, 'VESSEL DUMP', '280.0', NULL, '1905-07-13 00:00:00', '2022-05-18 00:00:00', 'Tanjung Priok', '44699', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(53, 'MDT015', 11, 16, 'MEC 2432BANP114268', 'KD-MGJ', '400951D0119131', 'Mercedes Benz', '8,1 TON', NULL, 'VESSEL DUMP', '280.0', NULL, '1905-07-13 00:00:00', '2022-05-18 00:00:00', 'Tanjung Priok', '44699', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(54, 'MDT016', 11, 16, 'MEC 2423BANP115011', 'KD-MGJ', '400951D0119517', 'Mercedes Benz', '8,1 TON', NULL, 'VESSEL DUMP', '280.0', NULL, '1905-07-13 00:00:00', '2022-04-29 00:00:00', 'Tanjung Priok', '44680', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(55, 'MDT017', 11, 16, 'MEC 2432BANP115125', 'KD-MGJ', '400951D0119596', 'Mercedes Benz', '8,1 TON', NULL, 'VESSEL DUMP', '280.0', NULL, '1905-07-13 00:00:00', '2022-04-29 00:00:00', 'Tanjung Priok', '44680', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(56, 'MDT019', 11, 16, 'MEC 2432BANP115180', 'KD-MGJ', '400951D0119629', 'Mercedes Benz', '8,1 TON', NULL, 'VESSEL DUMP', '280.0', NULL, '1905-07-13 00:00:00', '2022-04-29 00:00:00', 'Tanjung Priok', '44680', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(57, 'MDT020', 11, 16, 'MEC 2432BANP115198', 'KD-MGJ', '400951D0119631', 'Mercedes Benz', '8,1 TON', NULL, 'VESSEL DUMP', '280.0', NULL, '1905-07-13 00:00:00', '2022-04-29 00:00:00', 'Tanjung Priok', '44680', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(58, 'MDT021', 11, 16, 'MEC 2432BANP115220', 'KD-MGJ', '400951D0119643', 'Mercedes Benz', '8,1 TON', NULL, 'VESSEL DUMP', '280.0', NULL, '1905-07-13 00:00:00', '2022-04-29 00:00:00', 'Tanjung Priok', '44680', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(59, 'MDT022', 11, 16, 'MEC 2432BANP115226', 'KD-MGJ', '400951D0119589', 'Mercedes Benz', '8,1 TON', NULL, 'VESSEL DUMP', '280.0', NULL, '1905-07-13 00:00:00', '2022-08-01 00:00:00', 'Tanjung Priok', '44774', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(60, 'MDT023', 11, 16, 'MEC2432BANP115044', 'KD-MGJ', '400951D0119532', 'Mercedes Benz', '8,1 TON', NULL, 'VESSEL DUMP', '280.0', NULL, '1905-07-13 00:00:00', '2022-08-01 00:00:00', 'Tanjung Priok', '44774', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(61, 'MDT025', 11, 16, 'MEC2432BANP116405', 'KD-MGJ', '400951D0120273', 'Mercedes Benz', '8,1 TON', NULL, 'VESSEL DUMP', '280.0', NULL, '1905-07-13 00:00:00', '2022-08-01 00:00:00', 'Tanjung Priok', '44774', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(62, 'MDT026', 11, 16, 'MEC2432BANP116429', 'KD-MGJ', '400951D0120271', 'Mercedes Benz', '8,1 TON', NULL, 'VESSEL DUMP', '280.0', NULL, '1905-07-13 00:00:00', '2022-08-01 00:00:00', 'Tanjung Priok', '44774', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(63, 'MDT027', 11, 16, 'MEC2432BANP116870', 'KD-MGJ', '400951D0120652', 'Mercedes Benz', '8,1 TON', NULL, 'VESSEL DUMP', '280.0', NULL, '1905-07-13 00:00:00', '2022-08-01 00:00:00', 'Tanjung Priok', '44774', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(64, 'MDT028', 11, 16, 'MEC2432BANP116897', 'KD-MGJ', '400951D0120521', 'Mercedes Benz', '8,1 TON', NULL, 'VESSEL DUMP', '280.0', NULL, '1905-07-13 00:00:00', '2022-08-01 00:00:00', 'Tanjung Priok', '44774', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(65, 'MDT029', 11, 16, 'MEC2432BANP116914', 'KD-MGJ', '400951D0120531', 'Mercedes Benz', '8,1 TON', NULL, 'VESSEL DUMP', '280.0', NULL, '1905-07-13 00:00:00', '2022-08-01 00:00:00', 'Tanjung Priok', '44774', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(66, 'MDT030', 11, 16, 'MEC2432BANP117073', 'KD-MGJ', '400951D0120711', 'Mercedes Benz', '8,1 TON', NULL, 'VESSEL DUMP', '280.0', NULL, '1905-07-13 00:00:00', '2022-08-01 00:00:00', 'Tanjung Priok', '44774', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(67, 'MDT035', 11, 16, 'MEC2432BANP115105', 'KD-MGJ', '400951D0119571', 'Mercedes Benz', '8,1 TON', NULL, 'VESSEL DUMP', '280.0', NULL, '1905-07-13 00:00:00', '2022-11-01 00:00:00', 'Tanjung Priok', '44866', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(68, 'MDT036', 11, 16, 'MEC2432BANP116404', 'KD-MGJ', '400951D0120262', 'Mercedes Benz', '8,1 TON', NULL, 'VESSEL DUMP', '280.0', NULL, '1905-07-13 00:00:00', '2022-11-01 00:00:00', 'Tanjung Priok', '44866', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(69, 'MDT039', 11, 16, 'MEC2432BANP115068', 'KD-MGJ', '400951D0119536', 'Mercedes Benz', '8,1 TON', NULL, 'VESSEL DUMP', '280.0', NULL, '1905-07-13 00:00:00', '2022-11-01 00:00:00', 'Tanjung Priok', '44866', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(70, 'MDT040', 11, 16, 'MEC2432BANP115055', 'KD-MGJ', '400951D0119529', 'Mercedes Benz', '8,1 TON', NULL, 'VESSEL DUMP', '280.0', NULL, '1905-07-13 00:00:00', '2022-11-01 00:00:00', 'Tanjung Priok', '44866', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(71, 'MDT041', 11, 16, 'MEC2432BANP115073', 'KD-MGJ', '400951D0119539', 'Mercedes Benz', '8,1 TON', NULL, 'VESSEL DUMP', '280.0', NULL, '1905-07-13 00:00:00', '2022-11-01 00:00:00', 'Tanjung Priok', '44866', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(72, 'MDT042', 11, 16, 'MEC2432BANP115094', 'KD-MGJ', '400951D0119501', 'Mercedes Benz', '8,1 TON', NULL, 'VESSEL DUMP', '280.0', NULL, '1905-07-13 00:00:00', '2022-11-01 00:00:00', 'Tanjung Priok', '44866', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(73, 'MDT043', 11, 16, 'MEC2432BANP115054', 'KD-MGJ', '400951D0119513', 'Mercedes Benz', '8,1 TON', NULL, 'VESSEL DUMP', '280.0', NULL, '1905-07-13 00:00:00', '2022-11-01 00:00:00', 'Tanjung Priok', '44866', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(74, 'MDT045', 11, 16, 'MEC2432BANP115017', 'KD-MGJ', '400951D0119503', 'Mercedes Benz', '8,1 TON', NULL, 'VESSEL DUMP', '280.0', NULL, '1905-07-13 00:00:00', '2022-11-01 00:00:00', 'Tanjung Priok', '44866', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(75, 'MDT046', 11, 17, 'MFFCWZ50GNK819925', 'CWE 280', 'GH8E581283C1P', 'NISSAN', '8,1 TON', NULL, 'VESSEL DUMP', '280.0', NULL, '2022', '2022-12-12 00:00:00', 'SAMARINDA', '44907', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(76, 'MDT047', 11, 17, 'MFFCWZ50GNK819929', 'CWE 280', 'GH8E581288C1P', 'NISSAN', '8,1 TON', NULL, 'VESSEL DUMP', '280.0', NULL, '1905-07-14 00:00:00', '2022-12-12 00:00:00', 'SAMARINDA', '44907', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(77, 'MDT048', 11, 17, 'MFFCWZ50GNK819931', 'CWE 280', 'GH8E581293C1P', 'NISSAN', '8,1 TON', NULL, 'VESSEL DUMP', '280.0', NULL, '1905-07-14 00:00:00', '2022-12-12 00:00:00', 'SAMARINDA', '44907', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(78, 'MDT051', 11, 17, 'MFFCWZ50GNK819905', 'CWE 280', 'GH8E581276C1P', 'NISSAN', '8,1 TON', NULL, 'VESSEL DUMP', '280.0', NULL, '1905-07-14 00:00:00', '2022-12-12 00:00:00', 'SAMARINDA', '44907', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(79, 'MDT052', 11, 17, 'MFFCWZ50GNK819908', 'CWE 280', 'GH8E581237C1P', 'NISSAN', '8,1 TON', NULL, 'VESSEL DUMP', '280.0', NULL, '1905-07-14 00:00:00', '2022-12-12 00:00:00', 'SAMARINDA', '44907', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(80, 'ME020', 12, 18, 'SY0753CA01558', 'BB-6WG1XQA', NULL, 'ISUZU', '5,4  M³', NULL, 'BUCKET', '512.0', '377', '2021', '2021-01-19 00:00:00', '-', 'WORKSHOP', 'B/down major', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(81, 'ME021', 12, 18, 'SY0753CA01568', 'BB-6WG1XQA', '637710 / 6611095 ( new )', 'ISUZU', '5,4  M³', NULL, 'BUCKET', '512.0', '377', '2021', '2021-02-17 00:00:00', '-', 'WORKSHOP', 'B/down major', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(82, 'ME022', 12, 18, 'SY0753CA01538', 'ISUZU BB-6WG1XQA', NULL, 'ISUZU', '5,4  M³', NULL, 'BUCKET', '512.0', '377', '2021', '2021-03-11 00:00:00', 'SAMARINDA', 'WORKSHOP', 'B/down major', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(83, 'ME023', 13, 19, 'SY036GCA52928', 'ISUZU GHK1XDHAG-01', 'R040013 / 988342 ( New )', 'ISUZU', '2,7M³', NULL, 'BUCKET', '288.0', '212', '1905-07-14 00:00:00', '2022-05-18 00:00:00', 'SAMARINDA', '44699', 'SAMARINDA', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(84, 'ME028', 14, 20, 'SY0483CB10228', 'GH-6WG1XKSC-01', 'R-040015', 'ISUZU', '3,5 M³', NULL, 'BUCKET', '405.0', '298', '2021', '2021-06-22 00:00:00', '-', 'WORKSHOP', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(85, 'ME029', 14, 21, 'HHKH616HE0001918', 'CUMMINS HM5.9', '22464972', 'HYUNDAI', '0,8 M³', NULL, 'BUCKET & BLADE', '172.0', '133.4', '2021', '2021-07-23 00:00:00', '-', 'WORKSHOP', 'B/down major', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(86, 'ME031', 12, 22, 'CAT00395CSGD00251', 'C18', NULL, 'CATERPILLAR', '7 M³', NULL, 'BUCKET', '542.0', '404', '2021', '2021-09-24 00:00:00', '-', 'WORKSHOP', 'B/down major', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(87, 'ME032', 12, 22, 'CAT00395VSGD00252', 'C18', 'H4E00251', 'CATERPILLAR', '7 M³', NULL, 'BUCKET', '542.0', '404', '2021', '2021-10-02 00:00:00', '-', 'WORKSHOP', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(88, 'ME034', 14, 23, 'CAT00320ADKJ31122', 'C7', '2W229591', 'CATERPILLAR', '1,13 M³', NULL, 'BUCKET', '138.0', '103', '2021', '2021-12-07 00:00:00', '-', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(89, 'ME035', 14, 20, 'SY048DCB15708', 'ISUZU 6WG1XDHAG-02', '6WG1-648919', 'ISUZU', '3,5 M³', NULL, 'BUCKET', '405.0', '298', '2021', '2021-12-06 00:00:00', 'SAMARINDA', 'WORKSHOP', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(90, 'ME039', 14, 23, 'CAT00320TDKJ31127', 'C4.4', '2W229598', 'CATERPILLAR', '2,7M³', NULL, 'BUCKET', '138.0', '103', '2022', '2022-06-07 00:00:00', 'SAMARINDA', 'Jetty', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(91, 'ME040', 14, 20, 'SY048DCB17318', 'GH-6WG1XKSC-01', NULL, 'ISUZU', '3,5 M³', NULL, 'BUCKET', '405.0', '298', '2022', '2022-04-27 00:00:00', 'NEW', 'WORKSHOP', 'B/down major', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(92, 'ME041', 14, 24, 'CAT00320PFEK30306', 'C7', 'E7A53487', 'CATERPILLAR', '2,12 M³', NULL, 'BUCKET', '213.0', '519', '2022', '2022-07-23 00:00:00', 'SAMBOJA', 'WORKSHOP', 'B/down major', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(93, 'ME042', 12, 18, 'SY0758CC03008', 'ISUZU GH-6WG1XKSC', '6WG1-656526', 'ISUZU', '5,4  M³', NULL, 'BUCKET', '512.0', '377', '2022', NULL, 'SAMARINDA', 'WORKSHOP', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(94, 'ME043', 14, 20, 'SY048DCC36258', 'ISUZU GHK1XDHAG-03', '6WG1-654529', 'ISUZU', '3,5 M³', NULL, 'BUCKET', '405.0', '298', '2022', '2022-10-02 00:00:00', 'SAMARINDA', 'Jetty', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(95, 'ME045', 14, 25, 'HCMDCDF0P00007427', 'CC6BG1TRA14', '411276', 'IZUSU', '0,8 M³', NULL, 'BUCKET', '215.0', '158', '2022', '2022-11-22 00:00:00', 'Hexindo', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(96, 'ME046', 14, 26, 'HCMDDEF2E00052528', 'AA6HK1XQA03', '6HKI-977451', 'IZUSU', '2,0 M³', NULL, 'BUCKET', '246.0', '184', '2022', '2022-11-22 00:00:00', 'SANY', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(97, 'ME047', 12, 27, 'HCMJBE93H00051049', 'BB-6WG1XQA-04', '6WG1-657388', 'IZUSU', '5,5 M³', NULL, 'BUCKET', '483.0', '360', '2022', '2022-12-22 00:00:00', 'SANY', 'WORKSHOP', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(98, 'ME048', 12, 28, '100508', 'SAA6D125E-5', '665882', 'Komatsu', '3,8 M³', NULL, 'BUCKET', '363.0', '270', '2022', '2023-01-27 00:00:00', 'UT', 'HW', 'BBE', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(99, 'ME049', 12, 29, 'CAT00374VRGM10025', 'C15', 'E4T00491', 'CATERPILLAR', '4,40  M³', NULL, 'BUCKET', '481.0', '359', '2022', '2023-01-29 00:00:00', 'Trakindo', 'PIT A9', 'OPERASI', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(100, 'ME050', 12, 27, 'HCMJBE93P00051073', 'BB-6WG1XQA-04', '6WG1-657904', 'IZUSU', '5,5 M³', NULL, 'BUCKET', '483.0', '360', '2022', '2023-02-10 00:00:00', 'Hexindo', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(101, 'ME051', 14, 30, '100519', 'SAA6D125E-5', '665938', 'Komatsu', '3,8 M³', NULL, 'BUCKET', '363.0', '270', '2023', '2023-03-08 00:00:00', 'UT', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(102, 'ME052', 12, 31, 'SY048DCC37608', '6WG1XDHAG-03', '6WG1-654795', 'SANY', '3,5 M³', NULL, 'BUCKET', '405.0', '298', '2023', '2023-04-20 00:00:00', 'SANY', 'HW', 'BBE', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(103, 'ME053', 13, 20, 'SY048DCC39958', '6WG1XDHAG-03', '6WG1-652550', 'SANY', '3,5 M³', NULL, 'BUCKET', '405.0', '298', '2023', '2023-04-21 00:00:00', 'SANY', 'Jetty', 'OPERASI', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(104, 'ME054', 12, 27, 'HCMJBE93H00051091', 'BB-6WG1XQA-04', '658563', 'IZUSU', '5,5 M³', NULL, 'BUCKET', '483.0', '360', '2023', '2023-04-22 00:00:00', 'Hexindo', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(105, 'ME055', 12, 29, 'CRGM10041', 'C15', 'E4T00557', 'CATERPILLAR', '4,40  M³', NULL, 'BUCKET', '483.0', '360', '2023', '2023-04-29 00:00:00', 'Trakindo', 'PIT A9', 'OPERASI', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(106, 'ME056', 12, 29, 'VRGM10042', 'C15', 'E4T00527', 'CATERPILLAR', '4,40  M³', NULL, 'BUCKET', '483.0', '360', '2023', '2023-04-29 00:00:00', 'Trakindo', 'PIT A9', 'OPERASI', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(107, 'ME057', 14, 32, 'HCMDCDF0P00007833', 'CC6BG1TRA14', '419123', 'IZUSU', '1,13 M³', NULL, 'BUCKET', '215.0', '158', '1905-07-15 00:00:00', '2025-10-12 00:00:00', 'BBE', 'BBE', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(108, 'ME058', 14, 26, 'HCMDDEF2E00052660', 'BB-6WG1XQA-04', '6HK1-98167', 'IZUSU', '2,0 M³', NULL, 'BUCKET', '246.0', '184', '2023', '2023-05-05 00:00:00', 'Hexindo', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(109, 'ME059', 14, 25, 'HCMDCDF0V00007837', 'CC-6BG1TRA-14', '418674', 'IZUSU', '1,13 M³', NULL, 'BUCKET', '215.0', '158', '1905-07-15 00:00:00', '2023-06-01 00:00:00', 'Hexindo', 'BBE', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(110, 'ME060', 14, 25, 'HCMDCDF0K00007803', 'CC-6BG1TRA-14', '124290', 'ISUZU', '1,13 M³', NULL, 'BUCKET', '215.0', '158', '2023', '2023-06-14 00:00:00', 'Hexindo', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(111, 'ME061', 12, 33, 'CAT06015P6B900160', 'C27', 'TZR00423', 'CATERPILLAR', '8,6 M³', NULL, 'BUCKET', NULL, '606', '2023', '2023-09-07 00:00:00', 'CKB', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(112, 'ME062', 12, 33, 'CAT06015P6B900159', 'C27', 'TZR00422', 'CATERPILLAR', '10 M³', NULL, 'BUCKET', NULL, '606', '2023', '2023-09-15 00:00:00', 'CKB', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(113, 'ME063', 14, 21, 'HHKHZ616LE0002436', 'CUMMINS HM5.9', '22615686', 'HYUNDAI', '0,8 M³', NULL, 'BUCKET + WHEEL', NULL, NULL, '2024', '2024-06-14 00:00:00', 'UNIQUIP', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(114, 'ME065', 12, 34, 'J20028', 'SAA6D170E-7', '750366', 'Komatsu', '8 M³', NULL, 'BUCKET', NULL, NULL, '2024', '2024-07-05 00:00:00', 'UT', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(115, 'ME066', 14, 35, 'DWGCEOPXTP1010590', 'DX530LC -7M', '7312205', 'HYUNDAI DOOSAN', '3.6 m3', NULL, 'BUCKET', '387.6', '294', '1905-07-16 00:00:00', '2024-08-29 00:00:00', 'BALIKPAPAN', 'BRCM', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(116, 'ME067', 14, 35, 'DWGCECFXCP1010563', 'DX530LC -7M', '950209-02977C', 'HYUNDAI DOOSAN', '3.6 m3', NULL, 'BUCKET', '387.6', '289', '1905-07-16 00:00:00', '2024-08-29 00:00:00', 'BALIKPAPAN', 'BRCM', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(117, 'ME068', 14, 35, 'DWGCECFXLP1010597', 'DX530LC -7M', '7271598', 'HYUNDAI DOOSAN', '3.6 m3', NULL, 'BUCKET', '387.6', '294', '1905-07-16 00:00:00', '2024-08-29 00:00:00', 'BALIKPAPAN', 'BRCM', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(118, 'ME069', 14, 32, 'HCMDCDF0L00008666', 'ZX200-5G', '435663', 'HITACHI', '0.8 m³', NULL, 'BUCKET', '168.0', '125', '1905-07-16 00:00:00', '2024-08-30 00:00:00', 'HITACHI CMI', 'BRCM', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(119, 'ME070', 14, 32, 'HCMDCDF0H00008734', 'ZX200-5G', '437227', 'HITACHI', '0.8 m³', NULL, 'BUCKET', '168.0', '125', '1905-07-16 00:00:00', '2024-08-30 00:00:00', 'HITACHI CMI', 'BRCM', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(120, 'ME071', 14, 26, 'HCMDGHF2H00010030', 'AE-6HK1XWSA-01', '6HK1-A04055', 'ISUZU', '2,0 M³', NULL, 'BUCKET', NULL, NULL, '2024', '2024-09-10 00:00:00', 'Hexindo', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(121, 'ME072', 14, 36, 'HCMDGHF2C00010029', 'ZX350H-7G', '6HK1-A03738', 'ISUZU', '2.3 m3', NULL, 'BUCKET', NULL, NULL, '1905-07-16 00:00:00', '2024-10-09 00:00:00', 'Hexindo', 'BRCM', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(122, 'ME073', 14, 37, 'DBCH2737', 'SAA4D107E-1', '26804055', 'Komatsu', '1,1 M³', NULL, 'BUCKET', NULL, '110', '2024', '2025-05-03 00:00:00', 'United Tracktors', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(123, 'MFT007', 15, 38, 'MFFCWZ50GPK821692', 'CWE 280', 'GHE859D409', 'NISSAN', '20KL', NULL, 'FUEL TANK', NULL, NULL, NULL, '2023-07-26 00:00:00', 'SAMARINDA', 'HW', 'BRCM', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(124, 'MFT009', 15, 39, 'MJEFMJN2RIX23815', '280 JD', 'J08EWDJ35619', 'HINO', NULL, NULL, 'FUEL TANK', NULL, NULL, NULL, '2024-08-29 00:00:00', 'BALIKPAPAN', 'HW', 'BRCM', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(125, 'MG011', 16, 40, 'SEM00922AS9T00769', 'SC9D220G2', 'D921AO16430', 'SDEC POWER (SEM)', NULL, NULL, 'BLADE AND RIPPER', '220.0', '162', '2022', NULL, 'BBE', 'Hauling Road', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(126, 'MG012', 16, 40, 'SEM00922KS9T00796', 'SC9D220G2', 'D921C018247', 'SDEC POWER (SEM)', NULL, NULL, 'BLADE & RIPPER', '220.0', '162', '2022', NULL, 'NEW', 'Workshop', 'B/down major', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(127, 'MG015', 16, 40, 'SEM00922KS9T00817', 'SC9D220G2', NULL, 'SDEC POWER (SEM)', NULL, NULL, 'BLADE & RIPPER', '220.0', '162', '2022', '2023-03-04 00:00:00', 'NEW', 'Workshop', 'Standby', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(128, 'MG016', 16, 40, 'SEM00922VS9T00818', 'SC9D220G2', 'D9222002000', 'SDEC POWER (SEM)', NULL, NULL, 'BLADE & RIPPER', '220.0', '162', '2022', '2023-03-04 00:00:00', 'NEW', 'Jetty', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(129, 'MG017', 16, 40, 'SEM00922HS9T00998', 'SC9D220G2', 'D922B010117', 'SDEC POWER (SEM)', NULL, NULL, 'BLADE & RIPPER', '220.0', '162', '2022', '2023-04-29 00:00:00', 'NEW', 'Workshop', 'B/down minor', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(130, 'MG018', 16, 40, 'SEM00922ES9T01005', 'SC9D220G2', 'D922B010112', 'SDEC POWER (SEM)', NULL, NULL, 'BLADE & RIPPER', '220.0', '162', '1905-07-14 00:00:00', '2023-04-29 00:00:00', NULL, '45045', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(131, 'MG019', 16, 41, '10662', 'SAAGD125E-5', '666639', 'KOMATSU', NULL, NULL, 'BLADE & RIPPER', '290.0', '216', '1905-07-15 00:00:00', '2023-07-12 00:00:00', NULL, '45119', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(132, 'MG020', 16, 42, 'CAT00014VF5800136', 'C13', 'TXX05080 / SMZ01102', 'CATERPILLAR', NULL, NULL, 'BLADE & RIPPER', '259.0', '193', '2023', '2023-09-06 00:00:00', 'NEW', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(133, 'MG021', 16, 42, 'CAT00014VF5800138', 'C13', 'TXX05053 / SMZ01107', 'CATERPILLAR', NULL, NULL, 'BLADE & RIPPER', '259.0', '193', '2023', '2023-09-06 00:00:00', 'NEW', 'PIT A9', 'OPERASI', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(134, 'MG022', 16, 43, 'CAT00016JEB900218', 'C13', 'P4E06073', 'CATERPILLAR', NULL, NULL, 'BLADE & RIPPER', '353.0', '263', '2024', '2024-05-02 00:00:00', 'NEW', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(135, 'MG023', 16, 43, 'CAT00016CEB900214', 'C13', 'P4E05954', 'CATERPILLAR', NULL, NULL, 'BLADE & RIPPER', '353.0', '263', '2024', '2024-06-21 00:00:00', 'NEW', 'Workshop', 'B/down minor', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(136, 'MG025', 16, 44, 'KMTGD021CRD013694', 'SAD140E-2', '00044865', 'KOMATSU', NULL, NULL, 'BLADE & RIPPER', NULL, NULL, '2024', '2025-01-12 00:00:00', 'Tanjung Priok', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(137, 'MGS002', 17, 45, 'OLY00000EMMR01671', NULL, 'U396483', NULL, NULL, '-', 'GENSET', NULL, '35KVA', '2014', NULL, NULL, 'HW', 'BRCM', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(138, 'MGS006', 17, 46, '-', 'TF230-di', NULL, NULL, NULL, '-', 'GENSET', NULL, '15KVA', '2022', NULL, NULL, 'HW', 'BRCM', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(139, 'MGS009', 17, 47, 'APC44T23011450', '4BT3.9-G1', '93240711', 'DONFENG CUMMINS', NULL, '-', 'GENSET', '1500.0', '40KVA', '2024', '2024-10-10 00:00:00', 'SAMBOJA', 'HW', 'BRCM', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(140, 'MLT005', 18, 48, 'MAD37700', 'WP10.380E32', '1621f062548', '1621G062548', NULL, NULL, 'OIL TANK', NULL, NULL, '2022', '2021-12-10 00:00:00', 'SAMARINDA', 'HW', 'BBE', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(141, 'MLT008', 18, 17, 'MFFCWZG50GPK823232', 'CWE 280', 'GHE601744', 'NISSAN', NULL, NULL, 'OIL TANK', '280.0', NULL, '2023', '2023-07-28 00:00:00', 'SAMARINDA', 'BRCM', 'BRCM', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(142, 'MMH005', 4, 49, 'LFNB8LCA6PLE00304', 'CA2080P40-130CG', 'YC4D130-33-D36Y5P00005', 'Faw', '29', NULL, 'Bus', NULL, NULL, '2023', '2023-07-04 00:00:00', 'BALIKPAPAN', 'HW', 'BBE', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(143, 'MTL013', 19, 50, 'PEC00220318', '3TNM72-GPGE', '71753', 'YANMAR', '12,6 KW', NULL, 'TOWER LAMP', NULL, '8-19 KW', NULL, '2021-11-11 00:00:00', 'UNIQUIP', 'HW', 'BBE', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(144, 'MTL015', 19, 50, 'PEC00220319', '3TNM72-GPGE', '71790', 'YANMAR', '12,6 KW', NULL, 'TOWER LAMP', NULL, '8-19 KW', NULL, '2021-11-11 00:00:00', 'UNIQUIP', 'HW', 'BBE', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(145, 'MTL016', 19, 50, 'PEC00220320', '3TNM72-GPGE', '71789', 'YANMAR', '12,6 KW', NULL, 'TOWER LAMP', NULL, '8-19 KW', NULL, '2021-11-11 00:00:00', 'UNIQUIP', 'HW', 'BBE', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(146, 'MTL023', 19, 51, 'X1CH128167', '3TNM72-GHFCL', '93705', 'YANMAR', '5,1KW', NULL, 'TOWER LAMP', NULL, '37KW', NULL, '2022-09-01 00:00:00', 'SAMARINDA', 'HW', 'BRCM', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(147, 'MTL031', 19, 51, 'X1CH129625', '3TNM72-GHFCL', '93707', 'YANMAR', '5,1KW', NULL, 'TOWER LAMP', NULL, '37KW', NULL, '2023-05-20 00:00:00', 'SAMARINDA', 'HW', 'BRCM', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(148, 'MTL035', 19, 51, 'X1CH129630', '3TNM72-GHFCL', '93708', 'YANMAR', '5,1KW', NULL, 'TOWER LAMP', NULL, '37KW', NULL, '2023-05-20 00:00:00', 'SAMARINDA', 'HW', 'BRCM', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(149, 'MTL040', 19, 51, 'X1CH151046', '3TNM72-GHFCL', '103713', 'YANMAR', '5,1KW', NULL, 'TOWER LAMP', NULL, '37KW', NULL, '2024-09-12 00:00:00', 'PT. KJA JAKARTA', 'HW', 'BRCM', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(150, 'MTL041', 19, 51, 'X1CH151047', '3TNM72-GHFCL', '103714', 'YANMAR', '5,1KW', NULL, 'TOWER LAMP', NULL, '37KW', NULL, '2024-09-12 00:00:00', 'PT. KJA JAKARTA', 'HW', 'BRCM', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(151, 'MTL042', 19, 51, 'X1CH151806', '3TNM72-GHFCL', '103868', 'YANMAR', '5,1KW', NULL, 'TOWER LAMP', NULL, '37KW', NULL, '2024-09-12 00:00:00', 'PT. KJA JAKARTA', 'HW', 'BRCM', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(152, 'MTL043', 19, 51, 'X1CH151807', '3TNM72-GHFCL', '103856', 'YANMAR', '5,1KW', NULL, 'TOWER LAMP', NULL, '37KW', NULL, '2024-09-12 00:00:00', 'PT. KJA JAKARTA', 'HW', 'BRCM', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(153, 'MWF007', 20, 46, '-', NULL, NULL, 'TF 155', NULL, NULL, 'WATERFILL', NULL, NULL, NULL, NULL, '-', 'BRCM', NULL, 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(154, 'MWL007', 21, 52, 'SEM00655ES5506982', 'WP10G220E233', '1220C001739', 'WEICHAI', '2,7-4,5 M³', NULL, 'BUCKET', '238.0', '162', '2021', '2021-06-06 00:00:00', 'KITADIN', 'Workshop', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(155, 'MWL008', 21, 53, 'SEM00660VS62003076', 'WP10G240E203', '1221G008725', 'WEICHAI', '2,7-4,5 M³', NULL, 'BUCKET', '238.0', '162', '2021', '2022-03-29 00:00:00', 'KITADIN', 'Workshop', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(156, 'MWL009', 21, 54, 'SEM006760CS7D00272', 'WP10G270E341', '1622L049521', 'WEICHAI', '7 M³', NULL, 'BUCKET', '200.0', '199', '2022', '2024-03-19 00:00:00', 'TSU CKB', 'Jetty', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(157, 'MWM006', 22, 46, '-', 'TS 230', NULL, NULL, NULL, NULL, 'Welding Mechine', NULL, NULL, NULL, NULL, NULL, 'HW', 'BRCM', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(158, 'MWM007', 22, 46, '-', 'TS 230', NULL, NULL, NULL, NULL, 'Welding Mechine', NULL, NULL, NULL, NULL, NULL, 'HW', 'BRCM', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(159, 'MWM010', 22, 55, '11272205', NULL, 'MC250030E', NULL, NULL, NULL, 'Welding Mechine', '32.0', '23,9 KWA', '2021', '2024-11-12 00:00:00', 'PT ATE JKT TO SAMBOJA', 'HW', 'BRCM', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(160, 'MWP003', 20, 56, '-', 'PF6TB-22', '81020N', 'NISSAN', NULL, NULL, 'PUMP', NULL, NULL, NULL, NULL, 'SURABAYA', 'BBE', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(161, 'MWP005', 20, 56, '-', 'CAT 3306', NULL, 'MITSUBISHI', NULL, NULL, 'PUMP', NULL, NULL, NULL, NULL, NULL, 'BRCM', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(162, 'MWP007', 20, 57, '-', 'CAT 3406', NULL, 'CATERPILLAR', NULL, NULL, 'PUMP', NULL, NULL, NULL, NULL, NULL, 'PIT A9', 'OPERASI', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(163, 'MWP008', 20, 58, '-', 'C13', 'LGK23469', 'CATERPILLAR', NULL, NULL, 'PUMP', NULL, NULL, '2022', '2022-04-27 00:00:00', 'BALIKPAPAN', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(164, 'MWP009', 20, 58, NULL, 'CAT 3406 / CF 385', NULL, 'CATERPILLAR', NULL, NULL, 'PUMP', NULL, NULL, '2022', '2022-10-20 00:00:00', 'BALIKPAPAN', 'Workshop', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(165, 'MWP012', 20, 59, '-', 'CAT 3412 (twin turbho)', '10Z28503', 'CATERPILLAR', NULL, NULL, 'PUMP', NULL, NULL, '2023', '2023-06-20 00:00:00', NULL, 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(166, 'MWP015', 20, 60, '-', 'CAT 3412 (singel turbho)', 'D8AYT004067', 'CATERPILLAR', NULL, NULL, 'PUMP', NULL, NULL, '2024-10-01 00:00:00', NULL, 'Perakitan unit', 'PIT A9', 'BD', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(167, 'MWP016', 20, 60, '-', 'CAT 3412 (singel turbho)', NULL, 'CATERPILLAR', NULL, NULL, 'PUMP', NULL, NULL, '2025-03-01 00:00:00', NULL, 'Perakitan unit', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(168, 'MWT005', 23, 61, 'MHMFN62FXLK001529', '6M60', '260183', 'T.RAD', '20KL', NULL, 'WATER TANK', NULL, NULL, '2021', '2021-08-28 00:00:00', 'SAMARINDA', 'BRCM', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(169, 'MWT006', 23, 48, 'MAD37692', 'WP10.380E32', '1621F062518', '1621G062498', '32KL', NULL, 'WATER TANK', '380.0', NULL, '2021', '2021-12-24 00:00:00', NULL, 'Workshop', 'B/down major', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(170, 'MWT007', 23, 48, 'MAD37695', 'WP10.380E32', '1621F062519', '1621G062548', '32KL', NULL, 'WATER TANK', '380.0', NULL, '2022', '2022-04-25 00:00:00', 'NEW', 'Workshop', 'B/down minor', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(171, 'MWT008', 23, 48, 'MAD37693', 'WP10.380E32', '1621F062520', '1621G062548', '32KL', NULL, 'WATER TANK', '380.0', NULL, '2022', '2022-04-25 00:00:00', 'NEW', 'Workshop', 'B/down major', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(172, 'MWT009', 23, 17, 'MFFCWZ50GNK821707', 'CWE 280', 'GH8E590579P', 'NISSAN', '20KL', NULL, 'WATER TANK', '280.0', NULL, '1905-07-15 00:00:00', '2023-04-05 00:00:00', 'NEW', 'BBE', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(173, 'MWT010', 23, 17, 'MFFCWZ50GPK821698', 'CWE 280', 'GH8E590405P', 'NISSAN', '20KL', NULL, 'WATER TANK', '280.0', NULL, '1905-07-15 00:00:00', '2023-04-29 00:00:00', 'NEW', 'BRCM', NULL, 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(174, 'MWT012', 23, 62, 'LWJMT960JPO718513', 'WP13G530E310', '3123A002324', 'WEICHAI', '40KL', NULL, 'WATER TANK', NULL, '390', '2023', '2023-06-14 00:00:00', 'NEW', 'Workshop', 'B/down minor', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(175, 'MWT015', 23, 63, 'TLS75430CR7530109', 'WP12G430E310', '1423L083564', 'WEICHAI', '50KL', NULL, 'WATER TANK', NULL, '353', '2024', '2024-04-08 00:00:00', 'NEW', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(176, 'MWT016', 23, 63, 'TLS75430CR7530112', 'WP12G430E310', '1424C013684', 'WEICHAI', '50KL', NULL, 'WATER TANK', NULL, '353', '2024', '2024-08-03 00:00:00', 'NEW', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(177, 'MWT017', 23, 64, 'LWJMT960KM0718083', 'WP13G530E310', '3121G059853', 'WEICHAI', '50KL', NULL, 'WATER TANK ( FABRICATION )', '530.0', '390', '2021', '2021-09-20 00:00:00', 'BBE', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(178, 'OHT057', 24, 65, 'PRB01136', '3412E', 'NTE01348', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2022-03-28 00:00:00', 'NEW', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(179, 'OHT058', 24, 65, 'PRB01137', '3412E', 'NTE01334', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2022-03-28 00:00:00', 'NEW', 'PIT STOP JONGKANG', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(180, 'OHT059', 24, 65, 'PRB01292', '3412E', 'NTE01509', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2022-10-07 00:00:00', 'NEW', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(181, 'OHT060', 24, 65, 'PRB01294', '3412E', 'NTE01513', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2022-10-07 00:00:00', 'NEW', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(182, 'OHT061', 24, 65, 'PRB01296', '3412E', 'NTE01510', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', NULL, 'Trakindo', 'PIT STOP JONGKANG', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(183, 'OHT062', 24, 65, 'PRB01334', '3412E', 'NTE01563', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', NULL, 'Trakindo', 'PIT STOP JONGKANG', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(184, 'OHT063', 24, 65, 'PRB01336', '3412E', 'NTE01562', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', NULL, 'Trakindo', 'PIT STOP JONGKANG', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(185, 'OHT065', 24, 65, 'PRB01337', '3412E', 'NTE01565', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', NULL, 'Trakindo', 'PIT STOP JONGKANG', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(186, 'OHT066', 24, 65, 'PRB01338', '3412E', 'NTE01564', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', NULL, 'Trakindo', 'PIT STOP JONGKANG', 'Standby', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(187, 'OHT067', 24, 65, 'PRB01361', '3412E', 'NTE01598', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2022-12-15 00:00:00', 'Trakindo', 'PIT STOP JONGKANG', 'Standby', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(188, 'OHT068', 24, 65, 'PRB01362', '3412E', 'NTE01599', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2022-12-15 00:00:00', 'Trakindo', 'PIT STOP JONGKANG', 'Standby', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(189, 'OHT069', 24, 65, 'PRB01366', '3412E', 'NTE01606', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2022-12-15 00:00:00', 'Trakindo', 'PIT STOP JONGKANG', 'Standby', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(190, 'OHT070', 24, 65, 'PRB01367', '3412E', 'NTE01604', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2022-12-15 00:00:00', 'Trakindo', 'PIT STOP JONGKANG', 'Standby', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(191, 'OHT071', 24, 65, 'PRB01385', '3412E', 'NTE01616', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2022-12-15 00:00:00', 'Trakindo', 'PIT STOP JONGKANG', 'Standby', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(192, 'OHT072', 24, 65, 'PRB01389', '3412E', 'NTE01594', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-01-29 00:00:00', 'Trakindo', 'PIT STOP JONGKANG', 'Standby', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(193, 'OHT073', 24, 65, 'PRB01390', '3412E', 'NTE01570', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-01-29 00:00:00', 'Trakindo', 'PIT STOP JONGKANG', 'Standby', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(194, 'OHT074', 24, 65, 'PRB01426', '3412E', NULL, 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-01-29 00:00:00', 'Trakindo', 'PIT STOP JONGKANG', 'Standby', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(195, 'OHT075', 24, 65, 'PRB01427', '3412E', NULL, 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-01-29 00:00:00', 'Trakindo', 'PIT STOP JONGKANG', 'Standby', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(196, 'OHT076', 24, 65, 'PRB01430', '3412E', 'NTE01638', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-01-29 00:00:00', 'Trakindo', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(197, 'OHT077', 24, 65, 'PRB01420', '3412E', 'NTE01658', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-02-06 00:00:00', 'Trakindo', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(198, 'OHT078', 24, 65, 'PRB01421', '3412E', 'NTE01643', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-02-06 00:00:00', 'Trakindo', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(199, 'OHT079', 24, 65, 'PRB01422', '3412E', 'NTE01635', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-02-06 00:00:00', 'Trakindo', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(200, 'OHT080', 24, 65, 'PRB01432', '3412E', 'NTE01646', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-02-06 00:00:00', 'Trakindo', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(201, 'OHT081', 24, 65, 'PRB01433', '3412E', 'NTE01629', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-02-06 00:00:00', 'Trakindo', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(202, 'OHT082', 24, 65, 'PRB01440', '3412E', NULL, 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-03-04 00:00:00', 'Trakindo', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(203, 'OHT083', 24, 65, 'PRB01445', '3412E', 'NTE01677', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-03-04 00:00:00', 'Trakindo', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1);
INSERT INTO `master_units` (`id`, `nomor_unit`, `unit_type_id`, `unit_model_id`, `sn_chassis`, `engine_model`, `sn_engine`, `engine_make`, `capacity`, `no_polisi`, `attachments`, `hp`, `kw`, `perakitan`, `date_receive`, `dari`, `location`, `remarks`, `service`, `active`, `site`, `created_at`, `updated_at`, `site_id`) VALUES
(204, 'OHT084', 24, 65, 'PRB01446', '3412E', 'NTE01672', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-03-04 00:00:00', 'Trakindo', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(205, 'OHT085', 24, 65, 'PRB01451', '3412E', 'NTE01681', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-03-04 00:00:00', 'Trakindo', 'WORKSHOP', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(206, 'OHT086', 24, 65, 'PRB01452', '3412E', 'NTE01674', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-03-04 00:00:00', 'Trakindo', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(207, 'OHT087', 24, 65, 'PRB01460', '3412E', 'NTE01685', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-04-06 00:00:00', 'Trakindo', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(208, 'OHT088', 24, 65, 'PRB01461', '3412E', 'NTE01639', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-04-06 00:00:00', 'Trakindo', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(209, 'OHT089', 24, 65, 'PRB01462', '3412E', 'NTE01688', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-04-06 00:00:00', 'Trakindo', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(210, 'OHT090', 24, 65, 'PRB01501', '3412E', 'NTE01726', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-04-20 00:00:00', 'Trakindo', 'WORKSHOP', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(211, 'OHT091', 24, 65, 'PRB01502', '3412E', 'NTE01728', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-04-20 00:00:00', 'Trakindo', 'WORKSHOP', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(212, 'OHT092', 24, 65, 'PRB01503', '3412E', 'NTE01730', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-04-20 00:00:00', 'Trakindo', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(213, 'OHT093', 24, 65, 'PRB01436', '3412E', 'NTE01666', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-08-22 00:00:00', 'Trakindo', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(214, 'OHT094', 24, 65, 'PRB01437', '3412E', 'NTE01634', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-08-22 00:00:00', 'Trakindo', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(215, 'OHT095', 24, 65, 'PRB01459', '3412E', 'NTE01686', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-08-22 00:00:00', 'Trakindo', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(216, 'OHT096', 24, 65, 'PRB01464', '3412E', 'NTE01671', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-08-22 00:00:00', 'Trakindo', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(217, 'OHT097', 24, 65, 'PRB01608', '3412E', 'NTE01840', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-08-22 00:00:00', 'Trakindo', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(218, 'OHT098', 24, 65, 'PRB01463', '3412E', 'NTE01620', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-08-22 00:00:00', 'Trakindo', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(219, 'OHT099', 24, 65, 'PRB01609', '3412E', 'NTE01841', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-08-22 00:00:00', 'Trakindo', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(220, 'OHT100', 24, 65, 'PRB01610', '3412E', 'NTE01824', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-08-22 00:00:00', 'Trakindo', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(221, 'OHT101', 24, 65, 'PRB01611', '3412E', 'NTE01804', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-08-22 00:00:00', 'Trakindo', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(222, 'OHT102', 24, 65, 'PRB01612', '3412E', 'NTE01820', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2022', '2023-08-22 00:00:00', 'Trakindo', 'PIT A9', 'OPERASI', 0, 0, 'BBE', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 1),
(223, 'OHT115', 24, 66, 'FKT03077', '3412E', 'NTE02006', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2024', '2024-12-22 00:00:00', 'Tanjung Priok', 'PIT A9', 'OPERASI', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(224, 'OHT116', 24, 66, 'FKT03078', '3412E', 'NTE02005', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2024', '2024-12-22 00:00:00', 'Tanjung Priok', 'PIT A9', 'OPERASI', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(225, 'OHT117', 24, 66, 'FKT03079', '3412E', 'NTE01970', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2024', '2024-12-22 00:00:00', 'Tanjung Priok', 'PIT A9', 'OPERASI', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(226, 'OHT118', 24, 66, 'FKT03080', '3412E', 'NTE02009', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2024', '2024-12-22 00:00:00', 'Tanjung Priok', 'PIT A9', 'OPERASI', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(227, 'OHT119', 24, 66, 'FKT03083', '3412E', 'NTE02011', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2024', '2024-12-22 00:00:00', 'Tanjung Priok', 'PIT A9', 'OPERASI', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(228, 'OHT120', 24, 66, 'FKT03084', '3412E', 'NTE02009', 'CATERPILLAR', '60 TON', NULL, 'VESSEL DUMP', '681.0', '501', '2024', '2024-12-22 00:00:00', 'Tanjung Priok', 'PIT A9', 'OPERASI', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2),
(229, 'T-02', 3, 67, 'MK2KSWMDNPJ000429', 'PAJERO SPORT 2,5 GLX- 4X4 5 MT', '4D56UBK5732', 'PAJERO SPORT', NULL, 'KT 1061 IE', 'MOPEN/JEEP', NULL, NULL, '2023', NULL, 'SAMARINDA', 'HW', 'EX HO', 0, 1, 'HW', '2026-08-05 10:54:56', '2026-08-05 10:54:56', 2);

-- --------------------------------------------------------

--
-- Table structure for table `mechanics`
--

CREATE TABLE `mechanics` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_lengkap` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan_id` bigint UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `site_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mechanics`
--

INSERT INTO `mechanics` (`id`, `nama_lengkap`, `jabatan_id`, `is_active`, `created_at`, `updated_at`, `site_id`) VALUES
(1, 'Joko', 1, 1, '2026-08-04 20:20:41', '2026-08-05 00:25:08', 2);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint UNSIGNED NOT NULL,
  `sender_id` bigint UNSIGNED NOT NULL,
  `receiver_id` bigint UNSIGNED NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `body`, `read_at`, `created_at`, `updated_at`) VALUES
(5, 3, 1, '👍 hi apa kabar', '2026-08-05 17:20:37', '2026-08-05 17:20:18', '2026-08-05 17:20:37'),
(6, 1, 3, 'baik,, bagaimana dengan kamu', '2026-08-05 17:20:48', '2026-08-05 17:20:46', '2026-08-05 17:20:48'),
(7, 3, 1, 'tes', '2026-08-05 17:21:11', '2026-08-05 17:21:09', '2026-08-05 17:21:11'),
(11, 1, 2, '[Work Order: WO-08-26-0004](http://127.0.0.1:8000/work-orders/6)', NULL, '2026-08-06 21:10:53', '2026-08-06 21:10:53'),
(12, 1, 2, 'http://127.0.0.1:8000/work-orders/6', NULL, '2026-08-06 21:11:04', '2026-08-06 21:11:04'),
(13, 1, 2, 'hi', NULL, '2026-08-06 21:14:19', '2026-08-06 21:14:19'),
(14, 4, 1, 'hi', '2026-08-06 21:15:42', '2026-08-06 21:15:35', '2026-08-06 21:15:42'),
(15, 1, 4, '👍', '2026-08-06 21:15:51', '2026-08-06 21:15:50', '2026-08-06 21:15:51');

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2026_08_04_123953_create_permission_tables', 1),
(6, '2026_08_04_124008_create_activity_log_table', 1),
(7, '2026_08_04_124009_add_event_column_to_activity_log_table', 1),
(8, '2026_08_04_124010_add_batch_uuid_column_to_activity_log_table', 1),
(9, '2026_08_04_124024_create_departments_table', 1),
(10, '2026_08_04_124025_create_jabatans_table', 1),
(11, '2026_08_04_124026_create_app_settings_table', 1),
(12, '2026_08_04_124027_create_approval_matrices_table', 1),
(13, '2026_08_04_135958_create_messages_table', 2),
(14, '2026_08_04_140110_create_approval_matrices_table', 3),
(15, '2026_08_04_142538_fix_approval_matrices_columns', 4),
(16, '2026_08_04_152106_create_modules_table', 5),
(17, '2026_08_04_153917_create_unit_types_table', 6),
(18, '2026_08_04_153943_create_unit_models_table', 6),
(19, '2026_08_04_153954_create_master_units_table', 6),
(20, '2026_08_04_165548_create_mechanics_table', 7),
(21, '2026_08_04_165647_create_tool_categories_table', 7),
(22, '2026_08_04_165648_create_tools_table', 7),
(23, '2026_08_04_165649_create_tool_stocks_table', 7),
(24, '2026_08_04_165650_create_tool_transactions_table', 8),
(25, '2026_08_04_165651_create_incident_reports_table', 9),
(26, '2026_08_04_165652_create_stock_opnames_table', 9),
(27, '2026_08_04_165653_create_stock_opname_details_table', 9),
(28, '2026_08_05_100001_create_breakdown_types_table', 10),
(29, '2026_08_05_100002_create_component_groups_table', 10),
(30, '2026_08_05_100003_create_wo_categories_table', 10),
(31, '2026_08_05_100004_create_parts_table', 10),
(32, '2026_08_05_100005_create_work_orders_table', 10),
(33, '2026_08_05_100006_create_wo_tasks_table', 10),
(34, '2026_08_05_100007_create_wo_subtasks_table', 10),
(35, '2026_08_05_100008_create_wo_subtask_manpower_table', 10),
(36, '2026_08_05_100009_create_wo_subtask_parts_table', 10),
(37, '2026_08_05_100010_create_wo_subtask_tools_table', 10),
(38, '2026_08_05_072207_add_opportunity_to_work_orders_table', 11),
(39, '2026_08_05_075757_create_sites_table', 12),
(40, '2026_08_05_075919_add_site_id_to_master_tables', 12),
(41, '2026_08_05_104135_create_hour_meters_table', 13),
(43, '2026_08_05_140000_add_avatar_to_users_table', 14),
(44, '2026_08_05_150000_add_performance_indexes', 14),
(45, '2026_08_05_174229_create_pm_templates_table', 15),
(46, '2026_08_05_174230_create_pm_template_tasks_table', 15),
(47, '2026_08_05_174231_create_pm_template_subtasks_table', 15),
(48, '2026_08_05_174232_create_pm_schedules_table', 15),
(49, '2026_08_05_174233_add_pm_schedule_id_to_work_orders_table', 15),
(50, '2026_08_05_175641_add_site_id_to_pm_templates', 16),
(51, '2026_08_06_011800_create_hse_jsas_table', 17),
(52, '2026_08_06_011801_create_hse_jsa_steps_table', 17),
(53, '2026_08_06_011802_create_hse_ptws_table', 17),
(54, '2026_08_06_011803_create_hse_lotos_table', 17),
(55, '2026_08_06_014200_add_lokasi_kerusakan_to_work_orders_table', 18),
(56, '2026_08_06_014201_create_pra_work_orders_table', 18),
(57, '2026_08_06_053853_add_document_scan_to_hse_jsas_table', 19),
(58, '2026_08_06_055543_add_document_scan_to_hse_ptws_table', 20),
(59, '2026_08_06_073646_create_plan_budgets_table', 21),
(60, '2026_08_06_075946_create_plan_budget_parts_table', 22),
(61, '2026_08_06_141437_create_vendors_table', 23),
(62, '2026_08_06_141438_create_jwos_table', 23),
(63, '2026_08_06_160610_create_document_signatures_table', 24),
(64, '2026_08_06_172033_add_photos_to_jwos_table', 25),
(65, '2026_08_07_140000_add_bio_to_users_table', 26),
(66, '2026_08_07_054725_create_fars_table', 27),
(67, '2026_08_07_054726_create_far_attachments_table', 27),
(75, '2026_08_08_135854_create_productions_table', 28),
(76, '2026_08_08_135854a_create_production_fleets_table', 28),
(77, '2026_08_08_135855_create_production_haulers_table', 28),
(78, '2026_08_08_135855_create_production_supports_table', 28),
(79, '2026_08_08_135856_create_production_delays_table', 28),
(80, '2026_08_08_152859_add_target_bcm_to_production_fleets_table', 29);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_permissions`
--

INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES
(95, 'App\\Models\\User', 1),
(96, 'App\\Models\\User', 1),
(97, 'App\\Models\\User', 1),
(98, 'App\\Models\\User', 1),
(1, 'App\\Models\\User', 2),
(5, 'App\\Models\\User', 2),
(6, 'App\\Models\\User', 2),
(7, 'App\\Models\\User', 2),
(9, 'App\\Models\\User', 2),
(10, 'App\\Models\\User', 2),
(11, 'App\\Models\\User', 2),
(25, 'App\\Models\\User', 2),
(29, 'App\\Models\\User', 2),
(30, 'App\\Models\\User', 2),
(33, 'App\\Models\\User', 2),
(34, 'App\\Models\\User', 2),
(35, 'App\\Models\\User', 2),
(37, 'App\\Models\\User', 2),
(38, 'App\\Models\\User', 2),
(39, 'App\\Models\\User', 2),
(41, 'App\\Models\\User', 2),
(42, 'App\\Models\\User', 2),
(43, 'App\\Models\\User', 2),
(45, 'App\\Models\\User', 2),
(46, 'App\\Models\\User', 2),
(47, 'App\\Models\\User', 2),
(49, 'App\\Models\\User', 2),
(50, 'App\\Models\\User', 2),
(52, 'App\\Models\\User', 2),
(53, 'App\\Models\\User', 2),
(54, 'App\\Models\\User', 2);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 3),
(5, 'App\\Models\\User', 4);

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parts`
--

CREATE TABLE `parts` (
  `id` bigint UNSIGNED NOT NULL,
  `part_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `part_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `satuan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `kategori_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kategori_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kategori_3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kategori_4` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `site_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parts`
--

INSERT INTO `parts` (`id`, `part_number`, `part_description`, `satuan`, `cost`, `kategori_1`, `kategori_2`, `kategori_3`, `kategori_4`, `created_at`, `updated_at`, `site_id`) VALUES
(1, '15W-40', 'Oil Engine', 'Liter', 27000.00, 'Oil & Fuel', NULL, NULL, NULL, '2026-08-05 23:37:50', '2026-08-05 23:40:23', 2),
(2, 'SAE 30', 'Oil SAE 30', 'Liter', 30000.00, 'Oil & Fuel', NULL, NULL, NULL, '2026-08-05 23:38:56', '2026-08-05 23:40:35', 2),
(3, 'Fuel', 'Fuel', 'Liter', 32000.00, 'Oil & Fuel', NULL, NULL, NULL, '2026-08-05 23:41:19', '2026-08-05 23:41:19', 2);

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
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view_users', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(2, 'create_users', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(3, 'edit_users', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(4, 'delete_users', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(5, 'view_departments', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(6, 'create_departments', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(7, 'edit_departments', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(8, 'delete_departments', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(9, 'view_jabatans', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(10, 'create_jabatans', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(11, 'edit_jabatans', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(12, 'delete_jabatans', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(13, 'view_roles', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(14, 'create_roles', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(15, 'edit_roles', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(16, 'delete_roles', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(17, 'view_modules', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(18, 'create_modules', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(19, 'edit_modules', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(20, 'delete_modules', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(21, 'view_approval_matrix', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(22, 'create_approval_matrix', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(23, 'edit_approval_matrix', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(24, 'delete_approval_matrix', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(25, 'view_activity_log', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(26, 'create_activity_log', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(27, 'edit_activity_log', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(28, 'delete_activity_log', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(29, 'view_backup', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(30, 'create_backup', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(31, 'edit_backup', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(32, 'delete_backup', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(33, 'view_settings', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(34, 'create_settings', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(35, 'edit_settings', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(36, 'delete_settings', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(37, 'view_master_units', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(38, 'create_master_units', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(39, 'edit_master_units', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(40, 'delete_master_units', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(41, 'view_unit_types', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(42, 'create_unit_types', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(43, 'edit_unit_types', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(44, 'delete_unit_types', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(45, 'view_unit_models', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(46, 'create_unit_models', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(47, 'edit_unit_models', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(48, 'delete_unit_models', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(49, 'view_chat', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(50, 'create_chat', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(51, 'edit_chat', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(52, 'delete_chat', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(53, 'download_backup', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(54, 'send_chat', 'web', '2026-08-04 08:00:08', '2026-08-04 08:00:08'),
(55, 'view_mechanics', 'web', '2026-08-04 09:08:26', '2026-08-04 09:08:26'),
(56, 'create_mechanics', 'web', '2026-08-04 09:08:26', '2026-08-04 09:08:26'),
(57, 'edit_mechanics', 'web', '2026-08-04 09:08:26', '2026-08-04 09:08:26'),
(58, 'delete_mechanics', 'web', '2026-08-04 09:08:26', '2026-08-04 09:08:26'),
(59, 'view_tool_categories', 'web', '2026-08-04 09:08:26', '2026-08-04 09:08:26'),
(60, 'create_tool_categories', 'web', '2026-08-04 09:08:26', '2026-08-04 09:08:26'),
(61, 'edit_tool_categories', 'web', '2026-08-04 09:08:26', '2026-08-04 09:08:26'),
(62, 'delete_tool_categories', 'web', '2026-08-04 09:08:26', '2026-08-04 09:08:26'),
(63, 'view_tools', 'web', '2026-08-04 09:08:26', '2026-08-04 09:08:26'),
(64, 'create_tools', 'web', '2026-08-04 09:08:26', '2026-08-04 09:08:26'),
(65, 'edit_tools', 'web', '2026-08-04 09:08:26', '2026-08-04 09:08:26'),
(66, 'delete_tools', 'web', '2026-08-04 09:08:26', '2026-08-04 09:08:26'),
(67, 'view_tool_stocks', 'web', '2026-08-04 09:08:26', '2026-08-04 09:08:26'),
(68, 'create_tool_stocks', 'web', '2026-08-04 09:08:26', '2026-08-04 09:08:26'),
(69, 'edit_tool_stocks', 'web', '2026-08-04 09:08:26', '2026-08-04 09:08:26'),
(70, 'delete_tool_stocks', 'web', '2026-08-04 09:08:26', '2026-08-04 09:08:26'),
(71, 'view_tool_transactions', 'web', '2026-08-04 09:08:26', '2026-08-04 09:08:26'),
(72, 'create_tool_transactions', 'web', '2026-08-04 09:08:26', '2026-08-04 09:08:26'),
(73, 'edit_tool_transactions', 'web', '2026-08-04 09:08:26', '2026-08-04 09:08:26'),
(74, 'delete_tool_transactions', 'web', '2026-08-04 09:08:26', '2026-08-04 09:08:26'),
(75, 'view_incident_reports', 'web', '2026-08-04 09:08:26', '2026-08-04 09:08:26'),
(76, 'create_incident_reports', 'web', '2026-08-04 09:08:26', '2026-08-04 09:08:26'),
(77, 'edit_incident_reports', 'web', '2026-08-04 09:08:26', '2026-08-04 09:08:26'),
(78, 'delete_incident_reports', 'web', '2026-08-04 09:08:26', '2026-08-04 09:08:26'),
(79, 'view_stock_opnames', 'web', '2026-08-04 09:08:26', '2026-08-04 09:08:26'),
(80, 'create_stock_opnames', 'web', '2026-08-04 09:08:26', '2026-08-04 09:08:26'),
(81, 'edit_stock_opnames', 'web', '2026-08-04 09:08:26', '2026-08-04 09:08:26'),
(82, 'delete_stock_opnames', 'web', '2026-08-04 09:08:26', '2026-08-04 09:08:26'),
(83, 'view_work_orders', 'web', '2026-08-04 20:53:44', '2026-08-04 20:53:44'),
(84, 'create_work_orders', 'web', '2026-08-04 20:53:44', '2026-08-04 20:53:44'),
(85, 'edit_work_orders', 'web', '2026-08-04 20:53:44', '2026-08-04 20:53:44'),
(86, 'delete_work_orders', 'web', '2026-08-04 20:53:44', '2026-08-04 20:53:44'),
(87, 'view_parts', 'web', '2026-08-04 20:53:44', '2026-08-04 20:53:44'),
(88, 'create_parts', 'web', '2026-08-04 20:53:44', '2026-08-04 20:53:44'),
(89, 'edit_parts', 'web', '2026-08-04 20:53:44', '2026-08-04 20:53:44'),
(90, 'delete_parts', 'web', '2026-08-04 20:53:44', '2026-08-04 20:53:44'),
(91, 'view_hour_meters', 'web', '2026-08-05 02:49:21', '2026-08-05 02:49:21'),
(92, 'create_hour_meters', 'web', '2026-08-05 02:49:21', '2026-08-05 02:49:21'),
(93, 'edit_hour_meters', 'web', '2026-08-05 02:49:21', '2026-08-05 02:49:21'),
(94, 'delete_hour_meters', 'web', '2026-08-05 02:49:21', '2026-08-05 02:49:21'),
(95, 'view_plan_budgets', 'web', '2026-08-05 23:45:13', '2026-08-05 23:45:13'),
(96, 'create_plan_budgets', 'web', '2026-08-05 23:45:13', '2026-08-05 23:45:13'),
(97, 'edit_plan_budgets', 'web', '2026-08-05 23:45:13', '2026-08-05 23:45:13'),
(98, 'delete_plan_budgets', 'web', '2026-08-05 23:45:13', '2026-08-05 23:45:13'),
(99, 'view_sites', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(100, 'create_sites', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(101, 'edit_sites', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(102, 'delete_sites', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(103, 'view_productions', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(104, 'create_productions', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(105, 'edit_productions', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(106, 'delete_productions', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(107, 'view_hse_jsas', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(108, 'create_hse_jsas', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(109, 'edit_hse_jsas', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(110, 'delete_hse_jsas', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(111, 'view_hse_ptws', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(112, 'create_hse_ptws', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(113, 'edit_hse_ptws', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(114, 'delete_hse_ptws', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(115, 'view_hse_lotos', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(116, 'create_hse_lotos', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(117, 'edit_hse_lotos', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(118, 'delete_hse_lotos', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(119, 'view_breakdown_types', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(120, 'create_breakdown_types', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(121, 'edit_breakdown_types', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(122, 'delete_breakdown_types', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(123, 'view_component_groups', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(124, 'create_component_groups', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(125, 'edit_component_groups', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(126, 'delete_component_groups', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(127, 'view_wo_categories', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(128, 'create_wo_categories', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(129, 'edit_wo_categories', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(130, 'delete_wo_categories', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(131, 'view_vendors', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(132, 'create_vendors', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(133, 'edit_vendors', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(134, 'delete_vendors', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(135, 'view_pm_templates', 'web', '2026-08-08 07:47:43', '2026-08-08 07:47:43'),
(136, 'create_pm_templates', 'web', '2026-08-08 07:47:44', '2026-08-08 07:47:44'),
(137, 'edit_pm_templates', 'web', '2026-08-08 07:47:44', '2026-08-08 07:47:44'),
(138, 'delete_pm_templates', 'web', '2026-08-08 07:47:44', '2026-08-08 07:47:44'),
(139, 'view_pm_schedules', 'web', '2026-08-08 07:47:44', '2026-08-08 07:47:44'),
(140, 'create_pm_schedules', 'web', '2026-08-08 07:47:44', '2026-08-08 07:47:44'),
(141, 'edit_pm_schedules', 'web', '2026-08-08 07:47:44', '2026-08-08 07:47:44'),
(142, 'delete_pm_schedules', 'web', '2026-08-08 07:47:44', '2026-08-08 07:47:44'),
(143, 'view_pra_work_orders', 'web', '2026-08-08 07:47:44', '2026-08-08 07:47:44'),
(144, 'create_pra_work_orders', 'web', '2026-08-08 07:47:44', '2026-08-08 07:47:44'),
(145, 'edit_pra_work_orders', 'web', '2026-08-08 07:47:44', '2026-08-08 07:47:44'),
(146, 'delete_pra_work_orders', 'web', '2026-08-08 07:47:44', '2026-08-08 07:47:44'),
(147, 'view_jwos', 'web', '2026-08-08 07:47:44', '2026-08-08 07:47:44'),
(148, 'create_jwos', 'web', '2026-08-08 07:47:44', '2026-08-08 07:47:44'),
(149, 'edit_jwos', 'web', '2026-08-08 07:47:44', '2026-08-08 07:47:44'),
(150, 'delete_jwos', 'web', '2026-08-08 07:47:44', '2026-08-08 07:47:44'),
(151, 'view_fars', 'web', '2026-08-08 07:47:44', '2026-08-08 07:47:44'),
(152, 'create_fars', 'web', '2026-08-08 07:47:44', '2026-08-08 07:47:44'),
(153, 'edit_fars', 'web', '2026-08-08 07:47:44', '2026-08-08 07:47:44'),
(154, 'delete_fars', 'web', '2026-08-08 07:47:44', '2026-08-08 07:47:44');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plan_budgets`
--

CREATE TABLE `plan_budgets` (
  `id` bigint UNSIGNED NOT NULL,
  `site_id` bigint UNSIGNED DEFAULT NULL,
  `period` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Draft','Approved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plan_budgets`
--

INSERT INTO `plan_budgets` (`id`, `site_id`, `period`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 2, '2026-09', 'Draft', 1, '2026-08-06 00:25:05', '2026-08-06 00:25:05');

-- --------------------------------------------------------

--
-- Table structure for table `plan_budget_parts`
--

CREATE TABLE `plan_budget_parts` (
  `id` bigint UNSIGNED NOT NULL,
  `plan_budget_unit_id` bigint UNSIGNED NOT NULL,
  `part_id` bigint UNSIGNED NOT NULL,
  `qty` int NOT NULL DEFAULT '1',
  `price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plan_budget_parts`
--

INSERT INTO `plan_budget_parts` (`id`, `plan_budget_unit_id`, `part_id`, `qty`, `price`, `total_price`, `created_at`, `updated_at`) VALUES
(2, 2, 1, 25, 27000.00, 675000.00, '2026-08-06 00:25:05', '2026-08-06 00:25:05');

-- --------------------------------------------------------

--
-- Table structure for table `plan_budget_units`
--

CREATE TABLE `plan_budget_units` (
  `id` bigint UNSIGNED NOT NULL,
  `plan_budget_id` bigint UNSIGNED NOT NULL,
  `master_unit_id` bigint UNSIGNED NOT NULL,
  `target_pa` decimal(5,2) NOT NULL DEFAULT '0.00',
  `planned_cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plan_budget_units`
--

INSERT INTO `plan_budget_units` (`id`, `plan_budget_id`, `master_unit_id`, `target_pa`, `planned_cost`, `created_at`, `updated_at`) VALUES
(2, 2, 64, 80.00, 675000.00, '2026-08-06 00:25:05', '2026-08-06 00:25:05');

-- --------------------------------------------------------

--
-- Table structure for table `pm_schedules`
--

CREATE TABLE `pm_schedules` (
  `id` bigint UNSIGNED NOT NULL,
  `site_id` bigint UNSIGNED DEFAULT NULL,
  `master_unit_id` bigint UNSIGNED NOT NULL,
  `pm_template_id` bigint UNSIGNED NOT NULL,
  `last_executed_value` decimal(10,1) DEFAULT NULL,
  `next_due_value` decimal(10,1) NOT NULL,
  `status_jadwal` enum('Upcoming','Due','Overdue') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Upcoming',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pm_templates`
--

CREATE TABLE `pm_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `site_id` bigint UNSIGNED DEFAULT NULL,
  `unit_model_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `interval_type` enum('hour_meter','kilometer','days') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hour_meter',
  `interval_value` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pm_template_subtasks`
--

CREATE TABLE `pm_template_subtasks` (
  `id` bigint UNSIGNED NOT NULL,
  `pm_template_task_id` bigint UNSIGNED NOT NULL,
  `subtask_name` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `sequence` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pm_template_tasks`
--

CREATE TABLE `pm_template_tasks` (
  `id` bigint UNSIGNED NOT NULL,
  `pm_template_id` bigint UNSIGNED NOT NULL,
  `task_name` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `sequence` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pra_work_orders`
--

CREATE TABLE `pra_work_orders` (
  `id` bigint UNSIGNED NOT NULL,
  `site_id` bigint UNSIGNED DEFAULT NULL,
  `master_unit_id` bigint UNSIGNED NOT NULL,
  `waktu_bd` datetime NOT NULL,
  `hours_meter` decimal(10,1) DEFAULT NULL,
  `lokasi_kerusakan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `problem` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `work_order_id` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pra_work_orders`
--

INSERT INTO `pra_work_orders` (`id`, `site_id`, `master_unit_id`, `waktu_bd`, `hours_meter`, `lokasi_kerusakan`, `problem`, `status`, `work_order_id`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 2, 98, '2026-08-06 07:47:00', 4543.0, 'Pit3', 'banyak masalah hidupnya', 'Generated', NULL, 1, '2026-08-05 17:48:22', '2026-08-05 18:17:59'),
(2, 2, 54, '2026-08-06 11:57:00', 2345.0, 'workshop', 'Tyre Leak', 'Generated', 5, 1, '2026-08-05 19:58:06', '2026-08-05 19:58:25'),
(3, 1, 120, '2026-08-06 12:22:00', 2345.0, 'Pit 3', 'Track Loose', 'Generated', 6, 1, '2026-08-05 20:23:12', '2026-08-05 20:23:27'),
(4, 1, 120, '2026-08-05 12:32:00', 2345.0, 'jety', 'kerusakan aja sech', 'Cancelled', NULL, 1, '2026-08-05 20:32:37', '2026-08-05 21:01:48'),
(5, 1, 49, '2026-08-06 18:31:00', 1223.0, 'Pit 10', 'Track Loose', 'Pending', NULL, 1, '2026-08-06 02:32:55', '2026-08-06 02:32:55');

-- --------------------------------------------------------

--
-- Table structure for table `productions`
--

CREATE TABLE `productions` (
  `id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `shift` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `productions`
--

INSERT INTO `productions` (`id`, `date`, `shift`, `notes`, `created_at`, `updated_at`) VALUES
(1, '2026-08-08', 'NS', NULL, '2026-08-08 06:43:40', '2026-08-08 06:43:40');

-- --------------------------------------------------------

--
-- Table structure for table `production_delays`
--

CREATE TABLE `production_delays` (
  `id` bigint UNSIGNED NOT NULL,
  `production_id` bigint UNSIGNED NOT NULL,
  `production_fleet_id` bigint UNSIGNED DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `delay_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `production_fleets`
--

CREATE TABLE `production_fleets` (
  `id` bigint UNSIGNED NOT NULL,
  `production_id` bigint UNSIGNED NOT NULL,
  `digger_id` bigint UNSIGNED NOT NULL,
  `material_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `distance` decimal(8,2) DEFAULT NULL,
  `target_bcm_per_hour` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `production_fleets`
--

INSERT INTO `production_fleets` (`id`, `production_id`, `digger_id`, `material_type`, `distance`, `target_bcm_per_hour`, `created_at`, `updated_at`) VALUES
(1, 1, 105, 'OB (Overburden)', 0.60, NULL, '2026-08-08 06:43:40', '2026-08-08 06:43:40');

-- --------------------------------------------------------

--
-- Table structure for table `production_haulers`
--

CREATE TABLE `production_haulers` (
  `id` bigint UNSIGNED NOT NULL,
  `production_fleet_id` bigint UNSIGNED NOT NULL,
  `hauler_id` bigint UNSIGNED NOT NULL,
  `payload` decimal(8,2) NOT NULL DEFAULT '0.00',
  `hourly_ritasi` json DEFAULT NULL,
  `total_ritasi` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `production_haulers`
--

INSERT INTO `production_haulers` (`id`, `production_fleet_id`, `hauler_id`, `payload`, `hourly_ritasi`, `total_ritasi`, `created_at`, `updated_at`) VALUES
(1, 1, 225, 40.00, '{\"1\": \"4\", \"2\": null, \"3\": null, \"4\": null, \"5\": null, \"6\": null, \"7\": null, \"8\": null, \"9\": null, \"10\": null, \"11\": null, \"12\": null}', 4, '2026-08-08 06:43:40', '2026-08-08 06:43:40'),
(2, 1, 188, 40.00, '{\"1\": \"4\", \"2\": null, \"3\": null, \"4\": null, \"5\": null, \"6\": null, \"7\": null, \"8\": null, \"9\": null, \"10\": null, \"11\": null, \"12\": null}', 4, '2026-08-08 06:43:41', '2026-08-08 06:43:41');

-- --------------------------------------------------------

--
-- Table structure for table `production_supports`
--

CREATE TABLE `production_supports` (
  `id` bigint UNSIGNED NOT NULL,
  `production_id` bigint UNSIGNED NOT NULL,
  `support_id` bigint UNSIGNED NOT NULL,
  `hm_awal` decimal(10,2) DEFAULT NULL,
  `hm_akhir` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `production_supports`
--

INSERT INTO `production_supports` (`id`, `production_id`, `support_id`, `hm_awal`, `hm_akhir`, `created_at`, `updated_at`) VALUES
(1, 1, 39, 234.00, 244.75, '2026-08-08 06:43:41', '2026-08-08 06:43:41');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'web', '2026-08-04 04:58:52', '2026-08-04 04:58:52'),
(2, 'Admin', 'web', '2026-08-04 05:37:46', '2026-08-04 05:37:46'),
(3, 'User', 'web', '2026-08-04 05:37:58', '2026-08-04 05:37:58'),
(4, 'Guest', 'web', '2026-08-05 03:08:14', '2026-08-05 03:08:14'),
(5, 'Supervisor', 'web', '2026-08-06 07:47:43', '2026-08-06 07:47:43'),
(6, 'Foreman', 'web', '2026-08-06 07:54:21', '2026-08-06 07:54:21'),
(7, 'Superintendent', 'web', '2026-08-06 07:58:27', '2026-08-06 07:58:27');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(56, 1),
(57, 1),
(58, 1),
(59, 1),
(60, 1),
(61, 1),
(62, 1),
(63, 1),
(64, 1),
(65, 1),
(66, 1),
(67, 1),
(68, 1),
(69, 1),
(70, 1),
(71, 1),
(72, 1),
(73, 1),
(74, 1),
(75, 1),
(76, 1),
(77, 1),
(78, 1),
(79, 1),
(80, 1),
(81, 1),
(82, 1),
(83, 1),
(84, 1),
(85, 1),
(86, 1),
(87, 1),
(88, 1),
(89, 1),
(90, 1),
(91, 1),
(92, 1),
(93, 1),
(94, 1),
(95, 1),
(96, 1),
(97, 1),
(98, 1),
(99, 1),
(100, 1),
(101, 1),
(102, 1),
(103, 1),
(104, 1),
(105, 1),
(106, 1),
(107, 1),
(108, 1),
(109, 1),
(110, 1),
(111, 1),
(112, 1),
(113, 1),
(114, 1),
(115, 1),
(116, 1),
(117, 1),
(118, 1),
(119, 1),
(120, 1),
(121, 1),
(122, 1),
(123, 1),
(124, 1),
(125, 1),
(126, 1),
(127, 1),
(128, 1),
(129, 1),
(130, 1),
(131, 1),
(132, 1),
(133, 1),
(134, 1),
(135, 1),
(136, 1),
(137, 1),
(138, 1),
(139, 1),
(140, 1),
(141, 1),
(142, 1),
(143, 1),
(144, 1),
(145, 1),
(146, 1),
(147, 1),
(148, 1),
(149, 1),
(150, 1),
(151, 1),
(152, 1),
(153, 1),
(154, 1),
(1, 2),
(5, 2),
(9, 2),
(13, 2),
(17, 2),
(21, 2),
(25, 2),
(29, 2),
(30, 2),
(33, 2),
(37, 2),
(38, 2),
(39, 2),
(41, 2),
(42, 2),
(43, 2),
(45, 2),
(46, 2),
(47, 2),
(49, 2),
(50, 2),
(52, 2),
(53, 2),
(54, 2),
(55, 2),
(56, 2),
(57, 2),
(59, 2),
(60, 2),
(61, 2),
(63, 2),
(64, 2),
(65, 2),
(67, 2),
(68, 2),
(71, 2),
(72, 2),
(75, 2),
(76, 2),
(77, 2),
(79, 2),
(80, 2),
(83, 2),
(84, 2),
(85, 2),
(87, 2),
(88, 2),
(89, 2),
(25, 4),
(29, 4),
(33, 4),
(37, 4),
(49, 4),
(50, 4),
(52, 4),
(55, 4),
(63, 4),
(67, 4),
(71, 4),
(75, 4),
(79, 4),
(83, 4),
(87, 4),
(25, 5),
(37, 5),
(49, 5),
(50, 5),
(55, 5),
(56, 5),
(57, 5),
(63, 5),
(67, 5),
(71, 5),
(75, 5),
(79, 5),
(83, 5),
(85, 5),
(87, 5),
(9, 6),
(25, 6),
(37, 6),
(49, 6),
(50, 6),
(51, 6),
(55, 6),
(63, 6),
(71, 6),
(75, 6),
(79, 6),
(83, 6),
(84, 6),
(87, 6),
(1, 7),
(25, 7),
(29, 7),
(37, 7),
(49, 7),
(50, 7),
(51, 7),
(55, 7),
(56, 7),
(57, 7),
(59, 7),
(63, 7),
(64, 7),
(65, 7),
(67, 7),
(71, 7),
(75, 7),
(79, 7),
(83, 7),
(85, 7),
(87, 7);

-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

CREATE TABLE `sites` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sites`
--

INSERT INTO `sites` (`id`, `name`, `code`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Bukit Baiduri Energi', 'BBE', NULL, '2026-08-05 00:01:12', '2026-08-05 00:29:37'),
(2, 'Harindo Wahana', 'HW', 'Lokasi di Tering Kubar', '2026-08-05 00:22:33', '2026-08-05 00:22:33');

-- --------------------------------------------------------

--
-- Table structure for table `stock_opnames`
--

CREATE TABLE `stock_opnames` (
  `id` bigint UNSIGNED NOT NULL,
  `tanggal_audit` timestamp NOT NULL,
  `tipe_audit` enum('ToolRoom','Mechanic') COLLATE utf8mb4_unicode_ci NOT NULL,
  `mechanic_id` bigint UNSIGNED DEFAULT NULL,
  `auditor_user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_opname_details`
--

CREATE TABLE `stock_opname_details` (
  `id` bigint UNSIGNED NOT NULL,
  `stock_opname_id` bigint UNSIGNED NOT NULL,
  `tool_id` bigint UNSIGNED NOT NULL,
  `stok_sistem` int NOT NULL DEFAULT '0',
  `stok_fisik` int NOT NULL DEFAULT '0',
  `selisih` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tools`
--

CREATE TABLE `tools` (
  `id` bigint UNSIGNED NOT NULL,
  `tool_category_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `spesifikasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `site_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tool_categories`
--

CREATE TABLE `tool_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tool_stocks`
--

CREATE TABLE `tool_stocks` (
  `id` bigint UNSIGNED NOT NULL,
  `tool_id` bigint UNSIGNED NOT NULL,
  `location_type` enum('ToolRoom','Mechanic') COLLATE utf8mb4_unicode_ci NOT NULL,
  `mechanic_id` bigint UNSIGNED DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tool_transactions`
--

CREATE TABLE `tool_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `tool_id` bigint UNSIGNED NOT NULL,
  `mechanic_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `tipe_transaksi` enum('Pinjam Sementara','Pinjam Permanen') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_pinjam` timestamp NOT NULL,
  `borrow_qty` int NOT NULL,
  `tanggal_kembali` timestamp NULL DEFAULT NULL,
  `returned_good_qty` int NOT NULL DEFAULT '0',
  `returned_broken_qty` int NOT NULL DEFAULT '0',
  `returned_lost_qty` int NOT NULL DEFAULT '0',
  `status` enum('Borrowed','Returned') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Borrowed',
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `unit_models`
--

CREATE TABLE `unit_models` (
  `id` bigint UNSIGNED NOT NULL,
  `unit_type_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `unit_models`
--

INSERT INTO `unit_models` (`id`, `unit_type_id`, `name`, `created_at`, `updated_at`) VALUES
(2, 3, 'Mitsubushi Triton', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(3, 3, 'Light Vehicle', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(4, 3, 'Passanger', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(5, 4, 'Hino', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(6, 4, 'Mitsubishi', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(7, 5, 'DOMFENG', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(8, 6, 'CS11GC', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(9, 6, 'SSR220C-8H', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(10, 6, 'SSR260C-8', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(11, 7, 'HINO 260 TI', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(12, 8, 'D85ESS', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(13, 9, '375A', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(14, 10, 'T50', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(15, 10, 'L100-E3', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(16, 11, 'MERCY 2528 AXOR', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(17, 11, 'QUESTER CWE 280', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(18, 12, 'SY750H', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(19, 13, '365 H PRO', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(20, 14, 'SY500H', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(21, 14, 'R210W-9S', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(22, 12, '395', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(23, 14, '320GC', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(24, 14, '330GC', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(25, 14, 'ZX200', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(26, 14, 'ZX350', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(27, 12, 'ZX870LCH-5G', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(28, 12, 'PC 500 KOMATSU', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(29, 12, '374', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(30, 14, 'PC-500', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(31, 12, 'SY 500 SANNY', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(32, 14, 'ZX200-5G', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(33, 12, '6015B', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(34, 12, '1250SP-11R', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(35, 14, 'DX530LC 7M', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(36, 14, 'ZX350H-7G', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(37, 14, 'PC 200 -10', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(38, 15, 'NISSAN QUESTER CWE 280', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(39, 15, 'FM 280 JD', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(40, 16, '922 AWD', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(41, 16, 'GD755-5', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(42, 16, '14', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(43, 16, '16 GC', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(44, 16, 'GD825A', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(45, 17, 'OLYMPIAN', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(46, 22, 'YANMAR', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(47, 17, 'GENSET CUMMING', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(48, 23, 'FAW HD380CG', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(49, 4, 'FAW', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(50, 19, 'PRAMAC', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(51, 19, 'HIMOINSA', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(52, 21, '655D', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(53, 21, '660D', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(54, 21, '676D', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(55, 22, 'MILLER BIG BLUE 500', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(56, 20, 'WATER PUMP', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(57, 20, 'WATER PUMP MF390', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(58, 20, 'WATER PUMP MF385', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(59, 20, 'WATER PUMP MF420', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(60, 20, 'WATER PUMP MF400', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(61, 23, 'FUSO FN62', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(62, 23, 'LGMG CMS50', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(63, 23, 'TONLY TLS 753', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(64, 23, 'LGMG CMT96', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(65, 24, '773E', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(66, 24, '773', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(67, 3, 'Pajero Sport 2.5L', '2026-08-05 10:54:55', '2026-08-05 10:54:55');

-- --------------------------------------------------------

--
-- Table structure for table `unit_types`
--

CREATE TABLE `unit_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `unit_types`
--

INSERT INTO `unit_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(3, 'Light Vehicle', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(4, 'SARANA / BUS', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(5, 'COMPRESSOR', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(6, 'COMPACTOR', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(7, 'CRANE TRUCK', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(8, 'SMALL DOZER', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(9, 'BIG DOZER', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(10, 'DRILLING', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(11, 'DUMP TRUCK', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(12, 'BIG DIGGER', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(13, 'EXCAVATOR CRUISHER', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(14, 'SMALL DIGGER', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(15, 'FUEL TRUCK', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(16, 'MOTOR GRADER', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(17, 'GENSET', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(18, 'LUBE TRUCK', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(19, 'TOWER LAMP', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(20, 'DEWATERING PUMP', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(21, 'WHEEL LOADER', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(22, 'WELDING MACHINE', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(23, 'WATER TRUCK', '2026-08-05 10:54:55', '2026-08-05 10:54:55'),
(24, 'HAULER', '2026-08-05 10:54:55', '2026-08-05 10:54:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `nik` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_lengkap` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_whatsapp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `department_id` bigint UNSIGNED DEFAULT NULL,
  `jabatan_id` bigint UNSIGNED DEFAULT NULL,
  `role_id` bigint UNSIGNED DEFAULT NULL,
  `status` enum('pending','active','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `site_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nik`, `nama_lengkap`, `email`, `avatar`, `email_verified_at`, `password`, `no_whatsapp`, `bio`, `department_id`, `jabatan_id`, `role_id`, `status`, `remember_token`, `created_at`, `updated_at`, `deleted_at`, `site_id`) VALUES
(1, 'ADMIN001', 'Super Administrator', 'admin@cmms-aisfar.com', 'avatar_1_1785979300.png', NULL, '$2y$12$exzVEzBxkch/deiuChdlqubUybWdmcdhmVfVxbWw4FSceh4E4Vs8W', '081234567890', NULL, 1, NULL, NULL, 'active', 'JURh8jqefkAKGM5w9oPTQdeweuFl9mviN7VT5x5crUEkNic6zVXfRkjfsxUL', '2026-08-04 04:58:53', '2026-08-05 17:21:40', NULL, NULL),
(2, 'NIK002', 'Admin', 'admin02@cmms-aisfar.com', NULL, NULL, '$2y$12$t7oxEAfkjO/uoiuS1cC.CupwHnZgX2v7pPcBbbCx/i7nCtCGKrdv.', '085247953300', NULL, NULL, NULL, NULL, 'active', NULL, '2026-08-04 07:43:04', '2026-08-04 08:26:45', NULL, NULL),
(3, 'NIK00123', 'Admin BBE', 'bbe@cmms-aisfar.com', NULL, NULL, '$2y$12$uef3p56J0HuEXEcuMFCY7.r2.UP5ReJCz.vDh.ybd.5yI9DtoBPlC', '085247953311', NULL, 1, 4, NULL, 'active', NULL, '2026-08-05 06:57:17', '2026-08-05 07:05:34', NULL, 1),
(4, 'NIK-SPVBBE', 'Supervisor BBE', 'spvbbe@cmms-aisfar.com', NULL, NULL, '$2y$12$2eLAID8wqGo77WvfS225aeo8zK049wuiNsc.LVA0SsMmMJMMDWXZa', '085247953322', NULL, 1, 3, NULL, 'active', NULL, '2026-08-06 07:44:42', '2026-08-06 07:49:03', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `name`, `address`, `contact_person`, `phone`, `email`, `site_id`, `created_at`, `updated_at`) VALUES
(1, 'PT ATE', NULL, NULL, NULL, NULL, NULL, '2026-08-06 08:16:51', '2026-08-06 08:16:51');

-- --------------------------------------------------------

--
-- Table structure for table `work_orders`
--

CREATE TABLE `work_orders` (
  `id` bigint UNSIGNED NOT NULL,
  `no_wo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_wo` enum('Open','Inprogress','Completed','Cancel','Backlog') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Open',
  `tipe_wo` enum('BD','Plan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BD',
  `downtime_code` enum('Schedule','Unschedule','Accident') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Unschedule',
  `opportunity` tinyint(1) NOT NULL DEFAULT '0',
  `master_unit_id` bigint UNSIGNED NOT NULL,
  `hours_meter` decimal(10,1) DEFAULT NULL,
  `lokasi_kerusakan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `waktu_bd` datetime DEFAULT NULL,
  `waktu_rfu` datetime DEFAULT NULL,
  `breakdown_type_id` bigint UNSIGNED DEFAULT NULL,
  `component_group_id` bigint UNSIGNED DEFAULT NULL,
  `wo_category_1_id` bigint UNSIGNED DEFAULT NULL,
  `wo_category_2_id` bigint UNSIGNED DEFAULT NULL,
  `wo_category_3_id` bigint UNSIGNED DEFAULT NULL,
  `wo_category_4_id` bigint UNSIGNED DEFAULT NULL,
  `wo_category_5_id` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `site_id` bigint UNSIGNED DEFAULT NULL,
  `pm_schedule_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `work_orders`
--

INSERT INTO `work_orders` (`id`, `no_wo`, `status_wo`, `tipe_wo`, `downtime_code`, `opportunity`, `master_unit_id`, `hours_meter`, `lokasi_kerusakan`, `waktu_bd`, `waktu_rfu`, `breakdown_type_id`, `component_group_id`, `wo_category_1_id`, `wo_category_2_id`, `wo_category_3_id`, `wo_category_4_id`, `wo_category_5_id`, `created_by`, `created_at`, `updated_at`, `site_id`, `pm_schedule_id`) VALUES
(2, 'WO-08-26-0001', 'Open', 'BD', 'Unschedule', 0, 82, NULL, NULL, '2026-08-05 09:26:00', NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, 1, '2026-08-05 17:27:06', '2026-08-05 17:27:06', NULL, NULL),
(5, 'WO-08-26-0003', 'Open', 'BD', 'Unschedule', 0, 54, 2345.0, NULL, '2026-08-06 11:57:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-08-05 19:58:25', '2026-08-05 21:17:04', NULL, NULL),
(6, 'WO-08-26-0004', 'Open', 'BD', 'Unschedule', 0, 120, 2345.0, 'Pit 3', '2026-08-04 12:22:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-08-05 20:23:27', '2026-08-05 22:54:17', NULL, NULL),
(7, 'WO-08-26-0005', 'Open', 'BD', 'Unschedule', 0, 83, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, 1, '2026-08-08 04:45:29', '2026-08-08 04:45:29', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wo_categories`
--

CREATE TABLE `wo_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `level` tinyint NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wo_categories`
--

INSERT INTO `wo_categories` (`id`, `level`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Service', '2026-08-08 04:44:47', '2026-08-08 04:44:47'),
(2, 1, 'Greasing', '2026-08-08 04:45:11', '2026-08-08 04:45:11');

-- --------------------------------------------------------

--
-- Table structure for table `wo_subtasks`
--

CREATE TABLE `wo_subtasks` (
  `id` bigint UNSIGNED NOT NULL,
  `wo_task_id` bigint UNSIGNED NOT NULL,
  `action` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_action` datetime DEFAULT NULL,
  `status` enum('Open','Inprogress','Completed','Cancel','Backlog') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wo_subtasks`
--

INSERT INTO `wo_subtasks` (`id`, `wo_task_id`, `action`, `date_action`, `status`, `created_at`, `updated_at`) VALUES
(3, 8, 'di perbaiki', NULL, 'Open', '2026-08-05 23:43:46', '2026-08-05 23:43:46'),
(4, 9, 'Connect Track', '2026-08-05 18:41:00', 'Open', '2026-08-06 02:42:04', '2026-08-06 02:42:04');

-- --------------------------------------------------------

--
-- Table structure for table `wo_subtask_manpower`
--

CREATE TABLE `wo_subtask_manpower` (
  `id` bigint UNSIGNED NOT NULL,
  `wo_subtask_id` bigint UNSIGNED NOT NULL,
  `mechanic_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wo_subtask_manpower`
--

INSERT INTO `wo_subtask_manpower` (`id`, `wo_subtask_id`, `mechanic_id`, `created_at`, `updated_at`) VALUES
(3, 3, 1, '2026-08-05 23:43:46', '2026-08-05 23:43:46'),
(4, 4, 1, '2026-08-06 02:42:04', '2026-08-06 02:42:04');

-- --------------------------------------------------------

--
-- Table structure for table `wo_subtask_parts`
--

CREATE TABLE `wo_subtask_parts` (
  `id` bigint UNSIGNED NOT NULL,
  `wo_subtask_id` bigint UNSIGNED NOT NULL,
  `part_id` bigint UNSIGNED NOT NULL,
  `qty` int NOT NULL DEFAULT '1',
  `satuan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wo_subtask_parts`
--

INSERT INTO `wo_subtask_parts` (`id`, `wo_subtask_id`, `part_id`, `qty`, `satuan`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 20, NULL, '2026-08-05 23:43:46', '2026-08-05 23:43:46');

-- --------------------------------------------------------

--
-- Table structure for table `wo_subtask_tools`
--

CREATE TABLE `wo_subtask_tools` (
  `id` bigint UNSIGNED NOT NULL,
  `wo_subtask_id` bigint UNSIGNED NOT NULL,
  `tool_transaction_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wo_tasks`
--

CREATE TABLE `wo_tasks` (
  `id` bigint UNSIGNED NOT NULL,
  `work_order_id` bigint UNSIGNED NOT NULL,
  `problem` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `component_group_id` bigint UNSIGNED DEFAULT NULL,
  `date_problem` datetime DEFAULT NULL,
  `status` enum('Open','Inprogress','Completed','Cancel','Backlog') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wo_tasks`
--

INSERT INTO `wo_tasks` (`id`, `work_order_id`, `problem`, `component_group_id`, `date_problem`, `status`, `created_at`, `updated_at`) VALUES
(5, 5, 'Tyre Leak', NULL, NULL, 'Open', '2026-08-05 19:58:25', '2026-08-05 19:58:25'),
(8, 2, 'Rusak', 1, '2026-08-06 09:26:00', 'Open', '2026-08-05 23:43:46', '2026-08-05 23:43:46'),
(9, 6, 'Track Loose', 2, '2026-08-05 18:40:00', 'Open', '2026-08-06 02:42:04', '2026-08-06 02:42:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject` (`subject_type`,`subject_id`),
  ADD KEY `causer` (`causer_type`,`causer_id`),
  ADD KEY `activity_log_log_name_index` (`log_name`);

--
-- Indexes for table `approval_matrices`
--
ALTER TABLE `approval_matrices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_settings`
--
ALTER TABLE `app_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `app_settings_key_unique` (`key`);

--
-- Indexes for table `breakdown_types`
--
ALTER TABLE `breakdown_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `breakdown_types_name_unique` (`name`);

--
-- Indexes for table `component_groups`
--
ALTER TABLE `component_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `component_groups_name_unique` (`name`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `document_signatures`
--
ALTER TABLE `document_signatures`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `doc_sign_unique` (`document_type`,`document_id`,`sign_type`),
  ADD KEY `document_signatures_document_type_document_id_index` (`document_type`,`document_id`),
  ADD KEY `document_signatures_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `fars`
--
ALTER TABLE `fars`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fars_no_far_unique` (`no_far`),
  ADD KEY `fars_site_id_foreign` (`site_id`),
  ADD KEY `fars_work_order_id_foreign` (`work_order_id`),
  ADD KEY `fars_master_unit_id_foreign` (`master_unit_id`),
  ADD KEY `fars_reported_by_foreign` (`reported_by`);

--
-- Indexes for table `far_attachments`
--
ALTER TABLE `far_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `far_attachments_far_id_foreign` (`far_id`);

--
-- Indexes for table `hour_meters`
--
ALTER TABLE `hour_meters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_hm_site_date` (`site_id`,`date`),
  ADD KEY `idx_hm_unit_date` (`master_unit_id`,`date`),
  ADD KEY `idx_hm_date` (`date`);

--
-- Indexes for table `hse_jsas`
--
ALTER TABLE `hse_jsas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hse_jsas_work_order_id_foreign` (`work_order_id`),
  ADD KEY `hse_jsas_site_id_foreign` (`site_id`),
  ADD KEY `hse_jsas_created_by_foreign` (`created_by`),
  ADD KEY `hse_jsas_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `hse_jsa_steps`
--
ALTER TABLE `hse_jsa_steps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hse_jsa_steps_hse_jsa_id_foreign` (`hse_jsa_id`);

--
-- Indexes for table `hse_lotos`
--
ALTER TABLE `hse_lotos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hse_lotos_work_order_id_foreign` (`work_order_id`),
  ADD KEY `hse_lotos_site_id_foreign` (`site_id`),
  ADD KEY `hse_lotos_applied_by_foreign` (`applied_by`),
  ADD KEY `hse_lotos_removed_by_foreign` (`removed_by`);

--
-- Indexes for table `hse_ptws`
--
ALTER TABLE `hse_ptws`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hse_ptws_work_order_id_foreign` (`work_order_id`),
  ADD KEY `hse_ptws_site_id_foreign` (`site_id`),
  ADD KEY `hse_ptws_applicant_id_foreign` (`applicant_id`),
  ADD KEY `hse_ptws_approver_id_foreign` (`approver_id`);

--
-- Indexes for table `incident_reports`
--
ALTER TABLE `incident_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `incident_reports_tool_transaction_id_foreign` (`tool_transaction_id`),
  ADD KEY `incident_reports_mechanic_id_foreign` (`mechanic_id`),
  ADD KEY `incident_reports_approved_by_foreign` (`approved_by`),
  ADD KEY `idx_ir_created_at` (`created_at`),
  ADD KEY `idx_ir_status_approval` (`status_approval`);

--
-- Indexes for table `jabatans`
--
ALTER TABLE `jabatans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jwos`
--
ALTER TABLE `jwos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `jwos_no_jwo_unique` (`no_jwo`),
  ADD KEY `jwos_vendor_id_foreign` (`vendor_id`),
  ADD KEY `jwos_work_order_id_foreign` (`work_order_id`),
  ADD KEY `jwos_unit_id_foreign` (`unit_id`),
  ADD KEY `jwos_component_group_id_foreign` (`component_group_id`),
  ADD KEY `jwos_part_id_foreign` (`part_id`),
  ADD KEY `jwos_site_id_foreign` (`site_id`),
  ADD KEY `jwos_created_by_foreign` (`created_by`);

--
-- Indexes for table `master_units`
--
ALTER TABLE `master_units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `master_units_nomor_unit_unique` (`nomor_unit`),
  ADD KEY `master_units_unit_type_id_foreign` (`unit_type_id`),
  ADD KEY `idx_mu_site` (`site_id`),
  ADD KEY `idx_mu_model` (`unit_model_id`);

--
-- Indexes for table `mechanics`
--
ALTER TABLE `mechanics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mechanics_jabatan_id_foreign` (`jabatan_id`),
  ADD KEY `mechanics_site_id_foreign` (`site_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_sender_id_receiver_id_index` (`sender_id`,`receiver_id`),
  ADD KEY `idx_msg_receiver_read` (`receiver_id`,`read_at`),
  ADD KEY `idx_msg_sender` (`sender_id`);

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
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `modules_name_unique` (`name`);

--
-- Indexes for table `parts`
--
ALTER TABLE `parts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `parts_part_number_unique` (`part_number`),
  ADD KEY `parts_site_id_foreign` (`site_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

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
-- Indexes for table `plan_budgets`
--
ALTER TABLE `plan_budgets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plan_budgets_site_id_foreign` (`site_id`),
  ADD KEY `plan_budgets_created_by_foreign` (`created_by`);

--
-- Indexes for table `plan_budget_parts`
--
ALTER TABLE `plan_budget_parts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plan_budget_parts_plan_budget_unit_id_foreign` (`plan_budget_unit_id`),
  ADD KEY `plan_budget_parts_part_id_foreign` (`part_id`);

--
-- Indexes for table `plan_budget_units`
--
ALTER TABLE `plan_budget_units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plan_budget_units_plan_budget_id_foreign` (`plan_budget_id`),
  ADD KEY `plan_budget_units_master_unit_id_foreign` (`master_unit_id`);

--
-- Indexes for table `pm_schedules`
--
ALTER TABLE `pm_schedules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pm_schedules_master_unit_id_pm_template_id_unique` (`master_unit_id`,`pm_template_id`),
  ADD KEY `pm_schedules_site_id_foreign` (`site_id`),
  ADD KEY `pm_schedules_pm_template_id_foreign` (`pm_template_id`);

--
-- Indexes for table `pm_templates`
--
ALTER TABLE `pm_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pm_templates_unit_model_id_foreign` (`unit_model_id`),
  ADD KEY `pm_templates_site_id_foreign` (`site_id`);

--
-- Indexes for table `pm_template_subtasks`
--
ALTER TABLE `pm_template_subtasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pm_template_subtasks_pm_template_task_id_foreign` (`pm_template_task_id`);

--
-- Indexes for table `pm_template_tasks`
--
ALTER TABLE `pm_template_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pm_template_tasks_pm_template_id_foreign` (`pm_template_id`);

--
-- Indexes for table `pra_work_orders`
--
ALTER TABLE `pra_work_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pra_work_orders_site_id_foreign` (`site_id`),
  ADD KEY `pra_work_orders_master_unit_id_foreign` (`master_unit_id`),
  ADD KEY `pra_work_orders_work_order_id_foreign` (`work_order_id`),
  ADD KEY `pra_work_orders_created_by_foreign` (`created_by`);

--
-- Indexes for table `productions`
--
ALTER TABLE `productions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `production_delays`
--
ALTER TABLE `production_delays`
  ADD PRIMARY KEY (`id`),
  ADD KEY `production_delays_production_id_foreign` (`production_id`),
  ADD KEY `production_delays_production_fleet_id_foreign` (`production_fleet_id`);

--
-- Indexes for table `production_fleets`
--
ALTER TABLE `production_fleets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `production_fleets_production_id_foreign` (`production_id`),
  ADD KEY `production_fleets_digger_id_foreign` (`digger_id`);

--
-- Indexes for table `production_haulers`
--
ALTER TABLE `production_haulers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `production_haulers_production_fleet_id_foreign` (`production_fleet_id`),
  ADD KEY `production_haulers_hauler_id_foreign` (`hauler_id`);

--
-- Indexes for table `production_supports`
--
ALTER TABLE `production_supports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `production_supports_production_id_foreign` (`production_id`),
  ADD KEY `production_supports_support_id_foreign` (`support_id`);

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
-- Indexes for table `sites`
--
ALTER TABLE `sites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sites_name_unique` (`name`),
  ADD UNIQUE KEY `sites_code_unique` (`code`);

--
-- Indexes for table `stock_opnames`
--
ALTER TABLE `stock_opnames`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_opnames_mechanic_id_foreign` (`mechanic_id`),
  ADD KEY `stock_opnames_auditor_user_id_foreign` (`auditor_user_id`);

--
-- Indexes for table `stock_opname_details`
--
ALTER TABLE `stock_opname_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_opname_details_stock_opname_id_foreign` (`stock_opname_id`),
  ADD KEY `stock_opname_details_tool_id_foreign` (`tool_id`);

--
-- Indexes for table `tools`
--
ALTER TABLE `tools`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tools_tool_category_id_foreign` (`tool_category_id`),
  ADD KEY `tools_site_id_foreign` (`site_id`);

--
-- Indexes for table `tool_categories`
--
ALTER TABLE `tool_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tool_stocks`
--
ALTER TABLE `tool_stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tool_stocks_tool_id_foreign` (`tool_id`),
  ADD KEY `tool_stocks_mechanic_id_foreign` (`mechanic_id`);

--
-- Indexes for table `tool_transactions`
--
ALTER TABLE `tool_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tool_transactions_mechanic_id_foreign` (`mechanic_id`),
  ADD KEY `tool_transactions_user_id_foreign` (`user_id`),
  ADD KEY `idx_tt_created_at` (`created_at`),
  ADD KEY `idx_tt_status` (`status`),
  ADD KEY `idx_tt_tool` (`tool_id`);

--
-- Indexes for table `unit_models`
--
ALTER TABLE `unit_models`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unit_models_unit_type_id_foreign` (`unit_type_id`);

--
-- Indexes for table `unit_types`
--
ALTER TABLE `unit_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unit_types_name_unique` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_nik_unique` (`nik`),
  ADD KEY `idx_users_status` (`status`),
  ADD KEY `idx_users_site` (`site_id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendors_site_id_foreign` (`site_id`);

--
-- Indexes for table `work_orders`
--
ALTER TABLE `work_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `work_orders_no_wo_unique` (`no_wo`),
  ADD KEY `work_orders_breakdown_type_id_foreign` (`breakdown_type_id`),
  ADD KEY `work_orders_component_group_id_foreign` (`component_group_id`),
  ADD KEY `work_orders_created_by_foreign` (`created_by`),
  ADD KEY `work_orders_wo_category_1_id_foreign` (`wo_category_1_id`),
  ADD KEY `work_orders_wo_category_2_id_foreign` (`wo_category_2_id`),
  ADD KEY `work_orders_wo_category_3_id_foreign` (`wo_category_3_id`),
  ADD KEY `work_orders_wo_category_4_id_foreign` (`wo_category_4_id`),
  ADD KEY `work_orders_wo_category_5_id_foreign` (`wo_category_5_id`),
  ADD KEY `idx_wo_site_status` (`site_id`,`status_wo`),
  ADD KEY `idx_wo_status_created` (`status_wo`,`created_at`),
  ADD KEY `idx_wo_unit_status` (`master_unit_id`,`status_wo`),
  ADD KEY `idx_wo_created_at` (`created_at`),
  ADD KEY `idx_wo_waktu_bd` (`waktu_bd`),
  ADD KEY `work_orders_pm_schedule_id_foreign` (`pm_schedule_id`);

--
-- Indexes for table `wo_categories`
--
ALTER TABLE `wo_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wo_categories_level_name_unique` (`level`,`name`);

--
-- Indexes for table `wo_subtasks`
--
ALTER TABLE `wo_subtasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wo_subtasks_wo_task_id_foreign` (`wo_task_id`);

--
-- Indexes for table `wo_subtask_manpower`
--
ALTER TABLE `wo_subtask_manpower`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wo_subtask_manpower_wo_subtask_id_foreign` (`wo_subtask_id`),
  ADD KEY `wo_subtask_manpower_mechanic_id_foreign` (`mechanic_id`);

--
-- Indexes for table `wo_subtask_parts`
--
ALTER TABLE `wo_subtask_parts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wo_subtask_parts_wo_subtask_id_foreign` (`wo_subtask_id`),
  ADD KEY `wo_subtask_parts_part_id_foreign` (`part_id`);

--
-- Indexes for table `wo_subtask_tools`
--
ALTER TABLE `wo_subtask_tools`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wo_subtask_tools_wo_subtask_id_foreign` (`wo_subtask_id`),
  ADD KEY `wo_subtask_tools_tool_transaction_id_foreign` (`tool_transaction_id`);

--
-- Indexes for table `wo_tasks`
--
ALTER TABLE `wo_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wo_tasks_work_order_id_foreign` (`work_order_id`),
  ADD KEY `wo_tasks_component_group_id_foreign` (`component_group_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `approval_matrices`
--
ALTER TABLE `approval_matrices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `app_settings`
--
ALTER TABLE `app_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `breakdown_types`
--
ALTER TABLE `breakdown_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `component_groups`
--
ALTER TABLE `component_groups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `document_signatures`
--
ALTER TABLE `document_signatures`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fars`
--
ALTER TABLE `fars`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `far_attachments`
--
ALTER TABLE `far_attachments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hour_meters`
--
ALTER TABLE `hour_meters`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hse_jsas`
--
ALTER TABLE `hse_jsas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hse_jsa_steps`
--
ALTER TABLE `hse_jsa_steps`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hse_lotos`
--
ALTER TABLE `hse_lotos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hse_ptws`
--
ALTER TABLE `hse_ptws`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `incident_reports`
--
ALTER TABLE `incident_reports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jabatans`
--
ALTER TABLE `jabatans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jwos`
--
ALTER TABLE `jwos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `master_units`
--
ALTER TABLE `master_units`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=230;

--
-- AUTO_INCREMENT for table `mechanics`
--
ALTER TABLE `mechanics`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `parts`
--
ALTER TABLE `parts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plan_budgets`
--
ALTER TABLE `plan_budgets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `plan_budget_parts`
--
ALTER TABLE `plan_budget_parts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `plan_budget_units`
--
ALTER TABLE `plan_budget_units`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pm_schedules`
--
ALTER TABLE `pm_schedules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pm_templates`
--
ALTER TABLE `pm_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pm_template_subtasks`
--
ALTER TABLE `pm_template_subtasks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pm_template_tasks`
--
ALTER TABLE `pm_template_tasks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pra_work_orders`
--
ALTER TABLE `pra_work_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `productions`
--
ALTER TABLE `productions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `production_delays`
--
ALTER TABLE `production_delays`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `production_fleets`
--
ALTER TABLE `production_fleets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `production_haulers`
--
ALTER TABLE `production_haulers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `production_supports`
--
ALTER TABLE `production_supports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sites`
--
ALTER TABLE `sites`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stock_opnames`
--
ALTER TABLE `stock_opnames`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_opname_details`
--
ALTER TABLE `stock_opname_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tools`
--
ALTER TABLE `tools`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tool_categories`
--
ALTER TABLE `tool_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tool_stocks`
--
ALTER TABLE `tool_stocks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tool_transactions`
--
ALTER TABLE `tool_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `unit_models`
--
ALTER TABLE `unit_models`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `unit_types`
--
ALTER TABLE `unit_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `work_orders`
--
ALTER TABLE `work_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `wo_categories`
--
ALTER TABLE `wo_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wo_subtasks`
--
ALTER TABLE `wo_subtasks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `wo_subtask_manpower`
--
ALTER TABLE `wo_subtask_manpower`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `wo_subtask_parts`
--
ALTER TABLE `wo_subtask_parts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wo_subtask_tools`
--
ALTER TABLE `wo_subtask_tools`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wo_tasks`
--
ALTER TABLE `wo_tasks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `document_signatures`
--
ALTER TABLE `document_signatures`
  ADD CONSTRAINT `document_signatures_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fars`
--
ALTER TABLE `fars`
  ADD CONSTRAINT `fars_master_unit_id_foreign` FOREIGN KEY (`master_unit_id`) REFERENCES `master_units` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fars_reported_by_foreign` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fars_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fars_work_order_id_foreign` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `far_attachments`
--
ALTER TABLE `far_attachments`
  ADD CONSTRAINT `far_attachments_far_id_foreign` FOREIGN KEY (`far_id`) REFERENCES `fars` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hour_meters`
--
ALTER TABLE `hour_meters`
  ADD CONSTRAINT `hour_meters_master_unit_id_foreign` FOREIGN KEY (`master_unit_id`) REFERENCES `master_units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hour_meters_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `hse_jsas`
--
ALTER TABLE `hse_jsas`
  ADD CONSTRAINT `hse_jsas_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `hse_jsas_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `hse_jsas_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hse_jsas_work_order_id_foreign` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hse_jsa_steps`
--
ALTER TABLE `hse_jsa_steps`
  ADD CONSTRAINT `hse_jsa_steps_hse_jsa_id_foreign` FOREIGN KEY (`hse_jsa_id`) REFERENCES `hse_jsas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hse_lotos`
--
ALTER TABLE `hse_lotos`
  ADD CONSTRAINT `hse_lotos_applied_by_foreign` FOREIGN KEY (`applied_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `hse_lotos_removed_by_foreign` FOREIGN KEY (`removed_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `hse_lotos_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hse_lotos_work_order_id_foreign` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hse_ptws`
--
ALTER TABLE `hse_ptws`
  ADD CONSTRAINT `hse_ptws_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `hse_ptws_approver_id_foreign` FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `hse_ptws_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hse_ptws_work_order_id_foreign` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `incident_reports`
--
ALTER TABLE `incident_reports`
  ADD CONSTRAINT `incident_reports_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `incident_reports_mechanic_id_foreign` FOREIGN KEY (`mechanic_id`) REFERENCES `mechanics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `incident_reports_tool_transaction_id_foreign` FOREIGN KEY (`tool_transaction_id`) REFERENCES `tool_transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jwos`
--
ALTER TABLE `jwos`
  ADD CONSTRAINT `jwos_component_group_id_foreign` FOREIGN KEY (`component_group_id`) REFERENCES `component_groups` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `jwos_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `jwos_part_id_foreign` FOREIGN KEY (`part_id`) REFERENCES `parts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `jwos_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `jwos_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `master_units` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `jwos_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `jwos_work_order_id_foreign` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `master_units`
--
ALTER TABLE `master_units`
  ADD CONSTRAINT `master_units_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `master_units_unit_model_id_foreign` FOREIGN KEY (`unit_model_id`) REFERENCES `unit_models` (`id`),
  ADD CONSTRAINT `master_units_unit_type_id_foreign` FOREIGN KEY (`unit_type_id`) REFERENCES `unit_types` (`id`);

--
-- Constraints for table `mechanics`
--
ALTER TABLE `mechanics`
  ADD CONSTRAINT `mechanics_jabatan_id_foreign` FOREIGN KEY (`jabatan_id`) REFERENCES `jabatans` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `mechanics_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `parts`
--
ALTER TABLE `parts`
  ADD CONSTRAINT `parts_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `plan_budgets`
--
ALTER TABLE `plan_budgets`
  ADD CONSTRAINT `plan_budgets_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `plan_budgets_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `plan_budget_parts`
--
ALTER TABLE `plan_budget_parts`
  ADD CONSTRAINT `plan_budget_parts_part_id_foreign` FOREIGN KEY (`part_id`) REFERENCES `parts` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `plan_budget_parts_plan_budget_unit_id_foreign` FOREIGN KEY (`plan_budget_unit_id`) REFERENCES `plan_budget_units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `plan_budget_units`
--
ALTER TABLE `plan_budget_units`
  ADD CONSTRAINT `plan_budget_units_master_unit_id_foreign` FOREIGN KEY (`master_unit_id`) REFERENCES `master_units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `plan_budget_units_plan_budget_id_foreign` FOREIGN KEY (`plan_budget_id`) REFERENCES `plan_budgets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pm_schedules`
--
ALTER TABLE `pm_schedules`
  ADD CONSTRAINT `pm_schedules_master_unit_id_foreign` FOREIGN KEY (`master_unit_id`) REFERENCES `master_units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pm_schedules_pm_template_id_foreign` FOREIGN KEY (`pm_template_id`) REFERENCES `pm_templates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pm_schedules_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `pm_templates`
--
ALTER TABLE `pm_templates`
  ADD CONSTRAINT `pm_templates_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pm_templates_unit_model_id_foreign` FOREIGN KEY (`unit_model_id`) REFERENCES `unit_models` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pm_template_subtasks`
--
ALTER TABLE `pm_template_subtasks`
  ADD CONSTRAINT `pm_template_subtasks_pm_template_task_id_foreign` FOREIGN KEY (`pm_template_task_id`) REFERENCES `pm_template_tasks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pm_template_tasks`
--
ALTER TABLE `pm_template_tasks`
  ADD CONSTRAINT `pm_template_tasks_pm_template_id_foreign` FOREIGN KEY (`pm_template_id`) REFERENCES `pm_templates` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pra_work_orders`
--
ALTER TABLE `pra_work_orders`
  ADD CONSTRAINT `pra_work_orders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pra_work_orders_master_unit_id_foreign` FOREIGN KEY (`master_unit_id`) REFERENCES `master_units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pra_work_orders_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pra_work_orders_work_order_id_foreign` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `production_delays`
--
ALTER TABLE `production_delays`
  ADD CONSTRAINT `production_delays_production_fleet_id_foreign` FOREIGN KEY (`production_fleet_id`) REFERENCES `production_fleets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `production_delays_production_id_foreign` FOREIGN KEY (`production_id`) REFERENCES `productions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `production_fleets`
--
ALTER TABLE `production_fleets`
  ADD CONSTRAINT `production_fleets_digger_id_foreign` FOREIGN KEY (`digger_id`) REFERENCES `master_units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `production_fleets_production_id_foreign` FOREIGN KEY (`production_id`) REFERENCES `productions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `production_haulers`
--
ALTER TABLE `production_haulers`
  ADD CONSTRAINT `production_haulers_hauler_id_foreign` FOREIGN KEY (`hauler_id`) REFERENCES `master_units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `production_haulers_production_fleet_id_foreign` FOREIGN KEY (`production_fleet_id`) REFERENCES `production_fleets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `production_supports`
--
ALTER TABLE `production_supports`
  ADD CONSTRAINT `production_supports_production_id_foreign` FOREIGN KEY (`production_id`) REFERENCES `productions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `production_supports_support_id_foreign` FOREIGN KEY (`support_id`) REFERENCES `master_units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_opnames`
--
ALTER TABLE `stock_opnames`
  ADD CONSTRAINT `stock_opnames_auditor_user_id_foreign` FOREIGN KEY (`auditor_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stock_opnames_mechanic_id_foreign` FOREIGN KEY (`mechanic_id`) REFERENCES `mechanics` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_opname_details`
--
ALTER TABLE `stock_opname_details`
  ADD CONSTRAINT `stock_opname_details_stock_opname_id_foreign` FOREIGN KEY (`stock_opname_id`) REFERENCES `stock_opnames` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_opname_details_tool_id_foreign` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tools`
--
ALTER TABLE `tools`
  ADD CONSTRAINT `tools_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tools_tool_category_id_foreign` FOREIGN KEY (`tool_category_id`) REFERENCES `tool_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tool_stocks`
--
ALTER TABLE `tool_stocks`
  ADD CONSTRAINT `tool_stocks_mechanic_id_foreign` FOREIGN KEY (`mechanic_id`) REFERENCES `mechanics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tool_stocks_tool_id_foreign` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tool_transactions`
--
ALTER TABLE `tool_transactions`
  ADD CONSTRAINT `tool_transactions_mechanic_id_foreign` FOREIGN KEY (`mechanic_id`) REFERENCES `mechanics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tool_transactions_tool_id_foreign` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tool_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `unit_models`
--
ALTER TABLE `unit_models`
  ADD CONSTRAINT `unit_models_unit_type_id_foreign` FOREIGN KEY (`unit_type_id`) REFERENCES `unit_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `vendors`
--
ALTER TABLE `vendors`
  ADD CONSTRAINT `vendors_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `work_orders`
--
ALTER TABLE `work_orders`
  ADD CONSTRAINT `work_orders_breakdown_type_id_foreign` FOREIGN KEY (`breakdown_type_id`) REFERENCES `breakdown_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `work_orders_component_group_id_foreign` FOREIGN KEY (`component_group_id`) REFERENCES `component_groups` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `work_orders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `work_orders_master_unit_id_foreign` FOREIGN KEY (`master_unit_id`) REFERENCES `master_units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `work_orders_pm_schedule_id_foreign` FOREIGN KEY (`pm_schedule_id`) REFERENCES `pm_schedules` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `work_orders_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `work_orders_wo_category_1_id_foreign` FOREIGN KEY (`wo_category_1_id`) REFERENCES `wo_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `work_orders_wo_category_2_id_foreign` FOREIGN KEY (`wo_category_2_id`) REFERENCES `wo_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `work_orders_wo_category_3_id_foreign` FOREIGN KEY (`wo_category_3_id`) REFERENCES `wo_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `work_orders_wo_category_4_id_foreign` FOREIGN KEY (`wo_category_4_id`) REFERENCES `wo_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `work_orders_wo_category_5_id_foreign` FOREIGN KEY (`wo_category_5_id`) REFERENCES `wo_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `wo_subtasks`
--
ALTER TABLE `wo_subtasks`
  ADD CONSTRAINT `wo_subtasks_wo_task_id_foreign` FOREIGN KEY (`wo_task_id`) REFERENCES `wo_tasks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wo_subtask_manpower`
--
ALTER TABLE `wo_subtask_manpower`
  ADD CONSTRAINT `wo_subtask_manpower_mechanic_id_foreign` FOREIGN KEY (`mechanic_id`) REFERENCES `mechanics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wo_subtask_manpower_wo_subtask_id_foreign` FOREIGN KEY (`wo_subtask_id`) REFERENCES `wo_subtasks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wo_subtask_parts`
--
ALTER TABLE `wo_subtask_parts`
  ADD CONSTRAINT `wo_subtask_parts_part_id_foreign` FOREIGN KEY (`part_id`) REFERENCES `parts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wo_subtask_parts_wo_subtask_id_foreign` FOREIGN KEY (`wo_subtask_id`) REFERENCES `wo_subtasks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wo_subtask_tools`
--
ALTER TABLE `wo_subtask_tools`
  ADD CONSTRAINT `wo_subtask_tools_tool_transaction_id_foreign` FOREIGN KEY (`tool_transaction_id`) REFERENCES `tool_transactions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `wo_subtask_tools_wo_subtask_id_foreign` FOREIGN KEY (`wo_subtask_id`) REFERENCES `wo_subtasks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wo_tasks`
--
ALTER TABLE `wo_tasks`
  ADD CONSTRAINT `wo_tasks_component_group_id_foreign` FOREIGN KEY (`component_group_id`) REFERENCES `component_groups` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `wo_tasks_work_order_id_foreign` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
