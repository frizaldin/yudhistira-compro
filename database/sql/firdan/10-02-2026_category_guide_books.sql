-- Category Guide Book (Teacher Hub)
-- Kategori untuk Buku Panduan

CREATE TABLE `category_guide_books` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(200) NOT NULL,
    `judul` VARCHAR(200) NULL,
    `url` TEXT NULL,
    `order` INT NULL,
    `visible` ENUM('yes', 'no') NOT NULL DEFAULT 'yes',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
