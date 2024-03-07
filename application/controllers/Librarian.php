<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*  @author   : Creativeitem
*  date      : November, 2019
*  Ekattor School Management System With Addons
*  http://codecanyon.net/user/Creativeitem
*  http://support.creativeitem.com
*/

class Librarian extends CI_Controller {
	public function __construct(){

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

		/*cache control*/
		$this->output->set_header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
		$this->output->set_header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		$this->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
		$this->output->set_header("Pragma: no-cache");

		/*SET DEFAULT TIMEZONE*/
		timezone();
		
		// CHECK WHETHER LIBRARIAN IS LOGGED IN
		if($this->session->userdata('librarian_login') != 1){
			redirect(site_url('login'), 'refresh');
		}
	}

	// INDEX FUNCTION
	public function index(){
		redirect(site_url('librarian/dashboard'), 'refresh');
	}

	//DASHBOARD
	public function dashboard(){
		$page_data['page_title'] = 'Dashboard';
		$page_data['folder_name'] = 'dashboard';
		$this->load->view('backend/index', $page_data);
	}

	// BACKOFFICE MANAGEMENT STARTS
	//BOOK LIST MANAGER
	public function book($param1 = "", $param2 = "") {
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
			$this->load->view('backend/librarian/book/list');
		}

		// showing the index file
		if(empty($param1)){
			$page_data['folder_name'] = 'book';
			$page_data['page_title']  = 'books';
			$this->load->view('backend/index', $page_data);
		}
	}

	//BOOK ISSUE LIST MANAGER
	public function book_issue($param1 = "", $param2 = "") {
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
			$page_data['date_from'] = strtotime($date[0].' 00:00:00');
			$page_data['date_to']   = strtotime($date[1].' 23:59:59');
			$this->load->view('backend/librarian/book_issue/list', $page_data);
		}

		// showing the index file
		if(empty($param1)){
			$page_data['folder_name'] = 'book_issue';
			$page_data['page_title']  = 'book_issue';
			$page_data['date_from'] = strtotime(date('d-M-Y', strtotime(' -30 day')).' 00:00:00');
			$page_data['date_to']   = strtotime(date('d-M-Y').' 23:59:59');
			$this->load->view('backend/index', $page_data);
		}
	}
	// BACKOFFICE MANAGEMENT ENDS

	//STUDENT LIST STARTS
	public function student($param1 = "", $param2 = "") {
		// Get the list of student. Here param2 defines classId
		if ($param1 == 'dropdown') {
			$page_data['enrolments'] = $this->user_model->get_student_details_by_id('class', $param2);
			$this->load->view('backend/superadmin/student/dropdown', $page_data);
		}
	}
	//STUDENT LIST ENDS

	//MANAGE PROFILE STARTS
	public function profile($param1 = "", $param2 = "") {
		if ($param1 == 'update_profile') {
			$response = $this->user_model->update_profile();
			echo $response;
		}
		if ($param1 == 'update_password') {
			$response = $this->user_model->update_password();
			echo $response;
		}

		// showing the Smtp Settings file
		if(empty($param1)){
			$page_data['folder_name'] = 'profile';
			$page_data['page_title']  = 'manage_profile';
			$this->load->view('backend/index', $page_data);
		}
	}
	//MANAGE PROFILE ENDS
}
