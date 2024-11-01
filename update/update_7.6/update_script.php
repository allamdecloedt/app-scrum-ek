<?php
$CI = get_instance();
$CI->load->database();
$CI->load->dbforge();

function init_field($type, $constraint = 255, $default = null, $null = true, $collation = 'utf8_unicode_ci')
{
    $column_type['type'] = ($type == 'id') ? 'INT' : strtoupper($type);
    $column_type['collation'] = $collation;

    if (!in_array($type, ['double', 'long', 'float', 'timestamp'])) {
        $column_type['constraint'] = $constraint;
    }

    if ($type == 'id') {
        $column_type['unsigned'] = true;
        $column_type['auto_increment'] = true;
    } else {
        $column_type['default'] = $default;
        $column_type['null'] = $null;
    }
    return $column_type;
}

$tables['assign_students'] = [
    'id' => init_field('id'),
    'school_id' => init_field('int'),
    'vehicle_id' => init_field('int'),
    'driver_id' => init_field('int'),
    'class_id' => init_field('int'),
    'student_id' => init_field('int'),
    'date_added' => init_field('int'),
];

$tables['drivers'] = [
    'id' => init_field('id'),
    'school_id' => init_field('int'),
    'user_id' => init_field('int'),
    'social_links' => init_field('long'),
    'about' => init_field('long'),
];

$tables['trips'] = [
    'id' => init_field('id'),
    'school_id' => init_field('int'),
    'vehicle_id' => init_field('int'),
    'driver_id' => init_field('int'),
    'start_time' => init_field('varchar'),
    'end_time' => init_field('varchar'),
    'status' => init_field('int', 255, 1),
    'starting_point' => init_field('varchar'),
    'last_location' => init_field('varchar'),
];

$tables['vehicles'] = [
    'id' => init_field('id'),
    'school_id' => init_field('int'),
    'driver' => init_field('int'),
    'vh_num' => init_field('varchar'),
    'vh_model' => init_field('varchar'),
    'vh_chassis' => init_field('varchar'),
    'capacity' => init_field('int'),
    'route' => init_field('varchar'),
];

// add new fields to the database
foreach ($tables as $key => $item) {
    $CI->dbforge->add_field($item);
    $CI->dbforge->add_key('id', TRUE);
    $attributes = array('collation' => "utf8_unicode_ci");
    $CI->dbforge->create_table($key, TRUE);
}

// add new columns in menu table
$fields = ['driver_access' => init_field('int')];
$CI->dbforge->add_column('menus', $fields);

// get parent id
$last_menu = $CI->db->order_by('id', 'desc')->get('menus')->row_array();

// add new menu item as row
$menus = [
    [
        'displayed_name' => 'transport',
        'route_name' => 'transport',
        'parent' => '0',
        'icon' => 'uil-bus',
        'status' => 1,
        'superadmin_access' => 0,
        'admin_access' => 1,
        'teacher_access' => 0,
        'parent_access' => 0,
        'student_access' => 0,
        'accountant_access' => 0,
        'librarian_access' => 0,
        'driver_access' => 0,
        'sort_order' => '9',
        'is_addon' => 0,
        'unique_identifier' => 'transport'
    ],
    [
        'displayed_name' => 'driver',
        'route_name' => 'driver',
        'parent' => $last_menu['id'] + 1,
        'icon' => null,
        'status' => 1,
        'superadmin_access' => 0,
        'admin_access' => 1,
        'teacher_access' => 0,
        'parent_access' => 0,
        'student_access' => 0,
        'accountant_access' => 0,
        'librarian_access' => 0,
        'driver_access' => 0,
        'sort_order' => '9',
        'unique_identifier' => 'driver'
    ],
    [
        'displayed_name' => 'vehicle',
        'route_name' => 'vehicle',
        'parent' => $last_menu['id'] + 1,
        'icon' => null,
        'status' => 1,
        'superadmin_access' => 0,
        'admin_access' => 1,
        'teacher_access' => 0,
        'parent_access' => 0,
        'student_access' => 0,
        'accountant_access' => 0,
        'librarian_access' => 0,
        'driver_access' => 0,
        'sort_order' => '9',
        'unique_identifier' => 'vehicle'
    ],
    [
        'displayed_name' => 'assign students',
        'route_name' => 'assign_student',
        'parent' => $last_menu['id'] + 1,
        'icon' => null,
        'status' => 1,
        'superadmin_access' => 0,
        'admin_access' => 1,
        'teacher_access' => 0,
        'parent_access' => 0,
        'student_access' => 0,
        'accountant_access' => 0,
        'librarian_access' => 0,
        'driver_access' => 0,
        'sort_order' => '9',
        'unique_identifier' => 'assign_students'
    ],
    [
        'displayed_name' => 'trip',
        'route_name' => 'trip',
        'parent' => 0,
        'icon' => 'uil-bus',
        'status' => 1,
        'superadmin_access' => 0,
        'admin_access' => 0,
        'teacher_access' => 0,
        'parent_access' => 0,
        'student_access' => 0,
        'accountant_access' => 0,
        'librarian_access' => 0,
        'driver_access' => 1,
        'sort_order' => '9',
        'unique_identifier' => 'trip'
    ],
    [
        'displayed_name' => 'assigned_student',
        'route_name' => 'assigned_student',
        'parent' => 0,
        'icon' => 'uil-user-check',
        'status' => 1,
        'superadmin_access' => 0,
        'admin_access' => 0,
        'teacher_access' => 0,
        'parent_access' => 0,
        'student_access' => 0,
        'accountant_access' => 0,
        'librarian_access' => 0,
        'driver_access' => 1,
        'sort_order' => '9',
        'unique_identifier' => 'assigned_student',
    ],
    [
        'displayed_name' => 'trips',
        'route_name' => 'trips',
        'parent' => 0,
        'icon' => 'uil-bus',
        'status' => 1,
        'superadmin_access' => 0,
        'admin_access' => 0,
        'teacher_access' => 0,
        'parent_access' => 1,
        'student_access' => 0,
        'accountant_access' => 0,
        'librarian_access' => 0,
        'driver_access' => 0,
        'sort_order' => '9',
        'unique_identifier' => 'trips',
    ],
];

$index = 0;
foreach ($menus as $menu) {
    $CI->db->insert('menus', $menu);
}

// update VERSION NUMBER INSIDE SETTINGS TABLE
$CI->db->where('id', 1);
$CI->db->update('settings', ['version' => '7.6']);
