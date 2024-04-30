<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/TokenHandler.php';
//include Rest Controller library
require APPPATH . 'libraries/REST_Controller.php';

class Admin extends REST_Controller {

  protected $token;
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

    /*API MODEL*/
    $this->load->model('api/Admin_model','admin_model');

    /*SET DEFAULT TIMEZONE*/
		timezone();
    
    // creating object of TokenHandler class at first
    $this->tokenHandler = new TokenHandler();
    header('Content-Type: application/json');
  }

  /*
  * Unprotected routes will be located here.
  **/

  // FETCH ALL THE LANGUAGES
  public function languages_get() {
    $languages = $this->admin_model->languages_get();
    $this->set_response($languages, REST_Controller::HTTP_OK);
  }
  // menu
  public function menu_get() {
   
    $user_id = $this->get('user_id');
    if ($user_id) {
        $user_role = $this->getUserRole($user_id); 

        $user_type = array(
            'user_id' => $user_id,
            'role' => $user_role,
           
        );
    } else {
       
        $user_type = array();
    }


    $userdata = $this->admin_model->menu($user_type);

    $this->response($userdata, REST_Controller::HTTP_OK);
}



public function getUserRole($user_id) {

  $this->db->select('role');
  $this->db->from('users');
  $this->db->where('user_id', $user_id);
  $query = $this->db->get();

  if ($query && $query->num_rows() > 0) {
   
      $row = $query->row();
      return $row->role;
  } else {
     
      return null;
  }
}


//Edit API CALL
public function editProfile_post() {
  
  $response = $this->admin_model->editProfile();

  return $this->set_response($response, REST_Controller::HTTP_OK);
}

//Update Current Password
public function updatePassword_post() {
  
  $response = $this->admin_model->updatePassword();

  return $this->set_response($response, REST_Controller::HTTP_OK);
}
//dashbord API CALL
public function get_dashboard_data_get() {
  $user_id = $this->input->get('user_id');

  $response = $this->admin_model->get_dashboard_data($user_id);

  return $this->set_response($response, REST_Controller::HTTP_OK);
}
//CREATE SCHOOL API CALL

public function create_school_post() {
  $name = $this->post('name');
  $phone = $this->post('phone');
  $address = $this->post('address');

  // Validate input data if needed

  $data = array(
      'name' => $name,
      'phone' => $phone,
      'address' => $address
  );

  $this->db->insert('schools', $data);

  $new_school_id = $this->db->insert_id();
  $new_school_data = $this->db->get_where('schools', array('id' => $new_school_id))->row_array();

  $response = array(
      'status' => true,
      'message' => 'School added successfully',
      'school' => $new_school_data
  );

  $this->response($response, REST_Controller::HTTP_OK);
}


public function update_school_post($param1) {
  $name = $this->post('name');
  $phone = $this->post('phone');
  $address = $this->post('address');

  $data = array(
      'name' => $name,
      'phone' => $phone,
      'address' => $address
  );

  $this->db->where('id', $param1);
  $this->db->update('schools', $data);

  // Fetch updated school data
  $updated_school = $this->db->get_where('schools', array('id' => $param1))->row_array();

  $response = array(
      'status' => true,
      'notification' => get_phrase('school_has_been_updated_successfully'),
      'school' => $updated_school  // Return updated school data
  );

  $this->response($response, REST_Controller::HTTP_OK);
}

public function delete_school_post($param1) {
  $this->db->where('id', $param1);
  $this->db->delete('schools');

  $response = array(
      'status' => true,
      'notification' => get_phrase('school_has_been_deleted_successfully')
  );

  $this->response($response, REST_Controller::HTTP_OK);
}

public function schools_get() {
  // Load necessary libraries if not already loaded
  $this->load->database(); // Load the database library if not already loaded

  // Query to retrieve all schools
  $schools = $this->db->get('schools')->result_array();

  // Check if schools are found
  if ($schools) {
      // Prepare success response
      $response = array(
          'status' => true,
          'schools' => $schools
      );
  } else {
      // Prepare failure response
      $response = array(
          'status' => false,
          'notification' => 'No schools found'
      );
  }

  // Return the response
  $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($response));
}


///////////////////////////////////////


//Expense API CALL

