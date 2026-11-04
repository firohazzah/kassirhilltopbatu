-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for kasirkafe
CREATE DATABASE IF NOT EXISTS `kasirkafe` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `kasirkafe`;

-- Dumping structure for table kasirkafe.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kasirkafe.categories: ~3 rows (approximately)
INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
	(1, 'Kopi', '2026-11-01 19:33:58', '2026-11-01 19:33:58'),
	(2, 'Non-Kopi', '2026-11-01 19:33:58', '2026-11-01 19:33:58'),
	(3, 'Makanan', '2026-11-01 19:33:58', '2026-11-01 19:33:58');

-- Dumping structure for table kasirkafe.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kasirkafe.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table kasirkafe.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kasirkafe.migrations: ~10 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_resets_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(5, '2026_11_02_000001_create_categories_table', 1),
	(6, '2026_11_02_000002_create_products_table', 1),
	(7, '2026_11_02_000003_create_transactions_table', 1),
	(8, '2026_11_02_000004_create_transaction_items_table', 1),
	(9, '2026_11_02_032434_add_image_to_products_table', 2),
	(10, '2026_11_03_015805_add_role_to_users_table', 3);

-- Dumping structure for table kasirkafe.password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kasirkafe.password_resets: ~0 rows (approximately)

-- Dumping structure for table kasirkafe.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kasirkafe.personal_access_tokens: ~0 rows (approximately)

