<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
 *  @author   : Creativeitem
 *  date      : November, 2019
 *  Ekattor School Management System With Addons
 *  http://codecanyon.net/user/Creativeitem
 *  http://support.creativeitem.com
 */

class User_model extends CI_Model
{

	protected $school_id;
	protected $active_session;

	public function __construct()
	{
		parent::__construct();
		$this->school_id = school_id();
		$this->active_session = active_session();
	}

	// GET SUPERADMIN DETAILS
	public function get_superadmin()
	{
		$this->db->where('role', 'superadmin');
		return $this->db->get('users')->row_array();
	}
	// GET USER DETAILS
	public function get_user_details($user_id = '', $column_name = '')
	{
		if ($column_name != '') {
			return $this->db->get_where('users', array('id' => $user_id))->row($column_name);
		} else {
			return $this->db->get_where('users', array('id' => $user_id))->row_array();
		}
	}

	// ADMIN CRUD SECTION STARTS
	public function create_admin()
	{
		$data['school_id'] = html_escape($this->input->post('school_id'));
		$data['name'] = html_escape($this->input->post('name'));
		$data['email'] = html_escape($this->input->post('email'));
		$data['password'] = sha1($this->input->post('password'));
		$data['phone'] = html_escape($this->input->post('phone'));
		$data['gender'] = html_escape($this->input->post('gender'));
		$data['address'] = html_escape($this->input->post('address'));
		$data['role'] = 'admin';
		$data['watch_history'] = '[]';

		// check email duplication
		$duplication_status = $this->check_duplication('on_create', $data['email']);
		if ($duplication_status) {
			$this->db->insert('users', $data);

			$response = array(
				'status' => true,
				'notification' => get_phrase('admin_added_successfully')
			);
		} else {
			$response = array(
				'status' => false,
				'notification' => get_phrase('sorry_this_email_has_been_taken')
			);
		}

		return json_encode($response);
	}

	public function update_admin($param1 = '')
	{
		$data['name'] = html_escape($this->input->post('name'));
		$data['email'] = html_escape($this->input->post('email'));
		$data['phone'] = html_escape($this->input->post('phone'));
		$data['gender'] = html_escape($this->input->post('gender'));
		$data['address'] = html_escape($this->input->post('address'));
		$data['school_id'] = html_escape($this->input->post('school_id'));
		// check email duplication
		$duplication_status = $this->check_duplication('on_update', $data['email'], $param1);
		if ($duplication_status) {
			$this->db->where('id', $param1);
			$this->db->update('users', $data);

			$response = array(
				'status' => true,
				'notification' => get_phrase('admin_has_been_updated_successfully')
			);

		} else {
			$response = array(
				'status' => false,
				'notification' => get_phrase('sorry_this_email_has_been_taken')
			);
		}

		return json_encode($response);
	}

	public function delete_admin($param1 = '')
	{
		$this->db->where('id', $param1);
		$this->db->delete('users');

		$response = array(
			'status' => true,
			'notification' => get_phrase('admin_has_been_deleted_successfully')
		);
		return json_encode($response);
	}
	// ADMIN CRUD SECTION ENDS

	// SCHOOL CRUD SECTION STARTS
	public function create_school()
	{
		// $data['school_id'] = html_escape($this->input->post('school_id'));
		$data['name'] = html_escape($this->input->post('name'));
		$data['phone'] = html_escape($this->input->post('phone'));
		// $data['email'] = html_escape($this->input->post('email'));
		$data['description'] = html_escape($this->input->post('description'));
		$data['address'] = html_escape($this->input->post('address'));
		$data['access'] = html_escape($this->input->post('access'));
		$data['category'] = html_escape($this->input->post('category'));
		$data['status'] = 1;
		// $data['role'] = 'admin';
		// $data['watch_history'] = '[]';

		// check email duplication
		$duplication_status = $this->check_duplication('on_create', $data['name']);
		if($duplication_status){
		$this->db->insert('schools', $data);
		$school_id = $this->db->insert_id();
		if ($_FILES['school_image']['name'] != "") {
			move_uploaded_file($_FILES['school_image']['tmp_name'], 'uploads/schools/' . $school_id . '.jpg');
		}
	    // Data to be inserted
		$data = array(
					array(
						
						'key' => 'stripe_settings',
						'value' => '[{\"stripe_active\":\"yes\",\"stripe_mode\":\"on\",\"stripe_test_secret_key\":\"1234\",\"stripe_test_public_key\":\"1234\",\"stripe_live_secret_key\":\"1234\",\"stripe_live_public_key\":\"1234\",\"stripe_currency\":\"USD\"}]',
						'school_id' => $school_id
					),
					array(
						
						'key' => 'paypal_settings',
						'value' => '[{\"paypal_active\":\"yes\",\"paypal_mode\":\"sandbox\",\"paypal_client_id_sandbox\":\"1234\",\"paypal_client_id_production\":\"1234\",\"paypal_currency\":\"USD\"}]',
						'school_id' => $school_id
					)
				);
		
				// Insert data into the `payment_settings` table
				$this->db->insert_batch('payment_settings', $data);
		
		        // Data to be inserted
				$data = array(
					
					'school_id' => $school_id,
					'system_currency' => 'USD',
					'currency_position' => 'left',
					'language' => 'english',

				);
		
				// Insert data into the `settings` table
				$this->db->insert('settings_school', $data);		

		$response = array(
			'status' => true,
			'notification' => get_phrase('school_added_successfully')
		);
		}else{
			$response = array(
				'status' => false,
				'notification' => get_phrase('sorry_this_name_has_been_taken')
			);
		 }

		return json_encode($response);
	}

