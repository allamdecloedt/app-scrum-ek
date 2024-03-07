<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
*  @author   : Creativeitem
*  date      : November, 2019
*  Ekattor School Management System With Addons
*  http://codecanyon.net/user/Creativeitem
*  http://support.creativeitem.com
*/

class Driver extends CI_Controller
{
    public function __construct()
    {

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
        $this->load->model('Driver_model', 'driver_model');

        /*cache control*/
        $this->output->set_header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
        $this->output->set_header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        $this->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
        $this->output->set_header("Pragma: no-cache");

        /*SET DEFAULT TIMEZONE*/
        timezone();

        /*LOAD EXTERNAL LIBRARIES*/
        $this->load->library('pdf');

        // CHECK WHETHER Accountant IS LOGGED IN
        if ($this->session->userdata('driver_login') != 1) {
            redirect(site_url('login'), 'refresh');
        }
    }

    public function index()
    {
        // $page_data['page_title'] = 'Dashboard';
        // $page_data['page_name'] = 'index';
        // $page_data['folder_name'] = 'dashboard';
        // $this->load->view('backend/index', $page_data);

        redirect(site_url('driver/dashboard'), 'refresh');
    }

    //DASHBOARD
    public function dashboard()
    {
        $page_data['page_title'] = 'Dashboard';
        $page_data['folder_name'] = 'dashboard';
        $this->load->view('backend/index', $page_data);
    }

    function trip($param1 = '', $param2 = '')
    {
        if ($param1 == 'add') {
            $response = $this->driver_model->add_new_trip();
            echo $response;
        }

        if ($param1 == 'list') {
            $this->load->view('backend/driver/trip/list');
        }

        if ($param1 == 'get_route_by_vehicle') {
            $vehicle_details = $this->db->where('id', $param2)->get('vehicles')->row_array();
            echo $vehicle_details['route'];
        }

        if ($param1 == 'end_trip') {
            $response = $this->driver_model->end_trip($param2);
            echo $response;
        }

        if (empty($param1)) {
            $this->db->where(['status' => 1, 'driver_id' => driver_id()]);
            $old_trip = $this->db->order_by('id', 'desc')->get('trips')->row_array();

            $page_data['old_trip'] = $old_trip;
            $page_data['page_title'] = 'Trip';
            $page_data['folder_name'] = 'trip';
            $this->load->view('backend/index', $page_data);
        }
    }

    function assigned_student($param1 = '', $param2 = '')
    {
        if ($param1 == 'list') {
            $this->load->view('backend/driver/trip/list');
        }

        if ($param1 == 'filter') {
            $page_data['vehicle_id'] = $param2;
            $this->load->view('backend/driver/assign_student/list', $page_data);
        }

        if (empty($param1)) {
            $page_data['page_title'] = 'assigned_student';
            $page_data['folder_name'] = 'assign_student';
            $this->load->view('backend/index', $page_data);
        }
    }

    function update_driver_location()
    {
        $location = json_encode([
            'latitude' => html_escape($this->input->post('latitude')),
            'longitude' => html_escape($this->input->post('longitude')),
        ]);

        $trip_id = html_escape($this->input->post('trip_id'));
        $track = html_escape($this->input->post('track'));

        $update_location = ($track == 'once') ? ['starting_point' => $location] : ['last_location' => $location];

        $this->db->where('id', $trip_id)->update('trips', $update_location);
        echo json_encode(['status' => 'success']);
    }
}
