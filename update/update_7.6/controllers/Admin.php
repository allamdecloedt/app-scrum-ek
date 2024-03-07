<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
*  @author   : Creativeitem
*  date      : November, 2019
*  Ekattor School Management System With Addons
*  http://codecanyon.net/user/Creativeitem
*  http://support.creativeitem.com
*/

class Admin extends CI_Controller
{

	public function __construct()
	{

		parent::__construct();

		$this->load->database();
		$this->load->library('session');

		/*LOADING ALL THE MODELS HERE*/
		$this->load->model('Crud_model',     'crud_model');
		$this->load->model('User_model',     'user_model');
		$this->load->model('Settings_model', 'settings_model');
		$this->load->model('Payment_model',  'payment_model');
		$this->load->model('Email_model',    'email_model');
		$this->load->model('Addon_model',    'addon_model');
		$this->load->model('Frontend_model', 'frontend_model');
		$this->load->model('Driver_model', 'driver_model');

		/*cache control*/
		$this->output->set_header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
		$this->output->set_header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		$this->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
		$this->output->set_header("Pragma: no-cache");

		/*SET DEFAULT TIMEZONE*/
		timezone();

		/*LOAD EXTERNAL LIBRARIES*/
		$this->load->library('pdf');

		if ($this->session->userdata('admin_login') != 1) {
			redirect(site_url('login'), 'refresh');
		}
	}
	//dashboard
	public function index()
	{
		redirect(route('dashboard'), 'refresh');
	}

	public function dashboard()
	{

		// $this->msg91_model->clickatell();
		$page_data['page_title'] = 'Dashboard';
		$page_data['folder_name'] = 'dashboard';
		$this->load->view('backend/index', $page_data);
	}

	//START CLASS secion
	public function manage_class($param1 = '', $param2 = '', $param3 = '')
	{

		if ($param1 == 'create') {
			$response = $this->crud_model->class_create();
			echo $response;
		}

		if ($param1 == 'delete') {
			$response = $this->crud_model->class_delete($param2);
			echo $response;
		}

		if ($param1 == 'update') {
			$response = $this->crud_model->class_update($param2);
			echo $response;
		}

		if ($param1 == 'section') {
			$response = $this->crud_model->section_update($param2);
			echo $response;
		}

		// show data from database
		if ($param1 == 'list') {
			$this->load->view('backend/admin/class/list');
		}

		if (empty($param1)) {
			$page_data['folder_name'] = 'class';
			$page_data['page_title'] = 'class';
			$this->load->view('backend/index', $page_data);
		}
	}
	//END CLASS section

	//	SECTION STARTED
	public function section($action = "", $id = "")
	{

		// PROVIDE A LIST OF SECTION ACCORDING TO CLASS ID
		if ($action == 'list') {
			$page_data['class_id'] = $id;
			$this->load->view('backend/admin/section/list', $page_data);
		}
	}
	//	SECTION ENDED

	//START CLASS_ROOM section
	public function class_room($param1 = '', $param2 = '')
	{

		if ($param1 == 'create') {
			$response = $this->crud_model->class_room_create();
			echo $response;
		}

		if ($param1 == 'update') {
			$response = $this->crud_model->class_room_update($param2);
			echo $response;
		}

		if ($param1 == 'delete') {
			$response = $this->crud_model->class_room_delete($param2);
			echo $response;
		}

		// PROVIDE A LIST OF SECTION ACCORDING TO CLASS ID
		if ($param1 == 'list') {
			$this->load->view('backend/admin/class_room/list');
		}

		if (empty($param1)) {
			$page_data['folder_name'] = 'class_room';
			$page_data['page_title'] = 'class_room';
			$this->load->view('backend/index', $page_data);
		}
	}
	//END CLASS_ROOM section

	//START SUBJECT section
	public function subject($param1 = '', $param2 = '')
	{

		if ($param1 == 'create') {
			$response = $this->crud_model->subject_create();
			echo $response;
		}

		if ($param1 == 'update') {
			$response = $this->crud_model->subject_update($param2);
			echo $response;
		}

		if ($param1 == 'delete') {
			$response = $this->crud_model->subject_delete($param2);
			echo $response;
		}

		if ($param1 == 'list') {
			$page_data['class_id'] = $param2;
			$this->load->view('backend/admin/subject/list', $page_data);
		}

		if (empty($param1)) {
			$page_data['folder_name'] = 'subject';
			$page_data['page_title'] = 'subject';
			$this->load->view('backend/index', $page_data);
		}
	}

	public function class_wise_subject($class_id)
	{

		// PROVIDE A LIST OF SUBJECT ACCORDING TO CLASS ID
		$page_data['class_id'] = $class_id;
		$this->load->view('backend/admin/subject/dropdown', $page_data);
	}
	//END SUBJECT section


	//START DEPARTMENT section
	public function department($param1 = '', $param2 = '')
	{

		if ($param1 == 'create') {
			$response = $this->crud_model->department_create();
			echo $response;
		}

		if ($param1 == 'update') {
			$response = $this->crud_model->department_update($param2);
			echo $response;
		}

		if ($param1 == 'delete') {
			$response = $this->crud_model->department_delete($param2);
			echo $response;
		}

		// Get the data from database
		if ($param1 == 'list') {
			$this->load->view('backend/admin/department/list');
		}

		if (empty($param1)) {
			$page_data['folder_name'] = 'department';
			$page_data['page_title'] = 'department';
			$this->load->view('backend/index', $page_data);
		}
	}
	//END DEPARTMENT section


