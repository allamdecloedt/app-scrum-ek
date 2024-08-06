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
//CREATE SCHOOL API CALL
public function categoriesByName_get() {
  $this->db->select('name');
  $this->db->order_by('name', 'ASC');
  $query = $this->db->get('categories');
  $categories = $query->result();

  // Format the response to include only names
  $category_names = array_map(function($category) {
      return $category->name;
  }, $categories);

  // Return the response in JSON format
  $response = array(
      'status' => true,
      'categories' => $category_names
  );

  echo json_encode($response);
}
/*  public function create_school_post() {
  // Get post data for the school
  $school_name = $this->post('name');
  $school_phone = $this->post('phone');
  $address = $this->post('address');
  $description = $this->post('description');
  $access = $this->post('access') === 'private' ? 0 : 1; // Convert 'private' to 0 and 'public' to 1
  $category = $this->post('category');

  // Get post data for the admin user
  $admin_full_name = $this->post('admin_full_name');
  $admin_email = $this->post('email');
  $admin_password = $this->post('password');
  $admin_gender = $this->post('gender');
  $admin_phone = $this->post('phone');

  // Check for duplicate email for admin
  $email_exists = $this->db->get_where('users', array('email' => $admin_email))->row_array();
  if ($email_exists) {
      // Duplicate email found
      $response = array(
          'status' => false,
          'message' => 'Admin email already exists'
      );
      $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
      return; 
  }

  // No duplicate email found for admin, proceed with inserting data for both school and admin user
  $this->db->trans_start(); // Start transaction
  $school_data = array(
      'name' => $school_name,
      'phone' => $school_phone,
      'address' => $address,
      'description' => $description,
      'access' => $access,
      'category' => $category
  );

  $this->db->insert('schools', $school_data);
  $school_id = $this->db->insert_id();

  // Insert admin user data
  $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT); // Hash the password
  $admin_data = array(
      'name' => $admin_full_name,
      'email' => $admin_email,
      'password' => $hashed_password,
      'role' => 'admin',
      'status' => 1,
      'gender' => $admin_gender,
      'phone' => $admin_phone,
      'school_id' => $school_id,
      'watch_history' => '[]' 
  );

  $this->db->insert('users', $admin_data);
  $admin_id = $this->db->insert_id();

  // Handle the image upload
  if (!empty($_FILES['image_file']['name'])) {
      $image_path = 'uploads/schools/' . $school_id . '.jpg';
      if (!move_uploaded_file($_FILES['image_file']['tmp_name'], $image_path)) {
          // Image upload failed, rollback transaction
          $this->db->trans_rollback();
          $response = array(
              'status' => false,
              'message' => 'Failed to upload school image'
          );
          $this->response($response, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
          return;
      }
  }

  $this->db->trans_complete(); // Complete transaction

  if ($this->db->trans_status() === false) {
      // Transaction failed, rollback
      $this->db->trans_rollback();
      $response = array(
          'status' => false,
          'message' => 'Failed to create school and admin user'
      );
      $this->response($response, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
  } else {
      // Transaction successful
      $response = array(
          'status' => true,
          'message' => 'School and admin user added successfully'
      );
      $this->response($response, REST_Controller::HTTP_CREATED);
  }
}   */


