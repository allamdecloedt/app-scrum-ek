<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admission extends CI_Controller
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


    /*Admissions*/
    function online_admission($param1 = "", $param2 = "")
    {

        if ($param1 == 'submit') {
            if (!$this->crud_model->check_recaptcha() && get_common_settings('recaptcha_status') == true) {
                redirect(site_url('home/contact'), 'refresh');
            }
            if ($param2 == 'school'){
                echo $this->frontend_model->online_admission_school();
                return;
            }
          
        }

        $page_data['page_name'] = 'online_admission';
        $page_data['page_title'] = get_phrase('online_admission');
        $this->load->view('frontend/' . $this->theme . '/index', $page_data);
    }

        /*Admissions*/
        function online_admission_student($param1 = "", $param2 = "")
        {
    
            if ($param1 == 'submit') {
                if (!$this->crud_model->check_recaptcha() && get_common_settings('recaptcha_status') == true) {
                    redirect(site_url('home/contact'), 'refresh');
                }
                if ($param2 == 'student'){
                    echo $this->user_model->register_user_form();
                    return;
                }

              
            }
    
            $page_data['page_name'] = 'online_admission_student';
            $page_data['page_title'] = get_phrase('online_admission_student');
            $this->load->view('frontend/' . $this->theme . '/index', $page_data);
        }


    
}

