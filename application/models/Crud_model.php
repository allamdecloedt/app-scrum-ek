<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*  @author   : Creativeitem
*  date      : November, 2019
*  Ekattor School Management System With Addons
*  http://codecanyon.net/user/Creativeitem
*  http://support.creativeitem.com
*/

require APPPATH.'third_party/PHPExcel/IOFactory.php';
class Crud_model extends CI_Model {

	protected $school_id;
	protected $active_session;

	public function __construct()
	{
		parent::__construct();
		$this->school_id = school_id();
		$this->active_session = active_session();
	}


	//START CLASS section
	public function get_classes($id = "") {

		$this->db->where('school_id', $this->school_id);

		if ($id > 0) {
			$this->db->where('id', $id);

		}
		return $this->db->get('classes');


	}
	public function class_create()
	{
		$data['name'] = html_escape($this->input->post('name'));
		$data['school_id'] = $this->school_id;
		$this->db->insert('classes', $data);

		$insert_id = $this->db->insert_id();
		$section_data['name'] = 'A';
		$section_data['class_id'] = $insert_id;
		$this->db->insert('sections', $section_data);

		$response = array(
			'status' => true,
			'notification' => get_phrase('class_added_successfully')
		);
		return json_encode($response);
	}

	public function class_update($param1 = '')
	{
		$data['name'] = html_escape($this->input->post('name'));
		$this->db->where('id', $param1);
		$this->db->update('classes', $data);

		$response = array(
			'status' => true,
			'notification' => get_phrase('class_added_successfully')
		);
		return json_encode($response);
	}

	public function section_update($param1 = '')
	{
		$section_id = html_escape($this->input->post('section_id'));
		$section_name = html_escape($this->input->post('name'));
		foreach($section_id as $key => $value){
			if($value == 0){
				$data['class_id'] = $param1;
				$data['name'] = $section_name[$key];
				$this->db->insert('sections', $data);
			}
			if($value != 0 && $value != 'delete'){
				$data['name'] = $section_name[$key];
				$this->db->where('class_id', $param1);
				$this->db->where('id', $value);
				$this->db->update('sections', $data);
			}

			$section_value = null;
			if (strpos($value, 'delete') == true) {
				$section_value = str_replace('delete', '', $value);
			}
			if($value == $section_value.'delete'){
				$data['name'] = $section_name[$key];
				$this->db->where('class_id', $param1);
				$this->db->where('id', $section_value);
				$this->db->delete('sections');
			}
		}

		$response = array(
			'status' => true,
			'notification' => get_phrase('section_list_updated_successfully')
		);
		return json_encode($response);
	}

	public function class_delete($param1 = '')
	{
		$this->db->where('id', $param1);
		$this->db->delete('classes');

		$this->db->where('class_id', $param1);
		$this->db->delete('sections');

		$response = array(
			'status' => true,
			'notification' => get_phrase('class_deleted_successfully')
		);
		return json_encode($response);
	}

	// Get section details by class and section id
	public function get_section_details_by_id($type = "", $id = "") {
		$section_details = array();
		if ($type == 'class') {
			$section_details = $this->db->get_where('sections', array('class_id' => $id));
		}elseif ($type == 'section') {
			$section_details = $this->db->get_where('sections', array('id' => $id));
		}
		return $section_details;
	}

	//get Class details by id
	public function get_class_details_by_id($id) {
		$class_details = $this->db->get_where('classes', array('id' => $id));
		return $class_details;
	}
	//END CLASS section


	//START CLASS_ROOM section
	public function class_room_create()
	{
		$data['name'] = html_escape($this->input->post('name'));
		$data['school_id'] = html_escape($this->input->post('school_id'));
		$this->db->insert('class_rooms', $data);

		$response = array(
			'status' => true,
			'notification' => get_phrase('classroom_added_successfully')
		);
		return json_encode($response);
	}

	public function class_room_update($param1 = '')
	{
		$data['name'] = html_escape($this->input->post('name'));
		$this->db->where('id', $param1);
		$this->db->update('class_rooms', $data);

		$response = array(
			'status' => true,
			'notification' => get_phrase('classroom_updated_successfully')
		);
		return json_encode($response);
	}

	public function class_room_delete($param1 = '')
	{
		$this->db->where('id', $param1);
		$this->db->delete('class_rooms');

		$response = array(
			'status' => true,
			'notification' => get_phrase('classroom_deleted_successfully')
		);
		return json_encode($response);
	}
	//END CLASS_ROOM section


	//START MANAGE_SESSION section
	public function session_create()
	{
		$data['name'] = html_escape($this->input->post('session_title'));
		$this->db->insert('sessions', $data);

		$response = array(
			'status' => true,
			'notification' => get_phrase('session_has_been_created_successfully')
		);

		return json_encode($response);
	}

	public function session_update($param1 = '')
	{
		$data['name'] = html_escape($this->input->post('session_title'));
		$this->db->where('id', $param1);
		$this->db->update('sessions', $data);
		$response = array(
			'status' => true,
			'notification' => get_phrase('session_has_been_updated_successfully')
		);

		return json_encode($response);
	}

	public function session_delete($param1 = '')
	{
		$this->db->where('id', $param1);
		$this->db->delete('sessions');
		$response = array(
			'status' => true,
			'notification' => get_phrase('session_has_been_deleted_successfully')
		);

		return json_encode($response);
	}