-- Dumping structure for table kasirkafe.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` bigint unsigned NOT NULL,
  `stock` int unsigned NOT NULL DEFAULT '0',
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_is_available_name_index` (`is_available`,`name`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kasirkafe.products: ~8 rows (approximately)
INSERT INTO `products` (`id`, `category_id`, `name`, `image`, `price`, `stock`, `is_available`, `created_at`, `updated_at`) VALUES
	(1, 2, 'Americano', 'products/8R0WrIpsSvbpFz0kugqkP4My1UWy8FLkOuAkzvxb.png', 18000, 28, 1, '2026-11-01 19:33:58', '2026-11-02 19:07:04'),
	(2, 1, 'Cappuccino', 'products/QuC192K9N9VoAaoz2Qoz525DGyvQaEFtKgOtHK3I.png', 25000, 21, 1, '2026-11-01 19:33:58', '2026-11-03 18:58:18'),
	(3, 1, 'Cafe Latte', 'products/wmrJnZUb2yzUjkYv7uqRi7XscID4PV9XguqvPlJ8.png', 26000, 21, 1, '2026-11-01 19:33:58', '2026-11-02 19:42:29'),
	(4, 2, 'Matcha Latte', 'products/mAuK3XQI6aqz2QsFoe2omtW4QiqUfctVcOPApRD4.png', 27000, 19, 1, '2026-11-01 19:33:58', '2026-11-02 20:58:53'),
	(5, 2, 'Ice Chocolate', 'products/Kwxfp45KIUSpiGflKHhQY4ni45FQsnOacAcelvXL.png', 23000, 18, 1, '2026-11-01 19:33:58', '2026-11-03 18:58:18'),
	(6, 3, 'French Fries', 'products/rU6YgZE2ypOFFa6iZaV0T12vBWwNdrnNovQUCzlM.png', 20000, 14, 1, '2026-11-01 19:33:58', '2026-11-03 18:58:18'),
	(7, 3, 'Croissant', 'products/t1z2DrSr7YfPKMSf85jhQPeBWzSeR4bL9IHOZxRb.png', 22000, 9, 1, '2026-11-01 19:33:58', '2026-11-03 18:58:18'),
	(10, 2, 'matcha cloud', 'products/83fDe6s8Hiv0h7c4TNQF0Vpyo1pdrdUrbKR63lVT.png', 15000, 10, 1, '2026-11-01 20:47:42', '2026-11-01 21:47:44');

-- Dumping structure for table kasirkafe.transactions
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` bigint unsigned NOT NULL,
  `discount` bigint unsigned NOT NULL DEFAULT '0',
  `total` bigint unsigned NOT NULL,
  `payment_method` enum('cash','qris','transfer') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount_paid` bigint unsigned NOT NULL DEFAULT '0',
  `change_amount` bigint unsigned NOT NULL DEFAULT '0',
  `status` enum('paid','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'paid',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transactions_invoice_number_unique` (`invoice_number`),
  KEY `transactions_status_created_at_index` (`status`,`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kasirkafe.transactions: ~6 rows (approximately)
INSERT INTO `transactions` (`id`, `invoice_number`, `customer_name`, `subtotal`, `discount`, `total`, `payment_method`, `amount_paid`, `change_amount`, `status`, `notes`, `created_at`, `updated_at`) VALUES
	(1, 'INV-20261102025455-N8HA', NULL, 73000, 0, 73000, 'cash', 100000, 27000, 'paid', NULL, '2026-11-01 19:54:55', '2026-11-01 19:54:55'),
	(2, 'INV-20261102045157-3CMN', 'ais', 44000, 0, 44000, 'cash', 299995, 255995, 'paid', 'yes', '2026-11-01 21:51:57', '2026-11-01 21:51:57'),
	(3, 'INV-20261103020704-WBCD', 'ani', 43000, 0, 43000, 'cash', 300000, 257000, 'paid', NULL, '2026-11-02 19:07:04', '2026-11-02 19:07:04'),
	(4, 'INV-20261103024229-IC8N', NULL, 52000, 0, 52000, 'cash', 99987, 47987, 'paid', NULL, '2026-11-02 19:42:29', '2026-11-02 19:42:29'),
	(5, 'INV-20261103035853-CQQZ', 'eeer', 72000, 0, 72000, 'cash', 120000, 48000, 'paid', NULL, '2026-11-02 20:58:53', '2026-11-02 20:58:53'),
	(6, 'INV-20261104015818-UK8W', 'ena', 115000, 0, 115000, 'cash', 200022, 85022, 'paid', NULL, '2026-11-03 18:58:18', '2026-11-03 18:58:18');

-- Dumping structure for table kasirkafe.transaction_items
CREATE TABLE IF NOT EXISTS `transaction_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int unsigned NOT NULL,
  `price` bigint unsigned NOT NULL,
  `line_total` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_items_transaction_id_foreign` (`transaction_id`),
  KEY `transaction_items_product_id_foreign` (`product_id`),
  KEY `transaction_items_product_name_index` (`product_name`),
  CONSTRAINT `transaction_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transaction_items_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kasirkafe.transaction_items: ~15 rows (approximately)
INSERT INTO `transaction_items` (`id`, `transaction_id`, `product_id`, `product_name`, `quantity`, `price`, `line_total`, `created_at`, `updated_at`) VALUES
	(1, 1, 3, 'Cafe Latte', 1, 26000, 26000, '2026-11-01 19:54:55', '2026-11-01 19:54:55'),
	(2, 1, 2, 'Cappuccino', 1, 25000, 25000, '2026-11-01 19:54:55', '2026-11-01 19:54:55'),
	(3, 1, 7, 'Croissant', 1, 22000, 22000, '2026-11-01 19:54:55', '2026-11-01 19:54:55'),
	(4, 2, 3, 'Cafe Latte', 1, 26000, 26000, '2026-11-01 21:51:57', '2026-11-01 21:51:57'),
	(5, 2, 1, 'Americano', 1, 18000, 18000, '2026-11-01 21:51:57', '2026-11-01 21:51:57'),
	(6, 3, 2, 'Cappuccino', 1, 25000, 25000, '2026-11-02 19:07:04', '2026-11-02 19:07:04'),
	(7, 3, 1, 'Americano', 1, 18000, 18000, '2026-11-02 19:07:04', '2026-11-02 19:07:04'),
	(8, 4, 3, 'Cafe Latte', 2, 26000, 52000, '2026-11-02 19:42:29', '2026-11-02 19:42:29'),
	(9, 5, 7, 'Croissant', 1, 22000, 22000, '2026-11-02 20:58:53', '2026-11-02 20:58:53'),
	(10, 5, 5, 'Ice Chocolate', 1, 23000, 23000, '2026-11-02 20:58:53', '2026-11-02 20:58:53'),
	(11, 5, 4, 'Matcha Latte', 1, 27000, 27000, '2026-11-02 20:58:53', '2026-11-02 20:58:53'),
	(12, 6, 5, 'Ice Chocolate', 1, 23000, 23000, '2026-11-03 18:58:18', '2026-11-03 18:58:18'),
	(13, 6, 2, 'Cappuccino', 2, 25000, 50000, '2026-11-03 18:58:18', '2026-11-03 18:58:18'),
	(14, 6, 7, 'Croissant', 1, 22000, 22000, '2026-11-03 18:58:18', '2026-11-03 18:58:18'),
	(15, 6, 6, 'French Fries', 1, 20000, 20000, '2026-11-03 18:58:18', '2026-11-03 18:58:18');

-- Dumping structure for table kasirkafe.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table kasirkafe.users: ~2 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'admin', 'admin@kafe.com', NULL, '$2y$10$VEZ1hKCVOqjNIqVrDbgUuuh6KEpBm.uPANXRy9mkwDjHVxYGYlYM2', 'admin', NULL, '2026-11-02 19:37:31', '2026-11-02 19:37:31'),
	(2, 'kasir', 'kasir@kafe.com', NULL, '$2y$10$fg0OM47Hosv96.3hjSwTguobzzNkur0oAWq5ZjYGqqke4gL/58LSS', 'user', NULL, '2026-11-02 19:38:23', '2026-11-02 19:38:23');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
