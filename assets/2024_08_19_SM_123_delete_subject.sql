UPDATE `menus` 
SET `superadmin_access` = '0', `admin_access` = '0' , `teacher_access` = '0' , `student_access` = '0' 
WHERE `menus`.`id` = 14;
