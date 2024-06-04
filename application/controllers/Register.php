<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Register extends CI_Controller
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


            $this->session->set_userdata('student_login', true);
            $this->session->set_userdata('user_id', $this->db->insert_id());
            $this->session->set_userdata('school_id', 1);
            $this->session->set_userdata('user_name', $data['name']);
            $this->session->set_userdata('user_type', 'student');
            $this->session->set_flashdata('success', get_phrase('registration_successfull'));

            if (isset($_SERVER['HTTP_REFERER'])) {
                redirect($_SERVER['HTTP_REFERER'], 'refresh');
            }

        }

    }




   public function validate_email()
{
    $json_data = json_decode(file_get_contents('php://input'), true);
    $email = $json_data['email'];
    $query = $this->db->get_where('users', array('email' => $email));
    $num_rows = $query->num_rows();

    if ($num_rows > 0) {
        echo json_encode(array('status' => false, 'debug' => 'Email exists, num_rows: ' . $num_rows, "email" => $email));
    } else {
        echo json_encode(array('status' => true, 'debug' => 'Email does not exist, num_rows: ' . $num_rows, "email" => $email));
    }
}






}