UPDATE `menus` SET `admin_access` = '1' WHERE `menus`.`id` = 36;

ALTER TABLE `payment_settings` ADD `school_id` INT(11) NOT NULL AFTER `value`;

INSERT INTO `payment_settings` ( `key`, `value`, `school_id`) VALUES
('stripe_settings', '[{\"stripe_active\":\"yes\",\"stripe_mode\":\"on\",\"stripe_test_secret_key\":\"1234\",\"stripe_test_public_key\":\"1234\",\"stripe_live_secret_key\":\"1234\",\"stripe_live_public_key\":\"1234\",\"stripe_currency\":\"USD\"}]', 13),
( 'paypal_settings', '[{\"paypal_active\":\"yes\",\"paypal_mode\":\"production\",\"paypal_client_id_sandbox\":\"5355654\",\"paypal_client_id_production\":\"5212474\",\"paypal_currency\":\"USD\"}]', 13);
