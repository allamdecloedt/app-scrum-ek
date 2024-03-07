<?php
	$CI = get_instance();
	$CI->load->database();
	$CI->load->dbforge();

	//update data in settings table
	$settings_data = array( 'version' => '7.5' );
	$CI->db->update('settings', $settings_data);
?>