	public function active_session($param1 = ''){
		$previous_session_id = active_session();
		$this->db->where('id', $previous_session_id);
		$this->db->update('sessions', array('status' => 0));
		$this->db->where('id', $param1);
		$this->db->update('sessions', array('status' => 1));
		$response = array(
			'status' => true,
			'notification' => get_phrase('session_has_been_activated')
		);
		return json_encode($response);
	}
	//END MANAGE_SESSION section


	//START SUBJECT section
	public function subject_create()
	{
		$data['name'] = html_escape($this->input->post('name'));
		$data['class_id'] = html_escape($this->input->post('class_id'));
		$data['school_id'] = html_escape($this->input->post('school_id'));
		$data['session'] = html_escape($this->input->post('session'));
		$this->db->insert('subjects', $data);

		$response = array(
			'status' => true,
			'notification' => get_phrase('subject_has_been_added_successfully')
		);

		return json_encode($response);
	}

	public function subject_update($param1 = '')
	{
		$data['class_id'] = html_escape($this->input->post('class_id'));
		$data['name'] = html_escape($this->input->post('name'));
		$this->db->where('id', $param1);
		$this->db->update('subjects', $data);

		$response = array(
			'status' => true,
			'notification' => get_phrase('subject_has_been_updated_successfully')
		);

		return json_encode($response);
	}

	public function subject_delete($param1 = '')
	{
		$this->db->where('id', $param1);
		$this->db->delete('subjects');

		$response = array(
			'status' => true,
			'notification' => get_phrase('subject_has_been_deleted_successfully')
		);

		return json_encode($response);
	}

	public function get_subject_by_id($subject_id = '') {
		return $this->db->get_where('subjects', array('id' => $subject_id))->row_array();
	}
	
	//END SUBJECT section


	//START DEPARTMENT section
	public function department_create()
	{
		$data['name'] = html_escape($this->input->post('name'));
		$data['school_id'] = html_escape($this->input->post('school_id'));
		$this->db->insert('departments', $data);

		$response = array(
			'status' => true,
			'notification' => get_phrase('department_has_been_added_successfully')
		);

		return json_encode($response);
	}

	public function department_update($param1 = '')
	{
		$data['name'] = html_escape($this->input->post('name'));
		$this->db->where('id', $param1);
		$this->db->update('departments', $data);

		$response = array(
			'status' => true,
			'notification' => get_phrase('department_has_been_updated_successfully')
		);

		return json_encode($response);
	}

	public function department_delete($param1 = '')
	{
		$this->db->where('id', $param1);
		$this->db->delete('departments');

		$response = array(
			'status' => true,
			'notification' => get_phrase('department_has_been_deleted_successfully')
		);

		return json_encode($response);
	}
	//END DEPARTMENT section


	//START SYLLABUS section
	public function syllabus_create($param1 = '')
	{
		$data['title'] = html_escape($this->input->post('title'));
		$data['class_id'] = html_escape($this->input->post('class_id'));
		$data['section_id'] = html_escape($this->input->post('section_id'));
		$data['subject_id'] = html_escape($this->input->post('subject_id'));
		$data['session_id'] = html_escape($this->input->post('session_id'));
		$data['school_id'] = html_escape($this->input->post('school_id'));
		$file_ext = pathinfo($_FILES['syllabus_file']['name'], PATHINFO_EXTENSION);
		$data['file'] = md5(rand(10000000, 20000000)).'.'.$file_ext;
		move_uploaded_file($_FILES['syllabus_file']['tmp_name'], 'uploads/syllabus/'.$data['file']);
		$this->db->insert('syllabuses', $data);

		$response = array(
			'status' => true,
			'notification' => get_phrase('syllabus_added_successfully')
		);
		return json_encode($response);
	}
	public function syllabus_delete($param1){
		$syllabus_details = $this->get_syllabus_by_id($param1);
		$this->db->where('id', $param1);
		$this->db->delete('syllabuses');
		$path = 'uploads/syllabus/'.$syllabus_details['file'];
		if (file_exists($path)){
				unlink($path);
		}
		$response = array(
			'status' => true,
			'notification' => get_phrase('syllabus_deleted_successfully')
		);
		return json_encode($response);
	}

	public function get_syllabus_by_id($syllabus_id = "") {
		return $this->db->get_where('syllabuses', array('id' => $syllabus_id))->row_array();
	}
	//END SYLLABUS section

	//START CLASS ROUTINE section
	public function routine_create()
	{
		$data['class_id'] = html_escape($this->input->post('class_id'));
		$data['section_id'] = html_escape($this->input->post('section_id'));
		$data['subject_id'] = html_escape($this->input->post('subject_id'));
		$data['teacher_id'] = html_escape($this->input->post('teacher_id'));
		$data['room_id'] = html_escape($this->input->post('class_room_id'));
		$data['day'] = html_escape($this->input->post('day'));
		$data['starting_hour'] = html_escape($this->input->post('starting_hour'));
		$data['starting_minute'] = html_escape($this->input->post('starting_minute'));
		$data['ending_hour'] = html_escape($this->input->post('ending_hour'));
		$data['ending_minute'] = html_escape($this->input->post('ending_minute'));
		$data['school_id'] = $this->school_id;
		$data['session_id'] = $this->active_session;
		$this->db->insert('routines', $data);

		$response = array(
			'status' => true,
			'notification' => get_phrase('class_routine_added_successfully')
		);

		return json_encode($response);
	}

