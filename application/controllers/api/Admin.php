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
//Online Admission API CALL
public function fetchStudentsByName_get($school_id, $name) {
  // Fetch students by name from the database
  $this->db->select('*');
  $this->db->from('users');
  $this->db->where('role', 'student');
  $this->db->where('school_id', $school_id);
  
  // Check if the name parameter is provided and not empty
  if (!empty($name)) {
      // If the name is just a single letter, filter by the first letter of the name
      if (strlen($name) === 1) {
          $this->db->like('name', $name, 'after'); // Filter by names starting with the provided letter
      } else {
          $this->db->like('name', $name); // Filter by the provided name
      }
  }
  
  $students = $this->db->get()->result_array();

  // Check if students were found
  if ($students) {
      // Students found, return success response
      $response['status'] = 200;
      $response['message'] = 'Students retrieved successfully';
      $response['students'] = $students;
  } else {
      // No students found
      $response['status'] = 404;
      $response['message'] = 'No students found with the specified name';
      $response['students'] = [];
  }

  // Send response
  $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($response));
  
  // Debugging: Print the generated SQL query
  // echo $this->db->last_query();
}



public function onlineadmission_post() {
  $response = array();
  $data = array(
      'name' => $this->post('name'),
      'email' => $this->post('email'),
      'password' => sha1($this->post('password')),
      'role' => 'student',
      'address' => $this->post('address'),
      'phone' => $this->post('phone'),
      'birthday' => date('Y-m-d', strtotime($this->post('birthday'))),
      'gender' => $this->post('gender'),
      'blood_group' => $this->post('blood_group'),
      'school_id' => $this->post('school_id'), 
      'status' => $this->post('status') ?? 1, // Set status to 1 by default for students
      'watch_history' => json_encode($this->post('watch_history')), 
  );
  $existing_email = $this->db->get_where('users', array('email' => $data['email']))->row_array();

  if ($existing_email) {
      $response['status'] = 409; 
      $response['message'] = 'Email already exists';
  } else {
      $insert = $this->db->insert('users', $data);
      if ($insert) {
          $user_id = $this->db->insert_id();
          if (isset($_FILES['student_image']) && $_FILES['student_image']['error'] === UPLOAD_ERR_OK) {
              move_uploaded_file($_FILES['student_image']['tmp_name'], 'uploads/users/'.$user_id.'.jpg');
          }
          $data['watch_history'] = json_decode($data['watch_history']);

          $response['status'] = 201;
          $response['message'] = 'User registered successfully';
          $response['user_id'] = $user_id;
          $response['name'] = $data['name'];
          $response['email'] = $data['email'];
          $response['role'] = $data['role'];
          $response['address'] = $data['address'];
          $response['phone'] = $data['phone'];
          $response['birthday'] = date('d-M-Y', strtotime($data['birthday']));
          $response['gender'] = $data['gender'];
          $response['blood_group'] = $data['blood_group'];
          $response['school_id'] = $data['school_id'];
          $response['status'] = $data['status']; 
          $response['watch_history'] = $data['watch_history']; 

      } else {
          $response['status'] = 500;
          $response['message'] = 'Failed to register user';
      }
  }

  // Send response
  $this->set_response($response, REST_Controller::HTTP_CREATED);
}

public function onlineadmissionEdit_put($id) {
  $response = array();

  // Get user input data
  $data = array(
      'name' => $this->put('name'),
      'email' => $this->put('email'),
      'address' => $this->put('address'),
      'phone' => $this->put('phone'),
      'birthday' => date('Y-m-d', strtotime($this->put('birthday'))),
      'gender' => $this->put('gender'),
      'blood_group' => $this->put('blood_group'),
      'watch_history' => json_encode($this->put('watch_history')), // Convert watch_history array to JSON
  );

  // Check if user with given id exists in the database
  $existing_user = $this->db->get_where('users', array('id' => $id))->row_array();

  if ($existing_user) {
      // Update user data in the database
      $this->db->where('id', $id);
      $update = $this->db->update('users', $data);

      if ($update) {
          // User data updated successfully
          $response['status'] = 200;
          $response['message'] = 'User data updated successfully';

          // Handle image update
          $user_id = $this->db->insert_id(); // Get the ID of the updated user
          if (isset($_FILES['student_image']) && $_FILES['student_image']['error'] === UPLOAD_ERR_OK) {
              // Move the uploaded image to the desired location with the new filename based on the user's ID
              move_uploaded_file($_FILES['student_image']['tmp_name'], 'uploads/users/'.$user_id.'.jpg');
          }
      } else {
          // Failed to update user data
          $response['status'] = 500;
          $response['message'] = 'Failed to update user data';
      }
  } else {
      // User with given id does not exist
      $response['status'] = 404;
      $response['message'] = 'User not found';
  }

  // Send response
  $this->set_response($response, REST_Controller::HTTP_OK);
}


