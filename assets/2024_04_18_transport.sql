DROP TABLE vehicles ; 
DROP TABLE drivers ; 
DROP TABLE trips ; 

ALTER TABLE assign_students
DROP COLUMN vehicle_id ;

ALTER TABLE assign_students
DROP COLUMN driver_id ;

ALTER TABLE menus
DROP COLUMN driver_access ;

DELETE FROM `menus`
WHERE `id` = 118;
DELETE FROM `menus`
WHERE `id` = 119;
DELETE FROM `menus`
WHERE `id` = 120;
DELETE FROM `menus`
WHERE `id` = 121;
DELETE FROM `menus`
WHERE `id` = 122;
DELETE FROM `menus`
WHERE `id` = 124;