	public function update_school($param1 = '')
	{
		$data['name'] = html_escape($this->input->post('name'));
		$data['phone'] = html_escape($this->input->post('phone'));
		// $data['email'] = html_escape($this->input->post('email'));
		$data['description'] = html_escape($this->input->post('description'));
		$data['address'] = html_escape($this->input->post('address'));
		$data['access'] = html_escape($this->input->post('access'));
		$data['category'] = htmlspecialchars_decode($this->input->post('category'));
		// check email duplication
		// $duplication_status = $this->check_duplication('on_update', $data['email'], $param1);
		// if($duplication_status){
		$this->db->where('id', $param1);
		$this->db->update('schools', $data);
		move_uploaded_file($_FILES['school_image']['tmp_name'], 'uploads/schools/' . $param1 . '.jpg');

		$response = array(
			'status' => true,
			'notification' => get_phrase('school_has_been_updated_successfully')
		);

		// }else{
		// 	$response = array(
		// 		'status' => false,
		// 		'notification' => get_phrase('sorry_this_email_has_been_taken')
		// 	);
		// }

		return json_encode($response);
	}

	public function delete_school($param1 = '')
	{
		// $this->db->where('id', $param1);
		$data['Etat'] = 0;
		$this->db->where('id', $param1);
		$this->db->update('schools', $data);
		// $this->db->delete('schools');

		$response = array(
			'status' => true,
			'notification' => get_phrase('school_has_been_deleted_successfully')
		);
		return json_encode($response);
	}
	// school CRUD SECTION ENDS

	//START TEACHER section
	public function create_teacher()
	{
		$data['school_id'] = html_escape($this->input->post('school_id'));
		$data['name'] = html_escape($this->input->post('name'));
		$data['email'] = html_escape($this->input->post('email'));
		$data['password'] = sha1($this->input->post('password'));
		$data['phone'] = html_escape($this->input->post('phone'));
		$data['gender'] = html_escape($this->input->post('gender'));
		$data['address'] = html_escape($this->input->post('address'));
		$data['role'] = 'teacher';
		$data['watch_history'] = '[]';

		// check email duplication
		$duplication_status = $this->check_duplication('on_create', $data['email']);
		if ($duplication_status) {
			$this->db->insert('users', $data);


			$teacher_id = $this->db->insert_id();
			$teacher_table_data['user_id'] = $teacher_id;
			$teacher_table_data['about'] = html_escape($this->input->post('about'));
			$social_links = array(
				'facebook' => $this->input->post('facebook_link'),
				'twitter' => $this->input->post('twitter_link'),
				'linkedin' => $this->input->post('linkedin_link')
			);
			$teacher_table_data['social_links'] = json_encode($social_links);
			$teacher_table_data['department_id'] = html_escape($this->input->post('department'));
			$teacher_table_data['designation'] = html_escape($this->input->post('designation'));
			$teacher_table_data['school_id'] = html_escape($this->input->post('school_id'));
			$teacher_table_data['show_on_website'] = $this->input->post('show_on_website');
			$this->db->insert('teachers', $teacher_table_data);

			if ($_FILES['image_file']['name'] != "") {
				move_uploaded_file($_FILES['image_file']['tmp_name'], 'uploads/users/' . $teacher_id . '.jpg');
			}

			$response = array(
				'status' => true,
				'notification' => get_phrase('teacher_added_successfully')
			);
		} else {
			$response = array(
				'status' => false,
				'notification' => get_phrase('sorry_this_email_has_been_taken')
			);
		}

		return json_encode($response);
	}

	public function update_teacher($param1 = '')
	{
		$data['name'] = html_escape($this->input->post('name'));
		$data['email'] = html_escape($this->input->post('email'));
		$data['phone'] = html_escape($this->input->post('phone'));
		$data['gender'] = html_escape($this->input->post('gender'));
		$data['address'] = html_escape($this->input->post('address'));

		// check email duplication
		$duplication_status = $this->check_duplication('on_update', $data['email'], $param1);
		if ($duplication_status) {
			$this->db->where('id', $param1);
			$this->db->where('school_id', $this->input->post('school_id'));
			$this->db->update('users', $data);

			$teacher_table_data['department_id'] = html_escape($this->input->post('department'));
			$teacher_table_data['designation'] = html_escape($this->input->post('designation'));
			$teacher_table_data['about'] = html_escape($this->input->post('about'));
			$social_links = array(
				'facebook' => $this->input->post('facebook_link'),
				'twitter' => $this->input->post('twitter_link'),
				'linkedin' => $this->input->post('linkedin_link')
			);
			$teacher_table_data['social_links'] = json_encode($social_links);
			$teacher_table_data['show_on_website'] = $this->input->post('show_on_website');
			$this->db->where('school_id', $this->input->post('school_id'));
			$this->db->where('user_id', $param1);
			$this->db->update('teachers', $teacher_table_data);

			if ($_FILES['image_file']['name'] != "") {
				move_uploaded_file($_FILES['image_file']['tmp_name'], 'uploads/users/' . $param1 . '.jpg');
			}

			$response = array(
				'status' => true,
				'notification' => get_phrase('teacher_has_been_updated_successfully')
			);

		} else {
			$response = array(
				'status' => false,
				'notification' => get_phrase('sorry_this_email_has_been_taken')
			);
		}

		return json_encode($response);
	}

	public function delete_teacher($param1 = '', $param2)
	{
		$this->db->where('id', $param1);
		$this->db->delete('users');

		$this->db->where('user_id', $param1);
		$this->db->delete('teachers');

		$this->db->where('teacher_id', $param2);
		$this->db->delete('teacher_permissions');

		$response = array(
			'status' => true,
			'notification' => get_phrase('teacher_has_been_deleted_successfully')
		);
		return json_encode($response);
	}

	public function get_teachers()
	{
		$checker = array(
			'school_id' => $this->school_id,
			'role' => 'teacher'
		);
		return $this->db->get_where('users', $checker);
	}

	public function get_teacher_by_id($teacher_id = "")
	{
		$checker = array(
			'school_id' => $this->school_id,
			'id' => $teacher_id
		);
		$result = $this->db->get_where('teachers', $checker)->row_array();
		return $this->db->get_where('users', array('id' => $result['user_id']));
	}
	//END TEACHER section


