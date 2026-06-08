-- ============================================================
-- Table: product_documents
-- Deskripsi: Menyimpan dokumen atau link yang dilampirkan
--            pada setiap produk buku dari BuyBuku.
-- ============================================================

CREATE TABLE IF NOT EXISTS `product_documents` (
    `id`           BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `product_id`   BIGINT UNSIGNED  NOT NULL COMMENT 'ID produk dari API BuyBuku',
    `attachment`   TEXT             NOT NULL COMMENT 'Path file (jika type=file) atau URL Google Drive / link (jika type=link)',
    `type`         ENUM('file','link') NOT NULL DEFAULT 'link' COMMENT 'Jenis lampiran: file upload atau link eksternal',
    `json_product` JSON             NULL     COMMENT 'Snapshot data produk dari API BuyBuku saat dokumen ditambahkan',
    `created_at`   TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    INDEX `idx_product_documents_product_id` (`product_id`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Dokumen atau link lampiran per produk buku BuyBuku';