	//START SYLLABUS section
	public function syllabus($param1 = '', $param2 = '', $param3 = '')
	{

		if ($param1 == 'create') {
			$response = $this->crud_model->syllabus_create();
			echo $response;
		}

		if ($param1 == 'delete') {
			$response = $this->crud_model->syllabus_delete($param2);
			echo $response;
		}

		if ($param1 == 'list') {
			$page_data['class_id'] = $param2;
			$page_data['section_id'] = $param3;
			$this->load->view('backend/admin/syllabus/list', $page_data);
		}

		if (empty($param1)) {
			$page_data['folder_name'] = 'syllabus';
			$page_data['page_title'] = 'syllabus';
			$this->load->view('backend/index', $page_data);
		}
	}
	//END SYLLABUS section

	//START TEACHER section
	public function teacher($param1 = '', $param2 = '', $param3 = '')
	{


		if ($param1 == 'create') {
			$response = $this->user_model->create_teacher();
			echo $response;
		}

		if ($param1 == 'update') {
			$response = $this->user_model->update_teacher($param2);
			echo $response;
		}

		if ($param1 == 'delete') {
			$teacher_id = $this->db->get_where('teachers', array('user_id' => $param2))->row('id');
			$response = $this->user_model->delete_teacher($param2, $teacher_id);
			echo $response;
		}

		if ($param1 == 'list') {
			$this->load->view('backend/admin/teacher/list');
		}

		if (empty($param1)) {
			$page_data['folder_name'] = 'teacher';
			$page_data['page_title'] = 'techers';
			$this->load->view('backend/index', $page_data);
		}
	}
	//END TEACHER section



	//START TEACHER PERMISSION section
	public function permission($param1 = '', $param2 = '', $param3 = '')
	{

		if ($param1 == 'filter') {
			$page_data['class_id'] = $param2;
			$page_data['section_id'] = $param3;
			$this->load->view('backend/admin/permission/list', $page_data);
		}

		if ($param1 == 'modify_permission') {
			$page_data['class_id'] = htmlspecialchars($this->input->post('class_id'));
			$page_data['section_id'] = htmlspecialchars($this->input->post('section_id'));
			$this->user_model->teacher_permission();
			$this->load->view('backend/admin/permission/list', $page_data);
		}

		if (empty($param1)) {
			$page_data['folder_name'] = 'permission';
			$page_data['page_title'] = 'teacher_permissions';
			$this->load->view('backend/index', $page_data);
		}
	}
	//END TEACHER PERMISSION section


	//START PARENT section
	public function parent($param1 = '', $param2 = '')
	{

		if ($param1 == 'create') {
			$response = $this->user_model->parent_create();
			echo $response;
		}

		if ($param1 == 'update') {
			$response = $this->user_model->parent_update($param2);
			echo $response;
		}

		if ($param1 == 'delete') {
			$response = $this->user_model->parent_delete($param2);
			echo $response;
		}

		// show data from database
		if ($param1 == 'list') {
			$this->load->view('backend/admin/parent/list');
		}

		if (empty($param1)) {
			$page_data['folder_name'] = 'parent';
			$page_data['page_title'] = 'parent';
			$this->load->view('backend/index', $page_data);
		}
	}
	//END PARENT section


	//START ACCOUNTANT section
	public function accountant($param1 = '', $param2 = '')
	{

		if ($param1 == 'create') {
			$response = $this->user_model->accountant_create();
			echo $response;
		}

		if ($param1 == 'update') {
			$response = $this->user_model->accountant_update($param2);
			echo $response;
		}

		if ($param1 == 'delete') {
			$response = $this->user_model->accountant_delete($param2);
			echo $response;
		}

		// show data from database
		if ($param1 == 'list') {
			$this->load->view('backend/admin/accountant/list');
		}

		if (empty($param1)) {
			$page_data['folder_name'] = 'accountant';
			$page_data['page_title'] = 'accountant';
			$this->load->view('backend/index', $page_data);
		}
	}
	//END ACCOUNTANT section


	//START LIBRARIAN section
	public function librarian($param1 = '', $param2 = '')
	{

		if ($param1 == 'create') {
			$response = $this->user_model->librarian_create();
			echo $response;
		}

		if ($param1 == 'update') {
			$response = $this->user_model->librarian_update($param2);
			echo $response;
		}

		if ($param1 == 'delete') {
			$response = $this->user_model->librarian_delete($param2);
			echo $response;
		}

		// show data from database
		if ($param1 == 'list') {
			$this->load->view('backend/admin/librarian/list');
		}

		if (empty($param1)) {
			$page_data['folder_name'] = 'librarian';
			$page_data['page_title'] = 'librarian';
			$this->load->view('backend/index', $page_data);
		}
	}
	//END LIBRARIAN section