public function expense_get($school_id) {
  $expenses = $this->get_expenses_by_school($school_id);

  if ($expenses === null) {
      $this->response([
          'status' => false,
          'message' => 'No expenses data found for the specified school_id'
      ], REST_Controller::HTTP_NOT_FOUND);
  } else {
      $formatted_expenses = array();
      foreach ($expenses as $expense) {
        
          $category_name = $this->get_category_name($expense['expense_category_id']);
          
          $formatted_expenses[] = array(
              'id' => $expense['id'],
              'category_id' => $expense['expense_category_id'],
              'name' => $category_name,
              'date' => date('Y-m-d', $expense['date']), 
              'amount' => $expense['amount'],
              'school_id' => $expense['school_id'],
              'session' => $expense['session'],
              'created_at' => date('Y-m-d H:i:s', $expense['created_at']), 
              'updated_at' => date('Y-m-d H:i:s', $expense['updated_at']), 
          );
      }
      $this->response([
          'status' => true,
          'expenses' => $formatted_expenses
      ], REST_Controller::HTTP_OK);
  }
}

public function get_category_name($category_id) {
  $sql = "SELECT name FROM expense_categories WHERE id = ?";
  $query = $this->db->query($sql, array($category_id));
  if ($query->num_rows() > 0) {
      $row = $query->row();
      return $row->name;
  } else {
      return null;
  }
}

public function get_expenses_by_school($school_id) {
  $sql = "SELECT * FROM expenses WHERE school_id = ?";

  $query = $this->db->query($sql, array($school_id));
  if ($query->num_rows() > 0) {
      $expenses = $query->result_array();
      return $expenses;
  } else {
      return null;
  }
}





//iNVOICES API CALL





public function invoice_get($student_id) {
  $invoices = $this->get_invoices_with_user($student_id);

  if ($invoices === null) {
      $this->response([
          'status' => false,
          'message' => 'No invoices found for the specified student_id'
      ], REST_Controller::HTTP_NOT_FOUND);
  } else {
      $formatted_invoices = array();
      foreach ($invoices as $invoice) {
          $formatted_invoices[] = array(
              'id' => $invoice['id'],
              'title' => $invoice['title'],
              'total_amount' => $invoice['total_amount'],
              'class_id' => $invoice['class_id'],
              'class_name' => $invoice['class_name'], 
              'student_id' => $invoice['student_id'],
              'student_name' => $invoice['student_name'],
              'payment_method' => $invoice['payment_method'],
              'paid_amount' => $invoice['paid_amount'],
              'status' => $invoice['status'],
              'school_id' => $invoice['school_id'],
              'session' => $invoice['session'],
              'created_at' => date('Y-m-d H:i:s', strtotime($invoice['created_at'])),
              'updated_at' => date('Y-m-d H:i:s', strtotime($invoice['updated_at']))
          );
      }
      $this->response([
          'status' => true,
          'invoices' => $formatted_invoices
      ], REST_Controller::HTTP_OK);
  }
}

public function get_invoices_with_user($student_id) {
  $sql = "SELECT invoices.*, users.name AS student_name, classes.name AS class_name
          FROM invoices 
          JOIN students ON invoices.student_id = students.id
          JOIN users ON students.user_id = users.id 
          JOIN classes ON invoices.class_id = classes.id
          WHERE invoices.student_id = ?";

  $query = $this->db->query($sql, array($student_id));
  if ($query->num_rows() > 0) {
      $invoices = $query->result_array();
      return $invoices;
  } else {
      return null;
  }
}