	//START TEACHER PERMISSION section
	public function teacher_permission()
	{
		$class_id = html_escape($this->input->post('class_id'));
		$section_id = html_escape($this->input->post('section_id'));
		$teacher_id = html_escape($this->input->post('teacher_id'));
		$column_name = html_escape($this->input->post('column_name'));
		$value = html_escape($this->input->post('value'));

		$check_row = $this->db->get_where('teacher_permissions', array('class_id' => $class_id, 'section_id' => $section_id, 'teacher_id' => $teacher_id));
		if ($check_row->num_rows() > 0) {
			$data[$column_name] = $value;
			$this->db->where('class_id', $class_id);
			$this->db->where('section_id', $section_id);
			$this->db->where('teacher_id', $teacher_id);
			$this->db->update('teacher_permissions', $data);
		} else {
			$data['class_id'] = $class_id;
			$data['section_id'] = $section_id;
			$data['teacher_id'] = $teacher_id;
			$data[$column_name] = 1;
			$this->db->insert('teacher_permissions', $data);
		}
	}
	//END TEACHER PERMISSION section

	//START ACCOUNTANT section
	public function accountant_create()
	{
		$data['name'] = html_escape($this->input->post('name'));
		$data['email'] = html_escape($this->input->post('email'));
		$data['password'] = sha1($this->input->post('password'));
		$data['phone'] = html_escape($this->input->post('phone'));
		$data['gender'] = html_escape($this->input->post('gender'));
		$data['address'] = html_escape($this->input->post('address'));
		$data['school_id'] = $this->school_id;
		$data['role'] = 'accountant';
		$data['watch_history'] = '[]';

		$duplication_status = $this->check_duplication('on_create', $data['email']);
		if ($duplication_status) {
			$this->db->insert('users', $data);

			$response = array(
				'status' => true,
				'notification' => get_phrase('accountant_added_successfully')
			);
		} else {
			$response = array(
				'status' => false,
				'notification' => get_phrase('sorry_this_email_has_been_taken')
			);
		}

		return json_encode($response);
	}

	public function accountant_update($param1 = '')
	{
		$data['name'] = html_escape($this->input->post('name'));
		$data['email'] = html_escape($this->input->post('email'));
		$data['phone'] = html_escape($this->input->post('phone'));
		$data['gender'] = html_escape($this->input->post('gender'));
		$data['address'] = html_escape($this->input->post('address'));

		$duplication_status = $this->check_duplication('on_update', $data['email'], $param1);
		if ($duplication_status) {
			$this->db->where('id', $param1);
			$this->db->update('users', $data);

			$response = array(
				'status' => true,
				'notification' => get_phrase('accountant_has_been_updated_successfully')
			);

		} else {
			$response = array(
				'status' => false,
				'notification' => get_phrase('sorry_this_email_has_been_taken')
			);
		}

		return json_encode($response);

	}

	public function accountant_delete($param1 = '')
	{
		$this->db->where('id', $param1);
		$this->db->delete('users');

		$response = array(
			'status' => true,
			'notification' => get_phrase('accountant_has_been_deleted_successfully')
		);

		return json_encode($response);
	}

	public function get_accountants()
	{
		$checker = array(
			'school_id' => $this->school_id,
			'role' => 'accountant'
		);
		return $this->db->get_where('users', $checker);
	}

	public function get_accountant_by_id($accountant_id = "")
	{
		$checker = array(
			'school_id' => $this->school_id,
			'id' => $accountant_id
		);
		return $this->db->get_where('users', $checker);
	}
	//END ACCOUNTANT section

	//START LIBRARIAN section
	public function librarian_create()
	{
		$data['name'] = html_escape($this->input->post('name'));
		$data['email'] = html_escape($this->input->post('email'));
		$data['password'] = sha1($this->input->post('password'));
		$data['phone'] = html_escape($this->input->post('phone'));
		$data['gender'] = html_escape($this->input->post('gender'));
		$data['address'] = html_escape($this->input->post('address'));
		$data['school_id'] = $this->school_id;
		$data['role'] = 'librarian';
		$data['watch_history'] = '[]';

		// check email duplication
		$duplication_status = $this->check_duplication('on_create', $data['email']);
		if ($duplication_status) {
			$this->db->insert('users', $data);

			$response = array(
				'status' => true,
				'notification' => get_phrase('librarian_added_successfully')
			);
		} else {
			$response = array(
				'status' => false,
				'notification' => get_phrase('sorry_this_email_has_been_taken')
			);
		}

		return json_encode($response);
	}

	public function librarian_update($param1 = '')
	{
		$data['name'] = html_escape($this->input->post('name'));
		$data['email'] = html_escape($this->input->post('email'));
		$data['phone'] = html_escape($this->input->post('phone'));
		$data['gender'] = html_escape($this->input->post('gender'));
		$data['address'] = html_escape($this->input->post('address'));

		// check email duplication
		$duplication_status = $this->check_duplication('on_update', $data['email'], $param1);
		if ($duplication_status) {
			$this->db->where('id', $param1);
			$this->db->update('users', $data);

			$response = array(
				'status' => true,
				'notification' => get_phrase('librarian_updated_successfully')
			);
		} else {
			$response = array(
				'status' => false,
				'notification' => get_phrase('sorry_this_email_has_been_taken')
			);
		}

		return json_encode($response);
	}

	public function librarian_delete($param1 = '')
	{
		$this->db->where('id', $param1);
		$this->db->delete('users');

		$response = array(
			'status' => true,
			'notification' => get_phrase('librarian_deleted_successfully')
		);
		return json_encode($response);
	}


	public function get_librarians()
	{
		$checker = array(
			'school_id' => $this->school_id,
			'role' => 'librarian'
		);
		return $this->db->get_where('users', $checker);
	}

	public function get_librarian_by_id($librarian_id = "")
	{
		$checker = array(
			'school_id' => $this->school_id,
			'id' => $librarian_id
		);
		return $this->db->get_where('users', $checker);
	}
	//END LIBRARIAN section


