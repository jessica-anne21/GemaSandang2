-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Apr 26, 2026 at 07:52 PM
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
-- Database: `gemasandangdb2`
--

-- --------------------------------------------------------

--
-- Table structure for table `bargains`
--

CREATE TABLE `bargains` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `harga_tawaran` int(11) NOT NULL,
  `status` enum('pending','accepted','rejected') NOT NULL DEFAULT 'pending',
  `catatan_admin` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bargains`
--

INSERT INTO `bargains` (`id`, `user_id`, `product_id`, `harga_tawaran`, `status`, `catatan_admin`, `created_at`, `updated_at`, `is_read`) VALUES
(1, 4, 4, 100000, 'accepted', NULL, '2025-12-19 00:10:21', '2025-12-28 10:24:19', 1),
(2, 4, 4, 160000, 'rejected', NULL, '2025-12-27 07:36:17', '2025-12-28 10:24:19', 1),
(3, 2, 4, 100000, 'rejected', 'Tawaran terlalu rendah. Minimal 125.000 ya', '2025-12-28 09:23:31', '2025-12-28 10:21:02', 1),
(4, 2, 4, 125000, 'accepted', NULL, '2025-12-28 09:35:21', '2025-12-28 10:21:02', 1),
(5, 2, 6, 50000, 'rejected', 'Stok habis', '2025-12-28 10:23:20', '2025-12-28 17:20:12', 1),
(6, 1, 6, 80000, 'rejected', 'Maaf, kesepakatan dibatalkan karena melebihi batas waktu pembayaran.', '2025-12-28 10:23:51', '2025-12-28 10:31:22', 1),
(7, 4, 6, 42500, 'rejected', 'Maaf, kesepakatan dibatalkan karena melebihi batas waktu pembayaran.', '2025-12-28 10:24:18', '2025-12-28 10:34:53', 1),
(20, 4, 8, 60000, 'accepted', NULL, '2026-01-08 14:54:54', '2026-01-08 14:55:36', 1);

-- --------------------------------------------------------

--
-- Table structure for table `barter_items`
--

CREATE TABLE `barter_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `kategori` enum('Outer','Dress','Atasan','Celana','Aksesoris') NOT NULL,
  `kondisi` varchar(100) NOT NULL,
  `foto_barang` varchar(255) NOT NULL,
  `status` enum('available','traded','hidden') DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('gema_sandang_cache_budi@gmail.com|127.0.0.1', 'i:1;', 1770884265),
('gema_sandang_cache_budi@gmail.com|127.0.0.1:timer', 'i:1770884265;', 1770884265),
('gema_sandang_cache_jessica@gmail.com|127.0.0.1', 'i:1;', 1776324153),
('gema_sandang_cache_jessica@gmail.com|127.0.0.1:timer', 'i:1776324153;', 1776324153);

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
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` float NOT NULL,
  `is_bargain` tinyint(1) NOT NULL DEFAULT 0,
  `bargain_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `product_id`, `quantity`, `price`, `is_bargain`, `bargain_id`, `created_at`, `updated_at`) VALUES
