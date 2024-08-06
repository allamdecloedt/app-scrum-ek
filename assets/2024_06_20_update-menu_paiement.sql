UPDATE `menus` SET `admin_access` = '1' WHERE `menus`.`id` = 36;

ALTER TABLE `payment_settings` ADD `school_id` INT(11) NOT NULL AFTER `value`;

CREATE TABLE `settings_school` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL,
  `system_currency` varchar(255) DEFAULT NULL,
  `currency_position` varchar(255) DEFAULT NULL,
  `language` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;