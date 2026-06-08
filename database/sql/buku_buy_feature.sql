-- Insert feature baru: Katalog Buku BuyBuku
-- Jalankan query ini di database untuk menambahkan menu permission

INSERT INTO `features` (`title`, `code`, `type`, `created_at`, `updated_at`)
VALUES ('Katalog Buku BuyBuku', 'buku_buy', 'master data', NOW(), NOW());
UPDATE `authorities` SET `code` = '[\"1\",\"38\",\"49\",\"50\",\"51\",\"52\",\"53\",\"54\",\"55\",\"56\",\"57\",\"58\",\"59\",\"60\",\"61\",\"62\",\"63\",\"64\",\"65\",\"12\",\"66\",\"67\",\"68\",\"69\"]' WHERE `authorities`.`id` = 5;


-- Setelah insert, catat ID yang dihasilkan, lalu update authority yang perlu akses ke menu ini.
-- Contoh: tambahkan ID feature ke JSON code authority_id = 5 (Teacher Hub admin):
-- UPDATE `authorities` SET `code` = JSON_ARRAY_APPEND(`code`, '$', <ID_BARU>) WHERE `id` = 5;
--
-- Atau jika kolom code berupa JSON array string, bisa gunakan:
-- UPDATE `authorities`
-- SET `code` = JSON_ARRAY_APPEND(code, '$', LAST_INSERT_ID())
-- WHERE id IN (1, 5);  -- sesuaikan authority yang perlu akses
