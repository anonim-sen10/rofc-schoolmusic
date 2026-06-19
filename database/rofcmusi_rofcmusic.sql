-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 13 Jun 2026 pada 00.14
-- Versi server: 11.4.12-MariaDB
-- Versi PHP: 8.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rofcmusi_rofcmusic`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `activities`
--

CREATE TABLE `activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `module` varchar(255) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `activities`
--

INSERT INTO `activities` (`id`, `user_id`, `module`, `action`, `description`, `meta`, `created_at`, `updated_at`) VALUES
(1, 2, 'student', 'created', 'created student: Jokowi Dodo', '[]', '2026-05-10 10:45:27', '2026-05-10 10:45:27'),
(2, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-10 10:45:28', '2026-05-10 10:45:28'),
(3, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-10 10:45:28', '2026-05-10 10:45:28'),
(4, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-10 10:45:28', '2026-05-10 10:45:28'),
(5, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-10 10:45:28', '2026-05-10 10:45:28'),
(6, 2, 'registration', 'updated', 'updated registration: Jokowi Dodo', '{\"old\":{\"status\":\"pending\",\"updated_at\":\"2026-05-10T17:40:16.000000Z\"},\"new\":{\"status\":\"accepted\",\"updated_at\":\"2026-05-10 17:45:28\"}}', '2026-05-10 10:45:28', '2026-05-10 10:45:28'),
(7, 2, 'student', 'deleted', 'deleted student: Zahra', '[]', '2026-05-12 09:59:27', '2026-05-12 09:59:27'),
(8, 20, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-12 10:13:57', '2026-05-12 10:13:57'),
(9, 20, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-10T17:45:28.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-12 17:13:57\"}}', '2026-05-12 10:13:57', '2026-05-12 10:13:57'),
(10, 2, 'registration', 'created', 'created registration: rofcstudent', '[]', '2026-05-13 06:23:49', '2026-05-13 06:23:49'),
(11, 2, 'student', 'created', 'created student: rofcstudent', '[]', '2026-05-13 06:24:11', '2026-05-13 06:24:11'),
(12, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-13 06:24:11', '2026-05-13 06:24:11'),
(13, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-13 06:24:11', '2026-05-13 06:24:11'),
(14, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-13 06:24:11', '2026-05-13 06:24:11'),
(15, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-13 06:24:11', '2026-05-13 06:24:11'),
(16, 2, 'registration', 'updated', 'updated registration: rofcstudent', '{\"old\":{\"status\":\"pending\",\"updated_at\":\"2026-05-13T13:23:49.000000Z\"},\"new\":{\"status\":\"accepted\",\"updated_at\":\"2026-05-13 13:24:11\"}}', '2026-05-13 06:24:11', '2026-05-13 06:24:11'),
(17, 2, 'teacher', 'created', 'created teacher: NIZAM', '[]', '2026-05-13 06:41:18', '2026-05-13 06:41:18'),
(18, 2, 'student', 'created', 'created student: andi', '[]', '2026-05-13 06:51:39', '2026-05-13 06:51:39'),
(19, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-13 06:51:39', '2026-05-13 06:51:39'),
(20, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-13 06:51:39', '2026-05-13 06:51:39'),
(21, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-13 06:51:39', '2026-05-13 06:51:39'),
(22, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-13 06:51:39', '2026-05-13 06:51:39'),
(23, 2, 'registration', 'updated', 'updated registration: andi', '{\"old\":{\"status\":\"pending\",\"updated_at\":\"2026-05-13T13:50:03.000000Z\"},\"new\":{\"status\":\"accepted\",\"updated_at\":\"2026-05-13 13:51:39\"}}', '2026-05-13 06:51:39', '2026-05-13 06:51:39'),
(24, 20, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-13 06:54:19', '2026-05-13 06:54:19'),
(25, 20, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-13T13:24:11.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-13 13:54:19\"}}', '2026-05-13 06:54:19', '2026-05-13 06:54:19'),
(26, 2, 'teacher', 'created', 'created teacher: ZAMZAM KAMIL', '[]', '2026-05-13 12:24:34', '2026-05-13 12:24:34'),
(27, 2, 'teacher', 'updated', 'updated teacher: ZAMZAM KAMIL', '{\"old\":{\"instrument\":\"Drum\",\"updated_at\":\"2026-05-13T19:24:34.000000Z\"},\"new\":{\"instrument\":\"Piano\",\"updated_at\":\"2026-05-13 19:24:49\"}}', '2026-05-13 12:24:49', '2026-05-13 12:24:49'),
(28, 2, 'musicclass', 'created', 'created musicclass: Vocal', '[]', '2026-05-13 13:08:03', '2026-05-13 13:08:03'),
(29, 2, 'musicclass', 'updated', 'updated musicclass: Piano', '{\"old\":{\"name\":\"Vocal\",\"updated_at\":\"2026-05-13T20:08:03.000000Z\"},\"new\":{\"name\":\"Piano\",\"updated_at\":\"2026-05-13 20:08:25\"}}', '2026-05-13 13:08:25', '2026-05-13 13:08:25'),
(30, 2, 'registration', 'created', 'created registration: TESS', '[]', '2026-05-13 13:12:39', '2026-05-13 13:12:39'),
(31, 2, 'student', 'created', 'created student: TESS', '[]', '2026-05-13 13:13:28', '2026-05-13 13:13:28'),
(32, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-13 13:13:28', '2026-05-13 13:13:28'),
(33, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-13 13:13:28', '2026-05-13 13:13:28'),
(34, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-13 13:13:28', '2026-05-13 13:13:28'),
(35, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-13 13:13:28', '2026-05-13 13:13:28'),
(36, 2, 'registration', 'updated', 'updated registration: TESS', '{\"old\":{\"status\":\"pending\",\"updated_at\":\"2026-05-13T20:12:39.000000Z\"},\"new\":{\"status\":\"accepted\",\"updated_at\":\"2026-05-13 20:13:28\"}}', '2026-05-13 13:13:28', '2026-05-13 13:13:28'),
(37, 2, 'registration', 'deleted', 'deleted registration: saddam abrizam detik', '[]', '2026-05-15 01:43:58', '2026-05-15 01:43:58'),
(38, 2, 'registration', 'created', 'created registration: saddam abrizam detik', '[]', '2026-05-15 01:51:00', '2026-05-15 01:51:00'),
(39, 2, 'student', 'created', 'created student: saddam abrizam detik', '[]', '2026-05-15 01:51:17', '2026-05-15 01:51:17'),
(40, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-15 01:51:17', '2026-05-15 01:51:17'),
(41, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-15 01:51:17', '2026-05-15 01:51:17'),
(42, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-15 01:51:17', '2026-05-15 01:51:17'),
(43, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-15 01:51:17', '2026-05-15 01:51:17'),
(44, 2, 'registration', 'updated', 'updated registration: saddam abrizam detik', '{\"old\":{\"status\":\"pending\",\"updated_at\":\"2026-05-15T08:51:00.000000Z\"},\"new\":{\"status\":\"accepted\",\"updated_at\":\"2026-05-15 08:51:17\"}}', '2026-05-15 01:51:17', '2026-05-15 01:51:17'),
(45, 2, 'student', 'updated', 'updated student: saddam abrizam detik', '{\"old\":{\"address\":\"jln merpati putih no.19 pekanbaru\",\"updated_at\":\"2026-05-15T08:51:17.000000Z\"},\"new\":{\"address\":null,\"updated_at\":\"2026-05-15 09:01:58\"}}', '2026-05-15 02:01:58', '2026-05-15 02:01:58'),
(46, 2, 'registration', 'updated', 'updated registration: saddam abrizam detik', '{\"old\":{\"ig_siswa\":null,\"ig_ortu\":null,\"updated_at\":\"2026-05-15T08:51:17.000000Z\"},\"new\":{\"ig_siswa\":\"@saddam_detik\",\"ig_ortu\":\"@desva_tika\",\"updated_at\":\"2026-05-15 09:06:26\"}}', '2026-05-15 02:06:26', '2026-05-15 02:06:26'),
(47, 2, 'student', 'updated', 'updated student: saddam abrizam detik', '{\"old\":{\"tanggal_lahir\":\"2015-06-09\",\"ig_siswa\":null,\"address\":null,\"ig_ortu\":null,\"updated_at\":\"2026-05-15T09:01:58.000000Z\"},\"new\":{\"tanggal_lahir\":\"2015-06-09T00:00:00.000000Z\",\"ig_siswa\":\"@saddam_detik\",\"address\":\"jln merpati putih no.19 pekanbaru\",\"ig_ortu\":\"@desva_tika\",\"updated_at\":\"2026-05-15 09:06:26\"}}', '2026-05-15 02:06:26', '2026-05-15 02:06:26'),
(48, 19, 'registration', 'created', 'created registration: KENRICK ANANTA HO', '[]', '2026-05-15 09:56:59', '2026-05-15 09:56:59'),
(49, 2, 'student', 'created', 'created student: KENRICK ANANTA HO', '[]', '2026-05-15 09:57:29', '2026-05-15 09:57:29'),
(50, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-15 09:57:29', '2026-05-15 09:57:29'),
(51, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-15 09:57:29', '2026-05-15 09:57:29'),
(52, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-15 09:57:29', '2026-05-15 09:57:29'),
(53, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-15 09:57:29', '2026-05-15 09:57:29'),
(54, 2, 'registration', 'updated', 'updated registration: KENRICK ANANTA HO', '{\"old\":{\"status\":\"pending\",\"updated_at\":\"2026-05-15T16:56:59.000000Z\"},\"new\":{\"status\":\"accepted\",\"updated_at\":\"2026-05-15 16:57:29\"}}', '2026-05-15 09:57:29', '2026-05-15 09:57:29'),
(55, 2, 'registration', 'created', 'created registration: Alaric Ava Alteza', '[]', '2026-05-15 10:26:29', '2026-05-15 10:26:29'),
(56, 2, 'registration', 'created', 'created registration: keysha khayira danis', '[]', '2026-05-15 11:00:12', '2026-05-15 11:00:12'),
(57, 2, 'registration', 'updated', 'updated registration: keysha khayira danis', '{\"old\":{\"ig_siswa\":null,\"ig_ortu\":null,\"updated_at\":\"2026-05-15T18:00:12.000000Z\"},\"new\":{\"ig_siswa\":\"@evrtng.keyy\",\"ig_ortu\":\"@nini_susanty\",\"updated_at\":\"2026-05-15 18:17:37\"}}', '2026-05-15 11:17:37', '2026-05-15 11:17:37'),
(58, 19, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-16 03:18:07', '2026-05-16 03:18:07'),
(59, 19, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-15T08:51:17.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-16 10:18:07\"}}', '2026-05-16 03:18:07', '2026-05-16 03:18:07'),
(60, 2, 'student', 'created', 'created student: keysha khayira danis', '[]', '2026-05-16 03:29:58', '2026-05-16 03:29:58'),
(61, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 03:29:58', '2026-05-16 03:29:58'),
(62, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 03:29:58', '2026-05-16 03:29:58'),
(63, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 03:29:58', '2026-05-16 03:29:58'),
(64, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 03:29:58', '2026-05-16 03:29:58'),
(65, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 03:29:58', '2026-05-16 03:29:58'),
(66, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 03:29:58', '2026-05-16 03:29:58'),
(67, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 03:29:58', '2026-05-16 03:29:58'),
(68, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 03:29:58', '2026-05-16 03:29:58'),
(69, 2, 'registration', 'updated', 'updated registration: keysha khayira danis', '{\"old\":{\"status\":\"pending\",\"updated_at\":\"2026-05-15T18:17:37.000000Z\"},\"new\":{\"status\":\"accepted\",\"updated_at\":\"2026-05-16 10:29:58\"}}', '2026-05-16 03:29:58', '2026-05-16 03:29:58'),
(70, 2, 'student', 'created', 'created student: Alaric Ava Alteza', '[]', '2026-05-16 03:30:17', '2026-05-16 03:30:17'),
(71, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 03:30:17', '2026-05-16 03:30:17'),
(72, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 03:30:17', '2026-05-16 03:30:17'),
(73, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 03:30:17', '2026-05-16 03:30:17'),
(74, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 03:30:17', '2026-05-16 03:30:17'),
(75, 2, 'registration', 'updated', 'updated registration: Alaric Ava Alteza', '{\"old\":{\"status\":\"pending\",\"updated_at\":\"2026-05-15T17:26:29.000000Z\"},\"new\":{\"status\":\"accepted\",\"updated_at\":\"2026-05-16 10:30:17\"}}', '2026-05-16 03:30:17', '2026-05-16 03:30:17'),
(76, 21, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-16 03:41:35', '2026-05-16 03:41:35'),
(77, 21, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-16T10:30:17.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-16 10:41:35\"}}', '2026-05-16 03:41:35', '2026-05-16 03:41:35'),
(78, 2, 'registration', 'created', 'created registration: Gilviani Aurelia Chow', '[]', '2026-05-16 03:55:03', '2026-05-16 03:55:03'),
(79, 2, 'student', 'created', 'created student: Gilviani Aurelia Chow', '[]', '2026-05-16 22:18:19', '2026-05-16 22:18:19'),
(80, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 22:18:19', '2026-05-16 22:18:19'),
(81, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 22:18:19', '2026-05-16 22:18:19'),
(82, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 22:18:19', '2026-05-16 22:18:19'),
(83, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 22:18:19', '2026-05-16 22:18:19'),
(84, 2, 'registration', 'updated', 'updated registration: Gilviani Aurelia Chow', '{\"old\":{\"status\":\"pending\",\"updated_at\":\"2026-05-16T10:55:03.000000Z\"},\"new\":{\"status\":\"accepted\",\"updated_at\":\"2026-05-17 05:18:19\"}}', '2026-05-16 22:18:19', '2026-05-16 22:18:19'),
(85, 2, 'registration', 'deleted', 'deleted registration: Dion Avrel', '[]', '2026-05-16 22:24:23', '2026-05-16 22:24:23'),
(86, 2, 'registration', 'deleted', 'deleted registration: Caca', '[]', '2026-05-16 22:24:29', '2026-05-16 22:24:29'),
(87, 2, 'registration', 'deleted', 'deleted registration: Dion Avrel', '[]', '2026-05-16 22:24:35', '2026-05-16 22:24:35'),
(88, 2, 'registration', 'deleted', 'deleted registration: andi saputra', '[]', '2026-05-16 22:24:45', '2026-05-16 22:24:45'),
(89, 2, 'registration', 'created', 'created registration: Hilya Az Zahra Medina', '[]', '2026-05-16 22:36:58', '2026-05-16 22:36:58'),
(90, 2, 'registration', 'updated', 'updated registration: Hilya Az Zahra Medina', '{\"old\":{\"ig_siswa\":null,\"ig_ortu\":null,\"updated_at\":\"2026-05-17T05:36:58.000000Z\"},\"new\":{\"ig_siswa\":\"@babyhilya_20\",\"ig_ortu\":\"@sherli_novia._\",\"updated_at\":\"2026-05-17 05:41:09\"}}', '2026-05-16 22:41:09', '2026-05-16 22:41:09'),
(91, 2, 'student', 'created', 'created student: Hilya Az Zahra Medina', '[]', '2026-05-16 22:41:13', '2026-05-16 22:41:13'),
(92, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 22:41:13', '2026-05-16 22:41:13'),
(93, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 22:41:13', '2026-05-16 22:41:13'),
(94, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 22:41:13', '2026-05-16 22:41:13'),
(95, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 22:41:13', '2026-05-16 22:41:13'),
(96, 2, 'registration', 'updated', 'updated registration: Hilya Az Zahra Medina', '{\"old\":{\"status\":\"pending\",\"updated_at\":\"2026-05-17T05:41:09.000000Z\"},\"new\":{\"status\":\"accepted\",\"updated_at\":\"2026-05-17 05:41:13\"}}', '2026-05-16 22:41:13', '2026-05-16 22:41:13'),
(97, 2, 'registration', 'created', 'created registration: inara ayudia rahman', '[]', '2026-05-16 22:47:01', '2026-05-16 22:47:01'),
(98, 2, 'student', 'created', 'created student: inara ayudia rahman', '[]', '2026-05-16 22:47:57', '2026-05-16 22:47:57'),
(99, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 22:47:57', '2026-05-16 22:47:57'),
(100, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 22:47:57', '2026-05-16 22:47:57'),
(101, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 22:47:57', '2026-05-16 22:47:57'),
(102, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 22:47:57', '2026-05-16 22:47:57'),
(103, 2, 'registration', 'updated', 'updated registration: inara ayudia rahman', '{\"old\":{\"status\":\"pending\",\"updated_at\":\"2026-05-17T05:47:01.000000Z\"},\"new\":{\"status\":\"accepted\",\"updated_at\":\"2026-05-17 05:47:57\"}}', '2026-05-16 22:47:57', '2026-05-16 22:47:57'),
(104, 2, 'registration', 'created', 'created registration: Gladion Shawn Hutahaean', '[]', '2026-05-16 22:51:39', '2026-05-16 22:51:39'),
(105, 2, 'registration', 'created', 'created registration: Rinjani Adara Marzuki', '[]', '2026-05-16 22:55:03', '2026-05-16 22:55:03'),
(106, 2, 'student', 'created', 'created student: Rinjani Adara Marzuki', '[]', '2026-05-16 22:55:39', '2026-05-16 22:55:39'),
(107, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 22:55:39', '2026-05-16 22:55:39'),
(108, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 22:55:39', '2026-05-16 22:55:39'),
(109, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 22:55:39', '2026-05-16 22:55:39'),
(110, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 22:55:39', '2026-05-16 22:55:39'),
(111, 2, 'registration', 'updated', 'updated registration: Rinjani Adara Marzuki', '{\"old\":{\"status\":\"pending\",\"updated_at\":\"2026-05-17T05:55:03.000000Z\"},\"new\":{\"status\":\"accepted\",\"updated_at\":\"2026-05-17 05:55:39\"}}', '2026-05-16 22:55:39', '2026-05-16 22:55:39'),
(112, 2, 'student', 'created', 'created student: Gladion Shawn Hutahaean', '[]', '2026-05-16 22:55:44', '2026-05-16 22:55:44'),
(113, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 22:55:44', '2026-05-16 22:55:44'),
(114, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 22:55:44', '2026-05-16 22:55:44'),
(115, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 22:55:44', '2026-05-16 22:55:44'),
(116, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 22:55:44', '2026-05-16 22:55:44'),
(117, 2, 'registration', 'updated', 'updated registration: Gladion Shawn Hutahaean', '{\"old\":{\"status\":\"pending\",\"updated_at\":\"2026-05-17T05:51:39.000000Z\"},\"new\":{\"status\":\"accepted\",\"updated_at\":\"2026-05-17 05:55:44\"}}', '2026-05-16 22:55:44', '2026-05-16 22:55:44'),
(118, 2, 'registration', 'created', 'created registration: Senja Mandalawangi Marzuki', '[]', '2026-05-16 22:58:41', '2026-05-16 22:58:41'),
(119, 2, 'student', 'created', 'created student: Senja Mandalawangi Marzuki', '[]', '2026-05-16 23:05:08', '2026-05-16 23:05:08'),
(120, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 23:05:08', '2026-05-16 23:05:08'),
(121, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 23:05:08', '2026-05-16 23:05:08'),
(122, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 23:05:08', '2026-05-16 23:05:08'),
(123, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-16 23:05:08', '2026-05-16 23:05:08'),
(124, 2, 'registration', 'updated', 'updated registration: Senja Mandalawangi Marzuki', '{\"old\":{\"status\":\"pending\",\"updated_at\":\"2026-05-17T05:58:41.000000Z\"},\"new\":{\"status\":\"accepted\",\"updated_at\":\"2026-05-17 06:05:08\"}}', '2026-05-16 23:05:08', '2026-05-16 23:05:08'),
(125, 2, 'teacher', 'created', 'created teacher: SHYAKIRA FATIHA', '[]', '2026-05-19 06:57:18', '2026-05-19 06:57:18'),
(126, 2, 'registration', 'created', 'created registration: Winola', '[]', '2026-05-19 07:18:44', '2026-05-19 07:18:44'),
(127, 2, 'student', 'created', 'created student: Winola', '[]', '2026-05-19 07:18:59', '2026-05-19 07:18:59'),
(128, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-19 07:18:59', '2026-05-19 07:18:59'),
(129, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-19 07:18:59', '2026-05-19 07:18:59'),
(130, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-19 07:18:59', '2026-05-19 07:18:59'),
(131, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-19 07:18:59', '2026-05-19 07:18:59'),
(132, 2, 'registration', 'updated', 'updated registration: Winola', '{\"old\":{\"status\":\"pending\",\"updated_at\":\"2026-05-19T14:18:44.000000Z\"},\"new\":{\"status\":\"accepted\",\"updated_at\":\"2026-05-19 14:18:59\"}}', '2026-05-19 07:18:59', '2026-05-19 07:18:59'),
(133, 2, 'registration', 'created', 'created registration: student tes', '[]', '2026-05-19 07:21:23', '2026-05-19 07:21:23'),
(134, 2, 'student', 'created', 'created student: student tes', '[]', '2026-05-19 07:21:32', '2026-05-19 07:21:32'),
(135, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-19 07:21:32', '2026-05-19 07:21:32'),
(136, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-19 07:21:32', '2026-05-19 07:21:32'),
(137, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-19 07:21:32', '2026-05-19 07:21:32'),
(138, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-19 07:21:32', '2026-05-19 07:21:32'),
(139, 2, 'registration', 'updated', 'updated registration: student tes', '{\"old\":{\"status\":\"pending\",\"updated_at\":\"2026-05-19T14:21:23.000000Z\"},\"new\":{\"status\":\"accepted\",\"updated_at\":\"2026-05-19 14:21:32\"}}', '2026-05-19 07:21:32', '2026-05-19 07:21:32'),
(140, 46, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-19 07:25:19', '2026-05-19 07:25:19'),
(141, 46, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-19T14:21:32.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-19 14:25:19\"}}', '2026-05-19 07:25:19', '2026-05-19 07:25:19'),
(142, 2, 'student', 'updated', 'updated student: student tes', '{\"old\":{\"is_active\":true,\"updated_at\":\"2026-05-19T14:21:32.000000Z\"},\"new\":{\"is_active\":false,\"updated_at\":\"2026-05-19 15:20:41\"}}', '2026-05-19 08:20:41', '2026-05-19 08:20:41'),
(143, 2, 'musicclass', 'updated', 'updated musicclass: Piano', '{\"old\":{\"description\":\"Class Piano (Miss. Dewi)\",\"teacher_id\":\"14\",\"updated_at\":\"2026-05-19T15:00:25.000000Z\"},\"new\":{\"description\":\"Class Piano\",\"teacher_id\":\"12\",\"updated_at\":\"2026-05-19 16:05:12\"}}', '2026-05-19 09:05:12', '2026-05-19 09:05:12'),
(144, 2, 'attendance', 'deleted', 'deleted attendance: Record', '[]', '2026-05-19 09:48:33', '2026-05-19 09:48:33'),
(145, 46, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-20 06:20:07', '2026-05-20 06:20:07'),
(146, 46, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-19T14:18:59.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-20 13:20:07\"}}', '2026-05-20 06:20:07', '2026-05-20 06:20:07'),
(147, 2, 'teacher', 'created', 'created teacher: TRI SUTRISNO', '[]', '2026-05-21 05:54:03', '2026-05-21 05:54:03'),
(148, 2, 'registration', 'created', 'created registration: Cesca', '[]', '2026-05-21 06:08:55', '2026-05-21 06:08:55'),
(149, 2, 'student', 'created', 'created student: Cesca', '[]', '2026-05-21 06:12:13', '2026-05-21 06:12:13'),
(150, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-21 06:12:13', '2026-05-21 06:12:13'),
(151, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-21 06:12:13', '2026-05-21 06:12:13'),
(152, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-21 06:12:13', '2026-05-21 06:12:13'),
(153, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-21 06:12:13', '2026-05-21 06:12:13'),
(154, 2, 'registration', 'updated', 'updated registration: Cesca', '{\"old\":{\"status\":\"pending\",\"updated_at\":\"2026-05-21T13:08:55.000000Z\"},\"new\":{\"status\":\"accepted\",\"updated_at\":\"2026-05-21 13:12:13\"}}', '2026-05-21 06:12:13', '2026-05-21 06:12:13'),
(155, 2, 'registration', 'created', 'created registration: Fai', '[]', '2026-05-21 06:20:47', '2026-05-21 06:20:47'),
(156, 2, 'student', 'created', 'created student: Fai', '[]', '2026-05-21 06:21:02', '2026-05-21 06:21:02'),
(157, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-21 06:21:02', '2026-05-21 06:21:02'),
(158, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-21 06:21:02', '2026-05-21 06:21:02'),
(159, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-21 06:21:02', '2026-05-21 06:21:02'),
(160, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-21 06:21:02', '2026-05-21 06:21:02'),
(161, 2, 'registration', 'updated', 'updated registration: Fai', '{\"old\":{\"status\":\"pending\",\"updated_at\":\"2026-05-21T13:20:47.000000Z\"},\"new\":{\"status\":\"accepted\",\"updated_at\":\"2026-05-21 13:21:02\"}}', '2026-05-21 06:21:02', '2026-05-21 06:21:02'),
(162, 2, 'registration', 'updated', 'updated registration: KENRICK ANANTA HO', '{\"old\":{\"pekerjaan_ortu\":null,\"updated_at\":\"2026-05-15T16:57:29.000000Z\"},\"new\":{\"pekerjaan_ortu\":\"-\",\"updated_at\":\"2026-05-21 14:18:57\"}}', '2026-05-21 07:18:57', '2026-05-21 07:18:57'),
(163, 2, 'student', 'updated', 'updated student: KENRICK ANANTA HO', '{\"old\":{\"tanggal_lahir\":\"2017-05-17\",\"pekerjaan_ortu\":null,\"updated_at\":\"2026-05-15T16:57:29.000000Z\"},\"new\":{\"tanggal_lahir\":\"2017-05-17T00:00:00.000000Z\",\"pekerjaan_ortu\":\"-\",\"updated_at\":\"2026-05-21 14:18:58\"}}', '2026-05-21 07:18:58', '2026-05-21 07:18:58'),
(164, 2, 'registration', 'created', 'created registration: Akil', '[]', '2026-05-21 09:19:59', '2026-05-21 09:19:59'),
(165, 2, 'student', 'created', 'created student: Akil', '[]', '2026-05-21 09:21:10', '2026-05-21 09:21:10'),
(166, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-21 09:21:10', '2026-05-21 09:21:10'),
(167, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-21 09:21:10', '2026-05-21 09:21:10'),
(168, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-21 09:21:10', '2026-05-21 09:21:10'),
(169, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-21 09:21:10', '2026-05-21 09:21:10'),
(170, 2, 'registration', 'updated', 'updated registration: Akil', '{\"old\":{\"status\":\"pending\",\"updated_at\":\"2026-05-21T16:19:59.000000Z\"},\"new\":{\"status\":\"accepted\",\"updated_at\":\"2026-05-21 16:21:10\"}}', '2026-05-21 09:21:10', '2026-05-21 09:21:10'),
(171, 2, 'registration', 'created', 'created registration: Zahra', '[]', '2026-05-21 09:58:37', '2026-05-21 09:58:37'),
(172, 2, 'student', 'created', 'created student: Zahra', '[]', '2026-05-21 09:59:04', '2026-05-21 09:59:04'),
(173, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-21 09:59:04', '2026-05-21 09:59:04'),
(174, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-21 09:59:04', '2026-05-21 09:59:04'),
(175, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-21 09:59:04', '2026-05-21 09:59:04'),
(176, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-21 09:59:04', '2026-05-21 09:59:04'),
(177, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-21 09:59:04', '2026-05-21 09:59:04'),
(178, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-21 09:59:04', '2026-05-21 09:59:04'),
(179, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-21 09:59:04', '2026-05-21 09:59:04'),
(180, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-21 09:59:04', '2026-05-21 09:59:04'),
(181, 2, 'registration', 'updated', 'updated registration: Zahra', '{\"old\":{\"status\":\"pending\",\"updated_at\":\"2026-05-21T16:58:37.000000Z\"},\"new\":{\"status\":\"accepted\",\"updated_at\":\"2026-05-21 16:59:04\"}}', '2026-05-21 09:59:04', '2026-05-21 09:59:04'),
(182, 20, 'registration', 'created', 'created registration: Aqila', '[]', '2026-05-21 10:03:15', '2026-05-21 10:03:15'),
(183, 2, 'student', 'created', 'created student: Aqila', '[]', '2026-05-21 10:03:36', '2026-05-21 10:03:36'),
(184, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-21 10:03:36', '2026-05-21 10:03:36'),
(185, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-21 10:03:36', '2026-05-21 10:03:36'),
(186, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-21 10:03:36', '2026-05-21 10:03:36'),
(187, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-21 10:03:36', '2026-05-21 10:03:36'),
(188, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-21 10:03:36', '2026-05-21 10:03:36'),
(189, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-21 10:03:36', '2026-05-21 10:03:36'),
(190, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-21 10:03:36', '2026-05-21 10:03:36'),
(191, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-21 10:03:36', '2026-05-21 10:03:36'),
(192, 2, 'registration', 'updated', 'updated registration: Aqila', '{\"old\":{\"status\":\"pending\",\"updated_at\":\"2026-05-21T17:03:15.000000Z\"},\"new\":{\"status\":\"accepted\",\"updated_at\":\"2026-05-21 17:03:36\"}}', '2026-05-21 10:03:36', '2026-05-21 10:03:36'),
(193, 19, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-22 02:26:07', '2026-05-22 02:26:07'),
(194, 19, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-21T13:21:02.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-22 09:26:07\"}}', '2026-05-22 02:26:07', '2026-05-22 02:26:07'),
(195, 22, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-22 02:51:39', '2026-05-22 02:51:39'),
(196, 22, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-17T05:41:13.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-22 09:51:39\"}}', '2026-05-22 02:51:39', '2026-05-22 02:51:39'),
(197, 22, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-22 02:53:27', '2026-05-22 02:53:27'),
(198, 22, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-21T13:12:13.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-22 09:53:27\"}}', '2026-05-22 02:53:27', '2026-05-22 02:53:27'),
(199, 22, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-22 21:23:04', '2026-05-22 21:23:04'),
(200, 22, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-17T05:18:19.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-23 04:23:04\"}}', '2026-05-22 21:23:04', '2026-05-22 21:23:04'),
(201, 2, 'registration', 'created', 'created registration: Rayzent', '[]', '2026-05-25 04:49:25', '2026-05-25 04:49:25'),
(202, 2, 'student', 'created', 'created student: Rayzent', '[]', '2026-05-25 04:49:34', '2026-05-25 04:49:34'),
(203, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-25 04:49:34', '2026-05-25 04:49:34'),
(204, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-25 04:49:34', '2026-05-25 04:49:34'),
(205, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-25 04:49:34', '2026-05-25 04:49:34'),
(206, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-25 04:49:34', '2026-05-25 04:49:34'),
(207, 2, 'registration', 'updated', 'updated registration: Rayzent', '{\"old\":{\"status\":\"pending\",\"updated_at\":\"2026-05-25T11:49:25.000000Z\"},\"new\":{\"status\":\"accepted\",\"updated_at\":\"2026-05-25 11:49:34\"}}', '2026-05-25 04:49:34', '2026-05-25 04:49:34'),
(208, 49, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-25 04:57:41', '2026-05-25 04:57:41'),
(209, 49, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-25T11:49:34.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-25 11:57:41\"}}', '2026-05-25 04:57:41', '2026-05-25 04:57:41'),
(210, 2, 'teacher', 'updated', 'updated teacher: SHYAKIRA FATIHA', '{\"old\":{\"phone\":\"+62858-3754-8792\",\"updated_at\":\"2026-05-19T13:57:18.000000Z\"},\"new\":{\"phone\":\"085837548792\",\"updated_at\":\"2026-05-27 12:53:55\"}}', '2026-05-27 05:53:55', '2026-05-27 05:53:55'),
(211, 2, 'teacher', 'updated', 'updated teacher: TRI SUTRISNO', '{\"old\":{\"phone\":\"+62 852-6343-2288\",\"updated_at\":\"2026-05-21T12:54:03.000000Z\"},\"new\":{\"phone\":\"085263432288\",\"updated_at\":\"2026-05-27 12:54:14\"}}', '2026-05-27 05:54:14', '2026-05-27 05:54:14'),
(212, 2, 'musicclass', 'updated', 'updated musicclass: Vocal', '{\"old\":{\"description\":\"Class Vocal (ABDUL HAMID)\",\"teacher_id\":\"15\",\"updated_at\":\"2026-05-27T12:53:55.000000Z\"},\"new\":{\"description\":\"Class Vocal\",\"teacher_id\":\"9\",\"updated_at\":\"2026-05-27 13:00:46\"}}', '2026-05-27 06:00:46', '2026-05-27 06:00:46'),
(213, 2, 'registration', 'created', 'created registration: Gracio', '[]', '2026-05-27 06:03:57', '2026-05-27 06:03:57'),
(214, 2, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-27T11:53:02.000000Z\"},\"new\":{\"status\":\"rescheduled\",\"updated_at\":\"2026-05-27 13:23:15\"}}', '2026-05-27 06:23:15', '2026-05-27 06:23:15'),
(215, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-27 06:23:15', '2026-05-27 06:23:15'),
(216, 2, 'student', 'created', 'created student: Gracio', '[]', '2026-05-27 10:42:36', '2026-05-27 10:42:36'),
(217, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-27 10:42:36', '2026-05-27 10:42:36'),
(218, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-27 10:42:36', '2026-05-27 10:42:36'),
(219, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-27 10:42:36', '2026-05-27 10:42:36'),
(220, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-27 10:42:36', '2026-05-27 10:42:36'),
(221, 2, 'registration', 'updated', 'updated registration: Gracio', '{\"old\":{\"status\":\"pending\",\"updated_at\":\"2026-05-27T13:03:57.000000Z\"},\"new\":{\"status\":\"accepted\",\"updated_at\":\"2026-05-27 17:42:36\"}}', '2026-05-27 10:42:36', '2026-05-27 10:42:36'),
(222, 2, 'registration', 'created', 'created registration: Haeden', '[]', '2026-05-27 10:58:48', '2026-05-27 10:58:48'),
(223, 2, 'student', 'created', 'created student: Haeden', '[]', '2026-05-27 10:59:50', '2026-05-27 10:59:50'),
(224, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-27 10:59:50', '2026-05-27 10:59:50'),
(225, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-27 10:59:50', '2026-05-27 10:59:50'),
(226, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-27 10:59:50', '2026-05-27 10:59:50'),
(227, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-27 10:59:50', '2026-05-27 10:59:50'),
(228, 2, 'registration', 'updated', 'updated registration: Haeden', '{\"old\":{\"status\":\"pending\",\"updated_at\":\"2026-05-27T17:58:48.000000Z\"},\"new\":{\"status\":\"accepted\",\"updated_at\":\"2026-05-27 17:59:50\"}}', '2026-05-27 10:59:50', '2026-05-27 10:59:50'),
(229, 20, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-28 01:43:55', '2026-05-28 01:43:55'),
(230, 20, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-28T07:30:04.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-28 08:43:55\"}}', '2026-05-28 01:43:55', '2026-05-28 01:43:55'),
(231, 20, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-28 08:22:30', '2026-05-28 08:22:30'),
(232, 20, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-28T06:30:04.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-28 15:22:30\"}}', '2026-05-28 08:22:30', '2026-05-28 08:22:30'),
(233, 2, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-15T08:51:17.000000Z\"},\"new\":{\"status\":\"rescheduled\",\"updated_at\":\"2026-05-28 15:56:40\"}}', '2026-05-28 08:56:40', '2026-05-28 08:56:40'),
(234, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-28 08:56:40', '2026-05-28 08:56:40'),
(235, 19, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-28 08:59:56', '2026-05-28 08:59:56'),
(236, 19, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-28T15:56:40.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-28 15:59:56\"}}', '2026-05-28 08:59:56', '2026-05-28 08:59:56'),
(237, 20, 'registration', 'created', 'created registration: Jeany', '[]', '2026-05-28 23:10:49', '2026-05-28 23:10:49'),
(238, 2, 'student', 'created', 'created student: Jeany', '[]', '2026-05-28 23:11:03', '2026-05-28 23:11:03'),
(239, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-28 23:11:03', '2026-05-28 23:11:03'),
(240, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-28 23:11:03', '2026-05-28 23:11:03'),
(241, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-28 23:11:03', '2026-05-28 23:11:03'),
(242, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-28 23:11:03', '2026-05-28 23:11:03'),
(243, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-28 23:11:03', '2026-05-28 23:11:03'),
(244, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-28 23:11:03', '2026-05-28 23:11:03'),
(245, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-28 23:11:03', '2026-05-28 23:11:03'),
(246, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-28 23:11:03', '2026-05-28 23:11:03'),
(247, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-28 23:11:03', '2026-05-28 23:11:03'),
(248, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-28 23:11:03', '2026-05-28 23:11:03'),
(249, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-28 23:11:03', '2026-05-28 23:11:03'),
(250, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-28 23:11:03', '2026-05-28 23:11:03'),
(251, 2, 'registration', 'updated', 'updated registration: Jeany', '{\"old\":{\"status\":\"pending\",\"updated_at\":\"2026-05-29T06:10:49.000000Z\"},\"new\":{\"status\":\"accepted\",\"updated_at\":\"2026-05-29 06:11:03\"}}', '2026-05-28 23:11:03', '2026-05-28 23:11:03'),
(252, 22, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-29 07:02:59', '2026-05-29 07:02:59'),
(253, 22, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-28T22:30:03.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-29 14:02:59\"}}', '2026-05-29 07:02:59', '2026-05-29 07:02:59'),
(254, 22, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-29 07:06:51', '2026-05-29 07:06:51'),
(255, 22, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-28T21:30:05.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-29 14:06:51\"}}', '2026-05-29 07:06:51', '2026-05-29 07:06:51'),
(256, 2, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-21T10:03:36.000000Z\"},\"new\":{\"status\":\"rescheduled\",\"updated_at\":\"2026-05-29 14:39:01\"}}', '2026-05-29 07:39:01', '2026-05-29 07:39:01'),
(257, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 07:39:01', '2026-05-29 07:39:01'),
(258, 2, 'registration', 'created', 'created registration: Adam', '[]', '2026-05-29 07:41:52', '2026-05-29 07:41:52'),
(259, 2, 'registration', 'created', 'created registration: Jelita', '[]', '2026-05-29 07:43:43', '2026-05-29 07:43:43'),
(260, 2, 'student', 'created', 'created student: Jelita', '[]', '2026-05-29 07:49:24', '2026-05-29 07:49:24'),
(261, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 07:49:24', '2026-05-29 07:49:24'),
(262, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 07:49:24', '2026-05-29 07:49:24'),
(263, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 07:49:24', '2026-05-29 07:49:24'),
(264, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 07:49:24', '2026-05-29 07:49:24'),
(265, 2, 'registration', 'updated', 'updated registration: Jelita', '{\"old\":{\"status\":\"pending\",\"updated_at\":\"2026-05-29T07:43:43.000000Z\"},\"new\":{\"status\":\"accepted\",\"updated_at\":\"2026-05-29 14:49:24\"}}', '2026-05-29 07:49:24', '2026-05-29 07:49:24'),
(266, 2, 'student', 'created', 'created student: Adam', '[]', '2026-05-29 07:49:28', '2026-05-29 07:49:28'),
(267, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 07:49:28', '2026-05-29 07:49:28'),
(268, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 07:49:28', '2026-05-29 07:49:28'),
(269, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 07:49:28', '2026-05-29 07:49:28'),
(270, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 07:49:28', '2026-05-29 07:49:28'),
(271, 2, 'registration', 'updated', 'updated registration: Adam', '{\"old\":{\"status\":\"pending\",\"updated_at\":\"2026-05-29T07:41:52.000000Z\"},\"new\":{\"status\":\"accepted\",\"updated_at\":\"2026-05-29 14:49:28\"}}', '2026-05-29 07:49:28', '2026-05-29 07:49:28'),
(272, 2, 'student', 'updated', 'updated student: Gladion Shawn Hutahaean', '{\"old\":{\"is_active\":true,\"updated_at\":\"2026-05-16T22:55:44.000000Z\"},\"new\":{\"is_active\":false,\"updated_at\":\"2026-05-29 14:49:51\"}}', '2026-05-29 07:49:51', '2026-05-29 07:49:51'),
(273, 2, 'student', 'updated', 'updated student: Gladion Shawn Hutahaean', '{\"old\":{\"schedule_id\":null},\"new\":{\"schedule_id\":653}}', '2026-05-29 07:49:51', '2026-05-29 07:49:51'),
(274, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 07:49:51', '2026-05-29 07:49:51'),
(275, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 07:49:51', '2026-05-29 07:49:51'),
(276, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 07:49:51', '2026-05-29 07:49:51'),
(277, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 07:49:51', '2026-05-29 07:49:51'),
(278, 2, 'student', 'updated', 'updated student: Adam', '{\"old\":{\"schedule_id\":null,\"updated_at\":\"2026-05-29T07:49:28.000000Z\"},\"new\":{\"schedule_id\":313,\"updated_at\":\"2026-05-29 15:26:27\"}}', '2026-05-29 08:26:27', '2026-05-29 08:26:27'),
(279, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 08:26:27', '2026-05-29 08:26:27'),
(280, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 08:26:27', '2026-05-29 08:26:27'),
(281, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 08:26:27', '2026-05-29 08:26:27'),
(282, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 08:26:27', '2026-05-29 08:26:27'),
(283, 2, 'student', 'updated', 'updated student: Aqila', '{\"old\":{\"schedule_id\":null,\"updated_at\":\"2026-05-21T10:03:36.000000Z\"},\"new\":{\"schedule_id\":311,\"updated_at\":\"2026-05-29 15:34:35\"}}', '2026-05-29 08:34:35', '2026-05-29 08:34:35'),
(284, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 08:34:35', '2026-05-29 08:34:35'),
(285, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 08:34:35', '2026-05-29 08:34:35'),
(286, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 08:34:35', '2026-05-29 08:34:35'),
(287, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 08:34:35', '2026-05-29 08:34:35'),
(288, 2, 'student', 'updated', 'updated student: Zahra', '{\"old\":{\"schedule_id\":null,\"updated_at\":\"2026-05-21T09:59:04.000000Z\"},\"new\":{\"schedule_id\":310,\"updated_at\":\"2026-05-29 15:35:09\"}}', '2026-05-29 08:35:09', '2026-05-29 08:35:09'),
(289, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 08:35:09', '2026-05-29 08:35:09'),
(290, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 08:35:09', '2026-05-29 08:35:09'),
(291, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 08:35:09', '2026-05-29 08:35:09'),
(292, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 08:35:09', '2026-05-29 08:35:09'),
(293, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 08:35:09', '2026-05-29 08:35:09'),
(294, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 08:35:09', '2026-05-29 08:35:09'),
(295, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 08:35:09', '2026-05-29 08:35:09'),
(296, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 08:35:09', '2026-05-29 08:35:09'),
(297, 2, 'student', 'updated', 'updated student: Jeany', '{\"old\":{\"schedule_id\":null,\"updated_at\":\"2026-05-28T23:11:03.000000Z\"},\"new\":{\"schedule_id\":284,\"updated_at\":\"2026-05-29 15:36:14\"}}', '2026-05-29 08:36:14', '2026-05-29 08:36:14'),
(298, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 08:36:14', '2026-05-29 08:36:14'),
(299, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 08:36:14', '2026-05-29 08:36:14'),
(300, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 08:36:14', '2026-05-29 08:36:14'),
(301, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 08:36:14', '2026-05-29 08:36:14'),
(302, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 08:36:14', '2026-05-29 08:36:14'),
(303, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 08:36:14', '2026-05-29 08:36:14'),
(304, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 08:36:14', '2026-05-29 08:36:14'),
(305, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 08:36:14', '2026-05-29 08:36:14'),
(306, 2, 'registration', 'created', 'created registration: Arkan', '[]', '2026-05-29 08:40:42', '2026-05-29 08:40:42'),
(307, 2, 'student', 'created', 'created student: Arkan', '[]', '2026-05-29 08:41:13', '2026-05-29 08:41:13'),
(308, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 08:41:13', '2026-05-29 08:41:13'),
(309, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 08:41:13', '2026-05-29 08:41:13'),
(310, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 08:41:13', '2026-05-29 08:41:13'),
(311, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 08:41:13', '2026-05-29 08:41:13'),
(312, 2, 'registration', 'updated', 'updated registration: Arkan', '{\"old\":{\"status\":\"pending\",\"updated_at\":\"2026-05-29T08:40:42.000000Z\"},\"new\":{\"status\":\"accepted\",\"updated_at\":\"2026-05-29 15:41:13\"}}', '2026-05-29 08:41:13', '2026-05-29 08:41:13'),
(313, 22, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-29 08:52:00', '2026-05-29 08:52:00'),
(314, 22, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-16T22:18:19.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-29 15:52:00\"}}', '2026-05-29 08:52:00', '2026-05-29 08:52:00'),
(315, 22, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-29 09:06:17', '2026-05-29 09:06:17'),
(316, 22, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-16T22:55:39.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-29 16:06:18\"}}', '2026-05-29 09:06:18', '2026-05-29 09:06:18'),
(317, 22, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-29 09:07:34', '2026-05-29 09:07:34');
INSERT INTO `activities` (`id`, `user_id`, `module`, `action`, `description`, `meta`, `created_at`, `updated_at`) VALUES
(318, 22, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-16T23:05:08.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-29 16:07:34\"}}', '2026-05-29 09:07:34', '2026-05-29 09:07:34'),
(319, 22, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-29 09:08:12', '2026-05-29 09:08:12'),
(320, 22, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-16T22:47:57.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-29 16:08:12\"}}', '2026-05-29 09:08:12', '2026-05-29 09:08:12'),
(321, 22, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-29 09:08:26', '2026-05-29 09:08:26'),
(322, 22, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-16T23:05:08.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-29 16:08:26\"}}', '2026-05-29 09:08:26', '2026-05-29 09:08:26'),
(323, 22, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-29 09:09:00', '2026-05-29 09:09:00'),
(324, 22, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-16T22:55:39.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-29 16:09:00\"}}', '2026-05-29 09:09:00', '2026-05-29 09:09:00'),
(325, 2, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-29T08:30:10.000000Z\"},\"new\":{\"status\":\"rescheduled\",\"updated_at\":\"2026-05-29 17:04:25\"}}', '2026-05-29 10:04:25', '2026-05-29 10:04:25'),
(326, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-29 10:04:25', '2026-05-29 10:04:25'),
(327, 20, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-29 10:08:49', '2026-05-29 10:08:49'),
(328, 20, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-29T08:36:14.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-29 17:08:49\"}}', '2026-05-29 10:08:49', '2026-05-29 10:08:49'),
(329, 21, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-30 05:44:05', '2026-05-30 05:44:05'),
(330, 21, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-30T05:01:03.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-30 12:44:05\"}}', '2026-05-30 05:44:05', '2026-05-30 05:44:05'),
(331, 20, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-30 06:44:34', '2026-05-30 06:44:34'),
(332, 20, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-30T01:30:06.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-30 13:44:34\"}}', '2026-05-30 06:44:34', '2026-05-30 06:44:34'),
(333, 20, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-30 06:44:55', '2026-05-30 06:44:55'),
(334, 20, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-30T04:30:05.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-30 13:44:55\"}}', '2026-05-30 06:44:55', '2026-05-30 06:44:55'),
(335, 20, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-30 06:45:36', '2026-05-30 06:45:36'),
(336, 20, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-30T05:30:05.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-30 13:45:36\"}}', '2026-05-30 06:45:36', '2026-05-30 06:45:36'),
(337, 22, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-30 06:56:26', '2026-05-30 06:56:26'),
(338, 22, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-30T06:00:05.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-30 13:56:26\"}}', '2026-05-30 06:56:26', '2026-05-30 06:56:26'),
(339, 20, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-30 08:08:12', '2026-05-30 08:08:12'),
(340, 20, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-30T06:30:04.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-30 15:08:12\"}}', '2026-05-30 08:08:12', '2026-05-30 08:08:12'),
(341, 20, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-30 08:09:01', '2026-05-30 08:09:01'),
(342, 20, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-30T07:30:05.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-30 15:09:01\"}}', '2026-05-30 08:09:01', '2026-05-30 08:09:01'),
(343, 19, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-30 08:30:48', '2026-05-30 08:30:48'),
(344, 19, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-30T07:30:05.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-30 15:30:48\"}}', '2026-05-30 08:30:48', '2026-05-30 08:30:48'),
(345, 20, 'attendance', 'created', 'created attendance: Record', '[]', '2026-05-30 09:58:57', '2026-05-30 09:58:57'),
(346, 20, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-30T08:30:03.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-05-30 16:58:57\"}}', '2026-05-30 09:58:57', '2026-05-30 09:58:57'),
(347, 2, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-31T07:30:04.000000Z\"},\"new\":{\"status\":\"rescheduled\",\"updated_at\":\"2026-05-31 15:24:33\"}}', '2026-05-31 08:24:33', '2026-05-31 08:24:33'),
(348, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-31 08:24:33', '2026-05-31 08:24:33'),
(349, 2, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-30T06:30:03.000000Z\"},\"new\":{\"status\":\"rescheduled\",\"updated_at\":\"2026-05-31 15:24:38\"}}', '2026-05-31 08:24:38', '2026-05-31 08:24:38'),
(350, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-31 08:24:38', '2026-05-31 08:24:38'),
(351, 2, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-25T04:49:34.000000Z\"},\"new\":{\"status\":\"rescheduled\",\"updated_at\":\"2026-05-31 15:24:48\"}}', '2026-05-31 08:24:48', '2026-05-31 08:24:48'),
(352, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-05-31 08:24:48', '2026-05-31 08:24:48'),
(353, 2, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-31T08:24:48.000000Z\"},\"new\":{\"status\":\"rescheduled\",\"updated_at\":\"2026-06-01 17:20:01\"}}', '2026-06-01 10:20:02', '2026-06-01 10:20:02'),
(354, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-06-01 10:20:02', '2026-06-01 10:20:02'),
(355, 49, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-01 10:22:00', '2026-06-01 10:22:00'),
(356, 49, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-06-01T10:20:02.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-01 17:22:00\"}}', '2026-06-01 10:22:00', '2026-06-01 10:22:00'),
(357, 2, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-29T11:30:05.000000Z\"},\"new\":{\"status\":\"rescheduled\",\"updated_at\":\"2026-06-01 17:24:46\"}}', '2026-06-01 10:24:46', '2026-06-01 10:24:46'),
(358, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-06-01 10:24:46', '2026-06-01 10:24:46'),
(359, 21, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-01 10:28:58', '2026-06-01 10:28:58'),
(360, 21, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-06-01T05:30:05.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-01 17:28:58\"}}', '2026-06-01 10:28:58', '2026-06-01 10:28:58'),
(361, 2, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-25T04:49:34.000000Z\"},\"new\":{\"status\":\"rescheduled\",\"updated_at\":\"2026-06-02 14:48:30\"}}', '2026-06-02 07:48:30', '2026-06-02 07:48:30'),
(362, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-06-02 07:48:30', '2026-06-02 07:48:30'),
(363, 2, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-06-02T07:48:30.000000Z\"},\"new\":{\"status\":\"rescheduled\",\"updated_at\":\"2026-06-02 18:24:45\"}}', '2026-06-02 11:24:45', '2026-06-02 11:24:45'),
(364, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-06-02 11:24:45', '2026-06-02 11:24:45'),
(365, 49, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-02 11:25:38', '2026-06-02 11:25:38'),
(366, 49, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-06-02T11:24:45.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-02 18:25:38\"}}', '2026-06-02 11:25:38', '2026-06-02 11:25:38'),
(367, 2, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-25T04:49:34.000000Z\"},\"new\":{\"status\":\"rescheduled\",\"updated_at\":\"2026-06-02 18:26:19\"}}', '2026-06-02 11:26:19', '2026-06-02 11:26:19'),
(368, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-06-02 11:26:19', '2026-06-02 11:26:19'),
(369, 49, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-02 11:26:44', '2026-06-02 11:26:44'),
(370, 49, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-06-02T11:26:19.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-02 18:26:44\"}}', '2026-06-02 11:26:44', '2026-06-02 11:26:44'),
(371, 19, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-02 13:11:11', '2026-06-02 13:11:11'),
(372, 19, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-06-02T00:30:04.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-02 20:11:11\"}}', '2026-06-02 13:11:11', '2026-06-02 13:11:11'),
(373, 2, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-27T10:59:50.000000Z\"},\"new\":{\"status\":\"rescheduled\",\"updated_at\":\"2026-06-03 16:35:47\"}}', '2026-06-03 09:35:47', '2026-06-03 09:35:47'),
(374, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-06-03 09:35:47', '2026-06-03 09:35:47'),
(375, 20, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-03 12:07:39', '2026-06-03 12:07:39'),
(376, 20, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-06-03T09:35:47.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-03 19:07:39\"}}', '2026-06-03 12:07:39', '2026-06-03 12:07:39'),
(377, 46, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-03 13:45:16', '2026-06-03 13:45:16'),
(378, 46, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-06-03T11:30:14.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-03 20:45:16\"}}', '2026-06-03 13:45:16', '2026-06-03 13:45:16'),
(379, 2, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-06-04T09:30:06.000000Z\"},\"new\":{\"status\":\"rescheduled\",\"updated_at\":\"2026-06-04 17:10:35\"}}', '2026-06-04 10:10:35', '2026-06-04 10:10:35'),
(380, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-06-04 10:10:35', '2026-06-04 10:10:35'),
(381, 20, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-04 11:11:51', '2026-06-04 11:11:51'),
(382, 20, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-06-04T06:30:03.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-04 18:11:51\"}}', '2026-06-04 11:11:51', '2026-06-04 11:11:51'),
(383, 20, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-04 11:12:08', '2026-06-04 11:12:08'),
(384, 20, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-06-04T10:10:35.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-04 18:12:08\"}}', '2026-06-04 11:12:08', '2026-06-04 11:12:08'),
(385, 2, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-21T06:21:02.000000Z\"},\"new\":{\"status\":\"rescheduled\",\"updated_at\":\"2026-06-05 15:14:20\"}}', '2026-06-05 08:14:20', '2026-06-05 08:14:20'),
(386, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-06-05 08:14:20', '2026-06-05 08:14:20'),
(387, 21, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-06 05:52:09', '2026-06-06 05:52:09'),
(388, 21, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-06-06T05:00:09.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-06 12:52:09\"}}', '2026-06-06 05:52:09', '2026-06-06 05:52:09'),
(389, 20, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-06 06:55:00', '2026-06-06 06:55:00'),
(390, 20, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-06-06T04:30:10.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-06 13:55:00\"}}', '2026-06-06 06:55:00', '2026-06-06 06:55:00'),
(391, 20, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-06 06:55:16', '2026-06-06 06:55:16'),
(392, 20, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-06-06T05:30:04.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-06 13:55:16\"}}', '2026-06-06 06:55:16', '2026-06-06 06:55:16'),
(393, 20, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-06 07:29:43', '2026-06-06 07:29:43'),
(394, 20, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-31T05:30:04.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-06 14:29:43\"}}', '2026-06-06 07:29:43', '2026-06-06 07:29:43'),
(395, 20, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-06 07:35:48', '2026-06-06 07:35:48'),
(396, 20, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-31T06:30:04.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-06 14:35:48\"}}', '2026-06-06 07:35:48', '2026-06-06 07:35:48'),
(397, 19, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-06 09:19:16', '2026-06-06 09:19:16'),
(398, 19, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-15T01:51:17.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-06 16:19:16\"}}', '2026-06-06 09:19:16', '2026-06-06 09:19:16'),
(399, 2, 'student', 'updated', 'updated student: Rayzent', '{\"old\":{\"start_date\":\"2026-05-25\",\"end_date\":\"2026-06-25\",\"updated_at\":\"2026-05-25T04:49:34.000000Z\"},\"new\":{\"start_date\":\"2026-05-26\",\"end_date\":\"2026-06-26\",\"updated_at\":\"2026-06-08 22:24:44\"}}', '2026-06-08 15:24:44', '2026-06-08 15:24:44'),
(400, 2, 'student', 'updated', 'updated student: Rayzent', '{\"old\":{\"schedule_id\":null},\"new\":{\"schedule_id\":780}}', '2026-06-08 15:24:44', '2026-06-08 15:24:44'),
(401, 2, 'student', 'updated', 'updated student: Rayzent', '{\"old\":{\"start_date\":\"2026-05-26\",\"end_date\":\"2026-06-26\",\"updated_at\":\"2026-06-08T15:24:44.000000Z\"},\"new\":{\"start_date\":\"2026-06-09\",\"end_date\":\"2026-07-09\",\"updated_at\":\"2026-06-09 12:47:28\"}}', '2026-06-09 05:47:28', '2026-06-09 05:47:28'),
(402, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-06-09 05:47:28', '2026-06-09 05:47:28'),
(403, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-06-09 05:47:28', '2026-06-09 05:47:28'),
(404, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-06-09 05:47:28', '2026-06-09 05:47:28'),
(405, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-06-09 05:47:28', '2026-06-09 05:47:28'),
(406, 2, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-06-09T06:30:03.000000Z\"},\"new\":{\"status\":\"rescheduled\",\"updated_at\":\"2026-06-09 17:38:41\"}}', '2026-06-09 10:38:41', '2026-06-09 10:38:41'),
(407, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-06-09 10:38:41', '2026-06-09 10:38:41'),
(408, 2, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-27T06:23:15.000000Z\"},\"new\":{\"status\":\"rescheduled\",\"updated_at\":\"2026-06-10 12:58:18\"}}', '2026-06-10 05:58:18', '2026-06-10 05:58:18'),
(409, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-06-10 05:58:18', '2026-06-10 05:58:18'),
(410, 2, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-06-09T05:47:28.000000Z\"},\"new\":{\"status\":\"rescheduled\",\"updated_at\":\"2026-06-10 15:05:27\"}}', '2026-06-10 08:05:27', '2026-06-10 08:05:27'),
(411, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-06-10 08:05:27', '2026-06-10 08:05:27'),
(412, 2, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-06-10T08:05:27.000000Z\"},\"new\":{\"status\":\"rescheduled\",\"updated_at\":\"2026-06-10 15:07:45\"}}', '2026-06-10 08:07:45', '2026-06-10 08:07:45'),
(413, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-06-10 08:07:45', '2026-06-10 08:07:45'),
(414, 46, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-10 13:00:00', '2026-06-10 13:00:00'),
(415, 46, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-06-10T11:30:06.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-10 20:00:00\"}}', '2026-06-10 13:00:00', '2026-06-10 13:00:00'),
(416, 46, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-10 14:03:37', '2026-06-10 14:03:37'),
(417, 46, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-06-10T12:30:05.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-10 21:03:37\"}}', '2026-06-10 14:03:37', '2026-06-10 14:03:37'),
(418, 20, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-11 09:06:38', '2026-06-11 09:06:38'),
(419, 20, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-06-11T06:30:05.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-11 16:06:38\"}}', '2026-06-11 09:06:38', '2026-06-11 09:06:38'),
(420, 2, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-06-09T05:47:28.000000Z\"},\"new\":{\"status\":\"rescheduled\",\"updated_at\":\"2026-06-11 16:38:27\"}}', '2026-06-11 09:38:27', '2026-06-11 09:38:27'),
(421, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-06-11 09:38:27', '2026-06-11 09:38:27'),
(422, 49, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-11 09:57:17', '2026-06-11 09:57:17'),
(423, 49, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-06-11T09:39:03.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-11 16:57:17\"}}', '2026-06-11 09:57:17', '2026-06-11 09:57:17'),
(424, 49, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-11 10:50:22', '2026-06-11 10:50:22'),
(425, 49, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-06-09T10:39:03.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-11 17:50:22\"}}', '2026-06-11 10:50:22', '2026-06-11 10:50:22'),
(426, 49, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-11 10:50:49', '2026-06-11 10:50:49'),
(427, 49, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-06-10T08:08:02.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-11 17:50:49\"}}', '2026-06-11 10:50:49', '2026-06-11 10:50:49'),
(428, 22, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-12 05:56:31', '2026-06-12 05:56:31'),
(429, 22, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-06-12T04:30:05.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-12 12:56:31\"}}', '2026-06-12 05:56:31', '2026-06-12 05:56:31'),
(430, 22, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-12 06:12:30', '2026-06-12 06:12:30'),
(431, 22, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-06-12T05:30:04.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-12 13:12:30\"}}', '2026-06-12 06:12:30', '2026-06-12 06:12:30'),
(432, 22, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-12 09:01:42', '2026-06-12 09:01:42'),
(433, 22, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-30T08:30:03.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-12 16:01:42\"}}', '2026-06-12 09:01:42', '2026-06-12 09:01:42'),
(434, 22, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-12 09:01:56', '2026-06-12 09:01:56'),
(435, 22, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-31T08:30:04.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-12 16:01:56\"}}', '2026-06-12 09:01:56', '2026-06-12 09:01:56'),
(436, 22, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-12 09:02:10', '2026-06-12 09:02:10'),
(437, 22, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-31T09:30:05.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-12 16:02:10\"}}', '2026-06-12 09:02:10', '2026-06-12 09:02:10'),
(438, 22, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-12 09:02:21', '2026-06-12 09:02:21'),
(439, 22, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-06-05T04:30:08.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-12 16:02:21\"}}', '2026-06-12 09:02:21', '2026-06-12 09:02:21'),
(440, 22, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-12 09:02:42', '2026-06-12 09:02:42'),
(441, 22, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-06-06T06:00:04.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-12 16:02:42\"}}', '2026-06-12 09:02:42', '2026-06-12 09:02:42'),
(442, 22, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-12 09:03:09', '2026-06-12 09:03:09'),
(443, 22, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-05-16T22:47:57.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-12 16:03:09\"}}', '2026-06-12 09:03:09', '2026-06-12 09:03:09'),
(444, 22, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-12 09:03:22', '2026-06-12 09:03:22'),
(445, 22, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-06-07T08:30:06.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-12 16:03:22\"}}', '2026-06-12 09:03:22', '2026-06-12 09:03:22'),
(446, 22, 'attendance', 'created', 'created attendance: Record', '[]', '2026-06-12 09:03:34', '2026-06-12 09:03:34'),
(447, 22, 'schedulesession', 'updated', 'updated schedulesession: Record', '{\"old\":{\"status\":\"booked\",\"updated_at\":\"2026-06-07T09:30:05.000000Z\"},\"new\":{\"status\":\"completed\",\"updated_at\":\"2026-06-12 16:03:34\"}}', '2026-06-12 09:03:34', '2026-06-12 09:03:34'),
(448, 2, 'student', 'updated', 'updated student: Winola', '{\"old\":{\"start_date\":\"2026-05-20\",\"end_date\":\"2026-06-20\",\"updated_at\":\"2026-05-19T07:18:59.000000Z\"},\"new\":{\"start_date\":\"2026-06-12\",\"end_date\":\"2026-07-12\",\"updated_at\":\"2026-06-12 16:23:22\"}}', '2026-06-12 09:23:22', '2026-06-12 09:23:22'),
(449, 2, 'student', 'updated', 'updated student: Winola', '{\"old\":{\"schedule_id\":null},\"new\":{\"schedule_id\":695}}', '2026-06-12 09:23:22', '2026-06-12 09:23:22'),
(450, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-06-12 09:23:22', '2026-06-12 09:23:22'),
(451, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-06-12 09:23:22', '2026-06-12 09:23:22'),
(452, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-06-12 09:23:22', '2026-06-12 09:23:22'),
(453, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-06-12 09:23:22', '2026-06-12 09:23:22'),
(454, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-06-12 09:23:22', '2026-06-12 09:23:22'),
(455, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-06-12 09:23:22', '2026-06-12 09:23:22'),
(456, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-06-12 09:23:22', '2026-06-12 09:23:22'),
(457, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-06-12 09:23:22', '2026-06-12 09:23:22'),
(458, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-06-12 09:23:22', '2026-06-12 09:23:22'),
(459, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-06-12 09:23:22', '2026-06-12 09:23:22'),
(460, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-06-12 09:23:22', '2026-06-12 09:23:22'),
(461, 2, 'schedulesession', 'created', 'created schedulesession: Record', '[]', '2026-06-12 09:23:22', '2026-06-12 09:23:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `attendance`
--

CREATE TABLE `attendance` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `attendance_date` date NOT NULL,
  `status` enum('present','absent','late') NOT NULL DEFAULT 'present',
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `schedule_id` bigint(20) UNSIGNED NOT NULL,
  `session_id` bigint(20) UNSIGNED DEFAULT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('present','absent','reschedule') NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `attendances`
--

INSERT INTO `attendances` (`id`, `schedule_id`, `session_id`, `teacher_id`, `student_id`, `status`, `latitude`, `longitude`, `note`, `image_path`, `created_at`, `updated_at`) VALUES
(4, 207, 35, 9, 16, 'present', 0.50371200, 101.45433150, 'Pada sesi kali ini, saddam belajar penghayatan, dinamika dan stage act dalam bernyanyi.', 'attendances/attendance_35_1778926687.jpeg', '2026-05-16 03:18:07', '2026-05-16 03:18:07'),
(5, 650, 51, 11, 19, 'present', 0.51956160, 101.41579140, 'Sir nanti video nya aku share lewat wa aja yah', 'attendances/attendance_51_1778928094.jpeg', '2026-05-16 03:41:35', '2026-05-16 03:41:35'),
(7, 695, 79, 15, 26, 'present', 0.50626802, 101.43470783, 'siswa latihan pitch matching dan song practice “cinta dan rahasia - glen fredly ft. yura yunita”.', 'attendances/attendance_79_1779283206.jpeg', '2026-05-20 06:20:07', '2026-05-20 06:20:07'),
(8, 196, 91, 9, 29, 'reschedule', 0.50373020, 101.45431990, 'Ganti ke hari jumat minggu depan', 'attendances/attendance_91_1779441966.jpeg', '2026-05-22 02:26:07', '2026-05-22 02:26:07'),
(9, 504, 59, 12, 21, 'present', 0.46846847, 101.44483233, NULL, 'attendances/attendance_59_1779443499.jpeg', '2026-05-22 02:51:39', '2026-05-22 02:51:39'),
(10, 505, 87, 12, 28, 'present', 0.46846847, 101.44483233, NULL, 'attendances/attendance_87_1779443607.jpeg', '2026-05-22 02:53:27', '2026-05-22 02:53:27'),
(11, 652, 56, 12, 20, 'present', 0.50450450, 101.42735537, NULL, 'attendances/attendance_56_1779510184.jpeg', '2026-05-22 21:23:04', '2026-05-22 21:23:04'),
(16, 769, 115, 16, 33, 'present', 0.47855010, 101.41574720, NULL, 'attendances/attendance_115_1779710260.jpeg', '2026-05-25 04:57:41', '2026-05-25 04:57:41'),
(17, 282, 124, 10, 35, 'present', 0.50631790, 101.43472370, NULL, 'attendances/attendance_124_1779957835.jpeg', '2026-05-28 01:43:55', '2026-05-28 01:43:55'),
(18, 281, 120, 10, 34, 'present', 0.53705250, 101.41521840, NULL, 'attendances/attendance_120_1779981750.jpeg', '2026-05-28 08:22:30', '2026-05-28 08:22:30'),
(19, 177, 128, 9, 16, 'present', 0.50373010, 101.45431190, 'Belajar teknik dinamika dan penerapannya pada lagu satu-satu', 'attendances/attendance_128_1779983996.jpeg', '2026-05-28 08:59:56', '2026-05-28 08:59:56'),
(23, 505, 88, 12, 28, 'present', 0.50625070, 101.43474150, NULL, 'attendances/attendance_88_1780038179.jpeg', '2026-05-29 07:02:59', '2026-05-29 07:02:59'),
(24, 504, 60, 12, 21, 'present', 0.50630450, 101.43477110, NULL, 'attendances/attendance_60_1780038411.jpeg', '2026-05-29 07:06:51', '2026-05-29 07:06:51'),
(25, 652, 55, 12, 20, 'present', 0.49066286, 101.44821150, 'ABSEN YANG TERLEWAT(OTOMATIS)', 'attendances/attendance_55_1780044720.jpeg', '2026-05-29 08:52:00', '2026-05-29 08:52:00'),
(26, 538, 67, 12, 23, 'present', 0.49063286, 101.44817650, 'ABSEN INI DI AMBIL OTOMATIS OLEH SISTEM', 'attendances/attendance_67_1780045577.jpeg', '2026-05-29 09:06:17', '2026-05-29 09:06:17'),
(27, 539, 75, 12, 25, 'present', 0.49066286, 101.44821150, 'ABSEN INI DI AMBIL OTOMATIS OLEH SISTEM', 'attendances/attendance_75_1780045654.jpeg', '2026-05-29 09:07:34', '2026-05-29 09:07:34'),
(28, 523, 63, 12, 22, 'present', 0.49070475, 101.44837283, 'ABSEN INI DI AMBIL OTOMATIS OLEH SISTEM', 'attendances/attendance_63_1780045692.jpeg', '2026-05-29 09:08:12', '2026-05-29 09:08:12'),
(29, 539, 76, 12, 25, 'present', 0.49060469, 101.44821669, 'ABSEN INI DI AMBIL OTOMATIS OLEH SISTEM', 'attendances/attendance_76_1780045706.jpeg', '2026-05-29 09:08:26', '2026-05-29 09:08:26'),
(30, 538, 68, 12, 23, 'present', 0.49060469, 101.44821669, 'ABSEN INI DI AMBIL OTOMATIS OLEH SISTEM', 'attendances/attendance_68_1780045739.jpeg', '2026-05-29 09:09:00', '2026-05-29 09:09:00'),
(31, 294, 170, 10, 36, 'present', 0.50648930, 101.43476760, NULL, 'attendances/attendance_170_1780049329.jpeg', '2026-05-29 10:08:49', '2026-05-29 10:08:49'),
(32, 650, 53, 11, 19, 'present', 0.50626330, 101.43473700, NULL, 'attendances/attendance_53_1780119845.jpeg', '2026-05-30 05:44:05', '2026-05-30 05:44:05'),
(33, 306, 142, 10, 37, 'present', 0.50626470, 101.43473400, NULL, 'attendances/attendance_142_1780123474.jpeg', '2026-05-30 06:44:34', '2026-05-30 06:44:34'),
(34, 309, 174, 10, 36, 'present', 0.50629620, 101.43472900, NULL, 'attendances/attendance_174_1780123495.jpeg', '2026-05-30 06:44:55', '2026-05-30 06:44:55'),
(35, 310, 163, 10, 31, 'present', 0.50626390, 101.43473740, NULL, 'attendances/attendance_163_1780123536.jpeg', '2026-05-30 06:45:36', '2026-05-30 06:45:36'),
(36, 652, 57, 12, 20, 'present', 0.53279380, 101.47153090, NULL, 'attendances/attendance_57_1780124186.jpeg', '2026-05-30 06:56:26', '2026-05-30 06:56:26'),
(37, 311, 141, 10, 32, 'present', 0.50630820, 101.43473750, NULL, 'attendances/attendance_141_1780128492.jpeg', '2026-05-30 08:08:12', '2026-05-30 08:08:12'),
(38, 312, 178, 10, 39, 'present', 0.50639110, 101.43468760, NULL, 'attendances/attendance_178_1780128541.jpeg', '2026-05-30 08:09:01', '2026-05-30 08:09:01'),
(39, 207, 37, 9, 16, 'present', 0.50628820, 101.43472330, 'Hari ini saddam belajar dengan materi lagu satu satu, prepare lomba', 'attendances/attendance_37_1780129848.jpeg', '2026-05-30 08:30:48', '2026-05-30 08:30:48'),
(40, 313, 154, 10, 38, 'present', 0.50627490, 101.43474570, NULL, 'attendances/attendance_154_1780135137.jpeg', '2026-05-30 09:58:57', '2026-05-30 09:58:57'),
(41, 768, 186, 16, 33, 'present', 0.49060510, 101.44823270, 'ABSEN YANG RESHCEDULE SISWA HARI JUMAT', 'attendances/attendance_186_1780309319.jpeg', '2026-06-01 10:22:00', '2026-06-01 10:22:00'),
(42, 340, 183, 11, 18, 'present', 0.49065683, 101.44824842, 'HADIR DI UNDUR JAM 3 SORE', 'attendances/attendance_183_1780309738.jpeg', '2026-06-01 10:28:58', '2026-06-01 10:28:58'),
(43, 844, 189, 16, 33, 'present', 0.49163167, 101.44797228, 'ABSEN YANG HARI JUMAT', 'attendances/attendance_189_1780399538.jpeg', '2026-06-02 11:25:38', '2026-06-02 11:25:38'),
(44, 784, 190, 16, 33, 'present', 0.49163167, 101.44797228, 'ABSEN SELASA', 'attendances/attendance_190_1780399604.jpeg', '2026-06-02 11:26:44', '2026-06-02 11:26:44'),
(45, 140, 187, 9, 29, 'present', 0.50625970, 101.43472580, 'Hari ini fai take video untuk seleksi fls3n menyanyi solo SD', 'attendances/attendance_187_1780405871.jpeg', '2026-06-02 13:11:11', '2026-06-02 13:11:11'),
(46, 864, 191, 10, 35, 'present', 0.53714030, 101.41530460, NULL, 'attendances/attendance_191_1780488459.jpeg', '2026-06-03 12:07:39', '2026-06-03 12:07:39'),
(47, 695, 81, 15, 26, 'present', 0.50638687, 101.43480334, 'berdasarkan pertemuan minggu lalu, perkembangan siswa hari ini yaitu sudah mulai peka terhadap nada dan siswa juga belajar ketepatan tempo.', 'attendances/attendance_81_1780494315.jpeg', '2026-06-03 13:45:16', '2026-06-03 13:45:16'),
(48, 281, 121, 10, 34, 'reschedule', 0.50628950, 101.43472550, 'Sakit', 'attendances/attendance_121_1780571511.jpeg', '2026-06-04 11:11:51', '2026-06-04 11:11:51'),
(49, 283, 192, 10, 36, 'present', 0.50629550, 101.43474690, NULL, 'attendances/attendance_192_1780571528.jpeg', '2026-06-04 11:12:08', '2026-06-04 11:12:08'),
(50, 650, 54, 11, 19, 'present', 0.50636580, 101.43470080, NULL, 'attendances/attendance_54_1780725129.jpeg', '2026-06-06 05:52:09', '2026-06-06 05:52:09'),
(51, 309, 175, 10, 36, 'present', 0.50630440, 101.43474780, NULL, 'attendances/attendance_175_1780728900.jpeg', '2026-06-06 06:55:00', '2026-06-06 06:55:00'),
(52, 310, 164, 10, 31, 'present', 0.50601490, 101.43482460, NULL, 'attendances/attendance_164_1780728916.jpeg', '2026-06-06 06:55:16', '2026-06-06 06:55:16'),
(53, 325, 167, 10, 31, 'present', 0.50626380, 101.43473900, NULL, 'attendances/attendance_167_1780730983.jpeg', '2026-06-06 07:29:43', '2026-06-06 07:29:43'),
(54, 326, 159, 10, 32, 'present', 0.50628980, 101.43472950, NULL, 'attendances/attendance_159_1780731348.jpeg', '2026-06-06 07:35:48', '2026-06-06 07:35:48'),
(55, 207, 38, 9, 16, 'present', 0.50630810, 101.43471050, 'Minggu pertama juni, saddam latihan dengan materi baru yaitu lagu risk it all dari bruno mars.', 'attendances/attendance_38_1780737556.jpeg', '2026-06-06 09:19:16', '2026-06-06 09:19:16'),
(56, 695, 82, 15, 26, 'present', 0.50628840, 101.43470479, 'Evaluasi pitch control dan tempo dengan praktek lagu Risk It All - Bruno Mara.', 'attendances/attendance_82_1781096400.jpeg', '2026-06-10 13:00:00', '2026-06-10 13:00:00'),
(57, 696, 199, 15, 26, 'present', 0.50628840, 101.43470479, 'Penerapan teknik vokal nasal (suara hidung) untuk memperindah resonansi suara, sekaligus pemantapan control pitch dan tempo.', 'attendances/attendance_199_1781100217.jpeg', '2026-06-10 14:03:37', '2026-06-10 14:03:37'),
(58, 281, 122, 10, 34, 'present', 0.50629080, 101.43468060, NULL, 'attendances/attendance_122_1781168798.jpeg', '2026-06-11 09:06:38', '2026-06-11 09:06:38'),
(59, 813, 202, 16, 33, 'present', 0.47848770, 101.41571330, NULL, 'attendances/attendance_202_1781171837.jpeg', '2026-06-11 09:57:17', '2026-06-11 09:57:17'),
(60, 784, 198, 16, 33, 'present', 0.47844910, 101.41569050, NULL, 'attendances/attendance_198_1781175021.jpeg', '2026-06-11 10:50:21', '2026-06-11 10:50:21'),
(61, 867, 201, 16, 33, 'present', 0.47846170, 101.41571310, NULL, 'attendances/attendance_201_1781175049.jpeg', '2026-06-11 10:50:49', '2026-06-11 10:50:49'),
(62, 504, 62, 12, 21, 'present', 0.50638880, 101.43457500, NULL, 'attendances/attendance_62_1781243791.jpeg', '2026-06-12 05:56:31', '2026-06-12 05:56:31'),
(63, 505, 90, 12, 28, 'present', 0.50624540, 101.43474580, NULL, 'attendances/attendance_90_1781244750.jpeg', '2026-06-12 06:12:30', '2026-06-12 06:12:30'),
(64, 523, 64, 12, 22, 'present', 0.49059059, 101.44818285, 'ABSEN SISTEM', 'attendances/attendance_64_1781254902.jpeg', '2026-06-12 09:01:42', '2026-06-12 09:01:42'),
(65, 538, 69, 12, 23, 'present', 0.49065277, 101.44816745, 'ABSEN SISTEM', 'attendances/attendance_69_1781254916.jpeg', '2026-06-12 09:01:56', '2026-06-12 09:01:56'),
(66, 539, 77, 12, 25, 'present', 0.49065277, 101.44816745, 'ABSEN SISTEM', 'attendances/attendance_77_1781254930.jpeg', '2026-06-12 09:02:10', '2026-06-12 09:02:10'),
(67, 504, 61, 12, 21, 'present', 0.49065277, 101.44816745, 'ABSEN SISTEM', 'attendances/attendance_61_1781254941.jpeg', '2026-06-12 09:02:21', '2026-06-12 09:02:21'),
(68, 505, 89, 12, 28, 'present', 0.49065822, 101.44815861, 'ABSEN SISTEM', 'attendances/attendance_89_1781254951.jpeg', '2026-06-12 09:02:31', '2026-06-12 09:02:31'),
(69, 652, 58, 12, 20, 'present', 0.49065822, 101.44815861, 'ABSEN SISTEM', 'attendances/attendance_58_1781254962.jpeg', '2026-06-12 09:02:42', '2026-06-12 09:02:42'),
(70, 523, 65, 12, 22, 'present', 0.49065822, 101.44815861, 'ABSEN SISTEM', 'attendances/attendance_65_1781254989.jpeg', '2026-06-12 09:03:09', '2026-06-12 09:03:09'),
(71, 538, 70, 12, 23, 'present', 0.49065822, 101.44815861, 'ABSEN SISTEM', 'attendances/attendance_70_1781255002.jpeg', '2026-06-12 09:03:22', '2026-06-12 09:03:22'),
(72, 539, 78, 12, 25, 'present', 0.49059596, 101.44815424, 'ABSEN SISTEM', 'attendances/attendance_78_1781255014.jpeg', '2026-06-12 09:03:34', '2026-06-12 09:03:34');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `classes`
--

CREATE TABLE `classes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `schedule` varchar(255) DEFAULT NULL,
  `teacher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assignment_status` enum('pending','accepted','rejected') NOT NULL DEFAULT 'pending',
  `assignment_note` text DEFAULT NULL,
  `assigned_at` timestamp NULL DEFAULT NULL,
  `responded_at` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `classes`
--

INSERT INTO `classes` (`id`, `name`, `description`, `price`, `schedule`, `teacher_id`, `assignment_status`, `assignment_note`, `assigned_at`, `responded_at`, `status`, `created_at`, `updated_at`) VALUES
(5, 'Violin', 'Class Violin (Sir. Azam)', 600000.00, NULL, 8, 'pending', NULL, NULL, NULL, 'active', '2026-05-05 05:53:30', '2026-05-05 07:35:34'),
(6, 'Vocal', 'Class Vocal', 600000.00, NULL, 9, 'pending', NULL, NULL, NULL, 'active', '2026-05-05 08:08:33', '2026-05-28 08:53:17'),
(7, 'Drum', 'Class DRUM(Sir Ahlan)', 600000.00, NULL, 10, 'pending', NULL, NULL, NULL, 'active', '2026-05-05 08:15:32', '2026-05-05 08:15:32'),
(8, 'Guitar', 'Class Guitar (Sir. Roby)', 600000.00, NULL, 16, 'pending', NULL, NULL, NULL, 'active', '2026-05-05 08:20:49', '2026-05-27 05:54:14'),
(9, 'Piano', 'Class Piano', 600000.00, NULL, 12, 'pending', NULL, NULL, NULL, 'active', '2026-05-05 08:35:15', '2026-05-19 09:05:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `class_students`
--

CREATE TABLE `class_students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `class_students`
--

INSERT INTO `class_students` (`id`, `class_id`, `student_id`, `created_at`, `updated_at`) VALUES
(8, 6, 16, '2026-05-15 02:01:58', '2026-05-15 02:01:58'),
(9, 8, 17, '2026-05-21 07:18:58', '2026-05-21 07:18:58'),
(10, 9, 24, '2026-05-29 07:49:51', '2026-05-29 07:49:51'),
(11, 7, 38, '2026-05-29 08:26:27', '2026-05-29 08:26:27'),
(12, 7, 32, '2026-05-29 08:34:35', '2026-05-29 08:34:35'),
(13, 7, 31, '2026-05-29 08:35:09', '2026-05-29 08:35:09'),
(14, 7, 36, '2026-05-29 08:36:14', '2026-05-29 08:36:14'),
(15, 8, 33, '2026-06-08 15:24:44', '2026-06-08 15:24:44'),
(16, 8, 26, '2026-06-12 09:23:22', '2026-06-12 09:23:22'),
(17, 6, 26, '2026-06-12 09:23:22', '2026-06-12 09:23:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `class_teacher`
--

CREATE TABLE `class_teacher` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `class_teacher`
--

INSERT INTO `class_teacher` (`id`, `class_id`, `teacher_id`, `created_at`, `updated_at`) VALUES
(2, 9, 14, '2026-05-13 13:11:47', '2026-05-13 13:11:47'),
(3, 9, 12, '2026-05-16 22:13:19', '2026-05-16 22:13:19'),
(4, 6, 15, '2026-05-19 06:57:18', '2026-05-19 06:57:18'),
(5, 5, 8, '2026-05-19 07:11:54', '2026-05-19 07:11:54'),
(6, 7, 10, '2026-05-19 07:11:54', '2026-05-19 07:11:54'),
(7, 8, 11, '2026-05-19 07:11:54', '2026-05-19 07:11:54'),
(8, 6, 9, '2026-05-19 07:12:36', '2026-05-19 07:12:36'),
(9, 8, 16, '2026-05-21 05:54:03', '2026-05-21 05:54:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `events`
--

CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `status` enum('draft','upcoming','completed','cancelled') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `expense_date` date DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
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
-- Struktur dari tabel `galleries`
--

CREATE TABLE `galleries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `type` enum('photo','video') NOT NULL DEFAULT 'photo',
  `file_path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `student_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `issued_at` date DEFAULT NULL,
  `due_at` date DEFAULT NULL,
  `status` enum('draft','issued','paid','overdue') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
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
-- Struktur dari tabel `job_batches`
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
-- Struktur dari tabel `materials`
--

CREATE TABLE `materials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED DEFAULT NULL,
  `teacher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_05_000003_create_roles_and_user_roles_tables', 1),
(5, '2026_04_05_000004_create_academic_management_tables', 1),
(6, '2026_04_05_000005_create_finance_cms_and_system_tables', 1),
(7, '2026_04_05_000006_create_teacher_attendances_table', 1),
(8, '2026_04_05_000007_add_location_fields_to_teacher_attendances_table', 1),
(9, '2026_04_09_000008_add_profile_fields_to_teachers_table', 2),
(10, '2026_04_09_000009_add_schedule_assignment_status_to_classes_table', 3),
(11, '2026_04_20_000010_add_class_id_to_payments_table', 4),
(12, '2026_04_20_000011_ensure_student_progress_table_structure', 4),
(13, '2026_04_20_000012_add_music_school_fields_to_registrations_table', 4),
(14, '2026_04_22_000013_create_schedules_table', 4),
(15, '2026_04_23_000014_add_schedule_booking_fields', 5),
(16, '2026_04_23_000015_enforce_schedule_booking_constraints', 5),
(17, '2026_05_01_080702_add_address_and_no_hp_to_students_table', 6),
(18, '2026_05_01_081841_create_attendances_table', 6),
(19, '2026_05_01_105037_add_student_id_to_schedules_table', 6),
(20, '2026_05_01_105046_create_registration_schedules_table', 6),
(21, '2026_05_01_131051_create_reschedule_requests_table', 6),
(22, '2026_05_01_144717_create_schedule_sessions_table', 6),
(23, '2026_05_01_144927_add_session_id_to_attendances_table', 6),
(24, '2026_05_01_145142_add_session_ids_to_reschedule_requests_table', 6),
(25, '2026_05_01_151919_add_duration_fields_to_registrations_and_students_tables', 6),
(26, '2026_05_01_152938_add_cascades_to_student_related_tables', 6),
(27, '2026_05_05_141913_add_ktp_path_to_teachers_table', 7),
(28, '2026_05_08_133953_add_image_path_to_attendances_table', 8),
(29, '2026_05_09_161230_add_detailed_fields_to_students_table', 8),
(30, '2026_05_13_193101_create_class_teacher_table', 9),
(31, '2026_05_13_195256_update_schedules_unique_index', 10),
(32, '2026_05_15_075303_add_favorite_song_to_registrations_table', 11),
(33, '2026_05_15_075832_add_favorite_song_to_students_table', 11),
(34, '2026_05_15_082806_add_instagram_fields_to_registrations_and_students_tables', 12),
(35, '2026_05_19_150000_migrate_classes_teacher_id_to_pivot_table', 13),
(36, '2026_05_19_152245_cleanup_inactive_students_schedules', 14),
(37, '2026_05_25_105920_add_is_reminder_sent_to_schedule_sessions_table', 15),
(38, '2026_05_29_063527_drop_schedule_id_unique_from_attendances_table', 16),
(39, '2026_06_02_163206_add_new_date_to_reschedule_requests_table', 17),
(40, '2026_06_12_141136_add_substitute_teacher_id_to_schedule_sessions_table', 18),
(41, '2026_06_12_233305_create_teacher_leaves_table', 19);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED DEFAULT NULL,
  `class_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `paid_at` date DEFAULT NULL,
  `method` varchar(255) DEFAULT NULL,
  `status` enum('pending','paid','failed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `author_id` bigint(20) UNSIGNED DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `registrations`
--

CREATE TABLE `registrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_lengkap` varchar(255) DEFAULT NULL,
  `nama_panggilan` varchar(255) DEFAULT NULL,
  `jenis_kelamin` enum('laki-laki','perempuan') DEFAULT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `kewarganegaraan` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp_siswa` varchar(30) DEFAULT NULL,
  `ig_siswa` varchar(100) DEFAULT NULL,
  `full_name` varchar(255) NOT NULL,
  `age` int(10) UNSIGNED DEFAULT NULL,
  `phone` varchar(30) NOT NULL,
  `email` varchar(255) NOT NULL,
  `start_date` date DEFAULT NULL,
  `duration_months` int(11) DEFAULT NULL,
  `nama_ortu` varchar(255) DEFAULT NULL,
  `pekerjaan_ortu` varchar(255) DEFAULT NULL,
  `no_hp_ortu` varchar(30) DEFAULT NULL,
  `ig_ortu` varchar(100) DEFAULT NULL,
  `email_ortu` varchar(255) DEFAULT NULL,
  `instrumen` varchar(255) DEFAULT NULL,
  `program_tambahan` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`program_tambahan`)),
  `favorite_song` varchar(255) DEFAULT NULL,
  `hari_pilihan` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`hari_pilihan`)),
  `pengalaman` tinyint(1) NOT NULL DEFAULT 0,
  `deskripsi_pengalaman` text DEFAULT NULL,
  `class_id` bigint(20) UNSIGNED DEFAULT NULL,
  `schedule_id` bigint(20) UNSIGNED DEFAULT NULL,
  `preferred_schedule` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','accepted','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `registrations`
--

INSERT INTO `registrations` (`id`, `nama_lengkap`, `nama_panggilan`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `kewarganegaraan`, `alamat`, `no_hp_siswa`, `ig_siswa`, `full_name`, `age`, `phone`, `email`, `start_date`, `duration_months`, `nama_ortu`, `pekerjaan_ortu`, `no_hp_ortu`, `ig_ortu`, `email_ortu`, `instrumen`, `program_tambahan`, `favorite_song`, `hari_pilihan`, `pengalaman`, `deskripsi_pengalaman`, `class_id`, `schedule_id`, `preferred_schedule`, `notes`, `status`, `created_at`, `updated_at`) VALUES
(13, 'Dion Avrel', 'Avr', 'laki-laki', 'pekanbaru', '2009-09-06', 'Indonesia', 'Jl. Pesantren Gg. Cingkawang', '085264900095', NULL, 'Dion Avrel', 16, '085264900095', 'kejuberlap@gmail.com', '2026-05-08', 2, 'Andi', 'swasta', '085264900095', NULL, 'sative@gmail.com', 'Drum', '[\"Teori Musik\"]', NULL, '[\"Senin\"]', 1, NULL, 7, 241, 'Senin - 19:00', NULL, 'accepted', '2026-05-08 03:01:56', '2026-05-08 03:02:27'),
(15, 'rioo', 'rioo', 'laki-laki', 'rioo', '2026-04-29', 'Indonesia', 'jln sukamaju', '082285014307', NULL, 'rioo', 0, '082285014307', 'rioo@gmail.com', '2026-05-08', 1, 'eeffd', 'dawdwa', '082285014307', NULL, 'rioo@gmail.com', 'Drum', '[\"Teori Musik\"]', NULL, '[\"Jumat\"]', 1, NULL, 7, 302, 'Jumat - 20:00', NULL, 'accepted', '2026-05-08 06:25:37', '2026-05-08 06:25:46'),
(16, 'Jokowi Dodo', 'Jokowi', 'laki-laki', 'Solo', '2026-02-05', 'Indonesia', 'jln sukamaju', '082285014307', NULL, 'Jokowi Dodo', 0, '082285014307', 'jokowidodo@gmail.com', '2026-05-10', 1, 'Sumartono', 'Presiden RI', '082285024307', NULL, 'jokowidodo@gmail.com', 'Drum', '[\"Teori Musik\"]', NULL, '[\"Senin\"]', 1, NULL, 7, 242, 'Senin - 20:00', NULL, 'accepted', '2026-05-10 10:40:16', '2026-05-10 10:45:28'),
(17, 'rofcstudent', 'rofc', 'laki-laki', 'pekanbaru', '2026-05-01', 'Indonesia', 'jln sukamaju', '082285014307', NULL, 'rofcstudent', 0, '082285014307', 'rofcstudent@gmail.com', '2026-05-13', 1, 'rofc', 'rofc', '082285014307', NULL, 'rofcstudent@gmail.com', 'Drum', '[\"Teori Musik\"]', NULL, '[\"Rabu\"]', 1, NULL, 7, 260, 'Rabu - 08:00', NULL, 'accepted', '2026-05-13 06:23:49', '2026-05-13 06:24:11'),
(18, 'andi', 'saputra', 'laki-laki', 'pekanbaru', '1992-08-24', 'Indonesia', 'Jl. Pesantren Gg. Cingkawang', '085264900095', NULL, 'andi', 33, '085264900095', 'kejubeelapis@gmail.com', '2026-05-14', 1, 'iswaldi', 'swasta', '085264900095', NULL, 'kejuberlaaapis@gmail.com', 'Drum', '[\"Teori Musik\"]', NULL, '[\"Jumat\"]', 0, NULL, 7, 303, 'Jumat - 21:00', NULL, 'accepted', '2026-05-13 06:50:03', '2026-05-13 06:51:39'),
(19, 'TESS', 'rofc', 'laki-laki', 'dawdawd', '1111-11-11', 'Indonesia', 'jln sukamaju', '082285014307', NULL, 'TESS', 914, '082285014307', 'dionavrel09@gmail.com', '2026-05-14', 1, 'dssda', 'rumahan', '082285014307', NULL, 'dionavrel09@gmail.com', 'Piano', '[]', NULL, '[\"Kamis\"]', 1, NULL, 9, 590, 'Kamis - 08:00', NULL, 'accepted', '2026-05-13 13:12:39', '2026-05-13 13:13:28'),
(21, 'saddam abrizam detik', 'saddam', 'laki-laki', 'pekanbaru', '2015-06-09', 'Indonesia', 'jln merpati putih no.19 pekanbaru', '0895331354553', '@saddam_detik', 'saddam abrizam detik', 10, '0895331354553', 'saddam@studentrofc.com', '2026-05-16', 1, 'Tika', '-', '08126820642', '@desva_tika', 'saddam@studentrofc.com', 'Vocal', '[\"Teori Musik\"]', 'selalu di nadi mu , jumbo', '[\"Sabtu\"]', 0, NULL, 6, 207, 'Sabtu - 15:00', NULL, 'accepted', '2026-05-15 01:51:00', '2026-05-15 02:06:26'),
(22, 'KENRICK ANANTA HO', 'KENRICK', 'laki-laki', 'PEKANBARU', '2017-05-17', 'Indonesia', 'JL KARYA BAKTI VILLA KARYA BAKTI HOUSING BLOK A8', '081372892359', NULL, 'KENRICK ANANTA HO', 8, '081372892359', 'kenrick@studentrofc.com', '2026-05-16', 1, 'kathy', '-', '081372892359', NULL, 'kenrick@studentrofc.com', 'Guitar', '[]', NULL, '[\"Sabtu\"]', 0, NULL, 8, 416, 'Sabtu - 14:00', NULL, 'accepted', '2026-05-15 09:56:59', '2026-05-21 07:18:57'),
(23, 'Alaric Ava Alteza', 'Alaric', 'laki-laki', 'Bandung', '2024-05-25', 'Indonesia', 'Hangtuah Home B-5 Jl Sialang Bungkuk', '085220500193', NULL, 'Alaric Ava Alteza', 1, '085220500193', 'alaric@studentrofc.com', '2026-05-16', 1, 'Teguh Abadi Putra', '-', '085220500193', NULL, 'alaric@studentrofc.com', 'Guitar', '[]', 'Rock, Poppunk, Pop-Alternatif', '[\"Sabtu\"]', 0, NULL, 8, 650, 'Sabtu - 12:30', NULL, 'accepted', '2026-05-15 10:26:29', '2026-05-16 03:30:17'),
(24, 'keysha khayira danis', 'key', 'perempuan', 'pekanbaru', '2011-11-10', 'Indonesia', 'jln bambu kuning, tenayan raya', '082268017460', '@evrtng.keyy', 'keysha khayira danis', 14, '082268017460', 'key@studentrofc.com', '2026-05-17', 1, 'nini susanty', '-', '08117531716', '@nini_susanty', 'key@studentrofc.com', 'Guitar', '[]', '-', '[\"Minggu\"]', 0, NULL, 8, 432, 'Minggu - 15:00, Minggu - 16:00', NULL, 'accepted', '2026-05-15 11:00:12', '2026-05-16 03:29:58'),
(25, 'Gilviani Aurelia Chow', 'Gilviani', 'perempuan', 'Pekanbaru', '2020-02-10', 'Indonesia', 'Jl Proyek Baru No 14N', '0811-6909-919', NULL, 'Gilviani Aurelia Chow', 6, '0811-6909-919', 'gilviani@studentrofc.com', '2026-05-16', 1, 'Asmadi', '-', '0813-7101-1969', NULL, 'gilviani@studentrofc.com', 'Piano', '[\"Teori Musik\"]', 'malu - malu', '[\"Sabtu\"]', 1, NULL, 9, 520, 'Sabtu - 13:00', NULL, 'accepted', '2026-05-16 03:55:03', '2026-05-16 22:18:19'),
(26, 'Hilya Az Zahra Medina', 'Hilya', 'perempuan', 'Pekanbaru', '2019-04-30', 'Indonesia', 'Perumahan villa putri duyung Blok M no 10', '0823-6875-7200', '@babyhilya_20', 'Hilya Az Zahra Medina', 7, '0823-6875-7200', 'hilya@studentrofc.com', '2026-05-22', 1, 'Sherli Novia', '-', '0823-6875-7200', '@sherli_novia._', 'hilya@studentrofc.com', 'Piano', '[]', NULL, '[\"Jumat\"]', 0, NULL, 9, 504, 'Jumat - 12:00', NULL, 'accepted', '2026-05-16 22:36:58', '2026-05-16 22:41:13'),
(27, 'inara ayudia rahman', 'inara', 'perempuan', 'Pekanbaru', '2018-04-09', 'Indonesia', 'jl tengku bey komp bumi sejahtera b1 no 14 air dingin pekanbaru', '081268566860', '@ryzamanda', 'inara ayudia rahman', 8, '081268566860', 'inara@studentrofc.com', '2026-05-23', 1, '-', '-', '081268566860', '@ryzamanda', 'inara @studentrofc.com', 'Piano', '[]', 'kami akan selalu di nadi mu (jumbo)', '[\"Sabtu\"]', 0, NULL, 9, 523, 'Sabtu - 16:00', NULL, 'accepted', '2026-05-16 22:47:01', '2026-05-16 22:47:57'),
(28, 'Gladion Shawn Hutahaean', 'Gladion', 'laki-laki', 'Pekanbaru', '2018-11-26', 'Indonesia', 'Jl. Melur no 71 Harjosari Sukajadi', '08117591974', '@hutahaean_elieser', 'Gladion Shawn Hutahaean', 7, '08117591974', 'gladion@studentrofc.com', '2026-05-23', 1, 'George Sebastian Hutahaean , Gregory Seemby Hutahaean', NULL, '08117531708', '@hutahaean_elieser', 'gladion@studentrofc.com', 'Piano', '[]', '-', '[\"Sabtu\"]', 0, NULL, 9, 653, 'Sabtu - 14:30', NULL, 'accepted', '2026-05-16 22:51:39', '2026-05-16 22:55:44'),
(29, 'Rinjani Adara Marzuki', 'Jane', 'perempuan', 'pekanbaru', '2016-04-04', 'Indonesia', 'jl hangtuah ujung perumahan bukit mutiara permai 3 blok c no 51', '081261060814', '@gadis bocah kecil', 'Rinjani Adara Marzuki', 10, '081261060814', 'jane@studentrofc.com', '2026-05-17', 1, 'Mellisa', '-', '081261060814', '@gadis bocah kecil', 'jane@studentrofc.com', 'Piano', '[]', '-', '[\"Minggu\"]', 0, NULL, 9, 538, 'Minggu - 16:00', NULL, 'accepted', '2026-05-16 22:55:03', '2026-05-16 22:55:39'),
(30, 'Senja Mandalawangi Marzuki', 'Senja', 'perempuan', 'pekanbaru', '2018-01-08', 'Indonesia', 'jl hangtuah ujung perumahan bukit mutiara permai 3 blok c no 51', '081261060814', '@gadis bocah kecil', 'Senja Mandalawangi Marzuki', 8, '081261060814', 'senja@studentrofc.com', '2026-05-17', 1, 'Mellisa', '-', '081261060814', '@gadis bocah kecil', 'senja@studentrofc.com', 'Piano', '[]', '-', '[\"Minggu\"]', 0, NULL, 9, 539, 'Minggu - 17:00', NULL, 'accepted', '2026-05-16 22:58:41', '2026-05-16 23:05:08'),
(31, 'Winola', 'Winola', 'perempuan', 'Pekanbaru', '2026-05-18', 'Indonesia', '-', '-', '-', 'Winola', 0, '-', 'winola@studentrofc.com', '2026-05-20', 1, '-', '-', '-', '-', 'winola@studentrofc.com', 'Vocal', '[]', '-', '[\"Rabu\"]', 0, NULL, 6, 695, 'Rabu - 19:00', NULL, 'accepted', '2026-05-19 07:18:44', '2026-05-19 07:18:59'),
(32, 'student tes', 'student', 'laki-laki', '-', '2026-05-19', 'Indonesia', '-', '-', '-', 'student tes', 0, '-', 'student@rofc.com', '2026-05-19', 1, '-', '-', '-', '-', 'student@rofc.com', 'Vocal', '[]', '-', '[\"Selasa\"]', 0, NULL, 6, 682, 'Selasa - 21:00', NULL, 'accepted', '2026-05-19 07:21:23', '2026-05-19 07:21:32'),
(33, 'Cesca', 'Cesca', 'perempuan', 'Pekanbaru', '2026-05-20', 'Indonesia', '-', '-', '-', 'Cesca', 0, '-', 'cesca@studentrofc.com', '2026-05-22', 1, '-', '-', '-', '-', 'cesca@studentrofc.com', 'Piano', '[]', '-', '[\"Jumat\"]', 0, NULL, 9, 505, 'Jumat - 13:00', NULL, 'accepted', '2026-05-21 06:08:55', '2026-05-21 06:12:13'),
(34, 'Fai', 'Fai', 'laki-laki', '-', '2026-05-21', 'Indonesia', '-', '-', '-', 'Fai', 0, '-', 'fai@studentrofc.com', '2026-05-22', 1, '-', '-', '-', '-', 'fai@studentrofc.com', 'Vocal', '[]', '-', '[\"Jumat\"]', 0, NULL, 6, 196, 'Jumat - 19:00', NULL, 'accepted', '2026-05-21 06:20:47', '2026-05-21 06:21:02'),
(35, 'Akil', 'Akil', 'laki-laki', 'Pekanbaru', '2026-05-21', 'Indonesia', '-', '-', '-', 'Akil', 0, '-', 'akil@studentrofc.com', '2026-05-22', 1, '-', '-', '-', '-', 'akil@studentrofc.com', 'Drum', '[]', '-', '[\"Jumat\"]', 0, NULL, 7, 298, 'Jumat - 16:00', NULL, 'accepted', '2026-05-21 09:19:59', '2026-05-21 09:21:10'),
(36, 'Zahra', 'Zahra', 'perempuan', 'Pekanbaru', '2026-05-21', 'Indonesia', '-', '-', '-', 'Zahra', 0, '-', 'zahra@studentrofc.com', '2026-05-23', 1, '-', '-', '-', '-', 'zahra@studentrofc.com', 'Drum', '[]', '-', '[\"Sabtu\",\"Minggu\"]', 0, NULL, 7, 309, 'Sabtu - 12:00, Minggu - 12:00', NULL, 'accepted', '2026-05-21 09:58:37', '2026-05-21 09:59:04'),
(37, 'Aqila', 'Aqila', 'perempuan', 'Pekanbaru', '2026-05-21', 'Indonesia', '-', '-', '-', 'Aqila', 0, '-', 'aqila@gmail.com', '2026-05-23', 1, '-', '-', '-', '-', 'aqila@gmail.com', 'Drum', '[]', '-', '[\"Sabtu\",\"Minggu\"]', 0, NULL, 7, 310, 'Sabtu - 13:00, Minggu - 13:00', NULL, 'accepted', '2026-05-21 10:03:15', '2026-05-21 10:03:36'),
(38, 'Rayzent', 'Rayzent', 'laki-laki', 'Pekanbaru', '2026-05-22', 'Indonesia', '-', '-', '-', 'Rayzent', 0, '-', 'rayzent@studentrofc.com', '2026-05-25', 1, '-', '-', '-', '-', 'rayzent@studentrofc.com', 'Guitar', '[]', '-', '[\"Senin\"]', 0, NULL, 8, 769, 'Senin - 18:00', NULL, 'accepted', '2026-05-25 04:49:25', '2026-05-25 04:49:34'),
(39, 'Gracio', 'Gracio', 'laki-laki', 'Pekanbaru', '2026-05-26', 'Indonesia', '-', '-', '-', 'Gracio', 0, '-', 'gracio@studentrofc.com', '2026-05-28', 1, '-', '-', '-', NULL, 'gracio@studentrofc.com', 'Drum', '[]', '-', '[\"Kamis\"]', 0, NULL, 7, 281, 'Kamis - 14:00', NULL, 'accepted', '2026-05-27 06:03:57', '2026-05-27 10:42:36'),
(40, 'Haeden', 'Haeden', 'laki-laki', 'Pekanbaru', '2026-05-27', 'Indonesia', '-', '-', '-', 'Haeden', 0, '-', 'haeden@studentrofc.com', '2026-05-28', 1, '-', '-', '-', '-', 'haeden@studentrofc.com', 'Drum', '[]', '-', '[\"Kamis\"]', 0, NULL, 7, 282, 'Kamis - 15:00', NULL, 'accepted', '2026-05-27 10:58:48', '2026-05-27 10:59:50'),
(41, 'Jeany', 'Jeany', 'perempuan', 'Pekanbaru', '2026-05-27', 'Indonesia', '-', '-', '-', 'Jeany', 0, '-', 'jeany@studentrofc.com', '2026-05-29', 1, '-', '-', '-', '-', 'jeany@studentrofc.com', 'Drum', '[]', '-', '[\"Kamis\",\"Jumat\",\"Sabtu\"]', 0, NULL, 7, 284, 'Kamis - 17:00, Jumat - 14:00, Sabtu - 11:00', NULL, 'accepted', '2026-05-28 23:10:49', '2026-05-28 23:11:03'),
(42, 'Adam', 'Adam', 'laki-laki', 'Pekanbaru', '2026-05-28', 'Indonesia', '-', '-', '-', 'Adam', 0, '-', 'adam@studentrofc.com', '2026-05-30', 1, '-', '-', '-', '-', 'adam@studentrofc.com', 'Drum', '[]', '-', '[\"Sabtu\"]', 0, NULL, 7, 311, 'Sabtu - 14:00', NULL, 'accepted', '2026-05-29 07:41:52', '2026-05-29 07:49:28'),
(43, 'Jelita', 'Jelita', 'perempuan', 'Pekanbaru', '2026-05-28', 'Indonesia', '-', '-', '-', 'Jelita', 0, '-', 'jelita@studentrofc.com', '2026-05-30', 1, '-', '-', '-', '-', 'jelita@studentrofc.com', 'Drum', '[]', '-', '[\"Sabtu\"]', 0, NULL, 7, 306, 'Sabtu - 09:00', NULL, 'accepted', '2026-05-29 07:43:43', '2026-05-29 07:49:24'),
(44, 'Arkan', 'Arkan', 'laki-laki', 'Pekanbaru', '2026-05-27', 'Indonesia', '-', '-', '-', 'Arkan', 0, '-', 'arkan@studentrofc.com', '2026-05-30', 1, '-', '-', '-', '-', 'arkan@studentrofc.com', 'Drum', '[]', '-', '[\"Sabtu\"]', 0, NULL, 7, 312, 'Sabtu - 15:00', NULL, 'accepted', '2026-05-29 08:40:42', '2026-05-29 08:41:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `registration_schedules`
--

CREATE TABLE `registration_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `registration_id` bigint(20) UNSIGNED NOT NULL,
  `schedule_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `registration_schedules`
--

INSERT INTO `registration_schedules` (`id`, `registration_id`, `schedule_id`, `created_at`, `updated_at`) VALUES
(3, 13, 241, NULL, NULL),
(5, 15, 302, NULL, NULL),
(6, 16, 242, NULL, NULL),
(7, 17, 260, NULL, NULL),
(8, 18, 303, NULL, NULL),
(9, 19, 590, NULL, NULL),
(10, 21, 207, NULL, NULL),
(11, 22, 416, NULL, NULL),
(12, 23, 650, NULL, NULL),
(13, 24, 432, NULL, NULL),
(14, 24, 433, NULL, NULL),
(16, 25, 652, NULL, NULL),
(17, 26, 504, NULL, NULL),
(18, 27, 523, NULL, NULL),
(19, 28, 653, NULL, NULL),
(20, 29, 538, NULL, NULL),
(21, 30, 539, NULL, NULL),
(22, 31, 695, NULL, NULL),
(23, 32, 682, NULL, NULL),
(24, 33, 505, NULL, NULL),
(25, 34, 196, NULL, NULL),
(26, 22, 399, NULL, NULL),
(27, 22, 400, NULL, NULL),
(28, 35, 298, NULL, NULL),
(29, 36, 309, NULL, NULL),
(30, 36, 324, NULL, NULL),
(31, 37, 310, NULL, NULL),
(32, 37, 325, NULL, NULL),
(33, 38, 769, NULL, NULL),
(34, 39, 281, NULL, NULL),
(35, 40, 282, NULL, NULL),
(36, 41, 284, NULL, NULL),
(37, 41, 296, NULL, NULL),
(38, 41, 308, NULL, NULL),
(39, 42, 311, NULL, NULL),
(40, 43, 306, NULL, NULL),
(41, 44, 312, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `reschedule_requests`
--

CREATE TABLE `reschedule_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `old_schedule_id` bigint(20) UNSIGNED NOT NULL,
  `old_session_id` bigint(20) UNSIGNED DEFAULT NULL,
  `new_schedule_id` bigint(20) UNSIGNED NOT NULL,
  `new_date` date DEFAULT NULL,
  `new_session_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `reschedule_requests`
--

INSERT INTO `reschedule_requests` (`id`, `student_id`, `old_schedule_id`, `old_session_id`, `new_schedule_id`, `new_date`, `new_session_id`, `reason`, `status`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(3, 26, 695, 80, 695, NULL, NULL, NULL, 'approved', 2, '2026-05-27 06:23:15', '2026-05-27 06:20:56', '2026-05-27 06:23:15'),
(4, 16, 207, 36, 177, NULL, NULL, NULL, 'approved', 2, '2026-05-28 08:56:40', '2026-05-28 08:56:17', '2026-05-28 08:56:40'),
(5, 32, 310, 108, 311, NULL, NULL, NULL, 'approved', 2, '2026-05-29 07:39:01', '2026-05-29 07:37:40', '2026-05-29 07:39:01'),
(6, 30, 298, 96, 298, NULL, NULL, NULL, 'approved', 2, '2026-05-29 10:04:25', '2026-05-29 10:03:43', '2026-05-29 10:04:25'),
(7, 33, 769, 116, 829, NULL, NULL, NULL, 'approved', 2, '2026-05-31 08:24:48', '2026-05-29 15:59:46', '2026-05-31 08:24:48'),
(8, 17, 416, 41, 430, NULL, NULL, NULL, 'approved', 2, '2026-05-31 08:24:38', '2026-05-30 05:46:48', '2026-05-31 08:24:38'),
(9, 18, 432, 45, 340, NULL, NULL, 'Roby jatuh sir', 'approved', 2, '2026-05-31 08:24:33', '2026-05-31 08:13:22', '2026-05-31 08:24:33'),
(10, 33, 829, 185, 768, NULL, NULL, NULL, 'approved', 2, '2026-06-01 10:20:02', '2026-06-01 10:09:37', '2026-06-01 10:20:02'),
(11, 29, 196, 92, 140, NULL, NULL, 'RESHCEDULE JAM 7:15', 'approved', 2, '2026-06-01 10:24:46', '2026-06-01 10:24:32', '2026-06-01 10:24:46'),
(12, 33, 769, 117, 780, NULL, NULL, NULL, 'approved', 2, '2026-06-02 07:48:30', '2026-06-02 07:48:06', '2026-06-02 07:48:30'),
(13, 33, 780, 188, 844, '2026-05-30', NULL, NULL, 'approved', 2, '2026-06-02 11:24:45', '2026-06-02 10:46:23', '2026-06-02 11:24:45'),
(14, 33, 769, 118, 784, '2026-06-02', NULL, NULL, 'approved', 2, '2026-06-02 11:26:19', '2026-06-02 11:26:06', '2026-06-02 11:26:19'),
(15, 35, 282, 125, 864, '2026-06-03', NULL, NULL, 'approved', 2, '2026-06-03 09:35:47', '2026-06-03 09:35:30', '2026-06-03 09:35:47'),
(16, 36, 284, 129, 283, '2026-06-04', NULL, NULL, 'approved', 2, '2026-06-04 10:10:35', '2026-06-04 10:04:27', '2026-06-04 10:10:35'),
(17, 29, 196, 93, 195, '2026-06-12', NULL, 'Keluar kota', 'approved', 2, '2026-06-05 08:14:20', '2026-06-05 05:54:22', '2026-06-05 08:14:20'),
(18, 37, 306, 143, 306, NULL, NULL, 'Lagi gabisa di rmhnya', 'approved', NULL, NULL, '2026-06-06 06:54:27', '2026-06-06 07:21:05'),
(19, 33, 780, 194, 784, '2026-06-09', NULL, NULL, 'approved', 2, '2026-06-09 10:38:41', '2026-06-09 06:26:24', '2026-06-09 10:38:41'),
(20, 26, 695, 119, 696, '2026-06-10', NULL, NULL, 'approved', 2, '2026-06-10 05:58:18', '2026-06-10 05:58:10', '2026-06-10 05:58:18'),
(21, 33, 780, 195, 866, '2026-06-10', NULL, NULL, 'approved', 2, '2026-06-10 08:05:27', '2026-06-10 08:04:13', '2026-06-10 08:05:27'),
(22, 33, 866, 200, 867, '2026-06-10', NULL, NULL, 'approved', 2, '2026-06-10 08:07:45', '2026-06-10 08:07:03', '2026-06-10 08:07:45'),
(23, 33, 780, 196, 813, '2026-06-11', NULL, NULL, 'approved', 2, '2026-06-11 09:38:27', '2026-06-11 09:38:17', '2026-06-11 09:38:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'super_admin', NULL, NULL, NULL),
(2, 'Admin', 'admin_slug', NULL, '2026-04-08 05:07:23', '2026-04-08 05:07:23'),
(3, 'Teacher', 'teacher', 'Portal pengajar dan akademik.', '2026-04-08 05:30:47', '2026-04-08 05:30:47'),
(4, 'Admin', 'admin', 'Operasional akademik dan konten website.', '2026-04-08 06:12:23', '2026-04-08 06:12:23'),
(5, 'Student', 'student', 'Portal siswa.', '2026-04-23 04:35:12', '2026-04-23 04:35:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `schedules`
--

CREATE TABLE `schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `day` varchar(20) NOT NULL,
  `time` time NOT NULL,
  `teacher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `student_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('available','booked') NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `schedules`
--

INSERT INTO `schedules` (`id`, `class_id`, `day`, `time`, `teacher_id`, `student_id`, `status`, `created_at`, `updated_at`) VALUES
(6, 5, 'Senin', '08:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(7, 5, 'Senin', '09:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(8, 5, 'Senin', '10:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(9, 5, 'Senin', '11:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(10, 5, 'Senin', '12:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(11, 5, 'Senin', '13:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(12, 5, 'Senin', '14:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(13, 5, 'Senin', '15:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(14, 5, 'Senin', '16:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(15, 5, 'Senin', '17:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(16, 5, 'Senin', '18:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(17, 5, 'Senin', '19:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(18, 5, 'Senin', '20:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(19, 5, 'Senin', '21:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(20, 5, 'Selasa', '08:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(21, 5, 'Selasa', '09:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(22, 5, 'Selasa', '10:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(23, 5, 'Selasa', '11:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(24, 5, 'Selasa', '12:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(25, 5, 'Selasa', '13:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(26, 5, 'Selasa', '14:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(27, 5, 'Selasa', '15:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(28, 5, 'Selasa', '16:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(29, 5, 'Selasa', '17:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(30, 5, 'Selasa', '18:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(31, 5, 'Selasa', '19:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(32, 5, 'Selasa', '20:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(33, 5, 'Selasa', '21:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(34, 5, 'Rabu', '08:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(35, 5, 'Rabu', '09:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(36, 5, 'Rabu', '10:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(37, 5, 'Rabu', '11:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(38, 5, 'Rabu', '12:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(39, 5, 'Rabu', '13:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(40, 5, 'Rabu', '14:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(41, 5, 'Rabu', '15:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(42, 5, 'Rabu', '16:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(43, 5, 'Rabu', '17:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(44, 5, 'Rabu', '18:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(45, 5, 'Rabu', '19:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(46, 5, 'Rabu', '20:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(47, 5, 'Rabu', '21:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(48, 5, 'Kamis', '08:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(49, 5, 'Kamis', '09:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(50, 5, 'Kamis', '10:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(51, 5, 'Kamis', '11:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(52, 5, 'Kamis', '12:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(53, 5, 'Kamis', '13:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(54, 5, 'Kamis', '14:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(55, 5, 'Kamis', '15:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(56, 5, 'Kamis', '16:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(57, 5, 'Kamis', '17:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(58, 5, 'Kamis', '18:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(59, 5, 'Kamis', '19:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(60, 5, 'Kamis', '20:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(61, 5, 'Kamis', '21:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(62, 5, 'Jumat', '08:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(63, 5, 'Jumat', '09:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(64, 5, 'Jumat', '10:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(65, 5, 'Jumat', '11:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(66, 5, 'Jumat', '12:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(67, 5, 'Jumat', '13:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(68, 5, 'Jumat', '14:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(69, 5, 'Jumat', '15:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(70, 5, 'Jumat', '16:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(71, 5, 'Jumat', '17:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(72, 5, 'Jumat', '18:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(73, 5, 'Jumat', '19:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(74, 5, 'Jumat', '20:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(75, 5, 'Jumat', '21:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(76, 5, 'Sabtu', '08:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(77, 5, 'Sabtu', '09:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(78, 5, 'Sabtu', '10:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(79, 5, 'Sabtu', '11:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(80, 5, 'Sabtu', '12:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(81, 5, 'Sabtu', '13:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(82, 5, 'Sabtu', '14:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(83, 5, 'Sabtu', '15:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(84, 5, 'Sabtu', '16:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(85, 5, 'Sabtu', '17:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(86, 5, 'Sabtu', '18:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(87, 5, 'Sabtu', '19:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(88, 5, 'Sabtu', '20:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(89, 5, 'Sabtu', '21:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(90, 5, 'Minggu', '08:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(91, 5, 'Minggu', '09:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(92, 5, 'Minggu', '10:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(93, 5, 'Minggu', '11:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(94, 5, 'Minggu', '12:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(95, 5, 'Minggu', '13:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(96, 5, 'Minggu', '14:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(97, 5, 'Minggu', '15:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(98, 5, 'Minggu', '16:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(99, 5, 'Minggu', '17:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(100, 5, 'Minggu', '18:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(101, 5, 'Minggu', '19:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(102, 5, 'Minggu', '20:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(103, 5, 'Minggu', '21:00:00', 8, NULL, 'available', '2026-05-05 06:15:57', '2026-05-05 06:15:57'),
(118, 5, 'Senin', '22:00:00', 8, NULL, 'available', '2026-05-05 06:41:06', '2026-05-05 06:41:06'),
(119, 5, 'Selasa', '22:00:00', 8, NULL, 'available', '2026-05-05 06:41:06', '2026-05-05 06:41:06'),
(120, 5, 'Rabu', '22:00:00', 8, NULL, 'available', '2026-05-05 06:41:06', '2026-05-05 06:41:06'),
(121, 5, 'Kamis', '22:00:00', 8, NULL, 'available', '2026-05-05 06:41:06', '2026-05-05 06:41:06'),
(122, 5, 'Jumat', '22:00:00', 8, NULL, 'available', '2026-05-05 06:41:06', '2026-05-05 06:41:06'),
(123, 5, 'Sabtu', '22:00:00', 8, NULL, 'available', '2026-05-05 06:41:06', '2026-05-05 06:41:06'),
(124, 5, 'Minggu', '22:00:00', 8, NULL, 'available', '2026-05-05 06:41:06', '2026-05-05 06:41:06'),
(125, 6, 'Senin', '08:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(126, 6, 'Senin', '09:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(127, 6, 'Senin', '10:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(128, 6, 'Senin', '11:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(129, 6, 'Senin', '12:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(130, 6, 'Senin', '13:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(131, 6, 'Senin', '14:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(132, 6, 'Senin', '15:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(133, 6, 'Senin', '16:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(134, 6, 'Senin', '17:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(135, 6, 'Senin', '18:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(136, 6, 'Senin', '19:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(137, 6, 'Senin', '20:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(138, 6, 'Senin', '21:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(139, 6, 'Senin', '22:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(140, 6, 'Selasa', '08:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(141, 6, 'Selasa', '09:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(142, 6, 'Selasa', '10:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(143, 6, 'Selasa', '11:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(144, 6, 'Selasa', '12:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(145, 6, 'Selasa', '13:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(146, 6, 'Selasa', '14:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(147, 6, 'Selasa', '15:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(148, 6, 'Selasa', '16:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(149, 6, 'Selasa', '17:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(150, 6, 'Selasa', '18:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(151, 6, 'Selasa', '19:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(152, 6, 'Selasa', '20:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(153, 6, 'Selasa', '21:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(154, 6, 'Selasa', '22:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(155, 6, 'Rabu', '08:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(156, 6, 'Rabu', '09:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(157, 6, 'Rabu', '10:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(158, 6, 'Rabu', '11:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(159, 6, 'Rabu', '12:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(160, 6, 'Rabu', '13:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(161, 6, 'Rabu', '14:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(162, 6, 'Rabu', '15:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(163, 6, 'Rabu', '16:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(164, 6, 'Rabu', '17:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(165, 6, 'Rabu', '18:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(166, 6, 'Rabu', '19:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(167, 6, 'Rabu', '20:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(168, 6, 'Rabu', '21:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(169, 6, 'Rabu', '22:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(170, 6, 'Kamis', '08:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(171, 6, 'Kamis', '09:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(172, 6, 'Kamis', '10:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(173, 6, 'Kamis', '11:00:00', 9, NULL, 'available', '2026-05-05 08:09:12', '2026-05-05 08:09:12'),
(174, 6, 'Kamis', '12:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(175, 6, 'Kamis', '13:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(176, 6, 'Kamis', '14:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(177, 6, 'Kamis', '15:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(178, 6, 'Kamis', '16:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(179, 6, 'Kamis', '17:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(180, 6, 'Kamis', '18:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(181, 6, 'Kamis', '19:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(182, 6, 'Kamis', '20:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(183, 6, 'Kamis', '21:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(184, 6, 'Kamis', '22:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(185, 6, 'Jumat', '08:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(186, 6, 'Jumat', '09:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(187, 6, 'Jumat', '10:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(188, 6, 'Jumat', '11:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(189, 6, 'Jumat', '12:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(190, 6, 'Jumat', '13:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(191, 6, 'Jumat', '14:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(192, 6, 'Jumat', '15:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(193, 6, 'Jumat', '16:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(194, 6, 'Jumat', '17:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(195, 6, 'Jumat', '18:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(196, 6, 'Jumat', '19:00:00', 9, 29, 'booked', '2026-05-05 08:09:13', '2026-05-21 06:21:02'),
(197, 6, 'Jumat', '20:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(198, 6, 'Jumat', '21:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(199, 6, 'Jumat', '22:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(200, 6, 'Sabtu', '08:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(201, 6, 'Sabtu', '09:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(202, 6, 'Sabtu', '10:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(203, 6, 'Sabtu', '11:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(204, 6, 'Sabtu', '12:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(205, 6, 'Sabtu', '13:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(206, 6, 'Sabtu', '14:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(207, 6, 'Sabtu', '15:00:00', 9, 16, 'booked', '2026-05-05 08:09:13', '2026-05-15 01:51:17'),
(208, 6, 'Sabtu', '16:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(209, 6, 'Sabtu', '17:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(210, 6, 'Sabtu', '18:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(211, 6, 'Sabtu', '19:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(212, 6, 'Sabtu', '20:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(213, 6, 'Sabtu', '21:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(214, 6, 'Sabtu', '22:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(215, 6, 'Minggu', '08:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(216, 6, 'Minggu', '09:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(217, 6, 'Minggu', '10:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(218, 6, 'Minggu', '11:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(219, 6, 'Minggu', '12:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(220, 6, 'Minggu', '13:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(221, 6, 'Minggu', '14:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(222, 6, 'Minggu', '15:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(223, 6, 'Minggu', '16:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(224, 6, 'Minggu', '17:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(225, 6, 'Minggu', '18:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(226, 6, 'Minggu', '19:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(227, 6, 'Minggu', '20:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(228, 6, 'Minggu', '21:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(229, 6, 'Minggu', '22:00:00', 9, NULL, 'available', '2026-05-05 08:09:13', '2026-05-05 08:09:13'),
(230, 7, 'Senin', '08:00:00', 10, NULL, 'available', '2026-05-05 08:15:53', '2026-05-05 08:15:53'),
(231, 7, 'Senin', '09:00:00', 10, NULL, 'available', '2026-05-05 08:15:53', '2026-05-05 08:15:53'),
(232, 7, 'Senin', '10:00:00', 10, NULL, 'available', '2026-05-05 08:15:53', '2026-05-05 08:15:53'),
(233, 7, 'Senin', '11:00:00', 10, NULL, 'available', '2026-05-05 08:15:53', '2026-05-05 08:15:53'),
(234, 7, 'Senin', '12:00:00', 10, NULL, 'available', '2026-05-05 08:15:53', '2026-05-05 08:15:53'),
(235, 7, 'Senin', '13:00:00', 10, NULL, 'available', '2026-05-05 08:15:53', '2026-05-05 08:15:53'),
(236, 7, 'Senin', '14:00:00', 10, NULL, 'available', '2026-05-05 08:15:53', '2026-05-05 08:15:53'),
(237, 7, 'Senin', '15:00:00', 10, NULL, 'available', '2026-05-05 08:15:53', '2026-05-05 08:15:53'),
(238, 7, 'Senin', '16:00:00', 10, NULL, 'available', '2026-05-05 08:15:53', '2026-05-05 08:15:53'),
(239, 7, 'Senin', '17:00:00', 10, NULL, 'available', '2026-05-05 08:15:53', '2026-05-05 08:15:53'),
(240, 7, 'Senin', '18:00:00', 10, NULL, 'available', '2026-05-05 08:15:53', '2026-05-05 08:15:53'),
(241, 7, 'Senin', '19:00:00', 10, NULL, 'booked', '2026-05-05 08:15:53', '2026-05-08 03:02:27'),
(242, 7, 'Senin', '20:00:00', 10, NULL, 'booked', '2026-05-05 08:15:53', '2026-05-10 10:45:28'),
(243, 7, 'Senin', '21:00:00', 10, NULL, 'available', '2026-05-05 08:15:53', '2026-05-05 08:15:53'),
(244, 7, 'Senin', '22:00:00', 10, NULL, 'available', '2026-05-05 08:15:53', '2026-05-05 08:15:53'),
(245, 7, 'Selasa', '08:00:00', 10, NULL, 'available', '2026-05-05 08:15:53', '2026-05-05 08:15:53'),
(246, 7, 'Selasa', '09:00:00', 10, NULL, 'available', '2026-05-05 08:15:53', '2026-05-05 08:15:53'),
(247, 7, 'Selasa', '10:00:00', 10, NULL, 'available', '2026-05-05 08:15:53', '2026-05-05 08:15:53'),
(248, 7, 'Selasa', '11:00:00', 10, NULL, 'available', '2026-05-05 08:15:53', '2026-05-05 08:15:53'),
(249, 7, 'Selasa', '12:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(250, 7, 'Selasa', '13:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(251, 7, 'Selasa', '14:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(252, 7, 'Selasa', '15:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(253, 7, 'Selasa', '16:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(254, 7, 'Selasa', '17:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(255, 7, 'Selasa', '18:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(256, 7, 'Selasa', '19:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(257, 7, 'Selasa', '20:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(258, 7, 'Selasa', '21:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(259, 7, 'Selasa', '22:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(260, 7, 'Rabu', '08:00:00', 10, NULL, 'booked', '2026-05-05 08:15:54', '2026-05-13 06:24:11'),
(261, 7, 'Rabu', '09:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(262, 7, 'Rabu', '10:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(263, 7, 'Rabu', '11:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(264, 7, 'Rabu', '12:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(265, 7, 'Rabu', '13:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(266, 7, 'Rabu', '14:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(267, 7, 'Rabu', '15:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(268, 7, 'Rabu', '16:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(269, 7, 'Rabu', '17:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(270, 7, 'Rabu', '18:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(271, 7, 'Rabu', '19:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(272, 7, 'Rabu', '20:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(273, 7, 'Rabu', '21:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(274, 7, 'Rabu', '22:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(275, 7, 'Kamis', '08:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(276, 7, 'Kamis', '09:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(277, 7, 'Kamis', '10:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(278, 7, 'Kamis', '11:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(279, 7, 'Kamis', '12:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(280, 7, 'Kamis', '13:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(281, 7, 'Kamis', '14:00:00', 10, 34, 'booked', '2026-05-05 08:15:54', '2026-05-27 10:42:36'),
(282, 7, 'Kamis', '15:00:00', 10, 35, 'booked', '2026-05-05 08:15:54', '2026-05-27 10:59:50'),
(283, 7, 'Kamis', '16:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(284, 7, 'Kamis', '17:00:00', 10, 36, 'booked', '2026-05-05 08:15:54', '2026-05-28 23:11:03'),
(285, 7, 'Kamis', '18:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(286, 7, 'Kamis', '19:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(287, 7, 'Kamis', '20:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(288, 7, 'Kamis', '21:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(289, 7, 'Kamis', '22:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(290, 7, 'Jumat', '08:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(291, 7, 'Jumat', '09:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(292, 7, 'Jumat', '10:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(293, 7, 'Jumat', '11:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(294, 7, 'Jumat', '12:00:00', 10, 36, 'booked', '2026-05-05 08:15:54', '2026-05-29 08:36:14'),
(295, 7, 'Jumat', '13:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(296, 7, 'Jumat', '14:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-29 08:36:14'),
(297, 7, 'Jumat', '15:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(298, 7, 'Jumat', '16:00:00', 10, 30, 'booked', '2026-05-05 08:15:54', '2026-05-21 09:21:10'),
(299, 7, 'Jumat', '17:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(300, 7, 'Jumat', '18:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(301, 7, 'Jumat', '19:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(302, 7, 'Jumat', '20:00:00', 10, NULL, 'booked', '2026-05-05 08:15:54', '2026-05-08 06:25:46'),
(303, 7, 'Jumat', '21:00:00', 10, NULL, 'booked', '2026-05-05 08:15:54', '2026-05-13 06:51:39'),
(304, 7, 'Jumat', '22:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(305, 7, 'Sabtu', '08:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(306, 7, 'Sabtu', '09:00:00', 10, 37, 'booked', '2026-05-05 08:15:54', '2026-05-29 07:49:24'),
(307, 7, 'Sabtu', '10:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(308, 7, 'Sabtu', '11:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-29 08:36:14'),
(309, 7, 'Sabtu', '12:00:00', 10, 36, 'booked', '2026-05-05 08:15:54', '2026-05-29 08:36:14'),
(310, 7, 'Sabtu', '13:00:00', 10, 31, 'booked', '2026-05-05 08:15:54', '2026-05-29 08:35:09'),
(311, 7, 'Sabtu', '14:00:00', 10, 32, 'booked', '2026-05-05 08:15:54', '2026-05-29 08:34:35'),
(312, 7, 'Sabtu', '15:00:00', 10, 39, 'booked', '2026-05-05 08:15:54', '2026-05-29 08:41:13'),
(313, 7, 'Sabtu', '16:00:00', 10, 38, 'booked', '2026-05-05 08:15:54', '2026-05-29 08:26:27'),
(314, 7, 'Sabtu', '17:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(315, 7, 'Sabtu', '18:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(316, 7, 'Sabtu', '19:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(317, 7, 'Sabtu', '20:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(318, 7, 'Sabtu', '21:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(319, 7, 'Sabtu', '22:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(320, 7, 'Minggu', '08:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(321, 7, 'Minggu', '09:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(322, 7, 'Minggu', '10:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(323, 7, 'Minggu', '11:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(324, 7, 'Minggu', '12:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-29 08:35:09'),
(325, 7, 'Minggu', '13:00:00', 10, 31, 'booked', '2026-05-05 08:15:54', '2026-05-29 08:35:09'),
(326, 7, 'Minggu', '14:00:00', 10, 32, 'booked', '2026-05-05 08:15:54', '2026-05-29 08:34:35'),
(327, 7, 'Minggu', '15:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(328, 7, 'Minggu', '16:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(329, 7, 'Minggu', '17:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(330, 7, 'Minggu', '18:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(331, 7, 'Minggu', '19:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(332, 7, 'Minggu', '20:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(333, 7, 'Minggu', '21:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(334, 7, 'Minggu', '22:00:00', 10, NULL, 'available', '2026-05-05 08:15:54', '2026-05-05 08:15:54'),
(335, 8, 'Senin', '08:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(336, 8, 'Senin', '09:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(337, 8, 'Senin', '10:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(338, 8, 'Senin', '11:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(339, 8, 'Senin', '12:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(340, 8, 'Senin', '13:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(341, 8, 'Senin', '14:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(342, 8, 'Senin', '15:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(343, 8, 'Senin', '16:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(344, 8, 'Senin', '17:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(345, 8, 'Senin', '18:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(346, 8, 'Senin', '19:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(347, 8, 'Senin', '20:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(348, 8, 'Senin', '21:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(349, 8, 'Senin', '22:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(350, 8, 'Selasa', '08:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(351, 8, 'Selasa', '09:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(352, 8, 'Selasa', '10:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(353, 8, 'Selasa', '11:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(354, 8, 'Selasa', '12:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(355, 8, 'Selasa', '13:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(356, 8, 'Selasa', '14:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(357, 8, 'Selasa', '15:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(358, 8, 'Selasa', '16:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(359, 8, 'Selasa', '17:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(360, 8, 'Selasa', '18:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(361, 8, 'Selasa', '19:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(362, 8, 'Selasa', '20:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(363, 8, 'Selasa', '21:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(364, 8, 'Selasa', '22:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(365, 8, 'Rabu', '08:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(366, 8, 'Rabu', '09:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(367, 8, 'Rabu', '10:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(368, 8, 'Rabu', '11:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(369, 8, 'Rabu', '12:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(370, 8, 'Rabu', '13:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(371, 8, 'Rabu', '14:00:00', 11, NULL, 'available', '2026-05-05 08:22:07', '2026-05-05 08:22:07'),
(372, 8, 'Rabu', '15:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(373, 8, 'Rabu', '16:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(374, 8, 'Rabu', '17:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(375, 8, 'Rabu', '18:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(376, 8, 'Rabu', '19:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(377, 8, 'Rabu', '20:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(378, 8, 'Rabu', '21:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(379, 8, 'Rabu', '22:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(380, 8, 'Kamis', '08:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(381, 8, 'Kamis', '09:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(382, 8, 'Kamis', '10:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(383, 8, 'Kamis', '11:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(384, 8, 'Kamis', '12:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(385, 8, 'Kamis', '13:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(386, 8, 'Kamis', '14:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(387, 8, 'Kamis', '15:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(388, 8, 'Kamis', '16:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(389, 8, 'Kamis', '17:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(390, 8, 'Kamis', '18:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(391, 8, 'Kamis', '19:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(392, 8, 'Kamis', '20:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(393, 8, 'Kamis', '21:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(394, 8, 'Kamis', '22:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(395, 8, 'Jumat', '08:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(396, 8, 'Jumat', '09:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(397, 8, 'Jumat', '10:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(398, 8, 'Jumat', '11:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(399, 8, 'Jumat', '12:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(400, 8, 'Jumat', '13:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(401, 8, 'Jumat', '14:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(402, 8, 'Jumat', '15:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(403, 8, 'Jumat', '16:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(404, 8, 'Jumat', '17:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(405, 8, 'Jumat', '18:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(406, 8, 'Jumat', '19:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(407, 8, 'Jumat', '20:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(408, 8, 'Jumat', '21:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(409, 8, 'Jumat', '22:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(410, 8, 'Sabtu', '08:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(411, 8, 'Sabtu', '09:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(412, 8, 'Sabtu', '10:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(413, 8, 'Sabtu', '11:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(414, 8, 'Sabtu', '12:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(415, 8, 'Sabtu', '13:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(416, 8, 'Sabtu', '14:00:00', 11, 17, 'booked', '2026-05-05 08:22:09', '2026-05-15 09:57:29'),
(417, 8, 'Sabtu', '15:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(418, 8, 'Sabtu', '16:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(419, 8, 'Sabtu', '17:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(420, 8, 'Sabtu', '18:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(421, 8, 'Sabtu', '19:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(422, 8, 'Sabtu', '20:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(423, 8, 'Sabtu', '21:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(424, 8, 'Sabtu', '22:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(425, 8, 'Minggu', '08:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(426, 8, 'Minggu', '09:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(427, 8, 'Minggu', '10:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(428, 8, 'Minggu', '11:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(429, 8, 'Minggu', '12:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(430, 8, 'Minggu', '13:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(431, 8, 'Minggu', '14:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(432, 8, 'Minggu', '15:00:00', 11, 18, 'booked', '2026-05-05 08:22:09', '2026-05-16 03:29:58'),
(433, 8, 'Minggu', '16:00:00', 11, 18, 'booked', '2026-05-05 08:22:09', '2026-05-16 03:29:58'),
(434, 8, 'Minggu', '17:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(435, 8, 'Minggu', '18:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(436, 8, 'Minggu', '19:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(437, 8, 'Minggu', '20:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(438, 8, 'Minggu', '21:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(439, 8, 'Minggu', '22:00:00', 11, NULL, 'available', '2026-05-05 08:22:09', '2026-05-05 08:22:09'),
(440, 9, 'Senin', '08:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(441, 9, 'Senin', '09:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(442, 9, 'Senin', '10:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(443, 9, 'Senin', '11:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(444, 9, 'Senin', '12:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(445, 9, 'Senin', '13:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(446, 9, 'Senin', '14:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(447, 9, 'Senin', '15:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(448, 9, 'Senin', '16:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(449, 9, 'Senin', '17:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(450, 9, 'Senin', '18:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(451, 9, 'Senin', '19:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(452, 9, 'Senin', '20:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(453, 9, 'Senin', '21:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(454, 9, 'Senin', '22:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(455, 9, 'Selasa', '08:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(456, 9, 'Selasa', '09:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(457, 9, 'Selasa', '10:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(458, 9, 'Selasa', '11:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(459, 9, 'Selasa', '12:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(460, 9, 'Selasa', '13:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(461, 9, 'Selasa', '14:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(462, 9, 'Selasa', '15:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(463, 9, 'Selasa', '16:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(464, 9, 'Selasa', '17:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(465, 9, 'Selasa', '18:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(466, 9, 'Selasa', '19:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(467, 9, 'Selasa', '20:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(468, 9, 'Selasa', '21:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(469, 9, 'Selasa', '22:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(470, 9, 'Rabu', '08:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(471, 9, 'Rabu', '09:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(472, 9, 'Rabu', '10:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(473, 9, 'Rabu', '11:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(474, 9, 'Rabu', '12:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(475, 9, 'Rabu', '13:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(476, 9, 'Rabu', '14:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(477, 9, 'Rabu', '15:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(478, 9, 'Rabu', '16:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(479, 9, 'Rabu', '17:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(480, 9, 'Rabu', '18:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(481, 9, 'Rabu', '19:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(482, 9, 'Rabu', '20:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(483, 9, 'Rabu', '21:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(484, 9, 'Rabu', '22:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(485, 9, 'Kamis', '08:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(486, 9, 'Kamis', '09:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(487, 9, 'Kamis', '10:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(488, 9, 'Kamis', '11:00:00', 12, NULL, 'booked', '2026-05-05 10:14:17', '2026-05-07 05:32:37'),
(489, 9, 'Kamis', '12:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(490, 9, 'Kamis', '13:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(491, 9, 'Kamis', '14:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(492, 9, 'Kamis', '15:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(493, 9, 'Kamis', '16:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(494, 9, 'Kamis', '17:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(495, 9, 'Kamis', '18:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(496, 9, 'Kamis', '19:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(497, 9, 'Kamis', '20:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(498, 9, 'Kamis', '21:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(499, 9, 'Kamis', '22:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(500, 9, 'Jumat', '08:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(501, 9, 'Jumat', '09:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(502, 9, 'Jumat', '10:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(503, 9, 'Jumat', '11:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(504, 9, 'Jumat', '12:00:00', 12, 21, 'booked', '2026-05-05 10:14:17', '2026-05-16 22:41:13'),
(505, 9, 'Jumat', '13:00:00', 12, 28, 'booked', '2026-05-05 10:14:17', '2026-05-21 06:12:13'),
(506, 9, 'Jumat', '14:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(507, 9, 'Jumat', '15:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(508, 9, 'Jumat', '16:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(509, 9, 'Jumat', '17:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(510, 9, 'Jumat', '18:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(511, 9, 'Jumat', '19:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(512, 9, 'Jumat', '20:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(513, 9, 'Jumat', '21:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(514, 9, 'Jumat', '22:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(515, 9, 'Sabtu', '08:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(516, 9, 'Sabtu', '09:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(517, 9, 'Sabtu', '10:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(518, 9, 'Sabtu', '11:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(519, 9, 'Sabtu', '12:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(520, 9, 'Sabtu', '13:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(521, 9, 'Sabtu', '14:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(522, 9, 'Sabtu', '15:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(523, 9, 'Sabtu', '16:00:00', 12, 22, 'booked', '2026-05-05 10:14:17', '2026-05-16 22:47:57'),
(524, 9, 'Sabtu', '17:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(525, 9, 'Sabtu', '18:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(526, 9, 'Sabtu', '19:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(527, 9, 'Sabtu', '20:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(528, 9, 'Sabtu', '21:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(529, 9, 'Sabtu', '22:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(530, 9, 'Minggu', '08:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(531, 9, 'Minggu', '09:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17');
INSERT INTO `schedules` (`id`, `class_id`, `day`, `time`, `teacher_id`, `student_id`, `status`, `created_at`, `updated_at`) VALUES
(532, 9, 'Minggu', '10:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(533, 9, 'Minggu', '11:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(534, 9, 'Minggu', '12:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(535, 9, 'Minggu', '13:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(536, 9, 'Minggu', '14:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(537, 9, 'Minggu', '15:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(538, 9, 'Minggu', '16:00:00', 12, 23, 'booked', '2026-05-05 10:14:17', '2026-05-16 22:55:39'),
(539, 9, 'Minggu', '17:00:00', 12, 25, 'booked', '2026-05-05 10:14:17', '2026-05-16 23:05:08'),
(540, 9, 'Minggu', '18:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(541, 9, 'Minggu', '19:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(542, 9, 'Minggu', '20:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(543, 9, 'Minggu', '21:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(544, 9, 'Minggu', '22:00:00', 12, NULL, 'available', '2026-05-05 10:14:17', '2026-05-05 10:14:17'),
(545, 9, 'Senin', '08:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(546, 9, 'Senin', '09:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(547, 9, 'Senin', '10:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(548, 9, 'Senin', '11:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(549, 9, 'Senin', '12:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(550, 9, 'Senin', '13:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(551, 9, 'Senin', '14:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(552, 9, 'Senin', '15:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(553, 9, 'Senin', '16:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(554, 9, 'Senin', '17:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(555, 9, 'Senin', '18:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(556, 9, 'Senin', '19:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(557, 9, 'Senin', '20:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(558, 9, 'Senin', '21:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(559, 9, 'Senin', '22:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(560, 9, 'Selasa', '08:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(561, 9, 'Selasa', '09:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(562, 9, 'Selasa', '10:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(563, 9, 'Selasa', '11:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(564, 9, 'Selasa', '12:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(565, 9, 'Selasa', '13:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(566, 9, 'Selasa', '14:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(567, 9, 'Selasa', '15:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(568, 9, 'Selasa', '16:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(569, 9, 'Selasa', '17:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(570, 9, 'Selasa', '18:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(571, 9, 'Selasa', '19:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(572, 9, 'Selasa', '20:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(573, 9, 'Selasa', '21:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(574, 9, 'Selasa', '22:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(575, 9, 'Rabu', '08:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(576, 9, 'Rabu', '09:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(577, 9, 'Rabu', '10:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(578, 9, 'Rabu', '11:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(579, 9, 'Rabu', '12:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(580, 9, 'Rabu', '13:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(581, 9, 'Rabu', '14:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(582, 9, 'Rabu', '15:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(583, 9, 'Rabu', '16:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(584, 9, 'Rabu', '17:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(585, 9, 'Rabu', '18:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(586, 9, 'Rabu', '19:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(587, 9, 'Rabu', '20:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(588, 9, 'Rabu', '21:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(589, 9, 'Rabu', '22:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(590, 9, 'Kamis', '08:00:00', 14, NULL, 'booked', '2026-05-13 13:09:28', '2026-05-13 13:13:28'),
(591, 9, 'Kamis', '09:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(592, 9, 'Kamis', '10:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(593, 9, 'Kamis', '11:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(594, 9, 'Kamis', '12:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(595, 9, 'Kamis', '13:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(596, 9, 'Kamis', '14:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(597, 9, 'Kamis', '15:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(598, 9, 'Kamis', '16:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(599, 9, 'Kamis', '17:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(600, 9, 'Kamis', '18:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(601, 9, 'Kamis', '19:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(602, 9, 'Kamis', '20:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(603, 9, 'Kamis', '21:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(604, 9, 'Kamis', '22:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(605, 9, 'Jumat', '08:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(606, 9, 'Jumat', '09:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(607, 9, 'Jumat', '10:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(608, 9, 'Jumat', '11:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(609, 9, 'Jumat', '12:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(610, 9, 'Jumat', '13:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(611, 9, 'Jumat', '14:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(612, 9, 'Jumat', '15:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(613, 9, 'Jumat', '16:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(614, 9, 'Jumat', '17:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(615, 9, 'Jumat', '18:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(616, 9, 'Jumat', '19:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(617, 9, 'Jumat', '20:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(618, 9, 'Jumat', '21:00:00', 14, NULL, 'available', '2026-05-13 13:09:28', '2026-05-13 13:11:47'),
(619, 9, 'Jumat', '22:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(620, 9, 'Sabtu', '08:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(621, 9, 'Sabtu', '09:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(622, 9, 'Sabtu', '10:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(623, 9, 'Sabtu', '11:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(624, 9, 'Sabtu', '12:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(625, 9, 'Sabtu', '13:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(626, 9, 'Sabtu', '14:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(627, 9, 'Sabtu', '15:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(628, 9, 'Sabtu', '16:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(629, 9, 'Sabtu', '17:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(630, 9, 'Sabtu', '18:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(631, 9, 'Sabtu', '19:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(632, 9, 'Sabtu', '20:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(633, 9, 'Sabtu', '21:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(634, 9, 'Sabtu', '22:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(635, 9, 'Minggu', '08:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(636, 9, 'Minggu', '09:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(637, 9, 'Minggu', '10:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(638, 9, 'Minggu', '11:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(639, 9, 'Minggu', '12:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(640, 9, 'Minggu', '13:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(641, 9, 'Minggu', '14:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(642, 9, 'Minggu', '15:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(643, 9, 'Minggu', '16:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(644, 9, 'Minggu', '17:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(645, 9, 'Minggu', '18:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(646, 9, 'Minggu', '19:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(647, 9, 'Minggu', '20:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(648, 9, 'Minggu', '21:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(649, 9, 'Minggu', '22:00:00', 14, NULL, 'available', '2026-05-13 13:09:29', '2026-05-13 13:11:47'),
(650, 8, 'Sabtu', '12:30:00', 11, 19, 'booked', '2026-05-15 10:19:43', '2026-05-16 03:30:17'),
(651, 8, 'Sabtu', '13:30:00', 11, NULL, 'available', '2026-05-15 10:19:44', '2026-05-15 10:19:44'),
(652, 9, 'Sabtu', '13:30:00', 12, 20, 'booked', '2026-05-16 03:55:45', '2026-05-16 22:18:19'),
(653, 9, 'Sabtu', '14:30:00', 12, 24, 'booked', '2026-05-16 03:55:45', '2026-05-29 07:49:51'),
(654, 6, 'Senin', '08:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(655, 6, 'Senin', '09:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(656, 6, 'Senin', '10:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(657, 6, 'Senin', '11:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(658, 6, 'Senin', '12:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(659, 6, 'Senin', '13:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(660, 6, 'Senin', '14:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(661, 6, 'Senin', '15:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(662, 6, 'Senin', '16:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(663, 6, 'Senin', '17:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(664, 6, 'Senin', '18:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(665, 6, 'Senin', '19:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(666, 6, 'Senin', '20:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(667, 6, 'Senin', '21:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(668, 6, 'Senin', '22:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(669, 6, 'Selasa', '08:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(670, 6, 'Selasa', '09:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(671, 6, 'Selasa', '10:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(672, 6, 'Selasa', '11:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(673, 6, 'Selasa', '12:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(674, 6, 'Selasa', '13:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(675, 6, 'Selasa', '14:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(676, 6, 'Selasa', '15:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(677, 6, 'Selasa', '16:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(678, 6, 'Selasa', '17:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(679, 6, 'Selasa', '18:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(680, 6, 'Selasa', '19:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(681, 6, 'Selasa', '20:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(682, 6, 'Selasa', '21:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 08:20:41'),
(683, 6, 'Selasa', '22:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(684, 6, 'Rabu', '08:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(685, 6, 'Rabu', '09:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(686, 6, 'Rabu', '10:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(687, 6, 'Rabu', '11:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(688, 6, 'Rabu', '12:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(689, 6, 'Rabu', '13:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(690, 6, 'Rabu', '14:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(691, 6, 'Rabu', '15:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(692, 6, 'Rabu', '16:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(693, 6, 'Rabu', '17:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(694, 6, 'Rabu', '18:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(695, 6, 'Rabu', '19:00:00', 15, 26, 'booked', '2026-05-19 07:16:20', '2026-05-19 07:18:59'),
(696, 6, 'Rabu', '20:00:00', 15, 26, 'booked', '2026-05-19 07:16:20', '2026-06-12 09:23:22'),
(697, 6, 'Rabu', '21:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(698, 6, 'Rabu', '22:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(699, 6, 'Kamis', '08:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(700, 6, 'Kamis', '09:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(701, 6, 'Kamis', '10:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(702, 6, 'Kamis', '11:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(703, 6, 'Kamis', '12:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(704, 6, 'Kamis', '13:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(705, 6, 'Kamis', '14:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(706, 6, 'Kamis', '15:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(707, 6, 'Kamis', '16:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(708, 6, 'Kamis', '17:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(709, 6, 'Kamis', '18:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(710, 6, 'Kamis', '19:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(711, 6, 'Kamis', '20:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(712, 6, 'Kamis', '21:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(713, 6, 'Kamis', '22:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(714, 6, 'Jumat', '08:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(715, 6, 'Jumat', '09:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(716, 6, 'Jumat', '10:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(717, 6, 'Jumat', '11:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(718, 6, 'Jumat', '12:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(719, 6, 'Jumat', '13:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(720, 6, 'Jumat', '14:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(721, 6, 'Jumat', '15:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(722, 6, 'Jumat', '16:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(723, 6, 'Jumat', '17:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(724, 6, 'Jumat', '18:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(725, 6, 'Jumat', '19:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(726, 6, 'Jumat', '20:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(727, 6, 'Jumat', '21:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(728, 6, 'Jumat', '22:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(729, 6, 'Sabtu', '08:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(730, 6, 'Sabtu', '09:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(731, 6, 'Sabtu', '10:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(732, 6, 'Sabtu', '11:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(733, 6, 'Sabtu', '12:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(734, 6, 'Sabtu', '13:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(735, 6, 'Sabtu', '14:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(736, 6, 'Sabtu', '15:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(737, 6, 'Sabtu', '16:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(738, 6, 'Sabtu', '17:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(739, 6, 'Sabtu', '18:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(740, 6, 'Sabtu', '19:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(741, 6, 'Sabtu', '20:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(742, 6, 'Sabtu', '21:00:00', 15, NULL, 'available', '2026-05-19 07:16:20', '2026-05-19 07:16:20'),
(743, 6, 'Sabtu', '22:00:00', 15, NULL, 'available', '2026-05-19 07:16:21', '2026-05-19 07:16:21'),
(744, 6, 'Minggu', '08:00:00', 15, NULL, 'available', '2026-05-19 07:16:21', '2026-05-19 07:16:21'),
(745, 6, 'Minggu', '09:00:00', 15, NULL, 'available', '2026-05-19 07:16:21', '2026-05-19 07:16:21'),
(746, 6, 'Minggu', '10:00:00', 15, NULL, 'available', '2026-05-19 07:16:21', '2026-05-19 07:16:21'),
(747, 6, 'Minggu', '11:00:00', 15, NULL, 'available', '2026-05-19 07:16:21', '2026-05-19 07:16:21'),
(748, 6, 'Minggu', '12:00:00', 15, NULL, 'available', '2026-05-19 07:16:21', '2026-05-19 07:16:21'),
(749, 6, 'Minggu', '13:00:00', 15, NULL, 'available', '2026-05-19 07:16:21', '2026-05-19 07:16:21'),
(750, 6, 'Minggu', '14:00:00', 15, NULL, 'available', '2026-05-19 07:16:21', '2026-05-19 07:16:21'),
(751, 6, 'Minggu', '15:00:00', 15, NULL, 'available', '2026-05-19 07:16:21', '2026-05-19 07:16:21'),
(752, 6, 'Minggu', '16:00:00', 15, NULL, 'available', '2026-05-19 07:16:21', '2026-05-19 07:16:21'),
(753, 6, 'Minggu', '17:00:00', 15, NULL, 'available', '2026-05-19 07:16:21', '2026-05-19 07:16:21'),
(754, 6, 'Minggu', '18:00:00', 15, NULL, 'available', '2026-05-19 07:16:21', '2026-05-19 07:16:21'),
(755, 6, 'Minggu', '19:00:00', 15, NULL, 'available', '2026-05-19 07:16:21', '2026-05-19 07:16:21'),
(756, 6, 'Minggu', '20:00:00', 15, NULL, 'available', '2026-05-19 07:16:21', '2026-05-19 07:16:21'),
(757, 6, 'Minggu', '21:00:00', 15, NULL, 'available', '2026-05-19 07:16:21', '2026-05-19 07:16:21'),
(758, 6, 'Minggu', '22:00:00', 15, NULL, 'available', '2026-05-19 07:16:21', '2026-05-19 07:16:21'),
(759, 8, 'Senin', '08:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(760, 8, 'Senin', '09:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(761, 8, 'Senin', '10:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(762, 8, 'Senin', '11:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(763, 8, 'Senin', '12:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(764, 8, 'Senin', '13:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(765, 8, 'Senin', '14:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(766, 8, 'Senin', '15:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(767, 8, 'Senin', '16:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(768, 8, 'Senin', '17:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(769, 8, 'Senin', '18:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-06-08 15:24:44'),
(770, 8, 'Senin', '19:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(771, 8, 'Senin', '20:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(772, 8, 'Senin', '21:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(773, 8, 'Senin', '22:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(774, 8, 'Selasa', '08:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(775, 8, 'Selasa', '09:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(776, 8, 'Selasa', '10:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(777, 8, 'Selasa', '11:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(778, 8, 'Selasa', '12:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(779, 8, 'Selasa', '13:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(780, 8, 'Selasa', '14:00:00', 16, 33, 'booked', '2026-05-21 05:55:18', '2026-06-08 15:24:44'),
(781, 8, 'Selasa', '15:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(782, 8, 'Selasa', '16:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(783, 8, 'Selasa', '17:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(784, 8, 'Selasa', '18:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(785, 8, 'Selasa', '19:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(786, 8, 'Selasa', '20:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(787, 8, 'Selasa', '21:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(788, 8, 'Selasa', '22:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(789, 8, 'Rabu', '08:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(790, 8, 'Rabu', '09:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(791, 8, 'Rabu', '10:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(792, 8, 'Rabu', '11:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(793, 8, 'Rabu', '12:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(794, 8, 'Rabu', '13:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(795, 8, 'Rabu', '14:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(796, 8, 'Rabu', '15:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(797, 8, 'Rabu', '16:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(798, 8, 'Rabu', '17:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(799, 8, 'Rabu', '18:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(800, 8, 'Rabu', '19:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(801, 8, 'Rabu', '20:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(802, 8, 'Rabu', '21:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(803, 8, 'Rabu', '22:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(804, 8, 'Kamis', '08:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(805, 8, 'Kamis', '09:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(806, 8, 'Kamis', '10:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(807, 8, 'Kamis', '11:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(808, 8, 'Kamis', '12:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(809, 8, 'Kamis', '13:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(810, 8, 'Kamis', '14:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(811, 8, 'Kamis', '15:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(812, 8, 'Kamis', '16:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(813, 8, 'Kamis', '17:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(814, 8, 'Kamis', '18:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(815, 8, 'Kamis', '19:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(816, 8, 'Kamis', '20:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(817, 8, 'Kamis', '21:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(818, 8, 'Kamis', '22:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(819, 8, 'Jumat', '08:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(820, 8, 'Jumat', '09:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(821, 8, 'Jumat', '10:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(822, 8, 'Jumat', '11:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(823, 8, 'Jumat', '12:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(824, 8, 'Jumat', '13:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(825, 8, 'Jumat', '14:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(826, 8, 'Jumat', '15:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(827, 8, 'Jumat', '16:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(828, 8, 'Jumat', '17:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(829, 8, 'Jumat', '18:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(830, 8, 'Jumat', '19:00:00', 16, 26, 'booked', '2026-05-21 05:55:18', '2026-06-12 09:23:22'),
(831, 8, 'Jumat', '20:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(832, 8, 'Jumat', '21:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(833, 8, 'Jumat', '22:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(834, 8, 'Sabtu', '08:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(835, 8, 'Sabtu', '09:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(836, 8, 'Sabtu', '10:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(837, 8, 'Sabtu', '11:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(838, 8, 'Sabtu', '12:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(839, 8, 'Sabtu', '13:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(840, 8, 'Sabtu', '14:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(841, 8, 'Sabtu', '15:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(842, 8, 'Sabtu', '16:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(843, 8, 'Sabtu', '17:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(844, 8, 'Sabtu', '18:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(845, 8, 'Sabtu', '19:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(846, 8, 'Sabtu', '20:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(847, 8, 'Sabtu', '21:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(848, 8, 'Sabtu', '22:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(849, 8, 'Minggu', '08:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(850, 8, 'Minggu', '09:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(851, 8, 'Minggu', '10:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(852, 8, 'Minggu', '11:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(853, 8, 'Minggu', '12:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(854, 8, 'Minggu', '13:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(855, 8, 'Minggu', '14:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(856, 8, 'Minggu', '15:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(857, 8, 'Minggu', '16:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(858, 8, 'Minggu', '17:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(859, 8, 'Minggu', '18:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(860, 8, 'Minggu', '19:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(861, 8, 'Minggu', '20:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(862, 8, 'Minggu', '21:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(863, 8, 'Minggu', '22:00:00', 16, NULL, 'available', '2026-05-21 05:55:18', '2026-05-21 05:55:18'),
(864, 7, 'Rabu', '16:30:00', 10, NULL, 'available', '2026-06-03 09:32:30', '2026-06-03 09:32:30'),
(865, 7, 'Rabu', '17:30:00', 10, NULL, 'available', '2026-06-03 09:32:30', '2026-06-03 09:32:30'),
(866, 8, 'Rabu', '14:30:00', 16, NULL, 'available', '2026-06-10 08:03:50', '2026-06-10 08:03:50'),
(867, 8, 'Rabu', '15:30:00', 16, NULL, 'available', '2026-06-10 08:03:50', '2026-06-10 08:03:50');

-- --------------------------------------------------------

--
-- Struktur dari tabel `schedule_sessions`
--

CREATE TABLE `schedule_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `schedule_id` bigint(20) UNSIGNED DEFAULT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `session_date` date NOT NULL,
  `time` time NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'booked',
  `is_reminder_sent` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `substitute_teacher_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `schedule_sessions`
--

INSERT INTO `schedule_sessions` (`id`, `schedule_id`, `student_id`, `teacher_id`, `class_id`, `session_date`, `time`, `status`, `is_reminder_sent`, `created_at`, `updated_at`, `substitute_teacher_id`) VALUES
(35, 207, 16, 9, 6, '2026-05-16', '15:00:00', 'completed', 0, '2026-05-15 01:51:17', '2026-05-16 03:18:07', NULL),
(36, 207, 16, 9, 6, '2026-05-23', '15:00:00', 'rescheduled', 0, '2026-05-15 01:51:17', '2026-05-28 08:56:40', NULL),
(37, 207, 16, 9, 6, '2026-05-30', '15:00:00', 'completed', 1, '2026-05-15 01:51:17', '2026-05-30 08:30:48', NULL),
(38, 207, 16, 9, 6, '2026-06-06', '15:00:00', 'completed', 0, '2026-05-15 01:51:17', '2026-06-06 09:19:16', NULL),
(39, 416, 17, 11, 8, '2026-05-16', '14:00:00', 'booked', 0, '2026-05-15 09:57:29', '2026-05-15 09:57:29', NULL),
(40, 416, 17, 11, 8, '2026-05-23', '14:00:00', 'booked', 0, '2026-05-15 09:57:29', '2026-05-15 09:57:29', NULL),
(41, 416, 17, 11, 8, '2026-05-30', '14:00:00', 'rescheduled', 1, '2026-05-15 09:57:29', '2026-05-31 08:24:38', NULL),
(42, 416, 17, 11, 8, '2026-06-06', '14:00:00', 'booked', 1, '2026-05-15 09:57:29', '2026-06-06 06:30:06', NULL),
(43, 432, 18, 11, 8, '2026-05-17', '15:00:00', 'booked', 0, '2026-05-16 03:29:58', '2026-05-16 03:29:58', NULL),
(44, 432, 18, 11, 8, '2026-05-24', '15:00:00', 'booked', 0, '2026-05-16 03:29:58', '2026-05-16 03:29:58', NULL),
(45, 432, 18, 11, 8, '2026-05-31', '15:00:00', 'rescheduled', 1, '2026-05-16 03:29:58', '2026-05-31 08:24:33', NULL),
(46, 432, 18, 11, 8, '2026-06-07', '15:00:00', 'booked', 1, '2026-05-16 03:29:58', '2026-06-07 07:30:09', NULL),
(47, 433, 18, 11, 8, '2026-05-17', '16:00:00', 'booked', 0, '2026-05-16 03:29:58', '2026-05-16 03:29:58', NULL),
(48, 433, 18, 11, 8, '2026-05-24', '16:00:00', 'booked', 0, '2026-05-16 03:29:58', '2026-05-16 03:29:58', NULL),
(49, 433, 18, 11, 8, '2026-05-31', '16:00:00', 'booked', 1, '2026-05-16 03:29:58', '2026-05-31 08:30:04', NULL),
(50, 433, 18, 11, 8, '2026-06-07', '16:00:00', 'booked', 1, '2026-05-16 03:29:58', '2026-06-07 08:30:06', NULL),
(51, 650, 19, 11, 8, '2026-05-16', '12:30:00', 'completed', 0, '2026-05-16 03:30:17', '2026-05-16 03:41:35', NULL),
(52, 650, 19, 11, 8, '2026-05-23', '12:30:00', 'booked', 0, '2026-05-16 03:30:17', '2026-05-16 03:30:17', NULL),
(53, 650, 19, 11, 8, '2026-05-30', '12:30:00', 'completed', 1, '2026-05-16 03:30:17', '2026-05-30 05:44:05', NULL),
(54, 650, 19, 11, 8, '2026-06-06', '12:30:00', 'completed', 1, '2026-05-16 03:30:17', '2026-06-06 05:52:09', NULL),
(55, 652, 20, 12, 9, '2026-05-16', '13:30:00', 'completed', 0, '2026-05-16 22:18:19', '2026-05-29 08:52:00', NULL),
(56, 652, 20, 12, 9, '2026-05-23', '13:30:00', 'completed', 0, '2026-05-16 22:18:19', '2026-05-22 21:23:04', NULL),
(57, 652, 20, 12, 9, '2026-05-30', '13:30:00', 'completed', 1, '2026-05-16 22:18:19', '2026-05-30 06:56:26', NULL),
(58, 652, 20, 12, 9, '2026-06-06', '13:30:00', 'completed', 1, '2026-05-16 22:18:19', '2026-06-12 09:02:42', NULL),
(59, 504, 21, 12, 9, '2026-05-22', '12:00:00', 'completed', 0, '2026-05-16 22:41:13', '2026-05-22 02:51:39', NULL),
(60, 504, 21, 12, 9, '2026-05-29', '12:00:00', 'completed', 1, '2026-05-16 22:41:13', '2026-05-29 07:06:51', NULL),
(61, 504, 21, 12, 9, '2026-06-05', '12:00:00', 'completed', 1, '2026-05-16 22:41:13', '2026-06-12 09:02:21', NULL),
(62, 504, 21, 12, 9, '2026-06-12', '12:00:00', 'completed', 1, '2026-05-16 22:41:13', '2026-06-12 05:56:31', NULL),
(63, 523, 22, 12, 9, '2026-05-23', '16:00:00', 'completed', 0, '2026-05-16 22:47:57', '2026-05-29 09:08:12', NULL),
(64, 523, 22, 12, 9, '2026-05-30', '16:00:00', 'completed', 1, '2026-05-16 22:47:57', '2026-06-12 09:01:42', NULL),
(65, 523, 22, 12, 9, '2026-06-06', '16:00:00', 'completed', 0, '2026-05-16 22:47:57', '2026-06-12 09:03:09', NULL),
(66, 523, 22, 12, 9, '2026-06-13', '16:00:00', 'booked', 0, '2026-05-16 22:47:57', '2026-05-16 22:47:57', NULL),
(67, 538, 23, 12, 9, '2026-05-17', '16:00:00', 'completed', 0, '2026-05-16 22:55:39', '2026-05-29 09:06:18', NULL),
(68, 538, 23, 12, 9, '2026-05-24', '16:00:00', 'completed', 0, '2026-05-16 22:55:39', '2026-05-29 09:09:00', NULL),
(69, 538, 23, 12, 9, '2026-05-31', '16:00:00', 'completed', 1, '2026-05-16 22:55:39', '2026-06-12 09:01:56', NULL),
(70, 538, 23, 12, 9, '2026-06-07', '16:00:00', 'completed', 1, '2026-05-16 22:55:39', '2026-06-12 09:03:22', NULL),
(71, 653, 24, 12, 9, '2026-05-23', '14:30:00', 'booked', 0, '2026-05-16 22:55:44', '2026-05-16 22:55:44', NULL),
(75, 539, 25, 12, 9, '2026-05-17', '17:00:00', 'completed', 0, '2026-05-16 23:05:08', '2026-05-29 09:07:34', NULL),
(76, 539, 25, 12, 9, '2026-05-24', '17:00:00', 'completed', 0, '2026-05-16 23:05:08', '2026-05-29 09:08:26', NULL),
(77, 539, 25, 12, 9, '2026-05-31', '17:00:00', 'completed', 1, '2026-05-16 23:05:08', '2026-06-12 09:02:10', NULL),
(78, 539, 25, 12, 9, '2026-06-07', '17:00:00', 'completed', 1, '2026-05-16 23:05:08', '2026-06-12 09:03:34', NULL),
(79, 695, 26, 15, 6, '2026-05-20', '19:00:00', 'completed', 0, '2026-05-19 07:18:59', '2026-05-20 06:20:07', NULL),
(80, 695, 26, 15, 6, '2026-05-27', '19:00:00', 'rescheduled', 1, '2026-05-19 07:18:59', '2026-05-27 06:23:15', NULL),
(81, 695, 26, 15, 6, '2026-06-03', '19:00:00', 'completed', 1, '2026-05-19 07:18:59', '2026-06-03 13:45:16', NULL),
(82, 695, 26, 15, 6, '2026-06-10', '19:00:00', 'completed', 1, '2026-05-19 07:18:59', '2026-06-10 13:00:00', NULL),
(87, 505, 28, 12, 9, '2026-05-22', '13:00:00', 'completed', 0, '2026-05-21 06:12:13', '2026-05-22 02:53:27', NULL),
(88, 505, 28, 12, 9, '2026-05-29', '13:00:00', 'completed', 1, '2026-05-21 06:12:13', '2026-05-29 07:02:59', NULL),
(89, 505, 28, 12, 9, '2026-06-05', '13:00:00', 'booked', 1, '2026-05-21 06:12:13', '2026-06-05 05:30:04', NULL),
(90, 505, 28, 12, 9, '2026-06-12', '13:00:00', 'completed', 1, '2026-05-21 06:12:13', '2026-06-12 06:12:30', NULL),
(91, 196, 29, 9, 6, '2026-05-22', '19:00:00', 'completed', 0, '2026-05-21 06:21:02', '2026-05-22 02:26:07', NULL),
(92, 196, 29, 9, 6, '2026-05-29', '19:00:00', 'rescheduled', 1, '2026-05-21 06:21:02', '2026-06-01 10:24:46', NULL),
(93, 196, 29, 9, 6, '2026-06-05', '19:00:00', 'rescheduled', 0, '2026-05-21 06:21:02', '2026-06-05 08:14:20', NULL),
(94, 196, 29, 9, 6, '2026-06-12', '19:00:00', 'booked', 1, '2026-05-21 06:21:02', '2026-06-12 11:30:04', NULL),
(95, 298, 30, 10, 7, '2026-05-22', '16:00:00', 'booked', 0, '2026-05-21 09:21:10', '2026-05-21 09:21:10', NULL),
(96, 298, 30, 10, 7, '2026-05-29', '16:00:00', 'rescheduled', 1, '2026-05-21 09:21:10', '2026-05-29 10:04:25', NULL),
(97, 298, 30, 10, 7, '2026-06-05', '16:00:00', 'booked', 1, '2026-05-21 09:21:10', '2026-06-05 08:30:04', NULL),
(98, 298, 30, 10, 7, '2026-06-12', '16:00:00', 'booked', 1, '2026-05-21 09:21:10', '2026-06-12 08:30:05', NULL),
(99, 309, 31, 10, 7, '2026-05-23', '12:00:00', 'booked', 0, '2026-05-21 09:59:04', '2026-05-21 09:59:04', NULL),
(103, 324, 31, 10, 7, '2026-05-24', '12:00:00', 'booked', 0, '2026-05-21 09:59:04', '2026-05-21 09:59:04', NULL),
(107, 310, 32, 10, 7, '2026-05-23', '13:00:00', 'booked', 0, '2026-05-21 10:03:36', '2026-05-21 10:03:36', NULL),
(108, 310, 32, 10, 7, '2026-05-30', '13:00:00', 'rescheduled', 0, '2026-05-21 10:03:36', '2026-05-29 07:39:01', NULL),
(111, 325, 32, 10, 7, '2026-05-24', '13:00:00', 'booked', 0, '2026-05-21 10:03:36', '2026-05-21 10:03:36', NULL),
(115, 769, 33, 16, 8, '2026-05-25', '18:00:00', 'completed', 1, '2026-05-25 04:49:34', '2026-05-25 05:03:57', NULL),
(116, 769, 33, 16, 8, '2026-06-01', '18:00:00', 'rescheduled', 0, '2026-05-25 04:49:34', '2026-05-31 08:24:48', NULL),
(117, 769, 33, 16, 8, '2026-06-08', '18:00:00', 'rescheduled', 0, '2026-05-25 04:49:34', '2026-06-02 07:48:30', NULL),
(118, 769, 33, 16, 8, '2026-06-15', '18:00:00', 'rescheduled', 0, '2026-05-25 04:49:34', '2026-06-02 11:26:19', NULL),
(119, 695, 26, 15, 6, '2026-06-17', '19:00:00', 'rescheduled', 0, '2026-05-27 06:23:15', '2026-06-10 05:58:18', NULL),
(120, 281, 34, 10, 7, '2026-05-28', '14:00:00', 'completed', 1, '2026-05-27 10:42:36', '2026-05-28 08:22:30', NULL),
(121, 281, 34, 10, 7, '2026-06-04', '14:00:00', 'completed', 1, '2026-05-27 10:42:36', '2026-06-04 11:11:51', NULL),
(122, 281, 34, 10, 7, '2026-06-11', '14:00:00', 'completed', 1, '2026-05-27 10:42:36', '2026-06-11 09:06:38', NULL),
(123, 281, 34, 10, 7, '2026-06-18', '14:00:00', 'booked', 0, '2026-05-27 10:42:36', '2026-05-27 10:42:36', NULL),
(124, 282, 35, 10, 7, '2026-05-28', '15:00:00', 'completed', 1, '2026-05-27 10:59:50', '2026-05-28 01:43:55', NULL),
(125, 282, 35, 10, 7, '2026-06-04', '15:00:00', 'rescheduled', 0, '2026-05-27 10:59:50', '2026-06-03 09:35:47', NULL),
(126, 282, 35, 10, 7, '2026-06-11', '15:00:00', 'booked', 1, '2026-05-27 10:59:50', '2026-06-11 07:30:04', NULL),
(127, 282, 35, 10, 7, '2026-06-18', '15:00:00', 'booked', 0, '2026-05-27 10:59:50', '2026-05-27 10:59:50', NULL),
(128, 177, 16, 9, 6, '2026-05-28', '15:00:00', 'completed', 0, '2026-05-28 08:56:40', '2026-05-28 08:59:56', NULL),
(129, 284, 36, 10, 7, '2026-06-04', '17:00:00', 'rescheduled', 1, '2026-05-28 23:11:03', '2026-06-04 10:10:35', NULL),
(130, 284, 36, 10, 7, '2026-06-11', '17:00:00', 'booked', 1, '2026-05-28 23:11:03', '2026-06-11 09:30:04', NULL),
(131, 284, 36, 10, 7, '2026-06-18', '17:00:00', 'booked', 0, '2026-05-28 23:11:03', '2026-05-28 23:11:03', NULL),
(132, 284, 36, 10, 7, '2026-06-25', '17:00:00', 'booked', 0, '2026-05-28 23:11:03', '2026-05-28 23:11:03', NULL),
(141, 311, 32, 10, 7, '2026-05-30', '14:00:00', 'completed', 1, '2026-05-29 07:39:01', '2026-05-30 08:08:12', NULL),
(142, 306, 37, 10, 7, '2026-05-30', '09:00:00', 'completed', 1, '2026-05-29 07:49:24', '2026-05-30 06:44:34', NULL),
(143, 306, 37, 10, 7, '2026-06-06', '09:00:00', 'booked', 1, '2026-05-29 07:49:24', '2026-06-06 01:30:06', NULL),
(144, 306, 37, 10, 7, '2026-06-13', '09:00:00', 'booked', 0, '2026-05-29 07:49:24', '2026-05-29 07:49:24', NULL),
(145, 306, 37, 10, 7, '2026-06-20', '09:00:00', 'booked', 0, '2026-05-29 07:49:24', '2026-05-29 07:49:24', NULL),
(150, 653, 24, 12, 9, '2026-05-23', '14:30:00', 'booked', 0, '2026-05-29 07:49:51', '2026-05-29 07:49:51', NULL),
(151, 653, 24, 12, 9, '2026-05-30', '14:30:00', 'booked', 1, '2026-05-29 07:49:51', '2026-05-30 07:00:04', NULL),
(152, 653, 24, 12, 9, '2026-06-06', '14:30:00', 'booked', 1, '2026-05-29 07:49:51', '2026-06-06 07:00:08', NULL),
(153, 653, 24, 12, 9, '2026-06-13', '14:30:00', 'booked', 0, '2026-05-29 07:49:51', '2026-05-29 07:49:51', NULL),
(154, 313, 38, 10, 7, '2026-05-30', '16:00:00', 'completed', 1, '2026-05-29 08:26:27', '2026-05-30 09:58:57', NULL),
(155, 313, 38, 10, 7, '2026-06-06', '16:00:00', 'booked', 0, '2026-05-29 08:26:27', '2026-05-29 08:26:27', NULL),
(156, 313, 38, 10, 7, '2026-06-13', '16:00:00', 'booked', 0, '2026-05-29 08:26:27', '2026-05-29 08:26:27', NULL),
(157, 313, 38, 10, 7, '2026-06-20', '16:00:00', 'booked', 0, '2026-05-29 08:26:27', '2026-05-29 08:26:27', NULL),
(158, 326, 32, 10, 7, '2026-05-24', '14:00:00', 'booked', 0, '2026-05-29 08:34:35', '2026-05-29 08:34:35', NULL),
(159, 326, 32, 10, 7, '2026-05-31', '14:00:00', 'completed', 1, '2026-05-29 08:34:35', '2026-06-06 07:35:48', NULL),
(160, 326, 32, 10, 7, '2026-06-07', '14:00:00', 'booked', 1, '2026-05-29 08:34:35', '2026-06-07 06:30:09', NULL),
(161, 326, 32, 10, 7, '2026-06-14', '14:00:00', 'booked', 0, '2026-05-29 08:34:35', '2026-05-29 08:34:35', NULL),
(162, 310, 31, 10, 7, '2026-05-23', '13:00:00', 'booked', 0, '2026-05-29 08:35:09', '2026-05-29 08:35:09', NULL),
(163, 310, 31, 10, 7, '2026-05-30', '13:00:00', 'completed', 1, '2026-05-29 08:35:09', '2026-05-30 06:45:36', NULL),
(164, 310, 31, 10, 7, '2026-06-06', '13:00:00', 'completed', 1, '2026-05-29 08:35:09', '2026-06-06 06:55:16', NULL),
(165, 310, 31, 10, 7, '2026-06-13', '13:00:00', 'booked', 0, '2026-05-29 08:35:09', '2026-05-29 08:35:09', NULL),
(166, 325, 31, 10, 7, '2026-05-24', '13:00:00', 'booked', 0, '2026-05-29 08:35:09', '2026-05-29 08:35:09', NULL),
(167, 325, 31, 10, 7, '2026-05-31', '13:00:00', 'completed', 1, '2026-05-29 08:35:09', '2026-06-06 07:29:43', NULL),
(168, 325, 31, 10, 7, '2026-06-07', '13:00:00', 'booked', 1, '2026-05-29 08:35:09', '2026-06-07 05:30:05', NULL),
(169, 325, 31, 10, 7, '2026-06-14', '13:00:00', 'booked', 0, '2026-05-29 08:35:09', '2026-05-29 08:35:09', NULL),
(170, 294, 36, 10, 7, '2026-05-29', '12:00:00', 'completed', 0, '2026-05-29 08:36:14', '2026-05-29 10:08:49', NULL),
(171, 294, 36, 10, 7, '2026-06-05', '12:00:00', 'booked', 1, '2026-05-29 08:36:14', '2026-06-05 04:30:09', NULL),
(172, 294, 36, 10, 7, '2026-06-12', '12:00:00', 'booked', 1, '2026-05-29 08:36:14', '2026-06-12 04:30:05', NULL),
(173, 294, 36, 10, 7, '2026-06-19', '12:00:00', 'booked', 0, '2026-05-29 08:36:14', '2026-05-29 08:36:14', NULL),
(174, 309, 36, 10, 7, '2026-05-30', '12:00:00', 'completed', 1, '2026-05-29 08:36:14', '2026-05-30 06:44:55', NULL),
(175, 309, 36, 10, 7, '2026-06-06', '12:00:00', 'completed', 1, '2026-05-29 08:36:14', '2026-06-06 06:55:00', NULL),
(176, 309, 36, 10, 7, '2026-06-13', '12:00:00', 'booked', 0, '2026-05-29 08:36:14', '2026-05-29 08:36:14', NULL),
(177, 309, 36, 10, 7, '2026-06-20', '12:00:00', 'booked', 0, '2026-05-29 08:36:14', '2026-05-29 08:36:14', NULL),
(178, 312, 39, 10, 7, '2026-05-30', '15:00:00', 'completed', 1, '2026-05-29 08:41:13', '2026-05-30 08:09:01', NULL),
(179, 312, 39, 10, 7, '2026-06-06', '15:00:00', 'booked', 0, '2026-05-29 08:41:13', '2026-05-29 08:41:13', NULL),
(180, 312, 39, 10, 7, '2026-06-13', '15:00:00', 'booked', 0, '2026-05-29 08:41:13', '2026-05-29 08:41:13', NULL),
(181, 312, 39, 10, 7, '2026-06-20', '15:00:00', 'booked', 0, '2026-05-29 08:41:13', '2026-05-29 08:41:13', NULL),
(182, 298, 30, 10, 7, '2026-06-19', '16:00:00', 'booked', 0, '2026-05-29 10:04:25', '2026-05-29 10:04:25', NULL),
(183, 340, 18, 11, 8, '2026-06-01', '13:00:00', 'completed', 1, '2026-05-31 08:24:33', '2026-06-01 10:28:58', NULL),
(184, 430, 17, 11, 8, '2026-05-31', '13:00:00', 'booked', 0, '2026-05-31 08:24:38', '2026-05-31 08:24:38', NULL),
(185, 829, 33, 16, 8, '2026-06-05', '18:00:00', 'rescheduled', 0, '2026-05-31 08:24:48', '2026-06-01 10:20:01', NULL),
(186, 768, 33, 16, 8, '2026-06-01', '17:00:00', 'completed', 0, '2026-06-01 10:20:02', '2026-06-01 10:22:00', NULL),
(187, 140, 29, 9, 6, '2026-06-02', '08:00:00', 'completed', 1, '2026-06-01 10:24:46', '2026-06-02 13:11:11', NULL),
(188, 780, 33, 16, 8, '2026-06-09', '14:00:00', 'rescheduled', 0, '2026-06-02 07:48:30', '2026-06-02 11:24:45', NULL),
(189, 844, 33, 16, 8, '2026-05-30', '18:00:00', 'completed', 0, '2026-06-02 11:24:45', '2026-06-02 11:25:38', NULL),
(190, 784, 33, 16, 8, '2026-06-02', '18:00:00', 'completed', 0, '2026-06-02 11:26:19', '2026-06-02 11:26:44', NULL),
(191, 864, 35, 10, 7, '2026-06-03', '16:30:00', 'completed', 0, '2026-06-03 09:35:47', '2026-06-03 12:07:39', NULL),
(192, 283, 36, 10, 7, '2026-06-04', '16:00:00', 'completed', 0, '2026-06-04 10:10:35', '2026-06-04 11:12:08', NULL),
(193, 195, 29, 9, 6, '2026-06-12', '18:00:00', 'booked', 1, '2026-06-05 08:14:20', '2026-06-12 10:30:05', NULL),
(194, 780, 33, 16, 8, '2026-06-09', '14:00:00', 'rescheduled', 1, '2026-06-09 05:47:28', '2026-06-09 10:38:41', NULL),
(195, 780, 33, 16, 8, '2026-06-16', '14:00:00', 'rescheduled', 0, '2026-06-09 05:47:28', '2026-06-10 08:05:27', NULL),
(196, 780, 33, 16, 8, '2026-06-23', '14:00:00', 'rescheduled', 0, '2026-06-09 05:47:28', '2026-06-11 09:38:27', NULL),
(197, 780, 33, 16, 8, '2026-06-30', '14:00:00', 'booked', 0, '2026-06-09 05:47:28', '2026-06-09 05:47:28', NULL),
(198, 784, 33, 16, 8, '2026-06-09', '18:00:00', 'completed', 1, '2026-06-09 10:38:41', '2026-06-11 10:50:22', NULL),
(199, 696, 26, 15, 6, '2026-06-10', '20:00:00', 'completed', 1, '2026-06-10 05:58:18', '2026-06-10 14:03:37', NULL),
(200, 866, 33, 16, 8, '2026-06-10', '14:30:00', 'rescheduled', 0, '2026-06-10 08:05:27', '2026-06-10 08:07:45', NULL),
(201, 867, 33, 16, 8, '2026-06-10', '15:30:00', 'completed', 1, '2026-06-10 08:07:45', '2026-06-11 10:50:49', NULL),
(202, 813, 33, 16, 8, '2026-06-11', '17:00:00', 'completed', 1, '2026-06-11 09:38:27', '2026-06-11 09:57:17', NULL),
(203, 695, 26, 15, 6, '2026-06-17', '19:00:00', 'booked', 0, '2026-06-12 09:23:22', '2026-06-12 09:23:22', NULL),
(204, 695, 26, 15, 6, '2026-06-24', '19:00:00', 'booked', 0, '2026-06-12 09:23:22', '2026-06-12 09:23:22', NULL),
(205, 695, 26, 15, 6, '2026-07-01', '19:00:00', 'booked', 0, '2026-06-12 09:23:22', '2026-06-12 09:23:22', NULL),
(206, 695, 26, 15, 6, '2026-07-08', '19:00:00', 'booked', 0, '2026-06-12 09:23:22', '2026-06-12 09:23:22', NULL),
(207, 696, 26, 15, 6, '2026-06-17', '20:00:00', 'booked', 0, '2026-06-12 09:23:22', '2026-06-12 09:23:22', NULL),
(208, 696, 26, 15, 6, '2026-06-24', '20:00:00', 'booked', 0, '2026-06-12 09:23:22', '2026-06-12 09:23:22', NULL),
(209, 696, 26, 15, 6, '2026-07-01', '20:00:00', 'booked', 0, '2026-06-12 09:23:22', '2026-06-12 09:23:22', NULL),
(210, 696, 26, 15, 6, '2026-07-08', '20:00:00', 'booked', 0, '2026-06-12 09:23:22', '2026-06-12 09:23:22', NULL),
(211, 830, 26, 16, 8, '2026-06-12', '19:00:00', 'booked', 1, '2026-06-12 09:23:22', '2026-06-12 11:30:04', NULL),
(212, 830, 26, 16, 8, '2026-06-19', '19:00:00', 'booked', 0, '2026-06-12 09:23:22', '2026-06-12 09:23:22', NULL),
(213, 830, 26, 16, 8, '2026-06-26', '19:00:00', 'booked', 0, '2026-06-12 09:23:22', '2026-06-12 09:23:22', NULL),
(214, 830, 26, 16, 8, '2026-07-03', '19:00:00', 'booked', 0, '2026-06-12 09:23:22', '2026-06-12 09:23:22', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
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
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('D7AfwLXOiKT62IK1UKsW7nrfjFaSPgogMfzHhZGc', NULL, '216.73.216.117', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; ClaudeBot/1.0; +claudebot@anthropic.com)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidVVoMEVPTVJrOWNKZVFwRHVEYzk1NEpCaDJxaFdlWEFUM0p3cnpXSiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHBzOi8vcm9mY211c2ljc2Nob29sLmNvbS9zaXRlbWFwLnhtbCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1781283461),
('P0cwVEtS7UnkDlhuW9pVO4Qo3z6wzuxNP4Z97tCj', 49, '203.83.39.27', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiTVIwSDFpVmJYenBsbzdINk1Qd1J4Y2p0VEcyYzlYTHdJdVZpUGpkSiI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDk7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDY6Imh0dHBzOi8vcm9mY211c2ljc2Nob29sLmNvbS9zZXNzaW9uLWtlZXAtYWxpdmUiO3M6NToicm91dGUiO3M6MTg6InNlc3Npb24ua2VlcC1hbGl2ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MTU6ImltcGVyc29uYXRvcl9pZCI7aToyO30=', 1781284345);

-- --------------------------------------------------------

--
-- Struktur dari tabel `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `nama_panggilan` varchar(80) DEFAULT NULL,
  `jenis_kelamin` varchar(20) DEFAULT NULL,
  `tempat_lahir` varchar(120) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `kewarganegaraan` varchar(120) DEFAULT NULL,
  `age` int(10) UNSIGNED DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `ig_siswa` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `schedule_id` bigint(20) UNSIGNED DEFAULT NULL,
  `address` text DEFAULT NULL,
  `nama_ortu` varchar(120) DEFAULT NULL,
  `pekerjaan_ortu` varchar(120) DEFAULT NULL,
  `no_hp_ortu` varchar(30) DEFAULT NULL,
  `ig_ortu` varchar(100) DEFAULT NULL,
  `email_ortu` varchar(120) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `duration_months` int(11) DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `no_hp` varchar(255) DEFAULT NULL,
  `class_id` bigint(20) UNSIGNED DEFAULT NULL,
  `program_tambahan` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`program_tambahan`)),
  `pengalaman` tinyint(1) NOT NULL DEFAULT 0,
  `deskripsi_pengalaman` text DEFAULT NULL,
  `favorite_song` varchar(120) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `students`
--

INSERT INTO `students` (`id`, `user_id`, `name`, `nama_panggilan`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `kewarganegaraan`, `age`, `phone`, `ig_siswa`, `email`, `schedule_id`, `address`, `nama_ortu`, `pekerjaan_ortu`, `no_hp_ortu`, `ig_ortu`, `email_ortu`, `start_date`, `duration_months`, `end_date`, `is_active`, `created_at`, `updated_at`, `no_hp`, `class_id`, `program_tambahan`, `pengalaman`, `deskripsi_pengalaman`, `favorite_song`) VALUES
(16, 36, 'saddam abrizam detik', 'saddam', 'laki-laki', 'pekanbaru', '2015-06-09', 'Indonesia', 10, '0895331354553', '@saddam_detik', 'saddam@studentrofc.com', NULL, 'jln merpati putih no.19 pekanbaru', 'Tika', '-', '08126820642', '@desva_tika', 'saddam@studentrofc.com', '2026-05-16', 1, '2026-06-16', 1, '2026-05-15 01:51:17', '2026-05-15 02:06:26', NULL, 6, '[\"Teori Musik\"]', 0, NULL, 'selalu di nadi mu , jumbo'),
(17, 37, 'KENRICK ANANTA HO', 'KENRICK', 'laki-laki', 'PEKANBARU', '2017-05-17', 'Indonesia', 8, '081372892359', NULL, 'kenrick@studentrofc.com', NULL, 'JL KARYA BAKTI VILLA KARYA BAKTI HOUSING BLOK A8', 'kathy', '-', '081372892359', NULL, 'kenrick@studentrofc.com', '2026-05-16', 1, '2026-06-16', 1, '2026-05-15 09:57:29', '2026-05-21 07:18:58', NULL, 8, '[]', 0, NULL, NULL),
(18, 38, 'keysha khayira danis', 'key', 'perempuan', 'pekanbaru', '2011-11-10', 'Indonesia', 14, '082268017460', '@evrtng.keyy', 'key@studentrofc.com', NULL, 'jln bambu kuning, tenayan raya', 'nini susanty', '-', '08117531716', '@nini_susanty', 'key@studentrofc.com', '2026-05-17', 1, '2026-06-17', 1, '2026-05-16 03:29:58', '2026-05-16 03:29:58', NULL, 8, '[]', 0, NULL, '-'),
(19, 39, 'Alaric Ava Alteza', 'Alaric', 'laki-laki', 'Bandung', '2024-05-25', 'Indonesia', 1, '085220500193', NULL, 'alaric@studentrofc.com', NULL, 'Hangtuah Home B-5 Jl Sialang Bungkuk', 'Teguh Abadi Putra', '-', '085220500193', NULL, 'alaric@studentrofc.com', '2026-05-16', 1, '2026-06-16', 1, '2026-05-16 03:30:17', '2026-05-16 03:30:17', NULL, 8, '[]', 0, NULL, 'Rock, Poppunk, Pop-Alternatif'),
(20, 40, 'Gilviani Aurelia Chow', 'Gilviani', 'perempuan', 'Pekanbaru', '2020-02-10', 'Indonesia', 6, '0811-6909-919', NULL, 'gilviani@studentrofc.com', NULL, 'Jl Proyek Baru No 14N', 'Asmadi', '-', '0813-7101-1969', NULL, 'gilviani@studentrofc.com', '2026-05-16', 1, '2026-06-16', 1, '2026-05-16 22:18:19', '2026-05-16 22:18:19', NULL, 9, '[\"Teori Musik\"]', 1, NULL, 'malu - malu'),
(21, 41, 'Hilya Az Zahra Medina', 'Hilya', 'perempuan', 'Pekanbaru', '2019-04-30', 'Indonesia', 7, '0823-6875-7200', '@babyhilya_20', 'hilya@studentrofc.com', NULL, 'Perumahan villa putri duyung Blok M no 10', 'Sherli Novia', '-', '0823-6875-7200', '@sherli_novia._', 'hilya@studentrofc.com', '2026-05-22', 1, '2026-06-22', 1, '2026-05-16 22:41:13', '2026-05-16 22:41:13', NULL, 9, '[]', 0, NULL, NULL),
(22, 42, 'inara ayudia rahman', 'inara', 'perempuan', 'Pekanbaru', '2018-04-09', 'Indonesia', 8, '081268566860', '@ryzamanda', 'inara@studentrofc.com', NULL, 'jl tengku bey komp bumi sejahtera b1 no 14 air dingin pekanbaru', '-', '-', '081268566860', '@ryzamanda', 'inara @studentrofc.com', '2026-05-23', 1, '2026-06-23', 1, '2026-05-16 22:47:57', '2026-05-16 22:47:57', NULL, 9, '[]', 0, NULL, 'kami akan selalu di nadi mu (jumbo)'),
(23, 43, 'Rinjani Adara Marzuki', 'Jane', 'perempuan', 'pekanbaru', '2016-04-04', 'Indonesia', 10, '081261060814', '@gadis bocah kecil', 'jane@studentrofc.com', NULL, 'jl hangtuah ujung perumahan bukit mutiara permai 3 blok c no 51', 'Mellisa', '-', '081261060814', '@gadis bocah kecil', 'jane@studentrofc.com', '2026-05-17', 1, '2026-06-17', 1, '2026-05-16 22:55:39', '2026-05-16 22:55:39', NULL, 9, '[]', 0, NULL, '-'),
(24, 44, 'Gladion Shawn Hutahaean', 'Gladion', 'laki-laki', 'Pekanbaru', '2018-11-26', 'Indonesia', 7, '08117591974', '@hutahaean_elieser', 'gladion@studentrofc.com', 653, 'Jl. Melur no 71 Harjosari Sukajadi', 'George Sebastian Hutahaean , Gregory Seemby Hutahaean', NULL, '08117531708', '@hutahaean_elieser', 'gladion@studentrofc.com', '2026-05-23', 1, '2026-06-23', 0, '2026-05-16 22:55:44', '2026-05-29 07:49:51', NULL, 9, '[]', 0, NULL, '-'),
(25, 45, 'Senja Mandalawangi Marzuki', 'Senja', 'perempuan', 'pekanbaru', '2018-01-08', 'Indonesia', 8, '081261060814', '@gadis bocah kecil', 'senja@studentrofc.com', NULL, 'jl hangtuah ujung perumahan bukit mutiara permai 3 blok c no 51', 'Mellisa', '-', '081261060814', '@gadis bocah kecil', 'senja@studentrofc.com', '2026-05-17', 1, '2026-06-17', 1, '2026-05-16 23:05:08', '2026-05-16 23:05:08', NULL, 9, '[]', 0, NULL, '-'),
(26, 47, 'Winola', 'Winola', 'perempuan', 'Pekanbaru', '2026-05-18', 'Indonesia', 0, '-', '-', 'winola@studentrofc.com', 695, '-', '-', '-', '-', '-', 'winola@studentrofc.com', '2026-06-12', 1, '2026-07-12', 1, '2026-05-19 07:18:59', '2026-06-12 09:23:22', NULL, 6, '[]', 0, NULL, '-'),
(28, 50, 'Cesca', 'Cesca', 'perempuan', 'Pekanbaru', '2026-05-20', 'Indonesia', 0, '-', '-', 'cesca@studentrofc.com', NULL, '-', '-', '-', '-', '-', 'cesca@studentrofc.com', '2026-05-22', 1, '2026-06-22', 1, '2026-05-21 06:12:13', '2026-05-21 06:12:13', NULL, 9, '[]', 0, NULL, '-'),
(29, 51, 'Fai', 'Fai', 'laki-laki', '-', '2026-05-21', 'Indonesia', 0, '-', '-', 'fai@studentrofc.com', NULL, '-', '-', '-', '-', '-', 'fai@studentrofc.com', '2026-05-22', 1, '2026-06-22', 1, '2026-05-21 06:21:02', '2026-05-21 06:21:02', NULL, 6, '[]', 0, NULL, '-'),
(30, 52, 'Akil', 'Akil', 'laki-laki', 'Pekanbaru', '2026-05-21', 'Indonesia', 0, '-', '-', 'akil@studentrofc.com', NULL, '-', '-', '-', '-', '-', 'akil@studentrofc.com', '2026-05-22', 1, '2026-06-22', 1, '2026-05-21 09:21:10', '2026-05-21 09:21:10', NULL, 7, '[]', 0, NULL, '-'),
(31, 53, 'Zahra', 'Zahra', 'perempuan', 'Pekanbaru', '2026-05-21', 'Indonesia', 0, '-', '-', 'zahra@studentrofc.com', 310, '-', '-', '-', '-', '-', 'zahra@studentrofc.com', '2026-05-23', 1, '2026-06-23', 1, '2026-05-21 09:59:04', '2026-05-29 08:35:09', NULL, 7, '[]', 0, NULL, '-'),
(32, 54, 'Aqila', 'Aqila', 'perempuan', 'Pekanbaru', '2026-05-21', 'Indonesia', 0, '-', '-', 'aqila@gmail.com', 311, '-', '-', '-', '-', '-', 'aqila@gmail.com', '2026-05-23', 1, '2026-06-23', 1, '2026-05-21 10:03:36', '2026-05-29 08:34:35', NULL, 7, '[]', 0, NULL, '-'),
(33, 55, 'Rayzent', 'Rayzent', 'laki-laki', 'Pekanbaru', '2026-05-22', 'Indonesia', 0, '-', '-', 'rayzent@studentrofc.com', 780, '-', '-', '-', '-', '-', 'rayzent@studentrofc.com', '2026-06-09', 1, '2026-07-09', 1, '2026-05-25 04:49:34', '2026-06-09 05:47:28', NULL, 8, '[]', 0, NULL, '-'),
(34, 56, 'Gracio', 'Gracio', 'laki-laki', 'Pekanbaru', '2026-05-26', 'Indonesia', 0, '-', '-', 'gracio@studentrofc.com', NULL, '-', '-', '-', '-', NULL, 'gracio@studentrofc.com', '2026-05-28', 1, '2026-06-28', 1, '2026-05-27 10:42:36', '2026-05-27 10:42:36', NULL, 7, '[]', 0, NULL, '-'),
(35, 57, 'Haeden', 'Haeden', 'laki-laki', 'Pekanbaru', '2026-05-27', 'Indonesia', 0, '-', '-', 'haeden@studentrofc.com', NULL, '-', '-', '-', '-', '-', 'haeden@studentrofc.com', '2026-05-28', 1, '2026-06-28', 1, '2026-05-27 10:59:50', '2026-05-27 10:59:50', NULL, 7, '[]', 0, NULL, '-'),
(36, 58, 'Jeany', 'Jeany', 'perempuan', 'Pekanbaru', '2026-05-27', 'Indonesia', 0, '-', '-', 'jeany@studentrofc.com', 284, '-', '-', '-', '-', '-', 'jeany@studentrofc.com', '2026-05-29', 1, '2026-06-29', 1, '2026-05-28 23:11:03', '2026-05-29 08:36:14', NULL, 7, '[]', 0, NULL, '-'),
(37, 59, 'Jelita', 'Jelita', 'perempuan', 'Pekanbaru', '2026-05-28', 'Indonesia', 0, '-', '-', 'jelita@studentrofc.com', NULL, '-', '-', '-', '-', '-', 'jelita@studentrofc.com', '2026-05-30', 1, '2026-06-30', 1, '2026-05-29 07:49:24', '2026-05-29 07:49:24', NULL, 7, '[]', 0, NULL, '-'),
(38, 60, 'Adam', 'Adam', 'laki-laki', 'Pekanbaru', '2026-05-28', 'Indonesia', 0, '-', '-', 'adam@studentrofc.com', 313, '-', '-', '-', '-', '-', 'adam@studentrofc.com', '2026-05-30', 1, '2026-06-30', 1, '2026-05-29 07:49:28', '2026-05-29 08:26:27', NULL, 7, '[]', 0, NULL, '-'),
(39, 61, 'Arkan', 'Arkan', 'laki-laki', 'Pekanbaru', '2026-05-27', 'Indonesia', 0, '-', '-', 'arkan@studentrofc.com', NULL, '-', '-', '-', '-', '-', 'arkan@studentrofc.com', '2026-05-30', 1, '2026-06-30', 1, '2026-05-29 08:41:13', '2026-05-29 08:41:13', NULL, 7, '[]', 0, NULL, '-');

-- --------------------------------------------------------

--
-- Struktur dari tabel `student_progress`
--

CREATE TABLE `student_progress` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `topic` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `recorded_at` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `student_progress`
--

INSERT INTO `student_progress` (`id`, `student_id`, `class_id`, `teacher_id`, `topic`, `note`, `score`, `recorded_at`, `created_at`, `updated_at`) VALUES
(2, 16, 6, 9, 'Dinamika', 'Sudah paham pengertian dinamika, namun belum maksimal pada penerapan.', 75, '2026-05-16', '2026-05-16 03:19:24', '2026-05-16 03:19:24'),
(3, 16, 6, 9, 'Dinamika', 'Sudah paham pengertian dinamika, namun belum maksimal pada penerapan.', 75, '2026-05-16', '2026-05-16 03:19:37', '2026-05-16 03:19:37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `teachers`
--

CREATE TABLE `teachers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `religion` varchar(30) DEFAULT NULL,
  `instrument` varchar(255) NOT NULL,
  `bio` text DEFAULT NULL,
  `experience` varchar(255) DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `ktp_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `teachers`
--

INSERT INTO `teachers` (`id`, `user_id`, `name`, `phone`, `address`, `gender`, `religion`, `instrument`, `bio`, `experience`, `photo_path`, `ktp_path`, `is_active`, `created_at`, `updated_at`) VALUES
(8, 18, 'MUHAMMAD AZZAM', '085271564016', 'Jl. Dorak', 'laki-laki', 'islam', 'Violin', NULL, NULL, NULL, 'teachers/ktp/ur5RtcIXXmgVwGSOkd7pykLJf7rvcpIMxOYv4jHE.jpg', 1, '2026-05-03 07:01:55', '2026-05-05 08:52:21'),
(9, 19, 'ABDUL HAMID', '082284111619', 'ROKAN HULU,DALU-DALU,TAMBUSAI TENGAH , RT 1 RW 1', 'laki-laki', 'Islam', 'VOCAL', NULL, NULL, NULL, 'teachers/ktp/Wwj1i6vEQePvC9D4eCLLXbVhT6uCMM3DDiW7arOe.jpg', 1, '2026-05-05 08:07:53', '2026-05-05 08:54:25'),
(10, 20, 'IDA BAGUS AHLAN ZEN', '089697730896', 'Pekanbaru, Jl.Pertanian Perm Lobak, Bukit Lestari Blok A3, Rt 2 Rw 12. Kel.Delima Kec.Tampan.', 'laki-laki', 'Kristen', 'Drum', NULL, NULL, NULL, 'teachers/ktp/nZ5SHuOdoDg8RNy628dBi0a0rqXi7ZCt3YtCnvlI.jpg', 1, '2026-05-05 08:14:36', '2026-05-05 08:14:36'),
(11, 21, 'ROBY LAMBERTUS HADINATA', '089506169230', 'Pekanbaru,Jl.Pertama 1 GG.Permata 1, No 50.  Rt 1 Rw 1, Kel.BandarRaya , Kec.PayungSekaki', 'laki-laki', 'Katholik', 'Guitar', NULL, NULL, NULL, 'teachers/ktp/Js0yJ02P0hD202m3yJxEfbQ0ZcwOGrwFBVWcwvrB.jpg', 1, '2026-05-05 08:19:59', '2026-05-05 08:54:14'),
(12, 22, 'DEWI HANDAYANI', '081266929666', 'Pekanbaru', 'perempuan', 'Islam', 'Piano', NULL, NULL, NULL, NULL, 1, '2026-05-05 08:34:38', '2026-05-05 08:54:03'),
(14, 34, 'ZAMZAM KAMIL', '082258724356', 'JL.MUHAJIRIN GG.KUANTAN', 'laki-laki', 'ISLAM', 'Piano', NULL, NULL, NULL, 'teachers/ktp/bIhLgA52Otpg0U3PRWEXcrKios1jxdNh92tAm1wY.jpg', 1, '2026-05-13 12:24:34', '2026-05-13 12:24:49'),
(15, 46, 'SHYAKIRA FATIHA', '085837548792', 'JL.GARUDA NO 28', 'perempuan', 'Islam', 'Vocal', NULL, NULL, NULL, 'teachers/ktp/epKsjpkJdjiH9gWbgyqc6Lu7pgpeQepB3KImdYq6.jpg', 1, '2026-05-19 06:57:18', '2026-05-27 05:53:55'),
(16, 49, 'TRI SUTRISNO', '085263432288', 'JL.KH NASUTION NO.27\r\nSIMPANG TIGA', 'laki-laki', 'Islam', 'Guitar', NULL, NULL, NULL, 'teachers/ktp/RqMdG2xvfqJzR2ZczIe5RO7O6U6NG41VxOV6av6N.jpg', 1, '2026-05-21 05:54:03', '2026-05-27 05:54:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `teacher_attendances`
--

CREATE TABLE `teacher_attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `attendance_date` date NOT NULL,
  `status` enum('present','absent','late') NOT NULL DEFAULT 'present',
  `location_text` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `teacher_attendances`
--

INSERT INTO `teacher_attendances` (`id`, `teacher_id`, `attendance_date`, `status`, `location_text`, `latitude`, `longitude`, `note`, `created_at`, `updated_at`) VALUES
(3, 10, '2026-05-08', 'present', '-6.243965, 106.784130', -6.2439650, 106.7841300, NULL, '2026-05-08 03:13:56', '2026-05-08 03:13:56');

-- --------------------------------------------------------

--
-- Struktur dari tabel `teacher_leaves`
--

CREATE TABLE `teacher_leaves` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `substitute_teacher_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `teacher_leaves`
--

INSERT INTO `teacher_leaves` (`id`, `teacher_id`, `substitute_teacher_id`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(1, 11, 16, '2026-06-12', '2026-08-12', '2026-06-12 16:40:19', '2026-06-12 16:40:19');

-- --------------------------------------------------------

--
-- Struktur dari tabel `teacher_salaries`
--

CREATE TABLE `teacher_salaries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `period` varchar(255) NOT NULL,
  `base_salary` decimal(12,2) NOT NULL DEFAULT 0.00,
  `bonus` decimal(12,2) NOT NULL DEFAULT 0.00,
  `deduction` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_paid` decimal(12,2) NOT NULL DEFAULT 0.00,
  `paid_at` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(2, 'Admin Baru', 'admin2@gmail.com', NULL, '$2y$12$AW6y5kMxQLvD/5UhqQpxkOokh75Iws.70Ly7eGqlqD45unYvFnLh2', 'Ig6bWzVCmBzIoIXAu5hEtq4gbiR9d33lSry76lAQw8vFHMYQQZT4wEUCaM6n', '2026-04-08 04:48:14', '2026-04-08 04:48:14'),
(5, 'Manager', 'manager@gmail.com', NULL, '$2y$12$ahsdkCgwYMzi9gumO4f8M.i9mOhLRIxHbQ33DSpJWjJcuexgjIe6.', NULL, '2026-04-08 06:12:23', '2026-04-08 06:12:23'),
(18, 'MUHAMMAD AZZAM', 'azam@rofc.com', NULL, '$2y$12$vWNaYtRsm512EffVbf4KT.oguCsBIfRIV/j8Cm63ug5.QIQMVQkzO', NULL, '2026-05-03 07:01:55', '2026-05-05 08:36:36'),
(19, 'ABDUL HAMID', 'abdul@rofc.com', NULL, '$2y$12$7a6T3kwf7Jj2DzgazkfyCeE6NbKbQyFBudgo2MNC/o49Gl3vRWWsK', 'q5HlYXHtWQ4ljCntOJVItdtT8LE4FGBEciZAtLYvhSJQ6WpoZYsjfZVP7D2m', '2026-05-05 08:07:52', '2026-06-05 05:52:38'),
(20, 'IDA BAGUS AHLAN ZEN', 'ahlan@rofc.com', NULL, '$2y$12$w1QPRCehlU8cuvt/zkvDt.dTqFPzRuEjml9Fuux/oofiVHQ175gD.', 'lnPfQOtskD8pVEWrFTEXmTCaDeO4rgfdAr7xuX0kWNcZXDSPQnlCEP59y9Js', '2026-05-05 08:14:35', '2026-05-05 08:14:35'),
(21, 'ROBY LAMBERTUS HADINATA', 'roby@rofc.com', NULL, '$2y$12$2wgV6/Qu8dVmttmHXxUUyOPJynAl0opkG4bO.I/nN3iqWNo1TB7ta', 'RI9GsM7opusYvduv25QUfLMwdD5ubKyu0br477VjLzwXSPTI2zGYIbavP9PE', '2026-05-05 08:19:59', '2026-05-05 08:19:59'),
(22, 'DEWI HANDAYANI', 'dewi@rofc.com', NULL, '$2y$12$6TbWq8ukB6n3uEyHYTD9iOhGtdkpL4qX.CMDp.Zdtb9DhPU/.3dNa', NULL, '2026-05-05 08:34:38', '2026-05-19 09:55:03'),
(34, 'ZAMZAM KAMIL', 'nizam@rofc.com', NULL, '$2y$12$nR8VJIjZZCFf7/PFfALwMuWxasU/5CbmdH/E8qY7/ZmjQjZ3..tZS', NULL, '2026-05-13 12:24:34', '2026-05-20 06:24:24'),
(36, 'saddam abrizam detik', 'saddam@studentrofc.com', NULL, '$2y$12$AJox4xKl0TdniMckGguhCuG12zAXqAZiHeB7A3YuHjO/JNW78sp5i', NULL, '2026-05-15 01:51:17', '2026-05-15 01:51:17'),
(37, 'KENRICK ANANTA HO', 'kenrick@studentrofc.com', NULL, '$2y$12$7CcFFuJikS3gW2AbN8U2Wut38Ejbj./wFta0W.D5bYVDABeT3hK22', NULL, '2026-05-15 09:57:29', '2026-05-15 09:57:29'),
(38, 'keysha khayira danis', 'key@studentrofc.com', NULL, '$2y$12$Z.aPWpazPF7Ik6PcwwqwpumOSc5dLVyvEhpmoZxr5d79F6E4gGpwu', NULL, '2026-05-16 03:29:58', '2026-05-16 03:29:58'),
(39, 'Alaric Ava Alteza', 'alaric@studentrofc.com', NULL, '$2y$12$sVHOCHiI.emAW8crRZne8ec.Nkxt65olp.tDPh5Itigk3jrx77dw.', NULL, '2026-05-16 03:30:17', '2026-05-16 03:30:17'),
(40, 'Gilviani Aurelia Chow', 'gilviani@studentrofc.com', NULL, '$2y$12$oycUyING4AabsfVjDs3FLOTW/uXpRPXE9ALJkuwdj82xsv5IWDFXi', NULL, '2026-05-16 22:18:16', '2026-05-16 22:18:16'),
(41, 'Hilya Az Zahra Medina', 'hilya@studentrofc.com', NULL, '$2y$12$XC827u5KYhoF5QBAGhJUB.9ocWQDTEjUNOYlzWNG5RS/c1KZC/0sq', NULL, '2026-05-16 22:41:13', '2026-05-16 22:41:13'),
(42, 'inara ayudia rahman', 'inara@studentrofc.com', NULL, '$2y$12$/FKUsrPiSRQ60BFYxsGja.xRjNhb1qtdWkWGThr2F0dzYYVNBjJAq', NULL, '2026-05-16 22:47:57', '2026-05-16 22:47:57'),
(43, 'Rinjani Adara Marzuki', 'jane@studentrofc.com', NULL, '$2y$12$UXtZ7radU68tAR12SUF./uEeImunr8ZHSV.hV2MZiVrEIu1FHA0z6', NULL, '2026-05-16 22:55:39', '2026-05-16 22:55:39'),
(44, 'Gladion Shawn Hutahaean', 'gladion@studentrofc.com', NULL, '$2y$12$ZoMUGioyVMfaWX4xmzkAGugQyRfDRrMpd440Vb3Phxsyj.wNVlfHq', NULL, '2026-05-16 22:55:44', '2026-05-16 22:55:44'),
(45, 'Senja Mandalawangi Marzuki', 'senja@studentrofc.com', NULL, '$2y$12$IDCl9wd6eiFwXUPyFUtAEeQ50/l6.KPx3RXIODeK4A.VaATuWyNJS', NULL, '2026-05-16 23:05:08', '2026-05-16 23:05:08'),
(46, 'SHYAKIRA FATIHA', 'shyakira@rofc.com', NULL, '$2y$12$wIkENjdVVSrpXyjXkwQYX.t3cWT0n0KN2RoO38jM36b0hpas9w0uu', NULL, '2026-05-19 06:57:18', '2026-05-19 06:57:18'),
(47, 'Winola', 'winola@studentrofc.com', NULL, '$2y$12$Cq1xogtJ9Sb85D5exji8MOtvZZFaPZfv4rO3OflAxeyJyoBd3BEH.', NULL, '2026-05-19 07:18:59', '2026-05-19 07:18:59'),
(49, 'TRI SUTRISNO', 'tris@rofc.com', NULL, '$2y$12$327s9Lb1pNED17Yfe4z0.unCyMTEbz/2hT4AkNJt/fHGDBxhs6VJm', 'Jm98edGInjCtLRzcW0UW75noDuauYLS8lCyF1pftlPdgqg5X0XGOlIlIE0cJ', '2026-05-21 05:54:02', '2026-05-21 05:55:54'),
(50, 'Cesca', 'cesca@studentrofc.com', NULL, '$2y$12$zjLYix69.TE96K5dOxrTSOdXcRJXapVAHA7YvCxHPAArnTlid8Foa', NULL, '2026-05-21 06:12:13', '2026-05-21 06:12:13'),
(51, 'Fai', 'fai@studentrofc.com', NULL, '$2y$12$rIBvrIZz6fiIGUX.Ed9G8uIPjeqV2W5cAvJSJbwv/Y65LkbNSQX1i', NULL, '2026-05-21 06:21:02', '2026-05-21 06:21:02'),
(52, 'Akil', 'akil@studentrofc.com', NULL, '$2y$12$e9uF4V8AihUl4u8eUosI5.x5XGC6sHsQtesC9VBijt1UtZbn59eIO', NULL, '2026-05-21 09:21:10', '2026-05-21 09:21:10'),
(53, 'Zahra', 'zahra@studentrofc.com', NULL, '$2y$12$ayBocShGxHXyzJA1Ee1BR.2ElTvDXVB3MmY9hJX0Luxb7HCVACLM.', NULL, '2026-05-21 09:59:03', '2026-05-21 09:59:03'),
(54, 'Aqila', 'aqila@gmail.com', NULL, '$2y$12$/c1hQLZ6ue1Qqc0ypb2UCegPz9H3HLDjSm2V3vqlFDn.u39uZs6RW', NULL, '2026-05-21 10:03:36', '2026-05-21 10:03:36'),
(55, 'Rayzent', 'rayzent@studentrofc.com', NULL, '$2y$12$knscLlEUf9L04NWWPtd9C.jc3NQ4S53qudspW93S2BUCC/0AInc26', NULL, '2026-05-25 04:49:34', '2026-05-25 04:49:34'),
(56, 'Gracio', 'gracio@studentrofc.com', NULL, '$2y$12$o6L7T2aHHfhKCC5frf83Ue.YREqdzvuFAednTSFyySdQ76L.2gRvO', NULL, '2026-05-27 10:42:36', '2026-05-27 10:42:36'),
(57, 'Haeden', 'haeden@studentrofc.com', NULL, '$2y$12$6AJ3eiORGtm0sRSis45oNeRF/cCpr8CpeSaVVoPFtiAsVKG9.oiWO', NULL, '2026-05-27 10:59:50', '2026-05-27 10:59:50'),
(58, 'Jeany', 'jeany@studentrofc.com', NULL, '$2y$12$dGEWQwhl6vjwmgAk5Bcafu5NQBwzCruoSeEjD9b.7bUbAa1dSAxEe', NULL, '2026-05-28 23:11:03', '2026-05-28 23:11:03'),
(59, 'Jelita', 'jelita@studentrofc.com', NULL, '$2y$12$6iE2bujULpN/J0F4OrA2hOrLHL8j9kwVZ7mDiWL5SyFxEsDW5wI7O', NULL, '2026-05-29 07:49:24', '2026-05-29 07:49:24'),
(60, 'Adam', 'adam@studentrofc.com', NULL, '$2y$12$158v5HZRTRH9z7oqYv5n5OvwLUSvRy3KRytrcALCsXRF6znbfvXgC', NULL, '2026-05-29 07:49:28', '2026-05-29 07:49:28'),
(61, 'Arkan', 'arkan@studentrofc.com', NULL, '$2y$12$ioZBBhGMLaL6iw2Q1fAtWOJwQazqKvcZv4gk0xytxTycTLXZ5l5rq', NULL, '2026-05-29 08:41:13', '2026-05-29 08:41:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_roles`
--

CREATE TABLE `user_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `user_roles`
--

INSERT INTO `user_roles` (`id`, `user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(2, 2, 1, NULL, NULL),
(5, 5, 4, NULL, NULL),
(18, 18, 3, NULL, NULL),
(19, 19, 3, NULL, NULL),
(20, 20, 3, NULL, NULL),
(21, 21, 3, NULL, NULL),
(22, 22, 3, NULL, NULL),
(34, 34, 3, NULL, NULL),
(36, 36, 5, NULL, NULL),
(37, 37, 5, NULL, NULL),
(38, 38, 5, NULL, NULL),
(39, 39, 5, NULL, NULL),
(40, 40, 5, NULL, NULL),
(41, 41, 5, NULL, NULL),
(42, 42, 5, NULL, NULL),
(43, 43, 5, NULL, NULL),
(44, 44, 5, NULL, NULL),
(45, 45, 5, NULL, NULL),
(46, 46, 3, NULL, NULL),
(47, 47, 5, NULL, NULL),
(49, 49, 3, NULL, NULL),
(50, 50, 5, NULL, NULL),
(51, 51, 5, NULL, NULL),
(52, 52, 5, NULL, NULL),
(53, 53, 5, NULL, NULL),
(54, 54, 5, NULL, NULL),
(55, 55, 5, NULL, NULL),
(56, 56, 5, NULL, NULL),
(57, 57, 5, NULL, NULL),
(58, 58, 5, NULL, NULL),
(59, 59, 5, NULL, NULL),
(60, 60, 5, NULL, NULL),
(61, 61, 5, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activities_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendance_class_id_foreign` (`class_id`),
  ADD KEY `attendance_student_id_foreign` (`student_id`),
  ADD KEY `attendance_teacher_id_foreign` (`teacher_id`);

--
-- Indeks untuk tabel `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendances_teacher_id_foreign` (`teacher_id`),
  ADD KEY `attendances_student_id_foreign` (`student_id`),
  ADD KEY `attendances_session_id_foreign` (`session_id`),
  ADD KEY `attendances_schedule_id_foreign` (`schedule_id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classes_teacher_id_foreign` (`teacher_id`);

--
-- Indeks untuk tabel `class_students`
--
ALTER TABLE `class_students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `class_students_class_id_student_id_unique` (`class_id`,`student_id`),
  ADD KEY `class_students_student_id_foreign` (`student_id`);

--
-- Indeks untuk tabel `class_teacher`
--
ALTER TABLE `class_teacher`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `class_teacher_class_id_teacher_id_unique` (`class_id`,`teacher_id`),
  ADD KEY `class_teacher_teacher_id_foreign` (`teacher_id`);

--
-- Indeks untuk tabel `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `galleries`
--
ALTER TABLE `galleries`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `invoices_student_id_foreign` (`student_id`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `materials_class_id_foreign` (`class_id`),
  ADD KEY `materials_teacher_id_foreign` (`teacher_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_student_id_foreign` (`student_id`),
  ADD KEY `payments_invoice_id_foreign` (`invoice_id`),
  ADD KEY `payments_class_id_foreign` (`class_id`);

--
-- Indeks untuk tabel `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `posts_slug_unique` (`slug`),
  ADD KEY `posts_author_id_foreign` (`author_id`);

--
-- Indeks untuk tabel `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `registrations_class_id_foreign` (`class_id`),
  ADD KEY `registrations_schedule_id_foreign` (`schedule_id`);

--
-- Indeks untuk tabel `registration_schedules`
--
ALTER TABLE `registration_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `registration_schedules_registration_id_foreign` (`registration_id`),
  ADD KEY `registration_schedules_schedule_id_foreign` (`schedule_id`);

--
-- Indeks untuk tabel `reschedule_requests`
--
ALTER TABLE `reschedule_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reschedule_requests_student_id_foreign` (`student_id`),
  ADD KEY `reschedule_requests_old_schedule_id_foreign` (`old_schedule_id`),
  ADD KEY `reschedule_requests_new_schedule_id_foreign` (`new_schedule_id`),
  ADD KEY `reschedule_requests_approved_by_foreign` (`approved_by`),
  ADD KEY `reschedule_requests_old_session_id_foreign` (`old_session_id`),
  ADD KEY `reschedule_requests_new_session_id_foreign` (`new_session_id`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_slug_unique` (`slug`);

--
-- Indeks untuk tabel `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `schedules_full_unique` (`class_id`,`day`,`time`,`teacher_id`),
  ADD KEY `schedules_teacher_id_foreign` (`teacher_id`),
  ADD KEY `schedules_student_id_foreign` (`student_id`);

--
-- Indeks untuk tabel `schedule_sessions`
--
ALTER TABLE `schedule_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedule_sessions_schedule_id_foreign` (`schedule_id`),
  ADD KEY `schedule_sessions_student_id_foreign` (`student_id`),
  ADD KEY `schedule_sessions_teacher_id_foreign` (`teacher_id`),
  ADD KEY `schedule_sessions_class_id_foreign` (`class_id`),
  ADD KEY `schedule_sessions_substitute_teacher_id_foreign` (`substitute_teacher_id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indeks untuk tabel `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_schedule_id_unique` (`schedule_id`),
  ADD KEY `students_class_id_foreign` (`class_id`),
  ADD KEY `students_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `student_progress`
--
ALTER TABLE `student_progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_progress_student_id_foreign` (`student_id`),
  ADD KEY `student_progress_class_id_foreign` (`class_id`),
  ADD KEY `student_progress_teacher_id_foreign` (`teacher_id`);

--
-- Indeks untuk tabel `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teachers_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `teacher_attendances`
--
ALTER TABLE `teacher_attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_attendances_teacher_id_foreign` (`teacher_id`);

--
-- Indeks untuk tabel `teacher_leaves`
--
ALTER TABLE `teacher_leaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_leaves_teacher_id_foreign` (`teacher_id`),
  ADD KEY `teacher_leaves_substitute_teacher_id_foreign` (`substitute_teacher_id`);

--
-- Indeks untuk tabel `teacher_salaries`
--
ALTER TABLE `teacher_salaries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_salaries_teacher_id_foreign` (`teacher_id`);

--
-- Indeks untuk tabel `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indeks untuk tabel `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_roles_user_id_role_id_unique` (`user_id`,`role_id`),
  ADD KEY `user_roles_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `activities`
--
ALTER TABLE `activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=462;

--
-- AUTO_INCREMENT untuk tabel `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT untuk tabel `classes`
--
ALTER TABLE `classes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `class_students`
--
ALTER TABLE `class_students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `class_teacher`
--
ALTER TABLE `class_teacher`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `galleries`
--
ALTER TABLE `galleries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `materials`
--
ALTER TABLE `materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT untuk tabel `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `registrations`
--
ALTER TABLE `registrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT untuk tabel `registration_schedules`
--
ALTER TABLE `registration_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT untuk tabel `reschedule_requests`
--
ALTER TABLE `reschedule_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=868;

--
-- AUTO_INCREMENT untuk tabel `schedule_sessions`
--
ALTER TABLE `schedule_sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=215;

--
-- AUTO_INCREMENT untuk tabel `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT untuk tabel `student_progress`
--
ALTER TABLE `student_progress`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `teacher_attendances`
--
ALTER TABLE `teacher_attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `teacher_leaves`
--
ALTER TABLE `teacher_leaves`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `teacher_salaries`
--
ALTER TABLE `teacher_salaries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT untuk tabel `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendance_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendance_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendances_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `schedule_sessions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendances_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendances_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `class_students`
--
ALTER TABLE `class_students`
  ADD CONSTRAINT `class_students_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_students_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `class_teacher`
--
ALTER TABLE `class_teacher`
  ADD CONSTRAINT `class_teacher_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_teacher_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `materials`
--
ALTER TABLE `materials`
  ADD CONSTRAINT `materials_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `materials_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `registrations_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `registration_schedules`
--
ALTER TABLE `registration_schedules`
  ADD CONSTRAINT `registration_schedules_registration_id_foreign` FOREIGN KEY (`registration_id`) REFERENCES `registrations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `registration_schedules_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `reschedule_requests`
--
ALTER TABLE `reschedule_requests`
  ADD CONSTRAINT `reschedule_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reschedule_requests_new_schedule_id_foreign` FOREIGN KEY (`new_schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reschedule_requests_new_session_id_foreign` FOREIGN KEY (`new_session_id`) REFERENCES `schedule_sessions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reschedule_requests_old_schedule_id_foreign` FOREIGN KEY (`old_schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reschedule_requests_old_session_id_foreign` FOREIGN KEY (`old_session_id`) REFERENCES `schedule_sessions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reschedule_requests_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedules_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `schedules_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `schedule_sessions`
--
ALTER TABLE `schedule_sessions`
  ADD CONSTRAINT `schedule_sessions_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedule_sessions_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedule_sessions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedule_sessions_substitute_teacher_id_foreign` FOREIGN KEY (`substitute_teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `schedule_sessions_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `students_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `student_progress`
--
ALTER TABLE `student_progress`
  ADD CONSTRAINT `student_progress_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_progress_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_progress_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `teacher_attendances`
--
ALTER TABLE `teacher_attendances`
  ADD CONSTRAINT `teacher_attendances_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `teacher_leaves`
--
ALTER TABLE `teacher_leaves`
  ADD CONSTRAINT `teacher_leaves_substitute_teacher_id_foreign` FOREIGN KEY (`substitute_teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teacher_leaves_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `teacher_salaries`
--
ALTER TABLE `teacher_salaries`
  ADD CONSTRAINT `teacher_salaries_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
