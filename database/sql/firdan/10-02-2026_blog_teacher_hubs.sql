-- Blog Teacher Hub (Artikel Guru)

CREATE TABLE `blog_teacher_hubs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `category_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `nama` VARCHAR(255) NULL,
    `photo` TEXT NULL,
    `overview` TEXT NULL,
    `pratinjau` TEXT NULL,
    `description` LONGTEXT NULL,
    `deskripsi` LONGTEXT NULL,
    `tags` TEXT NULL,
    `url` TEXT NULL,
    `visible` ENUM('yes', 'no') NOT NULL DEFAULT 'no',
    `date` DATE NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `blog_teacher_hubs_category_id_foreign`
        FOREIGN KEY (`category_id`) REFERENCES `category_teacher_hubs`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `blog_teacher_hubs_created_by_foreign`
        FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