	//START CLASS ROUTINE section
	public function routine($param1 = '', $param2 = '', $param3 = '', $param4 = '')
	{

		if ($param1 == 'create') {
			$response = $this->crud_model->routine_create();
			echo $response;
		}

		if ($param1 == 'update') {
			$response = $this->crud_model->routine_update($param2);
			echo $response;
		}

		if ($param1 == 'delete') {
			$response = $this->crud_model->routine_delete($param2);
			echo $response;
		}

		if ($param1 == 'filter') {
			$page_data['class_id'] = $param2;
			$page_data['section_id'] = $param3;
			$this->load->view('backend/admin/routine/list', $page_data);
		}

		if (empty($param1)) {
			$page_data['folder_name'] = 'routine';
			$page_data['page_title'] = 'routine';
			$this->load->view('backend/index', $page_data);
		}
	}
	//END CLASS ROUTINE section


	//START DAILY ATTENDANCE section
	public function attendance($param1 = '', $param2 = '', $param3 = '')
	{

		if ($param1 == 'take_attendance') {
			$response = $this->crud_model->take_attendance();
			echo $response;
		}

		if ($param1 == 'filter') {
			$date = '01 ' . $this->input->post('month') . ' ' . $this->input->post('year');
			$page_data['attendance_date'] = strtotime($date);
			$page_data['class_id'] = $this->input->post('class_id');
			$page_data['section_id'] = $this->input->post('section_id');
			$page_data['month'] = $this->input->post('month');
			$page_data['year'] = $this->input->post('year');
			$this->load->view('backend/admin/attendance/list', $page_data);
		}

		if ($param1 == 'student') {
			$page_data['attendance_date'] = strtotime($this->input->post('date'));
			$page_data['class_id'] = htmlspecialchars($this->input->post('class_id'));
			$page_data['section_id'] = htmlspecialchars($this->input->post('section_id'));
			$this->load->view('backend/admin/attendance/student', $page_data);
		}

		if (empty($param1)) {
			$page_data['folder_name'] = 'attendance';
			$page_data['page_title'] = 'attendance';
			$this->load->view('backend/index', $page_data);
		}
	}
	//END DAILY ATTENDANCE section


	//START EVENT CALENDAR section
	public function event_calendar($param1 = '', $param2 = '')
	{

		if ($param1 == 'create') {
			$response = $this->crud_model->event_calendar_create();
			echo $response;
		}

		if ($param1 == 'update') {
			$response = $this->crud_model->event_calendar_update($param2);
			echo $response;
		}

		if ($param1 == 'delete') {
			$response = $this->crud_model->event_calendar_delete($param2);
			echo $response;
		}

		if ($param1 == 'all_events') {
			echo $this->crud_model->all_events();
		}

		if ($param1 == 'list') {
			$this->load->view('backend/admin/event_calendar/list');
		}

		if (empty($param1)) {
			$page_data['folder_name'] = 'event_calendar';
			$page_data['page_title'] = 'event_calendar';
			$this->load->view('backend/index', $page_data);
		}
	}
	//END EVENT CALENDAR section



	//START STUDENT ADN ADMISSION section
	public function student($param1 = '', $param2 = '', $param3 = '', $param4 = '', $param5 = '')
	{

		$page_data['class_id'] = '';
		$page_data['section_id'] = '';

		if ($param1 == 'create') {
			//form view
			if ($param2 == 'bulk') {
				$page_data['aria_expand'] = 'bulk';
				$page_data['working_page'] = 'create';
				$page_data['folder_name'] = 'student';
				$page_data['page_title'] = 'add_student';
				$this->load->view('backend/index', $page_data);
			} elseif ($param2 == 'excel') {
				$page_data['aria_expand'] = 'excel';
				$page_data['working_page'] = 'create';
				$page_data['folder_name'] = 'student';
				$page_data['page_title'] = 'add_student';
				$this->load->view('backend/index', $page_data);
			} else {
				$page_data['aria_expand'] = 'single';
				$page_data['working_page'] = 'create';
				$page_data['folder_name'] = 'student';
				$page_data['page_title'] = 'add_student';
				$this->load->view('backend/index', $page_data);
			}
		}

		//create to database
		if ($param1 == 'create_single_student') {
			$response = $this->user_model->single_student_create();
			$page_data['class_id'] = html_escape($this->input->post('class_id'));
			$page_data['section_id'] = html_escape($this->input->post('section_id'));
			$page_data['working_page'] = 'filter';
			$page_data['folder_name'] = 'student';
			$page_data['page_title'] = 'student_list';
			$this->load->view('backend/index', $page_data);
		}

		if ($param1 == 'create_bulk_student') {
			$response = $this->user_model->bulk_student_create();
			echo $response;
		}

		if ($param1 == 'create_excel') {
			$response = $this->user_model->excel_create();
			echo $response;
		}

		// form view
		if ($param1 == 'edit') {
			$page_data['student_id'] = $param2;
			$page_data['working_page'] = 'edit';
			$page_data['folder_name'] = 'student';
			$page_data['page_title'] = 'update_student_information';
			$this->load->view('backend/index', $page_data);
		}

		if ($param1 == 'status') {
			$this->db->where('id', $param3);
			$this->db->update('users', array('status' => $param4));
			$response = array(
				'status' => true,
				'notification' => get_phrase('status_has_been_updated')
			);
			echo json_encode($response);
		}

		//updated to database
		if ($param1 == 'updated') {
			$response = $this->user_model->student_update($param2, $param3);
			echo $response;
		}
		//updated to database
		if ($param1 == 'id_card') {
			$page_data['student_id'] = $param2;
			$page_data['folder_name'] = 'student';
			$page_data['page_title'] = 'identity_card';
			$page_data['page_name'] = 'id_card';
			$this->load->view('backend/index', $page_data);
		}

		if ($param1 == 'delete') {
			$response = $this->user_model->delete_student($param2, $param3);
			echo $response;
		}

		if ($param1 == 'filter') {
			$page_data['class_id'] = $param2;
			$page_data['section_id'] = $param3;
			$this->load->view('backend/admin/student/list', $page_data);
		}

		if (empty($param1)) {
			$page_data['working_page'] = 'filter';
			$page_data['folder_name'] = 'student';
			$page_data['page_title'] = 'student_list';
			$this->load->view('backend/index', $page_data);
		}
	}
	//END STUDENT ADN ADMISSION section