public function online_admission_school_post()
{
    // Begin transaction
    $this->db->trans_begin();

    // Decode JSON data from the request body
    $json_data = json_decode($this->input->post('json_data'), true);

    if (is_null($json_data)) {
        $this->db->trans_rollback();
        $response = array(
            'status' => false,
            'message' => 'Invalid JSON data'
        );
        $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
        return;
    }

    // Check for duplicates
    $duplication_status = $this->user_model->check_duplication_school('on_create', $json_data['schoolName']);

    if ($duplication_status) {
        // Extract school data from JSON
        $school_data = array(
            'name' => htmlspecialchars($json_data['schoolName']),
            'address' => htmlspecialchars($json_data['schoolAddress']),
            'phone' => htmlspecialchars($json_data['schoolPhone']),
            'status' => 0,
            'description' => htmlspecialchars($json_data['description']),
            'access' => htmlspecialchars($json_data['access']),
            'category' => htmlspecialchars($json_data['selectedCategory']),
        );

        // Insert school data into the database
        $this->db->insert('schools', $school_data);
        $school_id = $this->db->insert_id();

        // Extract admin data from JSON
        $admin_data = array(
            'name' => htmlspecialchars($json_data['adminName']),
            'email' => htmlspecialchars($json_data['adminEmail']),
            'gender' => htmlspecialchars($json_data['adminGender']),
            'phone' => htmlspecialchars($json_data['adminPhone']),
            'password' => sha1($json_data['adminPassword']),
            'role' => 'admin',
            'school_id' => $school_id,
            'status' => 1,
            'watch_history' => '[]',
        );

        // Insert admin data into the database
        $this->db->insert('users', $admin_data);
        $user_id = $this->db->insert_id();

        // Handle image upload if it exists
        if (!empty($_FILES['image_file']['name'])) {
            $image_path = 'uploads/schools/' . $school_id . '.jpg';
            if (!move_uploaded_file($_FILES['image_file']['tmp_name'], $image_path)) {
                // Image upload failed, rollback transaction
                $this->db->trans_rollback();
                $response = array(
                    'status' => false,
                    'message' => 'Failed to upload school image'
                );
                $this->response($response, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                return;
            }
        }

        // Commit transaction
        $this->db->trans_commit();

        // Send success response
        $response = array('status' => 1, 'message' => 'School created successfully.');
        $this->response($response, REST_Controller::HTTP_OK);
    } else {
        // Rollback transaction in case of duplication error
        $this->db->trans_rollback();

        // Send duplication error response
        $response = array('status' => 0, 'message' => 'This school name already exists.');
        $this->response($response, REST_Controller::HTTP_CONFLICT);
    }
}
public function update_school_post()
{
    // Begin transaction
    $this->db->trans_begin();

    // Fetch raw POST data and decode JSON
    $raw_post_data = $this->input->raw_input_stream;
    log_message('debug', 'Raw POST data: ' . $raw_post_data);

    $json_data = json_decode($raw_post_data, true);

    if (is_null($json_data) || !isset($json_data['schoolId'])) {
        $this->db->trans_rollback();
        $response = array(
            'status' => false,
            'message' => 'Invalid JSON data or missing school ID'
        );
        log_message('error', 'Invalid JSON data or missing school ID');
        $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
        return;
    }

    // Extract school ID from JSON
    $school_id = htmlspecialchars($json_data['schoolId']);
    log_message('debug', 'Extracted school ID: ' . $school_id);

    // Check for duplicates (if updating the school name)
    if (isset($json_data['schoolName'])) {
        $duplication_status = $this->user_model->check_duplication_school('on_update', $json_data['schoolName'], $school_id);
        log_message('debug', 'Duplication check status: ' . $duplication_status);

       
    }

    // Prepare school data for update
    $school_data = array(
        'name' => isset($json_data['schoolName']) ? htmlspecialchars($json_data['schoolName']) : null,
        'address' => isset($json_data['schoolAddress']) ? htmlspecialchars($json_data['schoolAddress']) : null,
        'phone' => isset($json_data['schoolPhone']) ? htmlspecialchars($json_data['schoolPhone']) : null,
        'description' => isset($json_data['description']) ? htmlspecialchars($json_data['description']) : null,
        'access' => isset($json_data['access']) ? htmlspecialchars($json_data['access']) : null,
        'category' => isset($json_data['selectedCategory']) ? htmlspecialchars($json_data['selectedCategory']) : null,
    );

    // Remove null values from the update array
    $school_data = array_filter($school_data, function($value) {
        return !is_null($value);
    });

    log_message('debug', 'School data to update: ' . json_encode($school_data));

    // Update school data in the database
    $this->db->where('id', $school_id);
    if (!$this->db->update('schools', $school_data)) {
        $this->db->trans_rollback();
        $response = array(
            'status' => false,
            'message' => 'Failed to update school.'
        );
        log_message('error', 'Failed to update school.');
        $this->response($response, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        return;
    }

    // Prepare admin data for update
    $admin_data = array(
        'name' => isset($json_data['adminName']) ? htmlspecialchars($json_data['adminName']) : null,
        'email' => isset($json_data['adminEmail']) ? htmlspecialchars($json_data['adminEmail']) : null,
        'gender' => isset($json_data['adminGender']) ? htmlspecialchars($json_data['adminGender']) : null,
        'phone' => isset($json_data['adminPhone']) ? htmlspecialchars($json_data['adminPhone']) : null,
        'password' => isset($json_data['adminPassword']) ? sha1($json_data['adminPassword']) : null,
    );

    // Remove null values from the update array
    $admin_data = array_filter($admin_data, function($value) {
        return !is_null($value);
    });

    log_message('debug', 'Admin data to update: ' . json_encode($admin_data));

    // Update admin data in the database
    if (!empty($admin_data)) {
        $this->db->where('school_id', $school_id);
        $this->db->where('role', 'admin');
        if (!$this->db->update('users', $admin_data)) {
            $this->db->trans_rollback();
            $response = array(
                'status' => false,
                'message' => 'Failed to update admin user.'
            );
            log_message('error', 'Failed to update admin user.');
            $this->response($response, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            return;
        }
    }

    // Handle image upload if it exists
    if (!empty($_FILES['image_file']['name'])) {
        $image_path = 'uploads/schools/' . $school_id . '.jpg';
        if (!move_uploaded_file($_FILES['image_file']['tmp_name'], $image_path)) {
            // Image upload failed, rollback transaction
            $this->db->trans_rollback();
            $response = array(
                'status' => false,
                'message' => 'Failed to upload school image.'
            );
            log_message('error', 'Failed to upload school image.');
            $this->response($response, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            return;
        }
    }

    // Commit transaction
    $this->db->trans_commit();

    // Send success response
    $response = array(
        'status' => true,
        'message' => 'School updated successfully.'
    );
    log_message('debug', 'School updated successfully.');
    $this->response($response, REST_Controller::HTTP_OK);
}







public function delete_school_post($param1) {
  // Update the school's state to 0 (inactive) instead of deleting the record
  $this->db->where('id', $param1);
  $this->db->update('schools', array('etat' => 0));

  // Find and update the status of the admin user associated with the school
  $this->db->from('users');
  $this->db->where('school_id', $param1);
  $this->db->where('role', 'admin');
  $admin_user = $this->db->get()->row_array();

  if ($admin_user) {
      $this->db->where('id', $admin_user['id']);
      $this->db->update('users', array('status' => 0));
  }

  // Prepare the response
  $response = array(
      'status' => true,
      'notification' => get_phrase('school_has_been_deactivated_and_admin_status_updated_successfully')
  );

  // Send the response
  $this->response($response, REST_Controller::HTTP_OK);
}



public function schools_get() {
  // Query to retrieve all schools
  $schools = $this->db->get('schools')->result_array();

  // Check if schools are found
  if ($schools) {
      // Iterate over each school to add image path and admin info
      foreach ($schools as &$school) {
          // Add image path
          $image_path = 'uploads/schools/' . $school['id'] . '.jpg';
          if (file_exists($image_path)) {
              $school['image_url'] = base_url($image_path);
          } else {
              $school['image_url'] = null; // Or set a default image path
          }

          // Retrieve admin info from users table
          $this->db->select('name, email, gender, phone, role, status, watch_history');
          $this->db->from('users');
          $this->db->where('school_id', $school['id']);
          $this->db->where('role', 'admin');
          $admin = $this->db->get()->row_array();

          if ($admin) {
              $school['admin'] = array(
                  'name' => htmlspecialchars($admin['name']),
                  'email' => htmlspecialchars($admin['email']),
                  'gender' => htmlspecialchars($admin['gender']),
                  'phone' => htmlspecialchars($admin['phone']),
                  'role' => htmlspecialchars($admin['role']),
                  'status' => (int) $admin['status'],
                  'watch_history' => json_decode($admin['watch_history'])
              );
          } else {
              $school['admin'] = null; // Or provide default admin info
          }
      }

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

public function schools_by_category_get() {
    // Set response content type to JSON
    $this->output->set_content_type('application/json');

    // Query to retrieve all schools
    $this->db->select('schools.*');
    $this->db->from('schools');
    $schools = $this->db->get()->result_array();

    // Check if schools are found
    if ($schools) {
        // Initialize an array to hold schools grouped by category
        $schools_by_category = array();

        // Iterate over each school to add image path and admin info
        foreach ($schools as &$school) {
            // Add image path
            $image_path = 'uploads/schools/' . $school['id'] . '.jpg';
            if (file_exists($image_path)) {
                $school['image_url'] = base_url($image_path);
            } else {
                $school['image_url'] = null; // Or set a default image path
            }

            // Retrieve admin info from users table
            $this->db->select('name, email, gender, phone, role, status, watch_history');
            $this->db->from('users');
            $this->db->where('school_id', $school['id']);
            $this->db->where('role', 'admin');
            $admin = $this->db->get()->row_array();

            if ($admin) {
                $school['admin'] = array(
                    'name' => htmlspecialchars($admin['name']),
                    'email' => htmlspecialchars($admin['email']),
                    'gender' => htmlspecialchars($admin['gender']),
                    'phone' => htmlspecialchars($admin['phone']),
                    'role' => htmlspecialchars($admin['role']),
                    'status' => (int) $admin['status'],
                    'watch_history' => json_decode($admin['watch_history'])
                );
            } else {
                $school['admin'] = null; // Or provide default admin info
            }

            // Group schools by category attribute
            $category_name = $school['category'];
            if (!isset($schools_by_category[$category_name])) {
                $schools_by_category[$category_name] = array();
            }
            $schools_by_category[$category_name][] = $school;
        }

        // Add "All" category that includes all schools
        $schools_by_category['All'] = $schools;

        // Prepare success response
        $response = array(
            'status' => true,
            'schools_by_category' => $schools_by_category
        );
    } else {
        // Prepare failure response
        $response = array(
            'status' => false,
            'notification' => 'No schools found'
        );
    }

    // Return the response
    $this->output->set_output(json_encode($response));
}
public function search_schools_post() {
    // Set response content type to JSON
    $this->output->set_content_type('application/json');

    // Decode JSON data from the request body
    $json_data = json_decode($this->input->raw_input_stream, true);

    if (is_null($json_data)) {
        $response = array(
            'status' => false,
            'message' => 'Invalid JSON data'
        );
        $this->output->set_output(json_encode($response));
        return;
    }

    // Extract search parameters
    $search_name = isset($json_data['name']) ? htmlspecialchars($json_data['name']) : '';
    $search_category = isset($json_data['category']) ? htmlspecialchars($json_data['category']) : '';

    // Start building the query
    $this->db->select('schools.*');
    $this->db->from('schools');

    // Add search conditions
    if (!empty($search_name)) {
        $this->db->like('name', $search_name);
    }
    if (!empty($search_category) && $search_category !== 'All') {
        $this->db->where('category', $search_category);
    }


    // Execute the query
    $schools = $this->db->get()->result_array();

    // Check if schools are found
    if ($schools) {
        // Iterate over each school to add image path and admin info
        foreach ($schools as &$school) {
            // Add image path
            $image_path = 'uploads/schools/' . $school['id'] . '.jpg';
            if (file_exists($image_path)) {
                $school['image_url'] = base_url($image_path);
            } else {
                $school['image_url'] = null; // Or set a default image path
            }

           
            // Retrieve admin info from users table
            $this->db->select('name, email, gender, phone, role, status, watch_history');
            $this->db->from('users');
            $this->db->where('school_id', $school['id']);
            $this->db->where('role', 'admin');
            $admin = $this->db->get()->row_array();

            if ($admin) {
                $school['admin'] = array(
                    'name' => htmlspecialchars($admin['name']),
                    'email' => htmlspecialchars($admin['email']),
                    'gender' => htmlspecialchars($admin['gender']),
                    'phone' => htmlspecialchars($admin['phone']),
                    'role' => htmlspecialchars($admin['role']),
                    'status' => (int) $admin['status'],
                    'watch_history' => json_decode($admin['watch_history'])
                );
            } else {
                $school['admin'] = null; // Or provide default admin info
            }
        }

        // Prepare success response
        $response = array(
            'status' => true,
            'schools' => $schools
        );
    } else {
        // Prepare failure response
        $response = array(
            'status' => false,
            'message' => 'No schools found'
        );
    }

    // Return the response
    $this->output->set_output(json_encode($response));
}

///////////////////////////////////////

public function create_admin_post()
{
    // Check if all required fields are present
    $required_fields = ['adminName', 'adminEmail', 'adminGender', 'adminPhone', 'adminPassword', 'school_name', 'adminAddress'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $this->response(array(
                'status' => false,
                'message' => 'Missing field: ' . $field
            ), REST_Controller::HTTP_BAD_REQUEST);
            return;
        }
    }

    // Retrieve the school ID based on the school name
    $school_name = $_POST['school_name'];
    $school_id = $this->getSchoolId($school_name);

    if (!$school_id) {
        $this->response(array(
            'status' => false,
            'message' => 'Invalid school name'
        ), REST_Controller::HTTP_BAD_REQUEST);
        return;
    }

    // Extract admin data from POST
    $admin_data = array(
        'name' => $_POST['adminName'],
        'email' => $_POST['adminEmail'],
        'gender' => $_POST['adminGender'],
        'phone' => $_POST['adminPhone'],
        'password' => password_hash($_POST['adminPassword'], PASSWORD_BCRYPT),
        'role' => 'admin',
        'school_id' => $school_id,
        'status' => 1,
        'watch_history' => '[]',
        'address' => $_POST['adminAddress'], // Adding address field
    );

    // Insert admin data into the database
    $this->db->insert('users', $admin_data);
    $user_id = $this->db->insert_id();

    // Handle image upload if it exists
    if (!empty($_FILES['image_file']['name'])) {
        $image_path = 'uploads/users/' . $user_id . '.jpg';
        if (!move_uploaded_file($_FILES['image_file']['tmp_name'], $image_path)) {
            // Image upload failed
            $this->response(array(
                'status' => false,
                'message' => 'Failed to upload user image'
            ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            return;
        }
    }

    // Send success response
    $this->response(array('status' => true, 'message' => 'Admin created successfully.'), REST_Controller::HTTP_OK);
}


private function getSchoolId($school_name) {
    // Query the database to get the school ID
    $query = $this->db->get_where('schools', array('name' => $school_name));

    // Check if the query returned a result
    if ($query->num_rows() > 0) {
        // Extract the row and return the school ID
        $result = $query->row();
        return $result->id;
    } else {
        // Return null if the school name was not found
        return null;
    }
}

public function edit_admin_put($admin_id)
{
    if (empty($admin_id)) {
        $this->response([
            'status' => false,
            'message' => 'Missing admin ID'
        ], REST_Controller::HTTP_BAD_REQUEST);
        return;
    }
    // Check if the admin exists
    $admin_exists = $this->db->get_where('users', array('id' => $admin_id, 'role' => 'admin'));
    if ($admin_exists->num_rows() == 0) {
        $this->response(array(
            'status' => false,
            'message' => 'Admin not found'
        ), REST_Controller::HTTP_NOT_FOUND);
        return;
    }

    // Validate required fields

    // Handle image upload if it exists
    if (isset($_FILES['image_file']['name']) && !empty($_FILES['image_file']['name'])) {
        $config['upload_path'] = './uploads/users/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['file_name'] = $admin_id . '.jpg';  // Ensure extension matches
        $config['overwrite'] = TRUE;  // Overwrite the file if it already exists
    
        $this->load->library('upload', $config);
    
        if (!$this->upload->do_upload('image_file')) {
            $error = $this->upload->display_errors();
            $this->response([
                'status' => false,
                'message' => 'Failed to upload user image: ' . $error
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            return;
        } else {
            // Optionally, you might want to add the path to the uploaded file in the database
            $upload_data = $this->upload->data();
            $admin_data['image_path'] = 'uploads/users/' . $upload_data['file_name'];
        }
    }
    
    // Retrieve the school ID based on the school name
    $school_name = $this->put('school_name');
    $school_id = $this->getSchoolId($school_name);
    if (!$school_id) {
        $this->response(array(
            'status' => false,
            'message' => "Invalid school name provided: $school_name"
        ), REST_Controller::HTTP_BAD_REQUEST);
        return;
    }

    // Extract admin data from PUT request
    $admin_data = array(
        'name' => $this->put('adminName'),
        'email' => $this->put('adminEmail'),
        'gender' => $this->put('adminGender'),
        'phone' => $this->put('adminPhone'),
        'password' => password_hash($this->put('adminPassword'), PASSWORD_BCRYPT), // Consider only updating if password is actually changed
        'role' => 'admin',
        'school_id' => $school_id,
        'status' => 1,
        'watch_history' => '[]',
        'address' => $this->put('adminAddress'),
    );

    // Update the admin data in the database
    $this->db->where('id', $admin_id);
    $this->db->update('users', $admin_data);

    // Check if the update was successful
    if ($this->db->affected_rows() > 0) {
        $this->response(array(
            'status' => true,
            'message' => 'Admin updated successfully'
        ), REST_Controller::HTTP_OK);
    } else {
        $this->response(array(
            'status' => false,
            'message' => 'No changes made or update failed'
        ), REST_Controller::HTTP_BAD_REQUEST);
    }
}


public function all_school_names_get()
{
    // Query the database to get all school names
    $this->db->select('name');
    $this->db->from('schools');
    $query = $this->db->get();

    // Check if any schools are found
    if ($query->num_rows() > 0) {
        $school_names = $query->result_array();
        $response = array(
            'status' => true,
            'data' => $school_names
        );
        $this->response($response, REST_Controller::HTTP_OK);
    } else {
        // No schools found
        $response = array(
            'status' => false,
            'message' => 'No schools found'
        );
        $this->response($response, REST_Controller::HTTP_NOT_FOUND);
    }
}

public function Deladmin_delete($admin_id)
{
    // Validate that admin ID is provided and is numeric
    if (empty($admin_id) || !is_numeric($admin_id)) {
        $this->response(array(
            'status' => false,
            'message' => 'Invalid or missing admin ID'
        ), REST_Controller::HTTP_BAD_REQUEST);
        return;
    }

    // Start transaction
    $this->db->trans_start();

    // Check if the admin exists
    $this->db->where('id', $admin_id);
    $query = $this->db->get('users');
    if ($query->num_rows() == 0) {
        $this->db->trans_rollback();
        $this->response(array(
            'status' => false,
            'message' => 'Admin not found'
        ), REST_Controller::HTTP_NOT_FOUND);
        return;
    }

    // Delete the admin from the database
    $this->db->where('id', $admin_id);
    $this->db->delete('users');

    // Check if there's an associated image and delete it
    $image_path = 'uploads/users/' . $admin_id . '.jpg';
    if (file_exists($image_path) && !unlink($image_path)) {
        $this->db->trans_rollback();
        $this->response(array(
            'status' => false,
            'message' => 'Failed to delete admin image'
        ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        return;
    }

    // Complete the transaction
    $this->db->trans_complete();
    if ($this->db->trans_status() === FALSE) {
        $this->response(array(
            'status' => false,
            'message' => 'Failed to delete admin'
        ), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
    } else {
        $this->response(array(
            'status' => true,
            'message' => 'Admin deleted successfully'
        ), REST_Controller::HTTP_OK);
    }
}





public function all_admins_get()
{
    // Retrieve all admins including their hashed passwords (Not recommended)
    $this->db->select('users.id, users.name, users.email, users.phone, users.address, users.gender, schools.name as school_name'); // Included password
    $this->db->from('users');
    $this->db->join('schools', 'users.school_id = schools.id', 'left');
    $this->db->where('users.role', 'admin');
    $this->db->order_by('users.name', 'ASC'); // Order by name in ascending order
    $admins = $this->db->get()->result_array();

    // Check if any admins were found
    if (empty($admins)) {
        $response = array(
            'status' => false,
            'notification' => 'No admins found'
        );
    } else {
        // Iterate through each admin and check if image exists
        foreach ($admins as &$admin) {
            $image_path = 'uploads/users/' . $admin['id'] . '.jpg'; // Assuming 'id' is the unique identifier for each admin
            if (file_exists($image_path)) {
                $admin['image_url'] = base_url($image_path); // Assuming you're using CodeIgniter and 'base_url' is configured
            }
        
        }

        $response = array(
            'status' => true,
            'admins' => $admins
        );
    }

    // Return the JSON response
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response));
}



public function fetch_admins_by_name_get()
{
    // Retrieve the name, alphabet, and school_name query parameters
    $name = $this->input->get('name');
    $alphabet = $this->input->get('alphabet');
    $school_name = $this->input->get('school_name');

    // Log or echo the name, alphabet, and school_name for debugging
    log_message('debug', 'Provided name: ' . $name);
    log_message('debug', 'Provided alphabet: ' . $alphabet);
    log_message('debug', 'Provided school name: ' . $school_name);

    // Check if name, alphabet, or school_name is provided
    if (!$name && !$alphabet && !$school_name) {
        $response = array(
            'status' => false,
            'notification' => 'No name, alphabet, or school name provided'
        );
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
        return;
    }

    // Retrieve admins from the 'users' table where role is 'admin' and apply filters
    $this->db->select('users.*, schools.name as school_name'); // Select users and join with school names
    $this->db->from('users');
    $this->db->join('schools', 'users.school_id = schools.id', 'left');
    $this->db->where('role', 'admin');
    
    if ($name) {
        $this->db->like('users.name', $name);
    }
    if ($alphabet) {
        $this->db->like('users.name', $alphabet, 'after'); // Filter by name starting with the provided alphabet
    }
    if ($school_name) {
        $this->db->where('schools.name', $school_name);
    }

    // Execute the query to retrieve admins
    $admins = $this->db->get()->result_array();

    // Log or echo the generated SQL query for debugging
    log_message('debug', 'Generated SQL query: ' . $this->db->last_query());

    // Check if any admins were found
    if (empty($admins)) {
        $response = array(
            'status' => false,
            'notification' => 'No admins found with the provided criteria'
        );
    } else {
        // Iterate through each admin and check if image exists
        foreach ($admins as &$admin) {
            $image_path = 'uploads/users/' . $admin['id'] . '.jpg'; // Assuming 'id' is the unique identifier for each admin
            if (file_exists($image_path)) {
                $admin['image_url'] = base_url($image_path); // Assuming you're using CodeIgniter and 'base_url' is configured
            }
        }

        $response = array(
            'status' => true,
            'admins' => $admins
        );
    }

    // Return the JSON response
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response));
}

//END ADMIN PART////////
 

//Teacher Part

public function create_teacher_post() {
    // Validate the input data
    if (!$this->input->post('name') || !$this->input->post('email') || !$this->input->post('password') || !$this->input->post('address') || !$this->input->post('phone') || !$this->input->post('gender') || !$this->input->post('department_id')) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['error' => 'Invalid input data']));
        return;
    }

    $data = $this->input->post();

    // Log the received department ID and the data array
    log_message('debug', 'Department ID received: ' . $data['department_id']);
    log_message('debug', 'Full data received: ' . json_encode($data));

    // Fetch department details based on department ID, including school_id
    $this->db->select('id, name, school_id');
    $this->db->from('departments');
    $this->db->where('id', $data['department_id']);
    $department = $this->db->get()->row_array();
    
    if (!$department) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['error' => 'Invalid department ID']));
        return;
    }

    $department_id = $department['id'];
    $school_id = $department['school_id'];

    // Prepare social links as JSON
    $social_links = json_encode([
        'facebook' => $data['facebook'],
        'linkedin' => $data['linkedin'],
        'twitter' => $data['twitter'],
    ]);

    // Insert the user data into the 'users' table
    $user_data = [
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => password_hash($data['password'], PASSWORD_BCRYPT), // Hash the password
        'address' => $data['address'],
        'phone' => $data['phone'],
        'gender' => $data['gender'],
        'school_id' => $school_id // Include school_id
    ];
    $this->db->insert('users', $user_data);
    $user_id = $this->db->insert_id();

    if (!$user_id) {
        $this->output
            ->set_status_header(500)
            ->set_output(json_encode(['error' => 'Failed to create user']));
        return;
    }

    // Insert the teacher data into the 'teachers' table
    $teacher_data = [
        'user_id' => $user_id,
        'department_id' => $department_id,
        'school_id' => $school_id, // Use school_id from department
        'designation' => $data['designation'],
        'about' => $data['about'],
        'social_links' => $social_links,
    ];
    $this->db->insert('teachers', $teacher_data);
    $teacher_id = $this->db->insert_id();

    if (!$teacher_id) {
        $this->output
            ->set_status_header(500)
            ->set_output(json_encode(['error' => 'Failed to create teacher']));
        return;
    }

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
        $upload_path = './uploads/users/';
        $image_path = $upload_path . $user_id . '.jpg'; // Save the image as 'user_id.jpg'

        // Load the upload library
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['file_name'] = $user_id . '.jpg';
        $config['overwrite'] = true;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('image')) {
            $this->output
                ->set_status_header(500)
                ->set_output(json_encode(['error' => $this->upload->display_errors()]));
            return;
        }
    }

    // Return success response
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['success' => 'Teacher created successfully', 'teacher_id' => $teacher_id]));
}

