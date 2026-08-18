SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;

-- Dumping structure for table nongsanvietnam.banners
CREATE TABLE IF NOT EXISTS `banners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `image_id` bigint unsigned NOT NULL,
  `eyebrow` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `button_label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text_color` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dark',
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `banners_image_id_foreign` (`image_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table nongsanvietnam.banners: ~2 rows (approximately)
INSERT INTO `banners` (`id`, `image_id`, `eyebrow`, `title`, `subtitle`, `description`, `button_label`, `button_url`, `text_color`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 15, 'Nhãn nhỏ', 'Tiêu đề chính', 'Tiêu đề phụ', 'Mô tả Mô tả', 'Khám phá ngay', 'http://127.0.0.1:8000/san-pham', 'dark', 1, 1, '2026-08-13 11:17:26', '2026-08-13 11:20:40'),
	(2, 15, 'Tinh hoa', 'Nông sản Việt', 'Sạch - Ngon - Chất Lượng', 'Đều là sản phẩm có những chất lượng tốt nhất Việt Nam\r\nĂn vào là béo béo khỏe béo đẹp', 'Tìm hiểu thêm', 'http://127.0.0.1:8000/san-pham', 'light', 0, 1, '2026-08-13 11:27:29', '2026-08-13 11:27:29');

-- Dumping structure for table nongsanvietnam.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table nongsanvietnam.cache: ~0 rows (approximately)

-- Dumping structure for table nongsanvietnam.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table nongsanvietnam.cache_locks: ~0 rows (approximately)