	//START EXAM section
	public function exam($param1 = '', $param2 = '')
	{

		if ($param1 == 'create') {
			$response = $this->crud_model->exam_create();
			echo $response;
		}

		if ($param1 == 'update') {
			$response = $this->crud_model->exam_update($param2);
			echo $response;
		}

		if ($param1 == 'delete') {
			$response = $this->crud_model->exam_delete($param2);
			echo $response;
		}

		if ($param1 == 'list') {
			$this->load->view('backend/admin/exam/list');
		}

		if (empty($param1)) {
			$page_data['folder_name'] = 'exam';
			$page_data['page_title'] = 'exam';
			$this->load->view('backend/index', $page_data);
		}
	}
	//END EXAM section

	//START MARKS section
	public function mark($param1 = '', $param2 = '')
	{

		if ($param1 == 'list') {
			$page_data['class_id'] = htmlspecialchars($this->input->post('class_id'));
			$page_data['section_id'] = htmlspecialchars($this->input->post('section_id'));
			$page_data['subject_id'] = htmlspecialchars($this->input->post('subject'));
			$page_data['exam_id'] = htmlspecialchars($this->input->post('exam'));
			$this->crud_model->mark_insert($page_data['class_id'], $page_data['section_id'], $page_data['subject_id'], $page_data['exam_id']);
			$this->load->view('backend/admin/mark/list', $page_data);
		}

		if ($param1 == 'mark_update') {
			$this->crud_model->mark_update();
		}

		if (empty($param1)) {
			$page_data['folder_name'] = 'mark';
			$page_data['page_title'] = 'marks';
			$this->load->view('backend/index', $page_data);
		}
	}

	// GET THE GRADE ACCORDING TO MARK
	public function get_grade($acquired_mark)
	{
		echo get_grade($acquired_mark);
	}
	//END MARKS sesction

	// GRADE SECTION STARTS
	public function grade($param1 = "", $param2 = "")
	{

		// store data on database
		if ($param1 == 'create') {
			$response = $this->crud_model->grade_create();
			echo $response;
		}

		// update data on database
		if ($param1 == 'update') {
			$response = $this->crud_model->grade_update($param2);
			echo $response;
		}

		// delelte data from database
		if ($param1 == 'delete') {
			$response = $this->crud_model->grade_delete($param2);
			echo $response;
		}

		// show data from database
		if ($param1 == 'list') {
			$this->load->view('backend/admin/grade/list');
		}

		// showing the index file
		if (empty($param1)) {
			$page_data['folder_name'] = 'grade';
			$page_data['page_title'] = 'grades';
			$this->load->view('backend/index', $page_data);
		}
	}
	// GRADE SECTION ENDS

	// STUDENT PROMOTION SECTION STARTS
	function promotion($param1 = "", $promotion_data = "")
	{

		// Promote students. Here promotion_data contains all the data of a student to promote
		if ($param1 == 'promote') {
			$response = $this->crud_model->promote_student($promotion_data);
			echo $response;
		}
		//showing the list of student to promote
		if ($param1 == 'list') {
			$page_data['session_from'] = htmlspecialchars($this->input->post('session_from'));
			$page_data['session_to'] = htmlspecialchars($this->input->post('session_to'));
			$page_data['class_id_from'] = htmlspecialchars($this->input->post('class_id_from'));
			$page_data['class_id_to'] = htmlspecialchars($this->input->post('class_id_to'));
			$page_data['class_from_details'] = $this->crud_model->get_classes($this->input->post('class_id_from'))->row_array();
			$page_data['class_to_details'] = $this->crud_model->get_classes($this->input->post('class_id_to'))->row_array();
			$page_data['session_from_details'] = $this->crud_model->get_session($this->input->post('session_from'))->row_array();
			$page_data['session_to_details'] = $this->crud_model->get_session($this->input->post('session_to'))->row_array();
			$page_data['enrolments'] = $this->crud_model->get_student_list()->result_array();
			$this->load->view('backend/admin/promotion/list', $page_data);
		}
		// showing the index file
		if (empty($param1)) {
			$page_data['folder_name'] = 'promotion';
			$page_data['page_title'] = 'student_promotion';
			$this->load->view('backend/index', $page_data);
		}
	}
	// STUDENT PROMOTION SECTION ENDS

