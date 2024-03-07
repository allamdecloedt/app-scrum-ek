<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Frontend_model extends CI_Model {

  protected $school_id;
	protected $active_session;

	public function __construct()
	{
		parent::__construct();
		$this->school_id = school_id();
		$this->active_session = active_session();
    $this->load->model('Crud_model',     'crud_model');
	}

  // get noticeboard
  function get_frontend_noticeboard() {
    $this->db->where('show_on_website', 1);
    $this->db->order_by('create_timestamp', 'DESC');
    $result = $this->db->get('noticeboard')->result_array();
    return $result;
  }

  function get_frontend_recent_noticeboard() {
    $this->db->where('show_on_website', 1);
    $this->db->order_by('create_timestamp', 'DESC');
    $this->db->limit(4);
    $result = $this->db->get('noticeboard')->result_array();
    return $result;
  }

  function get_frontend_all_events() {
    $this->db->where('status', 1);
    $this->db->order_by('timestamp', 'DESC');
    $result = $this->db->get('frontend_events')->result_array();
    return $result;
  }

  function get_frontend_upcoming_events() {
    $this->db->where('status', 1);
    $this->db->where('school_id', $this->get_active_school_id());
    $this->db->where('timestamp >', time());
    $this->db->limit(4);
    $result = $this->db->get('frontend_events')->result_array();
    return $result;
  }

  function get_frontend_teachers() {
    $this->db->where('show_on_website', 1);
    $result = $this->db->get('teacher')->result_array();
    return $result;
  }

  function get_frontend_notice_by_id($notice_id) {
    $this->db->where('id', $notice_id);
    $result = $this->db->get('noticeboard')->result_array();
    return $result;
  }

  // get all events
  function get_events() {
    $this->db->order_by('timestamp', "DESC");
    $events = $this->db->get('frontend_events')->result_array();
    return $events;
  }
  // add event
  function event_create() {
    $data['title']  = html_escape($this->input->post('title'));
    $data['timestamp']  = strtotime(html_escape($this->input->post('timestamp')));
    $data['status'] = html_escape($this->input->post('status'));
    $data['school_id'] = school_id();
    $data['created_by'] = $this->session->userdata('user_id');
    $this->db->insert('frontend_events', $data);

    $response = array(
			'status' => true,
			'notification' => get_phrase('event_added')
		);
		return json_encode($response);
  }
  // edit event
  function event_update($event_id) {
    $data['title']  = html_escape($this->input->post('title'));
    $data['timestamp']  = strtotime(html_escape($this->input->post('timestamp')));
    $data['status'] = html_escape($this->input->post('status'));

    $this->db->where('frontend_events_id', $event_id);
    $this->db->update('frontend_events', $data);

    $response = array(
			'status' => true,
			'notification' => get_phrase('event_added')
		);
		return json_encode($response);
  }
  // delete event
  function event_delete($event_id) {
    $this->db->where('frontend_events_id', $event_id);
    $this->db->delete('frontend_events');

    $response = array(
			'status' => true,
			'notification' => get_phrase('event_deleted')
		);
		return json_encode($response);
  }

  // news
  function get_news() {
    $this->db->order_by('date_added', 'DESC');
    $news = $this->db->get('frontend_news')->result_array();
    return $news;
  }

  function add_news() {
    $data['title']  = html_escape($this->input->post('title'));
    $data['description']  = html_escape($this->input->post('description'));
    $data['date_added'] = strtotime(html_escape($this->input->post('date')));
    if ($_FILES['news_image']['name'] != '') {
      $data['image']  = $_FILES['news_image']['name'];
      move_uploaded_file($_FILES['news_image']['tmp_name'], 'uploads/frontend/news_image/'. $_FILES['news_image']['name']);
    }
    $this->db->insert('frontend_news', $data);
  }

  function delete_news($news_id) {
    // delete the news image if exists
    $news_image = $this->db->get_where('frontend_news', array('frontend_news_id' => $news_id))->row()->image;
    if ($news_image != NULL) {
      if (file_exists('uploads/frontend/news_image/'. $news_image)) {
        unlink('uploads/frontend/news_image/'. $news_image);
      }
    }
    // delete the db entry
    $this->db->where('frontend_news_id', $news_id);
    $this->db->delete('frontend_news');
  }

  // gallery
  function get_gallaries() {
    $this->db->order_by('date_added', 'DESC');
    $result = $this->db->get('frontend_gallery')->result_array();
    return $result;
  }

  function get_gallery_info_by_id($gallery_id) {
    $this->db->where('frontend_gallery_id', $gallery_id);
    $result = $this->db->get('frontend_gallery')->result_array();
    return $result;
  }

  function add_frontend_gallery() {

    $data['title']            = html_escape($this->input->post('title'));
    $data['description']      = html_escape($this->input->post('description'));
    $data['show_on_website']  = htmlspecialchars($this->input->post('show_on_website'));
    $data['school_id']        = $this->school_id;
    $data['date_added']       = strtotime(html_escape($this->input->post('date_added')));

    if ($_FILES['cover_image']['name'] != '') {
      $data['image']  = random(15).'.jpg';
      move_uploaded_file($_FILES['cover_image']['tmp_name'], 'uploads/images/gallery_cover/'. $data['image']);
    }
    $this->db->insert('frontend_gallery', $data);
    $response = array(
			'status' => true,
			'notification' => get_phrase('gallery_added')
		);
		return json_encode($response);
  }

  function update_frontend_gallery($gallery_id) {
    $data['title']            = html_escape($this->input->post('title'));
    $data['description']      = html_escape($this->input->post('description'));
    $data['show_on_website']  = htmlspecialchars($this->input->post('show_on_website'));

    if ($_FILES['cover_image']['name'] != '') {
      $data['image']  = random(15).'.jpg';
      move_uploaded_file($_FILES['cover_image']['tmp_name'], 'uploads/images/gallery_cover/'. $data['image']);
    }
    $this->db->where('frontend_gallery_id', $gallery_id);
    $this->db->update('frontend_gallery', $data);
    $response = array(
			'status' => true,
			'notification' => get_phrase('gallery_updated')
		);
		return json_encode($response);
  }

  public function delete_frontend_gallery($gallery_id = "") {
      $this->db->where('frontend_gallery_id', $gallery_id);
      $this->db->delete('frontend_gallery');

      $response = array(
  			'status' => true,
  			'notification' => get_phrase('gallery_deleted')
  		);
  		return json_encode($response);
  }

  // Add Image in gallery
  public function upload_gallery_photo($gallery_id) {
    if (isset($_FILES['gallery_photo']) && !empty($_FILES['gallery_photo']['name'])) {
			$data['frontend_gallery_id'] = $gallery_id;
			$data['image'] = random(20).'.jpg';
			move_uploaded_file($_FILES['gallery_photo']['tmp_name'], 'uploads/images/gallery_images/'.$data['image']);

			$this->db->insert('frontend_gallery_image', $data);

			$response = array(
				'status' => true,
				'notification' => get_phrase('gallery_image_has_been_added_successfully')
			);
		}else{
			$response = array(
				'status' => false,
				'notification' => get_phrase('no_image_found')
			);
		}
		return json_encode($response);
  }

  //DELETE PHOTO FROM GALLERY
  public function delete_gallery_photo($gallery_photo_id) {
    $gallery_photo_previous_data = $this->db->get_where('frontend_gallery_image', array('frontend_gallery_image_id' => $gallery_photo_id))->row_array();
    $this->db->where('frontend_gallery_image_id', $gallery_photo_id);
    $this->db->delete('frontend_gallery_image');
    $this->remove_image('gallery_images', $gallery_photo_previous_data['image']);
    $response = array(
      'status' => true,
      'notification' => get_phrase('gallery_photo_deleted')
    );
    return json_encode($response);
  }
  function add_gallery_images($gallery_id) {
    $files = $_FILES;
    $number_of_images = count($_FILES['gallery_images']['name']);
    for ($i=0; $i < $number_of_images; $i++) {
      if ($files['gallery_images']['name'][$i] != '') {
        move_uploaded_file($files['gallery_images']['tmp_name'][$i], 'uploads/frontend/gallery_images/'. $files['gallery_images']['name'][$i]);
        $data['frontend_gallery_id']  = $gallery_id;
        $data['image']  = $files['gallery_images']['name'][$i];
        $this->db->insert('frontend_gallery_image', $data);
      }
    }
  }

  function get_frontend_gallery_images_limited($gallery_id) {
    $this->db->where('frontend_gallery_id', $gallery_id);
    $this->db->order_by('frontend_gallery_image_id', 'desc');
    $this->db->limit(4);
    $result = $this->db->get('frontend_gallery_image')->result_array();
    return $result;
  }

  function delete_gallery_image($gallery_image_id) {
    $image = $this->db->get_where('frontend_gallery_image', array(
      'frontend_gallery_image_id' => $gallery_image_id
    ))->row()->image;
    if (file_exists('uploads/frontend/gallery_images/'.$image)) {
      unlink('uploads/frontend/gallery_images/'.$image);
    }
    $this->db->where('frontend_gallery_image_id', $gallery_image_id);
    $this->db->delete('frontend_gallery_image');
  }

  function get_gallery_images($gallery_id) {
    $this->db->where('frontend_gallery_id', $gallery_id);
    $this->db->order_by('frontend_gallery_image_id', 'desc');
    $result = $this->db->get('frontend_gallery_image')->result_array();
    return $result;
  }

  //FRONTEND GALLERY
  public function get_photos_by_gallery_id($frontend_gallery_id = "") {
    $this->db->where('frontend_gallery_id', $frontend_gallery_id);
    return $this->db->get('frontend_gallery_image')->result_array();
  }

  public function get_gallery_image($image = "") {
    if (file_exists('uploads/images/gallery_images/'.$image))
    return base_url().'uploads/images/gallery_images/'.$image;
    else
    return base_url().'uploads/images/gallery_images/placeholder.png';
  }

  // get general settings
  function get_frontend_general_settings($type = '') {
    $result = $this->db->get_where('frontend_settings', array(
      'type' => $type
    ))->row()->description;
    return $result == null ? '' : $result;
  }

  // update terms and conditions
  function update_terms_and_conditions() {
    $data['terms_conditions']  = html_escape($this->input->post('terms_and_conditions'));
    $this->db->where('id', 1);
    $this->db->update('frontend_settings', $data);

    $response = array(
      'status' => true,
      'notification' => get_phrase('updated')
    );
    return json_encode($response);
  }

  // update privacy policy
  function update_privacy_policy() {
    $data['privacy_policy']  = html_escape($this->input->post('privacy_policy'));
    $this->db->where('id', 1);
    $this->db->update('frontend_settings', $data);

    $response = array(
      'status' => true,
      'notification' => get_phrase('updated')
    );
    return json_encode($response);
  }

  // update about us
  function update_about_us() {
    $data['about_us']  = html_escape($this->input->post('about_us'));
    $this->db->where('id', 1);
    $this->db->update('frontend_settings', $data);

    if ($_FILES['about_us_image']['name'] != '') {
      move_uploaded_file($_FILES['about_us_image']['tmp_name'], 'uploads/images/about_us/about-us.jpg');
    }

    $response = array(
      'status' => true,
      'notification' => get_phrase('updated')
    );
    return json_encode($response);
  }

  // send message from contact form
  function send_contact_message() {
    $first_name = html_escape($this->input->post('first_name'));
    $last_name = html_escape($this->input->post('last_name'));
    $email = html_escape($this->input->post('email'));
    $phone = html_escape($this->input->post('phone'));
    $address = html_escape($this->input->post('address'));
    $comment = html_escape($this->input->post('comment'));

    $receiver_email = $this->db->get_where('users', array('role' => 'superadmin'))->row('email');

    $msg = '<p>'.nl2br($comment)."</p>";
    $msg .= '<p>'.$first_name." ".$last_name.'</p>';
    $msg .= "<p>Phone : ".$phone.'</p>';
    $msg .= "<p>Address : ". $address.'</p>';

    $this->email_model->contact_message_email($email, $receiver_email, $msg);
  }

  // update slider images
  function update_homepage_slider() {
    $current_images_json = get_frontend_settings('slider_images');
    $current_images = json_decode($current_images_json);
    $slider = array();
    for ($i=0; $i < 3; $i++) {
      $image = $current_images[$i]->image;
      $data['title']  = html_escape($this->input->post('title_'.$i));
      $data['description']  = html_escape($this->input->post('description_'.$i));
      if ($_FILES['slider_image_'.$i]['name'] != '') {
        $data['image']  = $_FILES['slider_image_'.$i]['name'];
        move_uploaded_file($_FILES['slider_image_'.$i]['tmp_name'], 'uploads/images/slider/'. $data['image']);
      } else {
        $data['image']  = $image;
      }
      array_push($slider, $data);
    }

    $slider_data['slider_images']  = json_encode($slider);
    $this->db->where('id', 1);
    $this->db->update('frontend_settings', $slider_data);

    $response = array(
      'status' => true,
      'notification' => get_phrase('updated')
    );
    return json_encode($response);
  }

  // update general settings
  function update_frontend_general_settings() {
    $links = array();
    $social['facebook'] = html_escape($this->input->post('facebook_link'));
    $social['twitter'] = html_escape($this->input->post('twitter_link'));
    $social['linkedin'] = html_escape($this->input->post('linkedin_link'));
    $social['google'] = html_escape($this->input->post('google_link'));
    $social['youtube'] = html_escape($this->input->post('youtube_link'));
    $social['instagram'] = html_escape($this->input->post('instagram_link'));
    array_push($links, $social);

    $data['social_links'] = json_encode($links);
    $data['website_title'] = htmlspecialchars($this->input->post('website_title'));
    $data['homepage_note_title'] = htmlspecialchars($this->input->post('homepage_note_title'));
    $data['homepage_note_description'] = htmlspecialchars($this->input->post('homepage_note_description'));
    $data['copyright_text'] = htmlspecialchars($this->input->post('copyright_text'));
    $this->db->where('id', 1);
    $this->db->update('frontend_settings', $data);

    if ($_FILES['header_logo']['name'] != '') {
      move_uploaded_file($_FILES['header_logo']['tmp_name'], 'uploads/system/logo/header-logo.png');
    }

    if ($_FILES['footer_logo']['name'] != '') {
      move_uploaded_file($_FILES['footer_logo']['tmp_name'], 'uploads/system/logo/footer-logo.png');
    }

    $response = array(
			'status' => true,
			'notification' => get_phrase('general_settings_updated')
		);
		return json_encode($response);
  }

  // update general settings
  function other_settings_update() {
    if ($_FILES['login_banner']['name'] != '') {
      move_uploaded_file($_FILES['login_banner']['tmp_name'], 'assets/backend/images/bg-auth.jpg');
    }

    $response = array(
      'status' => true,
      'notification' => get_phrase('other_settings_updated')
    );
    return json_encode($response);
  }

  function update_recaptcha_settings() {
    $data1['description'] = htmlspecialchars($this->input->post('recaptcha_status'));
    $data2['description'] = htmlspecialchars($this->input->post('recaptcha_sitekey'));
    $data3['description'] = htmlspecialchars($this->input->post('recaptcha_secretkey'));
    $this->db->where('type', 'recaptcha_status');
    $this->db->update('common_settings', $data1);

    $this->db->where('type', 'recaptcha_sitekey');
    $this->db->update('common_settings', $data2);

    $this->db->where('type', 'recaptcha_secretkey');
    $this->db->update('common_settings', $data3);

    $response = array(
      'status' => true,
      'notification' => get_phrase('recaptcha_settings_updated')
    );
    return json_encode($response);
  }


  // MY CODE STARTS FROM HERE

  //GET ATIVE SCHOOL ID
  public function get_active_school_id() {
    if (addon_status('multi-school')) {
      if ($this->session->userdata('active_school_id') > 0) {
        return $this->session->userdata('active_school_id');
      }else{
        $active_school_id = get_settings('school_id');
        $this->session->set_userdata('active_school_id', $active_school_id);
        return $this->session->userdata('active_school_id');
      }
    }else{
      $active_school_id = get_settings('school_id');
      $this->session->set_userdata('active_school_id', $active_school_id);
      return $this->session->userdata('active_school_id');
    }
  }
  // GET HEADER LOGO
  public function get_header_logo() {
    return base_url('uploads/system/logo/header-logo.png');
  }
  // GET FOOTER LOGO
  public function get_footer_logo() {
    return base_url('uploads/system/logo/footer-logo.png');
  }

  //GET ABOUT IMAGE
  public function get_about_image() {
    return base_url('uploads/images/about_us/about-us.jpg');
  }

  //GET SLIDER IMAGE
  public function get_slider_image($image) {
    return base_url('uploads/images/slider/'.$image);
  }

  public function remove_image($type = "", $photo = "") {
		$path = 'uploads/images/'.$type.'/'.$photo;
		if(file_exists($path)){
			unlink($path);
		}
	}

  function online_admission(){
    $educational_qualifications = $_FILES['educational_qualifications']['name'];
    if(pathinfo($educational_qualifications, PATHINFO_EXTENSION) != 'pdf'){
      return json_encode(array('status' => 0, 'message' => get_phrase('attach_PDF_file_for_educational_qualification')));
    }

    $duplication_status = $this->user_model->check_duplication('on_create', $this->input->post('email_of_parent'));
    if($duplication_status){
      $user_data['name'] = htmlspecialchars($this->input->post('name_of_parent'));
      $user_data['email'] = htmlspecialchars($this->input->post('email_of_parent'));
      $user_data['password'] = sha1(rand(500, 10000));
      $user_data['gender'] = htmlspecialchars($this->input->post('parent_gender'));
      $user_data['phone'] = htmlspecialchars($this->input->post('phone_number_of_parent'));
      $user_data['role'] = 'parent';
      $user_data['school_id'] = $this->session->userdata('active_school_id');
      $user_data['watch_history'] = '[]';
      $this->db->insert('users', $user_data);
      $user_id = $this->db->insert_id();

      $parent_info['user_id'] = $user_id;
      $parent_info['school_id'] = $this->session->userdata('active_school_id');
      $this->db->insert('parents', $parent_info);
      $parent_id = $this->db->insert_id();
    }else{
      $user_id = $this->db->get_where('users', array('email' => $this->input->post('email_of_parent')))->row('id');
      $parent_id = $this->db->get_where('parents', array('user_id' => $user_id))->row('id');
    }


    $duplication_status = $this->user_model->check_duplication('on_create', $this->input->post('email'));
    if($duplication_status){
      $user_data['name'] = htmlspecialchars($this->input->post('name'));
      $user_data['email'] = htmlspecialchars($this->input->post('email'));
      $user_data['password'] = sha1(rand(500, 10000));
      $user_data['gender'] = htmlspecialchars($this->input->post('gender'));
      $user_data['phone'] = htmlspecialchars($this->input->post('phone'));
      $user_data['birthday'] = htmlspecialchars($this->input->post('date_of_birth'));
      $user_data['blood_group'] = htmlspecialchars($this->input->post('blood_group'));
      $user_data['role'] = 'student';
      $user_data['school_id'] = $this->session->userdata('active_school_id');
      $user_data['watch_history'] = '[]';
      $user_data['status'] = 3;
      $this->db->insert('users', $user_data);
      $user_id = $this->db->insert_id();

      $student_data['code'] = student_code();
      $student_data['user_id'] = $user_id;
      $student_data['parent_id'] = $parent_id;
      $student_data['session'] = active_session();
      $student_data['school_id'] = $this->session->userdata('active_school_id');
      $this->db->insert('students', $student_data);
      $student_id = $this->db->insert_id();

      move_uploaded_file($_FILES['student_image']['tmp_name'], 'uploads/users/'.$user_id.'.jpg');
      move_uploaded_file($_FILES['educational_qualifications']['tmp_name'], 'uploads/admission_docs/'.$user_id.'.pdf');
      return json_encode(array('status' => 1, 'message' => get_phrase('successfully_has_been_recoded_your_request').'. '.get_phrase('you_will_be_notified_by_email_address_about_this_request')));
    }else{
      return json_encode(array('status' => 0, 'message' => get_phrase('this_student_email_already_exist')));
    }
  }
}