public function onlineadmissionList_get($school_id) {
  // Fetch online admissions for new students (status = 3) from the database
  $this->db->where('role', 'student');
  $this->db->where('status', 3); // Add condition for status = 3
  $online_admissions = $this->db->get('users')->result_array();
  
  $num_students = count($online_admissions); // Count the number of students
  
  if ($online_admissions) {
      // Online admissions found, return success response
      $response['status'] = 200; // Use status code 200 for success
      $response['message'] = 'Online admissions retrieved successfully';
      $response['num_students'] = $num_students; // Include the number of students in the response
      
      // Iterate through each online admission entry
      foreach ($online_admissions as &$admission) {
          // Add school_id to the admission entry
          $admission['school_id'] = $school_id;
          
          // Fetch and add image URL for the user
          $user_id = $admission['user_id']; // Assuming user_id is the primary key
          $image_path = 'uploads/users/'.$user_id.'.jpg'; // Construct image path
          
          // Check if the image file exists
          if (file_exists($image_path)) {
              // Image exists, include its URL in the admission entry
              $admission['image_url'] = base_url($image_path);
          } else {
              // Image does not exist, set image URL to null
              $admission['image_url'] = null;
          }
          
          // Add an empty watch_history array to the admission entry
          $admission['watch_history'] = [];
          
          // Optionally, you can remove 'user_id' from the admission entry if it's not needed in the response
          unset($admission['user_id']);
      }
      
      $response['online_admissions'] = $online_admissions;
  } else {
      // No online admissions found
      $response['status'] = 404; // Use status code 404 for not found
      $response['message'] = 'No new online admissions found for students';
      $response['num_students'] = 0; // Set the number of students to 0
      
      $response['online_admissions'] = [];
  }
  
  // Send response
  $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($response));
}

public function onlineadmissionListApprovedAndDesapproved_get($school_id) {
   $this->db->where('role', 'student');
   $this->db->group_start();
   $this->db->where('status', 0); 
   $this->db->or_where('status', 1); 
   $this->db->group_end(); 
   $online_admissions = $this->db->get('users')->result_array();
   
   $num_students = count($online_admissions);
   
   if ($online_admissions) {
       
       $response['status'] = 200; 
       $response['message'] = 'Online admissions retrieved successfully';
       $response['num_students'] = $num_students;
       $response['online_admissions'] = $online_admissions;
   } else {
       
       $response['status'] = 404; 
       $response['message'] = 'No new online admissions found for students with status = 0 or status = 1';
       $response['num_students'] = 0;
       $response['online_admissions'] = [];
   }
   $this->output
       ->set_content_type('application/json')
       ->set_output(json_encode($response));
}
public function approveOnlineAdmissions_put($id) {
  // Retrieve online admission with status 3 for the specified user ID
  $this->db->where('id', $id);
  $this->db->where('role', 'student');
  $this->db->where('status', 3);
  $online_admission = $this->db->get('users')->row_array();

  // Check if online admission with status 3 is found for the specified ID
  if ($online_admission) {
      // Update status to 1
      $this->db->where('id', $id);
      $this->db->update('users', array('status' => 1));

      // Return success response
      $response['status'] = 200;
      $response['message'] = 'Status updated successfully from 3 to 1';
  } else {
      // No online admission with status 3 found for the specified ID
      $response['status'] = 404;
      $response['message'] = 'No online admission found with status 3 for the specified ID';
  }

  // Send response
  $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($response));
}

public function deactivate_put($id) {
  // Retrieve online admission with status 1 for the specified user ID
  $this->db->where('id', $id);
  $this->db->where('role', 'student');
  $this->db->where('status', 1);
  $online_admission = $this->db->get('users')->row_array();

  // Check if online admission with status 1 is found for the specified ID
  if ($online_admission) {
      // Update status to 0
      $this->db->where('id', $id);
      $this->db->update('users', array('status' => 0));

      // Return success response
      $response['status'] = 200;
      $response['message'] = 'Status updated successfully from 1 to 0';
  } else {
      // No online admission with status 1 found for the specified ID
      $response['status'] = 404;
      $response['message'] = 'No online admission found with status 1 for the specified ID';
  }

  // Send response
  $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($response));
}
public function activate_put($id) {
  // Retrieve online admission with status 0 for the specified user ID
  $this->db->where('id', $id);
  $this->db->where('role', 'student');
  $this->db->where('status', 0);
  $online_admission = $this->db->get('users')->row_array();

  // Check if online admission with status 0 is found for the specified ID
  if ($online_admission) {
      // Update status to 1
      $this->db->where('id', $id);
      $this->db->update('users', array('status' => 1));

      // Return success response
      $response['status'] = 200;
      $response['message'] = 'Status updated successfully from 0 to 1';
  } else {
      // No online admission with status 0 found for the specified ID
      $response['status'] = 404;
      $response['message'] = 'No online admission found with status 0 for the specified ID';
  }

  // Send response
  $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($response));
}

public function onlineadmissionDelete_delete($id) {
  $existing_user = $this->db->get_where('users', array('id' => $id))->row_array();
  
  if ($existing_user) {

      $this->db->where('id', $id);
      $delete = $this->db->delete('users');
      
      if ($delete) {
      
          $response['status'] = 200;
          $response['message'] = 'User deleted successfully';
      } else {
         
          $response['status'] = 500;
          $response['message'] = 'Failed to delete user';
      }
  } else {
     
      $response['status'] = 404;
      $response['message'] = 'User not found';
  }
  
  // Send response
  $this->set_response($response, REST_Controller::HTTP_OK);
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




public function invoices_get($school_id) {
  // Assuming you have a function to fetch invoices based on school_id
  $invoices = $this->get_invoices_by_school($school_id);

  if ($invoices === null) {
      $this->response([
          'status' => false,
          'message' => 'No invoices found for the specified school_id'
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
public function get_invoices_by_school($school_id) {
  $sql = "SELECT invoices.*, users.name AS student_name, classes.name AS class_name
          FROM invoices 
          JOIN students ON invoices.student_id = students.id
          JOIN users ON students.user_id = users.id
          JOIN classes ON invoices.class_id = classes.id
          WHERE users.school_id = ?";

  $query = $this->db->query($sql, array($school_id));
  if ($query->num_rows() > 0) {
      $invoices = $query->result_array();
      return $invoices;
  } else {
      return null;
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
