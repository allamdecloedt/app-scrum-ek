<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
*  @author   : Creativeitem
*  date      : December, 2019
*  Ekattor School Management System With Addons
*  http://codecanyon.net/user/Creativeitem
*  http://support.creativeitem.com
*/


//SCHOOL ID
if (! function_exists('api_school_id')) {
  function api_school_id($user_id) {
    $CI =&  get_instance();
    $userdata = $CI->db->get_where('users', array('id' => $user_id))->row_array();
    if (strtolower($userdata['role']) == 'superadmin') {
      return get_settings('school_id');
    }else{
      return $userdata['school_id'];
    }
  }
}

//ACTIVE SESSION
if (! function_exists('api_active_session')) {
  function api_active_session($user_id = '', $type = '') {
    $CI =&  get_instance();
    $CI->load->database();
    $school_id = api_school_id($user_id);
    if($type == ''){
      $session_details = $CI->db->get_where('sessions', array('status' => 1))->row_array();
      return $session_details['id'];
    }else{
      $session_details = $CI->db->get_where('sessions', array('status' => 1))->row_array();
      return $session_details[$type];
    }
  }
}

// ------------------------------------------------------------------------
/* End of file api_helper.php */
