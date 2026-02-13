-- Chat tiket support center. User dan admin bisa chat terkait tiket.
-- viewpoint: user = guru, admin = admin | is_read: yes/no

CREATE TABLE `support_center_chats` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `support_center_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NULL,
    `viewpoint` ENUM('admin', 'user') NOT NULL,
    `message` TEXT NOT NULL,
    `is_read` ENUM('yes', 'no') NOT NULL DEFAULT 'no',
    `datetime` DATETIME NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `support_center_chats_support_center_id_foreign`
        FOREIGN KEY (`support_center_id`) REFERENCES `support_centers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `support_center_chats_user_id_foreign`
        FOREIGN KEY (`user_id`) REFERENCES `teachers`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