	//START STUDENT AND ADMISSION section
	public function single_student_create()
	{
		$user_data['name'] = html_escape($this->input->post('name'));
		$user_data['email'] = html_escape($this->input->post('email'));
		// $user_data['password'] = sha1(html_escape($this->input->post('password')));
		$user_data['birthday'] = strtotime(html_escape($this->input->post('birthday')));
		$user_data['gender'] = html_escape($this->input->post('gender'));
		$user_data['address'] = html_escape($this->input->post('address'));
		$user_data['phone'] = html_escape($this->input->post('phone'));
		$user_data['role'] = 'student';
		$user_data['school_id'] = $this->school_id;
		$user_data['watch_history'] = '[]';
		$user_data['status'] = '1';

		// check email duplication
		$duplication_status = $this->check_duplication('on_create', $user_data['email']);
		if ($duplication_status) {
			$this->db->insert('users', $user_data);
			$user_id = $this->db->insert_id();

			$student_data['code'] = student_code();
			$student_data['user_id'] = $user_id;

			$student_data['session'] = $this->active_session;
			$student_data['school_id'] = $this->school_id;
			$this->db->insert('students', $student_data);
			$student_id = $this->db->insert_id();

			$enroll_data['student_id'] = $student_id;
			$enroll_data['class_id'] = html_escape($this->input->post('class_id'));
			$enroll_data['section_id'] = html_escape($this->input->post('section_id'));
			$enroll_data['session'] = $this->active_session;
			$enroll_data['school_id'] = $this->school_id;
			$this->db->insert('enrols', $enroll_data);

			move_uploaded_file($_FILES['student_image']['tmp_name'], 'uploads/users/' . $user_id . '.jpg');
			// Create the reset link
			$reset_link = base_url("login/new_password_student?user_id=" . $user_id);

			$this->email_model->password_send_add_student($reset_link,$user_id);
	

			$this->session->set_flashdata('flash_message', get_phrase('student_added_successfully'));
			$response = array(
				'status' => true,
				'notification' => get_phrase('student_added_successfully')
			);
		} else {

			$this->session->set_flashdata('error_message', get_phrase('sorry_this_email_has_been_taken'));
			$response = array(
				'status' => false,
				'notification' => get_phrase('sorry_this_email_has_been_taken')
			);
		}

		return json_encode($response);
	}

	public function bulk_student_create()
	{
		$duplication_counter = 0;
		$class_id = html_escape($this->input->post('class_id'));
		$section_id = html_escape($this->input->post('section_id'));

		$students_name = html_escape($this->input->post('name'));
		$students_email = html_escape($this->input->post('email'));
		$students_password = html_escape($this->input->post('password'));
		$students_gender = html_escape($this->input->post('gender'));
		$students_parent = html_escape($this->input->post('parent_id'));

		foreach ($students_name as $key => $value):
			// check email duplication
			$duplication_status = $this->check_duplication('on_create', $students_email[$key]);
			if ($duplication_status) {
				$user_data['name'] = $students_name[$key];
				$user_data['email'] = $students_email[$key];
				$user_data['password'] = sha1($students_password[$key]);
				$user_data['gender'] = $students_gender[$key];
				$user_data['role'] = 'student';
				$user_data['school_id'] = $this->school_id;
				$user_data['watch_history'] = '[]';
				$user_data['status'] = '1';
				$this->db->insert('users', $user_data);
				$user_id = $this->db->insert_id();

				$student_data['code'] = student_code();
				$student_data['user_id'] = $user_id;

				$student_data['session'] = $this->active_session;
				$student_data['school_id'] = $this->school_id;
				$this->db->insert('students', $student_data);
				$student_id = $this->db->insert_id();

				$enroll_data['student_id'] = $student_id;
				$enroll_data['class_id'] = $class_id;
				$enroll_data['section_id'] = $section_id;
				$enroll_data['session'] = $this->active_session;
				$enroll_data['school_id'] = $this->school_id;
				$this->db->insert('enrols', $enroll_data);
			} else {
				$duplication_counter++;
			}
		endforeach;

		if ($duplication_counter > 0) {
			$response = array(
				'status' => true,
				'notification' => get_phrase('some_of_the_emails_have_been_taken')
			);
		} else {
			$response = array(
				'status' => true,
				'notification' => get_phrase('students_added_successfully')
			);
		}

		return json_encode($response);
	}

	public function excel_create()
	{
		$class_id = html_escape($this->input->post('class_id'));
		$section_id = html_escape($this->input->post('section_id'));
		$school_id = $this->school_id;
		$session_id = $this->active_session;
		$role = 'student';
		
		$file_name = $_FILES['csv_file']['name'];
		move_uploaded_file($_FILES['csv_file']['tmp_name'], 'uploads/csv_file/student.generate.csv');	
		if (($handle = fopen('uploads/csv_file/student.generate.csv', 'r')) !== FALSE) { // Check the resource is valid
			$count = 0;
			$duplication_counter = 0;
			while (($line = fgets($handle)) !== FALSE) { // Lire chaque ligne en tant que chaîne de caractères
				$all_data = explode(',', $line); // Diviser la ligne en utilisant la virgule comme séparateur
				   
				if ($count > 0) {
					$user_data['name'] = str_replace('"', '', trim($all_data[0]));
					$user_data['email'] = html_escape($all_data[1]);
					$user_data['password'] = sha1(trim($all_data[2]));
					$user_data['phone'] = trim(html_escape($all_data[3]));
					$user_data['gender'] = str_replace('"', '', trim($all_data[4]));
					$user_data['role'] = $role;
					$user_data['school_id'] = $school_id;
					$user_data['watch_history'] = '[]';
					$user_data['status'] = '1';

					// check email duplication
					$duplication_status = $this->check_duplication('on_create', $user_data['email']);
					if ($duplication_status) {
						$this->db->insert('users', $user_data);
						$user_id = $this->db->insert_id();
						$student_data['code'] = student_code();
						$student_data['user_id'] = $user_id;
						// $student_data['parent_id'] = html_escape($all_data[4]);				
						$student_data['session'] = $session_id;
						$student_data['school_id'] = $school_id;
						$this->db->insert('students', $student_data);
						$student_id = $this->db->insert_id();

						$enroll_data['student_id'] = $student_id;
						$enroll_data['class_id'] = $class_id;
						$enroll_data['section_id'] = $section_id;
						$enroll_data['session'] = $session_id;
						$enroll_data['school_id'] = $school_id;
						$this->db->insert('enrols', $enroll_data);
					} else {
						$duplication_counter++;
					}
				}
				$count++;
			}
			fclose($handle);
		}

		if ($duplication_counter > 0) {
			$response = array(
				'status' => true,
				'notification' => get_phrase('some_of_the_emails_have_been_taken')
			);
		} else {
			$response = array(
				'status' => true,
				'notification' => get_phrase('students_added_successfully')
			);
		}

		return json_encode($response);
	}

