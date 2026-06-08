-- Lampiran file pada chat tiket support center.

CREATE TABLE `support_center_chat_attachments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `support_center_chat_id` BIGINT UNSIGNED NOT NULL,
    `file` VARCHAR(255) NOT NULL,
    `datetime` DATETIME NULL,
    `filesize` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `support_center_chat_attachments_support_center_chat_id_foreign`
        FOREIGN KEY (`support_center_chat_id`) REFERENCES `support_center_chats`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
