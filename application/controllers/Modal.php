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
		header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://unpkg.com; img-src 'self' data:; font-src 'self' https://fonts.gstatic.com; frame-src 'self';");
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
		
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

	function popup($folder_name = '', $page_name = '' , $param1 = '' , $param2 = '', $param3 = '' , $param4 = '' , $param5 = '')
	{
		$page_data['param1']		=	$param1;
		$page_data['param2']		=	$param2;
		$page_data['param3']		=	$param3;
		$page_data['param4']		=	$param4;
		$page_data['param5']		=	$param5;
		if($folder_name == 'academy'){
			$this->load->view( 'backend/'.$folder_name.'/'.$page_name.'.php' ,$page_data);
		}else{
			$this->load->view( 'backend/'.$this->session->userdata('user_type').'/'.$folder_name.'/'.$page_name.'.php' ,$page_data);
		}		
	}
}