	public function routine_update($param1 = '')
	{
		$data['class_id'] = html_escape($this->input->post('class_id'));
		$data['section_id'] = html_escape($this->input->post('section_id'));
		$data['subject_id'] = html_escape($this->input->post('subject_id'));
		$data['teacher_id'] = html_escape($this->input->post('teacher_id'));
		$data['room_id'] = html_escape($this->input->post('class_room_id'));
		$data['day'] = html_escape($this->input->post('day'));
		$data['starting_hour'] = html_escape($this->input->post('starting_hour'));
		$data['starting_minute'] = html_escape($this->input->post('starting_minute'));
		$data['ending_hour'] = html_escape($this->input->post('ending_hour'));
		$data['ending_minute'] = html_escape($this->input->post('ending_minute'));
		$this->db->where('id', $param1);
		$this->db->update('routines', $data);

		$response = array(
			'status' => true,
			'notification' => get_phrase('class_routine_updated_successfully')
		);

		return json_encode($response);
	}

	public function routine_delete($param1 = '')
	{
		$this->db->where('id', $param1);
		$this->db->delete('routines');

		$response = array(
			'status' => true,
			'notification' => get_phrase('class_routine_deleted_successfully')
		);

		return json_encode($response);
	}
	//END CLASS ROUTINE section


	//START DAILY ATTENDANCE section
	public function take_attendance()
	{
		$students = $this->input->post('student_id');
		$data['timestamp'] = strtotime($this->input->post('date'));
		$data['class_id'] = html_escape($this->input->post('class_id'));
		$data['section_id'] = html_escape($this->input->post('section_id'));
		$data['school_id'] = $this->school_id;
		$data['session_id'] = $this->active_session;
		$check_data = $this->db->get_where('daily_attendances', array('timestamp' => $data['timestamp'], 'class_id' => $data['class_id'], 'section_id' => $data['section_id'], 'session_id' => $data['session_id'], 'school_id' => $data['school_id']));
		if($check_data->num_rows() > 0){
			foreach($students as $key => $student):
				$data['status'] = $this->input->post('status-'.$student);
				$data['student_id'] = $student;
				$attendance_id = $this->input->post('attendance_id');
				$this->db->where('id', $attendance_id[$key]);
				$this->db->update('daily_attendances', $data);
			endforeach;
		}else{
			foreach($students as $student):
				$data['status'] = $this->input->post('status-'.$student);
				$data['student_id'] = $student;
				$this->db->insert('daily_attendances', $data);
			endforeach;
		}

		$this->settings_model->last_updated_attendance_data();

		$response = array(
			'status' => true,
			'notification' => get_phrase('attendance_updated_successfully')
		);

		return json_encode($response);
	}

	public function get_todays_attendance() {
		$checker = array(
			'timestamp' => strtotime(date('Y-m-d')),
			'school_id' => $this->school_id,
			'status'    => 1
		);
		$todays_attendance = $this->db->get_where('daily_attendances', $checker);
		return $todays_attendance->num_rows();
	}
	//END DAILY ATTENDANCE section


	//START EVENT CALENDAR section
	public function event_calendar_create()
	{
		$data['title'] = html_escape($this->input->post('title'));
		$data['starting_date'] = $this->input->post('starting_date').' 00:00:1';
		$data['ending_date'] = $this->input->post('ending_date').' 23:59:59';
		$data['school_id'] = $this->school_id;
		$data['session'] = $this->active_session;
		$this->db->insert('event_calendars', $data);

		$response = array(
			'status' => true,
			'notification' => get_phrase('event_has_been_added_successfully')
		);

		return json_encode($response);
	}

	public function event_calendar_update($param1 = '')
	{
		$data['title'] = html_escape($this->input->post('title'));
		$starting_date = strtotime(date('d/m/Y')) +1;
		$ending_date = strtotime(date('d/m/Y')) -1;
		$data['starting_date'] = $this->input->post('starting_date').' 00:00:1';
		$data['ending_date'] = $this->input->post('ending_date').' 23:59:59';
		$this->db->where('id', $param1);
		$this->db->update('event_calendars', $data);

		$response = array(
			'status' => true,
			'notification' => get_phrase('event_has_been_updated_successfully')
		);

		return json_encode($response);
	}

	public function event_calendar_delete($param1 = '')
	{
		$this->db->where('id', $param1);
		$this->db->delete('event_calendars');

		$response = array(
			'status' => true,
			'notification' => get_phrase('event_has_been_deleted_successfully')
		);

		return json_encode($response);
	}

	public function all_events(){

		$event_calendars = $this->db->get_where('event_calendars', array('school_id' => $this->school_id, 'session' => $this->active_session))->result_array();
		return json_encode($event_calendars);
	}

	public function get_current_month_events() {
		$this->db->where('school_id', $this->school_id);
		$this->db->where('session', $this->active_session);
		$events = $this->db->get('event_calendars');
		return $events;
	}
	//END EVENT CALENDAR section

