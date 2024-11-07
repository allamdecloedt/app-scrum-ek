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
	function password_reset_email_link($link = '' , $user_id = "")
	{
		$query			=	$this->db->get_where('users' , array('id' => $user_id))->row_array();
		if(sizeof($query) > 0)
		{

			$email_msg = '
			<!DOCTYPE html>
			<html lang="fr">
			<head>
				<meta charset="UTF-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<title>Réinitialisation de votre mot de passe</title>
				<style>
					body {
						font-family: Arial, sans-serif;
						background-color: #f4f4f4;
						color: #333;
						line-height: 1.6;
						margin: 0;
						padding: 0;
					}
					.container {
						max-width: 600px;
						margin: 20px auto;
						background: #fff;
						padding: 20px;
						border-radius: 10px;
						box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
					}
					h2 {
						color: #5d0ea8;
					}
					p {
						margin: 10px 0;
						color: #555;
					}
					.button {
						display: inline-block;
						padding: 15px 25px;
						font-size: 16px;
						color: #fff !important;
						background-color: #5d0ea8;
						text-decoration: none;
						border-radius: 5px;
						margin-top: 20px;
						transition: background-color 0.3s ease;
					}
					.button:hover {
						background-color: #4c0e8f;
					}
					.footer {
						margin-top: 30px;
						font-size: 12px;
						color: #777;
						text-align: center;
					}
					.footer p {
						margin: 0;
					}
				</style>
			</head>
			<body>
				<div class="container">
					<h2>Réinitialisation de votre mot de passe</h2>
					<p>Bonjour '.ucfirst($query['name']).',</p>
					<p>Vous avez fait une demande de réinitialisation de votre mot de passe. Si vous êtes à l\'origine de cette demande, veuillez cliquer sur le lien ci-dessous pour créer un nouveau mot de passe :</p>
					<a href="'.$link.'" class="button">Réinitialiser mon mot de passe</a>
					<p>Ce lien est valide pendant 24 heures.</p>
					<p>Si vous n\'êtes pas à l\'origine de cette demande, veuillez ignorer cet e-mail. Votre mot de passe actuel restera inchangé et votre compte demeure sécurisé.</p>
					<p>Si vous avez des questions ou besoin d\'assistance, n\'hésitez pas à contacter notre équipe support à l’adresse suivante : <a href="mailto:'.get_settings('system_email').'">'.get_settings('system_email').'</a>.</p>
					<div class="footer">
						<p>&copy; 2024. Tous droits réservés.</p>
					</div>
				</div>
			</body>
			</html>';
			

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

	function password_send_add_student($link = '' , $user_id = "")
	{
		$query			=	$this->db->get_where('users' , array('id' => $user_id))->row_array();
		if(sizeof($query) > 0)
		{

			$email_msg	=	'<!DOCTYPE html>
				<html lang="en">
				<head>
					<meta charset="UTF-8">
					<meta name="viewport" content="width=device-width, initial-scale=1.0">
					<title>Password Reset</title>
					<style>
						body {
							font-family: Arial, sans-serif;
							background-color: #f4f4f4;
							color: #ffffff;
							line-height: 1.6;
							margin: 0;
							padding: 0;
						}
						.container {
							max-width: 600px;
							margin: 20px auto;
							background: #fff;
							padding: 20px;
							border-radius: 10px;
							box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
						}
						h2 {
							color: #333;
						}
						p {
							margin: 10px 0;
						}
						.button {
							display: inline-block;
							padding: 10px 20px;
							font-size: 16px;
							color: #fff !important;
							background-color: #5d0ea8;
							text-decoration: none;
							border-radius: 5px;
							margin-top: 20px;
						}
						.footer {
							margin-top: 30px;
							font-size: 12px;
							color: #777;
							text-align: center;
						}
					</style>
				</head>
				<body>
					<div class="container">
						<h2>Add Password </h2>
						<p>Hello '.ucfirst($query['name']).'</p>
						<p>We received a request to add your password for your account. You can add your password by clicking the link below:</p>
						<a href="'.$link.'" class="button"> Password</a>
						<p>If you did not request a password reset, please ignore this email or contact support if you have questions.</p>
						
						<div class="footer">
							<p>&copy; 2024. All rights reserved.</p>
						</div>
					</div>
				</body>
				</html>
				';

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
	function Add_online_admission($email = "", $user_id = "",$name = ""){

		$school_data = $this->settings_model->get_current_school_data();
		$image_url = "http://51.92.7.185/uploads/schools/".$school_data['id'].".jpg"; // URL de l'image à côté des informations

		$email_message =  '
		<html>
		<head>
		  <style>
			body {
			  font-family: Arial, sans-serif;
			  background-color: #f6f6f6;
			  margin: 0;
			  padding: 0;
			}
			.email-container {
			  max-width: 600px;
			  margin: 20px auto;
			  background-color: #ffffff;
			  border-radius: 8px;
			  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
			  overflow: hidden;
			}
			.email-header {
			  background-color: #27272d;
			  color: #ffffff;
			  padding: 20px;
			  text-align: center;
			  font-size: 24px;
			  font-weight: bold;
			}
			.email-body {
			  padding: 30px 20px;
			  color: #333333;
			}
			.email-body p {
			  line-height: 1.6;
			  margin: 10px 0;
			}
			.email-body .btn {
			  display: inline-block;
			  padding: 10px 20px;
			  margin: 20px 0;
			  background-color: #be02e8;
			  color: #ffffff;
			  text-decoration: none;
			  border-radius: 4px;
			}
			.email-footer {
			  background-color: #f1f1f1;
			  color: #777777;
			  padding: 10px;
			  text-align: center;
			  font-size: 12px;
			}
			.info-image {
				width: 100%;
				max-width: 200px;
				margin-right: 38px;
			  }
			  .info-container {
				display: flex;
				align-items: center;
			  }
		  </style>
		</head>
		<body>
		  <div class="email-container">
			<div class="email-header">
				Registration Student
			</div>
			<div class="email-body">
			<div class="info-container">
			<div>
			  <p>Your registration has been made.</p>
			  <p><strong>Name:</strong> '.$name.'</p>
			  <p><strong>Email:</strong> '.$email.'</p>
			  <p><a href="http://51.92.7.185/home/courses" class="btn">Login to Your Account</a></p>
			</div>
		  </div>
			</div>
			<div class="email-footer">
			  <p>&copy; '.date("Y").'  . All rights reserved.</p>
			</div>
		  </div>
		</body>
		</html>
		';

		$email_sub		= 'Registration ';
		$email_to = $email;
		

		$this->send_mail_using_smtp($email_message, $email_sub, $email_to ,Null,$school_data['name']);

	}
	function School_online_admission($email = "", $school_name = "",$name = ""){
		// $image_url = "http://51.92.7.185/uploads/images/decloedt/logo/white-logo.svg"; // URL de l'image à côté des informations
		$image_url = base_url('uploads/images/decloedt/logo/white-logo.png');

		$email_message =  '
		<html>
		<head>
		  <style>
			body {
			  font-family: Arial, sans-serif;
			  background-color: #f6f6f6;
			  margin: 0;
			  padding: 0;
			}
			.email-container {
			  max-width: 600px;
			  margin: 20px auto;
			  background-color: #ffffff;
			  border-radius: 8px;
			  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
			  overflow: hidden;
			}
			.email-header {
			  background-color: #4CAF50;
			  color: #ffffff;
			  padding: 20px;
			  text-align: center;
			  font-size: 24px;
			  font-weight: bold;
			}
			.email-body {
			  padding: 30px 20px;
			  color: #333333;
			}
			.email-body p {
			  line-height: 1.6;
			  margin: 10px 0;
			}
			.email-body .btn {
			  display: inline-block;
			  padding: 10px 20px;
			  margin: 20px 0;
			  background-color: #4CAF50;
			  color: #ffffff;
			  text-decoration: none;
			  border-radius: 4px;
			}
			.email-footer {
			  background-color: #f1f1f1;
			  color: #777777;
			  padding: 10px;
			  text-align: center;
			  font-size: 12px;
			}
			.info-image {
				width: 100%;
				max-width: 200px;
				margin-right: 38px;
			  }
			  .info-container {
				display: flex;
				align-items: center;
			  }
		  </style>
		</head>
		<body>
		  <div class="email-container">
			<div class="email-header">
			Registration School
			</div>
			<div class="email-body">
			<div class="info-container">
			<img src="'.$image_url.'" alt="Image" class="info-image">
			<div>
			  <p>Your registration has been made.</p>
			  <p>Hello '.$name.'</p>
			  <p><strong>Name school:</strong> '.$school_name.'</p>
			  <p><strong>Email:</strong> '.$email.'</p>
			  <p><a href="http://51.92.7.185/login" class="btn">Login to Your Account</a></p>
			</div>
		  </div>
			</div>
			<div class="email-footer">
			  <p>&copy; '.date("Y").' Wayo Acadmy  . All rights reserved.</p>
			</div>
		  </div>
		</body>
		</html>
		';

		$email_sub		= 'Registration ';
		$email_to = $email;
		$school_name = "Wayo acadmy";
		

		$this->send_mail_using_smtp($email_message, $email_sub, $email_to ,Null,$school_name);


	}
	function approved_online_admission($student_id = "", $user_id = "")
	{
				$student_details = $this->user_model->get_student_details_by_id('student', $student_id);
				$school_data = $this->settings_model->get_current_school_data();
				$student_email = $student_details['email'];
				$student_name = $student_details['name'];
				$student_code = $student_details['code'];
				$image_url = "http://51.92.7.185/uploads/schools/".$school_data['id'].".jpg"; // URL de l'image à côté des informations

				$email_message =  '
				<html>
				<head>
				<style>
					body {
					font-family: Arial, sans-serif;
					background-color: #f6f6f6;
					margin: 0;
					padding: 0;
					}
					.email-container {
					max-width: 600px;
					margin: 20px auto;
					background-color: #ffffff;
					border-radius: 8px;
					box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
					overflow: hidden;
					}
					.email-header {
					background-color: #4CAF50;
					color: #ffffff;
					padding: 20px;
					text-align: center;
					font-size: 24px;
					font-weight: bold;
					}
					.email-body {
					padding: 30px 20px;
					color: #333333;
					}
					.email-body p {
					line-height: 1.6;
					margin: 10px 0;
					}
					.email-body .btn {
					display: inline-block;
					padding: 10px 20px;
					margin: 20px 0;
					background-color: #4CAF50;
					color: #ffffff;
					text-decoration: none;
					border-radius: 4px;
					}
					.email-footer {
					background-color: #f1f1f1;
					color: #777777;
					padding: 10px;
					text-align: center;
					font-size: 12px;
					}
					.info-image {
						width: 100%;
						max-width: 200px;
						margin-right: 38px;
					}
					.info-container {
						display: flex;
						align-items: center;
					}
				</style>
				</head>
				<body>
				<div class="email-container">
					<div class="email-header">
					Admission Approved
					</div>
					<div class="email-body">
					<div class="info-container">
					<img src="'.$image_url.'" alt="Image" class="info-image">
					<div>
					<p>Your admission request has been accepted.</p>
					<p><strong>Student Code:</strong> '.$student_code.'</p>
					<p><strong>Email:</strong> '.$student_email.'</p>
					<p><a href="http://51.92.7.185/home/course_details/'.$school_data['id'].'" class="btn">Login to Your Account</a></p>
					</div>
				</div>
					</div>
					<div class="email-footer">
					<p>&copy; '.date("Y").' '.$school_data['name'].' . All rights reserved.</p>
					</div>
				</div>
				</body>
				</html>
				';
				// $email_message  = '<html><body><p> Your admission request has been accepted.'.'</p><br><p>Student Code : '.$student_code.'</p><p>Email : '.$student_email.'</p><p>Password : '.$password.'</p></body></html>';
				$email_sub		= 'Admission approval';
				$email_to = $student_email;
				

				$this->send_mail_using_smtp($email_message, $email_sub, $email_to,Null,$school_data['name']);
	}


	function join_student_email($email_student = "", $user_name ,$code_student = "", $name_school = "",$school_id ="")
	{
				$image_url = "http://51.92.7.185/uploads/schools/".$school_id.".jpg"; // URL de l'image à côté des informations

				$email_message =  '
				<html>
				<head>
				<style>
					body {
					font-family: Arial, sans-serif;
					background-color: #f6f6f6;
					margin: 0;
					padding: 0;
					}
					.email-container {
					max-width: 600px;
					margin: 20px auto;
					background-color: #ffffff;
					border-radius: 8px;
					box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
					overflow: hidden;
					}
					.email-header {
					background-color: #27272d;
					color: #ffffff;
					padding: 20px;
					text-align: center;
					font-size: 24px;
					font-weight: bold;
					}
					.email-body {
					padding: 30px 20px;
					color: #333333;
					}
					.email-body p {
					line-height: 1.6;
					margin: 10px 0;
					}
					.email-body .btn {
					display: inline-block;
					padding: 10px 20px;
					margin: 20px 0;
					background-color: #be02e8;
					color: #ffffff;
					text-decoration: none;
					border-radius: 4px;
					}
					.email-footer {
					background-color: #f1f1f1;
					color: #777777;
					padding: 10px;
					text-align: center;
					font-size: 12px;
					}
					.info-image {
						width: 100%;
						max-width: 200px;
						margin-right: 38px;
					}
					.info-container {
						display: flex;
						align-items: center;
					}
				</style>
				</head>
				<body>
				<div class="email-container">
					<div class="email-header">
					Join in '.$name_school.'
					</div>
					<div class="email-body">
					<div class="info-container">
					<img src="'.$image_url.'" alt="Image" class="info-image">
					<div>
					<p>Hello <strong> '.$user_name.'</strong>, Your join request has been made.</p>
					<p><strong>Student Code:</strong> '.$code_student.'</p>
					<p><strong>Email:</strong> '.$email_student.'</p>
					<p><a href="http://51.92.7.185/home/course_details/'.$school_id.'" class="btn">Login to Your Account</a></p>
					</div>
				</div>
					</div>
					<div class="email-footer">
					<p>&copy; '.date("Y").' '.$name_school.' . All rights reserved.</p>
					</div>
				</div>
				</body>
				</html>
				';
				$email_sub		= 'Admission approval';
				$email_to = $email_student;
				

				$this->send_mail_using_smtp($email_message, $email_sub, $email_to,Null,$name_school);
	}
	function join_student_email_for_admin($email_student = "", $user_name ,$code_student = "", $name_school = "",$school_id ="")
	{
				$image_url = "http://51.92.7.185/uploads/schools/".$school_id.".jpg"; // URL de l'image à côté des informations

				$email_message =  '
				<html>
				<head>
				<style>
					body {
					font-family: Arial, sans-serif;
					background-color: #f6f6f6;
					margin: 0;
					padding: 0;
					}
					.email-container {
					max-width: 600px;
					margin: 20px auto;
					background-color: #ffffff;
					border-radius: 8px;
					box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
					overflow: hidden;
					}
					.email-header {
					background-color: #27272d;
					color: #ffffff;
					padding: 20px;
					text-align: center;
					font-size: 24px;
					font-weight: bold;
					}
					.email-body {
					padding: 30px 20px;
					color: #333333;
					}
					.email-body p {
					line-height: 1.6;
					margin: 10px 0;
					}
					.email-body .btn {
					display: inline-block;
					padding: 10px 20px;
					margin: 20px 0;
					background-color: #be02e8;
					color: #ffffff;
					text-decoration: none;
					border-radius: 4px;
					}
					.email-footer {
					background-color: #f1f1f1;
					color: #777777;
					padding: 10px;
					text-align: center;
					font-size: 12px;
					}
					.info-image {
						width: 100%;
						max-width: 200px;
						margin-right: 38px;
					}
					.info-container {
						display: flex;
						align-items: center;
					}
				</style>
				</head>
				<body>
				<div class="email-container">
					<div class="email-header">
					You have a new registration in '.$name_school.'
					</div>
					<div class="email-body">
					<div class="info-container">
					<img src="'.$image_url.'" alt="Image" class="info-image">
					<div>
					<p> <strong>Name : </strong>'.$user_name.'</p>
					<p><strong>Student Code : </strong> '.$code_student.'</p>
					<p><strong>Email : </strong> '.$email_student.'</p>
					<p><a href="http://51.92.7.185/login" class="btn">Login to Your Account</a></p>
					</div>
				</div>
					</div>
					<div class="email-footer">
					<p>&copy; '.date("Y").' '.$name_school.' . All rights reserved.</p>
					</div>
				</div>
				</body>
				</html>
				';
				$email_sub		= 'Admission approval';
				$email_to = $email_student;
				

				$this->send_mail_using_smtp($email_message, $email_sub, $email_to,Null,$name_school);
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
	public function send_mail_using_smtp($msg=NULL, $sub=NULL, $to=NULL, $from=NULL ,$school_name =Null) {
		//Load email library
		$this->load->library('email');

		if($from == NULL){
			$from		=	get_settings('system_email');
		}
		$smtp_username = get_smtp('smtp_username');
		$smtp_password = get_smtp('smtp_password');
		//SMTP & mail configuration llcmpoajhkmglyf ghlirqnydynumjgy
		$config = array(
			'protocol'  => get_smtp('smtp_protocol'),
			'smtp_host' => get_smtp('smtp_host'),
			'smtp_port' => get_smtp('smtp_port'),
			'smtp_user' => $smtp_username,
			'smtp_pass' => $smtp_password,
			'smtp_crypto' => 'ssl',
			'mailtype'  => 'html',
			'charset'   => 'utf-8',
			'newline'   => "\r\n",
			'smtp_timeout' => '30',
			'mailpath' => '/usr/sbin/sendmail',
            'smtp_timeout' => '10', //in seconds
			'wordwrap' => TRUE
		);
		//  print_r('smtp_username : '.get_smtp('smtp_username').' - smtp_username : '.get_smtp('smtp_password'));die;
		$this->email->initialize($config);
		$this->email->set_mailtype("html");
		$this->email->set_newline("\r\n");

		$htmlContent = $msg;
		$school_data = $this->settings_model->get_current_school_data();
		$this->email->to($to);
        $this->email->from($from,"Service Support"); // Remplacez 'Votre Nom' par le nom souhaité
		$this->email->subject($sub);
		$this->email->message($htmlContent);

		//Send email
		$this->email->send();
		        // Envoyer l'email
				// if ($this->email->send()) {
				// 	echo "Email envoyé avec succès.";
				// } else {
				// 	echo "Échec de l'envoi de l'email.";
				// 	echo $this->email->print_debugger();
				// }
				// die;
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
	function send_email_with_validation_code($email_message, $email_to) {
		$email_sub = "Your Validation Code";
		
		// Sending the email using SMTP or PHPMailer
		if (get_smtp('mail_sender') == 'php_mailer') {
			return $this->send_mail_using_php_mailer($email_message, $email_sub, $email_to);
		} else {
			return $this->send_mail_using_smtp($email_message, $email_sub, $email_to);
		}
	}
	
}
