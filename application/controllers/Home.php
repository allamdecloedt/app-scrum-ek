<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
 *  @author   : Creativeitem
 *  date      : November, 2019
 *  Ekattor School Management System With Addons
 *  http://codecanyon.net/user/Creativeitem
 *  http://support.creativeitem.com
 */

class Home extends CI_Controller
{
	protected $theme;
	protected $active_school_id;

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->library('session');

		/*LOADING ALL THE MODELS HERE*/
		$this->load->model('Crud_model', 'crud_model');
		$this->load->model('User_model', 'user_model');
		$this->load->model('Settings_model', 'settings_model');
		$this->load->model('Payment_model', 'payment_model');
		$this->load->model('Email_model', 'email_model');
		$this->load->model('Addon_model', 'addon_model');
		$this->load->model('Frontend_model', 'frontend_model');

		if (addon_status('alumni')) {
			$this->load->model('addons/Alumni_model', 'alumni_model');
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

		if (!$this->session->userdata('active_school_id')) {
			$this->active_school_id_for_frontend();
		}
	}

	// INDEX FUNCTION
	// default function
	public function index()
	{
		$page_data['page_name'] = 'home';
		$page_data['page_title'] = get_phrase('home');
		$this->load->view('frontend/' . $this->theme . '/index', $page_data);
	}

	//ABOUT PAGE
	function about()
	{
		$page_data['page_name'] = 'about';
		$page_data['page_title'] = get_phrase('about_us');
		$this->load->view('frontend/' . $this->theme . '/index', $page_data);
	}

	// TEACHERS PAGE
	function teachers()
	{
		$count_teachers = $this->db->get_where('users', array('role' => 'teacher', 'school_id' => $this->active_school_id))->num_rows();
		$config = array();
		$config = manager($count_teachers, 9);
		$config['base_url'] = site_url('home/teachers/');
		$this->pagination->initialize($config);

		$page_data['per_page'] = $config['per_page'];
		$page_data['page_name'] = 'teacher';
		$page_data['page_title'] = get_phrase('teachers');
		$this->load->view('frontend/' . $this->theme . '/index', $page_data);
	}

	// EVENTS GETTING
	function events()
	{
		$count_events = $this->db->get_where('frontend_events', array('status' => 1, 'school_id' => $this->active_school_id))->num_rows();
		$config = array();
		$config = manager($count_events, 8);
		$config['base_url'] = site_url('home/events/');
		$this->pagination->initialize($config);

		$page_data['per_page'] = $config['per_page'];
		$page_data['page_name'] = 'event';
		$page_data['page_title'] = get_phrase('event_list');
		$this->load->view('frontend/' . $this->theme . '/index', $page_data);
	}

	// SCHOOL WISE GALLERY
	function gallery()
	{
		$count_gallery = $this->db->get_where('frontend_gallery', array('show_on_website' => 1, 'school_id' => $this->active_school_id))->num_rows();
		$config = array();
		$config = manager($count_gallery, 6);
		$config['base_url'] = site_url('home/gallery/');
		$this->pagination->initialize($config);

		$page_data['per_page'] = $config['per_page'];
		$page_data['page_name'] = 'gallery';
		$page_data['page_title'] = get_phrase('gallery');
		$this->load->view('frontend/' . $this->theme . '/index', $page_data);
	}

	// GALLERY DETAILS
	function gallery_view($gallery_id = '')
	{
		$count_images = $this->db->get_where(
			'frontend_gallery_image',
			array(
				'frontend_gallery_id' => $gallery_id
			)
		)->num_rows();
		$config = array();
		$config = manager($count_images, 9);
		$config['base_url'] = site_url('home/gallery_view/' . $gallery_id . '/');
		$this->pagination->initialize($config);

		$page_data['per_page'] = $config['per_page'];
		$page_data['gallery_id'] = $gallery_id;
		$page_data['page_name'] = 'gallery_view';
		$page_data['page_title'] = get_phrase('gallery');
		$this->load->view('frontend/' . $this->theme . '/index', $page_data);
	}

	//GET THE CONTACT PAGE
	function contact($param1 = '')
	{

		if ($param1 == 'send') {
			if (!$this->crud_model->check_recaptcha() && get_common_settings('recaptcha_status') == true) {
				redirect(site_url('home/contact'), 'refresh');
			}
			$this->frontend_model->send_contact_message();
			redirect(site_url('home/contact'), 'refresh');
		}
		$page_data['page_name'] = 'contact';
		$page_data['page_title'] = get_phrase('contact_us');
		$this->load->view('frontend/' . $this->theme . '/index', $page_data);
	}