	// START OF NOTICEBOARD SECTION
	public function create_notice() {
		$data['notice_title']     = html_escape($this->input->post('notice_title'));
		$data['notice']           = html_escape($this->input->post('notice'));
		$data['show_on_website']  = $this->input->post('show_on_website');
		$data['date'] 						= $this->input->post('date').' 00:00:1';
		$data['school_id'] 				= $this->school_id;
		$data['session'] 					= $this->active_session;
		if ($_FILES['notice_photo']['name'] != '') {
			$data['image']  = random(15).'.jpg';
			move_uploaded_file($_FILES['notice_photo']['tmp_name'], 'uploads/images/notice_images/'. $data['image']);
		}else{
			$data['image']  = 'placeholder.png';
		}
		$this->db->insert('noticeboard', $data);

		$response = array(
			'status' => true,
			'notification' => get_phrase('notice_has_been_created')
		);

		return json_encode($response);
	}

	public function update_notice($notice_id) {
		$data['notice_title']     = html_escape($this->input->post('notice_title'));
		$data['notice']           = html_escape($this->input->post('notice'));
		$data['show_on_website']  = $this->input->post('show_on_website');
		$data['date'] 						= $this->input->post('date').' 00:00:1';
		if ($_FILES['notice_photo']['name'] != '') {
			$data['image']  = random(15).'.jpg';
			move_uploaded_file($_FILES['notice_photo']['tmp_name'], 'uploads/images/notice_images/'. $data['image']);
		}
		$this->db->where('id', $notice_id);
		$this->db->update('noticeboard', $data);

		$response = array(
			'status' => true,
			'notification' => get_phrase('notice_has_been_updated')
		);

		return json_encode($response);
	}

	public function delete_notice($notice_id) {
		$this->db->where('id', $notice_id);
		$this->db->delete('noticeboard');

		$response = array(
			'status' => true,
			'notification' => get_phrase('notice_has_been_deleted')
		);

		return json_encode($response);
	}

	public function get_all_the_notices() {
		$notices = $this->db->get_where('noticeboard', array('school_id' => $this->school_id, 'session' => $this->active_session))->result_array();
		return json_encode($notices);
	}

	public function get_noticeboard_image($image) {
		if (file_exists('uploads/images/notice_images/'.$image))
		return base_url().'uploads/images/notice_images/'.$image;
		else
		return base_url().'uploads/images/notice_images/placeholder.png';
	}
	// END OF NOTICEBOARD SECTION

	//START EXAM section
	public function exam_create()
	{
		$data['name'] = html_escape($this->input->post('exam_name'));
		$data['starting_date'] = strtotime($this->input->post('starting_date'));
		$data['ending_date'] = strtotime($this->input->post('ending_date'));
		$data['school_id'] = $this->school_id;
		$data['session'] = $this->active_session;
		$this->db->insert('exams', $data);

		$response = array(
			'status' => true,
			'notification' => get_phrase('exam_created_successfully')
		);
		return json_encode($response);
	}

	public function exam_update($param1 = '')
	{
		$data['name'] = html_escape($this->input->post('exam_name'));
		$data['starting_date'] = strtotime($this->input->post('starting_date'));
		$data['ending_date'] = strtotime($this->input->post('ending_date'));
		$this->db->where('id', $param1);
		$this->db->update('exams', $data);

		$response = array(
			'status' => true,
			'notification' => get_phrase('exam_updated_successfully')
		);
		return json_encode($response);
	}

	public function exam_delete($param1 = '')
	{
		$this->db->where('id', $param1);
		$this->db->delete('exams');

		$response = array(
			'status' => true,
			'notification' => get_phrase('exam_deleted_successfully')
		);
		return json_encode($response);
	}

	public function get_exam_by_id($exam_id = "") {
		return $this->db->get_where('exams', array('id' => $exam_id))->row_array();
	}
	//END EXAM section


	//START MARKS section
	public function get_marks($class_id = "", $section_id = "", $subject_id = "", $exam_id = "") {
		$checker = array(
			'class_id' => $class_id,
			'section_id' => $section_id,
			'subject_id' => $subject_id,
			'exam_id' => $exam_id,
			'school_id' => $this->school_id,
			'session' => $this->active_session
		);
		$this->db->where($checker);
		return $this->db->get('marks');
	}
	public function mark_insert($class_id = "", $section_id = "", $subject_id = "", $exam_id = "") {
		$student_enrolments = $this->user_model->student_enrolment($section_id)->result_array();
		foreach ($student_enrolments as $student_enrolment) {
			$checker = array(
				'student_id' => $student_enrolment['student_id'],
				'class_id' => $class_id,
				'section_id' => $section_id,
				'subject_id' => $subject_id,
				'exam_id' => $exam_id,
				'school_id' => $this->school_id,
				'session' => $this->active_session
			);
			$this->db->where($checker);
			$number_of_rows = $this->db->get('marks')->num_rows();
			if($number_of_rows == 0) {
				$this->db->insert('marks', $checker);
			}
		}
	}

