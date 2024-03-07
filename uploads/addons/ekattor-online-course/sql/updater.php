<?php
$CI = get_instance();
$CI->load->database();
$CI->load->dbforge();


$menu_data['displayed_name'] = 'online_courses';
$menu_data['route_name'] = 'courses';
$menu_data['parent'] = '0';
$menu_data['icon'] = 'dripicons-media-play';
$menu_data['status'] = '1';
$menu_data['superadmin_access'] = '1';
$menu_data['admin_access'] = '1';
$menu_data['teacher_access'] = '1';
$menu_data['parent_access'] = '0';
$menu_data['student_access'] = '1';
$menu_data['accountant_access'] = '0';
$menu_data['librarian_access'] = '0';
$menu_data['sort_order'] = '52';
$menu_data['is_addon'] = '1';
$menu_data['unique_identifier'] = 'online_courses';
$CI->db->insert('menus', $menu_data);


// define table fields
$course_table = array(
	'id' => array(
		'type' => 'INT',
		'constraint' => 11,
		'unsigned' => TRUE,
		'auto_increment' => TRUE
	),
	'title' => array(
		'type' => 'VARCHAR',
		'constraint' => 255,
		'null' => TRUE
	),
	'description' => array(
		'type' => 'TEXT',
		'null' => TRUE
	),
	'outcomes' => array(
		'type' => 'TEXT',
		'null' => TRUE
	),
	'class_id' => array(
		'type' => 'INT',
		'constraint' => 11,
		'null' => TRUE
	),
	'section' => array(
		'type' => 'TEXT',
		'null' => TRUE
	),
	'subject_id' => array(
		'type' => 'INT',
		'constraint' => 11,
		'null' => TRUE
	),
	'user_id' => array(
		'type' => 'INT',
		'constraint' => 11,
		'null' => TRUE
	),
	'thumbnail' => array(
		'type' => 'VARCHAR',
		'constraint' => 255,
		'null' => TRUE
	),
	'course_overview_url' => array(
		'type' => 'VARCHAR',
		'constraint' => 255,
		'null' => TRUE
	),
	'date_added' => array(
		'type' => 'INT',
		'constraint' => 11,
		'null' => TRUE
	),
	'last_modified' => array(
		'type' => 'INT',
		'constraint' => 11,
		'null' => TRUE
	),
	'visibility' => array(
		'type' => 'INT',
		'constraint' => 11,
		'null' => TRUE
	),
	'status' => array(
		'type' => 'VARCHAR',
		'constraint' => 255,
		'null' => TRUE
	),
	'course_overview_provider' => array(
		'type' => 'VARCHAR',
		'constraint' => 255,
		'null' => TRUE
	),
	'school_id' => array(
		'type' => 'INT',
		'constraint' => 11,
		'null' => TRUE
	)
);
$CI->dbforge->add_field($course_table);
// define primary key
$CI->dbforge->add_key('id', TRUE);
// create table
$CI->dbforge->create_table('course');


// define table fields
$course_section_table = array(
	'id' => array(
		'type' => 'INT',
		'constraint' => 11,
		'unsigned' => TRUE,
		'auto_increment' => TRUE
	),
	'title' => array(
		'type' => 'VARCHAR',
		'constraint' => 255,
		'null' => TRUE
	),
	'course_id' => array(
		'type' => 'INT',
		'constraint' => 11,
		'null' => TRUE
	),
	'orders' => array(
		'type' => 'INT',
		'constraint' => 11,
		'null' => TRUE
	)
);
$CI->dbforge->add_field($course_section_table);
// define primary key
$CI->dbforge->add_key('id', TRUE);
// create table
$CI->dbforge->create_table('course_section');


// define table fields
$lesson_table = array(
	'id' => array(
		'type' => 'INT',
		'constraint' => 11,
		'unsigned' => TRUE,
		'auto_increment' => TRUE
	),
	'title' => array(
		'type' => 'VARCHAR',
		'constraint' => 255,
		'null' => TRUE
	),
	'duration' => array(
		'type' => 'VARCHAR',
		'constraint' => 255,
		'null' => TRUE
	),
	'course_id' => array(
		'type' => 'INT',
		'constraint' => 11,
		'null' => TRUE
	),
	'section_id' => array(
		'type' => 'INT',
		'constraint' => 11,
		'null' => TRUE
	),
	'video_type' => array(
		'type' => 'VARCHAR',
		'constraint' => 255,
		'null' => TRUE
	),
	'video_url' => array(
		'type' => 'VARCHAR',
		'constraint' => 255,
		'null' => TRUE
	),
	'lesson_type' => array(
		'type' => 'VARCHAR',
		'constraint' => 255,
		'null' => TRUE
	),
	'attachment' => array(
		'type' => 'VARCHAR',
		'constraint' => 255,
		'null' => TRUE
	),
	'attachment_type' => array(
		'type' => 'VARCHAR',
		'constraint' => 255,
		'null' => TRUE
	),
	'summary' => array(
		'type' => 'TEXT',
		'null' => TRUE
	),
	'order' => array(
		'type' => 'INT',
		'constraint' => 11,
		'default' => '0',
		'null' => TRUE
	),
	'date_added' => array(
		'type' => 'INT',
		'constraint' => 11,
		'null' => TRUE
	),
	'last_modified' => array(
		'type' => 'INT',
		'constraint' => 11,
		'null' => TRUE
	)
);
$CI->dbforge->add_field($lesson_table);
// define primary key
$CI->dbforge->add_key('id', TRUE);
// create table
$CI->dbforge->create_table('lesson');


// define table fields
$question_table = array(
	'id' => array(
		'type' => 'INT',
		'constraint' => 11,
		'unsigned' => TRUE,
		'auto_increment' => TRUE
	),
	'title' => array(
		'type' => 'TEXT',
		'null' => TRUE
	),
	'quiz_id' => array(
		'type' => 'INT',
		'constraint' => 11,
		'null' => TRUE
	),
	'type' => array(
		'type' => 'VARCHAR',
		'constraint' => 255,
		'null' => TRUE
	),
	'number_of_options' => array(
		'type' => 'INT',
		'constraint' => 11,
		'null' => TRUE
	),
	'options' => array(
		'type' => 'TEXT',
		'null' => TRUE
	),
	'correct_answers' => array(
		'type' => 'TEXT',
		'null' => TRUE
	),
	'order' => array(
		'type' => 'INT',
		'constraint' => 11,
		'default' => '0',
		'null' => TRUE
	)
);
$CI->dbforge->add_field($question_table);
// define primary key
$CI->dbforge->add_key('id', TRUE);
// create table
$CI->dbforge->create_table('question');

?>