	public function student_update($student_id = '', $user_id = '')
	{

		$user_data['name'] = html_escape($this->input->post('name'));
		$user_data['email'] = html_escape($this->input->post('email'));
		$user_data['birthday'] = strtotime(html_escape($this->input->post('birthday')));
		$user_data['gender'] = html_escape($this->input->post('gender'));
		$user_data['address'] = html_escape($this->input->post('address'));
		$user_data['phone'] = html_escape($this->input->post('phone'));
		// Check Duplication
		$duplication_status = $this->check_duplication('on_update', $user_data['email'], $user_id);
		if ($duplication_status) {			
			// Supprimer les anciennes classes de l'inscription de l'étudiant
			$this->db->where('student_id', $student_id);
			$this->db->delete('enrols');
			
			// Insérer les nouvelles classes sélectionnées
			$class_ids = $this->input->post('class_id');
			
			if (!empty($class_ids)) {
				foreach ($class_ids as $class_id) {
					$sections = $this->input->post('section_id_'.$class_id);
					$data = array(
						'student_id' => $student_id,
						'class_id' => html_escape($class_id),
						'section_id' => $sections,
						'session' => $this->active_session,
						'school_id' => $this->school_id,
					);
					$this->db->insert('enrols', $data);
				}
			}


			
			// Mettre à jour les données de l'utilisateur
			$this->db->where('id', $user_id);
			$this->db->update('users', $user_data);

			move_uploaded_file($_FILES['student_image']['tmp_name'], 'uploads/users/' . $user_id . '.jpg');

			$response = array(
				'status' => true,
				'notification' => get_phrase('student_updated_successfully')
			);

		} else {
			$response = array(
				'status' => false,
				'notification' => get_phrase('sorry_this_email_has_been_taken')
			);
		}

		return json_encode($response);
	}

	public function delete_student($student_id, $user_id)
	{

		$this->db->where('student_id', $student_id);
		$this->db->delete('enrols');


		// $path = 'uploads/users/' . $user_id . '.jpg';
		// if (file_exists($path)) {
		// 	unlink($path);
		// }

		$response = array(
			'status' => true,
			'notification' => get_phrase('student_deleted_successfully')
		);

		return json_encode($response);
	}

	public function student_enrolment($section_id = "")
	{
		return $this->db->get_where('enrols', array('section_id' => $section_id, 'school_id' => $this->school_id, 'session' => $this->active_session));
	}


	// This function will help to fetch student data by section, class or student id
	public function get_student_details_by_id($type = "", $id = "")
	{
		$enrol_data = array();
		if ($type == "section") {
			$checker = array(
				'section_id' => $id,
				'session' => $this->active_session,
				'school_id' => $this->school_id
			);
			$enrol_data = $this->db->get_where('enrols', $checker)->result_array();
			foreach ($enrol_data as $key => $enrol) {
				$student_details = $this->db->get_where('students', array('id' => $enrol['student_id']))->row_array();
				$enrol_data[$key]['code'] = $student_details['code'];
				$enrol_data[$key]['user_id'] = $student_details['user_id'];

				$user_details = $this->db->get_where('users', array('id' => $student_details['user_id']))->row_array();
				$enrol_data[$key]['name'] = $user_details['name'];
				$enrol_data[$key]['email'] = $user_details['email'];
				$enrol_data[$key]['role'] = $user_details['role'];
				$enrol_data[$key]['address'] = $user_details['address'];
				$enrol_data[$key]['phone'] = $user_details['phone'];
				$enrol_data[$key]['birthday'] = $user_details['birthday'];
				$enrol_data[$key]['gender'] = $user_details['gender'];

				$class_details = $this->crud_model->get_class_details_by_id($enrol['class_id'])->row_array();
				$section_details = $this->crud_model->get_section_details_by_id('section', $enrol['section_id'])->row_array();

				$enrol_data[$key]['class_name'] = $class_details['name'];
				$enrol_data[$key]['section_name'] = $section_details['name'];
			}
		} elseif ($type == "class") {
			$checker = array(
				'class_id' => $id,
				'session' => $this->active_session,
				'school_id' => $this->school_id
			);
			$enrol_data = $this->db->get_where('enrols', $checker)->result_array();
			foreach ($enrol_data as $key => $enrol) {
				$student_details = $this->db->get_where('students', array('id' => $enrol['student_id']))->row_array();
				$enrol_data[$key]['code'] = $student_details['code'];
				$enrol_data[$key]['user_id'] = $student_details['user_id'];

				$user_details = $this->db->get_where('users', array('id' => $student_details['user_id']))->row_array();
				$enrol_data[$key]['name'] = $user_details['name'];
				$enrol_data[$key]['email'] = $user_details['email'];
				$enrol_data[$key]['role'] = $user_details['role'];
				$enrol_data[$key]['address'] = $user_details['address'];
				$enrol_data[$key]['phone'] = $user_details['phone'];
				$enrol_data[$key]['birthday'] = $user_details['birthday'];
				$enrol_data[$key]['gender'] = $user_details['gender'];

				$class_details = $this->crud_model->get_class_details_by_id($enrol['class_id'])->row_array();
				$section_details = $this->crud_model->get_section_details_by_id('section', $enrol['section_id'])->row_array();

				$enrol_data[$key]['class_name'] = $class_details['name'];
				$enrol_data[$key]['section_name'] = $section_details['name'];
			}
		} elseif ($type == "student") {
			$checker = array(
				'student_id' => $id,
				'session' => $this->active_session,
				
			);
			$enrol_data = $this->db->get_where('enrols', $checker)->row_array();
			$student_details = $this->db->get_where('students', array('id' => $id))->row_array();
			$enrol_data['code'] = $student_details['code'];
			$enrol_data['user_id'] = $student_details['user_id'];

			$user_details = $this->db->get_where('users', array('id' => $student_details['user_id']))->row_array();
			$enrol_data['name'] = $user_details['name'];
			$enrol_data['email'] = $user_details['email'];
			$enrol_data['role'] = $user_details['role'];
			$enrol_data['address'] = $user_details['address'];
			$enrol_data['phone'] = $user_details['phone'];
			$enrol_data['birthday'] = $user_details['birthday'];
			$enrol_data['gender'] = $user_details['gender'];

			$class_details = $this->crud_model->get_class_details_by_id($enrol_data['class_id'])->row_array();
			$section_details = $this->crud_model->get_section_details_by_id('section', $enrol_data['section_id'])->row_array();

			$enrol_data['class_name'] = $class_details['name'];
			$enrol_data['section_name'] = $section_details['name'];
		}
		return $enrol_data;
	}
	//END STUDENT AND ADMISSION section