	public function mark_update(){
		$data['student_id'] = html_escape($this->input->post('student_id'));
		$data['class_id'] = html_escape($this->input->post('class_id'));
		$data['section_id'] = html_escape($this->input->post('section_id'));
		$data['subject_id'] = html_escape($this->input->post('subject_id'));
		$data['exam_id'] = html_escape($this->input->post('exam_id'));
		$data['mark_obtained'] = html_escape($this->input->post('mark'));
		$data['comment'] = html_escape($this->input->post('comment'));
		$data['school_id'] = $this->school_id;
		$data['session'] = $this->active_session;
		$query = $this->db->get_where('marks', array('student_id' => $data['student_id'], 'class_id' => $data['class_id'], 'section_id' => $data['section_id'], 'subject_id' => $data['subject_id'], 'exam_id' => $data['exam_id'], 'session' => $data['session'], 'school_id' => $data['school_id']));
		if($query->num_rows() > 0){
			$update_data['mark_obtained'] = html_escape($this->input->post('mark'));
			$update_data['comment'] = html_escape($this->input->post('comment'));
			$row = $query->row();
			$this->db->where('id', $row->id);
			$this->db->update('marks', $update_data);
		}else{
			$this->db->insert('marks', $data);
		}
	}
	//END MARKS section

	// Grade creation
	public function grade_create() {
		$data['name'] = html_escape($this->input->post('grade'));
		$data['grade_point'] = htmlspecialchars($this->input->post('grade_point'));
		$data['mark_from'] = htmlspecialchars($this->input->post('mark_from'));
		$data['mark_upto'] = htmlspecialchars($this->input->post('mark_upto'));
		$data['school_id'] = $this->school_id;
		$data['session'] = $this->active_session;
		$this->db->insert('grades', $data);

		$response = array(
			'status' => true,
			'notification' => get_phrase('grade_added_successfully')
		);
		return json_encode($response);
	}

	public function grade_update($id = "") {
		$data['name'] = html_escape($this->input->post('grade'));
		$data['grade_point'] = htmlspecialchars($this->input->post('grade_point'));
		$data['mark_from'] = htmlspecialchars($this->input->post('mark_from'));
		$data['mark_upto'] = htmlspecialchars($this->input->post('mark_upto'));
		$data['school_id'] = $this->school_id;
		$data['session'] = $this->active_session;

		$this->db->where('id', $id);
		$this->db->update('grades', $data);

		$response = array(
			'status' => true,
			'notification' => get_phrase('grade_updated_successfully')
		);
		return json_encode($response);
	}

	public function grade_delete($id = '')
	{
		$this->db->where('id', $id);
		$this->db->delete('grades');
		$response = array(
			'status' => true,
			'notification' => get_phrase('grade_deleted_successfully')
		);
		return json_encode($response);
	}
	// Grade ends

	// Student Promotion section Starts
	public function get_student_list() {
		$session_from = htmlspecialchars($this->input->post('session_from'));
		$session_to = htmlspecialchars($this->input->post('session_to'));
		$class_id_from = htmlspecialchars($this->input->post('class_id_from'));
		$class_id_to = htmlspecialchars($this->input->post('class_id_to'));
		$checker = array(
			'class_id' => $class_id_from,
			'session' => $session_from,
			'school_id' => $this->school_id
		);
		return $this->db->get_where('enrols', $checker);
	}

	//promote student
	public function promote_student($promotion_data = "") {
		$promotion_data = explode('-', $promotion_data);
		$enroll_id = $promotion_data[0];
		$class_id = $promotion_data[1];
		$session_id = $promotion_data[2];
		$enroll = $this->db->get_where('enrols', array('id' => $enroll_id))->row_array();
		$enroll['class_id'] = $class_id;
		$enroll['session'] = $session_id;
		$first_section_details = $this->db->get_where('sections', array('class_id' => $class_id))->row_array();
		$enroll['section_id'] = $first_section_details['id'];
		$this->db->where('id', $enroll_id);
		$this->db->update('enrols', $enroll);
		return true;
	}
	// Student Promotion section Ends

	//STUDENT ACCOUNTING SECTION STARTS
	public function get_invoice_by_id($id = "") {
		return $this->db->get_where('invoices', array('id' => $id))->row_array();
	}

	public function get_invoice_by_date_range($date_from = "", $date_to = "", $selected_class = "", $selected_status = "") {
		if ($selected_class != "all") {
			$this->db->where('class_id', $selected_class);
		}
		if ($selected_status != "all") {
			$this->db->where('status', $selected_status);
		}
		$this->db->where('created_at >=', $date_from);
		$this->db->where('created_at <=', $date_to);
		$this->db->where('school_id', $this->school_id);
		$this->db->where('session', $this->active_session);
		return $this->db->get('invoices');
	}

	public function get_invoice_by_student_id($student_id = "") {
		$this->db->where('school_id', $this->school_id);
		$this->db->where('session', $this->active_session);
		$this->db->where('student_id', $student_id);
		return $this->db->get('invoices');
	}

	// This function will be triggered if parent logs in
	public function get_invoice_by_parent_id() {
		$parent_user_id = $this->session->userdata('user_id');
		$parent_data = $this->db->get_where('parents', array('user_id' => $parent_user_id))->row_array();
		$student_list = $this->user_model->get_student_list_of_logged_in_parent();
		$student_ids = array();
		foreach ($student_list as $student) {
			if(!in_array($student['student_id'], $student_ids)){
				array_push($student_ids, $student['student_id']);
			}
		}

		if (count($student_ids) > 0) {
			$this->db->where_in('student_id', $student_ids);
			$this->db->where('school_id', $this->school_id);
			$this->db->where('session', $this->active_session);
			return $this->db->get('invoices')->result_array();
		}else{
			return array();
		}
	}

