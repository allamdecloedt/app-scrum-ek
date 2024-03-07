<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*  @author   : Creativeitem
*  date      : November, 2019
*  Ekattor School Management System With Addons
*  http://codecanyon.net/user/Creativeitem
*  http://support.creativeitem.com
*/

class Home extends CI_Controller {
	protected $theme;
	protected $active_school_id;

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

		if (addon_status('alumni')) {
			$this->load->model('addons/Alumni_model','alumni_model');
		}
		/*cache control*/
		$this->output->set_header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
		$this->output->set_header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		$this->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
		$this->output->set_header("Pragma: no-cache");

		/*SET DEFAULT TIMEZONE*/
		timezone();

		$this->theme = get_frontend_settings('theme');
		$this->active_school_id = $this->frontend_model->get_active_school_id();

		if(!$this->session->userdata('active_school_id')){
			$this->active_school_id_for_frontend();
		}
	}

	// INDEX FUNCTION
	// default function
	public function index() {
		$page_data['page_name']  = 'home';
		$page_data['page_title'] = get_phrase('home');
		$this->load->view('frontend/'.$this->theme.'/index', $page_data);
	}

	//ABOUT PAGE
	function about() {
		$page_data['page_name']  = 'about';
		$page_data['page_title'] = get_phrase('about_us');
		$this->load->view('frontend/'.$this->theme.'/index', $page_data);
	}

	// TEACHERS PAGE
	function teachers() {
		$count_teachers = $this->db->get_where('users', array('role' => 'teacher', 'school_id' => $this->active_school_id))->num_rows();
		$config = array();
		$config = manager($count_teachers, 9);
		$config['base_url']  = site_url('home/teachers/');
		$this->pagination->initialize($config);

		$page_data['per_page']    = $config['per_page'];
		$page_data['page_name']  = 'teacher';
		$page_data['page_title'] = get_phrase('teachers');
		$this->load->view('frontend/'.$this->theme.'/index', $page_data);
	}

	// EVENTS GETTING
	function events() {
		$count_events = $this->db->get_where('frontend_events', array('status' => 1, 'school_id' => $this->active_school_id))->num_rows();
		$config = array();
		$config = manager($count_events, 8);
		$config['base_url']  = site_url('home/events/');
		$this->pagination->initialize($config);

		$page_data['per_page']    = $config['per_page'];
		$page_data['page_name']  = 'event';
		$page_data['page_title'] = get_phrase('event_list');
		$this->load->view('frontend/'.$this->theme.'/index', $page_data);
	}

	// SCHOOL WISE GALLERY
	function gallery() {
		$count_gallery = $this->db->get_where('frontend_gallery', array('show_on_website' => 1, 'school_id' => $this->active_school_id))->num_rows();
		$config = array();
		$config = manager($count_gallery, 6);
		$config['base_url']  = site_url('home/gallery/');
		$this->pagination->initialize($config);

		$page_data['per_page']    = $config['per_page'];
		$page_data['page_name']  = 'gallery';
		$page_data['page_title'] = get_phrase('gallery');
		$this->load->view('frontend/'.$this->theme.'/index', $page_data);
	}

	// GALLERY DETAILS
	function gallery_view($gallery_id = '') {
		$count_images = $this->db->get_where('frontend_gallery_image', array(
			'frontend_gallery_id' => $gallery_id
		))->num_rows();
		$config = array();
		$config = manager($count_images, 9);
		$config['base_url']  = site_url('home/gallery_view/'.$gallery_id.'/');
		$this->pagination->initialize($config);

		$page_data['per_page']    = $config['per_page'];
		$page_data['gallery_id']  = $gallery_id;
		$page_data['page_name']  = 'gallery_view';
		$page_data['page_title'] = get_phrase('gallery');
		$this->load->view('frontend/'.$this->theme.'/index', $page_data);
	}

	//GET THE CONTACT PAGE
	function contact($param1 = '') {

		if ($param1 == 'send') {
			if(!$this->crud_model->check_recaptcha() && get_common_settings('recaptcha_status') == true){
				redirect(site_url('home/contact'), 'refresh');
			}
			$this->frontend_model->send_contact_message();
			redirect(site_url('home/contact'), 'refresh');
		}
		$page_data['page_name']  = 'contact';
		$page_data['page_title'] = get_phrase('contact_us');
		$this->load->view('frontend/'.$this->theme.'/index', $page_data);
	}

	//GET THE PRIVACY POLICY PAGE
	function privacy_policy() {
		$page_data['page_name']  = 'privacy_policy';
		$page_data['page_title'] = get_phrase('privacy_policy');
		$this->load->view('frontend/'.$this->theme.'/index', $page_data);
	}

	//GET THE TERMS AND CONDITION PAGE
	function terms_conditions() {
		$page_data['page_name']  = 'terms_conditions';
		$page_data['page_title'] = get_phrase('terms_and_conditions');
		$this->load->view('frontend/'.$this->theme.'/index', $page_data);
	}

	//GET THE ALLUMNI EVENT PAGE IF THE ADDON IS ENABLED
	function alumni_event() {
		if (addon_status('alumni')) {
			$page_data['page_name']  = 'alumni_event';
			$page_data['page_title'] = get_phrase('alumni_event');
			$this->load->view('frontend/'.$this->theme.'/index', $page_data);
		}else{
			redirect(site_url(), 'refresh');
		}
	}

	//GET THE ALLUMNI GALLERY PAGE IF THE ADDON IS ENABLED
	function alumni_gallery() {
		if (addon_status('alumni')) {
			$page_data['page_name']  = 'alumni_gallery';
			$page_data['page_title'] = get_phrase('alumni_gallery');
			$this->load->view('frontend/'.$this->theme.'/index', $page_data);
		}else{
			redirect(site_url(), 'refresh');
		}
	}

	//GET THE ALLUMNI GALLERY DETAILS
	function alumni_gallery_view($gallery_id = '') {
		if (addon_status('alumni')) {
			$count_images = $this->db->get_where('alumni_gallery_photos', array(
				'gallery_id' => $gallery_id
			))->num_rows();
			$config = array();
			$config = manager($count_images, 9);
			$config['base_url']  = site_url('home/alumni_gallery_view/'.$gallery_id.'/');
			$this->pagination->initialize($config);

			$page_data['per_page']    = $config['per_page'];
			$page_data['gallery_id']  = $gallery_id;
			$page_data['page_name']  = 'alumni_gallery_view';
			$page_data['page_title'] = get_phrase('alumni_gallery');
			$this->load->view('frontend/'.$this->theme.'/index', $page_data);
		}else{
			redirect(site_url(), 'refresh');
		}
	}

	// NOTICEBOARD
	function noticeboard() {
		$count_notice = $this->db->get_where('noticeboard', array('show_on_website' => 1, 'school_id' => $this->active_school_id, 'session' => active_session()))->num_rows();
		$config = array();
		$config = manager($count_notice, 9);
		$config['base_url']  = site_url('home/noticeboard/');
		$this->pagination->initialize($config);

		$page_data['per_page']    = $config['per_page'];
		$page_data['page_name']  = 'noticeboard';
		$page_data['page_title'] = get_phrase('noticeboard');
		$this->load->view('frontend/'.$this->theme.'/index', $page_data);
	}

	function notice_details($notice_id = '') {
		$page_data['notice_id'] = $notice_id;
		$page_data['page_name']  = 'notice_details';
		$page_data['page_title'] = get_phrase('notice_details');
		$this->load->view('frontend/'.$this->theme.'/index', $page_data);
	}

	function online_admission($param1 = ""){
		

		if($param1 == 'submit'){
			if(!$this->crud_model->check_recaptcha() && get_common_settings('recaptcha_status') == true){
				redirect(site_url('home/contact'), 'refresh');
			}
			echo $this->frontend_model->online_admission();
			return;
		}

		$page_data['page_name']  = 'online_admission';
		$page_data['page_title'] = get_phrase('online_admission');
		$this->load->view('frontend/'.$this->theme.'/index', $page_data);
	}

























	// ACTIVE SCHOOL ID FOR FRONTEND
	function active_school_id_for_frontend($active_school_id = "") {
		if (addon_status('multi-school') && $active_school_id > 0) {
			$this->session->set_userdata('active_school_id', $active_school_id);
		}else{
			$active_school_id = get_settings('school_id');
			$this->session->set_userdata('active_school_id', $active_school_id);
		}
	}
}