	// ACCOUNTING SECTION STARTS
	public function invoice($param1 = "", $param2 = "")
	{
		// For creating new invoice
		if ($param1 == 'single') {
			$response = $this->crud_model->create_single_invoice();
			echo $response;
		}

		// For creating new mass invoice
		if ($param1 == 'mass') {
			$response = $this->crud_model->create_mass_invoice();
			echo $response;
		}

		// For editing invoice
		if ($param1 == 'update') {
			$response = $this->crud_model->update_invoice($param2);
			echo $response;
		}

		// For deleting invoice
		if ($param1 == 'delete') {
			$response = $this->crud_model->delete_invoice($param2);
			echo $response;
		}

		// Get the list of student. Here param2 defines classId
		if ($param1 == 'student') {
			$page_data['enrolments'] = $this->user_model->get_student_details_by_id('class', $param2);
			$this->load->view('backend/admin/student/dropdown', $page_data);
		}

		// showing the list of invoices
		if ($param1 == 'invoice') {
			$page_data['invoice_id'] = $param2;
			$page_data['folder_name'] = 'invoice';
			$page_data['page_name'] = 'invoice';
			$page_data['page_title']  = 'invoice';
			$this->load->view('backend/index', $page_data);
		}

		// showing the list of invoices
		if ($param1 == 'list') {
			$date = explode('-', $this->input->get('date'));
			$page_data['date_from'] = strtotime($date[0] . ' 00:00:00');
			$page_data['date_to']   = strtotime($date[1] . ' 23:59:59');
			$page_data['selected_class'] = htmlspecialchars($this->input->get('selectedClass'));
			$page_data['selected_status'] = htmlspecialchars($this->input->get('selectedStatus'));
			$this->load->view('backend/admin/invoice/list', $page_data);
		}
		// showing the index file
		if (empty($param1)) {
			$page_data['folder_name'] = 'invoice';
			$page_data['page_title']  = 'invoice';
			$first_day_of_month = "1 " . date("M") . " " . date("Y") . ' 00:00:00';
			$last_day_of_month = date("t") . " " . date("M") . " " . date("Y") . ' 23:59:59';
			$page_data['date_from']   = strtotime($first_day_of_month);
			$page_data['date_to']     = strtotime($last_day_of_month);
			$page_data['selected_class'] = 'all';
			$page_data['selected_status'] = 'all';
			$this->load->view('backend/index', $page_data);
		}
	}

	//EXPORT STUDENT FEES
	public function export($param1 = "", $date_from = "", $date_to = "", $selected_class = "", $selected_status = "")
	{
		//RETURN EXPORT URL
		if ($param1 == 'url') {
			$type = htmlspecialchars($this->input->post('type'));
			$date = explode('-', $this->input->post('dateRange'));
			$date_from = strtotime($date[0] . ' 00:00:00');
			$date_to   = strtotime($date[1] . ' 23:59:59');
			$selected_class = htmlspecialchars($this->input->post('selectedClass'));
			$selected_status = htmlspecialchars($this->input->post('selectedStatus'));
			echo route('export/' . $type . '/' . $date_from . '/' . $date_to . '/' . $selected_class . '/' . $selected_status);
		}
		// EXPORT AS PDF
		if ($param1 == 'pdf' || $param1 == 'print') {
			$page_data['action']   = $param1;
			$page_data['date_from']   = $date_from;
			$page_data['date_to']     = $date_to;
			$page_data['selected_class'] = $selected_class;
			$page_data['selected_status'] = $selected_status;
			$html = $this->load->view('backend/admin/invoice/export', $page_data, true);

			$this->pdf->loadHtml($html);
			$this->pdf->set_paper("a4", "landscape");
			$this->pdf->render();

			// FILE DOWNLOADING CODES
			if ($selected_status == 'all') {
				$paymentStatusForTitle = 'paid-and-unpaid';
			} else {
				$paymentStatusForTitle = $selected_status;
			}
			if ($selected_class == 'all') {
				$classNameForTitle = 'all_class';
			} else {
				$class_details = $this->crud_model->get_classes($selected_class)->row_array();
				$classNameForTitle = $class_details['name'];
			}
			$fileName = 'Student_fees-' . date('d-M-Y', $date_from) . '-to-' . date('d-M-Y', $date_to) . '-' . $classNameForTitle . '-' . $paymentStatusForTitle . '.pdf';

			if ($param1 == 'pdf') {
				$this->pdf->stream($fileName, array("Attachment" => 1));
			} else {
				$this->pdf->stream($fileName, array("Attachment" => 0));
			}
		}
		// EXPORT AS CSV
		if ($param1 == 'csv') {
			$date_from   = $date_from;
			$date_to     = $date_to;
			$selected_class = $selected_class;
			$selected_status = $selected_status;

			$invoices = $this->crud_model->get_invoice_by_date_range($date_from, $date_to, $selected_class, $selected_status)->result_array();
			$csv_file = fopen("assets/csv_file/invoices.csv", "w");
			$header = array('Invoice-no', 'Student', 'Class', 'Invoice-Title', 'Total-Amount', 'Paid-Amount', 'Creation-Date', 'Payment-Date', 'Status');
			fputcsv($csv_file, $header);
			foreach ($invoices as $invoice) {
				$student_details = $this->user_model->get_student_details_by_id('student', $invoice['student_id']);
				$class_details = $this->crud_model->get_class_details_by_id($invoice['class_id'])->row_array();
				if ($invoice['updated_at'] > 0) {
					$payment_date = date('d-M-Y', $invoice['updated_at']);
				} else {
					$payment_date = get_phrase('not_found');
				}
				$lines = array(sprintf('%08d', $invoice['id']), $student_details['name'], $class_details['name'], $invoice['title'], currency($invoice['total_amount']), currency($invoice['paid_amount']), date('d-M-Y', $invoice['created_at']), $payment_date, ucfirst($invoice['status']));
				fputcsv($csv_file, $lines);
			}

			// FILE DOWNLOADING CODES
			if ($selected_status == 'all') {
				$paymentStatusForTitle = 'paid-and-unpaid';
			} else {
				$paymentStatusForTitle = $selected_status;
			}
			if ($selected_class == 'all') {
				$classNameForTitle = 'all_class';
			} else {
				$class_details = $this->crud_model->get_classes($selected_class)->row_array();
				$classNameForTitle = $class_details['name'];
			}
			$fileName = 'Student_fees-' . date('d-M-Y', $date_from) . '-to-' . date('d-M-Y', $date_to) . '-' . $classNameForTitle . '-' . $paymentStatusForTitle . '.csv';
			$this->download_file('assets/csv_file/invoices.csv', $fileName);
		}
	}

