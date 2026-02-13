-- Category Event Teacher Hub
-- Kategori untuk Event Guru

CREATE TABLE `category_event_teacher_hubs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(200) NOT NULL,
    `judul` VARCHAR(200) NULL,
    `url` TEXT NULL,
    `order` INT NULL,
    `visible` ENUM('yes', 'no') NOT NULL DEFAULT 'yes',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `event_teacher_hubs` ADD `category_id` BIGINT NULL AFTER `id`;