	//GET THE PRIVACY POLICY PAGE
	function privacy_policy()
	{
		$page_data['page_name'] = 'privacy_policy';
		$page_data['page_title'] = get_phrase('privacy_policy');
		$this->load->view('frontend/' . $this->theme . '/index', $page_data);
	}

	//GET THE TERMS AND CONDITION PAGE
	function terms_conditions()
	{
		$page_data['page_name'] = 'terms_conditions';
		$page_data['page_title'] = get_phrase('terms_and_conditions');
		$this->load->view('frontend/' . $this->theme . '/index', $page_data);
	}

	//GET THE ALLUMNI EVENT PAGE IF THE ADDON IS ENABLED
	function alumni_event()
	{
		if (addon_status('alumni')) {
			$page_data['page_name'] = 'alumni_event';
			$page_data['page_title'] = get_phrase('alumni_event');
			$this->load->view('frontend/' . $this->theme . '/index', $page_data);
		} else {
			redirect(site_url(), 'refresh');
		}
	}

	//GET THE ALLUMNI GALLERY PAGE IF THE ADDON IS ENABLED
	function alumni_gallery()
	{
		if (addon_status('alumni')) {
			$page_data['page_name'] = 'alumni_gallery';
			$page_data['page_title'] = get_phrase('alumni_gallery');
			$this->load->view('frontend/' . $this->theme . '/index', $page_data);
		} else {
			redirect(site_url(), 'refresh');
		}
	}

	//GET THE ALLUMNI GALLERY DETAILS
	function alumni_gallery_view($gallery_id = '')
	{
		if (addon_status('alumni')) {
			$count_images = $this->db->get_where(
				'alumni_gallery_photos',
				array(
					'gallery_id' => $gallery_id
				)
			)->num_rows();
			$config = array();
			$config = manager($count_images, 9);
			$config['base_url'] = site_url('home/alumni_gallery_view/' . $gallery_id . '/');
			$this->pagination->initialize($config);

			$page_data['per_page'] = $config['per_page'];
			$page_data['gallery_id'] = $gallery_id;
			$page_data['page_name'] = 'alumni_gallery_view';
			$page_data['page_title'] = get_phrase('alumni_gallery');
			$this->load->view('frontend/' . $this->theme . '/index', $page_data);
		} else {
			redirect(site_url(), 'refresh');
		}
	}

	// NOTICEBOARD
	function noticeboard()
	{
		$count_notice = $this->db->get_where('noticeboard', array('show_on_website' => 1, 'school_id' => $this->active_school_id, 'session' => active_session()))->num_rows();
		$config = array();
		$config = manager($count_notice, 9);
		$config['base_url'] = site_url('home/noticeboard/');
		$this->pagination->initialize($config);

		$page_data['per_page'] = $config['per_page'];
		$page_data['page_name'] = 'noticeboard';
		$page_data['page_title'] = get_phrase('noticeboard');
		$this->load->view('frontend/' . $this->theme . '/index', $page_data);
	}

	function notice_details($notice_id = '')
	{
		$page_data['notice_id'] = $notice_id;
		$page_data['page_name'] = 'notice_details';
		$page_data['page_title'] = get_phrase('notice_details');
		$this->load->view('frontend/' . $this->theme . '/index', $page_data);
	}


