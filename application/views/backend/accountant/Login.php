<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->database();
		$this->load->library('session');
		/*cache control*/
		$this->output->set_header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
		$this->output->set_header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		$this->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
		$this->output->set_header("Pragma: no-cache");
	}

	public function index()
	{
		if($this->session->userdata('superadmin_login') ==  true){
			redirect(route('dashboard'), 'refresh');
		}elseif($this->session->userdata('admin_login') ==  true){
			redirect(route('dashboard'), 'refresh');
		}elseif($this->session->userdata('teacher_login') ==  true){
			redirect(route('dashboard'), 'refresh');
		}elseif($this->session->userdata('parent_login') ==  true){
			redirect(route('dashboard'), 'refresh');
		}elseif($this->session->userdata('student_login') ==  true){
			redirect(route('dashboard'), 'refresh');
		}elseif($this->session->userdata('accountant_login') ==  true){
			redirect(route('dashboard'), 'refresh');
		}elseif($this->session->userdata('librarian_login') ==  true){
			redirect(route('dashboard'), 'refresh');
		}else{
			$this->load->view('login');
		}

	}

	public function validate_login(){
		$email = $this->input->post('email');
     	$password = $this->input->post('password');
     	$credential = array('email' => $email, 'password' => sha1($password));

     	// Checking login credential for admin
     	$query = $this->db->get_where('users', $credential);
     	if ($query->num_rows() > 0) {
         	$row = $query->row();
         	if($row->role == 'superadmin'){
         		$this->session->set_userdata('superadmin_login', true);
	         	$this->session->set_userdata('user_id', $row->id);
	         	$this->session->set_userdata('school_id', $row->school_id);
	         	$this->session->set_userdata('user_name', $row->name);
	         	$this->session->set_userdata('user_type', 'superadmin');
	         	$this->session->set_flashdata('flash_message', get_phrase('login_successfully'));
	         	redirect(site_url('superadmin/dashboard'), 'refresh');
         	}
					elseif($row->role == 'admin'){
         		$this->session->set_userdata('admin_login', true);
	         	$this->session->set_userdata('user_id', $row->id);
	         	$this->session->set_userdata('school_id', $row->school_id);
	         	$this->session->set_userdata('user_name', $row->name);
	         	$this->session->set_userdata('user_type', 'admin');
	         	$this->session->set_flashdata('flash_message', get_phrase('login_successfully'));
	         	redirect(site_url('admin/dashboard'), 'refresh');
         	}
					elseif($row->role == 'student'){
         		$this->session->set_userdata('student_login', true);
	         	$this->session->set_userdata('user_id', $row->id);
	         	$this->session->set_userdata('school_id', $row->school_id);
	         	$this->session->set_userdata('user_name', $row->name);
	         	$this->session->set_userdata('user_type', 'student');
	         	$this->session->set_flashdata('flash_message', get_phrase('login_successfully'));
	         	redirect(site_url('student/dashboard'), 'refresh');
         	}
					elseif($row->role == 'librarian'){
         		$this->session->set_userdata('librarian_login', true);
	         	$this->session->set_userdata('user_id', $row->id);
	         	$this->session->set_userdata('school_id', $row->school_id);
	         	$this->session->set_userdata('user_name', $row->name);
	         	$this->session->set_userdata('user_type', 'librarian');
	         	$this->session->set_flashdata('flash_message', get_phrase('login_successfully'));
	         	redirect(site_url('librarian/dashboard'), 'refresh');
         	}
					elseif($row->role == 'accountant'){
         		$this->session->set_userdata('accountant_login', true);
	         	$this->session->set_userdata('user_id', $row->id);
	         	$this->session->set_userdata('school_id', $row->school_id);
	         	$this->session->set_userdata('user_name', $row->name);
	         	$this->session->set_userdata('user_type', 'accountant');
	         	$this->session->set_flashdata('flash_message', get_phrase('login_successfully'));
	         	redirect(site_url('accountant/dashboard'), 'refresh');
         	}
     	}else{
     		$this->session->set_flashdata('error_message', get_phrase('invalid_your_email_or_password'));
     		redirect(site_url('login'), 'refresh');
     	}
    }

	public function logout() {
        $this->session->sess_destroy();
        $this->session->set_flashdata('info_message', get_phrase('logged_out'));
        redirect(site_url('login'), 'refresh');
    }
}
