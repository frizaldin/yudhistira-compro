-- Lampiran tiket support center (photo/file). Satu tiket bisa banyak lampiran.

CREATE TABLE `support_center_attachments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `support_center_id` BIGINT UNSIGNED NOT NULL,
    `path` VARCHAR(255) NOT NULL,
    `original_name` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `support_center_attachments_support_center_id_foreign`
        FOREIGN KEY (`support_center_id`) REFERENCES `support_centers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
