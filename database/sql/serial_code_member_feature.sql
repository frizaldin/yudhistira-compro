-- Insert feature baru: Serial Code Member
-- Jalankan query ini di database untuk menambahkan menu permission

INSERT INTO `features` (`title`, `code`, `type`, `created_at`, `updated_at`)
VALUES ('Serial Code Member', 'serial-code-member', 'master data', NOW(), NOW());

-- Update authority Super Admin (id = 1) untuk bisa akses menu ini
UPDATE `authorities`
SET `code` = JSON_ARRAY_APPEND(`code`, '$', CAST(LAST_INSERT_ID() AS CHAR))
WHERE `id` = 1;