	public function create_single_invoice() {
		$data['title'] = htmlspecialchars($this->input->post('title'));
		$data['total_amount'] = htmlspecialchars($this->input->post('total_amount'));
		$data['class_id'] = htmlspecialchars($this->input->post('class_id'));
		$data['student_id'] = htmlspecialchars($this->input->post('student_id'));
		$data['paid_amount'] = htmlspecialchars($this->input->post('paid_amount'));
		$data['status'] = htmlspecialchars($this->input->post('status'));
		$data['school_id'] = $this->school_id;
		$data['session'] = $this->active_session;
		$data['created_at'] = strtotime(date('d-M-Y'));

		/*KEEPING TRACK OF PAYMENT DATE*/
		if ($this->input->post('paid_amount') > 0) {
			$data['updated_at'] = strtotime(date('d-M-Y'));
		}

		if ($data['paid_amount'] > $data['total_amount']) {
			$response = array(
				'status' => false,
				'notification' => get_phrase('paid_amount_can_not_get_bigger_than_total_amount')
			);
			return json_encode($response);
		}
		if ($data['status'] == 'paid' && $data['total_amount'] != $data['paid_amount']) {
			$response = array(
				'status' => false,
				'notification' => get_phrase('paid_amount_is_not_equal_to_total_amount')
			);
			return json_encode($response);
		}

		$this->db->insert('invoices', $data);

		$response = array(
			'status' => true,
			'notification' => get_phrase('invoice_added_successfully')
		);
		return json_encode($response);
	}

	public function create_mass_invoice() {
		$data['total_amount'] = htmlspecialchars($this->input->post('total_amount'));
		$data['paid_amount'] = htmlspecialchars($this->input->post('paid_amount'));
		$data['status'] = htmlspecialchars($this->input->post('status'));

		if ($data['paid_amount'] > $data['total_amount']) {
			$response = array(
				'status' => false,
				'notification' => get_phrase('paid_amount_can_not_get_bigger_than_total_amount')
			);
			return json_encode($response);
		}

		if ($data['status'] == 'paid' && $data['total_amount'] != $data['paid_amount']) {
			$response = array(
				'status' => false,
				'notification' => get_phrase('paid_amount_is_not_equal_to_total_amount')
			);
			return json_encode($response);
		}

		$data['title'] = htmlspecialchars($this->input->post('title'));
		$data['class_id'] = htmlspecialchars($this->input->post('class_id'));
		$data['school_id'] = $this->school_id;
		$data['session'] = $this->active_session;
		$data['created_at'] = strtotime(date('d-M-Y'));

		/*KEEPING TRACK OF PAYMENT DATE*/
		if ($this->input->post('paid_amount') > 0) {
			$data['updated_at'] = strtotime(date('d-M-Y'));
		}

		$enrolments = $this->user_model->get_student_details_by_id('section', htmlspecialchars($this->input->post('section_id')));
		foreach ($enrolments as $enrolment) {
			$data['student_id'] = $enrolment['student_id'];
			$this->db->insert('invoices', $data);
		}

		if (sizeof($enrolments) > 0) {
			$response = array(
				'status' => true,
				'notification' => get_phrase('invoice_added_successfully')
			);
		}else{
			$response = array(
				'status' => false,
				'notification' => get_phrase('no_student_found')
			);
		}
		return json_encode($response);
	}

	public function update_invoice($id = "") {

		/*GET THE PREVIOUS INVOICE DETAILS FOR GETTING THE PAID AMOUNT*/
		$previous_invoice_data = $this->db->get_where('invoices', array('id' => $id))->row_array();

		$data['title'] = htmlspecialchars($this->input->post('title'));
		$data['total_amount'] = htmlspecialchars($this->input->post('total_amount'));
		$data['class_id'] = htmlspecialchars($this->input->post('class_id'));
		$data['student_id'] = htmlspecialchars($this->input->post('student_id'));
		$data['paid_amount'] = htmlspecialchars($this->input->post('paid_amount'));
		$data['status'] = htmlspecialchars($this->input->post('status'));

		if ($data['paid_amount'] > $data['total_amount']) {
			$response = array(
				'status' => false,
				'notification' => get_phrase('paid_amount_can_not_get_bigger_than_total_amount')
			);
			return json_encode($response);
		}
		if ($data['status'] == 'paid' && $data['total_amount'] != $data['paid_amount']) {
			$response = array(
				'status' => false,
				'notification' => get_phrase('paid_amount_is_not_equal_to_total_amount')
			);
			return json_encode($response);
		}

		if ($data['total_amount'] == $data['paid_amount']) {
			$data['status'] = 'paid';
		}

		/*KEEPING TRACK OF PAYMENT DATE*/
		if ($this->input->post('paid_amount') != $previous_invoice_data && $this->input->post('paid_amount') > 0) {
			$data['updated_at'] = strtotime(date('d-M-Y'));
		}elseif ($this->input->post('paid_amount') == 0 || $this->input->post('paid_amount') == "") {
			$data['updated_at'] = 0;
		}

		$this->db->where('id', $id);
		$this->db->update('invoices', $data);

		$response = array(
			'status' => true,
			'notification' => get_phrase('invoice_updated_successfully')
		);
		return json_encode($response);
	}

	public function delete_invoice($id = "") {
		$this->db->where('id', $id);
		$this->db->delete('invoices');

		$response = array(
			'status' => true,
			'notification' => get_phrase('invoice_deleted_successfully')
		);
		return json_encode($response);
	}
	//STUDENT ACCOUNTING SECTION ENDS

