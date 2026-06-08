-- Support Center: open tiket (pertanyaan / hal lainnya). user_id = teacher
-- ticket_number contoh: TKTYDHSTR0001 | status: new, process, done

CREATE TABLE `support_centers` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `ticket_number` VARCHAR(50) NOT NULL,
    `status` ENUM('new', 'process', 'done') NOT NULL DEFAULT 'new',
    `title` VARCHAR(255) NOT NULL,
    `topic` VARCHAR(255) NULL,
    `message` TEXT NOT NULL,
    `datetime` DATETIME NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    UNIQUE KEY `support_centers_ticket_number_unique` (`ticket_number`),
    CONSTRAINT `support_centers_user_id_foreign`
        FOREIGN KEY (`user_id`) REFERENCES `teachers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
