-- NPSN sekolah untuk akun teacher (API register & profil)

ALTER TABLE `teachers` ADD `npsn` VARCHAR(100) NOT NULL AFTER `id`;
