-- Guide Book (Teacher Hub)
-- Buku panduan PDF per kategori

CREATE TABLE `guide_books` (
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