	//Courses Overview Page
	function courses($param1 = null, $param2 = null)
	{
		$config = array();
		$config['base_url'] = site_url('home/courses/');
		$config['per_page'] = 8;
		$config['use_page_numbers'] = true;

		//check if a category is specified
		if ($param1 != null) {
			$cat_formated = str_replace("_", " ", $param1);
			$param1 = $cat_formated;
			$is_category = $this->frontend_model->contains("categories", "name", $param1);
		}

		//if a category is specified and schools exist in that category
		if ($is_category && $this->db->get_where('schools', array('category' => $param1))->num_rows() > 0) {

			$config['uri_segment'] = 4;
			$page = ($this->uri->segment($config['uri_segment'])) ? $this->uri->segment($config['uri_segment']) : 1;
			$offset = ($page - 1) * $config['per_page'];
			$page_data['schools'] = $this->user_model->get_schools_per_category($param1, $config['per_page'], $offset);
			$config['total_rows'] = $this->db->where('category', $param1)->count_all_results('schools');
			$page_data['statement'] = 1;
		}

		//if a category is specified but no schools exist in that category
		if ($is_category && $this->db->get_where('schools', array('category' => $param1))->num_rows() == 0) {

			$config['uri_segment'] = 4;
			$page = ($this->uri->segment($config['uri_segment'])) ? $this->uri->segment($config['uri_segment']) : 1;
			$offset = ($page - 1) * $config['per_page'];
			$config['total_rows'] = $this->db->count_all('schools');
			$page_data['schools'] = array();
			$page_data['no_courses_found'] = get_phrase('0_courses_found_in_category') . ' ' . $param1;
			$page_data['statement'] = 2;

		}

		//if no caterory is specified, but a page number is
		if ($param1 != null && is_numeric($param1)) {

			$config['uri_segment'] = 3;
			$page = ($this->uri->segment($config['uri_segment'])) ? $this->uri->segment($config['uri_segment']) : 1;
			$offset = ($page - 1) * $config['per_page'];
			$page_data['schools'] = $this->user_model->get_schools($config['per_page'], $offset);
			$config['total_rows'] = $this->db->count_all('schools');
			$page_data['statement'] = 4;
		}

		//if no category or page number is specifieds
		else if ($param1 == null) {

			$config['uri_segment'] = 2;
			$page = ($this->uri->segment($config['uri_segment'])) ? $this->uri->segment($config['uri_segment']) : 1;

			$page_data['schools'] = $this->user_model->get_schools($config['per_page'], $offset);
			$config['total_rows'] = $this->db->count_all('schools');
			$page_data['statement'] = 5;
		}

		//pagination bootstrap settings
		{

			$config['num_links'] = 1;

			$config['first_link'] = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-left" viewBox="0 0 16 16">
  			<path fill-rule="evenodd" d="M1.146 4.854a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H12.5A2.5 2.5 0 0 1 15 6.5v8a.5.5 0 0 1-1 0v-8A1.5 1.5 0 0 0 12.5 5H2.707l3.147 3.146a.5.5 0 1 1-.708.708z"/>
			</svg>';

			$config['last_link'] = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-right" viewBox="0 0 16 16">
  			<path fill-rule="evenodd" d="M14.854 4.854a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 4H3.5A2.5 2.5 0 0 0 1 6.5v8a.5.5 0 0 0 1 0v-8A1.5 1.5 0 0 1 3.5 5h9.793l-3.147 3.146a.5.5 0 0 0 .708.708z"/>
			</svg>';

			$config['next_link'] = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class=bi bi-arrow-bar-right" viewBox="0 0 16 16">
  			<path fill-rule="evenodd" d="M6 8a.5.5 0 0 0 .5.5h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L12.293 7.5H6.5A.5.5 0 0 0 6 8m-2.5 7a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5"/>
			</svg>';

			$config['prev_link'] = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-bar-left" viewBox="0 0 16 16">
  			<path fill-rule="evenodd" d="M12.5 15a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5M10 8a.5.5 0 0 1-.5.5H3.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L3.707 7.5H9.5a.5.5 0 0 1 .5.5"/>
			</svg>';

			$config['full_tag_open'] = '<nav aria-label="course page navigation "><div class="pagination-list">';
			$config['full_tag_close'] = '</div></nav>';
			$config['first_tag_open'] = '<div class="pagination-item">';
			$config['first_tag_close'] = '</div>';
			$config['last_tag_open'] = '<div class="pagination-item">';
			$config['last_tag_close'] = '</div>';
			$config['next_tag_open'] = '<div class="pagination-item">';
			$config['next_tag_close'] = '</div>';
			$config['prev_tag_open'] = '<div class="pagination-item">';
			$config['prev_tag_close'] = '</div>';
			$config['cur_tag_open'] = '<div class="pagination-item active"><a class="pagination-link" href="#">';
			$config['cur_tag_close'] = '</a></div>';
			$config['num_tag_open'] = '<div class="pagination-item">';
			$config['num_tag_close'] = '</div>';
			$config['attributes'] = array('class' => 'pagination-link');


		}

		//initialize pagination
		$this->pagination->initialize($config);

		//create pagination links
		$page_data['links'] = $this->pagination->create_links();

		//set page data
		$page_data['selected_category'] = $param1;
		$page_data['categories'] = $this->frontend_model->get_categories();
		$page_data['page_name'] = 'courses';
		$page_data['page_title'] = get_phrase('courses');
		$this->load->view('frontend/' . $this->theme . '/index', $page_data);

	}

