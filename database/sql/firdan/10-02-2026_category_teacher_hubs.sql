-- Category Teacher Hub (Blog Guru)
-- Kategori untuk Artikel Guru

CREATE TABLE `category_teacher_hubs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(200) NOT NULL,
    `judul` VARCHAR(200) NOT NULL,
    `file` TEXT NULL,
    `url` TEXT NULL,
    `order` INT NULL,
    `visible` ENUM('yes', 'no') NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
