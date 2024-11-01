<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
 *  @author   : Creativeitem
 *  date      : November, 2019
 *  Ekattor School Management System With Addons
 *  http://codecanyon.net/user/Creativeitem
 *  http://support.creativeitem.com
 */

class Login extends CI_Controller
{


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

		/*cache control*/
		$this->output->set_header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
		$this->output->set_header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		$this->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
		$this->output->set_header("Pragma: no-cache");

		/*SET DEFAULT TIMEZONE*/
		timezone();
	}

	public function index()
	{
		if ($this->session->userdata('superadmin_login') == true) {
			redirect(route('dashboard'), 'refresh');
		} elseif ($this->session->userdata('admin_login') == true) {
			redirect(route('dashboard'), 'refresh');
		} elseif ($this->session->userdata('teacher_login') == true) {
			redirect(route('dashboard'), 'refresh');
		} elseif ($this->session->userdata('parent_login') == true) {
			redirect(route('dashboard'), 'refresh');
		} elseif ($this->session->userdata('student_login') == true) {
			redirect(route('dashboard'), 'refresh');
		} elseif ($this->session->userdata('accountant_login') == true) {
			redirect(route('dashboard'), 'refresh');
		} elseif ($this->session->userdata('librarian_login') == true) {
			redirect(route('dashboard'), 'refresh');
		} elseif ($this->session->userdata('driver_login') == true) {
			redirect(route('dashboard'), 'refresh');
		} else {
			$this->load->view('login');
		}
	}

	public function validate_login()
	{
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$credential = array('email' => $email, 'password' => sha1($password));

		// Checking login credential for admin
		$query = $this->db->get_where('users', $credential);
		if ($query->num_rows() > 0) {
			$row = $query->row();
			$this->session->set_userdata('user_login_type', true);
			if ($row->role == 'superadmin') {
				$this->session->set_userdata('superadmin_login', true);
				$this->session->set_userdata('user_id', $row->id);
				$this->session->set_userdata('school_id', $row->school_id);
				$this->session->set_userdata('user_name', $row->name);
				$this->session->set_userdata('user_type', 'superadmin');
				$this->session->set_flashdata('flash_message', get_phrase('welcome_back'));
				redirect(site_url('superadmin/dashboard'), 'refresh');
			} elseif ($row->role == 'admin') {
				$this->session->set_userdata('admin_login', true);
				$this->session->set_userdata('user_id', $row->id);
				$this->session->set_userdata('school_id', $row->school_id);
				$this->session->set_userdata('user_name', $row->name);
				$this->session->set_userdata('user_type', 'admin');
				$this->session->set_flashdata('flash_message', get_phrase('welcome_back'));
				redirect(site_url('admin/dashboard'), 'refresh');
			} elseif ($row->role == 'teacher') {
				$this->session->set_userdata('teacher_login', true);
				$this->session->set_userdata('user_id', $row->id);
				$this->session->set_userdata('school_id', $row->school_id);
				$this->session->set_userdata('user_name', $row->name);
				$this->session->set_userdata('user_type', 'teacher');
				$this->session->set_flashdata('flash_message', get_phrase('welcome_back'));
				redirect(site_url('teacher/dashboard'), 'refresh');
			} elseif ($row->role == 'student') {
				if ($row->status != 1) {
					$this->session->set_flashdata('error_message', get_phrase('your_account_has_been_disabled'));
					redirect(site_url('login'), 'refresh');
				}
				$this->session->set_userdata('student_login', true);
				$this->session->set_userdata('user_id', $row->id);
				$this->session->set_userdata('school_id', $row->school_id);
				$this->session->set_userdata('user_name', $row->name);
				$this->session->set_userdata('user_type', 'student');
				$this->session->set_flashdata('flash_message', get_phrase('welcome_back'));
				redirect(site_url('student/dashboard'), 'refresh');
			} elseif ($row->role == 'librarian') {
				$this->session->set_userdata('librarian_login', true);
				$this->session->set_userdata('user_id', $row->id);
				$this->session->set_userdata('school_id', $row->school_id);
				$this->session->set_userdata('user_name', $row->name);
				$this->session->set_userdata('user_type', 'librarian');
				$this->session->set_flashdata('flash_message', get_phrase('welcome_back'));
				redirect(site_url('librarian/dashboard'), 'refresh');
			} elseif ($row->role == 'accountant') {
				$this->session->set_userdata('accountant_login', true);
				$this->session->set_userdata('user_id', $row->id);
				$this->session->set_userdata('school_id', $row->school_id);
				$this->session->set_userdata('user_name', $row->name);
				$this->session->set_userdata('user_type', 'accountant');
				$this->session->set_flashdata('flash_message', get_phrase('welcome_back'));
				redirect(site_url('accountant/dashboard'), 'refresh');
			} elseif ($row->role == 'driver') {
				$this->session->set_userdata('driver_login', true);
				$this->session->set_userdata('user_id', $row->id);
				$this->session->set_userdata('school_id', $row->school_id);
				$this->session->set_userdata('user_name', $row->name);
				$this->session->set_userdata('user_type', 'driver');
				$this->session->set_flashdata('flash_message', get_phrase('welcome_back'));
				redirect(site_url('driver/dashboard'), 'refresh');
			}
		} else {
			$this->session->set_flashdata('error_message', get_phrase('invalid_your_email_or_password'));
			redirect(site_url('login'), 'refresh');
		}
	}

	public function validate_login_frontend()
	{
		$email = htmlspecialchars($this->input->post('login_email'));
		$password = $this->input->post('login_password');
		$credential = array('email' => $email, 'password' => sha1($password));

		// Checking login credential for admin
		$query = $this->db->get_where('users', $credential);
		if ($query->num_rows() > 0) {
			$row = $query->row();
			$this->session->set_userdata('user_login_type', true);
			if ($row->role == 'superadmin') {
				$this->session->set_userdata('superadmin_login', true);
				$this->session->set_userdata('user_id', $row->id);
				$this->session->set_userdata('school_id', $row->school_id);
				$this->session->set_userdata('user_name', $row->name);
				$this->session->set_userdata('user_type', 'superadmin');
				$this->session->set_flashdata('flash_message', get_phrase('welcome_back'));
				if (isset($_SERVER['HTTP_REFERER'])) {
					redirect($_SERVER['HTTP_REFERER'], 'refresh');
				}
			} elseif ($row->role == 'admin') {
				$this->session->set_userdata('admin_login', true);
				$this->session->set_userdata('user_id', $row->id);
				$this->session->set_userdata('school_id', $row->school_id);
				$this->session->set_userdata('user_name', $row->name);
				$this->session->set_userdata('user_type', 'admin');
				$this->session->set_flashdata('flash_message', get_phrase('welcome_back'));
				if (isset($_SERVER['HTTP_REFERER'])) {
					redirect($_SERVER['HTTP_REFERER'], 'refresh');
				}
			} elseif ($row->role == 'teacher') {
				$this->session->set_userdata('teacher_login', true);
				$this->session->set_userdata('user_id', $row->id);
				$this->session->set_userdata('school_id', $row->school_id);
				$this->session->set_userdata('user_name', $row->name);
				$this->session->set_userdata('user_type', 'teacher');
				$this->session->set_flashdata('flash_message', get_phrase('welcome_back'));
				if (isset($_SERVER['HTTP_REFERER'])) {
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
				}
			} elseif ($row->role == 'student') {
				if ($row->status != 1) {
					$this->session->set_flashdata('error_message', get_phrase('your_account_has_been_disabled'));
					if (isset($_SERVER['HTTP_REFERER'])) {
					redirect($_SERVER['HTTP_REFERER'], 'refresh');
				}
				}
				$this->session->set_userdata('student_login', true);
				$this->session->set_userdata('user_id', $row->id);
				$this->session->set_userdata('school_id', $row->school_id);
				$this->session->set_userdata('user_name', $row->name);
				$this->session->set_userdata('user_type', 'student');
				$this->session->set_flashdata('flash_message', get_phrase('welcome_back'));
				if (isset($_SERVER['HTTP_REFERER'])) {
					redirect($_SERVER['HTTP_REFERER'], 'refresh');
				}
			} elseif ($row->role == 'parent') {
				$this->session->set_userdata('parent_login', true);
				$this->session->set_userdata('user_id', $row->id);
				$this->session->set_userdata('school_id', $row->school_id);
				$this->session->set_userdata('user_name', $row->name);
				$this->session->set_userdata('user_type', 'parent');
				$this->session->set_flashdata('flash_message', get_phrase('welcome_back'));
				if (isset($_SERVER['HTTP_REFERER'])) {
					redirect($_SERVER['HTTP_REFERER'], 'refresh');
				}
			} elseif ($row->role == 'librarian') {
				$this->session->set_userdata('librarian_login', true);
				$this->session->set_userdata('user_id', $row->id);
				$this->session->set_userdata('school_id', $row->school_id);
				$this->session->set_userdata('user_name', $row->name);
				$this->session->set_userdata('user_type', 'librarian');
				$this->session->set_flashdata('flash_message', get_phrase('welcome_back'));
				if (isset($_SERVER['HTTP_REFERER'])) {
					redirect($_SERVER['HTTP_REFERER'], 'refresh');
				}
			} elseif ($row->role == 'accountant') {
				$this->session->set_userdata('accountant_login', true);
				$this->session->set_userdata('user_id', $row->id);
				$this->session->set_userdata('school_id', $row->school_id);
				$this->session->set_userdata('user_name', $row->name);
				$this->session->set_userdata('user_type', 'accountant');
				$this->session->set_flashdata('flash_message', get_phrase('welcome_back'));
				if (isset($_SERVER['HTTP_REFERER'])) {
					redirect($_SERVER['HTTP_REFERER'], 'refresh');
				}
			} elseif ($row->role == 'driver') {
				$this->session->set_userdata('driver_login', true);
				$this->session->set_userdata('user_id', $row->id);
				$this->session->set_userdata('school_id', $row->school_id);
				$this->session->set_userdata('user_name', $row->name);
				$this->session->set_userdata('user_type', 'driver');
				$this->session->set_flashdata('flash_message', get_phrase('welcome_back'));
				if (isset($_SERVER['HTTP_REFERER'])) {
					redirect($_SERVER['HTTP_REFERER'], 'refresh');
				}
			}
		} else {
			$this->session->set_flashdata('error_message', get_phrase('invalid_your_email_or_password'));
			redirect($_SERVER['HTTP_REFERER'], 'refresh');
		}
	}

	public function logout()
	{
		$this->session->sess_destroy();

		$this->session->set_flashdata('info_message', get_phrase('logged_out'));

		if (isset($_SERVER['HTTP_REFERER'])) {
			// Capture the referring URL
			redirect($_SERVER['HTTP_REFERER'], 'refresh');
		} else {
			redirect(site_url('login'), 'refresh');
		}
	}

	// RETREIVE PASSWORD
	public function retrieve_password()
	{
		$email = $this->input->post('email');
		$query = $this->db->get_where('users', array('email' => $email));
		if ($query->num_rows() > 0) {
			$query = $query->row_array();
			$new_password = substr(md5(rand(100000000, 20000000000)), 0, 7);

			// updating the database
			$updater = array(
				'password' => sha1($new_password)
			);
			$this->db->where('id', $query['id']);
			$this->db->update('users', $updater);

			// sending mail to user
			$this->email_model->password_reset_email($new_password, $query['id']);

			$this->session->set_flashdata('flash_message', get_phrase('please_check_your_mail_inbox'));
			redirect(site_url('login'), 'refresh');
		} else {
			$this->session->set_flashdata('error_message', get_phrase('wrong_credential'));
			redirect(site_url('login'), 'refresh');
		}
	}

		// RETREIVE PASSWORD
		public function retrieve_password_site()
		{
			$email = $this->input->post('email');
			$query = $this->db->get_where('users', array('email' => $email));
			if ($query->num_rows() > 0) {
				$query = $query->row_array();
				$new_password = substr(md5(rand(100000000, 20000000000)), 0, 7);
	
				// updating the database
				$updater = array(
					'password' => sha1($new_password)
				);
				$this->db->where('id', $query['id']);
				$this->db->update('users', $updater);
	
				// sending mail to user
				$this->email_model->password_reset_email($new_password, $query['id']);

				$this->session->set_flashdata('message', get_phrase('please_check_your_mail_inbox'));
				$this->session->set_flashdata('message_type', 'success');
			      
            		redirect($_SERVER['HTTP_REFERER'], 'refresh');
       				 
			} else {

				$this->session->set_flashdata('message', get_phrase('invalid_your_email'));
				$this->session->set_flashdata('message_type', 'danger');

						redirect($_SERVER['HTTP_REFERER'], 'refresh');
			
			}
		}
		public function send_reset_link()
		{
			
			$email = $this->input->post('email');
			$query = $this->db->get_where('users', array('email' => $email));

			if ($query->num_rows() > 0) {
				$user = $query->row_array();
				
				// Generate a secure token
				$token = bin2hex(random_bytes(50));
				
				// Set the expiration date (1 hour from now)
				$expires_at = date("Y-m-d H:i:s", strtotime('+24 hour'));

				// Update the database with the token and expiration
				$this->db->where('id', $user['id']);
				$this->db->update('users', array(
					'reset_token' => $token,
					'reset_expires_at' => $expires_at
				));

				// Create the reset link
				$reset_link = base_url("login/new_password?token=" . $token);
				// print_r($reset_link);
				// die;
				// Send the email
				$this->email_model->password_reset_email_link($reset_link,$user['id']);

				// Vérifier si l'alerte a déjà été affichée
				// if (!$this->session->userdata('alert_shown')) {
					$this->session->set_flashdata('message', get_phrase('please_check_your_mail_inbox'));
					$this->session->set_flashdata('message_type', 'success');
					
				// }

            		redirect($_SERVER['HTTP_REFERER'], 'refresh');

			
			} else {
				// If the email is not found
				$this->session->set_flashdata('message', get_phrase('invalid_email_address'));
				$this->session->set_flashdata('message_type', 'danger');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			}
		}
		public function new_password(){
			$this->load->view('reset_password');
		}
		public function new_password_student(){
			$this->load->view('new_password');
		}
		public function reset_password()
		{
			$token = $this->input->get('token');
			$query = $this->db->get_where('users', array('reset_token' => $token));

			if ($query->num_rows() > 0) {
				$user = $query->row_array();
				// Vérifier si le jeton n'a pas expiré
				if (strtotime($user['reset_expires_at']) > time()) {
					$new_password = $this->input->post('new_password');
					$confirm_password = $this->input->post('confirm_password');

					if ($new_password === $confirm_password) {

						// Mettre à jour le mot de passe
						$this->db->where('id', $user['id']);
						$this->db->update('users', array(
							'password' => sha1($new_password),
							'reset_token' => NULL,
							'reset_expires_at' => NULL
						));

						// Message de succès
						$this->session->set_flashdata('message', get_phrase('password_reset_successful'));
						$this->session->set_flashdata('message_type', 'success');
						redirect('login');
					} else {
						// Les mots de passe ne correspondent pas
						$this->session->set_flashdata('message', get_phrase('passwords_do_not_match'));
						$this->session->set_flashdata('message_type', 'danger');
						redirect($_SERVER['HTTP_REFERER'], 'refresh');
					}
				} else {
					// Jeton expiré
					$this->session->set_flashdata('message', get_phrase('reset_link_expired'));
					$this->session->set_flashdata('message_type', 'danger');
					redirect('login/new_password');
				}
			} else {
				// Jeton invalide
				$this->session->set_flashdata('message', get_phrase('invalid_reset_link'));
				$this->session->set_flashdata('message_type', 'danger');
				redirect('login/new_password');
			}
		}

		public function add_new_password()
		{
			$user_id = $this->input->get('user_id');
			$query = $this->db->get_where('users', array('id' => $user_id));

			if ($query->num_rows() > 0) {
				$user = $query->row_array();
				// Vérifier si le jeton n'a pas expiré
				
					$new_password = $this->input->post('new_password');
					$confirm_password = $this->input->post('confirm_password');

					if ($new_password === $confirm_password) {

						// Mettre à jour le mot de passe
						$this->db->where('id', $user['id']);
						$this->db->update('users', array(
							'password' => sha1($new_password),
							
							
						));

						// Message de succès
						$this->session->set_flashdata('message', get_phrase('password_add_successful'));
						$this->session->set_flashdata('message_type', 'success');
						redirect('login');
					} else {
						// Les mots de passe ne correspondent pas
						$this->session->set_flashdata('message', get_phrase('passwords_do_not_match'));
						$this->session->set_flashdata('message_type', 'danger');
						redirect($_SERVER['HTTP_REFERER'], 'refresh');
					}
				
			} else {
				// Jeton invalide
				$this->session->set_flashdata('message', get_phrase('invalid_reset_link'));
				$this->session->set_flashdata('message_type', 'danger');
				redirect('login/new_password');
			}
		}


		 public function validate_credentials()
    {
        // $json_data = json_decode(file_get_contents('php://input'), true);
        $email = $this->input->post('email');
		$password = $this->input->post('password');
        $query = $this->db->get_where('users', array('email' => $email, 'password' => sha1($password)));
        $num_rows = $query->num_rows();
		// Préparer le nouveau jeton CSRF
	    $csrf = array(
			'csrfName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash(),
			);
		  


        if ($num_rows > 0) {
            echo json_encode(array('status' => true, 'debug' => 'Welcome' , 'csrf' => $csrf));
        } else {
            echo json_encode(array('status' => false, 'debug' => 'Credentials incorrect' , 'csrf' => $csrf));
        }
    }
	public function validate_code() {
		$user_id = $this->input->post('user_id');
		$code = $this->input->post('validation_code');
	
		$query = $this->db->get_where('users', array('id' => $user_id))->row_array();
		if (sizeof($query) > 0) {
			// Check if the code matches and if it's still valid
			if ($query['validation_code'] == $code && strtotime($query['validation_expires_at']) > time()) {
				// Code is valid
				$this->session->set_flashdata('message', 'Validation successful');
				$this->session->set_flashdata('message_type', 'success');
			} else {
				// Code is invalid or expired
				$this->session->set_flashdata('message', 'Invalid or expired validation code');
				$this->session->set_flashdata('message_type', 'danger');
			}
		} else {
			$this->session->set_flashdata('message', 'User not found');
			$this->session->set_flashdata('message_type', 'danger');
		}
	
		redirect($_SERVER['HTTP_REFERER'], 'refresh');
	}
	

}
