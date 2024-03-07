<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
*  @author   : Creativeitem
*  date      : November, 2019
*  Ekattor School Management System With Addons
*  http://codecanyon.net/user/Creativeitem
*  http://support.creativeitem.com
*/

class Modal extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->database();

		/*LOADING ALL THE MODELS HERE*/
		$this->load->model('Crud_model',     'crud_model');
		$this->load->model('User_model',     'user_model');
		$this->load->model('Settings_model', 'settings_model');
		$this->load->model('Payment_model',  'payment_model');
		$this->load->model('Email_model',    'email_model');
		$this->load->model('Addon_model',    'addon_model');
		$this->load->model('Frontend_model', 'frontend_model');

		if(addon_status('online_courses') != 0){
			$this->load->model('addons/Lms_model','lms_model');
			$this->load->model('addons/Video_model','video_model');
		}
		/*SET DEFAULT TIMEZONE*/
		timezone();
		
	}

	function popup($folder_name = '', $page_name = '' , $param1 = '' , $param2 = '', $param3 = '')
	{
		$page_data['param1']		=	$param1;
		$page_data['param2']		=	$param2;
		$page_data['param3']		=	$param3;
		if($folder_name == 'academy'){
			$this->load->view( 'backend/'.$folder_name.'/'.$page_name.'.php' ,$page_data);
		}else{
			$this->load->view( 'backend/'.$this->session->userdata('user_type').'/'.$folder_name.'/'.$page_name.'.php' ,$page_data);
		}		
	}
}
