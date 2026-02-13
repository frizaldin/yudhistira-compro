-- Teacher Book: redeem code buku yang digunakan guru (user_id = teacher)
-- Relasi: user_id -> teachers.id

CREATE TABLE `teacher_books` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `code` VARCHAR(100) NOT NULL,
    `book_id` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `teacher_books_user_id_foreign`
        FOREIGN KEY (`user_id`) REFERENCES `teachers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
