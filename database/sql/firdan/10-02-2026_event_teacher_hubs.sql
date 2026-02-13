-- Event Teacher Hub (Event Guru)

CREATE TABLE `event_teacher_hubs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `photo` TEXT NULL,
    `title` VARCHAR(255) NOT NULL,
    `judul` VARCHAR(255) NULL,
    `date` DATE NULL,
    `start_time` TIME NULL,
    `end_time` TIME NULL,
    `point` VARCHAR(100) NULL,
    `link_meeting` TEXT NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `event_teacher_hubs_created_by_foreign`
        FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
