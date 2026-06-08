-- Open Ticket: sama struktur dengan support center. user_id = teacher
-- ticket_number contoh: OTKTYDHSTR0001 | status: new, process, done

CREATE TABLE `open_tickets` (
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
    UNIQUE KEY `open_tickets_ticket_number_unique` (`ticket_number`),
    CONSTRAINT `open_tickets_user_id_foreign`
        FOREIGN KEY (`user_id`) REFERENCES `teachers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Lampiran tiket open ticket (photo/file). Satu tiket bisa banyak lampiran.

CREATE TABLE `open_ticket_attachments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `open_ticket_id` BIGINT UNSIGNED NOT NULL,
    `path` VARCHAR(255) NOT NULL,
    `original_name` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `open_ticket_attachments_open_ticket_id_foreign`
        FOREIGN KEY (`open_ticket_id`) REFERENCES `open_tickets`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Chat tiket open ticket. User dan admin bisa chat terkait tiket.
-- viewpoint: user = guru, admin = admin | is_read: yes/no

CREATE TABLE `open_ticket_chats` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `open_ticket_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NULL,
    `viewpoint` ENUM('admin', 'user') NOT NULL,
    `message` TEXT NOT NULL,
    `is_read` ENUM('yes', 'no') NOT NULL DEFAULT 'no',
    `datetime` DATETIME NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `open_ticket_chats_open_ticket_id_foreign`
        FOREIGN KEY (`open_ticket_id`) REFERENCES `open_tickets`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `open_ticket_chats_user_id_foreign`
        FOREIGN KEY (`user_id`) REFERENCES `teachers`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Lampiran file pada chat tiket open ticket.

CREATE TABLE `open_ticket_chat_attachments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `open_ticket_chat_id` BIGINT UNSIGNED NOT NULL,
    `file` VARCHAR(255) NOT NULL,
    `datetime` DATETIME NULL,
    `filesize` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `open_ticket_chat_attachments_open_ticket_chat_id_foreign`
        FOREIGN KEY (`open_ticket_chat_id`) REFERENCES `open_ticket_chats`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Feature menu Open Ticket (supaya muncul di sidebar backend)
INSERT INTO `features` (`id`, `title`, `code`, `type`, `created_at`, `updated_at`)
VALUES (60, 'Open Ticket', 'open_tickets', 'master data', NOW(), NOW());

UPDATE `authorities`
SET `code` = '["1","38","49","50","51","52","53","54","55","56","57","58","59","60"]'
WHERE `id` = 5;
