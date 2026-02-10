ALTER TABLE `teachers` CHANGE `domisili` `domisili` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;
ALTER TABLE `teachers` ADD INDEX(`domisili`);
ALTER TABLE `teachers` ADD FOREIGN KEY (`domisili`) REFERENCES `cities`(`code`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `teachers` CHANGE `teaching_field` `teaching_field` BIGINT NULL DEFAULT NULL;
ALTER TABLE `teachers` ADD INDEX(`teaching_field`);
ALTER TABLE `teachers` CHANGE `teaching_field` `teaching_field` BIGINT UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `teachers` ADD FOREIGN KEY (`teaching_field`) REFERENCES `mata_pelajaran`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `teachers` ADD `code_sales` VARCHAR(225) NOT NULL AFTER `birth_date`;