	/*FUNCTION FOR DOWNLOADING A FILE*/
	function download_file($path, $name)
	{
		// make sure it's a file before doing anything!
		if (is_file($path)) {
			// required for IE
			if (ini_get('zlib.output_compression')) {
				ini_set('zlib.output_compression', 'Off');
			}

			// get the file mime type using the file extension
			$this->load->helper('file');

			$mime = get_mime_by_extension($path);

			// Build the headers to push out the file properly.
			header('Pragma: public');     // required
			header('Expires: 0');         // no cache
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($path)) . ' GMT');
			header('Cache-Control: private', false);
			header('Content-Type: ' . $mime);  // Add the mime type from Code igniter.
			header('Content-Disposition: attachment; filename="' . basename($name) . '"');  // Add the file name
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: ' . filesize($path)); // provide file size
			header('Connection: close');
			readfile($path); // push it out
			exit();
		}
	}

	// Expense Category
	public function expense_category($param1 = "", $param2 = "")
	{
		if ($param1 == 'create') {
			$response = $this->crud_model->create_expense_category();
			echo $response;
		}

		if ($param1 == 'update') {
			$response = $this->crud_model->update_expense_category($param2);
			echo $response;
		}

		if ($param1 == 'delete') {
			$response = $this->crud_model->delete_expense_category($param2);
			echo $response;
		}

		if ($param1 == 'list') {
			$this->load->view('backend/admin/expense_category/list');
		}
		// showing the index file
		if (empty($param1)) {
			$page_data['folder_name'] = 'expense_category';
			$page_data['page_title']  = 'expense_category';
			$this->load->view('backend/index', $page_data);
		}
	}

	//Expense Manager
	public function expense($param1 = "", $param2 = "")
	{

		// adding expense
		if ($param1 == 'create') {
			$response = $this->crud_model->create_expense();
			echo $response;
		}

		// update expense
		if ($param1 == 'update') {
			$response = $this->crud_model->update_expense($param2);
			echo $response;
		}

		// deleting expense
		if ($param1 == 'delete') {
			$response = $this->crud_model->delete_expense($param2);
			echo $response;
		}
		// showing the list of expense
		if ($param1 == 'list') {
			$date = explode('-', $this->input->get('date'));
			$page_data['date_from'] = strtotime($date[0] . ' 00:00:00');
			$page_data['date_to']   = strtotime($date[1] . ' 23:59:59');
			$page_data['expense_category_id'] = htmlspecialchars($this->input->get('expense_category_id'));
			$this->load->view('backend/admin/expense/list', $page_data);
		}

		// showing the index file
		if (empty($param1)) {
			$page_data['folder_name'] = 'expense';
			$page_data['page_title']  = 'expense';
			$page_data['date_from']   = strtotime(date('d-M-Y', strtotime(' -30 day')) . ' 00:00:00');
			$page_data['date_to']     = strtotime(date('d-M-Y') . ' 23:59:59');
			$this->load->view('backend/index', $page_data);
		}
	}
	// ACCOUNTING SECTION ENDS

	// BACKOFFICE SECTION
	//BOOK LIST MANAGER
	public function book($param1 = "", $param2 = "")
	{
		// adding book
		if ($param1 == 'create') {
			$response = $this->crud_model->create_book();
			echo $response;
		}

		// update book
		if ($param1 == 'update') {
			$response = $this->crud_model->update_book($param2);
			echo $response;
		}

		// deleting book
		if ($param1 == 'delete') {
			$response = $this->crud_model->delete_book($param2);
			echo $response;
		}
		// showing the list of book
		if ($param1 == 'list') {
			$this->load->view('backend/admin/book/list');
		}

		// showing the index file
		if (empty($param1)) {
			$page_data['folder_name'] = 'book';
			$page_data['page_title']  = 'books';
			$this->load->view('backend/index', $page_data);
		}
	}

	//BOOK ISSUE LIST MANAGER
	public function book_issue($param1 = "", $param2 = "")
	{
		// adding book
		if ($param1 == 'create') {
			$response = $this->crud_model->create_book_issue();
			echo $response;
		}

		// update book
		if ($param1 == 'update') {
			$response = $this->crud_model->update_book_issue($param2);
			echo $response;
		}

		// Returning a book
		if ($param1 == 'return') {
			$response = $this->crud_model->return_issued_book($param2);
			echo $response;
		}

		// deleting book
		if ($param1 == 'delete') {
			$response = $this->crud_model->delete_book_issue($param2);
			echo $response;
		}
		// showing the list of book
		if ($param1 == 'list') {
			$date = explode('-', $this->input->get('date'));
			$page_data['date_from'] = strtotime($date[0] . ' 00:00:00');
			$page_data['date_to']   = strtotime($date[1] . ' 23:59:59');
			$this->load->view('backend/admin/book_issue/list', $page_data);
		}

		// showing the index file
		if (empty($param1)) {
			$page_data['folder_name'] = 'book_issue';
			$page_data['page_title']  = 'book_issue';
			$page_data['date_from'] = strtotime(date('d-M-Y', strtotime(' -30 day')) . ' 00:00:00');
			$page_data['date_to']   = strtotime(date('d-M-Y') . ' 23:59:59');
			$this->load->view('backend/index', $page_data);
		}
	}

	// NOTICEBOARD MANAGER
	public function noticeboard($param1 = "", $param2 = "", $param3 = "")
	{
		// adding notice
		if ($param1 == 'create') {
			$response = $this->crud_model->create_notice();
			echo $response;
		}

		// update notice
		if ($param1 == 'update') {
			$response = $this->crud_model->update_notice($param2);
			echo $response;
		}

		// deleting notice
		if ($param1 == 'delete') {
			$response = $this->crud_model->delete_notice($param2);
			echo $response;
		}
		// showing the list of notice
		if ($param1 == 'list') {
			$this->load->view('backend/admin/noticeboard/list');
		}

		// showing the all the notices
		if ($param1 == 'all_notices') {
			$response = $this->crud_model->get_all_the_notices();
			echo $response;
		}

		// showing the index file
		if (empty($param1)) {
			$page_data['folder_name'] = 'noticeboard';
			$page_data['page_title']  = 'noticeboard';
			$this->load->view('backend/index', $page_data);
		}
	}

	// SETTINGS MANAGER
	public function school_settings($param1 = "", $param2 = "")
	{
		if ($param1 == 'update') {
			$response = $this->settings_model->update_current_school_settings();
			echo $response;
		}

		// showing the System Settings file
		if (empty($param1)) {
			$page_data['folder_name'] = 'settings';
			$page_data['page_title']  = 'school_settings';
			$page_data['settings_type'] = 'school_settings';
			$this->load->view('backend/index', $page_data);
		}
	}
	// SETTINGS MANAGER

	//MANAGE PROFILE STARTS
	public function profile($param1 = "", $param2 = "")
	{
		if ($param1 == 'update_profile') {
			$response = $this->user_model->update_profile();
			echo $response;
		}
		if ($param1 == 'update_password') {
			$response = $this->user_model->update_password();
			echo $response;
		}

		// showing the Smtp Settings file
		if (empty($param1)) {
			$page_data['folder_name'] = 'profile';
			$page_data['page_title']  = 'manage_profile';
			$this->load->view('backend/index', $page_data);
		}
	}
	//MANAGE PROFILE ENDS

	// ABOUT APPLICATION STARTS
	public function online_admission($param1 = "", $user_id = "")
	{

		if ($param1 == 'assigned') {
			$data['student_id'] = $this->input->post('student_id');
			$data['class_id'] = $this->input->post('class_id');
			$data['section_id'] = $this->input->post('section_id');
			$data['school_id'] = school_id();
			$data['session'] = active_session();

			$this->db->insert('enrols', $data);

			$user_id = $this->db->get_where('students', array('id' => $data['student_id']))->row('user_id');

			$password = rand(100000, 999999);
			$this->db->where('id', $user_id);
			$this->db->update('users', array('status' => 1, 'password' => sha1($password)));
			$this->email_model->approved_online_admission($data['student_id'], $user_id, $password);

			$parent_id = $this->db->get_where('students', array('user_id' => $user_id))->row('parent_id');
			$parents_user_id = $this->db->get_where('parents', array('id' => $parent_id))->row('user_id');
			$this->db->where('id', $parents_user_id);
			$this->db->update('users', array('status' => 1, 'password' => sha1($password)));
			$this->email_model->approved_online_admission_parent_access($parents_user_id, $password);

			$this->session->set_flashdata('flash_message', get_phrase('admission_request_has_been_updated'));
			redirect(site_url('admin/online_admission'), 'refresh');
		}
		if ($param1 == 'delete') {
			$parent_id = $this->db->get_where('students', array('user_id' => $user_id))->row('parent_id');

			if ($this->db->get_where('students', array('parent_id' => $parent_id))->num_rows() <= 1) {
				$parents_user_id = $this->db->get_where('parents', array('id' => $parent_id))->row('user_id');

				$this->db->where('id', $parents_user_id);
				$this->db->delete('users');

				$this->db->where('user_id', $parents_user_id);
				$this->db->delete('parents');
			}

			$this->db->where('id', $user_id);
			$this->db->delete('users');

			$this->db->where('user_id', $user_id);
			$this->db->delete('students');
			$this->session->set_flashdata('flash_message', get_phrase('admission_data_deleted_successfully'));
			redirect(site_url('admin/online_admission'), 'refresh');
		}
		$page_data['applications'] = $this->db->get_where('users', array('status' => 3, 'school_id' => school_id()));
		$page_data['folder_name'] = 'online_admission';
		$page_data['page_title']  = 'online_admission';
		$this->load->view('backend/index', $page_data);
	}
	// ABOUT APPLICATION ENDS

	//   transport feature starts

	//                                       	1. driver action
	/*------------------------------------------------------------------------------------------------------------*/
	function driver($param1 = '', $param2 = '', $param3 = '')
	{
		if ($param1 == 'create') {
			$response = $this->driver_model->create_driver();
			echo $response;
		}

		if ($param1 == 'update') {
			$response = $this->driver_model->update_driver($param2);
			echo $response;
		}

		if ($param1 == 'delete') {
			$driver_id = $this->db->get_where('drivers', array('user_id' => $param2))->row('id');
			$response = $this->driver_model->delete_driver($param2, $driver_id);
			echo $response;
		}

		if ($param1 == 'list') {
			$this->load->view('backend/admin/driver/list');
		}

		if (empty($param1)) {
			$page_data['folder_name'] = 'driver';
			$page_data['page_title'] = 'drivers';
			$this->load->view('backend/index', $page_data);
		}
	}

	//                                       	2. vehicle action
	/*------------------------------------------------------------------------------------------------------------*/
	function vehicle($param1 = '', $param2 = '', $param3 = '')
	{
		if ($param1 == 'create') {
			$response = $this->driver_model->create_vehicle();
			echo $response;
		}

		if ($param1 == 'update') {
			$response = $this->driver_model->update_vehicle($param2);
			echo $response;
		}

		if ($param1 == 'delete') {
			$response = $this->driver_model->delete_vehicle($param2);
			echo $response;
		}

		if ($param1 == 'list') {
			$this->load->view('backend/admin/vehicle/list');
		}

		if (empty($param1)) {
			$page_data['folder_name'] = 'vehicle';
			$page_data['page_title'] = 'vehicles';
			$this->load->view('backend/index', $page_data);
		}
	}

	//                                       	3. assign students
	/*------------------------------------------------------------------------------------------------------------*/
	function assign_student($param1 = '', $param2 = '', $param3 = '', $param4 = '')
	{
		if ($param1 == 'add') {
			$response = $this->driver_model->add_to_vehicle();
			echo $response;
		}

		if ($param1 == 'delete') {
			$response = $this->driver_model->remove_from_vehicle($param2);
			echo $response;
		}

		if ($param1 == 'list') {
			$this->load->view('backend/admin/assign_student/list');
		}

		if ($param1 == 'filter') {
			$page_data['parent_category'] = $param2;
			$page_data['child_category'] = $param3;
			$this->load->view('backend/admin/assign_student/list', $page_data);
		}

		if (empty($param1)) {
			$page_data['folder_name'] = 'assign_student';
			$page_data['page_title'] = 'assign_student';
			$this->load->view('backend/index', $page_data);
		}
	}

	function class_wise_student($class_id)
	{
		$this->db->select('enrols.*, users.name');
		$this->db->from('enrols');
		$this->db->join('students', 'enrols.student_id = students.id');
		$this->db->join('users', 'students.user_id = users.id');
		$this->db->where('enrols.class_id', $class_id);
		$this->db->where('enrols.school_id', school_id());
		$total_students = $this->db->get()->result_array();

		$first_option = count($total_students) > 0 ? 'select_a_student' : 'no_student_available';
		echo '<select><option>' . get_phrase($first_option) . '</option>';

		foreach ($total_students as $student) {
			echo '<option value="' . $student['student_id'] . '">' . $student['name'] . '</option>';
		}
		echo '</select>';
	}

	function getAdditionalCategory($category)
	{
		$name = 'name';
		if ($category == 'class') {
			$this->db->where('school_id', school_id());
			$query = $this->db->get('classes');
		} elseif ($category == 'vehicle') {
			$name = 'vh_num';
			$this->db->where('school_id', school_id());
			$query = $this->db->get('vehicles');
		} elseif ($category == 'driver') {
			$this->db->select('drivers.*, users.name');
			$this->db->from('drivers');
			$this->db->join('users', 'drivers.user_id = users.id');
			$this->db->where('drivers.school_id', school_id());
			$query = $this->db->get();
		}

		$result = $query->result_array();

		$first_option = count($result) > 0 ? 'select_a_' . $category : 'no_' . $category . '_available';
		echo '<select><option>' . get_phrase($first_option) . '</option>';
		foreach ($result as $item) {
			echo '<option value="' . $item['id'] . '">' . $item[$name] . '</option>';
		}
		echo '</select>';
	}
}
