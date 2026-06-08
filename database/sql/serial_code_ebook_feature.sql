-- Insert feature baru: Serial Code E-Book
-- Jalankan query ini di database untuk menambahkan menu permission

INSERT INTO `features` (`title`, `code`, `type`, `created_at`, `updated_at`)
VALUES ('Serial Code E-Book', 'serial-code-ebook', 'master data', NOW(), NOW());

-- Setelah INSERT, jalankan query UPDATE berikut untuk memberikan akses ke authority_id = 1 (Super Admin).
-- Ganti <ID_BARU> dengan ID yang dihasilkan dari INSERT di atas (cek dengan SELECT LAST_INSERT_ID()).

-- Contoh otomatis menggunakan LAST_INSERT_ID():
UPDATE `authorities`
SET `code` = JSON_ARRAY_APPEND(`code`, '$', CAST(LAST_INSERT_ID() AS CHAR))
WHERE `id` = 1;

-- Jika ingin memberikan akses ke authority lain, jalankan query yang sama dengan WHERE id = <authority_id>.
-- Contoh untuk authority_id = 5 (Teacher Hub):
-- UPDATE `authorities`
-- SET `code` = JSON_ARRAY_APPEND(`code`, '$', CAST(LAST_INSERT_ID() AS CHAR))
-- WHERE `id` = 5;
