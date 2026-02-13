-- Digital Learning Support (Teacher Hub)
-- Video internal (file) atau external (embed)

CREATE TABLE `digital_learning_supports` (
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
