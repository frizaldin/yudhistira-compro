-- ============================================================
-- Tambah kolom certificate_temp ke tabel configurations
-- ============================================================

ALTER TABLE `configurations`
    ADD COLUMN `certificate_temp` TEXT NULL COMMENT 'Template sertifikat'
    AFTER `contact_deskripsi`;
