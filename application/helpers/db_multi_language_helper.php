<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* CodeIgniter
*
* An open source application development framework for PHP 5.1.6 or newer
*
* @package		CodeIgniter
* @author		ExpressionEngine Dev Team
* @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
* @license		http://codeigniter.com/user_guide/license.html
* @link		http://codeigniter.com
* @since		Version 1.0
* @filesource
*/


// This function helps us to get the translated phrase from the file. If it does not exist this function will save the phrase and by default it will have the same form as given
if ( ! function_exists('get_phrase'))
{
    function get_phrase($phrase = '') {
        $CI	=&	get_instance();
        $CI->load->database();
        $active_language = get_settings('language');
        $query = $CI->db->get_where('language', array('name' => $active_language, 'phrase' => $phrase));
        if($query->num_rows() == null){
            $translated = str_replace('_', ' ', $phrase);
            foreach(get_all_language() as $language){
                $CI->db->insert('language', array('name' => $language['name'], 'phrase' => $phrase, 'translated' => $translated));
            }
            return ucfirst($translated);
        }
        return ucfirst($query->row('translated'));
    }
}

if ( ! function_exists('site_phrase'))
{
    function site_phrase($phrase = '') {
        $CI	=&	get_instance();
        $CI->load->database();
        $active_language = get_settings('language');
        $query = $CI->db->get_where('language', array('name' => $active_language, 'phrase' => $phrase));
        if($query->num_rows() == null){
            $translated = str_replace('_', ' ', $phrase);
            foreach(get_all_language() as $language){
                $CI->db->insert('language', array('name' => $language['name'], 'phrase' => $phrase, 'translated' => $translated));
            }
            return ucfirst($translated);
        }
        return ucfirst($query->row('translated'));
    }
}

if ( ! function_exists('get_all_language'))
{
    function get_all_language() {
        $CI =&  get_instance();
        $CI->load->database();
        $CI->db->distinct('name');
        $CI->db->select('name');
        return $CI->db->get('language')->result_array();
    }
}

// This function helps us to create a new json file for new language
if ( ! function_exists('saveDefaultJSONFile'))
{
	function saveDefaultJSONFile($language_code){
		$CI	=&	get_instance();
        $CI->load->database();
		$new_language = strtolower(htmlspecialchars($language_code));
		$already_exist = $CI->db->get_where('language', array('name' => $new_language))->num_rows();
		if($already_exist <= 0){
			$CI->db->where('name', get_settings('language'));
			$all_phrases = $CI->db->get('language');

			if($all_phrases->num_rows() <= 0){
				$data['name'] = 'english';
				$data['phrase'] = 'english';
				$data['translated'] = 'english';
				$CI->db->insert('language', $data);
			}

			foreach($all_phrases->result_array() as $phrase){
				$data['name'] = $new_language;
				$data['phrase'] = $phrase['phrase'];
				$data['translated'] = str_replace('_', ' ', $phrase['phrase']);
				$CI->db->insert('language', $data);
			}
			return 'done';
		}else{
			return 'exist';
		}
	}
}

if ( ! function_exists('saveJSONFile'))
{
	function saveJSONFile($language_code, $updating_key, $updating_value){
		$CI	=&	get_instance();
        $CI->load->database();
		$language = strtolower($language_code);
		$phrase = strtolower($updating_key);
		$data['translated'] = htmlspecialchars($updating_value);

		$CI->db->where('name', $language);
		$CI->db->where('phrase', $phrase);
		$CI->db->update('language', $data);
	}
}


if ( ! function_exists('create_language_table'))
{
	function create_language_table(){
		$CI = get_instance();
		$CI->load->database();
		$CI->load->dbforge();


		$language_table = array(
		    'id' => array(
		        'type' => 'INT',
		        'constraint' => 11,
		        'unsigned' => TRUE,
		        'auto_increment' => TRUE,
		        'collation' => 'utf8_unicode_ci'
		    ),
		    'name' => array(
		        'type' => 'VARCHAR',
		        'constraint' => '50',
		        'default' => null,
		        'null' => TRUE,
		        'collation' => 'utf8_unicode_ci'
		    ),
		    'phrase' => array(
		        'type' => 'VARCHAR',
		        'constraint' => '300',
		        'default' => null,
		        'null' => TRUE,
		        'collation' => 'utf8_unicode_ci'
		    ),
		    'translated' => array(
		        'type' => 'VARCHAR',
		        'constraint' => '300',
		        'default' => null,
		        'null' => TRUE,
		        'collation' => 'utf8_unicode_ci'
		    )
		);
		$CI->dbforge->add_field($language_table);
		$CI->dbforge->add_key('id', TRUE);
		$attributes = array('collation' => "utf8_unicode_ci");
		$CI->dbforge->create_table('language', TRUE);
	}
}