	//Expense Category Starts
	public function get_expense_categories($id = "") {
		if ($id > 0) {
			$this->db->where('id', $id);
		}
		$this->db->where('school_id', $this->school_id);
		$this->db->where('session', $this->active_session);
		return $this->db->get('expense_categories');
	}
	public function create_expense_category() {
		$data['name'] = htmlspecialchars($this->input->post('name'));
		$data['school_id'] = $this->school_id;
		$data['session'] = $this->active_session;
		$this->db->insert('expense_categories', $data);
		$response = array(
			'status' => true,
			'notification' => get_phrase('expense_category_added_successfully')
		);
		return json_encode($response);
	}

	public function update_expense_category($id) {
		$data['name'] = htmlspecialchars($this->input->post('name'));
		$this->db->where('id', $id);
		$this->db->update('expense_categories', $data);
		$response = array(
			'status' => true,
			'notification' => get_phrase('expense_category_updated_successfully')
		);
		return json_encode($response);
	}

	public function delete_expense_category($id) {
		$this->db->where('id', $id);
		$this->db->delete('expense_categories');
		$response = array(
			'status' => true,
			'notification' => get_phrase('expense_category_deleted_successfully')
		);
		return json_encode($response);
	}
	//Expense Category Ends

	//Expense Manager Starts
	public function get_expense_by_id($id = "") {
		return $this->db->get_where('expenses', array('id' => $id))->row_array();
	}

	public function get_expense($date_from = "", $date_to = "", $expense_category_id = "") {
		if ($expense_category_id > 0) {
			$this->db->where('expense_category_id', $expense_category_id);
		}
		$this->db->where('date >=', $date_from);
		$this->db->where('date <=', $date_to);
		$this->db->where('school_id', $this->school_id);
		$this->db->where('session', $this->active_session);
		return $this->db->get('expenses');
	}

	// creating
	public function create_expense() {
		$data['date'] = strtotime($this->input->post('date'));
		$data['amount'] = htmlspecialchars($this->input->post('amount'));
		$data['expense_category_id'] = htmlspecialchars($this->input->post('expense_category_id'));
		$data['school_id'] = $this->school_id;
		$data['session'] = $this->active_session;
		$data['created_at'] = strtotime(date('d-M-Y'));
		$this->db->insert('expenses', $data);

		$response = array(
			'status' => true,
			'notification' => get_phrase('expense_added_successfully')
		);
		return json_encode($response);
	}

	// updating
	public function update_expense($id = "") {
		$data['date'] = strtotime($this->input->post('date'));
		$data['amount'] = htmlspecialchars($this->input->post('amount'));
		$data['expense_category_id'] = htmlspecialchars($this->input->post('expense_category_id'));
		$data['school_id'] = $this->school_id;
		$data['session'] = $this->active_session;
		$this->db->where('id', $id);
		$this->db->update('expenses', $data);

		$response = array(
			'status' => true,
			'notification' => get_phrase('expense_updated_successfully')
		);
		return json_encode($response);
	}

	// deleting
	public function delete_expense($id = "") {
		$this->db->where('id', $id);
		$this->db->delete('expenses');

		$response = array(
			'status' => true,
			'notification' => get_phrase('expense_deleted_successfully')
		);
		return json_encode($response);
	}
	// Expense Manager Ends

	// PROVIDE ENTRY AFTER PAYMENT SUCCESS
	public function payment_success($data = array()) {
		$this->db->where('id', $data['invoice_id']);
		$invoice_details = $this->db->get('invoices')->row_array();
		$due_amount = $invoice_details['total_amount'] - $invoice_details['paid_amount'];
		if ($due_amount == $data['amount_paid']) {
			$updater = array(
				'status' => 'paid',
				'payment_method' => $data['payment_method'],
				'paid_amount' => $data['amount_paid'] + $invoice_details['paid_amount'],
				'updated_at'  => strtotime(date('d-M-Y'))
			);
			$this->db->where('id', $data['invoice_id']);
			$this->db->update('invoices', $updater);
		}
	}

	// Back Office Section Starts
	public function get_session($id = "") {
		if ($id > 0) {
			$this->db->where('id', $id);
		}
		$sessions = $this->db->get('sessions');
		return $sessions;
	}

	// Book Manager
	public function get_books() {
		$checker = array(
			'session' => $this->active_session,
			'school_id' => $this->school_id
		);
		return $this->db->get_where('books', $checker);
	}

	public function get_book_by_id($id = "") {
		return $this->db->get_where('books', array('id' => $id))->row_array();
	}

	public function create_book() {
		$data['name']      = htmlspecialchars($this->input->post('name'));
		$data['author']    = htmlspecialchars($this->input->post('author'));
		$data['copies']    = htmlspecialchars($this->input->post('copies'));
		$data['school_id'] = $this->school_id;
		$data['session']   = $this->active_session;
		$this->db->insert('books', $data);

		$response = array(
			'status' => true,
			'notification' => get_phrase('books_added_successfully')
		);
		return json_encode($response);
	}

	public function update_book($id = "") {
		$data['name']      = htmlspecialchars($this->input->post('name'));
		$data['author']    = htmlspecialchars($this->input->post('author'));
		$data['copies']    = htmlspecialchars($this->input->post('copies'));
		$data['school_id'] = $this->school_id;
		$data['session']   = $this->active_session;

		$this->db->where('id', $id);
		$this->db->update('books', $data);

		$response = array(
			'status' => true,
			'notification' => get_phrase('books_updated_successfully')
		);
		return json_encode($response);
	}

