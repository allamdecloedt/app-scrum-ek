<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
*  @author   : Creativeitem
*  date      : November, 2019
*  Ekattor School Management System With Addons
*  http://codecanyon.net/user/Creativeitem
*  http://support.creativeitem.com
*/

if (! function_exists('addon_status')) {
  function addon_status($unique_identifier = '') {
    $CI	=&	get_instance();
    $CI->load->database();
    $result = $CI->db->get_where('addons', array('unique_identifier' => $unique_identifier));
    if ($result->num_rows() > 0) {
      $result = $result->row_array();
      return $result['status'];
    }else{
      return 0;
    }

  }
}
// ------------------------------------------------------------------------
/* End of file addon_helper.php */