public function edit_teacher_put($teacher_id) {
    // Read the raw input
    $raw_input = file_get_contents('php://input');
    log_message('debug', 'Raw input data: ' . $raw_input);

    // Decode the JSON input
    $data = json_decode($raw_input, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['error' => 'Invalid JSON input']));
        return;
    }

    // Validate the input data
    $required_fields = ['name', 'email', 'address', 'phone', 'gender', 'department_id', 'designation', 'about'];
    foreach ($required_fields as $field) {
        if (!isset($data[$field])) {
            $this->output
                ->set_status_header(400)
                ->set_output(json_encode(['error' => 'Invalid input data: Missing ' . $field]));
            return;
        }
    }

    // Log the received data
    log_message('debug', 'Received data: ' . json_encode($data));

    // Fetch department details based on department ID, including school_id
    $this->db->select('id, name, school_id');
    $this->db->from('departments');
    $this->db->where('id', $data['department_id']);
    $department = $this->db->get()->row_array();

    if (!$department) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['error' => 'Invalid department ID']));
        return;
    }

    $department_id = $department['id'];
    $school_id = $department['school_id'];

    // Prepare social links as JSON
    $social_links = json_encode([
        'facebook' => $data['facebook'] ?? '',
        'linkedin' => $data['linkedin'] ?? '',
        'twitter' => $data['twitter'] ?? '',
    ]);

    // Fetch the existing teacher data
    $this->db->select('user_id');
    $this->db->from('teachers');
    $this->db->where('id', $teacher_id);
    $teacher = $this->db->get()->row_array();

    if (!$teacher) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['error' => 'Invalid teacher ID']));
        return;
    }

    $user_id = $teacher['user_id'];

    // Update the user data in the 'users' table
    $user_data = [
        'name' => $data['name'],
        'email' => $data['email'],
        'address' => $data['address'],
        'phone' => $data['phone'],
        'gender' => $data['gender'],
        'school_id' => $school_id // Include school_id
    ];

    $this->db->where('id', $user_id);
    $this->db->update('users', $user_data);

    // Update the teacher data in the 'teachers' table
    $teacher_data = [
        'department_id' => $department_id,
        'school_id' => $school_id, // Use school_id from department
        'designation' => $data['designation'],
        'about' => $data['about'],
        'social_links' => $social_links,
    ];

    $this->db->where('id', $teacher_id);
    $this->db->update('teachers', $teacher_data);

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
        $upload_path = './uploads/users/';
        $image_path = $upload_path . $user_id . '.jpg'; // Save the image as 'user_id.jpg'

        // Load the upload library
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['file_name'] = $user_id . '.jpg';
        $config['overwrite'] = true;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('image')) {
            $this->output
                ->set_status_header(500)
                ->set_output(json_encode(['error' => $this->upload->display_errors()]));
            return;
        }
    }

    // Return success response
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['success' => 'Teacher updated successfully']));
}


public function all_teacher_get() {
    // Fetch all teacher data along with user info and department name from the database
    $school_id = $this->input->get('school_id');
    if (!$school_id) {
        $school_id = school_id(); // Assuming you have a function to get current school ID
    }

    // Join teachers table with users and departments table
    $this->db->select('teachers.*, users.name, users.address, users.email, users.phone, users.gender, departments.name as department_name');
    $this->db->from('teachers');
    $this->db->join('users', 'teachers.user_id = users.id');
    $this->db->join('departments', 'teachers.department_id = departments.id');
    $this->db->where('teachers.school_id', $school_id);

    $query = $this->db->get();
    $teachers = $query->result_array();

    // Add image URL to each teacher
    foreach ($teachers as &$teacher) {
        $image_path = 'uploads/users/' . $teacher['user_id'] . '.jpg'; // Assuming 'user_id' is the unique identifier for each user
        if (file_exists($image_path)) {
            $teacher['image_url'] = base_url($image_path); // Assuming you're using CodeIgniter and 'base_url' is configured
        } else {
            $teacher['image_url'] = base_url('uploads/users/default.jpg'); // Default image if user image doesn't exist
        }
    }

    // Send the data as JSON
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($teachers));
}

public function teacher_by_id_get($teacher_id) {
    // Validate the input data
    if (!$teacher_id) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['error' => 'Invalid teacher ID']));
        return;
    }

    // Join teachers table with users and departments table
    $this->db->select('teachers.*, users.name, users.address, users.email, users.phone, users.gender, departments.name as department_name');
    $this->db->from('teachers');
    $this->db->join('users', 'teachers.user_id = users.id');
    $this->db->join('departments', 'teachers.department_id = departments.id');
    $this->db->where('teachers.id', $teacher_id);

    $query = $this->db->get();
    $teacher = $query->row_array();

    if (!$teacher) {
        $this->output
            ->set_status_header(404)
            ->set_output(json_encode(['error' => 'Teacher not found']));
        return;
    }

    // Add image URL to the teacher
    $image_path = 'uploads/users/' . $teacher['user_id'] . '.jpg'; // Assuming 'user_id' is the unique identifier for each user
    if (file_exists(FCPATH . $image_path)) { // Ensure the path check is accurate
        $teacher['image_url'] = base_url($image_path); // Assuming you're using CodeIgniter and 'base_url' is configured
    } else {
        $teacher['image_url'] = base_url('uploads/users/default.jpg'); // Default image if user image doesn't exist
    }

    // Decode social links
    $social_links = json_decode($teacher['social_links'], true);
    if ($social_links) {
        $teacher['facebook'] = $social_links['facebook'];
        $teacher['linkedin'] = $social_links['linkedin'];
        $teacher['twitter'] = $social_links['twitter'];
    } else {
        $teacher['facebook'] = null;
        $teacher['linkedin'] = null;
        $teacher['twitter'] = null;
    }

    // Remove the social_links field as it is now split into individual fields
    unset($teacher['social_links']);

    // Send the data as JSON
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($teacher));
}


public function department_get() {
    // Fetch all department data from the database
    $school_id = $this->input->get('school_id');
    if ($school_id) {
        $this->db->where('school_id', $school_id);
    }

    $query = $this->db->get('departments'); // Assuming your table name is 'departments'
    $departments = $query->result_array();

    // Send the data as JSON
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($departments));
}
public function search_get() {
    $school_id = $this->input->get('school_id');
    $name = $this->input->get('name');

    if (!$school_id) {
        $school_id = school_id(); // Assuming you have a function to get current school ID
    }

    if (!$name) {
        // Return an error response if the name parameter is missing
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['error' => 'Name parameter is required']));
        return;
    }

    // Join teachers table with users and departments table
    $this->db->select('teachers.*, users.name, users.address, users.email, users.phone, users.gender, departments.name as department_name');
    $this->db->from('teachers');
    $this->db->join('users', 'teachers.user_id = users.id');
    $this->db->join('departments', 'teachers.department_id = departments.id');
    $this->db->where('teachers.school_id', $school_id);
    $this->db->like('users.name', $name); // Perform a search using the name parameter

    $query = $this->db->get();
    $teachers = $query->result_array();

    // Add image URL to each teacher
    foreach ($teachers as &$teacher) {
        $image_path = 'uploads/users/' . $teacher['user_id'] . '.jpg'; // Assuming 'user_id' is the unique identifier for each user
        if (file_exists($image_path)) {
            $teacher['image_url'] = base_url($image_path); // Assuming you're using CodeIgniter and 'base_url' is configured
        } else {
            $teacher['image_url'] = base_url('uploads/users/default.jpg'); // Default image if user image doesn't exist
        }
    }

    // Send the data as JSON
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($teachers));
}

public function delete_teacher_delete($teacher_id) {
    // Validate the input data
    if (!$teacher_id) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['error' => 'Invalid teacher ID']));
        return;
    }

    // Fetch the existing teacher data
    $this->db->select('user_id');
    $this->db->from('teachers');
    $this->db->where('id', $teacher_id);
    $teacher = $this->db->get()->row_array();

    if (!$teacher) {
        $this->output
            ->set_status_header(404)
            ->set_output(json_encode(['error' => 'Teacher not found']));
        return;
    }

    $user_id = $teacher['user_id'];

    // Delete the teacher record
    $this->db->where('id', $teacher_id);
    $this->db->delete('teachers');

    // Delete the user record
    $this->db->where('id', $user_id);
    $this->db->delete('users');

    // Handle image deletionC:\xampp\htdocs\SchoolManagementWeb\application\config\hooks.php
    $image_path = './uploads/users/' . $user_id . '.jpg'; // Save the image as 'user_id.jpg'
    if (file_exists($image_path)) {
        unlink($image_path); // Remove the image file
    }

    // Return success response
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['success' => 'Teacher deleted successfully']));
}



//End Part Teacher//


//Teacher Permission//
public function classes_get() {
    // Fetch all class names from the 'classes' table
    $this->db->select('name');
    $query = $this->db->get('classes');
    $classes = $query->result_array();

    // Return the result as a JSON response
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($classes));
}

public function delete_course_delete($course_id) {
    // Ensure the request method is DELETE
    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
        $this->output
            ->set_status_header(405)
            ->set_output(json_encode(['message' => 'Method not allowed']));
        return;
    }

    // Fetch the course from the database to check if it exists
    $this->db->where('id', $course_id);
    $query = $this->db->get('course');

    if ($query->num_rows() > 0) {
        // Begin transaction
        $this->db->trans_begin();

        // Delete the course
        $this->db->where('id', $course_id);
        $this->db->delete('course');

        // Check if the course was deleted successfully
        if ($this->db->affected_rows() > 0) {
            // Commit transaction
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $this->output
                    ->set_status_header(500)
                    ->set_output(json_encode(['message' => 'Transaction failed']));
            } else {
                $this->db->trans_commit();
                $this->output
                    ->set_status_header(200)
                    ->set_output(json_encode(['message' => 'Course deleted successfully']));
            }
        } else {
            $this->db->trans_rollback();
            $this->output
                ->set_status_header(500)
                ->set_output(json_encode(['message' => 'Failed to delete course']));
        }
    } else {
        $this->output
            ->set_status_header(404)
            ->set_output(json_encode(['message' => 'Course not found']));
    }
}


public function add_course_section_post($course_id) {
    // Ensure the request is a POST request
    if ($this->input->server('REQUEST_METHOD') == 'POST') {
        // Retrieve input data
        $input_data = $this->input->post();
        $title = isset($input_data['title']) ? $input_data['title'] : null;

        // Log received title for debugging
        log_message('debug', 'Received title: ' . $title);
        log_message('debug', 'Received course_id: ' . $course_id);

        // Validate the input
        if (empty($title)) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['status' => 'error', 'message' => 'Title is required']));
            return;
        }

        // Prepare data for insertion
        $data['title'] = $title;
        $data['course_id'] = $course_id;

        // Get the highest order value for the course and increment it
 
        $this->db->where('course_id', $course_id);
        $query = $this->db->get('course_section');
        $row = $query->row();
     
        // Insert the new section
        if ($this->db->insert('course_section', $data)) {
            $section_id = $this->db->insert_id();
            log_message('debug', 'Section inserted with ID: ' . $section_id);
            
            // Fetch course details
            $this->db->where('id', $course_id);
            $course_details = $this->db->get('course')->row_array();
            log_message('debug', 'Course details: ' . print_r($course_details, true));

            // Decode the section array or initialize it if empty
            $previous_sections = isset($course_details['section']) ? json_decode($course_details['section'], true) : [];

            if (is_array($previous_sections)) {
                array_push($previous_sections, $section_id);
            } else {
                $previous_sections = array($section_id);
            }

            // Update the course with the new section list
            $updater['section'] = json_encode($previous_sections);
            $this->db->where('id', $course_id);
            if ($this->db->update('course', $updater)) {
                log_message('debug', 'Course updated successfully with new sections');
                $this->output
                     ->set_content_type('application/json')
                     ->set_output(json_encode(['status' => 'success', 'message' => 'Section added successfully']));
            } else {
                log_message('error', 'Failed to update course sections');
                $this->output
                     ->set_content_type('application/json')
                     ->set_output(json_encode(['status' => 'error', 'message' => 'Failed to update course sections']));
            }
        } else {
            log_message('error', 'Failed to insert new section');
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['status' => 'error', 'message' => 'Failed to add section']));
        }
    } else {
        // Return method not allowed error
        log_message('error', 'Invalid request method');
        $this->output
             ->set_content_type('application/json')
             ->set_status_header(405)
             ->set_output(json_encode(['status' => 'error', 'message' => 'Invalid request method']));
    }
}

