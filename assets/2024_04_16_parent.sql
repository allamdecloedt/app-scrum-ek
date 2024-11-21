DROP TABLE parents ; 

ALTER TABLE students
DROP COLUMN parent_id ;

ALTER TABLE menus
DROP COLUMN parent_access ;

DELETE FROM `menus`
WHERE `id` = 5
