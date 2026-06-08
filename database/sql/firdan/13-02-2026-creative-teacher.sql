-- Creative Teacher: upload hasil kerja guru. user_id = teacher
-- number contoh: CTYD001 | status: new, under review, rejected, accepted

CREATE TABLE `creative_teachers` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `number` VARCHAR(50) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `topic` VARCHAR(255) NULL,
    `message` TEXT NULL,
    `link_upload` VARCHAR(500) NULL,
    `status` ENUM('new', 'under review', 'rejected', 'accepted') NOT NULL DEFAULT 'new',
    `datetime` DATETIME NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    UNIQUE KEY `creative_teachers_number_unique` (`number`),
    CONSTRAINT `creative_teachers_user_id_foreign`
        FOREIGN KEY (`user_id`) REFERENCES `teachers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Feature menu Creative Teacher (supaya muncul di sidebar backend)
INSERT INTO `features` (`id`, `title`, `code`, `type`, `created_at`, `updated_at`)
VALUES (61, 'Creative Teacher', 'creative_teachers', 'master data', NOW(), NOW());

UPDATE `authorities`
SET `code` = '["1","38","49","50","51","52","53","54","55","56","57","58","59","60","61"]'
WHERE `id` = 5;