public function sections_get($course_id) {
    // Ensure the request is a GET request
    if ($this->input->server('REQUEST_METHOD') == 'GET') {
        // Fetch sections based on the course ID
        $this->db->select('*');
        $this->db->from('course_section');
        $this->db->where('course_id', $course_id);
        
        $query = $this->db->get();
        $sections = $query->result_array();

        // Check if sections are found
        if ($sections) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['status' => 'success', 'sections' => $sections]));
        } else {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['status' => 'error', 'message' => 'No sections found']));
        }
    } else {
        // Return method not allowed error
        $this->output
             ->set_content_type('application/json')
             ->set_status_header(405)
             ->set_output(json_encode(['status' => 'error', 'message' => 'Invalid request method']));
    }
}

public function sections_title_post($course_id, $section_id) {
    // Ensure the request is a POST request
    if ($this->input->server('REQUEST_METHOD') == 'POST') {
        // Get the new title from the POST data
        $new_title = $this->input->post('title');

        // Validate the input
        if (empty($new_title)) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['status' => 'error', 'message' => 'Title cannot be empty']));
            return;
        }

        // Update the section title in the database
        $this->db->where('course_id', $course_id);
        $this->db->where('id', $section_id);
        $this->db->update('course_section', ['title' => $new_title]);

        // Check if the update was successful
        if ($this->db->affected_rows() > 0) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['status' => 'success', 'message' => 'Section title updated successfully']));
        } else {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['status' => 'error', 'message' => 'Failed to update section title']));
        }
    } else {
        // Return method not allowed error
        $this->output
             ->set_content_type('application/json')
             ->set_status_header(405)
             ->set_output(json_encode(['status' => 'error', 'message' => 'Invalid request method']));
    }
}

public function sections_del_delete($course_id, $section_id) {
    // Ensure the request is a DELETE request
    if ($this->input->server('REQUEST_METHOD') == 'DELETE') {
        // Delete the section from the database
        $this->db->where('course_id', $course_id);
        $this->db->where('id', $section_id);
        $this->db->delete('course_section');

        // Check if the deletion was successful
        if ($this->db->affected_rows() > 0) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['status' => 'success', 'message' => 'Section deleted successfully']));
        } else {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['status' => 'error', 'message' => 'Failed to delete section']));
        }
    } else {
        // Return method not allowed error
        $this->output
             ->set_content_type('application/json')
             ->set_status_header(405)
             ->set_output(json_encode(['status' => 'error', 'message' => 'Invalid request method']));
    }
}


public function quizzes_by_section_get($section_id) {
    // Ensure the request is a GET request
    if ($this->input->server('REQUEST_METHOD') == 'GET') {
        // Fetch quizzes based on the section ID
        $this->db->select('*');
        $this->db->from('lesson');
        $this->db->where('section_id', $section_id);
        $this->db->where('lesson_type', 'quiz'); // Assuming 'lesson_type' column marks a lesson as a quiz
        $query = $this->db->get();
        $quizzes = $query->result_array();

        // Check if quizzes are found
        if ($quizzes) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['status' => 'success', 'quizzes' => $quizzes]));
        } else {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['status' => 'error', 'message' => 'No quizzes found']));
        }
    } else {
        // Return method not allowed error
        $this->output
             ->set_content_type('application/json')
             ->set_status_header(405)
             ->set_output(json_encode(['status' => 'error', 'message' => 'Invalid request method']));
    }
}

public function quizzes_by_course_get($course_id) {
    // Ensure the request is a GET request
    if ($this->input->server('REQUEST_METHOD') == 'GET') {
        // Fetch quizzes based on the course ID
        $this->db->select('*');
        $this->db->from('lesson');
        $this->db->where('course_id', $course_id);
        $this->db->where('lesson_type', 'quiz'); // Assuming 'lesson_type' column marks a lesson as a quiz
        $query = $this->db->get();
        $quizzes = $query->result_array();

        // Check if quizzes are found
        if ($quizzes) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['status' => 'success', 'quizzes' => $quizzes]));
        } else {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['status' => 'error', 'message' => 'No quizzes found']));
        }
    } else {
        // Return method not allowed error
        $this->output
             ->set_content_type('application/json')
             ->set_status_header(405)
             ->set_output(json_encode(['status' => 'error', 'message' => 'Invalid request method']));
    }
}


public function quizzes_get($course_id = null, $section_id = null) {
    // Ensure the request is a GET request
    if ($this->input->server('REQUEST_METHOD') == 'GET') {
        $this->db->select('*');
        $this->db->from('lesson');
        
        if ($section_id !== null) {
            // Fetch quizzes based on the section ID
            $this->db->where('section_id', $section_id);
        } else if ($course_id !== null) {
            // Fetch quizzes based on the course ID
            $this->db->where('course_id', $course_id);
        } else {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['status' => 'error', 'message' => 'No course_id or section_id provided']));
            return;
        }

        $this->db->where('lesson_type', 'quiz'); // Assuming 'lesson_type' column marks a lesson as a quiz
        $this->db->order_by('order', 'ASC'); // Order by the 'order' field in ascending order
        $query = $this->db->get();
        $quizzes = $query->result_array();

        // Check if quizzes are found
        if ($quizzes) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['status' => 'success', 'quizzes' => $quizzes]));
        } else {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['status' => 'error', 'message' => 'No quizzes found']));
        }
    } else {
        // Return method not allowed error
        $this->output
             ->set_content_type('application/json')
             ->set_status_header(405)
             ->set_output(json_encode(['status' => 'error', 'message' => 'Invalid request method']));
    }
}


public function update_quiz_order_post($section_id = null) {
    // Ensure the request is a POST request
    if ($this->input->server('REQUEST_METHOD') == 'POST') {
        // Get the POST data
        $input_data = $this->input->post();

        // Check if section_id is provided
        if ($section_id === null) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'No section_id provided']));
            return;
        }

        // Check if the input data is an array of quizzes with their order
        if (!isset($input_data['quizzes']) || !is_array($input_data['quizzes'])) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Invalid input data']));
            return;
        }

        // Start transaction
        $this->db->trans_start();

        // Update the order of each quiz
        foreach ($input_data['quizzes'] as $quiz) {
            if (isset($quiz['id']) && isset($quiz['order'])) {
                $this->db->where('id', $quiz['id']);
                $this->db->where('lesson_type', 'quiz'); // Ensure the lesson is a quiz
                $this->db->where('section_id', $section_id); // Ensure the quiz belongs to the section
                $this->db->update('lesson', ['order' => $quiz['order']]);
            }
        }

        // Complete the transaction
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            // Transaction failed, return error response
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Failed to update quiz order']));
        } else {
            // Transaction succeeded, return success response
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'success', 'message' => 'Quiz order updated successfully']));
        }
    } else {
        // Return method not allowed error
        $this->output
            ->set_content_type('application/json')
            ->set_status_header(405)
            ->set_output(json_encode(['status' => 'error', 'message' => 'Invalid request method']));
    }
}



public function quiz_questions_get($quiz_id) {
    // Ensure the request is a GET request
    if ($this->input->server('REQUEST_METHOD') == 'GET') {
        // Fetch quiz questions based on the quiz ID
        $this->db->order_by("order", "asc");
        $this->db->where('quiz_id', $quiz_id);
        $query = $this->db->get('question');
        $questions = $query->result_array();

        // Check if questions are found
        if ($questions) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['status' => 'success', 'questions' => $questions]));
        } else {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['status' => 'error', 'message' => 'No questions found']));
        }
    } else {
        // Return method not allowed error
        $this->output
             ->set_content_type('application/json')
             ->set_status_header(405)
             ->set_output(json_encode(['status' => 'error', 'message' => 'Invalid request method']));
    }
}


public function add_quiz_post() {
    // Ensure the request is a POST request
    if ($this->input->server('REQUEST_METHOD') == 'POST') {
        // Retrieve input data using $this->input->post
        $title = $this->input->post('title');
        $section_name = $this->input->post('section_name');
        $summary = $this->input->post('instruction');  // Changed 'instruction' to 'summary'

        // Log received data for debugging
        log_message('debug', 'Received title: ' . $title);
        log_message('debug', 'Received section_name: ' . $section_name);
        log_message('debug', 'Received summary: ' . $summary);  // Log 'summary'

        // Validate the input
        if (empty($title) || empty($section_name)) {
            log_message('error', 'Validation failed: Title and Section Name are required');
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Title and Section Name are required']));
            return;
        }

        // Fetch section ID from section name
        $this->db->select('id');
        $this->db->from('course_section');
        $this->db->where('title', $section_name);
        $query = $this->db->get();

        if ($query->num_rows() == 0) {
            log_message('error', 'Invalid Section Name: ' . $section_name);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Invalid Section Name']));
            return;
        }

        $section = $query->row();
        $section_id = $section->id;

        // Prepare data for insertion
        $data = [
            'title' => $title,
            'section_id' => $section_id,
            'summary' => $summary,  // Changed 'instruction' to 'summary'
            'lesson_type' => 'quiz', // Assuming 'lesson_type' column marks a lesson as a quiz
            // Removed 'created_at'
        ];

        // Log data to be inserted
        log_message('debug', 'Data to be inserted: ' . json_encode($data));

        // Insert the new quiz
        if ($this->db->insert('lesson', $data)) {
            $quiz_id = $this->db->insert_id();
            log_message('debug', 'Quiz inserted with ID: ' . $quiz_id);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'success', 'message' => 'Quiz added successfully', 'quiz_id' => $quiz_id]));
        } else {
            log_message('error', 'Failed to insert new quiz');
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Failed to add quiz']));
        }
    } else {
        // Return method not allowed error
        log_message('error', 'Invalid request method');
        $this->output
            ->set_content_type('application/json')
            ->set_status_header(405)
            ->set_output(json_encode(['status' => 'error', 'message' => 'Invalid request method']));
    }
}


 public function update_quiz_post() {
    // Ensure the request is a POST request
    if ($this->input->server('REQUEST_METHOD') == 'POST') {
        // Retrieve input data using $this->input->post
        $quiz_id = $this->input->post('quiz_id');
        $title = $this->input->post('title');
        $section_name = $this->input->post('section_name');
        $summary = $this->input->post('summary'); // Changed 'instruction' to 'summary'

        // Log received data for debugging
        log_message('debug', 'Received quiz_id: ' . $quiz_id);
        log_message('debug', 'Received title: ' . $title);
        log_message('debug', 'Received section_name: ' . $section_name);
        log_message('debug', 'Received summary: ' . $summary);  // Log 'summary'

        // Validate the input
        if (empty($quiz_id) || empty($title) || empty($section_name)) {
            log_message('error', 'Validation failed: Quiz ID, Title, and Section Name are required');
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Quiz ID, Title, and Section Name are required']));
            return;
        }

        // Fetch section ID from section name
        $this->db->select('id');
        $this->db->from('course_section');
        $this->db->where('title', $section_name);
        $query = $this->db->get();

        if ($query->num_rows() == 0) {
            log_message('error', 'Invalid Section Name: ' . $section_name);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Invalid Section Name']));
            return;
        }

        $section = $query->row();
        $section_id = $section->id;

        // Prepare data for updating
        $data = [
            'title' => $title,
            'section_id' => $section_id,
            'summary' => $summary,  // Changed 'instruction' to 'summary'
            'lesson_type' => 'quiz', // Assuming 'lesson_type' column marks a lesson as a quiz
        ];

        // Log data to be updated
        log_message('debug', 'Data to be updated: ' . json_encode($data));

        // Update the quiz
        $this->db->where('id', $quiz_id);
        if ($this->db->update('lesson', $data)) {
            log_message('debug', 'Quiz updated with ID: ' . $quiz_id);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'success', 'message' => 'Quiz updated successfully', 'quiz_id' => $quiz_id]));
        } else {
            log_message('error', 'Failed to update quiz');
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Failed to update quiz']));
        }
    } else {
        // Return method not allowed error
        log_message('error', 'Invalid request method');
        $this->output
            ->set_content_type('application/json')
            ->set_status_header(405)
            ->set_output(json_encode(['status' => 'error', 'message' => 'Invalid request method']));
    }
}

public function delete_quiz_post() {
    // Ensure the request is a POST request
    if ($this->input->server('REQUEST_METHOD') == 'POST') {
        // Retrieve input data using $this->input->post
        $quiz_id = $this->input->post('quiz_id');

        // Log received data for debugging
        log_message('debug', 'Received quiz_id: ' . $quiz_id);

        // Validate the input
        if (empty($quiz_id)) {
            log_message('error', 'Validation failed: Quiz ID is required');
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Quiz ID is required']));
            return;
        }

        // Check if the quiz exists
        $this->db->select('id');
        $this->db->from('lesson');
        $this->db->where('id', $quiz_id);
        $query = $this->db->get();

        if ($query->num_rows() == 0) {
            log_message('error', 'Invalid Quiz ID: ' . $quiz_id);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Invalid Quiz ID']));
            return;
        }

        // Delete the quiz
        $this->db->where('id', $quiz_id);
        if ($this->db->delete('lesson')) {
            log_message('debug', 'Quiz deleted with ID: ' . $quiz_id);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'success', 'message' => 'Quiz deleted successfully', 'quiz_id' => $quiz_id]));
        } else {
            log_message('error', 'Failed to delete quiz');
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Failed to delete quiz']));
        }
    } else {
        // Return method not allowed error
        log_message('error', 'Invalid request method');
        $this->output
            ->set_content_type('application/json')
            ->set_status_header(405)
            ->set_output(json_encode(['status' => 'error', 'message' => 'Invalid request method']));
    }
}