	//STUDENT OF EACH SESSION
	public function get_session_wise_student()
	{
		$checker = array(
			'session' => $this->active_session,
			'school_id' => $this->school_id
		);
		return $this->db->get_where('enrols', $checker);
	}

	// Get User Image Starts
	public function get_user_image($user_id)
	{
		if (file_exists('uploads/users/' . $user_id . '.jpg'))
			return base_url() . 'uploads/users/' . $user_id . '.jpg';
		else
			return base_url() . 'uploads/users/placeholder.jpg';
	}
	// Get User Image Ends

	// Check user duplication
	public function check_duplication($action = "", $email = "", $user_id = "")
	{
		$duplicate_email_check = $this->db->get_where('users', array('email' => $email));

		if ($action == 'on_create') {
			if ($duplicate_email_check->num_rows() > 0) {
				return false;
			} else {
				return true;
			}
		} elseif ($action == 'on_update') {
			if ($duplicate_email_check->num_rows() > 0) {
				if ($duplicate_email_check->row()->id == $user_id) {
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
	}

	// Get School Image Starts
	public function get_school_image($school_id)
	{
		if (file_exists('uploads/schools/' . $school_id . '.jpg'))
			return base_url() . 'uploads/schools/' . $school_id . '.jpg';
		else
			return base_url() . 'uploads/schools/placeholder.jpg';
	}
	// Get School Image Ends

	public function check_duplication_school($action = "", $name = "")
	{
		$duplicate_name_check = $this->db->get_where('schools', array('name' => $name));

		if ($action == 'on_create') {
			if ($duplicate_name_check->num_rows() > 0) {
				return false;
			} else {
				return true;
			}
		}
	}

	public function get_school_count()
	{
		return $this->db->get('schools')->num_rows();
	}

	public function get_schools($limit, $start)
	{
		$result = $this->db->limit($limit, $start)->get_where('schools', array('status' => 1));
		return $result;
	}

	public function get_schools_per_category($category, $limit, $start)
	{
		$result = $this->db->limit($limit, $start)->get_where('schools', array('status' => 1, 'category' => $category));
		return $result;
	}

	public function get_schools_search($input, $limit, $start)
	{
		$this->db->limit($limit, $start);
		$this->db->where('status', 1);
		$this->db->group_start();
		$this->db->like('name', $input);
		$this->db->or_like('description', $input);
		$this->db->or_like('category', $input);
		$this->db->group_end();
		$result = $this->db->get('schools');
		return $result;
	}

	public function get_schools_search_count($input)
	{
		$this->db->where('status', 1);
		$this->db->group_start();
		$this->db->like('name', $input);
		$this->db->or_like('description', $input);
		$this->db->or_like('category', $input);
		$this->db->group_end();
		$result = $this->db->get('schools');
		return $result->num_rows();
	}

	public function get_school_details($school_id = '')
	{
		return $this->db->get_where('schools', array('status' => 1, 'id' => $school_id))->row_array();
	}



	//GET LOGGED IN USER DATA
	public function get_profile_data()
	{
		return $this->db->get_where('users', array('id' => $this->session->userdata('user_id')))->row_array();
	}
	public function approved_school()
	{
		$response = array();
		$school_id = html_escape($this->input->post('school_id'));
		$admin_user = $this->db->get_where('users', array('school_id' => $school_id, 'role' == "admin"))->row_array();

		// Update Admin User Status
		$admin_user['status'] = 1;
		$this->db->where('id', $admin_user['id']);
		$this->db->update('users', $admin_user);
		// return $school_id;
		$data['status'] = 1;
		$this->db->where('id', $school_id);
		$this->db->update('schools', $data);

		$response = array(
			'status' => true,
			'notification' => get_phrase('School_updated_successfully')
		);

		return json_encode($response);
	}
	public function update_profile()
	{
		$response = array();
		$user_id = $this->session->userdata('user_id');
		$data['name'] = htmlspecialchars($this->input->post('name'));
		$data['email'] = htmlspecialchars($this->input->post('email'));
		$data['phone'] = htmlspecialchars($this->input->post('phone'));
		$data['address'] = htmlspecialchars($this->input->post('address'));
		// Check Duplication
		$duplication_status = $this->check_duplication('on_update', $data['email'], $user_id);
		if ($duplication_status) {
			$this->db->where('id', $user_id);
			$this->db->update('users', $data);

			move_uploaded_file($_FILES['profile_image']['tmp_name'], 'uploads/users/' . $user_id . '.jpg');

			$response = array(
				'status' => true,
				'notification' => get_phrase('profile_updated_successfully')
			);
		} else {
			$response = array(
				'status' => false,
				'notification' => get_phrase('sorry_this_email_has_been_taken')
			);
		}

		return json_encode($response);
	}

	public function update_password()
	{
		$user_id = $this->session->userdata('user_id');
		if (!empty($_POST['current_password']) && !empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {
			$user_details = $this->get_user_details($user_id);
			$current_password = $this->input->post('current_password');
			$new_password = $this->input->post('new_password');
			$confirm_password = $this->input->post('confirm_password');
			if ($user_details['password'] == sha1($current_password) && $new_password == $confirm_password) {
				$data['password'] = sha1($new_password);
				$this->db->where('id', $user_id);
				$this->db->update('users', $data);

				$response = array(
					'status' => true,
					'notification' => get_phrase('password_updated_successfully')
				);
			} else {

				$response = array(
					'status' => false,
					'notification' => get_phrase('mismatch_password')
				);
			}
		} else {
			$response = array(
				'status' => false,
				'notification' => get_phrase('password_can_not_be_empty')
			);
		}
		return json_encode($response);
	}

	//GET LOGGED IN USERS CLASS ID AND SECTION ID (FOR STUDENT LOGGED IN VIEW)
	public function get_logged_in_student_details($school_id = null)
	{
		$user_id = $this->session->userdata('user_id');
		// $student_data = $this->db->get_where('students', array('user_id' => $user_id,"school_id" => $school_id))->row_array();
		if ($school_id !== null) {
			$student_data = $this->db->get_where('students', array('user_id' => $user_id, 'school_id' => $school_id))->row_array();
		} else {
			$student_data = $this->db->get_where('students', array('user_id' => $user_id))->row_array();
		}
		$student_details = $this->get_student_details_by_id('student', $student_data['id']);
		return $student_details;
	}

	// GET STUDENT LIST BY PARENT
	public function get_student_list_of_logged_in_parent()
	{
		$parent_id = $this->session->userdata('user_id');
		$parent_data = $this->db->get_where('parents', array('user_id' => $parent_id))->row_array();
		$checker = array(
			'parent_id' => $parent_data['id'],
			'session' => $this->active_session,
			'school_id' => $this->school_id
		);
		$students = $this->db->get_where('students', $checker)->result_array();
		foreach ($students as $key => $student) {
			$checker = array(
				'student_id' => $student['id'],
				'session' => $this->active_session,
				'school_id' => $this->school_id
			);
			$enrol_data = $this->db->get_where('enrols', $checker)->row_array();

			$user_details = $this->db->get_where('users', array('id' => $student['user_id']))->row_array();
			$students[$key]['student_id'] = $student['id'];
			$students[$key]['name'] = $user_details['name'];
			$students[$key]['email'] = $user_details['email'];
			$students[$key]['role'] = $user_details['role'];
			$students[$key]['address'] = $user_details['address'];
			$students[$key]['phone'] = $user_details['phone'];
			$students[$key]['birthday'] = $user_details['birthday'];
			$students[$key]['gender'] = $user_details['gender'];
			$students[$key]['class_id'] = $enrol_data['class_id'];
			$students[$key]['section_id'] = $enrol_data['section_id'];

			$class_details = $this->crud_model->get_class_details_by_id($enrol_data['class_id'])->row_array();
			$section_details = $this->crud_model->get_section_details_by_id('section', $enrol_data['section_id'])->row_array();

			$students[$key]['class_name'] = $class_details['name'];
			$students[$key]['section_name'] = $section_details['name'];
		}
		return $students;
	}

	// In Array for associative array
	function is_in_array($associative_array = array(), $look_up_key = "", $look_up_value = "")
	{
		foreach ($associative_array as $associative) {
			$keys = array_keys($associative);
			for ($i = 0; $i < count($keys); $i++) {
				if ($keys[$i] == $look_up_key) {
					if ($associative[$look_up_key] == $look_up_value) {
						return true;
					}
				}
			}
		}
		return false;
	}

	function get_all_teachers($user_id = "")
	{
		if ($user_id > 0) {
			$this->db->where('id', $user_id);
		}

		$this->db->where('school_id', $this->school_id);
		$this->db->where("(role='superadmin' OR role='admin' OR role='teacher')");
		return $this->db->get_where('users');
	}
	function get_all_users($user_id = "")
	{
		if ($user_id > 0) {
			$this->db->where('id', $user_id);
		}

		$this->db->where('school_id', $this->school_id);
		return $this->db->get_where('users');
	}


	public function get_school_id($school_name)
	{
		return $this->db->get_where('schools', array('name' => $school_name))->row()->id;
	}

	public function googleAPI()
	{ {
			$api = '';
			return $api;
		}
	}


	public function get_school_students_count($school_id)
	{
		return $this->db->get_where(
			'users',
			array(
				'status' => 1,
				'school_id' => $school_id,
				'role' => 'student'
			)
		)->num_rows();
	}

	public function get_school_admin($school_id)
	{
		return $this->db->get_where(
			'users',
			array(
				'school_id' => $school_id,
				'role' => 'admin'
			)
		)->row_array();
	}

	public function get_school_admin_image($school_id)
	{
		$admin = get_school_admin($school_id);

		if (file_exists('uploads/users/' . $admin["id"] . '.jpg'))
			return base_url() . 'uploads/users/' . $admin["id"] . '.jpg';
		else
			return base_url() . 'uploads/schools/placeholder.jpg';
	}


	public function join_school($school_id)
	{
		if ($this->session->userdata('user_id') == null || $this->session->userdata('user_id') == "") {
			$this->session->set_flashdata('error', get_phrase('please_login_before_continuing'));
			if (isset($_SERVER['HTTP_REFERER'])) {
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			}
		} else
			$user_id = $this->session->userdata('user_id');

		if ($school_id == null || $school_id == "") {

			$this->session->set_flashdata('error', get_phrase('no_school_found'));
			if (isset($_SERVER['HTTP_REFERER'])) {
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			}
		} else {

			if ($this->db->get_where('students', array('user_id' => $user_id, 'school_id' => $school_id))->num_rows() > 0) {
				$this->session->set_flashdata('success', get_phrase('already_joined_school'));
				if (isset($_SERVER['HTTP_REFERER'])) {
					redirect($_SERVER['HTTP_REFERER'], 'refresh');
				}
			} else {
				$student_row = $this->db->get_where('students', array('user_id' => $user_id))->row_array();
				$student_code = !empty($student_row) ? $student_row['code'] : student_code();
				
				$data['school_id'] = $school_id;
				$data['user_id'] = $user_id;
				$data['code'] = $student_code;
				$data['session'] = $this->active_session;

				$query = $this->db->get_where('schools', array('id' => $school_id));
				if ($query->num_rows() > 0) {
					$row = $query->row();
					if ($row->access == 1) {
						$data['status'] = 1; //Public
					} else {
						$data['status'] = 0; //Private
					}
				}

				$this->db->insert('students', $data);
				$user_email = $this->db->get_where('users', array('id' => $user_id))->row('email');
				$user_name = $this->db->get_where('users', array('id' => $user_id))->row('name');
				$this->db->where('school_id', $school_id);
				$this->db->where_in('role', array('admin', 'superadmin'));
				$user_email_admin = $this->db->get('users')->row('email');
				$email_ex = "soukaina.atif1949@gmail.com";
				
				$this->email_model->join_student_email($user_email,$user_name, $data['code'],$row ->name,$school_id);
				$this->email_model->join_student_email_for_admin($email_ex,$user_name, $data['code'],$row ->name,$school_id);
				

				if (isset($_SERVER['HTTP_REFERER'])) {
					redirect($_SERVER['HTTP_REFERER'], 'refresh');
				}
			}
		}
	}



	public function check_student_status($school_id)
	{
		$user_id = $this->session->userdata('user_id');
		$student = $this->db->get_where('students', array('user_id' => $user_id, 'school_id' => $school_id));

		if ($student->num_rows() == 0) {
			return -1;
		}

		$student_data = $student->row_array();

		if ($student_data['status'] == 1) {
			return 1;
		} else if ($student_data['status'] == 0) {
			return 0;
		}
	}


 public function register_user()
{
    $emailPattern = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';

    if ($this->input->post('register_email') == '' || !preg_match($emailPattern, $this->input->post('register_email')) || $this->input->post('register_password') == '' || $this->input->post('register_first_name') == '' || $this->input->post('register_last_name') == '' || $this->input->post('register_date_of_birth') == '' || $this->input->post('register_repeat_password') == '') {

        $this->session->set_flashdata('error', get_phrase('validation_error'));
        if (isset($_SERVER['HTTP_REFERER'])) {
            redirect($_SERVER['HTTP_REFERER'], 'refresh');
        }

    } else if ($this->db->get_where('users', array('email' => $this->input->post('register_email')))->num_rows() > 0) {

        $this->session->set_flashdata('error', get_phrase('email_already_exists'));
        if (isset($_SERVER['HTTP_REFERER'])) {
            redirect($_SERVER['HTTP_REFERER'], 'refresh');
        }

    } else {

        $data['name'] = htmlspecialchars($this->input->post('register_first_name') . ' ' . $this->input->post('register_last_name'));
        $data['email'] = htmlspecialchars($this->input->post('register_email'));
        $data['birthday'] = htmlspecialchars($this->input->post('register_date_of_birth'));
        $data['gender'] = htmlspecialchars($this->input->post('register_gender'));
        $data['password'] = sha1($this->input->post('register_password'));
        $data['role'] = 'student';
        $data['status'] = 1;
        $data['school_id'] = 1;
        $data['watch_history'] = '[]';

        $this->db->insert('users', $data);
        $user_id = $this->db->insert_id();


        if (isset($_FILES['student_image_upload']) && $_FILES['student_image_upload']['error'] == UPLOAD_ERR_OK) {
            $upload_path = 'uploads/users/' . $user_id . '.jpg';
            move_uploaded_file($_FILES['student_image_upload']['tmp_name'], $upload_path);
        }
		$this->email_model->Add_online_admission($data['email'], $user_id,$data['name']);
		$this->session->set_userdata('user_login_type', true);
        $this->session->set_userdata('student_login', true);
        $this->session->set_userdata('user_id', $user_id);
        $this->session->set_userdata('school_id', 1);
        $this->session->set_userdata('user_name', $data['name']);
        $this->session->set_userdata('user_type', 'student');
        $this->session->set_flashdata('success', get_phrase('registration_successful'));
    }

    if (isset($_SERVER['HTTP_REFERER'])) {
        redirect($_SERVER['HTTP_REFERER'], 'refresh');
    }
}



public function register_user_form()
{
    $emailPattern = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';

    if ($this->input->post('student_email') == '' || !preg_match($emailPattern, $this->input->post('student_email')) || $this->input->post('password-student') == '' || $this->input->post('first_name') == '' || $this->input->post('last_name') == '' || $this->input->post('date_of_birth') == '' || $this->input->post('repeat-password-student') == '') {

        $this->session->set_flashdata('error', get_phrase('validation_error'));
        if (isset($_SERVER['HTTP_REFERER'])) {
            redirect($_SERVER['HTTP_REFERER'], 'refresh');
        }

    } else if ($this->db->get_where('users', array('email' => $this->input->post('register_email')))->num_rows() > 0) {

        $this->session->set_flashdata('error', get_phrase('email_already_exists'));
        if (isset($_SERVER['HTTP_REFERER'])) {
            redirect($_SERVER['HTTP_REFERER'], 'refresh');
        }

    } else {

        $data['name'] = htmlspecialchars($this->input->post('first_name') . ' ' . $this->input->post('last_name'));
        $data['email'] = htmlspecialchars($this->input->post('student_email'));
        $data['birthday'] = htmlspecialchars($this->input->post('date_of_birth'));
        $data['gender'] = htmlspecialchars($this->input->post('gender'));
        $data['password'] = sha1($this->input->post('password-student'));
		if($this->input->post('phone') != ''){
			$data['phone'] = htmlspecialchars($this->input->post('phone'));
		}
		$data["address"] = htmlspecialchars($this->input->post('address'));
        $data['role'] = 'student';
        $data['status'] = 1;
        $data['school_id'] = 1;
        $data['watch_history'] = '[]';

        $this->db->insert('users', $data);
        $user_id = $this->db->insert_id();


        if (isset($_FILES['student_image']) && $_FILES['student_image']['error'] == UPLOAD_ERR_OK) {
		
            $upload_path = 'uploads/users/' . $user_id . '.jpg';
            move_uploaded_file($_FILES['student_image']['tmp_name'], $upload_path);
        }
		
		$this->email_model->Add_online_admission($data['email'], $user_id,$data['name']);
		$this->session->set_userdata('user_login_type', true);
        $this->session->set_userdata('student_login', true);
        $this->session->set_userdata('user_id', $user_id);
        $this->session->set_userdata('school_id', 1);
        $this->session->set_userdata('user_name', $data['name']);
        $this->session->set_userdata('user_type', 'student');
        $this->session->set_flashdata('success', get_phrase('registration_successful'));
    }

    if (isset($_SERVER['HTTP_REFERER'])) {
        redirect($_SERVER['HTTP_REFERER'], 'refresh');
    }
}

}