(3, 1, 4, 1, 190000, 0, NULL, '2026-01-08 14:54:11', '2026-01-08 14:54:11');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_kategori` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `nama_kategori`, `created_at`, `updated_at`) VALUES
(1, 'Outer', '2025-10-23 01:17:25', '2025-12-14 09:44:08'),
(2, 'Celana', '2025-10-23 07:24:25', '2025-10-23 07:24:25'),
(3, 'Atasan', '2025-11-22 09:53:50', '2025-11-22 09:53:50'),
(4, 'Dress', '2025-12-14 09:43:58', '2025-12-14 09:43:58'),
(5, 'Aksesoris', '2025-12-14 09:44:33', '2025-12-14 09:44:33');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `trend_id` int(11) NOT NULL,
  `isi_komentar` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `trend_id`, `isi_komentar`, `created_at`, `updated_at`) VALUES
(2, 4, 4, 'bagus banget', '2026-04-16 07:21:51', '2026-04-16 07:21:51'),
(4, 7, 39, 'wah keren banget', '2026-04-19 19:01:56', '2026-04-19 19:01:56');

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
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `isi_pesan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `isi_pesan`, `created_at`, `updated_at`) VALUES
(1, 2, 4, 'hai', '2026-03-28 15:10:44', '2026-03-28 15:10:44'),
(2, 4, 2, 'halo juga', '2026-03-28 15:11:17', '2026-03-28 15:11:17'),
(3, 4, 2, 'lagi apa', '2026-03-28 15:15:39', '2026-03-28 15:15:39'),
(4, 4, 2, 'test', '2026-03-28 15:17:58', '2026-03-28 15:17:58'),
(5, 2, 4, 'test', '2026-03-28 15:18:03', '2026-03-28 15:18:03'),
(6, 4, 2, 'test', '2026-03-28 15:21:39', '2026-03-28 15:21:39'),
(7, 4, 2, 'hai', '2026-03-28 15:23:20', '2026-03-28 15:23:20'),
(8, 2, 4, 'halo', '2026-03-28 15:23:28', '2026-03-28 15:23:28'),
(9, 2, 4, 'test', '2026-03-28 15:23:44', '2026-03-28 15:23:44'),
(10, 2, 4, '1', '2026-03-28 15:25:30', '2026-03-28 15:25:30'),
(11, 2, 4, 'hai', '2026-03-28 15:27:04', '2026-03-28 15:27:04'),
(12, 2, 4, 'hai', '2026-03-28 15:28:41', '2026-03-28 15:28:41'),
(13, 2, 4, 'testtest', '2026-03-28 15:28:53', '2026-03-28 15:28:53'),
(14, 4, 2, 'hai', '2026-03-28 15:30:19', '2026-03-28 15:30:19'),
(15, 4, 2, 'OMAIGATTT', '2026-03-28 15:30:25', '2026-03-28 15:30:25'),
(16, 2, 4, 'yasssss', '2026-03-28 15:30:29', '2026-03-28 15:30:29'),
(17, 4, 2, 'hai', '2026-03-28 15:30:42', '2026-03-28 15:30:42'),
(18, 2, 4, 'hai juga', '2026-03-28 15:30:47', '2026-03-28 15:30:47'),
(19, 2, 4, 'test lagi', '2026-03-28 15:33:24', '2026-03-28 15:33:24'),
(20, 4, 2, 'test', '2026-03-28 15:33:29', '2026-03-28 15:33:29'),
(21, 2, 4, 'haiiiii', '2026-03-28 15:34:54', '2026-03-28 15:34:54'),
(22, 4, 2, 'hai', '2026-03-28 15:34:59', '2026-03-28 15:34:59'),
(23, 2, 4, 'hola', '2026-03-28 15:38:10', '2026-03-28 15:38:10'),
(24, 4, 2, 'holaaa', '2026-03-28 15:38:13', '2026-03-28 15:38:13'),
(25, 2, 4, 'holaaa', '2026-03-28 15:38:16', '2026-03-28 15:38:16'),
(26, 4, 2, 'omaigatt', '2026-03-28 15:38:57', '2026-03-28 15:38:57'),
(27, 4, 2, 'hai', '2026-04-16 07:22:06', '2026-04-16 07:22:06'),
(28, 4, 2, 'hai', '2026-04-16 07:22:10', '2026-04-16 07:22:10'),
(29, 4, 2, 'hai', '2026-04-16 07:22:13', '2026-04-16 07:22:13'),
(30, 4, 2, 'hai', '2026-04-16 07:22:15', '2026-04-16 07:22:15'),
(31, 4, 2, 'hai', '2026-04-16 07:22:37', '2026-04-16 07:22:37'),
(32, 4, 2, 'test', '2026-04-16 07:22:43', '2026-04-16 07:22:43'),
(33, 2, 4, 'woy', '2026-04-16 07:24:04', '2026-04-16 07:24:04'),
(34, 4, 2, 'lagi apa', '2026-04-16 07:24:10', '2026-04-16 07:24:10'),
(35, 4, 2, 'lagi apa', '2026-04-16 07:24:10', '2026-04-16 07:24:10'),
(36, 2, 4, 'ngopi', '2026-04-16 07:24:13', '2026-04-16 07:24:13');

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
(4, '2025_10_09_171657_create_categories_table', 1),
(5, '2025_10_09_171657_create_products_table', 1),
(6, '2025_10_09_171658_create_orders_table', 1),
(7, '2025_10_09_172923_create_order_items_table', 2),
(8, '2025_11_22_170016_add_shipping_details_to_orders_table', 3),
(9, '2025_11_22_171258_add_payment_proof_to_orders_table', 4),
(10, '2025_11_22_172614_change_status_column_in_orders_table', 5),
(11, '2025_11_22_174800_add_received_date_to_orders_table', 6),
(12, '2025_12_19_064416_create_bargains_table', 7),
(13, '2025_12_28_204915_add_soft_deletes_to_products_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `total_harga` float NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'menunggu_pembayaran',
  `alamat_pengiriman` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `kurir` varchar(255) DEFAULT NULL,
  `layanan` varchar(255) DEFAULT NULL,
  `ongkir` int(11) NOT NULL DEFAULT 0,
  `nomor_resi` varchar(255) DEFAULT NULL,
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `tanggal_diterima` timestamp NULL DEFAULT NULL,
  `nomor_hp` varchar(20) NOT NULL,
  `catatan_customer` varchar(255) DEFAULT NULL,
  `catatan_admin` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_harga`, `status`, `alamat_pengiriman`, `created_at`, `updated_at`, `kurir`, `layanan`, `ongkir`, `nomor_resi`, `bukti_bayar`, `tanggal_diterima`, `nomor_hp`, `catatan_customer`, `catatan_admin`) VALUES
(7, 1, 60000, 'selesai', 'Jl Pakar Timur IV', '2025-11-22 10:39:38', '2025-11-22 10:45:43', 'jne', NULL, 20000, '12345678', 'payment_proofs/4g5PP2YkI3ZF4uccvajmcyMhyaHyjOZShymfMg8k.jpg', NULL, '08123456789', '', NULL),
(8, 1, 170000, 'selesai', 'Jl Pakar Timur IV', '2025-11-22 10:52:38', '2025-11-22 11:08:12', 'sicepat', NULL, 20000, '119273192371', 'payment_proofs/SFaPeYpoBJKftsg9pP1pTWQmSPmUD9kHwaToKfwc.jpg', '2025-11-22 11:08:12', '0', '', NULL),
(9, 2, 50000, 'selesai', 'Jl. Cibogo 31', '2025-11-27 18:01:42', '2025-11-27 18:06:25', 'grab', NULL, 20000, '764767479', 'payment_proofs/LN62trykCEPV37fMVQfLjJpVfDe9mDQcSY8fb8YH.jpg', '2025-11-27 18:06:25', '0812345678', '', NULL),
(10, 4, 210000, 'selesai', 'Jl Pakar Timur IV', '2025-12-14 22:52:24', '2025-12-28 13:46:01', 'sicepat', NULL, 35000, '119273192371', 'payment_proofs/7lVbK0vCYrRhEgTgIRLXrifzKjyhhEStSCdpfYbJ.png', '2025-12-28 13:46:01', '0812345678', '', NULL),
(11, 4, 210000, 'dibatalkan', 'Jl Surya Sumantri', '2025-12-27 10:17:25', '2025-12-27 10:55:56', 'sicepat', NULL, 20000, NULL, NULL, NULL, '0812345678', '', NULL),
(18, 4, 225000, 'dibatalkan', 'Jl Dago', '2025-12-27 11:54:26', '2025-12-27 11:56:14', 'jnt', NULL, 35000, NULL, NULL, NULL, '0812345678', 'tolong packing yg aman', NULL),
(43, 2, 25000, 'dikirim', 'Jl Dago', '2025-12-28 18:13:46', '2025-12-28 18:20:22', 'jnt', NULL, 20000, '119273192371', 'payment_proofs/vPhcNS7oXcVLyn7joCbCtSLi2f0knzKzSd0S3MrN.jpg', NULL, '0812345678', NULL, 'nominal salah'),
(44, 4, 270000, 'menunggu_pembayaran', 'Dago', '2026-01-08 14:57:55', '2026-01-08 14:57:55', 'jnt', NULL, 20000, NULL, NULL, NULL, '0812345678', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kuantitas` int(11) NOT NULL,
  `harga_saat_beli` float NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `kuantitas`, `harga_saat_beli`, `created_at`, `updated_at`) VALUES