public function add_question_post() {
    // Ensure the request is a POST request
    if ($this->input->server('REQUEST_METHOD') == 'POST') {
        // Retrieve input data using $this->input->post
        $quiz_id = $this->input->post('quiz_id');
        $title = $this->input->post('title');
        $type = $this->input->post('type');
        $number_of_options = $this->input->post('number_of_options');
        $options = $this->input->post('options'); // Expecting JSON format
        $correct_answers = $this->input->post('correct_answers'); // Expecting JSON format
        $order = $this->input->post('order');

        // Log received data for debugging
        log_message('debug', 'Received quiz_id: ' . $quiz_id);
        log_message('debug', 'Received title: ' . $title);
        log_message('debug', 'Received type: ' . $type);
        log_message('debug', 'Received number_of_options: ' . $number_of_options);
        log_message('debug', 'Received options: ' . $options);
        log_message('debug', 'Received correct_answers: ' . $correct_answers);
        log_message('debug', 'Received order: ' . $order);

        // Validate the input
        if (empty($quiz_id) || empty($title) || empty($type) || empty($number_of_options) || empty($options) || empty($correct_answers)) {
            log_message('error', 'Validation failed: Quiz ID, Title, Type, Number of Options, Options, and Correct Answers are required');
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Quiz ID, Title, Type, Number of Options, Options, and Correct Answers are required']));
            return;
        }

        // Prepare data for insertion
        $data = [
            'quiz_id' => $quiz_id,
            'title' => $title,
            'type' => $type,
            'number_of_options' => $number_of_options,
            'options' => $options,
            'correct_answers' => $correct_answers,
            'order' => $order,
        ];

        // Log data to be inserted
        log_message('debug', 'Data to be inserted: ' . json_encode($data));

        // Insert the new question
        if ($this->db->insert('question', $data)) {
            $question_id = $this->db->insert_id();
            log_message('debug', 'Question inserted with ID: ' . $question_id);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'success', 'message' => 'Question added successfully', 'question_id' => $question_id]));
        } else {
            log_message('error', 'Failed to insert new question');
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Failed to add question']));
        }
    } else {
        // Return method not allowed error
        log_message('error', 'Invalid request method');
        $this->output
            ->set_content_type('application/json')
            ->set_status_header(405)
            ->set_output(json_encode(['status' => 'error', 'message' => 'Invalid request method']));
    }
}


 public function get_quiz_questions_get($quiz_id) {
    if ($this->input->server('REQUEST_METHOD') == 'GET') {
        $this->db->order_by("order", "asc");
        $this->db->where('quiz_id', $quiz_id);
        $query = $this->db->get('question');
        $questions = $query->result_array();

        if ($questions) {
            // Decode options and add them to the response
            foreach ($questions as &$question) {
                $question['options'] = json_decode($question['options'], true);
            }
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'success', 'questions' => $questions]));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'No questions found']));
        }
    } else {
        $this->output
            ->set_content_type('application/json')
            ->set_status_header(405)
            ->set_output(json_encode(['status' => 'error', 'message' => 'Invalid request method']));
    }
}


public function edit_question_post() {
    // Ensure the request is a POST request
    if ($this->input->server('REQUEST_METHOD') == 'POST') {
        // Retrieve input data using $this->input->post
        $question_id = $this->input->post('question_id');
        $quiz_id = $this->input->post('quiz_id');
        $title = $this->input->post('title');
        $type = $this->input->post('type');
        $number_of_options = $this->input->post('number_of_options');
        $options = $this->input->post('options'); // Expecting JSON format
        $correct_answers = $this->input->post('correct_answers'); // Expecting JSON format
        $order = $this->input->post('order');

        // Log received data for debugging
        log_message('debug', 'Received question_id: ' . $question_id);
        log_message('debug', 'Received quiz_id: ' . $quiz_id);
        log_message('debug', 'Received title: ' . $title);
        log_message('debug', 'Received type: ' . $type);
        log_message('debug', 'Received number_of_options: ' . $number_of_options);
        log_message('debug', 'Received options: ' . $options);
        log_message('debug', 'Received correct_answers: ' . $correct_answers);
        log_message('debug', 'Received order: ' . $order);

        // Validate the input
        if (empty($question_id) || empty($quiz_id) || empty($title) || empty($type) || empty($number_of_options) || empty($options) || empty($correct_answers)) {
            log_message('error', 'Validation failed: Question ID, Quiz ID, Title, Type, Number of Options, Options, and Correct Answers are required');
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Question ID, Quiz ID, Title, Type, Number of Options, Options, and Correct Answers are required']));
            return;
        }

        // Convert necessary fields to appropriate types
        $number_of_options = (int)$number_of_options;
        $order = (int)$order;

        // Prepare data for update
        $data = [
            'quiz_id' => $quiz_id,
            'title' => $title,
            'type' => $type,
            'number_of_options' => $number_of_options,
            'options' => $options,
            'correct_answers' => $correct_answers,
            'order' => $order,
        ];

        // Log data to be updated
        log_message('debug', 'Data to be updated: ' . json_encode($data));

        // Update the question
        $this->db->where('id', $question_id);
        if ($this->db->update('question', $data)) {
            log_message('debug', 'Question updated with ID: ' . $question_id);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'success', 'message' => 'Question updated successfully']));
        } else {
            log_message('error', 'Failed to update question');
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Failed to update question']));
        }
    } else {
        // Return method not allowed error
        log_message('error', 'Invalid request method');
        $this->output
            ->set_content_type('application/json')
            ->set_status_header(405)
            ->set_output(json_encode(['status' => 'error', 'message' => 'Invalid request method']));
    }
}


public function delete_question_post() {
    // Ensure the request is a POST request
    if ($this->input->server('REQUEST_METHOD') == 'POST') {
        // Retrieve input data using $this->input->post
        $question_id = $this->input->post('question_id');

        // Log received data for debugging
        log_message('debug', 'Received question_id: ' . $question_id);

        // Validate the input
        if (empty($question_id)) {
            log_message('error', 'Validation failed: Question ID is required');
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Question ID is required']));
            return;
        }

        // Check if the question exists
        $this->db->where('id', $question_id);
        $query = $this->db->get('question');
        if ($query->num_rows() == 0) {
            log_message('error', 'Question not found with ID: ' . $question_id);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Question not found']));
            return;
        }

        // Delete the question
        $this->db->where('id', $question_id);
        if ($this->db->delete('question')) {
            log_message('debug', 'Question deleted with ID: ' . $question_id);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'success', 'message' => 'Question deleted successfully']));
        } else {
            log_message('error', 'Failed to delete question');
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Failed to delete question']));
        }
    } else {
        // Return method not allowed error
        log_message('error', 'Invalid request method');
        $this->output
            ->set_content_type('application/json')
            ->set_status_header(405)
            ->set_output(json_encode(['status' => 'error', 'message' => 'Invalid request method']));
    }
}


public function add_lesson_post() {
    $this->load->library('form_validation');

    // Set validation rules
    $this->form_validation->set_rules('course_id', 'Course ID', 'required');
    $this->form_validation->set_rules('title', 'Title', 'required');
    $this->form_validation->set_rules('section_id', 'Section ID', 'required');
    $this->form_validation->set_rules('lesson_type', 'Lesson Type', 'required');

    if ($this->form_validation->run() == FALSE) {
        $response = [
            'status' => 'error',
            'message' => validation_errors()
        ];
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
        return;
    }

    $data['course_id'] = $this->input->post('course_id');
    $data['title'] = $this->input->post('title');
    $data['section_id'] = $this->input->post('section_id');
    $data['summary'] = $this->input->post('summary');
    $data['date_added'] = strtotime(date('D, d-M-Y'));

    $lesson_type_array = explode('-', $this->input->post('lesson_type'));
    $lesson_type = $lesson_type_array[0];
    $data['lesson_type'] = $lesson_type;
    $data['attachment_type'] = isset($lesson_type_array[1]) ? $lesson_type_array[1] : null;

    if ($lesson_type == 'video') {
        $lesson_provider = $this->input->post('lesson_provider');
        if ($lesson_provider == 'youtube' || $lesson_provider == 'vimeo') {
            $data['video_url'] = $this->input->post('video_url');
            $duration_formatter = explode(':', $this->input->post('duration'));
            $data['duration'] = sprintf('%02d:%02d:%02d', $duration_formatter[0], $duration_formatter[1], $duration_formatter[2]);
            $data['video_type'] = $lesson_provider;
        } elseif ($lesson_provider == 'html5') {
            $data['video_url'] = $this->input->post('html5_video_url');
            $duration_formatter = explode(':', $this->input->post('html5_duration'));
            $data['duration'] = sprintf('%02d:%02d:%02d', $duration_formatter[0], $duration_formatter[1], $duration_formatter[2]);
            $data['video_type'] = 'html5';
            $this->upload_file('thumbnail', 'uploads/thumbnails/lesson_thumbnails/', $inserted_id . '.jpg');
        } elseif ($lesson_provider == 'mydevice') {
            $data['video_type'] = 'mydevice';
            $data['video_upload'] = $this->upload_file('userfileMe', 'uploads/videos/');
        } else {
            $response = ['status' => 'error', 'message' => 'Invalid lesson provider'];
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
            return;
        }
    } else {
        $data['duration'] = 0;
        $data['attachment'] = $this->upload_file('attachment', 'uploads/lesson_files/');
    }

    $this->db->insert('lesson', $data);
    $response = ['status' => 'success', 'message' => 'Lesson added successfully'];
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response));
}

private function upload_file($field_name, $upload_path, $file_name = null) {
    if (!file_exists($upload_path)) {
        mkdir($upload_path, 0777, true);
    }

    if (isset($_FILES[$field_name]) && $_FILES[$field_name]['error'] == 0) {
        $file_type = pathinfo($_FILES[$field_name]['name'], PATHINFO_EXTENSION);
        $file_name = $file_name ?? md5(uniqid(rand(), true)) . '.' . $file_type;
        $destination = $upload_path . $file_name;

        if (move_uploaded_file($_FILES[$field_name]['tmp_name'], $destination)) {
            return $file_name;
        } else {
            $response = ['status' => 'error', 'message' => 'There was a problem moving the file.'];
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
            exit;
        }
    } else {
        $response = ['status' => 'error', 'message' => 'No file uploaded or there was an upload error.'];
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
        exit;
    }
}


public function all_lesson_types_get($section_id) {
    // Ensure the request method is GET
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        $this->output
             ->set_status_header(405)
             ->set_output(json_encode(['message' => 'Method not allowed']));
        return;
    }

    // Validate the section_id
    if (!is_numeric($section_id)) {
        $this->output
             ->set_status_header(400)
             ->set_output(json_encode(['message' => 'Invalid section ID']));
        return;
    }

    // Base URLs for attachments and video uploads
    $base_attachment_url = 'http://10.0.2.2/SchoolManagementWeb/uploads/lesson_files/';
    $base_video_url = 'http://10.0.2.2/SchoolManagementWeb/uploads/videos/';

    // Fetch distinct lesson types, lesson titles, attachments, and video uploads from the 'lesson' table for the given section_id
    $this->db->select("lesson.lesson_type, lesson.title as lesson_title, 
                       CONCAT('$base_attachment_url', lesson.attachment) as attachment, 
                       CONCAT('$base_video_url', lesson.video_upload) as video_upload");
    $this->db->from('lesson');
    $this->db->where('lesson.section_id', $section_id);
    $query = $this->db->get();
    $lessons = $query->result_array();

    // Check if lessons are found
    if (!empty($lessons)) {
        $this->output
             ->set_status_header(200)
             ->set_content_type('application/json')
             ->set_output(json_encode($lessons));
    } else {
        $this->output
             ->set_status_header(404)
             ->set_output(json_encode(['message' => 'No lessons found for the given section ID']));
    }
}


public function associate_user_with_school_post() {
    // Ensure the request is a POST request
    if ($this->input->server('REQUEST_METHOD') !== 'POST') {
        $this->output
             ->set_status_header(405)
             ->set_output(json_encode(['message' => 'Method not allowed']));
        return;
    }

    // Log the entire $_POST array
    log_message('debug', 'POST data: ' . json_encode($this->input->post()));

    // Log the raw POST data
    log_message('debug', 'Raw POST data: ' . file_get_contents('php://input'));

    // Retrieve input data
    $postData = json_decode(file_get_contents('php://input'), true);
    $user_id = isset($postData['user_id']) ? $postData['user_id'] : null;
    $school_id = isset($postData['school_id']) ? $postData['school_id'] : null;
    $session = isset($postData['session']) ? $postData['session'] : 1; // Default to 1 if not provided

    // Log received data for debugging
    log_message('debug', 'Received user_id: ' . json_encode($user_id));
    log_message('debug', 'Received school_id: ' . json_encode($school_id));
    log_message('debug', 'Received session: ' . json_encode($session));

    // Validate the input
    if (empty($user_id) || empty($school_id)) {
        log_message('error', 'Validation failed: User ID and School ID are required');
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode(['message' => 'User ID and School ID are required']));
        return;
    }

    // Check if the user exists
    $this->db->select('id as user_id');
    $this->db->from('users');
    $this->db->where('id', $user_id);
    $user_query = $this->db->get();

    if ($user_query->num_rows() == 0) {
        log_message('error', 'Invalid User ID: ' . $user_id);
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode(['message' => 'Invalid User ID']));
        return;
    }

    // Check if the school exists
    $this->db->select('id as school_id');
    $this->db->from('schools');
    $this->db->where('id', $school_id);
    $school_query = $this->db->get();

    if ($school_query->num_rows() == 0) {
        log_message('error', 'Invalid School ID: ' . $school_id);
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode(['message' => 'Invalid School ID']));
        return;
    }

    // Prepare data for insertion
    $data = [
        'user_id' => $user_id,
        'school_id' => $school_id,
        'session' => $session, // Note the change here
        'status' => 0 // Default status to 0
    ];

    // Log data to be inserted
    log_message('debug', 'Data to be inserted: ' . json_encode($data));

    // Insert data into the students table
    if ($this->db->insert('students', $data)) {
        log_message('debug', 'Student associated successfully with ID: ' . $this->db->insert_id());
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode(['status' => 'success', 'message' => 'User associated with school successfully']));
    } else {
        log_message('error', 'Failed to associate user with school');
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode(['status' => 'error', 'message' => 'Failed to associate user with school']));
    }
}




