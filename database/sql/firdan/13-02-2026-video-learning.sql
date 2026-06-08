-- Video Learning: kategori
CREATE TABLE `video_learning_categories` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Video Learning: 1 learning punya banyak video (urutan 1,2,3...)
-- title/judul, description/deskripsi, thumbnail, point (diberi setelah selesai semua video + quiz)
CREATE TABLE `video_learnings` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `category_id` BIGINT UNSIGNED NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `judul` VARCHAR(255) NULL,
    `description` TEXT NULL,
    `deskripsi` TEXT NULL,
    `thumbnail` VARCHAR(500) NULL,
    `point` INT UNSIGNED NOT NULL DEFAULT 0,
    `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `video_learnings_category_id_foreign`
        FOREIGN KEY (`category_id`) REFERENCES `video_learning_categories`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Video di dalam 1 Video Learning (urutan: sort_order 1,2,3... user harus selesai video 1 baru bisa buka video 2)
CREATE TABLE `video_learning_videos` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `video_learning_id` BIGINT UNSIGNED NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `video_url` VARCHAR(500) NOT NULL,
    `sort_order` INT UNSIGNED NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `video_learning_videos_video_learning_id_foreign`
        FOREIGN KEY (`video_learning_id`) REFERENCES `video_learnings`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pertanyaan quiz: bisa per video (video_learning_video_id diisi) atau per learning (video_learning_video_id NULL = quiz setelah semua video)
CREATE TABLE `video_learning_quiz_questions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `video_learning_id` BIGINT UNSIGNED NOT NULL,
    `video_learning_video_id` BIGINT UNSIGNED NULL,
    `question_text` TEXT NOT NULL,
    `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `video_learning_quiz_questions_video_learning_id_foreign`
        FOREIGN KEY (`video_learning_id`) REFERENCES `video_learnings`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `video_learning_quiz_questions_video_id_foreign`
        FOREIGN KEY (`video_learning_video_id`) REFERENCES `video_learning_videos`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Opsi jawaban quiz (multiple choice)
CREATE TABLE `video_learning_quiz_options` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `video_learning_quiz_question_id` BIGINT UNSIGNED NOT NULL,
    `option_text` VARCHAR(500) NOT NULL,
    `is_correct` TINYINT(1) NOT NULL DEFAULT 0,
    `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `video_learning_quiz_options_question_id_foreign`
        FOREIGN KEY (`video_learning_quiz_question_id`) REFERENCES `video_learning_quiz_questions`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Progress user: video mana saja yang sudah ditonton sampai selesai (unlock video berikutnya)
CREATE TABLE `video_learning_video_progress` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `video_learning_video_id` BIGINT UNSIGNED NOT NULL,
    `completed_at` DATETIME NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    UNIQUE KEY `video_learning_video_progress_user_video_unique` (`user_id`, `video_learning_video_id`),
    CONSTRAINT `video_learning_video_progress_user_id_foreign`
        FOREIGN KEY (`user_id`) REFERENCES `teachers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `video_learning_video_progress_video_id_foreign`
        FOREIGN KEY (`video_learning_video_id`) REFERENCES `video_learning_videos`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Jawaban user untuk quiz (satu user per question bisa banyak attempt? atau satu saja?) -> satu jawaban per question (latest), atau simpan history. Simpel: satu record per user per question (update jika submit lagi)
CREATE TABLE `video_learning_quiz_answers` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `video_learning_quiz_question_id` BIGINT UNSIGNED NOT NULL,
    `video_learning_quiz_option_id` BIGINT UNSIGNED NOT NULL,
    `is_correct` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    UNIQUE KEY `video_learning_quiz_answers_user_question_unique` (`user_id`, `video_learning_quiz_question_id`),
    CONSTRAINT `video_learning_quiz_answers_user_id_foreign`
        FOREIGN KEY (`user_id`) REFERENCES `teachers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `video_learning_quiz_answers_question_id_foreign`
        FOREIGN KEY (`video_learning_quiz_question_id`) REFERENCES `video_learning_quiz_questions`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `video_learning_quiz_answers_option_id_foreign`
        FOREIGN KEY (`video_learning_quiz_option_id`) REFERENCES `video_learning_quiz_options`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Penyelesaian learning (user dapat point sekali per learning)
CREATE TABLE `video_learning_completions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `video_learning_id` BIGINT UNSIGNED NOT NULL,
    `point_awarded` INT UNSIGNED NOT NULL DEFAULT 0,
    `completed_at` DATETIME NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    UNIQUE KEY `video_learning_completions_user_learning_unique` (`user_id`, `video_learning_id`),
    CONSTRAINT `video_learning_completions_user_id_foreign`
        FOREIGN KEY (`user_id`) REFERENCES `teachers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `video_learning_completions_video_learning_id_foreign`
        FOREIGN KEY (`video_learning_id`) REFERENCES `video_learnings`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Feature menu Video Learning (video learnings + category)
INSERT INTO `features` (`id`, `title`, `code`, `type`, `created_at`, `updated_at`)
VALUES (62, 'Video Learning', 'video_learnings', 'master data', NOW(), NOW());
INSERT INTO `features` (`id`, `title`, `code`, `type`, `created_at`, `updated_at`)
VALUES (63, 'Kategori Video Learning', 'category_video_learning', 'master data', NOW(), NOW());

UPDATE `authorities`
SET `code` = '["1","38","49","50","51","52","53","54","55","56","57","58","59","60","61","62","63"]'
WHERE `id` = 5;
