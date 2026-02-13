-- Point guru (IN = dapat point, OUT = pakai point). user_id = teachers.id

CREATE TABLE `user_points` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `type` ENUM('IN', 'OUT') NOT NULL,
    `point` INT UNSIGNED NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `user_points_user_id_foreign`
        FOREIGN KEY (`user_id`) REFERENCES `teachers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