public function get_students_list_get() {
    // Log the request for debugging purposes
    log_message('debug', 'Received request for students list');

    // Get school_id from the GET parameters
    $school_id = $this->input->get('school_id');

    // Check if school_id is provided
    if (!$school_id) {
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode(['status' => 'error', 'message' => 'School ID is required']));
        return;
    }

    // Query the database for the list of students along with their user information
    $this->db->select('students.id, students.code, students.user_id, students.session, students.school_id, students.status, users.name, users.email, users.role, users.address, users.phone, users.birthday, users.gender');
    $this->db->from('students');
    $this->db->join('users', 'students.user_id = users.id', 'left');
    $this->db->where('students.school_id', $school_id);
    $this->db->where('students.status', 0); // Add condition to filter by status = 0
    $students_query = $this->db->get();

    if ($students_query->num_rows() > 0) {
        $students = $students_query->result_array();

        // Add full image URL to each student
        foreach ($students as &$student) {
            $image_path = 'uploads/users/' . $student['user_id'] . '.jpg'; // Assuming 'user_id' is the unique identifier for each user
            if (file_exists(FCPATH . $image_path)) {
                $student['image_url'] = base_url($image_path); // Assuming you're using CodeIgniter and 'base_url' is configured
            } else {
                $student['image_url'] = base_url('uploads/users/default.jpg'); // Default image if user image doesn't exist
            }
        }

        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode(['status' => 'success', 'students' => $students]));
    } else {
        log_message('error', 'No students found');
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode(['status' => 'error', 'message' => 'No students found']));
    }
}



public function approve_student_post() {
    // Validate the input data
    if (!$this->input->post('students_id')) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['error' => 'Invalid input data']));
        return;
    }

    $student_id = $this->input->post('students_id');

    // Update the student status to 1
    $this->db->where('id', $student_id);
    $this->db->update('students', ['status' => 1]);

    if ($this->db->affected_rows() > 0) {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['success' => 'Student approved successfully']));
    } else {
        $this->output
            ->set_status_header(500)
            ->set_output(json_encode(['error' => 'Failed to approve student']));
    }
}


public function section_get() {
    // Log the request for debugging purposes
    log_message('debug', 'Received request for sections list');

    // Query the database for the list of sections
    $this->db->select('id, name, class_id');
    $this->db->from('sections');
    $sections_query = $this->db->get();

    if ($sections_query->num_rows() > 0) {
        $sections = $sections_query->result_array();

        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode(['status' => 'success', 'sections' => $sections]));
    } else {
        log_message('error', 'No sections found');
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode(['status' => 'error', 'message' => 'No sections found']));
    }
}



public function delete_student_post() {
    // Validate the input data
    if (!$this->input->post('students_id')) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['error' => 'Invalid input data']));
        return;
    }

    $student_id = $this->input->post('students_id');

    // Delete the student
    $this->db->where('id', $student_id);
    $this->db->delete('students');

    if ($this->db->affected_rows() > 0) {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['success' => 'Student deleted successfully']));
    } else {
        $this->output
            ->set_status_header(500)
            ->set_output(json_encode(['error' => 'Failed to delete student']));
    }
}

public function count_student_online_admission_get() {
    // Log the request for debugging purposes
    log_message('debug', 'Received request to count students for online admission');

    // Get school_id from the GET parameters
    $school_id = $this->input->get('school_id');

    // Check if school_id is provided
    if (!$school_id) {
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode(['status' => 'error', 'message' => 'School ID is required']));
        return;
    }

    // Query the database to count the number of students with status 0 for the given school_id
    $this->db->where('school_id', $school_id);
    $this->db->where('status', 0);
    $this->db->from('students');
    $count = $this->db->count_all_results();

    // Return the count as a JSON response
    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode(['status' => 'success', 'count' => $count]));
}

public function get_student_id_post() {
    // Log the request for debugging purposes
    log_message('debug', 'Received request to get student id');

    // Validate the input data
    $user_id = $this->input->post('user_id');
    $school_id = $this->input->post('school_id');
    if (!$user_id || !$school_id) {
        log_message('error', 'Invalid input data: user_id or school_id is missing');
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['error' => 'Invalid input data']));
        return;
    }

    // Fetch the student's id based on user_id and school_id
    $this->db->select('id');
    $this->db->from('students');
    $this->db->where('user_id', $user_id);
    $this->db->where('school_id', $school_id);
    $student_query = $this->db->get();

    if ($student_query->num_rows() === 0) {
        log_message('error', 'Student not found with user_id: ' . $user_id . ' and school_id: ' . $school_id);
        $this->output
            ->set_status_header(404)
            ->set_output(json_encode(['error' => 'Student not found']));
        return;
    }

    $student = $student_query->row();

    // Return the student id as a JSON response
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => 'success', 'student_id' => (int) $student->id])); // Ensure student_id is an integer
}


public function get_appropriate_courses_post() {
    // Log the request for debugging purposes
    log_message('debug', 'Received request for appropriate courses');

    // Validate the input data
    $student_id = $this->input->post('students_id');
    if (!$student_id) {
        log_message('error', 'Invalid input data: students_id is missing');
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['error' => 'Invalid input data']));
        return;
    }

    // Fetch the student's school_id and status
    $this->db->select('school_id, status');
    $this->db->from('students');
    $this->db->where('id', $student_id);
    $student_query = $this->db->get();

    if ($student_query->num_rows() === 0) {
        log_message('error', 'Student not found with id: ' . $student_id);
        $this->output
            ->set_status_header(404)
            ->set_output(json_encode(['error' => 'Student not found']));
        return;
    }

    $student = $student_query->row();
    
    // Check if the student's status is 1
    if ($student->status != 1) {
        log_message('error', 'Student is not approved with id: ' . $student_id);
        $this->output
            ->set_status_header(403)
            ->set_output(json_encode(['error' => 'Student is not approved']));
        return;
    }

    // Fetch the active courses for the student's school_id with class prices
    $this->db->select('course.*, classes.price, CONCAT("http://10.0.2.2/SchoolManagementWeb/uploads/course_thumbnail/", course.thumbnail) as thumbnail');
    $this->db->from('course');
    $this->db->join('classes', 'course.class_id = classes.id', 'left');
    $this->db->where('course.school_id', $student->school_id);
    $this->db->where('course.status', 'active');
    $course_query = $this->db->get();
    $courses = $course_query->result_array();

    // Return the courses as a JSON response
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => 'success', 'courses' => $courses]));
}


public function student_enrollments_post() {
    // Log the request for debugging purposes
    log_message('debug', 'Received request for student enrollments');

    // Validate the input data
    $student_id = $this->input->post('student_id');
    $course_id = $this->input->post('course_id');
    $section_id = $this->input->post('section_id'); // Ensure this is provided in the request

    if (!$student_id || !$course_id || !$section_id) {
        log_message('error', 'Invalid input data: student_id, course_id, or section_id is missing');
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['error' => 'Invalid input data']));
        return;
    }

    // Fetch the course information
    $this->db->select('course.*, classes.id as class_id, classes.school_id');
    $this->db->from('course');
    $this->db->join('classes', 'course.class_id = classes.id', 'left');
    $this->db->where('course.id', $course_id);
    $course_query = $this->db->get();

    if ($course_query->num_rows() === 0) {
        log_message('error', 'Course not found with id: ' . $course_id);
        $this->output
            ->set_status_header(404)
            ->set_output(json_encode(['error' => 'Course not found']));
        return;
    }

    $course = $course_query->row();

    // Prepare the enrollment data
    $enroll_data = [
        'student_id' => $student_id,
        'class_id' => $course->class_id,
        'section_id' => $section_id,
        'school_id' => $course->school_id,
        'session' => $this->input->post('session') ?? 1 // Default value of 1 if session is not provided
    ];

    // Insert the enrollment data into the enrols table
    $this->db->insert('enrols', $enroll_data);

    if ($this->db->affected_rows() === 0) {
        log_message('error', 'Failed to insert enrollment data for student with id: ' . $student_id);
        $this->output
            ->set_status_header(500)
            ->set_output(json_encode(['error' => 'Failed to insert enrollment data']));
        return;
    }

    // Fetch the student's updated enrollment information
    $this->db->select('students.*, enrols.*, sections.name as section_name, classes.name as class_name, classes.price, schools.name as school_name');
    $this->db->from('students');
    $this->db->join('enrols', 'students.id = enrols.student_id', 'left');
    $this->db->join('sections', 'enrols.section_id = sections.id', 'left');
    $this->db->join('classes', 'sections.class_id = classes.id', 'left');
    $this->db->join('schools', 'students.school_id = schools.id', 'left');
    $this->db->where('students.id', $student_id);
    $query = $this->db->get();

    if ($query->num_rows() === 0) {
        log_message('error', 'No enrollment found for student with id: ' . $student_id);
        $this->output
            ->set_status_header(404)
            ->set_output(json_encode(['error' => 'No enrollment found for student']));
        return;
    }

    $enrollments = $query->result_array();

    // Return the enrollment information as a JSON response
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => 'success', 'enrollments' => $enrollments]));
}

public function sections_for_class_get() {
    // Log the request for debugging purposes
    log_message('debug', 'Received request for sections for class');

    // Validate the input data
    $class_id = $this->input->get('class_id');
    if (!$class_id) {
        log_message('error', 'Invalid input data: class_id is missing');
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['error' => 'Invalid input data']));
        return;
    }

    // Fetch the sections for the given class ID
    $this->db->select('id, name');
    $this->db->from('sections');
    $this->db->where('class_id', $class_id);
    $query = $this->db->get();

    if ($query->num_rows() === 0) {
        log_message('error', 'No sections found for class with id: ' . $class_id);
        $this->output
            ->set_status_header(404)
            ->set_output(json_encode(['error' => 'No sections found for class']));
        return;
    }

    $sections = $query->result_array();

    // Return the sections as a JSON response
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => 'success', 'sections' => $sections]));
}










//End Online courses 


//Teacher Permission//


public function add_teacher_permission_post() {
    // Validate the input data
    if (!$this->input->post('teacher_id') || !$this->input->post('permission_type') || $this->input->post('value') === NULL) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['error' => 'Invalid input data']));
        return;
    }

    $data = [
        'teacher_id' => $this->input->post('teacher_id'),
        'permission_type' => $this->input->post('permission_type'),
        'value' => (int)$this->input->post('value'),  // Cast the value to integer
    ];

    // Check if the permission already exists
    $this->db->where('teacher_id', $data['teacher_id']);
    $this->db->where('permission_type', $data['permission_type']);
    $query = $this->db->get('permissions');

    if ($query->num_rows() > 0) {
        // Update the existing permission
        $this->db->where('teacher_id', $data['teacher_id']);
        $this->db->where('permission_type', $data['permission_type']);
        $this->db->update('permissions', ['value' => $data['value']]);
    } else {
        // Insert a new permission
        $this->db->insert('permissions', $data);
    }

    if ($this->db->affected_rows() > 0) {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['success' => 'Permission updated successfully']));
    } else {
        $this->output
            ->set_status_header(500)
            ->set_output(json_encode(['error' => 'Failed to update permission']));
    }
}

public function teachers_by_class_get($class_id) {
    // Ensure $class_id is an integer to prevent SQL injection
    $class_id = (int)$class_id;
    
    // Select the required fields from the 'users' table
    $this->db->select('
        users.id as user_id,
        users.name,
        users.email,
        users.role,
        users.address,
        users.phone,
        users.birthday,
        users.gender,
        users.school_id as user_school_id,
        users.status
    ');
    $this->db->from('users');
    
    // Join the 'teachers' table on user_id
    $this->db->join('teachers', 'teachers.user_id = users.id');
    
    // Join the 'teacher_permissions' table on teacher_id
    $this->db->join('teacher_permissions', 'teacher_permissions.teacher_id = teachers.id');
    
    // Join the 'classes' table on class_id
    $this->db->join('classes', 'teacher_permissions.class_id = classes.id');
    
    // Add a where clause to filter by class_id
    $this->db->where('classes.id', $class_id);
    
    // Execute the query
    $query = $this->db->get();
    $users = $query->result_array();

    // Append image URL to each user
    foreach ($users as &$user) {
        $image_path = 'uploads/users/' . $user['user_id'] . '.jpg'; // Assuming 'user_id' is the unique identifier for each user
        if (file_exists(FCPATH . $image_path)) {
            $user['image_url'] = base_url($image_path); // Assuming you're using CodeIgniter and 'base_url' is configured
        } else {
            $user['image_url'] = base_url('uploads/users/default.jpg'); // Default image if user image doesn't exist
        }
    }

    // Return the result as a JSON response
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($users));
}