-- Dumping structure for table nongsanvietnam.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint unsigned DEFAULT NULL,
  `image_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_parent_id_foreign` (`parent_id`),
  KEY `categories_image_id_foreign` (`image_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table nongsanvietnam.categories: ~4 rows (approximately)
INSERT INTO `categories` (`id`, `parent_id`, `image_id`, `name`, `slug`, `description`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
	(7, NULL, 13, 'Phở khô, Bún, Miến, Mì', 'pho-kho-bun-mien-mi', 'Các loại phở khô, bún, miến và mì truyền thống Việt Nam.', 1, 2, '2026-08-11 01:24:00', '2026-08-13 09:41:06'),
	(8, NULL, NULL, 'Chưa phân loại', 'chua-phan-loai', 'Danh mục mặc định dành cho các sản phẩm chưa được phân loại.', 0, 9999, '2026-08-13 09:38:21', '2026-08-13 09:50:29'),
	(9, NULL, 12, 'Nông sản', 'nong-san', NULL, 1, 1, '2026-08-13 09:39:56', '2026-08-13 09:39:56'),
	(10, NULL, 14, 'Gia vị', 'gia-vi', NULL, 1, 3, '2026-08-13 09:46:15', '2026-08-13 09:46:15');

-- Dumping structure for table nongsanvietnam.failed_jobs
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

-- Dumping data for table nongsanvietnam.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table nongsanvietnam.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table nongsanvietnam.jobs: ~0 rows (approximately)

-- Dumping structure for table nongsanvietnam.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table nongsanvietnam.job_batches: ~0 rows (approximately)

-- Dumping structure for table nongsanvietnam.media
CREATE TABLE IF NOT EXISTS `media` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public',
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint unsigned NOT NULL DEFAULT '0',
  `alt_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_path_unique` (`path`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table nongsanvietnam.media: ~16 rows (approximately)
INSERT INTO `media` (`id`, `name`, `file_name`, `path`, `disk`, `mime_type`, `size`, `alt_text`, `created_at`, `updated_at`) VALUES
	(1, 'Trái cây Việt', 'trai-cay.svg', 'images/products/trai-cay.svg', 'asset', 'image/svg+xml', 0, 'Trái cây Việt', '2026-08-11 01:03:31', '2026-08-11 01:03:31'),
	(2, 'Rau củ tươi', 'rau-cu.svg', 'images/products/rau-cu.svg', 'asset', 'image/svg+xml', 0, 'Rau củ tươi', '2026-08-11 01:03:31', '2026-08-11 01:03:31'),
	(3, 'Gạo & ngũ cốc', 'gao.svg', 'images/products/gao.svg', 'asset', 'image/svg+xml', 0, 'Gạo & ngũ cốc', '2026-08-11 01:03:31', '2026-08-11 01:03:31'),
	(4, 'Đặc sản vùng miền', 'dac-san.svg', 'images/products/dac-san.svg', 'asset', 'image/svg+xml', 0, 'Đặc sản vùng miền', '2026-08-11 01:03:31', '2026-08-11 01:03:31'),
	(5, 'Các loại hạt', 'hat.svg', 'images/products/hat.svg', 'asset', 'image/svg+xml', 0, 'Các loại hạt', '2026-08-11 01:03:31', '2026-08-11 01:03:31'),
	(6, 'Gia vị Việt', 'gia-vi.svg', 'images/products/gia-vi.svg', 'asset', 'image/svg+xml', 0, 'Gia vị Việt', '2026-08-11 01:03:32', '2026-08-11 01:03:32'),
	(7, '1785299981522_201612095649054501_5943775442030567317_f5e229813d94228b61435ffcba5ec24e', '1785299981522_201612095649054501_5943775442030567317_f5e229813d94228b61435ffcba5ec24e.jpg', 'media/2026/08/f18cfd66-4d9a-4681-b9db-a307a8836b75.jpg', 'public', 'image/jpeg', 50195, 'Hình ảnh 1785299981522 201612095649054501 5943775442030567317 f5e229813d94228b61435ffcba5ec24e – Nông sản Việt Nam', '2026-08-11 01:58:26', '2026-08-11 02:31:55'),
	(8, '1785297447482_201612095649054501_5943775442030567317_8c5cb471296e11cf200917b51f9ca3b7', '1785297447482_201612095649054501_5943775442030567317_8c5cb471296e11cf200917b51f9ca3b7.jpg', 'media/2026/08/8e0b3f6b-830d-4dad-b9f0-2891b23a6d59.jpg', 'public', 'image/jpeg', 38231, 'Hình ảnh 1785297447482 201612095649054501 5943775442030567317 8c5cb471296e11cf200917b51f9ca3b7 – Nông sản Việt Nam', '2026-08-11 02:22:28', '2026-08-11 02:31:55'),
	(9, 'lolo-pharma', 'lolo-pharma.jpg', 'media/2026/08/a94fd938-69e9-48b1-831d-80b162a840a0.jpg', 'public', 'image/jpeg', 40144, 'Hình ảnh lolo pharma – Nông sản Việt Nam', '2026-08-11 06:44:43', '2026-08-11 06:44:43'),
	(10, 'lolo-pharma', 'lolo-pharma.jpg', 'media/2026/08/6846984e-fc02-4dae-9dae-4accb6f9f5ef.jpg', 'public', 'image/jpeg', 40144, 'Hình ảnh lolo pharma – Nông sản Việt Nam', '2026-08-11 06:45:41', '2026-08-11 06:45:41'),
	(11, 'lolo-pharma', 'lolo-pharma.jpg', 'media/2026/08/0ed2e2ea-21dc-43a8-b1f7-ce70d8575a1e.jpg', 'public', 'image/jpeg', 40144, 'Hình ảnh lolo pharma – Nông sản Việt Nam', '2026-08-11 06:47:45', '2026-08-11 06:47:45'),
	(12, 'lolo-pharma', 'lolo-pharma.jpg', 'media/2026/08/f979669c-d142-42a2-a59b-fe14bece0d21.jpg', 'public', 'image/jpeg', 40144, 'Hình ảnh lolo pharma – Nông sản Việt Nam', '2026-08-13 09:39:53', '2026-08-13 09:39:53'),
	(13, '771892059_1403569928541708_7003126661777667156_n', '771892059_1403569928541708_7003126661777667156_n.jpg', 'media/2026/08/654c0823-163b-44ae-8cb1-269e5fac8431.jpg', 'public', 'image/jpeg', 242642, 'Hình ảnh 771892059 1403569928541708 7003126661777667156 n – Nông sản Việt Nam', '2026-08-13 09:40:58', '2026-08-13 09:40:58'),
	(14, '423454356_1201748227460366_3965427583197702138_n', '423454356_1201748227460366_3965427583197702138_n.jpg', 'media/2026/08/6b7816d1-c70e-40c1-8605-b36a6be4d320.jpg', 'public', 'image/jpeg', 272185, 'Hình ảnh 423454356 1201748227460366 3965427583197702138 n – Nông sản Việt Nam', '2026-08-13 09:46:13', '2026-08-13 09:46:13'),
	(15, 'dd22b8e5-b940-45e8-a3cb-7710fbfb1bcf', 'dd22b8e5-b940-45e8-a3cb-7710fbfb1bcf.png', 'media/2026/08/55632f63-7abf-48e1-aa4d-e669a7d32938.png', 'public', 'image/png', 1952941, 'Hình ảnh dd22b8e5 b940 45e8 a3cb 7710fbfb1bcf – Nông sản Việt Nam', '2026-08-13 11:19:48', '2026-08-13 11:19:48'),
	(16, 'pexels-surya-travel-2068908189-37175473', 'pexels-surya-travel-2068908189-37175473.jpg', 'media/2026/08/8117d969-4ba7-4b93-9f1b-d76b7d5a35b4.jpg', 'public', 'image/jpeg', 1570876, 'Hình ảnh pexels surya travel 2068908189 37175473 – Nông sản Việt Nam', '2026-08-13 11:44:08', '2026-08-13 11:44:08');

-- Dumping structure for table nongsanvietnam.mediables
CREATE TABLE IF NOT EXISTS `mediables` (
  `media_id` bigint unsigned NOT NULL,
  `mediable_id` bigint unsigned NOT NULL,
  `mediable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`media_id`,`mediable_id`,`mediable_type`),
  KEY `mediables_mediable_id_mediable_type_index` (`mediable_id`,`mediable_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table nongsanvietnam.mediables: ~10 rows (approximately)
INSERT INTO `mediables` (`media_id`, `mediable_id`, `mediable_type`, `sort_order`, `is_primary`) VALUES
	(1, 2, 'App\\Models\\Product', 0, 1),
	(2, 3, 'App\\Models\\Product', 0, 1),
	(2, 4, 'App\\Models\\Product', 0, 1),
	(3, 5, 'App\\Models\\Product', 0, 1),
	(3, 6, 'App\\Models\\Product', 0, 1),
	(4, 7, 'App\\Models\\Product', 0, 1),
	(4, 10, 'App\\Models\\Product', 0, 1),
	(5, 8, 'App\\Models\\Product', 0, 1),
	(6, 9, 'App\\Models\\Product', 0, 1),
	(7, 1, 'App\\Models\\Product', 0, 0),
	(16, 1, 'App\\Models\\Product', 1, 1);

-- Dumping structure for table nongsanvietnam.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table nongsanvietnam.migrations: ~7 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2026_08_11_000001_create_catalog_tables', 1),
	(5, '2026_08_11_000002_add_dried_noodle_category', 2),
	(6, '2026_08_11_000003_create_posts_table', 3),
	(7, '2026_08_11_000004_fill_missing_media_alt_text', 4),
	(8, '2026_08_11_000005_create_settings_table', 5),
	(9, '2026_08_13_000003_add_original_price_to_products_table', 6),
	(10, '2026_08_14_000004_create_orders_tables', 7),
	(11, '2026_08_14_000005_create_banners_table', 8);

-- Dumping structure for table nongsanvietnam.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_order_code` bigint unsigned DEFAULT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `subtotal` decimal(14,0) NOT NULL,
  `shipping_fee` decimal(14,0) NOT NULL DEFAULT '0',
  `total` decimal(14,0) NOT NULL,
  `payment_method` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payos_payment_link_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payos_checkout_url` text COLLATE utf8mb4_unicode_ci,
  `payos_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_code_unique` (`code`),
  UNIQUE KEY `orders_payment_order_code_unique` (`payment_order_code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table nongsanvietnam.orders: ~1 rows (approximately)
INSERT INTO `orders` (`id`, `code`, `payment_order_code`, `customer_name`, `phone`, `email`, `address`, `note`, `subtotal`, `shipping_fee`, `total`, `payment_method`, `payment_status`, `status`, `payos_payment_link_id`, `payos_checkout_url`, `payos_reference`, `paid_at`, `created_at`, `updated_at`) VALUES
	(1, 'TD-260813-A52092', 1786642787001, 'Đặng Văn Dũng', '0336961703', NULL, '22 thành công ba đình hà nội', NULL, 85000, 0, 85000, 'cod', 'unpaid', 'confirmed', NULL, NULL, NULL, NULL, '2026-08-13 10:39:47', '2026-08-13 10:39:47');

-- Dumping structure for table nongsanvietnam.order_items
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_price` decimal(14,0) NOT NULL,
  `quantity` int unsigned NOT NULL,
  `line_total` decimal(14,0) NOT NULL,
  `unit` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table nongsanvietnam.order_items: ~0 rows (approximately)
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `sku`, `unit_price`, `quantity`, `line_total`, `unit`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 'Bưởi da xanh Bến Tre', 'NSV-BUOI-01', 85000, 1, 85000, 'quả', '2026-08-13 10:39:47', '2026-08-13 10:39:47');

-- Dumping structure for table nongsanvietnam.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table nongsanvietnam.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table nongsanvietnam.posts
CREATE TABLE IF NOT EXISTS `posts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `featured_image_id` bigint unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `posts_slug_unique` (`slug`),
  KEY `posts_user_id_foreign` (`user_id`),
  KEY `posts_featured_image_id_foreign` (`featured_image_id`),
  KEY `posts_status_index` (`status`),
  KEY `posts_published_at_index` (`published_at`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table nongsanvietnam.posts: ~3 rows (approximately)
INSERT INTO `posts` (`id`, `user_id`, `featured_image_id`, `title`, `slug`, `excerpt`, `content`, `status`, `published_at`, `created_at`, `updated_at`) VALUES
	(1, 1, 7, 'Hành trình đưa nông sản Việt từ ruộng vườn đến bàn ăn', 'hanh-trinh-nong-san-viet', 'Cùng gặp gỡ những người nông dân đang gìn giữ phương thức canh tác tử tế và tạo ra sản phẩm chất lượng.', '<h2>Từ vùng nguyên liệu Việt Nam</h2><p>Cùng gặp gỡ những người nông dân đang gìn giữ phương thức canh tác tử tế và tạo ra sản phẩm chất lượng.</p><p>Thành Đạt đồng hành cùng các nhà vườn và cơ sở sản xuất uy tín để lựa chọn nguồn nguyên liệu có xuất xứ rõ ràng, quy trình chăm sóc an toàn và chất lượng ổn định.</p><blockquote><p>Mỗi sản phẩm không chỉ mang hương vị quê hương mà còn chứa đựng tâm huyết của người làm nông.</p></blockquote><h2>Giá trị của sự tử tế</h2><ul><li data-list-item-id="ef7dc47c4c70eedc939ec821307b5e15c">Nguồn gốc minh bạch và được tuyển chọn kỹ lưỡng.</li><li data-list-item-id="e945b7b932c355eb37fa40a1f793d23e3">Đóng gói cẩn thận, giữ trọn độ tươi ngon.</li><li data-list-item-id="ecbb4617a08acee00a7c2f52f28082812">Đồng hành lâu dài và thu mua công bằng với nhà nông.</li></ul><p>Chúng tôi tin rằng khi người tiêu dùng lựa chọn nông sản Việt chất lượng, đó cũng là cách góp phần xây dựng một nền nông nghiệp bền vững hơn.</p><figure class="image"><img style="aspect-ratio:440/198;" src="http://127.0.0.1:8000/storage/media/2026/08/8e0b3f6b-830d-4dad-b9f0-2891b23a6d59.jpg" width="440" height="198"></figure>', 'published', '2026-08-11 02:07:00', '2026-08-11 02:07:24', '2026-08-11 02:22:39'),
	(2, 1, 3, 'Cách chọn gạo ngon cho từng món ăn Việt', 'cach-chon-gao-ngon', 'Mỗi giống gạo mang một độ dẻo, hương thơm riêng và phù hợp với những món ăn khác nhau.', '<h2>Từ vùng nguyên liệu Việt Nam</h2><p>Mỗi giống gạo mang một độ dẻo, hương thơm riêng và phù hợp với những món ăn khác nhau.</p><p>Thành Đạt đồng hành cùng các nhà vườn và cơ sở sản xuất uy tín để lựa chọn nguồn nguyên liệu có xuất xứ rõ ràng, quy trình chăm sóc an toàn và chất lượng ổn định.</p><blockquote><p>Mỗi sản phẩm không chỉ mang hương vị quê hương mà còn chứa đựng tâm huyết của người làm nông.</p></blockquote><h2>Giá trị của sự tử tế</h2><ul><li>Nguồn gốc minh bạch và được tuyển chọn kỹ lưỡng.</li><li>Đóng gói cẩn thận, giữ trọn độ tươi ngon.</li><li>Đồng hành lâu dài và thu mua công bằng với nhà nông.</li></ul><p>Chúng tôi tin rằng khi người tiêu dùng lựa chọn nông sản Việt chất lượng, đó cũng là cách góp phần xây dựng một nền nông nghiệp bền vững hơn.</p>', 'published', '2026-08-08 02:07:24', '2026-08-11 02:07:24', '2026-08-11 02:07:24'),
	(3, 1, 1, 'Lịch nông sản theo mùa bạn nên biết', 'lich-nong-san-theo-mua', 'Chọn đúng mùa để thưởng thức rau quả tươi ngon nhất, đồng thời ủng hộ nền nông nghiệp bền vững.', '<h2>Từ vùng nguyên liệu Việt Nam</h2><p>Chọn đúng mùa để thưởng thức rau quả tươi ngon nhất, đồng thời ủng hộ nền nông nghiệp bền vững.</p><p>Thành Đạt đồng hành cùng các nhà vườn và cơ sở sản xuất uy tín để lựa chọn nguồn nguyên liệu có xuất xứ rõ ràng, quy trình chăm sóc an toàn và chất lượng ổn định.</p><blockquote><p>Mỗi sản phẩm không chỉ mang hương vị quê hương mà còn chứa đựng tâm huyết của người làm nông.</p></blockquote><h2>Giá trị của sự tử tế</h2><ul><li>Nguồn gốc minh bạch và được tuyển chọn kỹ lưỡng.</li><li>Đóng gói cẩn thận, giữ trọn độ tươi ngon.</li><li>Đồng hành lâu dài và thu mua công bằng với nhà nông.</li></ul><p>Chúng tôi tin rằng khi người tiêu dùng lựa chọn nông sản Việt chất lượng, đó cũng là cách góp phần xây dựng một nền nông nghiệp bền vững hơn.</p>', 'published', '2026-08-05 02:07:24', '2026-08-11 02:07:24', '2026-08-11 02:07:24'),
	(4, 1, 8, 'xin chao', 'xin-chao', '1213', '<p>1212</p><figure class="image"><img style="aspect-ratio:553/552;" alt="Hình ảnh nông sản Việt Nam" src="http://127.0.0.1:8000/storage/media/2026/08/0ed2e2ea-21dc-43a8-b1f7-ce70d8575a1e.jpg" width="553" height="552"></figure>', 'published', '2026-08-05 13:47:00', '2026-08-11 06:47:57', '2026-08-11 06:47:57');

-- Dumping structure for table nongsanvietnam.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` text COLLATE utf8mb4_unicode_ci,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `price` decimal(14,0) NOT NULL,
  `original_price` decimal(14,0) DEFAULT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kg',
  `origin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock` int unsigned NOT NULL DEFAULT '0',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  KEY `products_category_id_foreign` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table nongsanvietnam.products: ~10 rows (approximately)
INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `sku`, `short_description`, `description`, `price`, `original_price`, `unit`, `origin`, `stock`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 7, 'Bưởi da xanh Bến Tre Siêu Ngon', 'buoi-da-xanh-ben-tre', 'NSV-BUOI-01', 'Bưởi ruột hồng, tép mọng nước, vị ngọt thanh và rất ít hạt.', 'Bưởi ruột hồng, tép mọng nước, vị ngọt thanh và rất ít hạt.\r\n\r\nSản phẩm được tuyển chọn tại vùng nguyên liệu, kiểm tra chất lượng và đóng gói cẩn thận trước khi giao đến khách hàng.', 85000, 300000, 'quả', 'Bến Tre', 32, 1, 1, '2026-08-11 01:03:32', '2026-08-13 11:44:44'),
	(2, 8, 'Cam sành Hà Giang', 'cam-sanh-ha-giang', 'NSV-CAM-01', 'Cam chín tự nhiên, mọng nước, vị chua ngọt hài hòa.', 'Cam chín tự nhiên, mọng nước, vị chua ngọt hài hòa.\n\nSản phẩm được tuyển chọn tại vùng nguyên liệu, kiểm tra chất lượng và đóng gói cẩn thận trước khi giao đến khách hàng.', 49000, NULL, 'kg', 'Hà Giang', 45, 1, 1, '2026-08-11 01:03:32', '2026-08-13 09:38:39'),
	(3, 8, 'Rau cải ngọt Đà Lạt', 'rau-cai-ngot-da-lat', 'NSV-RAU-01', 'Rau non tươi giòn, canh tác theo tiêu chuẩn an toàn.', 'Rau non tươi giòn, canh tác theo tiêu chuẩn an toàn.\n\nSản phẩm được tuyển chọn tại vùng nguyên liệu, kiểm tra chất lượng và đóng gói cẩn thận trước khi giao đến khách hàng.', 28000, NULL, 'bó', 'Đà Lạt', 18, 1, 1, '2026-08-11 01:03:32', '2026-08-13 09:38:36'),
	(4, 8, 'Cà rốt hữu cơ', 'ca-rot-huu-co', 'NSV-CAROT-01', 'Cà rốt giòn ngọt, màu sắc tự nhiên, giàu dinh dưỡng.', 'Cà rốt giòn ngọt, màu sắc tự nhiên, giàu dinh dưỡng.\n\nSản phẩm được tuyển chọn tại vùng nguyên liệu, kiểm tra chất lượng và đóng gói cẩn thận trước khi giao đến khách hàng.', 42000, NULL, 'kg', 'Đà Lạt', 22, 0, 1, '2026-08-11 01:03:32', '2026-08-13 09:38:36'),
	(5, 8, 'Gạo ST25 Sóc Trăng', 'gao-st25-soc-trang', 'NSV-GAO-01', 'Hạt gạo dài, cơm dẻo thơm và vị ngọt hậu đặc trưng.', 'Hạt gạo dài, cơm dẻo thơm và vị ngọt hậu đặc trưng.\n\nSản phẩm được tuyển chọn tại vùng nguyên liệu, kiểm tra chất lượng và đóng gói cẩn thận trước khi giao đến khách hàng.', 185000, NULL, 'túi 5kg', 'Sóc Trăng', 60, 1, 1, '2026-08-11 01:03:32', '2026-08-13 09:38:33'),
	(6, 8, 'Gạo lứt đỏ Điện Biên', 'gao-lut-do-dien-bien', 'NSV-GAO-02', 'Gạo lứt dẻo bùi, giữ nguyên lớp cám giàu chất xơ.', 'Gạo lứt dẻo bùi, giữ nguyên lớp cám giàu chất xơ.\n\nSản phẩm được tuyển chọn tại vùng nguyên liệu, kiểm tra chất lượng và đóng gói cẩn thận trước khi giao đến khách hàng.', 72000, NULL, 'kg', 'Điện Biên', 28, 0, 1, '2026-08-11 01:03:32', '2026-08-13 09:38:33'),
	(7, 8, 'Mật ong hoa cà phê', 'mat-ong-hoa-ca-phe', 'NSV-MAT-01', 'Mật ong nguyên chất, thơm dịu hương hoa cà phê.', 'Mật ong nguyên chất, thơm dịu hương hoa cà phê.\n\nSản phẩm được tuyển chọn tại vùng nguyên liệu, kiểm tra chất lượng và đóng gói cẩn thận trước khi giao đến khách hàng.', 165000, NULL, 'chai 500ml', 'Đắk Lắk', 12, 1, 1, '2026-08-11 01:03:32', '2026-08-13 09:38:30'),
	(8, 8, 'Hạt điều rang muối', 'hat-dieu-rang-muoi', 'NSV-DIEU-01', 'Hạt điều loại 1, rang giòn cùng muối biển vừa vị.', 'Hạt điều loại 1, rang giòn cùng muối biển vừa vị.\n\nSản phẩm được tuyển chọn tại vùng nguyên liệu, kiểm tra chất lượng và đóng gói cẩn thận trước khi giao đến khách hàng.', 145000, NULL, 'hộp 500g', 'Bình Phước', 35, 1, 1, '2026-08-11 01:03:32', '2026-08-13 09:38:27'),
	(9, 8, 'Tiêu đen Phú Quốc', 'tieu-den-phu-quoc', 'NSV-TIEU-01', 'Hạt tiêu chắc, cay nồng và thơm lâu.', 'Hạt tiêu chắc, cay nồng và thơm lâu.\n\nSản phẩm được tuyển chọn tại vùng nguyên liệu, kiểm tra chất lượng và đóng gói cẩn thận trước khi giao đến khách hàng.', 89000, NULL, 'hũ 250g', 'Phú Quốc', 25, 1, 1, '2026-08-11 01:03:32', '2026-08-13 09:38:21'),
	(10, 8, 'Trà sen Tây Hồ', 'tra-sen-tay-ho', 'NSV-TRA-01', 'Trà xanh ướp gạo sen thủ công, hương thanh tao.', 'Trà xanh ướp gạo sen thủ công, hương thanh tao.\n\nSản phẩm được tuyển chọn tại vùng nguyên liệu, kiểm tra chất lượng và đóng gói cẩn thận trước khi giao đến khách hàng.', 220000, NULL, 'hộp 200g', 'Hà Nội', 8, 0, 1, '2026-08-11 01:03:32', '2026-08-13 09:38:30');

-- Dumping structure for table nongsanvietnam.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table nongsanvietnam.sessions: ~1 rows (approximately)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('6jLwKKRgvsm4RuxfLTZMd3UTS2hPKdTnnrmPBADR', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZXBicldhUUJaRWRacnRMcXJEN2JUME43dE1lT2cwdEZQQlllNVRieSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1786647888);

-- Dumping structure for table nongsanvietnam.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci,
  `is_secret` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`),
  KEY `settings_group_index` (`group`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table nongsanvietnam.settings: ~34 rows (approximately)
INSERT INTO `settings` (`id`, `group`, `key`, `value`, `is_secret`, `created_at`, `updated_at`) VALUES
	(1, 'general', 'site.name', 'Thành Đạt Taabusico', 0, '2026-08-13 09:58:55', '2026-08-13 09:58:55'),
	(2, 'seo', 'seo.home.title', '', 0, '2026-08-13 09:58:55', '2026-08-13 09:58:55'),
	(3, 'seo', 'seo.home.description', '', 0, '2026-08-13 09:58:55', '2026-08-13 09:58:55'),
	(4, 'seo', 'seo.home.image_id', '', 0, '2026-08-13 09:58:55', '2026-08-13 09:58:55'),
	(5, 'seo', 'seo.products.title', '', 0, '2026-08-13 09:58:55', '2026-08-13 09:58:55'),
	(6, 'seo', 'seo.products.description', '', 0, '2026-08-13 09:58:55', '2026-08-13 09:58:55'),
	(7, 'seo', 'seo.products.image_id', '', 0, '2026-08-13 09:58:55', '2026-08-13 09:58:55'),
	(8, 'seo', 'seo.news.title', '', 0, '2026-08-13 09:58:55', '2026-08-13 09:58:55'),
	(9, 'seo', 'seo.news.description', '', 0, '2026-08-13 09:58:55', '2026-08-13 09:58:55'),
	(10, 'seo', 'seo.news.image_id', '', 0, '2026-08-13 09:58:55', '2026-08-13 09:58:55'),
	(11, 'seo', 'seo.contact.title', '', 0, '2026-08-13 09:58:55', '2026-08-13 09:58:55'),
	(12, 'seo', 'seo.contact.description', '', 0, '2026-08-13 09:58:55', '2026-08-13 09:58:55'),
	(13, 'seo', 'seo.contact.image_id', '', 0, '2026-08-13 09:58:55', '2026-08-13 09:58:55'),
	(14, 'smtp', 'smtp.host', 'smtp.gmail.com', 0, '2026-08-13 09:58:55', '2026-08-13 09:58:55'),
	(15, 'smtp', 'smtp.port', '587', 0, '2026-08-13 09:58:55', '2026-08-13 09:58:55'),
	(16, 'smtp', 'smtp.username', 'nongsanvietnam', 0, '2026-08-13 09:58:55', '2026-08-13 09:58:55'),
	(17, 'smtp', 'smtp.encryption', 'tls', 0, '2026-08-13 09:58:55', '2026-08-13 09:58:55'),
	(18, 'smtp', 'smtp.from_address', 'dungconmetheu@gmail.com', 0, '2026-08-13 09:58:55', '2026-08-13 09:58:55'),
	(19, 'smtp', 'smtp.from_name', 'VanDung', 0, '2026-08-13 09:58:55', '2026-08-13 09:58:55'),
	(20, 'smtp', 'smtp.password', 'eyJpdiI6Ik54ZE8zTW9raWtBbVdWT2hPUnNBZWc9PSIsInZhbHVlIjoiTm1NVWlqamE3aDExcjFML1FtWmZMdFY4MjBFanBHT09qMjJjcXpRWWpMOD0iLCJtYWMiOiI3MjcwZDI4MTAxOWExM2FiNjZlYzE0ZjI3ZDIwYjIwYmFjZjQ5Mzg3NzIzZGRiMjBmOWJiZGI3ZWQwNjZkMjhhIiwidGFnIjoiIn0=', 1, '2026-08-13 09:58:55', '2026-08-13 10:03:57'),
	(21, 'menu', 'menu.home.label', 'Trang chủ', 0, '2026-08-13 09:58:55', '2026-08-13 09:58:55'),
	(22, 'menu', 'menu.home.enabled', '1', 0, '2026-08-13 09:58:55', '2026-08-13 09:58:55'),
	(23, 'menu', 'menu.products.label', 'Sản phẩm', 0, '2026-08-13 09:58:55', '2026-08-13 09:58:55'),
	(24, 'menu', 'menu.products.enabled', '1', 0, '2026-08-13 09:58:55', '2026-08-13 09:58:55'),
	(25, 'menu', 'menu.agriculture.label', 'Nông sản', 0, '2026-08-13 09:58:55', '2026-08-13 09:58:55'),
	(26, 'menu', 'menu.agriculture.enabled', '1', 0, '2026-08-13 09:58:56', '2026-08-13 09:58:56'),
	(27, 'menu', 'menu.noodles.label', 'Phở khô, Bún, Miến, Mì', 0, '2026-08-13 09:58:56', '2026-08-13 09:58:56'),
	(28, 'menu', 'menu.noodles.enabled', '1', 0, '2026-08-13 09:58:56', '2026-08-13 09:58:56'),
	(29, 'menu', 'menu.spices.label', 'Gia vị', 0, '2026-08-13 09:58:56', '2026-08-13 09:58:56'),
	(30, 'menu', 'menu.spices.enabled', '1', 0, '2026-08-13 09:58:56', '2026-08-13 09:58:56'),
	(31, 'menu', 'menu.news.label', 'Tin tức & Sự kiện', 0, '2026-08-13 09:58:56', '2026-08-13 09:58:56'),
	(32, 'menu', 'menu.news.enabled', '1', 0, '2026-08-13 09:58:56', '2026-08-13 09:58:56'),
	(33, 'menu', 'menu.contact.label', 'Liên hệ', 0, '2026-08-13 09:58:56', '2026-08-13 09:58:56'),
	(34, 'menu', 'menu.contact.enabled', '1', 0, '2026-08-13 09:58:56', '2026-08-13 09:58:56');

-- Dumping structure for table nongsanvietnam.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table nongsanvietnam.users: ~0 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `is_admin`) VALUES
	(1, 'Quản trị viên', 'admin@nongsanviet.vn', NULL, '$2y$12$j6eN5x6yd9UJA5MysguCeOP5BuKwK7r5afdKlviffj/2Q/Nm6mDni', 'GH0Len5rFxJ4u3xU8KM6U9ZbDk91BMck3nAZ2tPpldJ1g2yseCUue57dPML5', '2026-08-11 01:03:31', '2026-08-11 01:03:31', 1);

-- Add foreign keys only after every referenced table exists.
ALTER TABLE `banners`
  ADD CONSTRAINT `banners_image_id_foreign` FOREIGN KEY (`image_id`) REFERENCES `media` (`id`) ON DELETE CASCADE;
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_image_id_foreign` FOREIGN KEY (`image_id`) REFERENCES `media` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
ALTER TABLE `mediables`
  ADD CONSTRAINT `mediables_media_id_foreign` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE CASCADE;
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_featured_image_id_foreign` FOREIGN KEY (`featured_image_id`) REFERENCES `media` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

SET FOREIGN_KEY_CHECKS=1;