(1, 7, 2, 1, 40000, '2025-11-22 10:39:38', '2025-11-22 10:39:38'),
(2, 8, NULL, 1, 150000, '2025-11-22 10:52:38', '2025-11-22 10:52:38'),
(3, 9, NULL, 1, 30000, '2025-11-27 18:01:42', '2025-11-27 18:01:42'),
(4, 10, 5, 1, 175000, '2025-12-14 22:52:24', '2025-12-14 22:52:24'),
(5, 11, 4, 1, 190000, '2025-12-27 10:17:25', '2025-12-27 10:17:25'),
(12, 18, 4, 1, 190000, '2025-12-27 11:54:26', '2025-12-27 11:54:26'),
(33, 43, 11, 1, 5000, '2025-12-28 18:13:46', '2025-12-28 18:13:46'),
(34, 44, 4, 1, 190000, '2026-01-08 14:57:55', '2026-01-08 14:57:55'),
(35, 44, 8, 1, 60000, '2026-01-08 14:57:55', '2026-01-08 14:57:55');

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
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` float NOT NULL,
  `foto_produk` varchar(255) DEFAULT NULL,
  `stok` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `warna` varchar(50) NOT NULL,
  `style` varchar(50) NOT NULL,
  `material` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `nama_produk`, `deskripsi`, `harga`, `foto_produk`, `stok`, `created_at`, `updated_at`, `warna`, `style`, `material`) VALUES
(2, 3, 'Atasan Pink \"Bebe\"', NULL, 40000, 'products/xsshC80Yc7OVE9RfGawJybBtwLwNSMY90OuyZBhT.jpg', 1, '2025-11-22 09:55:34', '2025-11-22 10:39:38', 'pink', 'casual', 'jersey'),
(4, 4, 'Vintage Floral Dress 90s Chic', 'Dress selip (slip dress) cantik dengan spaghetti strap. Motif bunga lembut bernuansa dusty rose dan hijau di atas dasar kain warna krem. Terdapat detail renda marun di bagian dada dan bawah gaun.\r\n\r\nBahan: Sifon tipis berlapis furing (tidak menerawang).\r\nKondisi: 9.5/10 (Excellent Condition)\r\nMinus: TIDAK ADA. Warna masih cerah dan tidak ada noda/lubang.\r\nKebersihan: Sudah dilaundry, wangi, dan siap pakai (Ready to Wear).\r\n\r\nDETAIL UKURAN:\r\nTag Size: L (Fit to M/L)\r\nLingkar Dada (LD): 90 - 95 cm (Dada elastis)\r\nPanjang Baju: 105 cm', 190000, 'products/aPeM8hg8shpcbzbgmNl3mHU0LMLj9dgEEIaDwBsB.jpg', 1, '2025-12-14 09:47:51', '2026-01-08 14:57:55', 'red', 'vintage', 'chiffon'),
(5, 2, 'Vintage Celana Corduroy Coklat Tua', 'Celana panjang berbahan corduroy (kain beludru bergaris) yang tebal dan kokoh. Warna cokelat tua yang sangat cocok untuk fall/winter look. Detail saku tempel di depan dan saku koin kecil. Resleting dan kancing berfungsi normal.\r\n\r\nKondisi: 8.5/10 (Very Good Condition)\r\nMinus: Ada sedikit pudar warna alami di bagian lutut dan ujung kaki (sesuai foto), khas material corduroy vintage.\r\nKebersihan: Sudah dilaundry, wangi, dan siap pakai (Ready to Wear).\r\n\r\nDETAIL UKURAN:\r\nTag Size: 28 (Setara S/M lokal)\r\nLingkar Pinggang (LP): 72 cm\r\nPanjang Celana: 102 cm', 175000, 'products/umsGTo6ifcT0x1e3hAZIDbEzWo0FaQTAOsk10DZq.jpg', 1, '2025-12-14 09:50:06', '2025-12-14 22:52:24', 'coklat', 'vintage', 'corduroy'),
(6, 1, 'Vintage Olive Cable Knit Cardigan', 'Cardigan rajut tebal (Chunky Knit) dengan motif Cable Knit (rajutan kepang) klasik. Warna hijau olive yang mewah dan mudah dipadukan dengan earth tone look. Model kancing penuh di bagian depan, bisa dipakai sebagai outer atau sweater tertutup.\r\n\r\nCocok untuk tampilan yang nyaman, retro, dan aesthetic untuk musim dingin atau ruangan ber-AC.\r\n\r\nKONDISI :\r\nKondisi: 9/10 (Excellent Condition)\r\nMinus: TIDAK ADA. Rajutan masih rapat, tidak ada benang tertarik, dan tidak berbulu (pilling).\r\nKebersihan: Sudah dilaundry, wangi, dan siap pakai (Ready to Wear).\r\n\r\nDETAIL UKURAN:\r\nTag Size: M (Fit Oversize S ke M, atau Fit L biasa)\r\nLingkar Dada (LD): 100 - 105 cm\r\nPanjang Baju: 58 cm\r\nPanjang Lengan: 60 cm', 85000, 'products/Ow1E7bWoZQsypTlmGZaCwO6WB25NshUxeTMRojDZ.jpg', 1, '2025-12-14 22:19:09', '2025-12-28 11:16:23', 'hijau', 'vintage', 'knit'),
(7, 4, 'Babydoll Floral Dress', 'Dress model babydoll yang super cantik dengan motif floral klasik. Dilengkapi detail renda (lace) cokelat di bagian dada dan bawah dress yang memberikan kesan bohemian vintage.\r\n\r\nKondisi: Like New (9.8/10).\r\nMinus: Tidak ada (No defect).\r\nKebersihan: Sudah dicuci bersih, disetrika uap, dan wangi (Ready to wear).\r\nDetail Ukuran (Size M):\r\nLingkar Dada (LD): 88-92 cm\r\nPanjang Baju: 85 cm', 185000, 'products/QbRx6QCtOe9aAerCNgZ9f882EKnyJasDYHFQiijn.jpg', 1, '2025-12-28 13:29:30', '2025-12-28 14:25:45', 'krem', 'vintage', 'lace'),
(8, 3, 'White Coquette Floral Top', 'Blouse vintage yang sangat cantik dengan motif floral pastel yang lembut. Memiliki detail kerah v-neck dengan aksen bunga mawar kecil di tengah, serta tepian renda (scalloped lace) yang sangat detail di bagian bawah dan lengan puff. Bagian dada memiliki detail smocked yang memberikan kesan ramping.\r\n\r\nKondisi: Excellent Condition (9.5/10).\r\nMinus: Tidak ada. Warna masih bersih, renda utuh tidak ada yang lepas.\r\nKebersihan: Sudah di-laundry dan di-steam (Siap pakai).\r\nDetail Ukuran (Size S fit to M):\r\nLingkar Dada (LD): 84 - 90 cm (bahan agak melar di bagian dada).\r\nPanjang Baju: 55 cm.', 90000, 'products/sqKNoslfFytbgxxeaikoUQUmF6k7BJSuzTm5p8mm.jpg', 0, '2025-12-28 13:31:15', '2026-01-08 14:57:55', 'putih', 'coquette', 'lace'),
(9, 3, 'Gingham Coquette Blouse with Lace Detail', 'Blouse bergaya Coquette yang sangat manis dengan motif Gingham cokelat-putih. Detail kerah renda (lace) dan aksen pita cokelat di bagian depan memberikan kesan feminin dan aesthetic. Model lengan puff pendek dengan pinggiran renda yang rapi.\r\n\r\nDetail Item:\r\nKondisi: 9.5/10 (Sangat terawat, kain masih kaku dan warna pekat).\r\nBahan: Katun seersucker premium (dingin dan menyerap keringat).\r\nSize: Fit to L (Lingkar Dada: 95-100 cm, Panjang: 55 cm).\r\nWarna: Brown & White Gingham.', 75000, 'products/FUjL2ILboV8Oc7iGqXhyfepCD6thWEzbKC6AJp7I.jpg', 1, '2025-12-28 15:53:35', '2025-12-28 17:37:24', 'putih', 'coquette', 'lace'),
(10, 2, 'test', NULL, 15000, 'products/sG2LCJJQp0IZsAw7cE3ZQCpaC7umBTLtwC8SnilS.jpg', 1, '2025-12-28 17:20:04', '2025-12-28 17:58:24', '', '', ''),
(11, 2, 'testt', NULL, 5000, 'products/cguPlNMhHDpnghTppYKEp8yEXuUOQL7vBYb4vU8m.jpg', 1, '2025-12-28 18:13:32', '2025-12-28 18:13:46', '', '', '');

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
('airfybRXuOL61lTdp93f1kezEX7l1TNRkPgBRYQN', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiU0NUTGxyNkYyd2RkM2NUZUJMUWx4Y08zZWNMTmN6a3FLWWRlRWZuYyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7fX0=', 1776667424),
('QioTY3hZebK9qRgkM0WhkoicKRuV39RXEB3A4MUe', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiN20wamNFVUgzRHBLQnBEYlFZTjkwYjdEUXcyMktjdEJLVDZMc2tyWiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9maWxlLzQiO319', 1776667418);

-- --------------------------------------------------------

--
-- Table structure for table `trends`
--

CREATE TABLE `trends` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `sumber` varchar(50) DEFAULT NULL,
  `link_sumber` text DEFAULT NULL,
  `style` varchar(100) DEFAULT NULL,
  `warna` varchar(50) DEFAULT NULL,
  `material` varchar(255) DEFAULT NULL,
  `skor_popularitas` int(11) DEFAULT 0,
  `status` enum('Draft','Published') DEFAULT 'Draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trends`