public function get_invoices_by_student($student_id) {
  $sql = "SELECT * FROM invoices WHERE student_id = ?";

  $query = $this->db->query($sql, array($student_id));
  if ($query->num_rows() > 0) {
      $invoices = $query->result_array();
      return $invoices;
  } else {
      return null;
  }
}


















    // Login API CALL
    public function login_post() {

      $userdata = $this->admin_model->login();
      if ($userdata['validity'] == 1) {
        $userdata['token'] = $this->tokenHandler->GenerateToken($userdata);
      }
      return $this->set_response($userdata, REST_Controller::HTTP_OK);
    }
  // FORGOT PASSWORD API CALL
  public function forgot_password_post() {
    $response = $this->admin_model->forgot_password();
    return $this->set_response($response, REST_Controller::HTTP_OK);
  }


  /*
  * Protected APIs. This APIs will require Authorization.
  **/

  // GET LOGGED IN USERDATA
  public function userdata_get() {
    $response = array();
    if (isset($_GET['auth_token']) && !empty($_GET['auth_token'])) {
      $auth_token = $_GET['auth_token'];
      $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);
      $response = $this->admin_model->get_userdata($logged_in_user_details['user_id']);
    }else{
      $response['status'] = 401;
      $response['message'] = 'Unauthorized';
    }
    return $this->set_response($response, REST_Controller::HTTP_OK);
  }
  public function dashboard_data_get() {
    $response = array();
    if (isset($_GET['auth_token']) && !empty($_GET['auth_token'])) {
      $auth_token = $_GET['auth_token'];
      $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);
      $response = $this->admin_model->get_dashboard_data($logged_in_user_details['user_id']);
    }else{
      $response['status'] = 401;
      $response['message'] = 'Unauthorized';
    }
    return $this->set_response($response, REST_Controller::HTTP_OK);
  }

  // GET DATA OF SUBJECTS
  public function subjects_get() {
    $response = array();
    if (isset($_GET['auth_token']) && !empty($_GET['auth_token'])) {
      $auth_token = $_GET['auth_token'];
      $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);
      $response = $this->admin_model->get_subjects($logged_in_user_details['user_id']);
    }else{
      $response['status'] = 401;
      $response['message'] = 'Unauthorized';
    }
    return $this->set_response($response, REST_Controller::HTTP_OK);
  }

  // GET STUDENT LIST BY CLASS ID
  public function students_get() {
    $response = array();
    if (isset($_GET['auth_token']) && !empty($_GET['auth_token'])) {
      $auth_token = $_GET['auth_token'];
      $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);
      $response = $this->admin_model->get_students($logged_in_user_details['user_id']);
    }else{
      $response['status'] = 401;
      $response['message'] = 'Unauthorized';
    }
    return $this->set_response($response, REST_Controller::HTTP_OK);
  }

  // GET STUDENT LIST BY CLASS ID
  public function student_wise_marks_get() {
    $response = array();
    if (isset($_GET['auth_token']) && !empty($_GET['auth_token'])) {
      $auth_token = $_GET['auth_token'];
      $logged_in_user_details = json_decode($this->token_data_get($auth_token), true);
      $response = $this->admin_model->get_student_wise_marks($logged_in_user_details['user_id']);
    }else{
      $response['status'] = 401;
      $response['message'] = 'Unauthorized';
    }
    return $this->set_response($response, REST_Controller::HTTP_OK);
  }




















  /////////// Generating Token and put user data into  token ///////////
  public function login_token_get()
  {
    $tokenData['user_id'] = '1';
    $tokenData['role'] = 'admin';
    $tokenData['first_name'] = 'Al';
    $tokenData['last_name'] = 'Mobin';
    $tokenData['phone'] = '+8801921040960';
    $jwtToken = $this->tokenHandler->GenerateToken($tokenData);
    $token = $jwtToken;
    echo json_encode(array('Token'=>$jwtToken));
  }

  //////// get data from token ////////////
  public function get_token_data()
  {
    $received_Token = $this->input->request_headers('Authorization');
    if (isset($received_Token['Token'])) {
      try
      {
        $jwtData = $this->tokenHandler->DecodeToken($received_Token['Token']);
        return json_encode($jwtData);
      }
      catch (Exception $e)
      {
        http_response_code('401');
        echo json_encode(array( "status" => false, "message" => $e->getMessage()));
        exit;
      }
    }else{
      echo json_encode(array( "status" => false, "message" => "Invalid Token"));
    }
  }

  public function token_data_get($auth_token)
  {
    //$received_Token = $this->input->request_headers('Authorization');
    if (isset($auth_token)) {
      try
      {

        $jwtData = $this->tokenHandler->DecodeToken($auth_token);
        return json_encode($jwtData);
      }
      catch (Exception $e)
      {
        echo 'catch';
        http_response_code('401');
        echo json_encode(array( "status" => false, "message" => $e->getMessage()));
        exit;
      }
    }else{
      echo json_encode(array( "status" => false, "message" => "Invalid Token"));
    }
  }

  /*
  * eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdGF0dXMiOjIwMCwibWVzc2FnZSI6Ik9LIiwidXNlcl9pZCI6IjI0MSIsIm5hbWUiOiJTdWJoYW4gTWlhIiwiZW1haWwiOiJzdWJoYW5AZXhhbXBsZS5jb20iLCJyb2xlIjoiYWRtaW4iLCJzY2hvb2xfaWQiOiI4IiwiYWRkcmVzcyI6IkJoYWlyYWIgQmF6YXIsIFJham5hZ2FyIiwicGhvbmUiOiIwMTkyMTA0MDk2MCIsImJpcnRoZGF5IjoiMDEtSmFuLTE5NzAiLCJnZW5kZXIiOiJtYWxlIiwiYmxvb2RfZ3JvdXAiOiJhYisiLCJ2YWxpZGl0eSI6dHJ1ZX0.z435NqyIgcVtVNVb7jnN1ewlF2omN6HGxVz23gQZBK8
  **/
}
