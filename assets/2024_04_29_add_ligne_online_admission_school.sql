INSERT INTO `menus` (`id`, `displayed_name`, `route_name`, `parent`, `icon`, `status`, `superadmin_access`, `admin_access`, `teacher_access`, `parent_access`, `student_access`, `accountant_access`, `librarian_access`, `sort_order`, `is_addon`, `unique_identifier`) VALUES
(127, 'online_admission_school', 'online_admission_school', 0, 'dripicons-graduation', 1, 1, 0, 0, 0, 0, 0, 0, 9, 0, 'online_admission_school');


ALTER TABLE schools
ADD status int(11) NOT NULL;

ALTER TABLE schools
ADD Etat int(11) NOT NULL DEFAULT 1 ;