--

INSERT INTO `trends` (`id`, `judul`, `deskripsi`, `gambar`, `sumber`, `link_sumber`, `style`, `warna`, `material`, `skor_popularitas`, `status`, `created_at`, `updated_at`) VALUES
(4, 'KIDS AIRism Katun T-Shirt Grafis Kerah Bulat', 'T‑shirt AIRism yang lembut & ringan, nyaman dipakai anak saat liburan.', 'https://im.uniqlo.com/global-cms/spa/res5c0afaaff7810ec0c7e321dacc6e6c63fr.jpg', 'Uniqlo', 'https://www.uniqlo.com/id/id/kids', 'Streetwear', 'Hijau', 'Cotton', 1, 'Published', '2026-03-27 18:02:50', '2026-04-19 18:44:13'),
(12, 'THE NEW', 'Gulir ke bawah', 'https://static.zara.net/assets/public/2603/70b1/757d4acf9e4f/2a273ecce90a/image-landscape-1-web-fill-18e1a9bb-4b4c-4120-a585-758045ab43a6-default_0/image-landscape-1-web-fill-18e1a9bb-4b4c-4120-a585-758045ab43a6-default_0.jpg?ts=1773859133481&w=1920', 'Zara', NULL, 'Streetwear', 'Hijau', NULL, 0, 'Draft', '2026-03-27 18:13:23', '2026-03-28 16:15:07'),
(31, 'Koleksi Spring/Summer 2026', 'Temukan pilihan pakaian anak yang nyaman untuk mendukung aktivitas sehari-hari.', 'https://im.uniqlo.com/global-cms/spa/res9d23fdace69aa1bb3a9bbd90783c7724fr.jpg', 'Uniqlo', 'https://www.uniqlo.com/id/id/special-feature/cp/monthly-news/kids-and-baby', NULL, NULL, NULL, 0, 'Draft', '2026-04-19 18:26:06', '2026-04-19 18:26:06'),
(32, 'Koleksi Spring/Summer 2026', 'Temukan pakaian bayi yang mudah dikenakan dan nyaman sepanjang hari.', 'https://im.uniqlo.com/global-cms/spa/res024e3d55d02ab3a93bc077c161580b15fr.jpg', 'Uniqlo', 'https://www.uniqlo.com/id/id/special-feature/cp/monthly-news/baby-collection', NULL, NULL, NULL, 0, 'Draft', '2026-04-19 18:26:06', '2026-04-19 18:26:06'),
(39, 'Formal Blazer for The Win', 'Teman-teman Gema Sandang, sekarang blazers are in loh! Find your fashion inspiration from Zara. Eitss inget, kita di Gema Sandang juga adaa loh blazer-blazer keren kaya ginii~~', 'https://static.zara.net/assets/public/2603/70b1/757d4acf9e4f/2a273ecce90a/image-landscape-1-web-fill-18e1a9bb-4b4c-4120-a585-758045ab43a6-default_0/image-landscape-1-web-fill-18e1a9bb-4b4c-4120-a585-758045ab43a6-default_0.jpg?ts=1773859133481&w=1920', 'Zara', 'https://www.zara.com/id/', 'Formal', 'Hitam', 'Chiffon', 0, 'Published', '2026-04-19 18:57:33', '2026-04-19 19:01:21');

