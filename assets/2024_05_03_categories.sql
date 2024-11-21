CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;



INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Business'),
(2, 'Health & fitness'),
(3, 'Personal development'),
(4, 'Arts & crafts'),
(5, 'Music'),
(6, 'E-commerce'),
(7, 'Sales & Marketing'),
(8, 'Tech'),
(9, 'Spirituality'),
(10, 'Beauty & fashion'),
(11, 'Real estate'),
(12, 'Gaming'),
(13, 'Sports'),
(14, 'Productivity'),
(15, 'Cars'),
(16, 'Pets'),
(17, 'Travel');
