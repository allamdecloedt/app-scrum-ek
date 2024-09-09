CREATE TABLE `quiz_responses` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `quiz_id` INT NOT NULL,
    `question_id` INT NOT NULL,
    `submitted_answers` TEXT NOT NULL,
    `correct_answers` TEXT NOT NULL,
    `submitted_answer_status` TINYINT NOT NULL,
    `date_submitted` TIMESTAMP DEFAULT CURRENT_TIMESTAMP

)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


INSERT INTO `menus` (`id`, `displayed_name`, `route_name`, `parent`, `icon`, `status`, `superadmin_access`, `admin_access`, `teacher_access`, `student_access`, `accountant_access`, `librarian_access`, `sort_order`, `is_addon`, `unique_identifier`) VALUES
(128, 'quiz_result', 'quiz_result', 19, NULL, 1, 1, 1, 0, 0,  0, 0, 10, 0, 'quiz_result');