-- --------------------------------------------------------

--
-- Table structure for table `trend_interactions`
--

CREATE TABLE `trend_interactions` (
  `id` int(11) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `trend_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trend_interactions`
--

INSERT INTO `trend_interactions` (`id`, `user_id`, `trend_id`, `created_at`) VALUES
(12, 4, 2, '2026-04-19 16:33:48'),
(20, 6, 1, '2026-04-19 18:03:33'),
(21, 7, 4, '2026-04-19 18:44:13');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `alamat` text DEFAULT NULL,
  `nomor_hp` varchar(255) DEFAULT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `bio`, `email`, `email_verified_at`, `password`, `alamat`, `nomor_hp`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Jessica Anne', NULL, NULL, 'jesshar2108@gmail.com', NULL, '$2y$12$BUdJ3qPPaMdATQcJw2R97ORyQO2dphz/pmGCpFXhJMnw/Octe69o6', 'Jl Pakar Timur', '082121349200', 'customer', NULL, '2025-10-16 01:45:14', '2026-01-01 12:35:04'),
(2, 'Jonathan', NULL, NULL, 'jonathan@gmail.com', NULL, '$2y$12$gDcEJGQLWVfv/wCcuksSGuY1uQMmRdtRWD7f7fG7RTdJKjbmEnwsO', 'Jl Dago', '0812345678', 'customer', NULL, '2025-10-16 17:49:01', '2025-12-28 09:47:06'),
(3, 'Admin', NULL, NULL, 'admin1@gmail.com', NULL, '$2y$12$AzvKjT2F8gUIKlVTISXUlOl00rAHOq14ov3UWOrKkeKjYdxmyfvIK', NULL, NULL, 'admin', NULL, '2025-10-21 02:31:01', '2025-10-21 02:31:01'),
(4, 'Anne', 'anne21', NULL, 'anne@gmail.com', NULL, '$2y$12$2fot7KE8dMna1o7nPkup6.bsTliSyXjQRpFHTZooUEkI0U1aLvIXK', 'Dago', '0812345678', 'customer', NULL, '2025-12-14 22:51:54', '2025-12-28 11:16:23'),
(5, 'Darmawan', 'darmawan19', 'I love fashion', 'darmawan@gmail.com', NULL, '$2y$12$vNw7DrkgwCPaQOPzZmAfoux1ELPNH6nz2wgcGeHLcXetOYZKWp/Z2', NULL, NULL, 'customer', NULL, '2026-04-18 12:13:41', '2026-04-19 09:26:07'),
(6, 'Ida', 'ida24', NULL, 'ida@gmail.com', NULL, '$2y$12$UhtwJ032LD3P0oTd.bUwEOm/eVYQXHWJaXaCjp9YJspqb5/1VUDwi', NULL, NULL, 'customer', NULL, '2026-04-19 16:55:01', '2026-04-19 16:55:01'),
(7, 'Ujang', 'ujangiskandar', NULL, 'ujang@gmail.com', NULL, '$2y$12$Qq8gK7pVdDdejz7RXEGpXua72QG9pQVz5Zz2NabnzaC1TTf6oakM2', NULL, NULL, 'customer', NULL, '2026-04-19 18:17:06', '2026-04-19 18:17:06'),
(8, 'asep', 'asep', NULL, 'asep@gmail.com', NULL, '$2y$12$/g3ORgCUU1LhgzRFhJq7xeZ8MmMn9apJbBxxH5Du3lKxNbXyBL7W.', NULL, NULL, 'customer', NULL, '2026-04-20 06:01:52', '2026-04-20 06:01:52');

-- --------------------------------------------------------

--
-- Table structure for table `user_verifications`
--

CREATE TABLE `user_verifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nik` varchar(16) NOT NULL,
  `ktp_path` varchar(255) NOT NULL,
  `status` enum('pending','verified','rejected') NOT NULL DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_verifications`
--

INSERT INTO `user_verifications` (`id`, `user_id`, `nik`, `ktp_path`, `status`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(1, 5, '1234567890123451', 'id_cards/yceuRtWyfYYBs8znOclKNUAYp6f3uqqyNlhRfWBf.jpg', 'verified', NULL, '2026-04-18 12:13:42', '2026-04-19 16:43:01'),
(2, 6, '1234567890123423', 'id_cards/JGQp5IEw7ibjEp47CzQLVF59jz2BzLehrh2KiRVp.png', 'verified', NULL, '2026-04-19 16:56:05', '2026-04-19 16:56:52'),
(3, 7, '1234567890123421', 'id_cards/NiOyIInLCCBAJP0Q9cH6ONAXa3QY1GE1mJZ2C5fq.png', 'verified', NULL, '2026-04-19 18:17:42', '2026-04-19 18:32:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bargains`
--
ALTER TABLE `bargains`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bargains_user_id_foreign` (`user_id`),
  ADD KEY `bargains_product_id_foreign` (`product_id`);

--
-- Indexes for table `barter_items`
--
ALTER TABLE `barter_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cart_user` (`user_id`),
  ADD KEY `fk_cart_product` (`product_id`),
  ADD KEY `fk_cart_bargain` (`bargain_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_comment_user` (`user_id`),
  ADD KEY `fk_comment_trend` (`trend_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `messages`
--
ALTER TABLE `messages`
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
  ADD KEY `orders_user_id_foreign` (`user_id`);

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
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `trends`
--
ALTER TABLE `trends`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trend_interactions`
--
ALTER TABLE `trend_interactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_verifications`
--
ALTER TABLE `user_verifications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_verifications_nik_unique` (`nik`),
  ADD KEY `user_verifications_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bargains`
--
ALTER TABLE `bargains`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `barter_items`
--
ALTER TABLE `barter_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `trends`
--
ALTER TABLE `trends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `trend_interactions`
--
ALTER TABLE `trend_interactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_verifications`
--
ALTER TABLE `user_verifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bargains`
--
ALTER TABLE `bargains`
  ADD CONSTRAINT `bargains_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bargains_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `barter_items`
--
ALTER TABLE `barter_items`
  ADD CONSTRAINT `barter_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `fk_cart_bargain` FOREIGN KEY (`bargain_id`) REFERENCES `bargains` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comment_trend` FOREIGN KEY (`trend_id`) REFERENCES `trends` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comment_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_verifications`
--
ALTER TABLE `user_verifications`
  ADD CONSTRAINT `user_verifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
