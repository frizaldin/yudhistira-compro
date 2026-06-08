-- Request buku digital oleh guru (maks 6 buku per request).
-- user_id = teacher yang mengajukan | code_book diisi admin setelah diproses

CREATE TABLE `request_books` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `number` VARCHAR(50) NOT NULL,
    `date` DATE NOT NULL,
    `status` ENUM('pending', 'processed', 'rejected') NOT NULL DEFAULT 'pending',
    `code_book` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    UNIQUE KEY `request_books_number_unique` (`number`),
    CONSTRAINT `request_books_user_id_foreign`
        FOREIGN KEY (`user_id`) REFERENCES `teachers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `request_book_details` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `request_book_id` BIGINT UNSIGNED NOT NULL,
    `book_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    UNIQUE KEY `request_book_details_request_book_book_unique` (`request_book_id`, `book_id`),
    CONSTRAINT `request_book_details_request_book_id_foreign`
        FOREIGN KEY (`request_book_id`) REFERENCES `request_books`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Menu backend (sesuaikan id jika bentrok dengan data features Anda)
INSERT INTO `features` (`id`, `title`, `code`, `type`, `created_at`, `updated_at`)
VALUES (67, 'Request Buku Digital', 'request_books', 'master data', NOW(), NOW());

-- Tambahkan "61" ke array JSON kolom `code` pada authorities role admin Anda (contoh seperti open_tickets di 13-02-2026-open-ticket.sql).