public function assign_teacher_permission_to_class_post() {
    // Log the received POST data for debugging
    log_message('debug', 'Received POST data: ' . json_encode($this->input->post()));


    // Fetch class_id based on class_name
    $this->db->select('id');
    $this->db->from('classes');
    $this->db->where('name', $this->input->post('class_name'));
    $class_query = $this->db->get();


    $class_id = $class_query->row()->id;

    $data = [
        'teacher_id' => $this->input->post('teacher_id'),
        'class_id' => $class_id,
        'section_id' => $this->input->post('section_id') ?? null,
        'marks' => (int)$this->input->post('marks') ?? 0,
        'assignment' => (int)$this->input->post('assignment') ?? 0,
        'attendance' => (int)$this->input->post('attendance') ?? 0,
        'online_exam' => (int)$this->input->post('online_exam') ?? 0
    ];

    // Log input data for debugging
    log_message('debug', 'Assign Permission Data: ' . json_encode($data));

    // Check if the teacher permission already exists for the class and section
    $this->db->where('teacher_id', $data['teacher_id']);
    $this->db->where('class_id', $data['class_id']);
    if ($data['section_id']) {
        $this->db->where('section_id', $data['section_id']);
    } else {
        $this->db->where('section_id', null);
    }
    $query = $this->db->get('teacher_permissions');

    if ($query->num_rows() > 0) {
        // Update the existing teacher permission
        $this->db->where('teacher_id', $data['teacher_id']);
        $this->db->where('class_id', $data['class_id']);
        if ($data['section_id']) {
            $this->db->where('section_id', $data['section_id']);
        } else {
            $this->db->where('section_id', null);
        }
        $this->db->update('teacher_permissions', $data);
    } else {
        // Insert a new teacher permission
        $this->db->insert('teacher_permissions', $data);
    }

    if ($this->db->affected_rows() > 0) {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['success' => 'Permission assigned successfully']));
    } else {
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['success' => 'Permission assigned successfully']));
    }
}
public function get_class_id_by_name_get() {
    $class_name = $this->input->get('name');

    // Fetch class_id based on class_name
    $this->db->select('id');
    $this->db->from('classes');
    $this->db->where('name', $class_name);
    $class_query = $this->db->get();

    if ($class_query->num_rows() === 0) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['error' => 'Invalid class name']));
        return;
    }

    $class_id = $class_query->row()->id;

    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['class_id' => $class_id]));
}



/////////////////

//Exams Part



public function create_exam_post()
{
    // Retrieve data from POST request
    $name = $this->input->post('name');
    $starting_date = $this->input->post('starting_date');
    $ending_date = $this->input->post('ending_date');
    $school_id = $this->input->post('school_id');
    $session = $this->input->post('session') ?? 2;

    // Log the received data for debugging
    log_message('debug', 'Received data: ' . json_encode($_POST));

    // Check if the required data is provided
    if (!$name || !$starting_date || !$ending_date || !$school_id) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['status' => false, 'message' => 'Invalid input data']));
        return;
    }

    // Prepare data to insert
    $exam_data = [
        'name' => $name,
        'starting_date' => $starting_date,
        'ending_date' => $ending_date,
        'school_id' => $school_id,
        'session' => $session
    ];

    // Insert data into the exams table
    $this->db->insert('exams', $exam_data);

    // Check if the insert was successful
    if ($this->db->affected_rows() == 0) {
        $this->output
            ->set_status_header(500)
            ->set_output(json_encode(['status' => false, 'message' => 'Failed to create exam']));
        return;
    }

    // Fetch the created exam to return
    $exam_id = $this->db->insert_id();
    $query = $this->db->get_where('exams', ['id' => $exam_id]);
    $exam = $query->row_array();

    // Return success response
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => true, 'exam' => $exam]));
}

public function edit_exam_post()
{
    // Retrieve data from POST request
    $exam_id = $this->input->post('id');
    $name = $this->input->post('name');
    $starting_date = $this->input->post('starting_date');
    $ending_date = $this->input->post('ending_date');
    $school_id = $this->input->post('school_id');
    $session = $this->input->post('session') ?? 1;

    // Log the received data for debugging
    log_message('debug', 'Received data: ' . json_encode($_POST));

    // Check if the required data is provided
    if (!$exam_id || !$name || !$starting_date || !$ending_date || !$school_id) {
=======
////Sesion Manager
public function sessions_get() {
    $this->load->database();

    // Fetch all sessions from the database
    $query = $this->db->get('sessions');
    $result = $query->result_array();

    // Check if any sessions found
    if (empty($result)) {
        $this->output
            ->set_status_header(404)
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => false, 'message' => 'No sessions found']));
        return;
    }

    // Return success response with sessions data
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => true, 'sessions' => $result]));
}

public function create_session_post() {
    // Load the database
    $this->load->database();
    
    // Get the session name from the POST request
    $data = $this->input->post();

    if (!isset($data['name'])) {
        $this->output
            ->set_status_header(400)
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => false, 'message' => 'Session name is required']));
        return;
    }

    // Set the status to 0
    $data['status'] = 0;

    // Insert the new session into the database
    $this->db->insert('sessions', $data);
    $insert_id = $this->db->insert_id();

    if ($insert_id) {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => true, 'message' => 'Session created successfully', 'session_id' => $insert_id]));
    } else {
        $this->output
            ->set_status_header(500)
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => false, 'message' => 'Failed to create session']));
    }
}


public function edit_session_post() {
    // Load the database
    $this->load->database();

    // Get the session data from the POST request
    $data = $this->input->post();

    // Validation: Ensure 'id', 'name', and 'status' are provided
    if (!isset($data['id']) || !isset($data['name']) || !isset($data['status'])) {
        $this->output
            ->set_status_header(400)
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => false, 'message' => 'Session ID, name, and status are required']));
        return;
    }

    // Set updated_at to the current date and time
    $data['updated_at'] = date('Y-m-d H:i:s');

    // Update the session in the database
    $this->db->where('id', $data['id']);
    $updated = $this->db->update('sessions', $data);

    if ($updated) {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => true, 'message' => 'Session updated successfully']));
    } else {
        $this->output
            ->set_status_header(500)
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => false, 'message' => 'Failed to update session']));
    }
}


public function delete_session_delete($id) {
    // Load the database
    $this->load->database();

    // Validation: Ensure 'id' is provided
    if (!$id) {
        $this->output
            ->set_status_header(400)
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => false, 'message' => 'Invalid session ID']));
        return;
    }

    // Delete the session from the database
    $this->db->where('id', $id);
    $deleted = $this->db->delete('sessions');

    if ($deleted) {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => true, 'message' => 'Session deleted successfully']));
    } else {
        $this->output
            ->set_status_header(500)
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => false, 'message' => 'Failed to delete session']));
    }
}



////BOOKS


//Grades
public function grades_by_school_id_get($school_id, $page = 1)
{
    // Validate school_id
    if (!$school_id) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['status' => false, 'message' => 'Invalid school_id']));
        return;
    }

    // Set pagination parameters
    if ($page < 1) {
        $page = 1;
    }
    $limit = 3; // Number of grades per page
    $offset = ($page - 1) * $limit;

    // Fetch grades by school_id with pagination and optional search
    $this->db->select('*');
    $this->db->from('grades');
    $this->db->where('school_id', $school_id);
    if ($this->input->get('search')) {
        $search = $this->input->get('search');
        $this->db->like('name', $search);
        $this->db->or_like('grade_point', $search);
        $this->db->or_like('mark_from', $search);
        $this->db->or_like('mark_upto', $search);
        $this->db->or_like('comment', $search);
    }
    $this->db->limit($limit, $offset);
    $query = $this->db->get();
    $result = $query->result_array();

    // Check if any grades found
    if (empty($result)) {
        $this->output
            ->set_status_header(404)
            ->set_output(json_encode(['status' => false, 'message' => 'No grades found']));
        return;
    }

    // Fetch the total number of grades for the school
    $this->db->where('school_id', $school_id);
    if ($this->input->get('search')) {
        $search = $this->input->get('search');
        $this->db->like('name', $search);
        $this->db->or_like('grade_point', $search);
        $this->db->or_like('mark_from', $search);
        $this->db->or_like('mark_upto', $search);
        $this->db->or_like('comment', $search);
    }
    $this->db->from('grades');
    $total_grades = $this->db->count_all_results();

    // Return success response with grades and total count
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => true, 'grades' => $result, 'total' => $total_grades]));
}

public function create_grade_post()
{
    $data = $this->input->post();

    if (!isset($data['school_id']) || !isset($data['name']) || !isset($data['grade_point']) || !isset($data['mark_from']) || !isset($data['mark_upto']) || !isset($data['comment'])) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['status' => false, 'message' => 'Incomplete grade data']));
        return;
    }

    // Set session to 2 and add current date and time
    $data['session'] = 2;
    $data['created_at'] = date('Y-m-d H:i:s');
    $data['updated_at'] = date('Y-m-d H:i:s');

    $this->db->insert('grades', $data);
    $insert_id = $this->db->insert_id();

    if ($insert_id) {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => true, 'message' => 'Grade created successfully', 'grade_id' => $insert_id]));
    } else {
        $this->output
            ->set_status_header(500)
            ->set_output(json_encode(['status' => false, 'message' => 'Failed to create grade']));
    }
}

public function edit_grade_post()
{
    $data = $this->input->post();

    if (!isset($data['id']) || !isset($data['school_id']) || !isset($data['name']) || !isset($data['grade_point']) || !isset($data['mark_from']) || !isset($data['mark_upto']) || !isset($data['comment'])) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['status' => false, 'message' => 'Incomplete grade data']));
        return;
    }

    // Set updated_at to the current date and time
    $data['updated_at'] = date('Y-m-d H:i:s');

    $this->db->where('id', $data['id']);
    $updated = $this->db->update('grades', $data);

    if ($updated) {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => true, 'message' => 'Grade updated successfully']));
    } else {
        $this->output
            ->set_status_header(500)
            ->set_output(json_encode(['status' => false, 'message' => 'Failed to update grade']));
    }
}


public function delete_grade_delete($id)
{
    if (!$id) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['status' => false, 'message' => 'Invalid grade id']));
        return;
    }

    $this->db->where('id', $id);
    $deleted = $this->db->delete('grades');

    if ($deleted) {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => true, 'message' => 'Grade deleted successfully']));
    } else {
        $this->output
            ->set_status_header(500)
            ->set_output(json_encode(['status' => false, 'message' => 'Failed to delete grade']));
    }
}


////End of grades

//Departments Part


////End session manager



public function books_by_school_id_get($school_id, $page = 1)


public function create_department_post()
{
        // Retrieve data from POST request
        $name = $this->input->post('name');
        $school_id = $this->input->post('school_id');

        // Check if the required data is provided
        if (!$name || !$school_id) {
            $this->output
                ->set_status_header(400)
                ->set_output(json_encode(['status' => false, 'message' => 'Invalid input data']));
            return;
        }

        // Prepare data to insert
        $department_data = [
            'name' => $name,
            'school_id' => $school_id
        ];

        // Insert data into the departments table
        $this->db->insert('departments', $department_data);

        // Check if the insert was successful
        if ($this->db->affected_rows() == 0) {
            $this->output
                ->set_status_header(500)
                ->set_output(json_encode(['status' => false, 'message' => 'Failed to create department']));
            return;
        }

        // Fetch the created department to return
        $department_id = $this->db->insert_id();
        $query = $this->db->get_where('departments', ['id' => $department_id]);
        $department = $query->row_array();

        // Return success response
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => true, 'department' => $department]));
}

public function departments_by_school_id_get($school_id)

{
    // Validate school_id
    if (!$school_id) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['status' => false, 'message' => 'Invalid school_id']));
        return;
    }


    // Set pagination parameters
    if ($page < 1) {
        $page = 1;
    }
    $limit = 6; // Number of books per page
    $offset = ($page - 1) * $limit;

    // Fetch books by school_id with pagination
    $this->db->select('*');
    $this->db->from('books');
    $this->db->where('school_id', $school_id);

    // Get pagination parameters from GET request
    $page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
    $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 4;
    $offset = ($page - 1) * $limit;

    // Fetch departments and school name by school_id with pagination
    $this->db->select('departments.*, schools.name as school_name');
    $this->db->from('departments');
    $this->db->join('schools', 'schools.id = departments.school_id');
    $this->db->where('departments.school_id', $school_id);

    $this->db->limit($limit, $offset);
    $query = $this->db->get();
    $result = $query->result_array();


    // Check if any books found
    if (empty($result)) {
        $this->output
            ->set_status_header(404)
            ->set_output(json_encode(['status' => false, 'message' => 'No books found']));
        return;
    }

    // Fetch the total number of books for the school
    $this->db->where('school_id', $school_id);
    $this->db->from('books');
    $total_books = $this->db->count_all_results();

    // Return success response with books and total count
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => true, 'books' => $result, 'total' => $total_books]));
}

public function create_book_post()
{
    $data = $this->input->post();

    if (!isset($data['school_id']) || !isset($data['name']) || !isset($data['author']) || !isset($data['copies'])) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['status' => false, 'message' => 'Incomplete book data']));
        return;
    }

    // Set session to 2 and add current date and time
    $data['session'] = 2;
    $data['created_at'] = date('Y-m-d H:i:s');
    $data['updated_at'] = date('Y-m-d H:i:s');

    $this->db->insert('books', $data);
    $insert_id = $this->db->insert_id();

    if ($insert_id) {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => true, 'message' => 'Book created successfully', 'book_id' => $insert_id]));
    } else {
        $this->output
            ->set_status_header(500)
            ->set_output(json_encode(['status' => false, 'message' => 'Failed to create book']));
    }
}


public function edit_book_post()
{
    $data = $this->input->post();

    if (!isset($data['id']) || !isset($data['school_id']) || !isset($data['name']) || !isset($data['author']) || !isset($data['copies'])) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['status' => false, 'message' => 'Incomplete book data']));
        return;
    }

    // Set updated_at to the current date and time
    $data['updated_at'] = date('Y-m-d H:i:s');

    $this->db->where('id', $data['id']);
    $updated = $this->db->update('books', $data);

    if ($updated) {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => true, 'message' => 'Book updated successfully']));
    } else {
        $this->output
            ->set_status_header(500)
            ->set_output(json_encode(['status' => false, 'message' => 'Failed to update book']));
    }
}


