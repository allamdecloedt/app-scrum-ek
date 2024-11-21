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

        $this->user_model->register_user();

        if (isset($_SERVER['HTTP_REFERER'])) {
            redirect($_SERVER['HTTP_REFERER'], 'refresh');
        }



    }

     public function register_user_form()
    {

        
        echo $this->user_model->register_user_form();
        return;
        if (isset($_SERVER['HTTP_REFERER'])) {
            redirect($_SERVER['HTTP_REFERER'], 'refresh');
        }



    }


    public function validate_email()
    {
        // $json_data = json_decode(file_get_contents('php://input'), true);
        $email = $this->input->post('email');
      
        $query = $this->db->get_where('users', array('email' => $email));
        $num_rows = $query->num_rows();
                  // Préparer le nouveau jeton CSRF
                  $csrf = array(
                    'csrfName' => $this->security->get_csrf_token_name(),
                    'csrfHash' => $this->security->get_csrf_hash(),
                );
          


        if ($num_rows > 0) {
            echo json_encode(array('status' => false, 'debug' => 'Email exists, num_rows: ' . $num_rows, "email" => $email, 'csrf' => $csrf));
        } else {
            echo json_encode(array('status' => true, 'debug' => 'Email does not exist, num_rows: ' . $num_rows, "email" => $email , 'csrf' => $csrf));
        }
    }






}