	public function delete_book($id = "") {
		$this->db->where('id', $id);
		$this->db->delete('books');

		$response = array(
			'status' => true,
			'notification' => get_phrase('books_deleted_successfully')
		);
		return json_encode($response);
	}

	// Book Issue
	public function get_book_issues($date_from = "", $date_to = "") {
		$this->db->where('session', $this->active_session);
		$this->db->where('school_id', $this->school_id);
		$this->db->where('issue_date >=', $date_from);
		$this->db->where('issue_date <=', $date_to);
		return $this->db->get('book_issues');
	}
	public function get_book_issues_by_student_id($student_id = "") {
		$this->db->where('student_id', $student_id);
		return $this->db->get('book_issues');
	}

	public function get_book_issue_by_id($id = "") {
		return $this->db->get_where('book_issues', array('id' => $id))->row_array();
	}

	public function create_book_issue() {
		$data['book_id']    = htmlspecialchars($this->input->post('book_id'));
		$data['class_id']   = htmlspecialchars($this->input->post('class_id'));
		$data['student_id'] = htmlspecialchars($this->input->post('student_id'));
		$data['issue_date'] = strtotime($this->input->post('issue_date'));
		$data['school_id'] = $this->school_id;
		$data['session']   = $this->active_session;

		$this->db->insert('book_issues', $data);

		$response = array(
			'status' => true,
			'notification' => get_phrase('added_successfully')
		);
		return json_encode($response);
	}

	public function update_book_issue($id = "") {
		$data['book_id']    = htmlspecialchars($this->input->post('book_id'));
		$data['class_id']   = htmlspecialchars($this->input->post('class_id'));
		$data['student_id'] = htmlspecialchars($this->input->post('student_id'));
		$data['issue_date'] = strtotime($this->input->post('issue_date'));
		$data['school_id'] = $this->school_id;
		$data['session']   = $this->active_session;

		$this->db->where('id', $id);
		$this->db->update('book_issues', $data);

		$response = array(
			'status' => true,
			'notification' => get_phrase('updated_successfully')
		);
		return json_encode($response);
	}

	public function return_issued_book($id = "") {
		$data['status']   = 1;

		$this->db->where('id', $id);
		$this->db->update('book_issues', $data);

		$response = array(
			'status' => true,
			'notification' => get_phrase('returned_successfully')
		);
		return json_encode($response);
	}

	public function get_number_of_issued_book_by_id($id) {
		return $this->db->get_where('book_issues', array('book_id' => $id, 'status' => 0))->num_rows();
	}

	public function delete_book_issue($id = "") {
		$this->db->where('id', $id);
		$this->db->delete('book_issues');

		$response = array(
			'status' => true,
			'notification' => get_phrase('deleted_successfully')
		);
		return json_encode($response);
	}

	//SCHOOL DETAILS
	public function get_schools() {
		if (!addon_status('multi-school')) {
			$this->db->where('id', school_id());
		}
		$schools = $this->db->get('schools');
		return $schools;
	}
	public function get_school_details_by_id($school_id = "") {
		return $this->db->get_where('schools', array('id' => $school_id))->row_array();
	}
	// Back Office Section Ends

	// GET INSTALLED ADDONS
	public function get_addons($unique_identifier = "") {
		if ($unique_identifier != "") {
			$addons = $this->db->get_where('addons', array('unique_identifier' => $unique_identifier));
		}else{
			$addons = $this->db->get_where('addons');
		}
		return $addons;
	}

	// A function to convert excel to csv
	public function excel_to_csv($file_path = "", $rename_to = "") {
		//read file from path
		$inputFileType = PHPExcel_IOFactory::identify($file_path);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($file_path);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
		$index = 0;
		if ($objPHPExcel->getSheetCount() > 1) {
			foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
				$objPHPExcel->setActiveSheetIndex($index);
				$fileName = strtolower(str_replace(array("-"," "), "_", $worksheet->getTitle()));
				$outFile = str_replace(".", "", $fileName) .".csv";
				$objWriter->setSheetIndex($index);
				$objWriter->save("assets/csv_file/".$outFile);
				$index++;
			}
		}else{
			$outFile = $rename_to;
			$objWriter->setSheetIndex($index);
			$objWriter->save("assets/csv_file/".$outFile);
		}

		return true;
	}

	public function check_recaptcha(){
        if (isset($_POST["g-recaptcha-response"])) {
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $data = array(
                'secret' => get_common_settings('recaptcha_secretkey'),
                'response' => $_POST["g-recaptcha-response"]
            );
                $query = http_build_query($data);
                $options = array(
                'http' => array (
                    'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
                        "Content-Length: ".strlen($query)."\r\n".
                        "User-Agent:MyAgent/1.0\r\n",
                    'method' => 'POST',
                    'content' => $query
                )
            );
            $context  = stream_context_create($options);
            $verify = file_get_contents($url, false, $context);
            $captcha_success = json_decode($verify);
            if ($captcha_success->success == false) {
                return false;
            } else if ($captcha_success->success == true) {
                return true;
            }
        } else {
            return false;
        }
    }
}
