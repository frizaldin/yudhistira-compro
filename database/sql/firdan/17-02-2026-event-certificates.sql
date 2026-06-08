-- Sertifikat event: dicatat setelah guru menyelesaikan kuis event (satu sertifikat per event per user)
CREATE TABLE `event_certificates` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `event_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    UNIQUE KEY `event_certificates_event_id_user_id_unique` (`event_id`, `user_id`),
    CONSTRAINT `event_certificates_event_id_foreign`
        FOREIGN KEY (`event_id`) REFERENCES `event_teacher_hubs`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `event_certificates_user_id_foreign`
        FOREIGN KEY (`user_id`) REFERENCES `teachers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
