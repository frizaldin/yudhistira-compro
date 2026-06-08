-- Bookmark / ikuti Event Teacher Hub atau Video Learning (per user/guru)
-- type: event_teacher_hub | video_learning, reference_id = id event atau id video_learning
CREATE TABLE `teacher_bookmarks` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `type` VARCHAR(50) NOT NULL,
    `reference_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    UNIQUE KEY `teacher_bookmarks_user_type_reference_unique` (`user_id`, `type`, `reference_id`),
    CONSTRAINT `teacher_bookmarks_user_id_foreign`
        FOREIGN KEY (`user_id`) REFERENCES `teachers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Feature menu Teacher Bookmark
INSERT INTO `features` (`id`, `title`, `code`, `type`, `created_at`, `updated_at`)
VALUES (65, 'Teacher Bookmark', 'teacher_bookmarks', 'master data', NOW(), NOW());

UPDATE `authorities`
SET `code` = '["1","38","49","50","51","52","53","54","55","56","57","58","59","60","61","62","63","64","65"]'
WHERE `id` = 5;
