-- Jawaban pertanyaan event (guru). user_id = teachers.id

CREATE TABLE `event_question_answers` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `event_id` BIGINT UNSIGNED NOT NULL,
    `question_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `answer` TEXT NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `event_question_answers_event_id_foreign`
        FOREIGN KEY (`event_id`) REFERENCES `event_teacher_hubs`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `event_question_answers_question_id_foreign`
        FOREIGN KEY (`question_id`) REFERENCES `event_teacher_hub_questions`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `event_question_answers_user_id_foreign`
        FOREIGN KEY (`user_id`) REFERENCES `teachers`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
