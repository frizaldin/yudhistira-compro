-- Catatan asal point (dari backend: creative teacher, support center, open ticket, dll)
ALTER TABLE `user_points`
    ADD COLUMN `note` TEXT NULL AFTER `point`;
