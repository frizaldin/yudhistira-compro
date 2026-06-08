-- Tutorial Video: title, judul, file (video mp4/mkv dll), thumbnail
CREATE TABLE `tutorial_videos` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `judul` VARCHAR(255) NULL,
    `file` VARCHAR(500) NULL,
    `thumbnail` VARCHAR(500) NULL,
    `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Feature menu Tutorial Video
INSERT INTO `features` (`id`, `title`, `code`, `type`, `created_at`, `updated_at`)
VALUES (64, 'Tutorial Video', 'tutorial_videos', 'master data', NOW(), NOW());


UPDATE `authorities`
SET `code` = '["1","38","49","50","51","52","53","54","55","56","57","58","59","60","61","62","63","64"]'
WHERE `id` = 5;