public function delete_book_delete($id)
{
    if (!$id) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['status' => false, 'message' => 'Invalid book ID']));
        return;
    }

    $this->db->where('id', $id);
    $deleted = $this->db->delete('books');

    if ($deleted) {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => true, 'message' => 'Book deleted successfully']));
    } else {
        $this->output
            ->set_status_header(500)
            ->set_output(json_encode(['status' => false, 'message' => 'Failed to delete book']));
    }
}


////

    // Check if any departments found
    if (empty($result)) {
        $this->output
            ->set_status_header(404)
            ->set_output(json_encode(['status' => false, 'message' => 'No departments found']));
        return;
    }

    // Get total count of departments
    $this->db->from('departments');
    $this->db->where('school_id', $school_id);
    $total_departments = $this->db->count_all_results();

    // Return success response with departments, school name, and pagination info
    $response = [
        'status' => true,
        'departments' => $result,
        'pagination' => [
            'current_page' => $page,
            'per_page' => $limit,
            'total_pages' => ceil($total_departments / $limit),
            'total_departments' => $total_departments
        ]
    ];

    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response));
}

public function update_department_post()
{
    // Retrieve data from POST request
    $id = $this->input->post('id');
    $name = $this->input->post('name');

    // Check if the required data is provided
    if (!$id || !$name) {

        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['status' => false, 'message' => 'Invalid input data']));
        return;
    }

    // Prepare data to update

    $exam_data = [
        'name' => $name,
        'starting_date' => $starting_date,
        'ending_date' => $ending_date,
        'school_id' => $school_id,
        'session' => $session
    ];

    // Update data in the exams table
    $this->db->where('id', $exam_id);
    $this->db->update('exams', $exam_data);

    $department_data = ['name' => $name];

    // Update data in the departments table
    $this->db->where('id', $id);
    $this->db->update('departments', $department_data);


    // Check if the update was successful
    if ($this->db->affected_rows() == 0) {
        $this->output
            ->set_status_header(500)

            ->set_output(json_encode(['status' => false, 'message' => 'Failed to update exam']));
        return;
    }

    // Fetch the updated exam to return
    $query = $this->db->get_where('exams', ['id' => $exam_id]);
    $exam = $query->row_array();

            ->set_output(json_encode(['status' => false, 'message' => 'Failed to update department']));
        return;
    }

    // Fetch the updated department to return
    $query = $this->db->get_where('departments', ['id' => $id]);
    $department = $query->row_array();


    // Return success response
    $this->output
        ->set_content_type('application/json')

        ->set_output(json_encode(['status' => true, 'exam' => $exam]));
}


public function delete_exam_delete($exam_id)
{
    // Validate exam_id
    if (!$exam_id) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['status' => false, 'message' => 'Invalid exam_id']));
        return;
    }

    // Delete the exam
    $this->db->where('id', $exam_id);
    $this->db->delete('exams');
=======
        ->set_output(json_encode(['status' => true, 'department' => $department]));
}

public function delete_department_post()
{
    // Retrieve data from POST request
    $id = $this->input->post('id');

    // Check if the required data is provided
    if (!$id) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['status' => false, 'message' => 'Invalid input data']));
        return;
    }

    // Delete data from the departments table
    $this->db->where('id', $id);
    $this->db->delete('departments');


    // Check if the delete was successful
    if ($this->db->affected_rows() == 0) {
        $this->output
            ->set_status_header(500)

            ->set_output(json_encode(['status' => false, 'message' => 'Failed to delete exam']));

            ->set_output(json_encode(['status' => false, 'message' => 'Failed to delete department']));

        return;
    }

    // Return success response
    $this->output
        ->set_content_type('application/json')

        ->set_output(json_encode(['status' => true, 'message' => 'Exam deleted successfully']));
}






//end of Exams

        ->set_output(json_encode(['status' => true, 'message' => 'Department deleted successfully']));
}



//end of department
  


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
  
  public function expense_categories_get($school_id, $page = 1, $items_per_page = 10) {
      $offset = ($page - 1) * $items_per_page;
  
      // Retrieve data from the expense_categories table with pagination
      $this->db->where('school_id', $school_id);
      $this->db->limit($items_per_page, $offset);
      $query = $this->db->get('expense_categories');
  
      if ($query->num_rows() > 0) {
          $categories = $query->result_array();
  
          // Count total items for pagination
          $this->db->where('school_id', $school_id);
          $total_items = $this->db->count_all_results('expense_categories');
  
          // Return response with data and pagination info
          $response = [
              'status' => true,
              'categories' => $categories,
              'pagination' => [
                  'current_page' => $page,
                  'items_per_page' => $items_per_page,
                  'total_items' => $total_items,
                  'total_pages' => ceil($total_items / $items_per_page)
              ]
          ];
          $this->output
              ->set_content_type('application/json')
              ->set_output(json_encode($response));
      } else {
          $this->output
              ->set_status_header(404)
              ->set_output(json_encode(['status' => false, 'message' => 'No expense categories found']));
      }
  }
  
  
  public function create_expense_category_post() {
      // Retrieve data from POST request
      $name = $this->input->post('name');
      $school_id = $this->input->post('school_id');
      $session = $this->input->post('session') ? $this->input->post('session') : 1;
  
      // Log the received data for debugging
      log_message('debug', 'Received data: ' . json_encode($this->input->post()));
  
      // Check if the required data is provided
      if (!$name || !$school_id) {
          $this->output
              ->set_status_header(400)
              ->set_output(json_encode(['status' => false, 'message' => 'Invalid input data']));
          return;
      }
  
      // Prepare data to insert
      $expense_data = [
          'name' => $name,
          'school_id' => $school_id,
          'session' => $session
      ];
  
      // Insert data into the expense_categories table
      $this->db->insert('expense_categories', $expense_data);
  
      // Check if the insert was successful
      if ($this->db->affected_rows() == 0) {
          $this->output
              ->set_status_header(500)
              ->set_output(json_encode(['status' => false, 'message' => 'Failed to create expense category']));
          return;
      }
  
      // Fetch the created expense category to return
      $expense_id = $this->db->insert_id();
      $query = $this->db->get_where('expense_categories', ['id' => $expense_id]);
      $expense = $query->row_array();
  
      // Return success response
      $this->output
          ->set_content_type('application/json')
          ->set_output(json_encode(['status' => true, 'expense_category' => $expense]));
  }
  
  public function edit_expense_category_post() {
      // Retrieve data from POST request
      $id = $this->input->post('id');
      $name = $this->input->post('name');
      $school_id = $this->input->post('school_id');
      $session = $this->input->post('session') ? $this->input->post('session') : 1;
  
      // Log the received data for debugging
      log_message('debug', 'Received data: ' . json_encode($this->input->post()));
  
      // Check if the required data is provided
      if (!$id || !$name || !$school_id) {
          $this->output
              ->set_status_header(400)
              ->set_output(json_encode(['status' => false, 'message' => 'Invalid input data']));
          return;
      }
  
      // Prepare data to update
      $expense_data = [
          'name' => $name,
          'school_id' => $school_id,
          'session' => $session
      ];
  
      // Update data in the expense_categories table
      $this->db->where('id', $id);
      $this->db->update('expense_categories', $expense_data);
  
      // Check if the update was successful
      if ($this->db->affected_rows() == 0) {
          $this->output
              ->set_status_header(500)
              ->set_output(json_encode(['status' => false, 'message' => 'Failed to update expense category']));
          return;
      }
  
      // Fetch the updated expense category to return
      $query = $this->db->get_where('expense_categories', ['id' => $id]);
      $expense = $query->row_array();
  
      // Return success response
      $this->output
          ->set_content_type('application/json')
          ->set_output(json_encode(['status' => true, 'expense_category' => $expense]));
  }
  
  public function delete_expense_category_post() {
      // Retrieve data from POST request
      $id = $this->input->post('id');
  
      // Log the received data for debugging
      log_message('debug', 'Received data: ' . json_encode($this->input->post()));
  
      // Check if the required data is provided
      if (!$id) {
          $this->output
              ->set_status_header(400)
              ->set_output(json_encode(['status' => false, 'message' => 'Invalid input data']));
          return;
      }
  
      // Delete the expense category from the expense_categories table
      $this->db->delete('expense_categories', ['id' => $id]);
  
      // Check if the delete was successful
      if ($this->db->affected_rows() == 0) {
          $this->output
              ->set_status_header(500)
              ->set_output(json_encode(['status' => false, 'message' => 'Failed to delete expense category']));
          return;
      }
  
      // Return success response
      $this->output
          ->set_content_type('application/json')
          ->set_output(json_encode(['status' => true, 'message' => 'Expense category deleted successfully']));
  }
  
  public function create_expense_post() {
      // Retrieve and decode JSON data from POST request
      $input_data = json_decode(file_get_contents('php://input'), true);
  
      $expense_category_id = isset($input_data['expense_category_id']) ? $input_data['expense_category_id'] : null;
      $date = isset($input_data['date']) ? $input_data['date'] : null;
      $amount = isset($input_data['amount']) ? $input_data['amount'] : null;
      $school_id = isset($input_data['school_id']) ? $input_data['school_id'] : null;
      $session = isset($input_data['session']) ? $input_data['session'] : null;
      $created_at = isset($input_data['created_at']) ? $input_data['created_at'] : null;
      $updated_at = isset($input_data['updated_at']) ? $input_data['updated_at'] : null;
  
      // Log the received data for debugging
      log_message('debug', 'Received data: ' . json_encode($input_data));
  
      // Check if the required data is provided
      if (!$expense_category_id || !$date || !$amount || !$school_id || !$session) {
          $this->output
              ->set_status_header(400)
              ->set_output(json_encode(['status' => false, 'message' => 'Invalid input data']));
          return;
      }
  
      // Prepare data to insert
      $expense_data = [
          'expense_category_id' => $expense_category_id,
          'date' => $date,
          'amount' => $amount,
          'school_id' => $school_id,
          'session' => $session,
          'created_at' => $created_at ? $created_at : date('Y-m-d H:i:s'),
          'updated_at' => $updated_at ? $updated_at : null
      ];
  
      // Insert data into the expenses table
      $this->db->insert('expenses', $expense_data);
  
      // Check if the insert was successful
      if ($this->db->affected_rows() == 0) {
          $this->output
              ->set_status_header(500)
              ->set_output(json_encode(['status' => false, 'message' => 'Failed to create expense']));
          return;
      }
  
      // Fetch the created expense to return
      $expense_id = $this->db->insert_id();
      $query = $this->db->get_where('expenses', ['id' => $expense_id]);
      $expense = $query->row_array();
  
      // Return success response
      $this->output
          ->set_content_type('application/json')
          ->set_output(json_encode(['status' => true, 'expense' => $expense]));
  }
  
  public function get_expenses_get($school_id) {
      // Retrieve data from the expenses table based on school_id
      $query = $this->db->get_where('expenses', ['school_id' => $school_id]);
  
      if ($query->num_rows() > 0) {
          $expenses = $query->result_array();
  
          // Return success response with the expenses data
          $this->output
              ->set_content_type('application/json')
              ->set_output(json_encode(['status' => true, 'expenses' => $expenses]));
      } else {
          // Return response indicating no expenses found
          $this->output
              ->set_status_header(404)
              ->set_output(json_encode(['status' => false, 'message' => 'No expenses found']));
      }
  }
  
  public function edit_expense_post() {
      // Retrieve data from POST request
      $id = $this->input->post('id');
      $expense_category_id = $this->input->post('expense_category_id');
      $date = $this->input->post('date');
      $amount = $this->input->post('amount');
      $school_id = $this->input->post('school_id');
      $session = $this->input->post('session');
      $updated_at = $this->input->post('updated_at');
  
      // Log the received data for debugging
      log_message('debug', 'Received data: ' . json_encode($this->input->post()));
  
      // Check if the required data is provided
      if (!$id || !$expense_category_id || !$date || !$amount || !$school_id || !$session) {
          $this->output
              ->set_status_header(400)
              ->set_output(json_encode(['status' => false, 'message' => 'Invalid input data']));
          return;
      }
  
      // Prepare data to update
      $expense_data = [
          'expense_category_id' => $expense_category_id,
          'date' => $date,
          'amount' => $amount,
          'school_id' => $school_id,
          'session' => $session,
          'updated_at' => $updated_at ? $updated_at : date('Y-m-d H:i:s')
      ];
  
      // Update data in the expenses table
      $this->db->where('id', $id);
      $this->db->update('expenses', $expense_data);
  
      // Check if the update was successful
      if ($this->db->affected_rows() == 0) {
          $this->output
              ->set_status_header(500)
              ->set_output(json_encode(['status' => false, 'message' => 'Failed to update expense']));
          return;
      }
  
      // Fetch the updated expense to return
      $query = $this->db->get_where('expenses', ['id' => $id]);
      $expense = $query->row_array();
  
      // Return success response
      $this->output
          ->set_content_type('application/json')
          ->set_output(json_encode(['status' => true, 'expense' => $expense]));
  }
  
  public function delete_expense_post() {
      // Retrieve data from POST request
      $id = $this->input->post('id');
  
      // Log the received data for debugging
      log_message('debug', 'Received data: ' . json_encode($this->input->post()));
  
      // Check if the required data is provided
      if (!$id) {
          $this->output
              ->set_status_header(400)
              ->set_output(json_encode(['status' => false, 'message' => 'Invalid input data']));
          return;
      }
  
      // Delete the expense from the expenses table
      $this->db->delete('expenses', ['id' => $id]);
  
      // Check if the delete was successful
      if ($this->db->affected_rows() == 0) {
          $this->output
              ->set_status_header(500)
              ->set_output(json_encode(['status' => false, 'message' => 'Failed to delete expense']));
          return;
      }
  
      // Return success response
      $this->output
          ->set_content_type('application/json')
          ->set_output(json_encode(['status' => true, 'message' => 'Expense deleted successfully']));
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
