-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 05, 2025 at 01:40 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `abcd`
--

-- --------------------------------------------------------

--
-- Table structure for table `class_subject`
--

CREATE TABLE `class_subject` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `class_subject`
--

INSERT INTO `class_subject` (`id`, `class_id`, `subject_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-05-31 06:29:31', '2025-05-31 06:29:31'),
(2, 1, 2, '2025-05-31 06:30:01', '2025-05-31 06:30:01');

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
-- Table structure for table `features`
--

CREATE TABLE `features` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `feature_group` varchar(255) NOT NULL,
  `value_type` enum('boolean','number','text') NOT NULL DEFAULT 'boolean',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `features`
--

INSERT INTO `features` (`id`, `name`, `code`, `description`, `feature_group`, `value_type`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Institute Profile', 'institute_profile', 'Manage institute profile settings', 'general_settings', 'boolean', 1, NULL, NULL),
(2, 'Rules & Regulations', 'rules_regulations', 'Manage institute rules and regulations', 'general_settings', 'boolean', 1, NULL, NULL),
(3, 'Account Settings', 'account_settings', 'Manage account settings', 'general_settings', 'boolean', 1, NULL, NULL),
(4, 'Notice Board', 'notice_board', 'Manage notice board announcements', 'general_settings', 'boolean', 1, NULL, NULL),
(5, 'Role Management', 'role_management', 'Manage user roles and permissions', 'general_settings', 'boolean', 1, NULL, NULL),
(6, 'Academic Sections', 'academic_sections', 'Manage academic sections', 'academics', 'boolean', 1, NULL, NULL),
(7, 'Academic Classes', 'academic_classes', 'Manage academic classes', 'academics', 'boolean', 1, NULL, NULL),
(8, 'Academic Subjects', 'academic_subjects', 'Manage academic subjects', 'academics', 'boolean', 1, NULL, NULL),
(9, 'Attendance', 'attendance', 'Manage student attendance', 'academics', 'boolean', 1, NULL, NULL),
(10, 'Timetable', 'timetable', 'Manage class timetables', 'academics', 'boolean', 1, NULL, NULL),
(11, 'Homework', 'homework', 'Manage student homework', 'academics', 'boolean', 1, NULL, NULL),
(12, 'Hostel Management', 'hostel_management', 'Manage hostel facilities', 'hostel', 'boolean', 1, NULL, NULL),
(13, 'Transport Management', 'transport_management', 'Manage transport facilities', 'transport', 'boolean', 1, NULL, NULL),
(14, 'Finance Management', 'finance_management', 'Manage school finances', 'finance', 'boolean', 1, NULL, NULL),
(15, 'Examination Management', 'examination_management', 'Manage examinations', 'examinations', 'boolean', 1, NULL, NULL),
(16, 'Library Management', 'library_management', 'Manage library resources', 'library', 'boolean', 1, NULL, NULL),
(17, 'Maximum Students', 'max_students', 'Maximum number of students allowed', 'limits', 'number', 1, NULL, NULL),
(18, 'Maximum Teachers', 'max_teachers', 'Maximum number of teachers allowed', 'limits', 'number', 1, NULL, NULL),
(19, 'Maximum Staff', 'max_staff', 'Maximum number of staff allowed', 'limits', 'number', 1, NULL, NULL),
(20, 'Storage Space', 'storage_space', 'Storage space in MB', 'limits', 'number', 1, NULL, NULL),
(21, 'Maximum File Size', 'max_file_size', 'Maximum file size in MB', 'limits', 'number', 1, NULL, NULL),
(22, 'Student Management', 'student_management', 'Manage students and related operations', 'academics', 'boolean', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `fee_groups`
--

CREATE TABLE `fee_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fee_groups`
--

INSERT INTO `fee_groups` (`id`, `school_id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(10, 5, 'Examination Fees', 'Fees for examinations', 1, '2025-06-03 04:07:20', '2025-06-03 04:07:20'),
(11, 5, 'Annual Fees', 'Annual one-time fees', 1, '2025-06-03 04:07:20', '2025-06-03 04:07:20'),
(12, 5, 'Admission Fees', 'One-time admission fees', 1, '2025-06-03 04:07:20', '2025-06-03 04:07:20'),
(13, 5, 'Sports Fees', 'Fees for sports activities', 1, '2025-06-03 04:07:20', '2025-06-03 04:07:20'),
(14, 5, 'Tuition Fees', 'Regular tuition fees', 1, '2025-06-03 04:58:27', '2025-06-03 04:58:27'),
(15, 5, 'Monthly Fees', 'Monthly recurring fees', 1, '2025-06-03 04:58:27', '2025-06-03 04:58:27'),
(16, 5, 'Transportation Fees', 'Fees for school transportation', 1, '2025-06-03 04:58:27', '2025-06-03 04:58:27'),
(17, 5, 'Hostel Fees', 'Fees for hostel accommodation', 1, '2025-06-03 04:58:27', '2025-06-03 04:58:27'),
(18, 5, 'Library Fees', 'Fees for library usage', 1, '2025-06-03 04:58:27', '2025-06-03 04:58:27'),
(19, 5, 'Laboratory Fees', 'Fees for laboratory usage', 1, '2025-06-03 04:58:27', '2025-06-03 04:58:27');

-- --------------------------------------------------------

--
-- Table structure for table `fee_types`
--

CREATE TABLE `fee_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unique_id` varchar(10) NOT NULL,
  `school_id` bigint(20) UNSIGNED NOT NULL,
  `fee_group_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `fees_code` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fee_types`
--

INSERT INTO `fee_types` (`id`, `unique_id`, `school_id`, `fee_group_id`, `name`, `fees_code`, `description`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'FT76535', 5, 10, 'Mid-Term Exam Fee', 'mid-term-exam-fee', 'Sample Mid-Term Exam Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(2, 'FT82154', 5, 10, 'Final Exam Fee', 'final-exam-fee', 'Sample Final Exam Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(3, 'FT71897', 5, 10, 'Practical Exam Fee', 'practical-exam-fee', 'Sample Practical Exam Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(4, 'FT52744', 5, 10, 'Special Exam Fee', 'special-exam-fee', 'Sample Special Exam Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(5, 'FT49423', 5, 11, 'Annual Development Fee', 'annual-development-fee', 'Sample Annual Development Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(6, 'FT96477', 5, 11, 'Annual Maintenance Fee', 'annual-maintenance-fee', 'Sample Annual Maintenance Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(7, 'FT83873', 5, 11, 'Annual Sports Fee', 'annual-sports-fee', 'Sample Annual Sports Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(8, 'FT56453', 5, 11, 'Annual Cultural Fee', 'annual-cultural-fee', 'Sample Annual Cultural Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(9, 'FT44777', 5, 12, 'New Admission Fee', 'new-admission-fee', 'Sample New Admission Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(10, 'FT22662', 5, 12, 'Registration Fee', 'registration-fee', 'Sample Registration Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(11, 'FT75361', 5, 12, 'Admission Processing Fee', 'admission-processing-fee', 'Sample Admission Processing Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(12, 'FT45817', 5, 12, 'Document Verification Fee', 'document-verification-fee', 'Sample Document Verification Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(13, 'FT63136', 5, 13, 'Sports Equipment Fee', 'sports-equipment-fee', 'Sample Sports Equipment Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(14, 'FT88770', 5, 13, 'Sports Coaching Fee', 'sports-coaching-fee', 'Sample Sports Coaching Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(15, 'FT68474', 5, 13, 'Sports Ground Maintenance', 'sports-ground-maintenance', 'Sample Sports Ground Maintenance for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(16, 'FT18910', 5, 13, 'Sports Competition Fee', 'sports-competition-fee', 'Sample Sports Competition Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(17, 'FT27742', 5, 14, 'Monthly Tuition Fee', 'monthly-tuition-fee', 'Sample Monthly Tuition Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(18, 'FT96525', 5, 14, 'Term Tuition Fee', 'term-tuition-fee', 'Sample Term Tuition Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(19, 'FT40614', 5, 14, 'Annual Tuition Fee', 'annual-tuition-fee', 'Sample Annual Tuition Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(20, 'FT82851', 5, 14, 'Special Subject Fee', 'special-subject-fee', 'Sample Special Subject Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(21, 'FT46383', 5, 15, 'Monthly Development Fee', 'monthly-development-fee', 'Sample Monthly Development Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(22, 'FT38215', 5, 15, 'Monthly Activity Fee', 'monthly-activity-fee', 'Sample Monthly Activity Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(23, 'FT80001', 5, 15, 'Monthly Computer Lab Fee', 'monthly-computer-lab-fee', 'Sample Monthly Computer Lab Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(24, 'FT72237', 5, 15, 'Monthly Smart Class Fee', 'monthly-smart-class-fee', 'Sample Monthly Smart Class Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(25, 'FT50445', 5, 16, 'Bus Fee - Zone 1', 'bus-fee-zone-1', 'Sample Bus Fee - Zone 1 for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(26, 'FT25757', 5, 16, 'Bus Fee - Zone 2', 'bus-fee-zone-2', 'Sample Bus Fee - Zone 2 for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(27, 'FT58200', 5, 16, 'Bus Fee - Zone 3', 'bus-fee-zone-3', 'Sample Bus Fee - Zone 3 for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(28, 'FT57383', 5, 16, 'Transportation Maintenance', 'transportation-maintenance', 'Sample Transportation Maintenance for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(29, 'FT19352', 5, 17, 'Hostel Room Fee', 'hostel-room-fee', 'Sample Hostel Room Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(30, 'FT48386', 5, 17, 'Hostel Mess Fee', 'hostel-mess-fee', 'Sample Hostel Mess Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(31, 'FT57579', 5, 17, 'Hostel Maintenance', 'hostel-maintenance', 'Sample Hostel Maintenance for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(32, 'FT63393', 5, 17, 'Hostel Utility Fee', 'hostel-utility-fee', 'Sample Hostel Utility Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(33, 'FT11085', 5, 18, 'Library Access Fee', 'library-access-fee', 'Sample Library Access Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(34, 'FT98624', 5, 18, 'Book Issue Fee', 'book-issue-fee', 'Sample Book Issue Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(35, 'FT96998', 5, 18, 'Library Maintenance', 'library-maintenance', 'Sample Library Maintenance for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(36, 'FT88422', 5, 18, 'E-Library Subscription', 'e-library-subscription', 'Sample E-Library Subscription for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(37, 'FT47695', 5, 19, 'Science Lab Fee', 'science-lab-fee', 'Sample Science Lab Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(38, 'FT88754', 5, 19, 'Computer Lab Fee', 'computer-lab-fee', 'Sample Computer Lab Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(39, 'FT95066', 5, 19, 'Language Lab Fee', 'language-lab-fee', 'Sample Language Lab Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL),
(40, 'FT41854', 5, 19, 'Equipment Usage Fee', 'equipment-usage-fee', 'Sample Equipment Usage Fee for testing purposes', 1, '2025-06-03 04:59:24', '2025-06-03 04:59:24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hostels`
--

CREATE TABLE `hostels` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('Boys','Girls','Co-ed') NOT NULL DEFAULT 'Boys',
  `address` text DEFAULT NULL,
  `intake` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hostels`
--

INSERT INTO `hostels` (`id`, `school_id`, `name`, `type`, `address`, `intake`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, 'Nove Nector', 'Boys', 'Laxmi Nagar', 200, NULL, 1, '2025-05-31 06:40:55', '2025-05-31 06:53:00'),
(2, 5, 'Tagore', 'Boys', 'Sector 62', 100, NULL, 1, '2025-05-31 06:44:01', '2025-05-31 06:53:11');

-- --------------------------------------------------------

--
-- Table structure for table `hostel_rooms`
--

CREATE TABLE `hostel_rooms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED NOT NULL,
  `hostel_id` bigint(20) UNSIGNED NOT NULL,
  `room_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `room_number` varchar(255) NOT NULL,
  `beds` int(11) NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hostel_rooms`
--

INSERT INTO `hostel_rooms` (`id`, `school_id`, `hostel_id`, `room_type_id`, `room_number`, `beds`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, 1, 2, '101', 2, 'bsjhvkoaf', 1, '2025-06-03 00:15:41', '2025-06-03 01:02:32'),
(7, 5, 2, 2, '101', 1, 'kdnv', 1, '2025-06-03 01:17:48', '2025-06-03 01:17:48');

-- --------------------------------------------------------

--
-- Table structure for table `hostel_room_types`
--

CREATE TABLE `hostel_room_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hostel_room_types`
--

INSERT INTO `hostel_room_types` (`id`, `school_id`, `name`, `description`, `price`, `status`, `created_at`, `updated_at`) VALUES
(2, 5, 'AC', NULL, 12000.00, 1, '2025-06-02 23:57:23', '2025-06-03 00:04:23'),
(3, 5, 'NON - AC', NULL, 10000.00, 1, '2025-06-02 23:58:35', '2025-06-02 23:58:35');

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_05_30_111254_create_schools_table', 1),
(6, '2025_05_30_111258_create_plans_table', 1),
(7, '2025_05_30_111303_create_features_table', 1),
(8, '2025_05_30_111308_create_plan_features_table', 1),
(9, '2025_05_30_111316_create_school_subscriptions_table', 1),
(11, '2025_05_30_123742_add_admin_id_to_schools_table', 2),
(12, '2025_05_31_053112_add_school_id_to_users_table', 3),
(13, '2023_06_04_000000_add_profile_fields_to_schools_table', 4),
(14, '2023_06_05_000000_create_rule_categories_table', 5),
(15, '2023_06_05_000001_create_rules_table', 5),
(16, '2025_05_31_075856_create_roles_table', 6),
(17, '2025_05_31_075908_create_permissions_table', 6),
(18, '2025_05_31_075915_create_role_permissions_table', 6),
(19, '2025_05_31_080004_create_user_roles_table', 6),
(20, '2025_05_31_081724_add_profile_columns_to_users_table', 7),
(21, '2025_05_31_082624_add_staff_role_to_users', 8),
(22, '2025_05_31_082830_fix_user_roles_assignment', 9),
(23, '2025_06_01_create_sections_table', 10),
(24, '2025_06_01_create_school_classes_table', 11),
(25, '2025_06_02_create_subjects_table', 12),
(26, '2025_07_01_create_hostels_table', 13),
(27, '2025_07_02_create_hostel_room_types_table', 14),
(28, '2025_06_03_053716_create_hostel_rooms_table', 15),
(29, '2025_06_03_070703_create_vehicle_drivers_table', 16),
(30, '2025_06_03_071632_create_vehicles_table', 17),
(31, '2025_06_03_073613_create_route_details_table', 18),
(32, '2025_06_03_073628_create_pickup_points_table', 18),
(33, '2025_06_03_075537_create_route_assignments_table', 19),
(34, '2025_06_03_082340_create_fee_groups_table', 20),
(35, '2024_01_01_create_fee_types_table', 21),
(36, '2025_05_30_115225_add_default_features', 22),
(37, '2025_06_03_161129_create_students_table', 23),
(38, '2025_06_04_050842_update_unique_constraint_in_school_classes_table', 24),
(39, '2025_06_04_074329_add_soft_deletes_to_school_classes_table', 24),
(40, '2025_06_04_074801_add_soft_deletes_to_school_classes_table', 24),
(41, '2025_06_04_074811_add_soft_deletes_to_sections_table', 24),
(42, '2025_06_04_081150_add_soft_deletes_to_students_table', 25),
(43, '2025_06_04_100309_add_default_to_academic_number_in_students_table', 26),
(44, '2025_06_04_105838_add_parent_information_to_students_table', 27),
(45, '2025_06_05_054227_create_teachers_table', 28);

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
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `feature_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(255) NOT NULL DEFAULT 'view',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `slug`, `description`, `feature_id`, `action`, `created_at`, `updated_at`) VALUES
(1, 'View Institute Profile', 'view-institute_profile', 'view access to Institute Profile', 1, 'view', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(2, 'Create Institute Profile', 'create-institute_profile', 'create access to Institute Profile', 1, 'create', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(3, 'Edit Institute Profile', 'edit-institute_profile', 'edit access to Institute Profile', 1, 'edit', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(4, 'Delete Institute Profile', 'delete-institute_profile', 'delete access to Institute Profile', 1, 'delete', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(5, 'View Rules & Regulations', 'view-rules_regulations', 'view access to Rules & Regulations', 2, 'view', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(6, 'Create Rules & Regulations', 'create-rules_regulations', 'create access to Rules & Regulations', 2, 'create', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(7, 'Edit Rules & Regulations', 'edit-rules_regulations', 'edit access to Rules & Regulations', 2, 'edit', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(8, 'Delete Rules & Regulations', 'delete-rules_regulations', 'delete access to Rules & Regulations', 2, 'delete', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(9, 'View Account Settings', 'view-account_settings', 'view access to Account Settings', 3, 'view', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(10, 'Create Account Settings', 'create-account_settings', 'create access to Account Settings', 3, 'create', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(11, 'Edit Account Settings', 'edit-account_settings', 'edit access to Account Settings', 3, 'edit', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(12, 'Delete Account Settings', 'delete-account_settings', 'delete access to Account Settings', 3, 'delete', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(13, 'View Notice Board', 'view-notice_board', 'view access to Notice Board', 4, 'view', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(14, 'Create Notice Board', 'create-notice_board', 'create access to Notice Board', 4, 'create', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(15, 'Edit Notice Board', 'edit-notice_board', 'edit access to Notice Board', 4, 'edit', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(16, 'Delete Notice Board', 'delete-notice_board', 'delete access to Notice Board', 4, 'delete', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(17, 'View Role Management', 'view-role_management', 'view access to Role Management', 5, 'view', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(18, 'Create Role Management', 'create-role_management', 'create access to Role Management', 5, 'create', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(19, 'Edit Role Management', 'edit-role_management', 'edit access to Role Management', 5, 'edit', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(20, 'Delete Role Management', 'delete-role_management', 'delete access to Role Management', 5, 'delete', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(21, 'View Academic Sections', 'view-academic_sections', 'view access to Academic Sections', 6, 'view', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(22, 'Create Academic Sections', 'create-academic_sections', 'create access to Academic Sections', 6, 'create', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(23, 'Edit Academic Sections', 'edit-academic_sections', 'edit access to Academic Sections', 6, 'edit', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(24, 'Delete Academic Sections', 'delete-academic_sections', 'delete access to Academic Sections', 6, 'delete', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(25, 'View Academic Classes', 'view-academic_classes', 'view access to Academic Classes', 7, 'view', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(26, 'Create Academic Classes', 'create-academic_classes', 'create access to Academic Classes', 7, 'create', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(27, 'Edit Academic Classes', 'edit-academic_classes', 'edit access to Academic Classes', 7, 'edit', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(28, 'Delete Academic Classes', 'delete-academic_classes', 'delete access to Academic Classes', 7, 'delete', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(29, 'View Academic Subjects', 'view-academic_subjects', 'view access to Academic Subjects', 8, 'view', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(30, 'Create Academic Subjects', 'create-academic_subjects', 'create access to Academic Subjects', 8, 'create', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(31, 'Edit Academic Subjects', 'edit-academic_subjects', 'edit access to Academic Subjects', 8, 'edit', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(32, 'Delete Academic Subjects', 'delete-academic_subjects', 'delete access to Academic Subjects', 8, 'delete', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(33, 'View Attendance', 'view-attendance', 'view access to Attendance', 9, 'view', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(34, 'Create Attendance', 'create-attendance', 'create access to Attendance', 9, 'create', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(35, 'Edit Attendance', 'edit-attendance', 'edit access to Attendance', 9, 'edit', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(36, 'Delete Attendance', 'delete-attendance', 'delete access to Attendance', 9, 'delete', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(37, 'View Timetable', 'view-timetable', 'view access to Timetable', 10, 'view', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(38, 'Create Timetable', 'create-timetable', 'create access to Timetable', 10, 'create', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(39, 'Edit Timetable', 'edit-timetable', 'edit access to Timetable', 10, 'edit', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(40, 'Delete Timetable', 'delete-timetable', 'delete access to Timetable', 10, 'delete', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(41, 'View Homework', 'view-homework', 'view access to Homework', 11, 'view', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(42, 'Create Homework', 'create-homework', 'create access to Homework', 11, 'create', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(43, 'Edit Homework', 'edit-homework', 'edit access to Homework', 11, 'edit', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(44, 'Delete Homework', 'delete-homework', 'delete access to Homework', 11, 'delete', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(45, 'View Hostel Management', 'view-hostel_management', 'view access to Hostel Management', 12, 'view', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(46, 'Create Hostel Management', 'create-hostel_management', 'create access to Hostel Management', 12, 'create', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(47, 'Edit Hostel Management', 'edit-hostel_management', 'edit access to Hostel Management', 12, 'edit', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(48, 'Delete Hostel Management', 'delete-hostel_management', 'delete access to Hostel Management', 12, 'delete', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(49, 'View Transport Management', 'view-transport_management', 'view access to Transport Management', 13, 'view', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(50, 'Create Transport Management', 'create-transport_management', 'create access to Transport Management', 13, 'create', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(51, 'Edit Transport Management', 'edit-transport_management', 'edit access to Transport Management', 13, 'edit', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(52, 'Delete Transport Management', 'delete-transport_management', 'delete access to Transport Management', 13, 'delete', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(53, 'View Finance Management', 'view-finance_management', 'view access to Finance Management', 14, 'view', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(54, 'Create Finance Management', 'create-finance_management', 'create access to Finance Management', 14, 'create', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(55, 'Edit Finance Management', 'edit-finance_management', 'edit access to Finance Management', 14, 'edit', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(56, 'Delete Finance Management', 'delete-finance_management', 'delete access to Finance Management', 14, 'delete', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(57, 'View Examination Management', 'view-examination_management', 'view access to Examination Management', 15, 'view', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(58, 'Create Examination Management', 'create-examination_management', 'create access to Examination Management', 15, 'create', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(59, 'Edit Examination Management', 'edit-examination_management', 'edit access to Examination Management', 15, 'edit', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(60, 'Delete Examination Management', 'delete-examination_management', 'delete access to Examination Management', 15, 'delete', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(61, 'View Library Management', 'view-library_management', 'view access to Library Management', 16, 'view', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(62, 'Create Library Management', 'create-library_management', 'create access to Library Management', 16, 'create', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(63, 'Edit Library Management', 'edit-library_management', 'edit access to Library Management', 16, 'edit', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(64, 'Delete Library Management', 'delete-library_management', 'delete access to Library Management', 16, 'delete', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(65, 'View Maximum Students', 'view-max_students', 'view access to Maximum Students', 17, 'view', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(66, 'Create Maximum Students', 'create-max_students', 'create access to Maximum Students', 17, 'create', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(67, 'Edit Maximum Students', 'edit-max_students', 'edit access to Maximum Students', 17, 'edit', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(68, 'Delete Maximum Students', 'delete-max_students', 'delete access to Maximum Students', 17, 'delete', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(69, 'View Maximum Teachers', 'view-max_teachers', 'view access to Maximum Teachers', 18, 'view', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(70, 'Create Maximum Teachers', 'create-max_teachers', 'create access to Maximum Teachers', 18, 'create', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(71, 'Edit Maximum Teachers', 'edit-max_teachers', 'edit access to Maximum Teachers', 18, 'edit', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(72, 'Delete Maximum Teachers', 'delete-max_teachers', 'delete access to Maximum Teachers', 18, 'delete', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(73, 'View Maximum Staff', 'view-max_staff', 'view access to Maximum Staff', 19, 'view', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(74, 'Create Maximum Staff', 'create-max_staff', 'create access to Maximum Staff', 19, 'create', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(75, 'Edit Maximum Staff', 'edit-max_staff', 'edit access to Maximum Staff', 19, 'edit', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(76, 'Delete Maximum Staff', 'delete-max_staff', 'delete access to Maximum Staff', 19, 'delete', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(77, 'View Storage Space', 'view-storage_space', 'view access to Storage Space', 20, 'view', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(78, 'Create Storage Space', 'create-storage_space', 'create access to Storage Space', 20, 'create', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(79, 'Edit Storage Space', 'edit-storage_space', 'edit access to Storage Space', 20, 'edit', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(80, 'Delete Storage Space', 'delete-storage_space', 'delete access to Storage Space', 20, 'delete', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(81, 'View Maximum File Size', 'view-max_file_size', 'view access to Maximum File Size', 21, 'view', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(82, 'Create Maximum File Size', 'create-max_file_size', 'create access to Maximum File Size', 21, 'create', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(83, 'Edit Maximum File Size', 'edit-max_file_size', 'edit access to Maximum File Size', 21, 'edit', '2025-05-31 02:36:30', '2025-05-31 02:36:30'),
(84, 'Delete Maximum File Size', 'delete-max_file_size', 'delete access to Maximum File Size', 21, 'delete', '2025-05-31 02:36:30', '2025-05-31 02:36:30');

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
-- Table structure for table `pickup_points`
--

CREATE TABLE `pickup_points` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `route_detail_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `latitude` varchar(255) DEFAULT NULL,
  `longitude` varchar(255) DEFAULT NULL,
  `sequence` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pickup_points`
--

INSERT INTO `pickup_points` (`id`, `route_detail_id`, `name`, `latitude`, `longitude`, `sequence`, `created_at`, `updated_at`) VALUES
(8, 7, 'Model Town', NULL, NULL, 1, '2025-06-03 06:17:45', '2025-06-03 06:17:45'),
(9, 7, 'Ashok Vihar', NULL, NULL, 2, '2025-06-03 06:17:45', '2025-06-03 06:17:45'),
(10, 7, 'Pitampura', NULL, NULL, 3, '2025-06-03 06:17:45', '2025-06-03 06:17:45'),
(11, 8, 'Saket', NULL, NULL, 1, '2025-06-03 06:17:45', '2025-06-03 06:17:45'),
(12, 8, 'Malviya Nagar', NULL, NULL, 2, '2025-06-03 06:17:45', '2025-06-03 06:17:45'),
(13, 9, 'Laxmi Nagar', NULL, NULL, 1, '2025-06-03 06:17:45', '2025-06-03 06:17:45'),
(14, 9, 'Mayur Vihar', NULL, NULL, 2, '2025-06-03 06:17:45', '2025-06-03 06:17:45'),
(15, 10, 'Laxmi nagar', NULL, NULL, 0, '2025-06-03 06:35:25', '2025-06-03 06:35:25'),
(16, 10, 'Akshardham', NULL, NULL, 1, '2025-06-03 06:35:25', '2025-06-03 06:35:25'),
(17, 10, 'Sector 62 Metro', NULL, NULL, 2, '2025-06-03 06:35:25', '2025-06-03 06:35:25');

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `billing_cycle` enum('monthly','yearly') NOT NULL DEFAULT 'monthly',
  `max_students` int(11) NOT NULL DEFAULT 0,
  `max_teachers` int(11) NOT NULL DEFAULT 0,
  `max_staff` int(11) NOT NULL DEFAULT 0,
  `is_popular` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`id`, `name`, `description`, `price`, `billing_cycle`, `max_students`, `max_teachers`, `max_staff`, `is_popular`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'BASIC', 'Basic Plans includes very few Features', 15.00, 'monthly', 10, 1, 1, 0, 1, '2025-05-30 06:55:20', '2025-05-31 00:51:48'),
(3, 'ADVANCE', 'kjsbvksbkvbkb', 49.00, 'monthly', 100, 10, 5, 1, 1, '2025-05-30 06:57:35', '2025-05-31 00:50:49'),
(4, 'PREMIUM', 'vsbkjsbkjbk', 99.00, 'monthly', 10000, 200, 50, 0, 0, '2025-05-30 06:58:44', '2025-05-31 02:39:49');

-- --------------------------------------------------------

--
-- Table structure for table `plan_features`
--

CREATE TABLE `plan_features` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `plan_id` bigint(20) UNSIGNED NOT NULL,
  `feature_id` bigint(20) UNSIGNED NOT NULL,
  `allowed_value` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plan_features`
--

INSERT INTO `plan_features` (`id`, `plan_id`, `feature_id`, `allowed_value`, `created_at`, `updated_at`) VALUES
(534, 3, 1, NULL, '2025-05-31 00:50:49', '2025-05-31 00:50:49'),
(535, 3, 2, NULL, '2025-05-31 00:50:49', '2025-05-31 00:50:49'),
(536, 3, 3, NULL, '2025-05-31 00:50:49', '2025-05-31 00:50:49'),
(537, 3, 4, NULL, '2025-05-31 00:50:49', '2025-05-31 00:50:49'),
(538, 3, 5, NULL, '2025-05-31 00:50:49', '2025-05-31 00:50:49'),
(539, 3, 6, NULL, '2025-05-31 00:50:49', '2025-05-31 00:50:49'),
(540, 3, 7, NULL, '2025-05-31 00:50:49', '2025-05-31 00:50:49'),
(541, 3, 8, NULL, '2025-05-31 00:50:49', '2025-05-31 00:50:49'),
(542, 3, 9, NULL, '2025-05-31 00:50:49', '2025-05-31 00:50:49'),
(543, 3, 10, NULL, '2025-05-31 00:50:49', '2025-05-31 00:50:49'),
(544, 3, 11, NULL, '2025-05-31 00:50:49', '2025-05-31 00:50:49'),
(545, 3, 14, NULL, '2025-05-31 00:50:49', '2025-05-31 00:50:49'),
(546, 3, 15, NULL, '2025-05-31 00:50:49', '2025-05-31 00:50:49'),
(547, 3, 17, '100', '2025-05-31 00:50:49', '2025-05-31 00:50:49'),
(548, 3, 18, '10', '2025-05-31 00:50:49', '2025-05-31 00:50:49'),
(549, 3, 19, '5', '2025-05-31 00:50:49', '2025-05-31 00:50:49'),
(550, 3, 20, '2', '2025-05-31 00:50:49', '2025-05-31 00:50:49'),
(551, 3, 21, '2', '2025-05-31 00:50:49', '2025-05-31 00:50:49'),
(552, 2, 1, NULL, '2025-05-31 00:51:48', '2025-05-31 00:51:48'),
(553, 2, 2, NULL, '2025-05-31 00:51:48', '2025-05-31 00:51:48'),
(554, 2, 3, NULL, '2025-05-31 00:51:48', '2025-05-31 00:51:48'),
(555, 2, 4, NULL, '2025-05-31 00:51:48', '2025-05-31 00:51:48'),
(556, 2, 5, NULL, '2025-05-31 00:51:48', '2025-05-31 00:51:48'),
(557, 2, 6, NULL, '2025-05-31 00:51:48', '2025-05-31 00:51:48'),
(558, 2, 7, NULL, '2025-05-31 00:51:48', '2025-05-31 00:51:48'),
(559, 2, 8, NULL, '2025-05-31 00:51:48', '2025-05-31 00:51:48'),
(560, 2, 9, NULL, '2025-05-31 00:51:48', '2025-05-31 00:51:48'),
(561, 2, 10, NULL, '2025-05-31 00:51:48', '2025-05-31 00:51:48'),
(562, 2, 11, NULL, '2025-05-31 00:51:48', '2025-05-31 00:51:48'),
(563, 2, 15, NULL, '2025-05-31 00:51:48', '2025-05-31 00:51:48'),
(564, 2, 17, '10', '2025-05-31 00:51:48', '2025-05-31 00:51:48'),
(565, 2, 18, '1', '2025-05-31 00:51:48', '2025-05-31 00:51:48'),
(566, 2, 19, '1', '2025-05-31 00:51:48', '2025-05-31 00:51:48'),
(567, 2, 20, '1', '2025-05-31 00:51:48', '2025-05-31 00:51:48'),
(568, 2, 21, '1', '2025-05-31 00:51:48', '2025-05-31 00:51:48'),
(653, 4, 1, NULL, '2025-05-31 02:39:49', '2025-05-31 02:39:49'),
(654, 4, 2, NULL, '2025-05-31 02:39:49', '2025-05-31 02:39:49'),
(655, 4, 3, NULL, '2025-05-31 02:39:49', '2025-05-31 02:39:49'),
(656, 4, 4, NULL, '2025-05-31 02:39:49', '2025-05-31 02:39:49'),
(657, 4, 5, NULL, '2025-05-31 02:39:49', '2025-05-31 02:39:49'),
(658, 4, 6, NULL, '2025-05-31 02:39:49', '2025-05-31 02:39:49'),
(659, 4, 7, NULL, '2025-05-31 02:39:49', '2025-05-31 02:39:49'),
(660, 4, 8, NULL, '2025-05-31 02:39:49', '2025-05-31 02:39:49'),
(661, 4, 9, NULL, '2025-05-31 02:39:49', '2025-05-31 02:39:49'),
(662, 4, 10, NULL, '2025-05-31 02:39:49', '2025-05-31 02:39:49'),
(663, 4, 11, NULL, '2025-05-31 02:39:49', '2025-05-31 02:39:49'),
(664, 4, 12, NULL, '2025-05-31 02:39:49', '2025-05-31 02:39:49'),
(665, 4, 13, NULL, '2025-05-31 02:39:49', '2025-05-31 02:39:49'),
(666, 4, 14, NULL, '2025-05-31 02:39:49', '2025-05-31 02:39:49'),
(667, 4, 15, NULL, '2025-05-31 02:39:49', '2025-05-31 02:39:49'),
(668, 4, 16, NULL, '2025-05-31 02:39:49', '2025-05-31 02:39:49'),
(669, 4, 17, '10000', '2025-05-31 02:39:49', '2025-05-31 02:39:49'),
(670, 4, 18, '200', '2025-05-31 02:39:49', '2025-05-31 02:39:49'),
(671, 4, 19, '50', '2025-05-31 02:39:49', '2025-05-31 02:39:49'),
(672, 4, 20, NULL, '2025-05-31 02:39:49', '2025-05-31 02:39:49'),
(673, 4, 21, NULL, '2025-05-31 02:39:49', '2025-05-31 02:39:49'),
(674, 2, 22, 'true', '2025-06-03 05:32:12', '2025-06-03 05:32:12'),
(675, 3, 22, 'true', '2025-06-03 05:32:12', '2025-06-03 05:32:12'),
(676, 4, 22, 'true', '2025-06-03 05:32:12', '2025-06-03 05:32:12');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `school_id` bigint(20) UNSIGNED NOT NULL,
  `is_system_role` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `school_id`, `is_system_role`, `created_at`, `updated_at`) VALUES
(1, 'Library', 'library', 'This Role is defined for Library Assistant', 5, 0, '2025-05-31 02:40:29', '2025-05-31 02:40:29'),
(2, 'Accounts', 'accounts', 'Roled based on the accounts', 5, 0, '2025-05-31 04:32:54', '2025-05-31 04:32:54'),
(3, 'school', 'school', 'Full access to all school features', 5, 1, '2025-05-31 04:51:46', '2025-05-31 04:51:46');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 1, 61, '2025-05-31 02:40:29', '2025-05-31 02:40:29'),
(2, 1, 62, '2025-05-31 02:40:29', '2025-05-31 02:40:29'),
(3, 1, 63, '2025-05-31 02:40:29', '2025-05-31 02:40:29'),
(4, 1, 64, '2025-05-31 02:40:29', '2025-05-31 02:40:29'),
(9, 2, 53, '2025-05-31 04:32:54', '2025-05-31 04:32:54'),
(10, 2, 54, '2025-05-31 04:32:54', '2025-05-31 04:32:54'),
(11, 2, 55, '2025-05-31 04:32:54', '2025-05-31 04:32:54'),
(12, 2, 56, '2025-05-31 04:32:54', '2025-05-31 04:32:54');

-- --------------------------------------------------------

--
-- Table structure for table `route_assignments`
--

CREATE TABLE `route_assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED NOT NULL,
  `route_detail_id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_id` bigint(20) UNSIGNED NOT NULL,
  `driver_id` bigint(20) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `route_assignments`
--

INSERT INTO `route_assignments` (`id`, `school_id`, `route_detail_id`, `vehicle_id`, `driver_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, 1, 2, 2, 1, '2025-06-03 02:32:47', '2025-06-03 02:32:47'),
(3, 5, 9, 9, 2, 1, '2025-06-04 02:40:50', '2025-06-04 02:40:50');

-- --------------------------------------------------------

--
-- Table structure for table `route_details`
--

CREATE TABLE `route_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED NOT NULL,
  `route_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `route_details`
--

INSERT INTO `route_details` (`id`, `school_id`, `route_name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(7, 5, 'North Delhi Route', 'Covering major areas in North Delhi', 1, '2025-06-03 06:17:45', '2025-06-03 06:17:45'),
(8, 5, 'South Delhi Route', 'Covering major areas in South Delhi', 1, '2025-06-03 06:17:45', '2025-06-03 06:17:45'),
(9, 5, 'East Delhi Route', 'Covering major areas in East Delhi', 1, '2025-06-03 06:17:45', '2025-06-03 06:17:45'),
(10, 5, 'Via Ghazipur', 'sdvsdvvsdv', 1, '2025-06-03 06:35:25', '2025-06-03 06:35:25');

-- --------------------------------------------------------

--
-- Table structure for table `rules`
--

CREATE TABLE `rules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED NOT NULL,
  `rule_category_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rules`
--

INSERT INTO `rules` (`id`, `school_id`, `rule_category_id`, `title`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 5, 1, 'Respect for Staff', 'Students must show respect to teachers and staff at all times.', 1, '2025-05-31 02:07:49', '2025-05-31 02:08:31'),
(2, 5, 1, 'No Bullying', 'Bullying among students is strictly prohibited.', 1, '2025-05-31 02:09:05', '2025-05-31 02:09:05'),
(3, 5, 1, 'Fighting Prohibited', 'Physical fights are strictly not allowed within school premises.', 1, '2025-05-31 02:10:11', '2025-05-31 02:10:11'),
(4, 5, 2, 'Regular Attendance', 'Students must maintain at least 75% attendance.', 1, '2025-05-31 02:10:47', '2025-05-31 02:10:47');

-- --------------------------------------------------------

--
-- Table structure for table `rule_categories`
--

CREATE TABLE `rule_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rule_categories`
--

INSERT INTO `rule_categories` (`id`, `school_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 5, 'Discipline', NULL, '2025-05-31 02:07:28', '2025-05-31 02:07:28'),
(2, 5, 'Attendance', NULL, '2025-05-31 02:10:30', '2025-05-31 02:10:30');

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `tagline` varchar(255) DEFAULT NULL,
  `admin_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `about` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','pending') NOT NULL DEFAULT 'pending',
  `registration_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`id`, `name`, `tagline`, `admin_name`, `email`, `phone`, `website`, `address`, `about`, `logo`, `status`, `registration_date`, `created_at`, `updated_at`, `admin_id`) VALUES
(5, 'Little Flower School', 'Empowering Minds, Transforming Lives', NULL, 'little@gmail.com', '6265336460', 'www.littleflowerschool.com', 'B-Block Sector 62', 'Passionate software developer with 5 years of experience in web technologies.\r\nI love creating user-friendly applications and solving complex problems.\r\nPassionate software developer with 5 years of experience in web technologies.I love creating user-friendly applications and solving complex problems.', '1748674217.png', 'active', NULL, '2025-05-31 00:57:27', '2025-05-31 01:20:18', 5);

-- --------------------------------------------------------

--
-- Table structure for table `school_classes`
--

CREATE TABLE `school_classes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `total_capacity` int(11) NOT NULL DEFAULT 30,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `school_classes`
--

INSERT INTO `school_classes` (`id`, `school_id`, `section_id`, `name`, `total_capacity`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 5, 1, 'Class 1', 50, 1, '2025-05-31 06:04:19', '2025-05-31 06:04:19', NULL),
(2, 5, 2, 'Class 1', 50, 1, '2025-06-04 02:44:44', '2025-06-04 02:44:44', NULL),
(3, 5, 1, 'Class 2', 50, 1, '2025-06-04 02:44:53', '2025-06-04 02:44:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `school_subscriptions`
--

CREATE TABLE `school_subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED NOT NULL,
  `plan_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `status` enum('active','expired','cancelled') NOT NULL DEFAULT 'active',
  `price_paid` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `school_subscriptions`
--

INSERT INTO `school_subscriptions` (`id`, `school_id`, `plan_id`, `start_date`, `end_date`, `status`, `price_paid`, `payment_method`, `transaction_id`, `created_at`, `updated_at`) VALUES
(1, 5, 4, '2025-05-31 00:00:00', '2025-07-01 00:00:00', 'active', 99.00, 'online', NULL, '2025-05-31 00:57:27', '2025-05-31 02:39:42');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(10) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `school_id`, `name`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 5, 'A', 1, '2025-05-31 05:37:17', '2025-05-31 05:53:49', NULL),
(2, 5, 'B', 1, '2025-05-31 05:38:34', '2025-05-31 05:51:17', NULL),
(3, 5, 'C', 1, '2025-05-31 05:38:39', '2025-05-31 05:57:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `academic_number` varchar(255) DEFAULT NULL,
  `student_id` varchar(255) NOT NULL,
  `admission_number` varchar(255) DEFAULT NULL,
  `roll_number` varchar(255) DEFAULT NULL,
  `class_id` bigint(20) UNSIGNED DEFAULT NULL,
  `section_id` bigint(20) UNSIGNED DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `blood_group` varchar(255) DEFAULT NULL,
  `religion` varchar(255) DEFAULT NULL,
  `house` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `primary_contact` varchar(255) DEFAULT NULL,
  `admission_date` date DEFAULT NULL,
  `academic_year` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `profile_image` varchar(255) DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `father_email` varchar(255) DEFAULT NULL,
  `father_phone_number` varchar(255) DEFAULT NULL,
  `father_occupation` varchar(255) DEFAULT NULL,
  `father_profile_image` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `mother_email` varchar(255) DEFAULT NULL,
  `mother_phone_number` varchar(255) DEFAULT NULL,
  `mother_occupation` varchar(255) DEFAULT NULL,
  `mother_profile_image` varchar(255) DEFAULT NULL,
  `guardian_type` varchar(255) DEFAULT NULL,
  `guardian_name` varchar(255) DEFAULT NULL,
  `guardian_relation` varchar(255) DEFAULT NULL,
  `guardian_email` varchar(255) DEFAULT NULL,
  `guardian_phone_number` varchar(255) DEFAULT NULL,
  `guardian_occupation` varchar(255) DEFAULT NULL,
  `guardian_address` text DEFAULT NULL,
  `guardian_profile_image` varchar(255) DEFAULT NULL,
  `mother_tongue` varchar(255) DEFAULT NULL,
  `languages_known` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`languages_known`)),
  `current_address` text DEFAULT NULL,
  `permanent_address` text DEFAULT NULL,
  `transport_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `pickup_point_id` bigint(20) UNSIGNED DEFAULT NULL,
  `hostel_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `hostel_id` bigint(20) UNSIGNED DEFAULT NULL,
  `room_id` bigint(20) UNSIGNED DEFAULT NULL,
  `medical_condition_document` varchar(255) DEFAULT NULL,
  `transfer_certificate_document` varchar(255) DEFAULT NULL,
  `medical_condition_status` varchar(255) DEFAULT NULL,
  `allergies` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`allergies`)),
  `medications` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`medications`)),
  `previous_school_name` varchar(255) DEFAULT NULL,
  `previous_school_address` text DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `ifsc_number` varchar(255) DEFAULT NULL,
  `other_information` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `school_id`, `first_name`, `last_name`, `email`, `password`, `academic_number`, `student_id`, `admission_number`, `roll_number`, `class_id`, `section_id`, `gender`, `dob`, `blood_group`, `religion`, `house`, `category`, `primary_contact`, `admission_date`, `academic_year`, `status`, `profile_image`, `parent_id`, `created_at`, `updated_at`, `deleted_at`, `father_name`, `father_email`, `father_phone_number`, `father_occupation`, `father_profile_image`, `mother_name`, `mother_email`, `mother_phone_number`, `mother_occupation`, `mother_profile_image`, `guardian_type`, `guardian_name`, `guardian_relation`, `guardian_email`, `guardian_phone_number`, `guardian_occupation`, `guardian_address`, `guardian_profile_image`, `mother_tongue`, `languages_known`, `current_address`, `permanent_address`, `transport_enabled`, `pickup_point_id`, `hostel_enabled`, `hostel_id`, `room_id`, `medical_condition_document`, `transfer_certificate_document`, `medical_condition_status`, `allergies`, `medications`, `previous_school_name`, `previous_school_address`, `bank_name`, `branch`, `ifsc_number`, `other_information`) VALUES
(8, 5, 'rohit', 'rawat', 'riwaban758@motivue.com', '$2y$12$zo0ZvWzcYdLWeAw6TrO9vuG4VQaHQgUyP77PPOl2/IdAi0CdbQUza', 'ADM20254LQPV0', 'STUNTHBMXPV', 'ADM20254LQPV0', NULL, 1, 1, 'male', '2003-06-26', 'O+', 'hinduism', 'red', 'general', '9988776656', '2025-06-17', 'June 2025/26', 'active', 'student_files/rohit_rawat/q5HXaKnbbxVYfvH4CLjRUdUJ7VlKUcZZsKWmNjht.jpg', NULL, '2025-06-05 04:53:07', '2025-06-05 04:53:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `school_id`, `name`, `code`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, 'Mathematics', '101', NULL, 1, '2025-05-31 06:11:46', '2025-05-31 06:11:46'),
(2, 5, 'Physics', '102', NULL, 1, '2025-05-31 06:29:52', '2025-05-31 06:29:52');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `primary_contact` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `date_of_joining` date DEFAULT NULL,
  `blood_group` varchar(255) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `marital_status` varchar(255) DEFAULT NULL,
  `languages_known` text DEFAULT NULL,
  `qualification` varchar(255) DEFAULT NULL,
  `work_experience` varchar(255) DEFAULT NULL,
  `previous_school` varchar(255) DEFAULT NULL,
  `previous_school_address` varchar(255) DEFAULT NULL,
  `previous_school_phone` varchar(255) DEFAULT NULL,
  `pan_number` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `notes` text DEFAULT NULL,
  `current_address` text DEFAULT NULL,
  `permanent_address` text DEFAULT NULL,
  `epf_no` varchar(255) DEFAULT NULL,
  `basic_salary` decimal(10,2) DEFAULT NULL,
  `contract_type` varchar(255) DEFAULT NULL,
  `work_shift` varchar(255) DEFAULT NULL,
  `work_location` varchar(255) DEFAULT NULL,
  `date_of_leaving` date DEFAULT NULL,
  `medical_leaves` int(11) DEFAULT NULL,
  `casual_leaves` int(11) DEFAULT NULL,
  `maternity_leaves` int(11) DEFAULT NULL,
  `sick_leaves` int(11) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `ifsc_number` varchar(255) DEFAULT NULL,
  `other_information` text DEFAULT NULL,
  `transport_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `pickup_point_id` bigint(20) UNSIGNED DEFAULT NULL,
  `hostel_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `hostel_id` bigint(20) UNSIGNED DEFAULT NULL,
  `room_id` bigint(20) UNSIGNED DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `medical_condition_document` varchar(255) DEFAULT NULL,
  `transfer_certificate_document` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `school_id`, `employee_id`, `first_name`, `last_name`, `email`, `password`, `gender`, `primary_contact`, `subject`, `date_of_birth`, `date_of_joining`, `blood_group`, `father_name`, `mother_name`, `marital_status`, `languages_known`, `qualification`, `work_experience`, `previous_school`, `previous_school_address`, `previous_school_phone`, `pan_number`, `status`, `notes`, `current_address`, `permanent_address`, `epf_no`, `basic_salary`, `contract_type`, `work_shift`, `work_location`, `date_of_leaving`, `medical_leaves`, `casual_leaves`, `maternity_leaves`, `sick_leaves`, `bank_name`, `branch`, `ifsc_number`, `other_information`, `transport_enabled`, `pickup_point_id`, `hostel_enabled`, `hostel_id`, `room_id`, `profile_image`, `medical_condition_document`, `transfer_certificate_document`, `remember_token`, `created_at`, `updated_at`) VALUES
(3, 5, 'T20255569', 'Teacher', 'Srivastava', 'teacher@gmail.com', '$2y$12$RQaVXyjzOs9tmgGOSCV/FuVXrWnkPTJJ0juJFYuwm5lNwmiYPbf9S', 'female', '5555666644', 'biology', '1994-05-05', '2025-05-04', 'AB+', 'Father Singh', 'Mother Singh', 'single', 'English,Hindi', 'B-Com', '2', 'csbvvvkbk', 'Address', '7567567567', 'ppppp0111x', 'active', NULL, 'ljsdbfidsbfi sfksibfibdsf fijsdfk fjbijbib', 'jisbdvjkbsdvi vbsidvbisdv bvidsjbvkbsd jsv', '3298468', 10000.00, 'permanent', NULL, 'Laxmi Nagar', '2031-05-04', 10, 10, 100, 10, 'Bank Name', 'Branch', 'IFSC Number', 'sajhvbjkdsbvkbsdvk sk bvkjb bkabs', 1, 14, 1, 1, 1, 'teachers/profile/gnSh65UVVRcAxJot2hY0EoaoScp0UBwsiZ0x7183.jpg', 'teachers/documents/ii34GVQUrI5Lb0d3PhtnHwthlN0WeIGH91utNB1m.pdf', 'teachers/documents/WzWX8FX49ASX9dBTBsaYwx93xEN9JrfJEJNrEIkK.pdf', NULL, '2025-06-05 01:49:52', '2025-06-05 04:58:12');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_subject`
--

CREATE TABLE `teacher_subject` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','teacher','finance','library','administration','school','saasAdmin','staff') NOT NULL DEFAULT 'student',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `school_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `name`, `email`, `username`, `phone`, `address`, `date_of_birth`, `gender`, `is_active`, `last_login_at`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`, `school_id`) VALUES
(1, NULL, NULL, 'Shrayansh Srivastava', 'mcs@gmail.com', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '$2y$12$qAXw95s5s3Ytvy5RWSo95u9oBGvmWZVvrtFDxKPb61DkcXfZ26qdO', 'saasAdmin', NULL, '2025-05-30 06:48:21', '2025-05-30 06:48:21', NULL),
(5, NULL, NULL, 'Little Flower School', 'little@gmail.com', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '$2y$12$gLJKlbRrl3bD40.QeclmcuznxRWjMLznTaBqRdKBeDb9LbZGgbMES', 'school', NULL, '2025-05-31 00:57:27', '2025-05-31 00:57:27', NULL),
(10, 'Amin', 'Toofani', 'Amin Toofani', 'amin@gmail.com', 'atoofani', NULL, NULL, NULL, NULL, 1, NULL, NULL, '$2y$12$1sSooFYehUnbvpOQSAITnuG7QILouLNekqVZXKeMWnN3fnMOq8t0W', 'library', NULL, '2025-05-31 02:59:24', '2025-05-31 04:09:10', 5),
(11, NULL, NULL, 'My Class Status', 'mcs1@gmail.com', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '$2y$12$nz/IprduEgmqgc/lbyIp1.pt31ZBkOIiIay.3AQv78dAnVWk.Ir0S', 'saasAdmin', NULL, '2025-05-31 03:56:04', '2025-05-31 03:56:04', NULL),
(12, 'Izack', 'Newton', 'Izack Newton', 'izack@gmail.com', 'inewton', NULL, NULL, NULL, NULL, 1, NULL, NULL, '$2y$12$DpvUOj3Z0xx3ulclAzWyGu3BTY30lkH4TG38/HfzXHWFEUxPnzm3W', 'administration', '2Yv7z2ckHHjeI9xZ5oRVxto13hs1pUxdNmgSlNvZzbRDQSVkFxFP9KswYuKE', '2025-05-31 04:33:10', '2025-06-03 05:30:41', 5);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 10, 1, '2025-05-31 02:59:24', '2025-05-31 02:59:24'),
(2, 12, 2, '2025-05-31 04:33:10', '2025-05-31 04:33:10'),
(3, 5, 3, '2025-05-31 04:51:46', '2025-05-31 04:51:46');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_no` varchar(255) NOT NULL,
  `vehicle_model` varchar(255) NOT NULL,
  `made_year` varchar(255) NOT NULL,
  `registration_no` varchar(255) NOT NULL,
  `chassis_no` varchar(255) NOT NULL,
  `seat_capacity` int(11) NOT NULL,
  `gps_tracking_id` varchar(255) DEFAULT NULL,
  `driver_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `school_id`, `vehicle_no`, `vehicle_model`, `made_year`, `registration_no`, `chassis_no`, `seat_capacity`, `gps_tracking_id`, `driver_id`, `status`, `created_at`, `updated_at`) VALUES
(6, 5, 'BUS-001', 'Tata Ultra School Bus', '2022', 'DL01AB1234', 'TATA123456789', 40, NULL, NULL, 1, '2025-06-03 06:17:45', '2025-06-03 06:17:45'),
(7, 5, 'BUS-002', 'Ashok Leyland Sunshine', '2021', 'DL01CD5678', 'ASHOK987654321', 35, NULL, NULL, 1, '2025-06-03 06:17:45', '2025-06-03 06:17:45'),
(8, 5, 'VAN-001', 'Force Traveller', '2023', 'DL01EF9012', 'FORCE123789456', 15, NULL, NULL, 1, '2025-06-03 06:17:45', '2025-06-03 06:17:45'),
(9, 5, 'UP53CH1111', 'Swift Dzire', '2020', '3242342423', '3423423432432', 5, '322432432423423', 2, 1, '2025-06-03 06:34:56', '2025-06-03 06:34:56');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_drivers`
--

CREATE TABLE `vehicle_drivers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED NOT NULL,
  `driver_name` varchar(255) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `license_number` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicle_drivers`
--

INSERT INTO `vehicle_drivers` (`id`, `school_id`, `driver_name`, `contact_number`, `license_number`, `address`, `profile_photo`, `status`, `created_at`, `updated_at`) VALUES
(2, 5, 'Shama', '7535873773', 'LC7899456689', '25 Crowfield Road, Phoenix', 'driver_photos/X63VfEFsUTx5FKqIDS2BA23nH8Bq6YZVvSxSRpRp.jpg', 1, '2025-06-03 01:42:23', '2025-06-03 01:42:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `class_subject`
--
ALTER TABLE `class_subject`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `class_subject_class_id_subject_id_unique` (`class_id`,`subject_id`),
  ADD KEY `class_subject_subject_id_foreign` (`subject_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `features`
--
ALTER TABLE `features`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `features_code_unique` (`code`);

--
-- Indexes for table `fee_groups`
--
ALTER TABLE `fee_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fee_groups_school_id_foreign` (`school_id`);

--
-- Indexes for table `fee_types`
--
ALTER TABLE `fee_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fee_types_unique_id_unique` (`unique_id`),
  ADD KEY `fee_types_school_id_foreign` (`school_id`),
  ADD KEY `fee_types_fee_group_id_foreign` (`fee_group_id`);

--
-- Indexes for table `hostels`
--
ALTER TABLE `hostels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hostels_school_id_name_unique` (`school_id`,`name`);

--
-- Indexes for table `hostel_rooms`
--
ALTER TABLE `hostel_rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hostel_rooms_school_id_hostel_id_room_number_unique` (`school_id`,`hostel_id`,`room_number`),
  ADD KEY `hostel_rooms_hostel_id_foreign` (`hostel_id`),
  ADD KEY `hostel_rooms_room_type_id_foreign` (`room_type_id`);

--
-- Indexes for table `hostel_room_types`
--
ALTER TABLE `hostel_room_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hostel_room_types_school_id_name_unique` (`school_id`,`name`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

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
  ADD UNIQUE KEY `permissions_feature_id_action_unique` (`feature_id`,`action`),
  ADD UNIQUE KEY `permissions_slug_unique` (`slug`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `pickup_points`
--
ALTER TABLE `pickup_points`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pickup_points_route_detail_id_foreign` (`route_detail_id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plan_features`
--
ALTER TABLE `plan_features`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plan_features_plan_id_feature_id_unique` (`plan_id`,`feature_id`),
  ADD KEY `plan_features_feature_id_foreign` (`feature_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_school_id_unique` (`name`,`school_id`),
  ADD UNIQUE KEY `roles_slug_unique` (`slug`),
  ADD KEY `roles_school_id_foreign` (`school_id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_permissions_role_id_permission_id_unique` (`role_id`,`permission_id`),
  ADD KEY `role_permissions_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `route_assignments`
--
ALTER TABLE `route_assignments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `route_assignments_school_id_route_detail_id_unique` (`school_id`,`route_detail_id`),
  ADD KEY `route_assignments_route_detail_id_foreign` (`route_detail_id`),
  ADD KEY `route_assignments_vehicle_id_foreign` (`vehicle_id`),
  ADD KEY `route_assignments_driver_id_foreign` (`driver_id`);

--
-- Indexes for table `route_details`
--
ALTER TABLE `route_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `route_details_school_id_foreign` (`school_id`);

--
-- Indexes for table `rules`
--
ALTER TABLE `rules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rules_school_id_foreign` (`school_id`),
  ADD KEY `rules_rule_category_id_foreign` (`rule_category_id`);

--
-- Indexes for table `rule_categories`
--
ALTER TABLE `rule_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rule_categories_school_id_foreign` (`school_id`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `schools_email_unique` (`email`),
  ADD KEY `schools_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `school_classes`
--
ALTER TABLE `school_classes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `school_classes_school_id_name_section_id_unique` (`school_id`,`name`,`section_id`),
  ADD KEY `school_classes_section_id_foreign` (`section_id`);

--
-- Indexes for table `school_subscriptions`
--
ALTER TABLE `school_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `school_subscriptions_school_id_foreign` (`school_id`),
  ADD KEY `school_subscriptions_plan_id_foreign` (`plan_id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sections_school_id_name_unique` (`school_id`,`name`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_email_unique` (`email`),
  ADD UNIQUE KEY `students_academic_number_unique` (`academic_number`),
  ADD UNIQUE KEY `students_student_id_unique` (`student_id`),
  ADD KEY `students_school_id_foreign` (`school_id`),
  ADD KEY `students_class_id_foreign` (`class_id`),
  ADD KEY `students_section_id_foreign` (`section_id`),
  ADD KEY `students_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subjects_school_id_code_unique` (`school_id`,`code`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `teachers_employee_id_unique` (`employee_id`),
  ADD UNIQUE KEY `teachers_email_unique` (`email`),
  ADD KEY `teachers_school_id_foreign` (`school_id`),
  ADD KEY `teachers_pickup_point_id_foreign` (`pickup_point_id`),
  ADD KEY `teachers_hostel_id_foreign` (`hostel_id`),
  ADD KEY `teachers_room_id_foreign` (`room_id`);

--
-- Indexes for table `teacher_subject`
--
ALTER TABLE `teacher_subject`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `teacher_subject_teacher_id_subject_id_unique` (`teacher_id`,`subject_id`),
  ADD KEY `teacher_subject_subject_id_foreign` (`subject_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_school_id_foreign` (`school_id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_roles_user_id_role_id_unique` (`user_id`,`role_id`),
  ADD KEY `user_roles_role_id_foreign` (`role_id`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vehicles_registration_no_unique` (`registration_no`),
  ADD UNIQUE KEY `vehicles_chassis_no_unique` (`chassis_no`),
  ADD KEY `vehicles_school_id_foreign` (`school_id`),
  ADD KEY `vehicles_driver_id_foreign` (`driver_id`);

--
-- Indexes for table `vehicle_drivers`
--
ALTER TABLE `vehicle_drivers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vehicle_drivers_license_number_unique` (`license_number`),
  ADD KEY `vehicle_drivers_school_id_foreign` (`school_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `class_subject`
--
ALTER TABLE `class_subject`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `features`
--
ALTER TABLE `features`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `fee_groups`
--
ALTER TABLE `fee_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `fee_types`
--
ALTER TABLE `fee_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `hostels`
--
ALTER TABLE `hostels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hostel_rooms`
--
ALTER TABLE `hostel_rooms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `hostel_room_types`
--
ALTER TABLE `hostel_room_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pickup_points`
--
ALTER TABLE `pickup_points`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `plan_features`
--
ALTER TABLE `plan_features`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=677;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `route_assignments`
--
ALTER TABLE `route_assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `route_details`
--
ALTER TABLE `route_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `rules`
--
ALTER TABLE `rules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rule_categories`
--
ALTER TABLE `rule_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `school_classes`
--
ALTER TABLE `school_classes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `school_subscriptions`
--
ALTER TABLE `school_subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `teacher_subject`
--
ALTER TABLE `teacher_subject`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `vehicle_drivers`
--
ALTER TABLE `vehicle_drivers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `class_subject`
--
ALTER TABLE `class_subject`
  ADD CONSTRAINT `class_subject_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `school_classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_subject_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fee_groups`
--
ALTER TABLE `fee_groups`
  ADD CONSTRAINT `fee_groups_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fee_types`
--
ALTER TABLE `fee_types`
  ADD CONSTRAINT `fee_types_fee_group_id_foreign` FOREIGN KEY (`fee_group_id`) REFERENCES `fee_groups` (`id`),
  ADD CONSTRAINT `fee_types_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hostels`
--
ALTER TABLE `hostels`
  ADD CONSTRAINT `hostels_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hostel_rooms`
--
ALTER TABLE `hostel_rooms`
  ADD CONSTRAINT `hostel_rooms_hostel_id_foreign` FOREIGN KEY (`hostel_id`) REFERENCES `hostels` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hostel_rooms_room_type_id_foreign` FOREIGN KEY (`room_type_id`) REFERENCES `hostel_room_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hostel_rooms_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hostel_room_types`
--
ALTER TABLE `hostel_room_types`
  ADD CONSTRAINT `hostel_room_types_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `permissions`
--
ALTER TABLE `permissions`
  ADD CONSTRAINT `permissions_feature_id_foreign` FOREIGN KEY (`feature_id`) REFERENCES `features` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pickup_points`
--
ALTER TABLE `pickup_points`
  ADD CONSTRAINT `pickup_points_route_detail_id_foreign` FOREIGN KEY (`route_detail_id`) REFERENCES `route_details` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `plan_features`
--
ALTER TABLE `plan_features`
  ADD CONSTRAINT `plan_features_feature_id_foreign` FOREIGN KEY (`feature_id`) REFERENCES `features` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `plan_features_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `roles`
--
ALTER TABLE `roles`
  ADD CONSTRAINT `roles_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `route_details`
--
ALTER TABLE `route_details`
  ADD CONSTRAINT `route_details_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_hostel_id_foreign` FOREIGN KEY (`hostel_id`) REFERENCES `hostels` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `teachers_pickup_point_id_foreign` FOREIGN KEY (`pickup_point_id`) REFERENCES `pickup_points` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `teachers_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `hostel_rooms` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `teachers_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
