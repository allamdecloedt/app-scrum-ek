<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*  @author   : Creativeitem
*  date      : November, 2019
*  Ekattor School Management System With Addons
*  http://codecanyon.net/user/Creativeitem
*  http://support.creativeitem.com
*/

class Email_model extends CI_Model {

	protected $school_id;
	protected $active_session;

	public function __construct()
	{
		parent::__construct();
		$this->school_id = school_id();
		$this->active_session = active_session();
	}

	function account_opening_email($account_type = '' , $email = '', $password = '')
	{
		$system_name	=	get_settings('system_name');

		$email_msg		=	"Welcome to ".$system_name."<br />";
		$email_msg		.=	"Your account type : ".$account_type."<br />";
		$email_msg		.=	"Your login password : ". $password ."<br />";
		$email_msg		.=	"Login Here : ".base_url()."<br />";

		$email_sub		=	"Account opening email";
		$email_to		=	$email;

		if (get_smtp('mail_sender') == 'php_mailer') {
			$this->send_mail_using_php_mailer($email_msg , $email_sub , $email_to);
		}else{
			$this->send_mail_using_smtp($email_msg , $email_sub , $email_to);
		}
	}

	function password_reset_email($new_password = '' , $user_id = "")
	{
		$query			=	$this->db->get_where('users' , array('id' => $user_id))->row_array();
		if(sizeof($query) > 0)
		{

			$email_msg	=	"Your account type is : ".ucfirst($query['role'])."<br />";
			$email_msg	.=	"Your password is : ".$new_password."<br />";

			$email_sub	=	"Password reset request";
			$email_to	=	$query['email'];

			if (get_smtp('mail_sender') == 'php_mailer') {
				$this->send_mail_using_php_mailer($email_msg , $email_sub , $email_to);
			}else{
				$this->send_mail_using_smtp($email_msg , $email_sub , $email_to);
			}
			return true;
		}
		else
		{
			return false;
		}
	}

	function contact_message_email($email_from, $email_to, $email_message) {
		$email_sub = "Message from School Website";

		if (get_smtp('mail_sender') == 'php_mailer') {
			$this->send_mail_using_php_mailer($email_message, $email_sub, $email_to, $email_from);
		}else{
			$this->send_mail_using_smtp($email_message, $email_sub, $email_to, $email_from);
		}
	}

	function personal_message_email($email_from, $email_to, $email_message) {
		$email_sub = "Message from School Website";

		if (get_smtp('mail_sender') == 'php_mailer') {
			$this->send_mail_using_php_mailer($email_message, $email_sub, $email_to, $email_from);
		}else{
			$this->send_mail_using_smtp($email_message, $email_sub, $email_to, $email_from);
		}
	}

	function request_book_email($student_id){
		$student_details = $this->user_model->get_student_details_by_id('student', $student_id);
		$student_name = $student_details['name'];
		$student_code = $student_details['code'];
		$email_message  = '<html><body><p>'.$student_name.' has been requested you, for the book.'.'</p><br><p>Student Code : '.$student_code.'</p></body></html>';
		$email_sub		= 'New book issued';
		$this->db->limit(1);
		$librarians = $this->db->get('librarian')->result_array();
		foreach($librarians as $librarian){
			$email_to = $librarian['email'];
		}
		$this->send_mail_using_smtp($email_message, $email_sub, $email_to);
	}

	function approved_online_admission($student_id = "", $user_id = "", $password = ""){
		$student_details = $this->user_model->get_student_details_by_id('student', $student_id);
		$student_email = $student_details['email'];
		$student_name = $student_details['name'];
		$student_code = $student_details['code'];
		$email_message  = '<html><body><p> Your admission request has been accepted.'.'</p><br><p>Student Code : '.$student_code.'</p><p>Email : '.$student_email.'</p><p>Password : '.$password.'</p></body></html>';
		$email_sub		= 'Admission approval';
		$email_to = $student_email;
		

		$this->send_mail_using_smtp($email_message, $email_sub, $email_to);
	}

	function approved_online_admission_parent_access($user_id = "", $password = ""){
		$parent_details = $this->db->get_where('users', array('id' => $user_id))->row_array();
		$email = $parent_details['email'];
		$email_message  = "<html><body><p> Your son/daughter's admission has been accepted.".'</p><br><p>Your account access-</p><p>Email : '.$email.'</p><p>Password : '.$password.'</p></body></html>';
		$email_sub		= 'Admission approval';
		$email_to = $email;
		

		$this->send_mail_using_smtp($email_message, $email_sub, $email_to);
	}


	//SEND MARKS VIA MAIL
	function send_marks_email($email_msg=NULL, $email_sub=NULL, $email_to=NULL)
	{
		if (get_smtp('mail_sender') == 'php_mailer') {
			$this->send_mail_using_php_mailer($email_msg , $email_sub , $email_to);
		}else{
			$this->send_mail_using_smtp($email_msg , $email_sub , $email_to);
		}
		return true;
	}
	// more stable function
	public function send_mail_using_smtp($msg=NULL, $sub=NULL, $to=NULL, $from=NULL) {
		//Load email library
		$this->load->library('email');

		if($from == NULL){
			$from		=	get_settings('system_email');
		}

		//SMTP & mail configuration
		$config = array(
			'protocol'  => get_smtp('smtp_protocol'),
			'smtp_host' => get_smtp('smtp_host'),
			'smtp_port' => get_smtp('smtp_port'),
			'smtp_user' => get_smtp('smtp_username'),
			'smtp_pass' => get_smtp('smtp_password'),
			'smtp_crypto' => get_settings('smtp_crypto'),
			'mailtype'  => 'html',
			'charset'   => 'utf-8',
			'newline'   => "\r\n",
			'smtp_timeout' => '30',
			'mailpath' => '/usr/sbin/sendmail',
            'smtp_timeout' => '10', //in seconds
			'wordwrap' => TRUE
		);
		$this->email->initialize($config);
		$this->email->set_mailtype("html");
		$this->email->set_newline("\r\n");

		$htmlContent = $msg;

		$this->email->to($to);
		$this->email->from($from, get_smtp('smtp_set_from'));
		$this->email->subject($sub);
		$this->email->message($htmlContent);

		//Send email
		$this->email->send();
	}

	public function send_mail_using_php_mailer($message=NULL, $subject=NULL, $to=NULL, $from=NULL) {
		// Load PHPMailer library
		$this->load->library('phpmailer_lib');

		// PHPMailer object
		$mail = $this->phpmailer_lib->load();

		// SMTP configuration
		$mail->isSMTP();
		$mail->Host       = get_smtp('smtp_host');
		$mail->SMTPAuth   = true;
		$mail->Username   = get_smtp('smtp_username');
		$mail->Password   = get_smtp('smtp_password');
		$mail->SMTPSecure = get_smtp('smtp_secure');
		$mail->Port       = get_smtp('smtp_port');

		$mail->setFrom(get_smtp('smtp_username'), get_smtp('smtp_set_from'));
		$mail->addReplyTo(get_settings('system_email'), get_settings('system_name'));

		// Add a recipient
		$mail->addAddress($to);

		// Email subject
		$mail->Subject = $subject;

		// Set email format to HTML
		$mail->isHTML(true);

		// Enabled debug
		$mail->SMTPDebug = false;

		// Email body content
		$mailContent = $message;
		$mail->Body = $mailContent;

		// Send email
		if(!$mail->send()){
			// echo 'Message could not be sent.';
			// echo 'Mailer Error: ' . $mail->ErrorInfo;
			return false;
		}else{
			//echo 'Message has been sent';
			return true;
		}
	}
}