	function courses_search()
	{

		$input = htmlspecialchars($this->input->get('search'));

		$config = array();
		$config['base_url'] = site_url('home/courses_search/');
		$config['suffix'] = '?search=' . urlencode($input);
		$config['per_page'] = 8;
		$config['use_page_numbers'] = true;
		$config['uri_segment'] = 3;


		if ($input == null) {

			$page = ($this->uri->segment($config['uri_segment'])) ? $this->uri->segment($config['uri_segment']) : 1;
			$offset = ($page - 1) * $config['per_page'];
			$page_data['schools'] = $this->user_model->get_schools($config['per_page'], $offset);
			$config['total_rows'] = $this->db->count_all('schools');
			$page_data['statement'] = 1;

		} else {

			$page = ($this->uri->segment($config['uri_segment'])) ? $this->uri->segment($config['uri_segment']) : 1;
			$offset = ($page - 1) * $config['per_page'];
			$page_data['input_search'] = $input;


			$page_data['schools'] = $this->user_model->get_schools_search($input, $config['per_page'], $offset);
			$config['total_rows'] = $this->user_model->get_schools_search_count($input);
			$page_data['statement'] = 2;

		}

		if ($page_data['schools']->num_rows() == 0) {
			$page_data['no_courses_found'] = get_phrase('0_courses_found_for_search') . ' ' . '"' . $input . '"';
		}

		//pagination bootstrap settings
		{
			$config['first_url'] = $config['base_url'] . $config['suffix'];


			$config['num_links'] = 1;

			$config['first_link'] = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-left" viewBox="0 0 16 16">
  			<path fill-rule="evenodd" d="M1.146 4.854a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H12.5A2.5 2.5 0 0 1 15 6.5v8a.5.5 0 0 1-1 0v-8A1.5 1.5 0 0 0 12.5 5H2.707l3.147 3.146a.5.5 0 1 1-.708.708z"/>
			</svg>';

			$config['last_link'] = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-right" viewBox="0 0 16 16">
  			<path fill-rule="evenodd" d="M14.854 4.854a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 4H3.5A2.5 2.5 0 0 0 1 6.5v8a.5.5 0 0 0 1 0v-8A1.5 1.5 0 0 1 3.5 5h9.793l-3.147 3.146a.5.5 0 0 0 .708.708z"/>
			</svg>';

			$config['next_link'] = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class=bi bi-arrow-bar-right" viewBox="0 0 16 16">
  			<path fill-rule="evenodd" d="M6 8a.5.5 0 0 0 .5.5h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L12.293 7.5H6.5A.5.5 0 0 0 6 8m-2.5 7a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5"/>
			</svg>';

			$config['prev_link'] = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-bar-left" viewBox="0 0 16 16">
  			<path fill-rule="evenodd" d="M12.5 15a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5M10 8a.5.5 0 0 1-.5.5H3.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L3.707 7.5H9.5a.5.5 0 0 1 .5.5"/>
			</svg>';

			$config['full_tag_open'] = '<nav aria-label="course page navigation "><div class="pagination-list">';
			$config['full_tag_close'] = '</div></nav>';
			$config['first_tag_open'] = '<div class="pagination-item">';
			$config['first_tag_close'] = '</div>';
			$config['last_tag_open'] = '<div class="pagination-item">';
			$config['last_tag_close'] = '</div>';
			$config['next_tag_open'] = '<div class="pagination-item">';
			$config['next_tag_close'] = '</div>';
			$config['prev_tag_open'] = '<div class="pagination-item">';
			$config['prev_tag_close'] = '</div>';
			$config['cur_tag_open'] = '<div class="pagination-item active"><a class="pagination-link" href="#">';
			$config['cur_tag_close'] = '</a></div>';
			$config['num_tag_open'] = '<div class="pagination-item">';
			$config['num_tag_close'] = '</div>';
			$config['attributes'] = array('class' => 'pagination-link');



		}

		//initialize pagination
		$this->pagination->initialize($config);

		//create pagination links
		$page_data['links'] = $this->pagination->create_links();

		//set page data


		$page_data['categories'] = $this->frontend_model->get_categories();
		$page_data['page_name'] = 'courses';
		$page_data['page_title'] = get_phrase('courses');
		$this->load->view('frontend/' . $this->theme . '/index', $page_data);


	}

	function course_details($course_name = '')
	{
		$page_data['school'] = $this->user_model->get_school_details( urldecode($course_name));
		$page_data['school_id'] = $this->user_model->get_school_id( urldecode($course_name));
		$page_data['course_name'] = urldecode($course_name) ;

		$page_data['page_name'] = 'course_details';
		$page_data['page_title'] = get_phrase('course_details');
		$this->load->view('frontend/' . $this->theme . '/index', $page_data);
	}







	// ACTIVE SCHOOL ID FOR FRONTEND
	function active_school_id_for_frontend($active_school_id = "")
	{
		if (addon_status('multi-school') && $active_school_id > 0) {
			$this->session->set_userdata('active_school_id', $active_school_id);
		} else {
			$active_school_id = get_settings('school_id');
			$this->session->set_userdata('active_school_id', $active_school_id);
		}
	}
}
