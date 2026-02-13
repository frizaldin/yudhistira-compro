INSERT INTO `features` (`id`, `title`, `code`, `type`, `created_at`, `updated_at`)
VALUES (55, 'Kategori Event', 'category_event_teacher_hubs', 'master data', '2026-02-09 03:52:46', '2026-02-09 03:52:46');

INSERT INTO `features` (`id`, `title`, `code`, `type`, `created_at`, `updated_at`)
VALUES (56, 'Guide Book', 'guide_book', 'master data', '2026-02-09 03:52:46', '2026-02-09 03:52:46');

INSERT INTO `features` (`id`, `title`, `code`, `type`, `created_at`, `updated_at`)
VALUES (57, 'Digital Learning Support', 'digital_learning_support', 'master data', '2026-02-09 03:52:46', '2026-02-09 03:52:46');

INSERT INTO `features` (`id`, `title`, `code`, `type`, `created_at`, `updated_at`)
VALUES (58, 'Kategori Guide Book', 'category_guide_book', 'master data', '2026-02-09 03:52:46', '2026-02-09 03:52:46');

INSERT INTO `features` (`id`, `title`, `code`, `type`, `created_at`, `updated_at`)
VALUES (59, 'Open Tiket', 'support_centers', 'master data', '2026-02-09 03:52:46', '2026-02-09 03:52:46');

UPDATE `authorities`
SET `code` = '["1","38","49","50","51","52","53","54","55","56","57","58","59"]'
WHERE `id` = 5;
