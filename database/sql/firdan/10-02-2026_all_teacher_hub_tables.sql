-- ============================================================
-- Teacher Hub: tabel baru (Firdan)
-- Jalankan file ini untuk buat semua tabel sekaligus.
-- Urutan: category dulu, lalu tabel yang punya FK.
-- ============================================================

-- 1. Category Guide Book
CREATE TABLE IF NOT EXISTS `category_guide_books` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(200) NOT NULL,
    `judul` VARCHAR(200) NULL,
    `url` TEXT NULL,
    `order` INT NULL,
    `visible` ENUM('yes', 'no') NOT NULL DEFAULT 'yes',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Category Event Teacher Hub
CREATE TABLE IF NOT EXISTS `category_event_teacher_hubs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(200) NOT NULL,
    `judul` VARCHAR(200) NULL,
    `url` TEXT NULL,
    `order` INT NULL,
    `visible` ENUM('yes', 'no') NOT NULL DEFAULT 'yes',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Digital Learning Support (FK: users)
CREATE TABLE IF NOT EXISTS `digital_learning_supports` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `judul` VARCHAR(255) NULL,
    `video_tipe` ENUM('internal', 'external') NOT NULL DEFAULT 'internal',
    `file` TEXT NULL,
    `embed` TEXT NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `digital_learning_supports_created_by_foreign`
        FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Guide Book (FK: category_guide_books)
CREATE TABLE IF NOT EXISTS `guide_books` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `category_id` BIGINT UNSIGNED NOT NULL,
    `thumbnail` TEXT NULL,
    `title` VARCHAR(255) NOT NULL,
    `judul` VARCHAR(255) NULL,
    `file` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `guide_books_category_id_foreign`
        FOREIGN KEY (`category_id`) REFERENCES `category_guide_books`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
