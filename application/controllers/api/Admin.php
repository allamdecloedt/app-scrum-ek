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
    $this->load->library('form_validation');
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
//Online Courses ///

public function change_course_status_post($course_id) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->output
             ->set_status_header(405)
             ->set_output(json_encode(['message' => 'Method not allowed']));
        return;
    }

    // Fetch the current status of the course
    $this->db->select('status');
    $this->db->from('course');
    $this->db->where('id', $course_id);
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
        $current_status = $query->row()->status;

        // Determine the new status
        $new_status = ($current_status == 'active') ? 'inactive' : 'active';

        // Update the status in the database
        $this->db->where('id', $course_id);
        $update = $this->db->update('course', ['status' => $new_status]);

        if ($update) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['message' => 'Course status updated successfully', 'new_status' => $new_status]));
        } else {
            $this->output
                 ->set_status_header(500)
                 ->set_output(json_encode(['message' => 'Failed to update course status']));
        }
    } else {
        $this->output
             ->set_status_header(404)
             ->set_output(json_encode(['message' => 'Course not found']));
    }
}


public function courses_by_class_get($class_name) {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        $this->output
             ->set_status_header(405)
             ->set_output(json_encode(['message' => 'Method not allowed']));
        return;
    }

    if ($class_name === 'All') {
        // Call a separate function to fetch all courses
        $this->get_all_courses();
    } else {
        // Fetch class ID by class name
        $this->db->select('id');
        $this->db->from('classes');
        $this->db->where('name', $class_name);
        $class_query = $this->db->get();

        if ($class_query->num_rows() > 0) {
            $class_id = $class_query->row()->id;

            // Fetch courses by class ID from the database
            $this->db->select('course.*, classes.name as class_name, users.name as instructor_name, CONCAT("http://10.0.2.2/SchoolManagementWeb/uploads/course_thumbnail/", course.thumbnail) as thumbnail');
            $this->db->from('course');
            $this->db->join('classes', 'classes.id = course.class_id');
            $this->db->join('users', 'users.id = course.user_id');
            $this->db->where('course.class_id', $class_id);
            $course_query = $this->db->get();

            if ($course_query->num_rows() > 0) {
                $courses = $course_query->result_array();
                $this->output
                     ->set_content_type('application/json')
                     ->set_output(json_encode($courses));
            } else {
                $this->output
                     ->set_status_header(404)
                     ->set_output(json_encode(['message' => 'No courses found for this class']));
            }
        } else {
            $this->output
                 ->set_status_header(404)
                 ->set_output(json_encode(['message' => 'Class not found']));
        }
    }
}


public function courses_by_user_get($username) {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        $this->output
             ->set_status_header(405)
             ->set_output(json_encode(['message' => 'Method not allowed']));
        return;
    }

    if ($username === 'All') {
        // Call a separate function to fetch all courses
        $this->get_all_courses();
    } else {
        // Fetch user ID by username
        $this->db->select('id');
        $this->db->from('users');
        $this->db->where('name', $username);
        $user_query = $this->db->get();

        if ($user_query->num_rows() > 0) {
            $user_id = $user_query->row()->id;

            // Fetch courses by user ID from the database
            $this->db->select('course.*, classes.name as class_name, users.name as instructor_name, CONCAT("http://10.0.2.2/SchoolManagementWeb/uploads/course_thumbnail/", course.thumbnail) as thumbnail');
            $this->db->from('course');
            $this->db->join('classes', 'classes.id = course.class_id');
            $this->db->join('users', 'users.id = course.user_id');
            $this->db->where('course.user_id', $user_id);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $courses = $query->result_array();
                $this->output
                     ->set_content_type('application/json')
                     ->set_output(json_encode($courses));
            } else {
                $this->output
                     ->set_status_header(404)
                     ->set_output(json_encode(['message' => 'No courses found for this user']));
            }
        } else {
            $this->output
                 ->set_status_header(404)
                 ->set_output(json_encode(['message' => 'User not found']));
        }
    }
}



public function courses_by_status_get($status) {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        $this->output
             ->set_status_header(405)
             ->set_output(json_encode(['message' => 'Method not allowed']));
        return;
    }

    if ($status === 'All') {
        // Call a separate function to fetch all courses
        $this->get_all_courses();
    } else {
        // Fetch courses by status from the database
        $this->db->select('course.*, classes.name as class_name, users.name as instructor_name, CONCAT("http://10.0.2.2/SchoolManagementWeb/uploads/course_thumbnail/", course.thumbnail) as thumbnail');
        $this->db->from('course');
        $this->db->join('classes', 'classes.id = course.class_id');
        $this->db->join('users', 'users.id = course.user_id');
        $this->db->where('course.status', $status);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $courses = $query->result_array();
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode($courses));
        } else {
            $this->output
                 ->set_status_header(404)
                 ->set_output(json_encode(['message' => 'No courses found with this status']));
        }
    }
}



public function all_courses_get() {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        $this->output
             ->set_status_header(405)
             ->set_output(json_encode(['message' => 'Method not allowed']));
        return;
    }

    $this->db->select('course.*, classes.name as class_name, users.name as instructor_name, CONCAT("http://10.0.2.2/SchoolManagementWeb/uploads/course_thumbnail/", course.thumbnail) as thumbnail');
    $this->db->from('course');
    $this->db->join('classes', 'classes.id = course.class_id');
    $this->db->join('users', 'users.id = course.user_id');
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
        $courses = $query->result_array();
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($courses));
    } else {
        $this->output
             ->set_status_header(404)
             ->set_output(json_encode(['message' => 'No courses found']));
    }
}


public function get_all_courses() {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        $this->output
             ->set_status_header(405)
             ->set_output(json_encode(['message' => 'Method not allowed']));
        return;
    }

    $this->db->select('course.*, classes.name as class_name, users.name as instructor_name');
    $this->db->from('course');
    $this->db->join('classes', 'classes.id = course.class_id');
    $this->db->join('users', 'users.id = course.user_id');
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
        $courses = $query->result_array();
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($courses));
    } else {
        $this->output
             ->set_status_header(404)
             ->set_output(json_encode(['message' => 'No courses found']));
    }
}

    
public function lessons_and_sections_get($course_id)
{
    // Get total lessons
    $this->db->where('course_id', $course_id);
    $this->db->from('lesson');
    $total_lessons = $this->db->count_all_results();
    $this->db->reset_query();  // Reset query to ensure it doesn't affect the next one

    // Get total sections
    $this->db->where('course_id', $course_id);
    $this->db->from('course_section');
    $total_sections = $this->db->count_all_results();

    // Prepare the result array
    $result = array(
        'total_lessons' => $total_lessons,
        'total_sections' => $total_sections
    );

    // Output the result as JSON
    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode($result));
}



public function all_teacher_names_get() {
    // Ensure the request method is GET
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        $this->output
             ->set_status_header(405)
             ->set_output(json_encode(['message' => 'Method not allowed']));
        return;
    }

    // Fetch all teacher names from the 'users' table where the role is 'teacher'
    $this->db->select('name');
    $this->db->where('role', 'teacher');
    $query = $this->db->get('users');
    $teachers = $query->result_array();

    if (!empty($teachers)) {
        // Return the result as a JSON response
        $this->output
             ->set_status_header(200)
             ->set_content_type('application/json')
             ->set_output(json_encode($teachers));
    } else {
        // Return a 404 status if no teachers are found
        $this->output
             ->set_status_header(404)
             ->set_output(json_encode(['message' => 'No teachers found']));
    }
}



 public function course_details_get($course_id) {
    // Ensure the request method is GET
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        $this->output
            ->set_status_header(405)
            ->set_output(json_encode(['message' => 'Method not allowed']));
        return;
    }

    // Fetch the course details from the database
    $this->db->select('id, title, description, class_id, user_id, subject_id, outcomes, course_overview_provider, course_overview_url, CONCAT("http://10.0.2.2/SchoolManagementWeb/uploads/course_thumbnail/", thumbnail) as thumbnail');
    $this->db->where('id', $course_id);
    $query = $this->db->get('course'); // Assuming 'course' is your table name

    if ($query->num_rows() > 0) {
        $course = $query->row_array();

        // Get class name
        $class_id = $course['class_id'];
        $this->db->select('name');
        $this->db->where('id', $class_id);
        $class_query = $this->db->get('classes');
        $course['class_id'] = ($class_query->num_rows() > 0) ? $class_query->row()->name : 'Unknown Class';

        // Get user name
        $user_id = $course['user_id'];
        $this->db->select('name');
        $this->db->where('id', $user_id);
        $user_query = $this->db->get('users');
        $course['user_id'] = ($user_query->num_rows() > 0) ? $user_query->row()->name : 'Unknown User';

        // Get subject name
        $subject_id = $course['subject_id'];
        $this->db->select('name');
        $this->db->where('id', $subject_id);
        $subject_query = $this->db->get('subjects');
        $course['subject_id'] = ($subject_query->num_rows() > 0) ? $subject_query->row()->name : 'Unknown Subject';

        $this->output
            ->set_status_header(200)
            ->set_output(json_encode($course));
    } else {
        $this->output
            ->set_status_header(404)
            ->set_output(json_encode(['message' => 'Course not found']));
    }
}

public function edit_course_post($course_id) {
    // Ensure the request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->output
            ->set_status_header(405)
            ->set_output(json_encode(['message' => 'Method not allowed']));
        return;
    }

    // Fetch input data from request
    $input_data = $this->input->post();
    log_message('debug', 'Input Data: ' . print_r($input_data, true));

    if (empty($input_data)) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['message' => 'Invalid input data']));
        return;
    }

    // Ensure all expected keys are present
    $expected_keys = ['title', 'class_id', 'user_id', 'subject_id', 'description', 'outcomes', 'course_overview_provider', 'course_overview_url', 'school_id'];
    foreach ($expected_keys as $key) {
        if (!array_key_exists($key, $input_data)) {
            log_message('error', "Missing field: $key");
            $this->output
                ->set_status_header(400)
                ->set_output(json_encode(['message' => "Missing field: $key"]));
            return;
        }
    }

    // Validate input data
    $this->form_validation->set_data($input_data);
    $this->form_validation->set_rules('title', 'Title', 'required');
    $this->form_validation->set_rules('class_id', 'Class ID', 'required');
    $this->form_validation->set_rules('user_id', 'User ID', 'required');
    $this->form_validation->set_rules('subject_id', 'Subject ID', 'required');
    $this->form_validation->set_rules('description', 'Description', 'required');
    $this->form_validation->set_rules('outcomes', 'Outcomes', 'required');
    $this->form_validation->set_rules('course_overview_provider', 'Course Overview Provider', 'required');
    $this->form_validation->set_rules('course_overview_url', 'Course Overview URL', 'required|valid_url');
    $this->form_validation->set_rules('school_id', 'School ID', 'required');

    if ($this->form_validation->run() == FALSE) {
        log_message('error', 'Validation Errors: ' . validation_errors());
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['message' => validation_errors()]));
        return;
    }

    // Fetch the class_id, user_id, and subject_id from the database based on the names
    $class_id = $this->get_class_id_by_name($input_data['class_id']);
    $user_id = $this->get_user_id_by_name($input_data['user_id']);
    $subject_id = $this->get_subject_id_by_name($input_data['subject_id']);
    $school_id = $input_data['school_id'];

    log_message('debug', 'Fetched class_id: ' . $class_id);
    log_message('debug', 'Fetched user_id: ' . $user_id);
    log_message('debug', 'Fetched subject_id: ' . $subject_id);
    log_message('debug', 'School ID: ' . $school_id);

    if (!$class_id || !$user_id || !$school_id || !$subject_id) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['message' => 'Invalid class, user, school, or subject name']));
        return;
    }

    // Prepare data for updating
    $data = [
        'title' => $input_data['title'],
        'class_id' => $class_id,
        'user_id' => $user_id,
        'subject_id' => $subject_id,
        'description' => $input_data['description'],
        'outcomes' => $input_data['outcomes'],
        'course_overview_provider' => $input_data['course_overview_provider'],
        'course_overview_url' => $input_data['course_overview_url'],
        'school_id' => $school_id
    ];

    log_message('debug', 'Data to be updated: ' . print_r($data, true));

    try {
        // Start transaction
        $this->db->trans_begin();

        // Update data in the database
        $this->db->where('id', $course_id);
        $this->db->update('course', $data);

        // Check if the course was updated successfully
        if ($this->db->affected_rows() > 0) {
            log_message('debug', 'Course updated in database');

            // Handle file upload for thumbnail if provided
            if (isset($_FILES['course_thumbnail']) && $_FILES['course_thumbnail']['error'] == UPLOAD_ERR_OK) {
                $upload_path = 'uploads/course_thumbnail/' . $course_id . '.jpg';
                if (!move_uploaded_file($_FILES['course_thumbnail']['tmp_name'], $upload_path)) {
                    throw new Exception('File upload failed');
                }
                log_message('debug', 'File uploaded to: ' . $upload_path);
            } else {
                log_message('debug', 'No file uploaded or file upload error');
            }

            // Commit transaction
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                log_message('error', 'Transaction failed');
                throw new Exception('Transaction failed');
            } else {
                $this->db->trans_commit();
                log_message('debug', 'Transaction committed successfully');
                $this->output
                    ->set_status_header(200)
                    ->set_output(json_encode(['message' => 'Course updated successfully', 'course_id' => $course_id]));
            }
        } else {
            $this->db->trans_rollback();
            log_message('error', 'Failed to update course');
            throw new Exception('Failed to update course');
        }
    } catch (Exception $e) {
        // Log the exception
        log_message('error', 'Exception: ' . $e->getMessage());
        $this->output
            ->set_status_header(500)
            ->set_output(json_encode(['message' => $e->getMessage()]));
    }
}




 public function create_course_post() {
    // Ensure the request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->output
            ->set_status_header(405)
            ->set_output(json_encode(['message' => 'Method not allowed']));
        return;
    }

    // Fetch input data from request
    $input_data = $this->input->post();

    if (empty($input_data)) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['message' => 'Invalid input data']));
        return;
    }

    // Log the input data for debugging
    log_message('debug', 'Input Data: ' . print_r($input_data, true));

    // Validate input data
    $this->form_validation->set_data($input_data);
    $this->form_validation->set_rules('title', 'Title', 'required');
    $this->form_validation->set_rules('class_id', 'Class ID', 'required');
    $this->form_validation->set_rules('user_id', 'User ID', 'required');
    $this->form_validation->set_rules('subject_id', 'Subject ID', 'required');
    $this->form_validation->set_rules('description', 'Description', 'required');
    $this->form_validation->set_rules('outcomes', 'Outcomes', 'required');
    $this->form_validation->set_rules('course_overview_provider', 'Course Overview Provider', 'required');
    $this->form_validation->set_rules('course_overview_url', 'Course Overview URL', 'required|valid_url');
    $this->form_validation->set_rules('school_id', 'School ID', 'required');

    if ($this->form_validation->run() == FALSE) {
        // Log validation errors
        log_message('error', 'Validation Errors: ' . validation_errors());

        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['message' => validation_errors()]));
        return;
    }

    // Fetch the class_id, user_id, and subject_id from the database based on the names
    $class_id = $this->get_class_id_by_name($input_data['class_id']);
    $user_id = $this->get_user_id_by_name($input_data['user_id']);
    $subject_id = $this->get_subject_id_by_name($input_data['subject_id']);
    $school_id = $input_data['school_id']; // Directly use school_id from input data

    if (!$class_id || !$user_id || !$school_id || !$subject_id) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['message' => 'Invalid class, user, school, or subject name']));
        return;
    }

    // Prepare data for insertion
    $data = [
        'title' => $input_data['title'],
        'class_id' => $class_id,
        'user_id' => $user_id,
        'subject_id' => $subject_id,
        'description' => $input_data['description'],
        'outcomes' => $input_data['outcomes'],
        'course_overview_provider' => $input_data['course_overview_provider'],
        'course_overview_url' => $input_data['course_overview_url'],
        'thumbnail' => rand() . '.jpg',
        'status' => 'active',
        'date_added' => strtotime(date('d M Y')),
        'school_id' => $school_id
    ];

    try {
        // Log the data to be inserted
        log_message('debug', 'Data to be inserted: ' . print_r($data, true));

        // Start transaction
        $this->db->trans_begin();

        // Insert data into the database
        $this->db->insert('course', $data);
        $insert_id = $this->db->insert_id();

        // Check if the course was created successfully
        if ($insert_id) {
            // Handle file upload for thumbnail
            if (isset($_FILES['course_thumbnail']['tmp_name'])) {
                $upload_path = 'uploads/course_thumbnail/' . $data['thumbnail'];
                if (!move_uploaded_file($_FILES['course_thumbnail']['tmp_name'], $upload_path)) {
                    throw new Exception('File upload failed');
                }
            }

            // Commit transaction
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                throw new Exception('Transaction failed');
            } else {
                $this->db->trans_commit();
                $this->output
                    ->set_status_header(201)
                    ->set_output(json_encode(['message' => 'Course created successfully', 'course_id' => $insert_id]));
            }
        } else {
            $this->db->trans_rollback();
            throw new Exception('Failed to create course');
        }
    } catch (Exception $e) {
        // Log the exception
        log_message('error', 'Exception: ' . $e->getMessage());

        $this->output
            ->set_status_header(500)
            ->set_output(json_encode(['message' => $e->getMessage()]));
    }
}

private function get_class_id_by_name($name) {
    $this->db->select('id');
    $this->db->from('classes');
    $this->db->where('name', $name);
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
        return $query->row()->id;
    }
    return false;
}

private function get_user_id_by_name($name) {
    $this->db->select('id');
    $this->db->from('users');
    $this->db->where('name', $name);
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
        return $query->row()->id;
    }
    return false;
}

private function get_subject_id_by_name($name) {
    $this->db->select('id');
    $this->db->from('subjects');
    $this->db->where('name', $name);
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
        return $query->row()->id;
    }
    return false;
}


public function subjects_names_get() {
    // Ensure the request method is GET
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
      $this->output
           ->set_status_header(405)
           ->set_output(json_encode(['message' => 'Method not allowed']));
      return;
    }

    // Fetch all subject names from the 'subjects' table
    $this->db->select('name');
    $query = $this->db->get('subjects');
    $subjects = $query->result_array();

    if (!empty($subjects)) {
      $this->output
           ->set_status_header(200)
           ->set_content_type('application/json')
           ->set_output(json_encode($subjects));
    } else {
      $this->output
           ->set_status_header(404)
           ->set_output(json_encode(['message' => 'No subjects found']));
    }
  }

  public function course_status_counts_get() {
    // Vérifie que la méthode de requête est GET
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
      $this->output
           ->set_status_header(405)
           ->set_output(json_encode(['message' => 'Method not allowed']));
      return;
    }

    // Compter les cours avec le statut "active"
    $this->db->where('status', 'active');
    $this->db->from('course');
    $active_count = $this->db->count_all_results();

    // Compter les cours avec le statut "inactive"
    $this->db->where('status', 'inactive');
    $this->db->from('course');
    $inactive_count = $this->db->count_all_results();

    // Retourner les résultats en JSON
    $status_counts = [
      'active' => $active_count,
      'inactive' => $inactive_count
    ];

    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode($status_counts));
  }




  ////////
















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

/* public function add_quiz_post() {
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
} */

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

/* public function get_quiz_questions_get($quiz_id) {
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
} */

/* public function get_quiz_questions_get($quiz_id) {
    if ($this->input->server('REQUEST_METHOD') == 'GET') {
        $this->db->order_by("order", "asc");
        $this->db->where('quiz_id', $quiz_id);
        $query = $this->db->get('question');
        $questions = $query->result_array();

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
        $this->output
            ->set_content_type('application/json')
            ->set_status_header(405)
            ->set_output(json_encode(['status' => 'error', 'message' => 'Invalid request method']));
    }
}
 */

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


  public function create_invoice_post() {
    $student_id = $this->input->post('student_id');
    $class_id = $this->input->post('class_id');
    $total_amount = $this->input->post('total_amount');
    $paid_amount = $this->input->post('paid_amount');
    $session = $this->input->post('session');
    $status = 'unpaid'; // Set status to 'unpaid'
    $payment_method = $this->input->post('payment_method'); // Get payment method
    $school_id = $this->input->post('school_id'); // Get school_id
    $user_name = $this->input->post('user_name'); // Get user name
    $created_at = $this->input->post('created_at'); // Get created_at

    log_message('debug', 'createInvoice_post called with data: ' . json_encode($this->input->post()));

    if (!$student_id || !$class_id || !$total_amount || !$paid_amount || !$session || !$payment_method || !$school_id || !$user_name || !$created_at) {
        log_message('error', 'Invalid input data: one or more fields are missing');
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['status' => false, 'message' => 'Invalid input data']));
        return;
    }

    // Add logging to see the original created_at value
    log_message('debug', 'Original created_at: ' . $created_at);

    // Convert the date to the correct format
    $created_at_formatted = DateTime::createFromFormat('d-m-Y H:i:s', $created_at);
    if ($created_at_formatted === false) {
        log_message('error', 'Invalid date format');
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['status' => false, 'message' => 'Invalid date format']));
        return;
    }
    $created_at_formatted = $created_at_formatted->format('Y-m-d H:i:s');

    // Add logging to see the formatted created_at value
    log_message('debug', 'Formatted created_at: ' . $created_at_formatted);

    $invoice_data = [
        'title' => 'Invoice for ' . $user_name,
        'total_amount' => $total_amount,
        'class_id' => $class_id,
        'student_id' => $student_id,
        'paid_amount' => $paid_amount,
        'status' => $status, // Use the 'unpaid' status
        'payment_method' => $payment_method, // Add payment method
        'school_id' => $school_id, // Use the provided school_id
        'session' => $session,
        'created_at' => $created_at_formatted, // Use the formatted date
        'updated_at' => $created_at_formatted, // Use the formatted date
    ];

    // Insert the invoice data into the database
    $this->db->insert('invoices', $invoice_data);

    if ($this->db->affected_rows() == 0) {
        log_message('error', 'Failed to insert invoice data');
        $this->output
            ->set_status_header(500)
            ->set_output(json_encode(['status' => false, 'message' => 'Failed to insert invoice data']));
        return;
    }

    // Get the newly inserted invoice ID
    $invoice_id = $this->db->insert_id();

    log_message('debug', 'Invoice created successfully with ID: ' . $invoice_id);

    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => true, 'invoice_id' => $invoice_id]));
}
  





public function paid_invoice_post() {
    // Retrieve POST data
    $input_data = json_decode(file_get_contents('php://input'), true);
    $invoice_id = isset($input_data['invoice_id']) ? $input_data['invoice_id'] : null;
    $payment_method = isset($input_data['payment_method']) ? $input_data['payment_method'] : null;

    // Log the received data for debugging purposes
    log_message('debug', 'paid_invoice_post called with data: ' . json_encode($input_data));

    // Validate the input data
    if (empty($invoice_id) || empty($payment_method)) {
        log_message('error', 'Invalid input data: one or more fields are missing');
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['status' => false, 'message' => 'Invalid input data']));
        return;
    }

    // Prepare the update data
    $update_data = [
        'payment_method' => $payment_method,
        'status' => 'paid',
        'updated_at' => date('Y-m-d H:i:s')
    ];

    // Log the update data for debugging
    log_message('debug', 'Update data: ' . json_encode($update_data));

    // Update the invoice data in the database
    $this->db->where('id', $invoice_id);
    $this->db->update('invoices', $update_data);

    // Log the database query
    log_message('debug', 'Executed query: ' . $this->db->last_query());

    // Check if the update was successful
    if ($this->db->affected_rows() == 0) {
        log_message('error', 'Failed to update invoice data');
        $this->output
            ->set_status_header(500)
            ->set_output(json_encode(['status' => false, 'message' => 'Failed to update invoice data']));
        return;
    }

    // Fetch the updated invoice to return
    $this->db->select('invoices.*, users.name as student_name, classes.name as class_name');
    $this->db->from('invoices');
    $this->db->join('students', 'invoices.student_id = students.id', 'left');
    $this->db->join('users', 'students.user_id = users.id', 'left');
    $this->db->join('classes', 'invoices.class_id = classes.id', 'left');
    $this->db->where('invoices.id', $invoice_id);
    $query = $this->db->get();

    // Log the database query for fetching the updated invoice
    log_message('debug', 'Fetch updated invoice query: ' . $this->db->last_query());

    // Get the invoice data
    $invoice = $query->row_array();

    // Log the updated invoice data
    log_message('debug', 'Updated invoice: ' . json_encode($invoice));

    // Return the response
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => true, 'invoice' => $invoice]));
}


public function user_name_by_student_id_get($student_id) {
    if (!$student_id) {
        log_message('error', 'Invalid input data: student_id is missing');
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['status' => false, 'message' => 'Invalid student ID']));
        return;
    }

    $this->db->select('users.name as user_name');
    $this->db->from('students');
    $this->db->join('users', 'students.user_id = users.id', 'left');
    $this->db->where('students.id', $student_id);
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
        $result = $query->row_array();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => true, 'user_name' => $result['user_name']]));
    } else {
        $this->output
            ->set_status_header(404)
            ->set_output(json_encode(['status' => false, 'message' => 'User not found']));
    }
} 


public function invoice_status_get() {
    $student_id = $this->input->get('student_id');
    $class_id = $this->input->get('class_id');

    if (!$student_id || !$class_id) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['status' => false, 'message' => 'Invalid input data']));
        return;
    }

    // Query to get the status of invoices
    $this->db->select('status');
    $this->db->from('invoices');
    $this->db->where('student_id', $student_id);
    $this->db->where('class_id', $class_id);
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
        $invoices = $query->result_array();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => true, 'invoices' => $invoices]));
    } else {
        $this->output
            ->set_status_header(404)
            ->set_output(json_encode(['status' => false, 'message' => 'No invoices found']));
    }
}

public function payment_settings_get($school_id)
{
    // Fetch payment settings
    $this->db->where('school_id', $school_id);
    $payment_query = $this->db->get('payment_settings');
    $payment_settings = $payment_query->result_array();

    // Fetch system currency settings
    $this->db->select('system_currency, currency_position');
    $this->db->where('school_id', $school_id);
    $currency_query = $this->db->get('settings');
    $system_currency = $currency_query->row_array();

    // Combine the results
    $response = array(
        'status' => true,
        'data' => array(
            'payment_settings' => $payment_settings,
            'system_currency' => $system_currency
        )
    );

    // Return the response as JSON
    echo json_encode($response);
}

public function class_create_post() {
    // Get input data
    $data = json_decode($this->input->raw_input_stream, true);

    // Validate input data
    if (!isset($data['name']) || !isset($data['school_id'])) {
        $response = array('status' => false, 'message' => 'Missing required fields: name or school_id');
        echo json_encode($response);
        return;
    }

    // Prepare data for insertion
    $class_data = array(
        'name' => $data['name'],
        'school_id' => $data['school_id'],
        'price' => isset($data['price']) ? $data['price'] : NULL
    );

    // Insert data directly in the controller
    $this->db->insert('classes', $class_data);
    $insert_id = $this->db->insert_id();

    // Prepare response
    if ($insert_id) {
        $response = array('status' => true, 'message' => 'Class created successfully', 'class_id' => $insert_id);
    } else {
        $response = array('status' => false, 'message' => 'Failed to create class');
    }

    // Output response
    echo json_encode($response);
}

public function class_update_put() {
    // Get input data
    $data = json_decode($this->input->raw_input_stream, true);

    // Validate input data
    if (!isset($data['id']) || !isset($data['name']) || !isset($data['school_id'])) {
        $response = array('status' => false, 'message' => 'Missing required fields: id, name or school_id');
        echo json_encode($response);
        return;
    }

    // Prepare data for update
    $class_data = array(
        'name' => $data['name'],
        'school_id' => $data['school_id'],
        'price' => isset($data['price']) ? $data['price'] : NULL
    );

    // Update class
    $this->db->where('id', $data['id']);
    $update = $this->db->update('classes', $class_data);

    // Prepare response
    if ($update) {
        $response = array('status' => true, 'message' => 'Class updated successfully');
    } else {
        $response = array('status' => false, 'message' => 'Failed to update class');
    }

    // Output response
    echo json_encode($response);
}

public function class_del_delete() {
    // Get input data
    $data = json_decode($this->input->raw_input_stream, true);

    // Validate input data
    if (!isset($data['id'])) {
        $response = array('status' => false, 'message' => 'Missing required field: id');
        echo json_encode($response);
        return;
    }

    // Delete class
    $this->db->where('id', $data['id']);
    $delete = $this->db->delete('classes');

    // Prepare response
    if ($delete) {
        $response = array('status' => true, 'message' => 'Class deleted successfully');
    } else {
        $response = array('status' => false, 'message' => 'Failed to delete class');
    }

    // Output response
    echo json_encode($response);
}


public function class_get() {
    // Get pagination parameters from the request
    $limit = $this->input->get('limit') ? intval($this->input->get('limit')) : 8;
    $offset = $this->input->get('offset') ? intval($this->input->get('offset')) : 0;

    // Get all classes with the corresponding school name, applying limit and offset for pagination
    $this->db->select('classes.*, schools.name as school_name');
    $this->db->from('classes');
    $this->db->join('schools', 'schools.id = classes.school_id');
    $this->db->limit($limit, $offset);
    $query = $this->db->get();
    $classes = $query->result_array();

    // Get sections assigned to each class
    foreach ($classes as &$class) {
        $this->db->where('class_id', $class['id']);
        $section_query = $this->db->get('sections');
        $sections = $section_query->result_array();
        $class['sections'] = $sections;
    }

    // Get the total number of classes for pagination purposes
    $this->db->from('classes');
    $total_classes = $this->db->count_all_results();

    // Prepare response
    $response = array(
        'status' => true,
        'data' => $classes,
        'total' => $total_classes,
        'limit' => $limit,
        'offset' => $offset
    );

    // Output response
    echo json_encode($response);
}

public function submit_quiz_responses_post() {
    // Get input data
    $data = json_decode($this->input->raw_input_stream, true);

    if (!isset($data['quiz_id']) || !isset($data['responses'])) {
        echo json_encode(array('status' => false, 'message' => 'Missing required fields: quiz_id or responses'));
        return;
    }

    $quiz_id = $data['quiz_id'];
    $responses = $data['responses'];

    // Get correct answers for the given quiz_id
    $this->db->where('quiz_id', $quiz_id);
    $query = $this->db->get('question');
    $questions = $query->result_array();

    $correct_answers = array();
    foreach ($questions as $question) {
        $correct_answers[$question['id']] = json_decode($question['correct_answers']);
    }

    // Calculate result
    $total_questions = count($questions);
    $correct_responses = 0;

    foreach ($responses as $question_id => $response) {
        if (isset($correct_answers[$question_id])) {
            $correct_answer = $correct_answers[$question_id];
            // Convert both to strings for comparison
            if (json_encode($correct_answer) === json_encode($response)) {
                $correct_responses++;
            }
        }
    }

    $result = array(
        'total_questions' => $total_questions,
        'correct_responses' => $correct_responses,
        'score' => ($correct_responses / $total_questions) * 100
    );

    // Output response
    echo json_encode(array('status' => true, 'result' => $result));
}

public function all_quiz_responses_get($quiz_id) {
    if (empty($quiz_id)) {
        echo json_encode(array('status' => false, 'message' => 'Missing required field: quiz_id'));
        return;
    }

    // Get all questions and their correct answers for the given quiz_id
    $this->db->where('quiz_id', $quiz_id);
    $query = $this->db->get('question');
    $questions = $query->result_array();

    if (empty($questions)) {
        echo json_encode(array('status' => false, 'message' => 'No questions found for the given quiz_id'));
        return;
    }

    // Prepare responses array
    $responses = array();
    foreach ($questions as $question) {
        $responses[] = array(
            'question_id' => $question['id'],
            'question' => $question['title'],
            'correct_answers' => json_decode($question['correct_answers'], true)
        );
    }

    // Output response
    echo json_encode(array('status' => true, 'responses' => $responses));
}


public function create_subject_post() {
    // Ensure the request method is POST
    if ($this->input->server('REQUEST_METHOD') !== 'POST') {
        $this->output
             ->set_status_header(405)
             ->set_output(json_encode(['message' => 'Method not allowed']));
        return;
    }

    // Retrieve input data
    $input = json_decode(trim(file_get_contents('php://input')), true);

    $name = $input['name'] ?? null;
    $class_id = $input['class_id'] ?? null;
    $school_id = $input['school_id'] ?? null;

    // Validate the input
    if (empty($name) || empty($class_id) || empty($school_id)) {
        $this->output
             ->set_status_header(400)
             ->set_output(json_encode(['message' => 'Name, Class ID, and School ID are required']));
        return;
    }

    // Prepare data for insertion
    $data = [
        'name' => $name,
        'class_id' => $class_id,
        'school_id' => $school_id,
        'session' => 1 // Default session
    ];

    // Insert the new subject
    $this->db->insert('subjects', $data);

    if ($this->db->affected_rows() > 0) {
        $this->output
             ->set_status_header(201)
             ->set_content_type('application/json')
             ->set_output(json_encode(['status' => 'success', 'message' => 'Subject created successfully']));
    } else {
        $this->output
             ->set_status_header(500)
             ->set_content_type('application/json')
             ->set_output(json_encode(['status' => 'error', 'message' => 'Failed to create subject']));
    }
}

public function subjects_by_class_id_get($class_id) {
    // Validate class_id
    if (!$class_id) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['status' => false, 'message' => 'Invalid class_id']));
        return;
    }

    // Fetch subjects by class_id
    $this->db->select('subjects.*, classes.name as class_name, schools.name as school_name');
    $this->db->from('subjects');
    $this->db->join('classes', 'classes.id = subjects.class_id');
    $this->db->join('schools', 'schools.id = subjects.school_id');
    $this->db->where('subjects.class_id', $class_id);
    $query = $this->db->get();
    $result = $query->result_array();

    // Check if any subjects found
    if (empty($result)) {
        $this->output
            ->set_status_header(404)
            ->set_output(json_encode(['status' => false, 'message' => 'No subjects found']));
        return;
    }

    // Return success response with subjects
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => true, 'subjects' => $result]));
}


public function get_classes_by_school_id_get($school_id) {
    // Ensure the request method is GET
    if ($this->input->server('REQUEST_METHOD') !== 'GET') {
        $this->output
             ->set_status_header(405)
             ->set_output(json_encode(['message' => 'Method not allowed']));
        return;
    }

    // Validate the input
    if (empty($school_id)) {
        $this->output
             ->set_status_header(400)
             ->set_output(json_encode(['message' => 'School ID is required']));
        return;
    }

    // Fetch classes by school_id
    $this->db->where('school_id', $school_id);
    $query = $this->db->get('classes');
    $classes = $query->result_array();

    // Check if any classes are found
    if (!empty($classes)) {
        $this->output
             ->set_status_header(200)
             ->set_content_type('application/json')
             ->set_output(json_encode(['status' => 'success', 'classes' => $classes]));
    } else {
        $this->output
             ->set_status_header(404)
             ->set_output(json_encode(['status' => 'error', 'message' => 'No classes found for the given school ID']));
    }
}

public function get_subjects_by_school_id_get($school_id) {
    // Ensure the request method is GET
    if ($this->input->server('REQUEST_METHOD') !== 'GET') {
        $this->output
             ->set_status_header(405)
             ->set_output(json_encode(['message' => 'Method not allowed']));
        return;
    }

    // Validate the input
    if (empty($school_id)) {
        $this->output
             ->set_status_header(400)
             ->set_output(json_encode(['message' => 'School ID is required']));
        return;
    }

    // Retrieve limit and offset from GET parameters
    $limit = $this->input->get('limit') ?? 8; // Default limit to 8 if not provided
    $offset = $this->input->get('offset') ?? 0; // Default offset to 0 if not provided

    // Fetch subjects by school_id with limit and offset
    $this->db->select('subjects.id, subjects.name as subject_name, classes.name as class_name');
    $this->db->from('subjects');
    $this->db->join('classes', 'subjects.class_id = classes.id');
    $this->db->where('subjects.school_id', $school_id);
    $this->db->limit($limit, $offset);
    $query = $this->db->get();
    $subjects = $query->result_array();

    // Check if any subjects are found
    if (!empty($subjects)) {
        // Fetch total count of subjects for the given school_id
        $this->db->where('school_id', $school_id);
        $total_query = $this->db->get('subjects');
        $total_count = $total_query->num_rows();

        $this->output
             ->set_status_header(200)
             ->set_content_type('application/json')
             ->set_output(json_encode([
                 'status' => 'success',
                 'total_count' => $total_count,
                 'subjects' => $subjects
             ]));
    } else {
        $this->output
             ->set_status_header(404)
             ->set_output(json_encode(['status' => 'error', 'message' => 'No subjects found for the given school ID']));
    }
}


//dropdowns//
public function schools_for_students_get() {
    // Fetch all schools
    $this->db->select('id, name, address, phone, status');
    $this->db->from('schools');
    $query = $this->db->get();
    $schools = $query->result_array();

    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode(['status' => 'success', 'schools' => $schools]));
}

public function classes_for_students_get() {
    // Fetch all classes
    $this->db->select('id, name, school_id, price');
    $this->db->from('classes');
    $query = $this->db->get();
    $classes = $query->result_array();

    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode(['status' => 'success', 'classes' => $classes]));
}

public function instructors_for_students_get() {
    // Fetch all instructors
    $this->db->select('id, name, email, phone');
    $this->db->from('instructors');
    $query = $this->db->get();
    $instructors = $query->result_array();

    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode(['status' => 'success', 'instructors' => $instructors]));
}

public function subjects_for_students_get() {
    // Fetch all subjects
    $this->db->select('id, name, class_id, school_id, session');
    $this->db->from('subjects');
    $query = $this->db->get();
    $subjects = $query->result_array();

    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode(['status' => 'success', 'subjects' => $subjects]));
}

//dropdowns//

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
    $department_data = ['name' => $name];

    // Update data in the departments table
    $this->db->where('id', $id);
    $this->db->update('departments', $department_data);

    // Check if the update was successful
    if ($this->db->affected_rows() == 0) {
        $this->output
            ->set_status_header(500)
            ->set_output(json_encode(['status' => false, 'message' => 'Failed to update department']));
        return;
    }

    // Fetch the updated department to return
    $query = $this->db->get_where('departments', ['id' => $id]);
    $department = $query->row_array();

    // Return success response
    $this->output
        ->set_content_type('application/json')
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
            ->set_output(json_encode(['status' => false, 'message' => 'Failed to delete department']));
        return;
    }

    // Return success response
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => true, 'message' => 'Department deleted successfully']));
}

public function create_event_post()
{
    // Retrieve data from POST request
    $title = $this->input->post('title');
    $starting_date = $this->input->post('starting_date');
    $ending_date = $this->input->post('ending_date');
    $school_id = $this->input->post('school_id');
    $session = $this->input->post('session') ?? 2;

    // Log the received data for debugging
    log_message('debug', 'Received data: ' . json_encode($_POST));

    // Check if the required data is provided
    if (!$title || !$starting_date || !$ending_date || !$school_id) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['status' => false, 'message' => 'Invalid input data']));
        return;
    }

    // Prepare data to insert
    $event_data = [
        'title' => $title,
        'starting_date' => $starting_date,
        'ending_date' => $ending_date,
        'school_id' => $school_id,
        'session' => $session
    ];

    // Insert data into the event_calendars table
    $this->db->insert('event_calendars', $event_data);

    // Check if the insert was successful
    if ($this->db->affected_rows() == 0) {
        $this->output
            ->set_status_header(500)
            ->set_output(json_encode(['status' => false, 'message' => 'Failed to create event']));
        return;
    }

    // Fetch the created event to return
    $event_id = $this->db->insert_id();
    $query = $this->db->get_where('event_calendars', ['id' => $event_id]);
    $event = $query->row_array();

    // Return success response
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => true, 'event' => $event]));
}

public function edit_event_post($event_id)
{
    // Retrieve data from POST request
    $title = $this->input->post('title');
    $starting_date = $this->input->post('starting_date');
    $ending_date = $this->input->post('ending_date');
    $school_id = $this->input->post('school_id');
    $session = $this->input->post('session') ?? 2;

    // Log the received data for debugging
    log_message('debug', 'Received data: ' . json_encode($_POST));

    // Check if the required data is provided
    if (!$title || !$starting_date || !$ending_date || !$school_id) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['status' => false, 'message' => 'Invalid input data']));
        return;
    }

    // Prepare data to update
    $event_data = [
        'title' => $title,
        'starting_date' => $starting_date,
        'ending_date' => $ending_date,
        'school_id' => $school_id,
        'session' => $session
    ];

    // Update the event in the event_calendars table
    $this->db->where('id', $event_id);
    $this->db->update('event_calendars', $event_data);

    // Check if the update was successful
    if ($this->db->affected_rows() == 0) {
        $this->output
            ->set_status_header(500)
            ->set_output(json_encode(['status' => false, 'message' => 'Failed to update event']));
        return;
    }

    // Fetch the updated event to return
    $query = $this->db->get_where('event_calendars', ['id' => $event_id]);
    $event = $query->row_array();

    // Return success response
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => true, 'event' => $event]));
}




public function events_by_school_id_get($school_id)
{
    // Validate school_id
    if (!$school_id) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['status' => false, 'message' => 'Invalid school_id']));
        return;
    }

    // Get the page number from the query string
    $page = $this->input->get('page');
    $limit = 3; // Number of events per page
    $offset = ($page - 1) * $limit;

    // Fetch events by school_id with pagination
    $this->db->select('event_calendars.*, schools.name as school_name');
    $this->db->from('event_calendars');
    $this->db->join('schools', 'schools.id = event_calendars.school_id');
    $this->db->where('event_calendars.school_id', $school_id);
    $this->db->limit($limit, $offset);
    $query = $this->db->get();
    $result = $query->result_array();

    // Check if any events found
    if (empty($result)) {
        $this->output
            ->set_status_header(404)
            ->set_output(json_encode(['status' => false, 'message' => 'No events found']));
        return;
    }

    // Return success response with events
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => true, 'events' => $result]));
}


public function delete_event_delete($event_id)
{
    // Validate event_id
    if (!$event_id) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['status' => false, 'message' => 'Invalid event_id']));
        return;
    }

    // Delete the event
    $this->db->where('id', $event_id);
    $this->db->delete('event_calendars');

    // Check if the delete was successful
    if ($this->db->affected_rows() == 0) {
        $this->output
            ->set_status_header(404)
            ->set_output(json_encode(['status' => false, 'message' => 'Event not found or already deleted']));
        return;
    }

    // Return success response
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => true, 'message' => 'Event deleted successfully']));
}

public function exams_by_school_id_get($school_id, $page = 1)
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
    $limit = 4; // Number of exams per page
    $offset = ($page - 1) * $limit;

    // Fetch exams by school_id with pagination and include school name
    $this->db->select('exams.*, schools.name as school_name');
    $this->db->from('exams');
    $this->db->join('schools', 'schools.id = exams.school_id');
    $this->db->where('exams.school_id', $school_id);
    $this->db->limit($limit, $offset);
    $query = $this->db->get();
    $result = $query->result_array();

    // Get the total count of exams
    $this->db->where('school_id', $school_id);
    $this->db->from('exams');
    $total = $this->db->count_all_results();

    // Check if any exams found
    if (empty($result)) {
        $this->output
            ->set_status_header(404)
            ->set_output(json_encode(['status' => false, 'message' => 'No exams found']));
        return;
    }

    // Return success response with exams and total count
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => true, 'exams' => $result, 'total' => $total]));
}



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

    // Check if the delete was successful
    if ($this->db->affected_rows() == 0) {
        $this->output
            ->set_status_header(500)
            ->set_output(json_encode(['status' => false, 'message' => 'Failed to delete exam']));
        return;
    }

    // Return success response
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => true, 'message' => 'Exam deleted successfully']));
}

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


////
///Routines Part
public function create_routine_post()
{
    // Get the raw POST data
    $postData = json_decode(file_get_contents('php://input'), true);

    // Debug: Log the received data
    log_message('debug', 'Received raw POST data: ' . print_r($postData, TRUE));

    // Fetch data from the decoded JSON
    $data = array(
        'class_id' => isset($postData['class_id']) ? $postData['class_id'] : null,
        'section_id' => isset($postData['section_id']) ? $postData['section_id'] : null,
        'subject_id' => isset($postData['subject_id']) ? $postData['subject_id'] : null,
        'starting_hour' => isset($postData['starting_hour']) ? $postData['starting_hour'] : null,
        'ending_hour' => isset($postData['ending_hour']) ? $postData['ending_hour'] : null,
        'starting_minute' => isset($postData['starting_minute']) ? $postData['starting_minute'] : null,
        'ending_minute' => isset($postData['ending_minute']) ? $postData['ending_minute'] : null,
        'day' => isset($postData['day']) ? $postData['day'] : null,
        'teacher_id' => isset($postData['teacher_id']) ? $postData['teacher_id'] : null,
        'room_id' => isset($postData['room_id']) ? $postData['room_id'] : null,
        'school_id' => isset($postData['school_id']) ? $postData['school_id'] : null,
        'session_id' => isset($postData['session_id']) ? $postData['session_id'] : null
    );

    // Debug: Log the parsed data
    log_message('debug', 'Parsed data: ' . print_r($data, TRUE));

    // Insert data into the database
    $inserted = $this->db->insert('routines', $data);

    // Debug: Log the insert status
    log_message('debug', 'Insert status: ' . ($inserted ? 'Success' : 'Failed'));

    // Return response
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode([
            'status' => $inserted,
            'notification' => $inserted ? get_phrase('class_routine_added_successfully') : get_phrase('failed_to_add_class_routine')
        ]));
}

public function update_routine_put($id)
{
    // Get the raw PUT data
    $putData = json_decode(file_get_contents('php://input'), true);

    // Debug: Log the received data
    log_message('debug', 'Received raw PUT data: ' . print_r($putData, TRUE));

    // Fetch data from the decoded JSON
    $data = array(
        'class_id' => isset($putData['class_id']) ? $putData['class_id'] : null,
        'section_id' => isset($putData['section_id']) ? $putData['section_id'] : null,
        'subject_id' => isset($putData['subject_id']) ? $putData['subject_id'] : null,
        'starting_hour' => isset($putData['starting_hour']) ? $putData['starting_hour'] : null,
        'ending_hour' => isset($putData['ending_hour']) ? $putData['ending_hour'] : null,
        'starting_minute' => isset($putData['starting_minute']) ? $putData['starting_minute'] : null,
        'ending_minute' => isset($putData['ending_minute']) ? $putData['ending_minute'] : null,
        'day' => isset($putData['day']) ? $putData['day'] : null,
        'teacher_id' => isset($putData['teacher_id']) ? $putData['teacher_id'] : null,
        'room_id' => isset($putData['room_id']) ? $putData['room_id'] : null
    );

    // Debug: Log the parsed data
    log_message('debug', 'Parsed data: ' . print_r($data, TRUE));

    // Remove any null values from the $data array
    $data = array_filter($data, function($value) {
        return $value !== null;
    });

    // Ensure there is data to update
    if (empty($data)) {
        log_message('debug', 'No data to update.');
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => false,
                'notification' => get_phrase('no_data_provided_for_update')
            ]));
        return;
    }

    // Update data in the database
    $this->db->where('id', $id);
    $updated = $this->db->update('routines', $data);

    // Debug: Log the SQL query and any errors
    log_message('debug', 'SQL Query: ' . $this->db->last_query());
    if (!$updated) {
        $error = $this->db->error();
        log_message('debug', 'DB Error: ' . $error['message']);
    }

    // Debug: Log the update status
    log_message('debug', 'Update status: ' . ($updated ? 'Success' : 'Failed'));

    // Return response
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode([
            'status' => $updated,
            'notification' => $updated ? get_phrase('class_routine_updated_successfully') : get_phrase('failed_to_update_class_routine')
        ]));
}


public function delete_routine_delete($id)
{
    // No need to fetch data for deletion, just use the ID

    // Debug: Log the ID to be deleted
    log_message('debug', 'Deleting routine with ID: ' . $id);

    // Delete the routine from the database
    $this->db->where('id', $id);
    $deleted = $this->db->delete('routines');

    // Debug: Log the delete status
    log_message('debug', 'Delete status: ' . ($deleted ? 'Success' : 'Failed'));

    // Return response
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode([
            'status' => $deleted,
            'notification' => $deleted ? get_phrase('class_routine_deleted_successfully') : get_phrase('failed_to_delete_class_routine')
        ]));
}


public function routines_by_school_id_get($school_id)
{
    // Debug: Log the received school_id
    log_message('debug', 'Fetching routines for school_id: ' . $school_id);

    // Fetch routines from the database
    $this->db->where('school_id', $school_id);
    $query = $this->db->get('routines');
    $routines = $query->result_array();

    // Debug: Log the fetched data
    log_message('debug', 'Fetched routines: ' . print_r($routines, TRUE));

    // Return response
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode([
            'status' => true,
            'data' => $routines,
            'notification' => get_phrase('routines_fetched_successfully')
        ]));
}



public function routines_by_class_and_section_get($class_id, $section_id)
{
    // Debug: Log the received class_id and section_id
    log_message('debug', 'Fetching routines for class_id: ' . $class_id . ' and section_id: ' . $section_id);

    // Fetch routines from the database
    $this->db->where('class_id', $class_id);
    $this->db->where('section_id', $section_id);
    $query = $this->db->get('routines');
    $routines = $query->result_array();

    // Debug: Log the fetched data
    log_message('debug', 'Fetched routines: ' . print_r($routines, TRUE));

    // Return response
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode([
            'status' => true,
            'data' => $routines,
            'notification' => get_phrase('routines_fetched_successfully')
        ]));
}


//End Routines
//Student Free Manager

public function invoice_by_date_range_post() {
    $this->load->database();

    // Retrieve and log posted data
    $date_from = $this->input->post('date_from');
    $date_to = $this->input->post('date_to');
    $selected_class = $this->input->post('selected_class');
    $selected_status = $this->input->post('selected_status');

    log_message('debug', 'Received: date_from=' . $date_from . ', date_to=' . $date_to . ', selected_class=' . $selected_class . ', selected_status=' . $selected_status);

    // Start building the query
    $this->db->from('invoices');

    // Apply class_id filter
    if (!empty($selected_class) && $selected_class != "all") {
        $this->db->where('class_id', $selected_class);
        log_message('debug', 'Applied class_id filter: ' . $selected_class);
    }

    // Apply status filter
    if (!empty($selected_status) && $selected_status != "all") {
        $this->db->where('status', $selected_status);
        log_message('debug', 'Applied status filter: ' . $selected_status);
    }

    // Apply date range filter
    if (!empty($date_from) && !empty($date_to)) {
        $this->db->where('DATE(created_at) >=', $date_from);
        $this->db->where('DATE(created_at) <=', $date_to);
        log_message('debug', 'Applied date range filter: ' . $date_from . ' to ' . $date_to);
    }

    // Execute the query
    $query = $this->db->get();

    // Log the executed SQL query for debugging
    log_message('debug', 'Executed SQL Query: ' . $this->db->last_query());

    // Return the results as a JSON response
    if ($query->num_rows() > 0) {
        $response = array('status' => 'success', 'data' => $query->result_array());
    } else {
        $response = array('status' => 'error', 'message' => 'No invoices found for the given criteria');
    }

    $this->output->set_content_type('application/json')->set_output(json_encode($response));
}


protected function get_class_name($class_id) {
    // Query the database to get the class name based on the class_id
    $this->load->database();
    $this->db->select('name');
    $this->db->from('classes');
    $this->db->where('id', $class_id);
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
        $result = $query->row();
        return $result->name;
    } else {
        return null; // or return a default value
    }
}

public function get_invoice_by_parent_id() {
    $parent_user_id = $this->session->userdata('user_id');
    $parent_data = $this->db->get_where('parents', array('user_id' => $parent_user_id))->row_array();
    $student_list = $this->user_model->get_student_list_of_logged_in_parent();
    $student_ids = array();

    foreach ($student_list as $student) {
        if (!in_array($student['student_id'], $student_ids)) {
            array_push($student_ids, $student['student_id']);
        }
    }

    if (count($student_ids) > 0) {
        $this->db->where_in('student_id', $student_ids);
        $this->db->where('school_id', $this->school_id);
        $this->db->where('session', $this->active_session);
        $invoices = $this->db->get('invoices')->result_array();

        $response = array('status' => true, 'data' => $invoices);
    } else {
        $response = array('status' => false, 'notification' => 'No invoices found');
    }

    $this->output->set_content_type('application/json')->set_output(json_encode($response));
}

public function create_single_invoice_post() {
    // Get raw POST data
    $input_data = json_decode($this->input->raw_input_stream, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        log_message('error', 'JSON decode error: ' . json_last_error_msg());
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['status' => false, 'message' => 'Invalid JSON input']));
        return;
    }

    // Log the input data for verification
    log_message('debug', 'Input data: ' . json_encode($input_data));

    // Validate and log the 'created_at' date
    $created_at = isset($input_data['created_at']) ? $input_data['created_at'] : date('Y-m-d H:i:s');
    $created_at_datetime = DateTime::createFromFormat('Y-m-d H:i:s', $created_at);

    if ($created_at_datetime === false) {
        $errors = DateTime::getLastErrors();
        log_message('error', 'Date parsing error: ' . $created_at . ' Errors: ' . print_r($errors, true));
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['status' => false, 'message' => 'Invalid date format. Expected format: Y-m-d H:i:s']));
        return;
    }

    $created_at_timestamp = $created_at_datetime->getTimestamp();
    log_message('debug', 'Parsed timestamp: ' . $created_at_timestamp);

    // Prepare invoice data
    $invoice_data = [
        'title' => isset($input_data['title']) ? $input_data['title'] : null,
        'total_amount' => isset($input_data['total_amount']) ? $input_data['total_amount'] : 0,
        'class_id' => isset($input_data['class_id']) ? $input_data['class_id'] : null,
        'student_id' => isset($input_data['student_id']) ? $input_data['student_id'] : null,
        'paid_amount' => isset($input_data['paid_amount']) ? $input_data['paid_amount'] : 0,
        'status' => isset($input_data['status']) ? $input_data['status'] : 'unpaid',
        'payment_method' => isset($input_data['payment_method']) ? $input_data['payment_method'] : null,
        'school_id' => isset($input_data['school_id']) ? $input_data['school_id'] : null,
        'session' => isset($input_data['session']) ? $input_data['session'] : null,
        'created_at' => $created_at_timestamp,
        'updated_at' => ($input_data['paid_amount'] > 0) ? $created_at_timestamp : null,
    ];

    // Check for required fields
    $required_fields = ['title', 'class_id', 'student_id', 'school_id', 'session'];
    foreach ($required_fields as $field) {
        if (empty($invoice_data[$field])) {
            log_message('error', ucfirst($field) . ' is required');
            $this->output
                ->set_status_header(400)
                ->set_output(json_encode(['status' => false, 'message' => ucfirst($field) . ' is required']));
            return;
        }
    }

    // Insert the data into the database
    $this->db->insert('invoices', $invoice_data);
    if ($this->db->affected_rows() > 0) {
        $invoice_id = $this->db->insert_id();
        log_message('debug', 'Invoice created successfully with ID: ' . $invoice_id);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => true, 'invoice_id' => $invoice_id, 'message' => 'Invoice created successfully']));
    } else {
        log_message('error', 'Database insert error: ' . $this->db->error()['message']);
        $this->output
            ->set_status_header(500)
            ->set_output(json_encode(['status' => false, 'message' => 'Failed to insert invoice data']));
    }
}




public function create_mass_invoice() {
    $data['total_amount'] = $this->input->post('total_amount');
    $data['paid_amount'] = $this->input->post('paid_amount');
    $data['status'] = $this->input->post('status');

    if ($data['paid_amount'] > $data['total_amount']) {
        $response = array('status' => false, 'notification' => 'Paid amount cannot be greater than total amount');
        return $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    if ($data['status'] == 'paid' && $data['total_amount'] != $data['paid_amount']) {
        $response = array('status' => false, 'notification' => 'Paid amount is not equal to total amount');
        return $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    $data['title'] = $this->input->post('title');
    $data['class_id'] = $this->input->post('class_id');
    $data['school_id'] = $this->school_id;
    $data['session'] = $this->active_session;
    $data['created_at'] = strtotime(date('d-M-Y'));

    if ($this->input->post('paid_amount') > 0) {
        $data['updated_at'] = strtotime(date('d-M-Y'));
    }

    $enrolments = $this->user_model->get_student_details_by_id('section', $this->input->post('section_id'));
    foreach ($enrolments as $enrolment) {
        $data['student_id'] = $enrolment['student_id'];
        $this->db->insert('invoices', $data);
    }

    if (sizeof($enrolments) > 0) {
        $response = array('status' => true, 'notification' => 'Invoices added successfully');
    } else {
        $response = array('status' => false, 'notification' => 'No students found');
    }

    return $this->output->set_content_type('application/json')->set_output(json_encode($response));
}

public function update_invoice($id = "") {
    $previous_invoice_data = $this->db->get_where('invoices', array('id' => $id))->row_array();

    $data['title'] = $this->input->post('title');
    $data['total_amount'] = $this->input->post('total_amount');
    $data['class_id'] = $this->input->post('class_id');
    $data['student_id'] = $this->input->post('student_id');
    $data['paid_amount'] = $this->input->post('paid_amount');
    $data['status'] = $this->input->post('status');

    if ($data['paid_amount'] > $data['total_amount']) {
        $response = array('status' => false, 'notification' => 'Paid amount cannot be greater than total amount');
        return $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
    if ($data['status'] == 'paid' && $data['total_amount'] != $data['paid_amount']) {
        $response = array('status' => false, 'notification' => 'Paid amount is not equal to total amount');
        return $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    if ($data['total_amount'] == $data['paid_amount']) {
        $data['status'] = 'paid';
    }

    if ($this->input->post('paid_amount') != $previous_invoice_data['paid_amount'] && $this->input->post('paid_amount') > 0) {
        $data['updated_at'] = strtotime(date('d-M-Y'));
    } elseif ($this->input->post('paid_amount') == 0 || $this->input->post('paid_amount') == "") {
        $data['updated_at'] = 0;
    }

    $this->db->where('id', $id);
    $this->db->update('invoices', $data);

    $response = array('status' => true, 'notification' => 'Invoice updated successfully');
    return $this->output->set_content_type('application/json')->set_output(json_encode($response));
}

public function delete_invoice($id = "") {
    $this->db->where('id', $id);
    $this->db->delete('invoices');

    $response = array('status' => true, 'notification' => 'Invoice deleted successfully');
    return $this->output->set_content_type('application/json')->set_output(json_encode($response));
}


//End Student Free Manager
//Class Room Part

public function add_class_room_post() {
    $data = json_decode($this->input->raw_input_stream, true);
    if (!isset($data['name']) || !isset($data['school_id'])) {
        return $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['status' => false, 'message' => 'Missing required fields']));
    }
    log_message('debug', 'Input data: ' . json_encode($data));
    $this->load->database();
    $this->db->insert('class_rooms', $data);
    if ($this->db->affected_rows() > 0) {
        return $this->output
            ->set_status_header(200)
            ->set_output(json_encode(['status' => true, 'message' => 'Class room added successfully']));
    } else {
        $error = $this->db->error();
        log_message('error', 'Database insert error: ' . $error['message']);
        return $this->output
            ->set_status_header(500)
            ->set_output(json_encode(['status' => false, 'message' => 'Failed to add class room', 'error' => $error['message']]));
    }
}

public function update_class_room_put($id) {
    $data = json_decode($this->input->raw_input_stream, true);
    if (!isset($data['name']) || !isset($data['school_id'])) {
        return $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['status' => false, 'message' => 'Missing required fields']));
    }
    log_message('debug', 'Input data: ' . json_encode($data));
    $this->load->database();
    $this->db->where('id', $id);
    $this->db->update('class_rooms', $data);
    if ($this->db->affected_rows() > 0) {
        return $this->output
            ->set_status_header(200)
            ->set_output(json_encode(['status' => true, 'message' => 'Class room updated successfully']));
    } else {
        $error = $this->db->error();
        log_message('error', 'Database update error: ' . $error['message']);
        return $this->output
            ->set_status_header(500)
            ->set_output(json_encode(['status' => false, 'message' => 'Failed to update class room', 'error' => $error['message']]));
    }
}

public function delete_class_room_delete($id) {
    $this->load->database();
    $this->db->where('id', $id);
    $this->db->delete('class_rooms');
    if ($this->db->affected_rows() > 0) {
        return $this->output
            ->set_status_header(200)
            ->set_output(json_encode(['status' => true, 'message' => 'Class room deleted successfully']));
    } else {
        $error = $this->db->error();
        log_message('error', 'Database delete error: ' . $error['message']);
        return $this->output
            ->set_status_header(500)
            ->set_output(json_encode(['status' => false, 'message' => 'Failed to delete class room', 'error' => $error['message']]));
    }
}


public function get_class_room_get($school_id) {
    $this->load->database();
    $this->db->select('class_rooms.*, schools.name as school_name');
    $this->db->from('class_rooms');
    $this->db->join('schools', 'schools.id = class_rooms.school_id');
    $this->db->where('class_rooms.school_id', $school_id);
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
        return $this->output
            ->set_status_header(200)
            ->set_output(json_encode(['status' => true, 'data' => $query->result()]));
    } else {
        return $this->output
            ->set_status_header(404)
            ->set_output(json_encode(['status' => false, 'message' => 'Class rooms not found']));
    }
}






//End of ClassRoom Part
///Marks Part

public function add_marks_post() {
    // Retrieve and decode input data
    $data = json_decode($this->input->raw_input_stream, true);

    // Validate input data
    if (!isset($data['student_id']) || !isset($data['subject_id']) || !isset($data['class_id']) || !isset($data['section_id']) || !isset($data['exam_id']) || !isset($data['mark_obtained']) || !isset($data['comment']) || !isset($data['school_id'])) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['status' => false, 'message' => 'Missing required fields']));
        return;
    }

    // Prepare data for insertion
    $data['session'] = 1; // Example session id, replace with your logic

    // Log the data for debugging
    log_message('debug', 'Input data: ' . json_encode($data));

    // Attempt to insert marks
    $this->db->insert('marks', $data);

    if ($this->db->affected_rows() > 0) {
        $this->output
            ->set_status_header(200)
            ->set_output(json_encode(['status' => true, 'message' => 'Marks added successfully']));
    } else {
        // Log the database error for debugging
        $error = $this->db->error();
        log_message('error', 'Database insert error: ' . $error['message']);
        
        $this->output
            ->set_status_header(500)
            ->set_output(json_encode(['status' => false, 'message' => 'Failed to add marks']));
    }
}


public function update_marks_put() {
    // Retrieve and decode input data
    $data = json_decode($this->input->raw_input_stream, true);

    // Validate input data
    if (!isset($data['student_id']) || !isset($data['subject_id']) || !isset($data['class_id']) || !isset($data['section_id']) || !isset($data['exam_id']) || !isset($data['mark_obtained']) || !isset($data['comment']) || !isset($data['school_id'])) {
        return $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['status' => false, 'message' => 'Missing required fields']));
    }

    // Log the data for debugging
    log_message('debug', 'Input data: ' . json_encode($data));

    // Prepare the data for the checker
    $checker = array(
        'student_id' => $data['student_id'],
        'class_id' => $data['class_id'],
        'section_id' => $data['section_id'],
        'subject_id' => $data['subject_id'],
        'exam_id' => $data['exam_id'],
        'school_id' => $data['school_id'],
        'session' => isset($data['session']) ? $data['session'] : 1 // Example session id, replace with your logic
    );

    // Prepare the update data
    $update_data = array(
        'mark_obtained' => $data['mark_obtained'],
        'comment' => $data['comment']
    );

    // Begin transaction
    $this->db->trans_start();

    // Check if the record exists
    $query = $this->db->get_where('marks', $checker);

    if ($query->num_rows() > 0) {
        // Record found, proceed with update
        $row = $query->row();
        $this->db->where('id', $row->id);
        $this->db->update('marks', $update_data);
    } else {
        // Record not found
        $this->db->trans_complete();
        return $this->output
            ->set_status_header(404)
            ->set_output(json_encode(['status' => false, 'message' => 'Record not found']));
    }

    // Complete transaction
    $this->db->trans_complete();

    // Check transaction status
    if ($this->db->trans_status() === FALSE) {
        // Log the database error for debugging
        $error = $this->db->error();
        log_message('error', 'Database update error: ' . $error['message']);

        return $this->output
            ->set_status_header(500)
            ->set_output(json_encode(['status' => false, 'message' => 'Failed to update marks']));
    } else {
        return $this->output
            ->set_status_header(200)
            ->set_output(json_encode(['status' => true, 'message' => 'Marks updated successfully']));
    }
}



public function filter_exams_by_school_id_get($school_id)
{
    // Validate school_id
    if (!$school_id) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['status' => false, 'message' => 'Invalid school_id']));
        return;
    }

    // Fetch exams by school_id and include school name
    $this->db->select('exams.*, schools.name as school_name');
    $this->db->from('exams');
    $this->db->join('schools', 'schools.id = exams.school_id');
    $this->db->where('exams.school_id', $school_id);
    $query = $this->db->get();
    $result = $query->result_array();

    // Check if any exams found
    if (empty($result)) {
        $this->output
            ->set_status_header(404)
            ->set_output(json_encode(['status' => false, 'message' => 'No exams found']));
        return;
    }

    // Return success response with exams
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => true, 'exams' => $result]));
}

public function filter_student_get() {
    // Retrieve and validate input data
    $exam_id = $this->input->get('exam_id');
    $class_id = $this->input->get('class_id');
    $section_id = $this->input->get('section_id');
    $subject_id = $this->input->get('subject_id');
    $school_id = $this->input->get('school_id');
    $session = $this->input->get('session');

    // Validate required parameters
    if (empty($exam_id) || empty($class_id) || empty($section_id) || empty($subject_id) || empty($school_id) || empty($session)) {
        $this->output
             ->set_status_header(400)
             ->set_output(json_encode(['status' => 'error', 'message' => 'Missing required fields']));
        return;
    }

    // Build the query to filter students and join with the students table to get student names
    $this->db->select('marks.*, users.name as student_name');
    $this->db->from('marks');
    $this->db->join('students', 'students.id = marks.student_id');
    $this->db->join('users', 'users.id = students.user_id'); // Assuming there's a users table and a user_id in the students table
    $this->db->where('marks.exam_id', $exam_id);
    $this->db->where('marks.class_id', $class_id);
    $this->db->where('marks.section_id', $section_id);
    $this->db->where('marks.subject_id', $subject_id);
    $this->db->where('marks.school_id', $school_id);
    $this->db->where('marks.session', $session);

    $query = $this->db->get();
    $result = $query->result_array();

    // Check if any students found
    if (empty($result)) {
        $this->output
             ->set_status_header(404)
             ->set_output(json_encode(['status' => 'error', 'message' => 'No students found']));
        return;
    }

    // Return success response with students data
    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode(['status' => 'success', 'students' => $result]));
}


public function get_sections_by_class_id_get($class_id) {
    // Ensure the request method is GET
    if ($this->input->server('REQUEST_METHOD') !== 'GET') {
        $this->output
             ->set_status_header(405)
             ->set_output(json_encode(['message' => 'Method not allowed']));
        return;
    }

    // Validate the input
    if (empty($class_id)) {
        $this->output
             ->set_status_header(400)
             ->set_output(json_encode(['message' => 'Class ID is required']));
        return;
    }

    // Fetch sections by class_id
    $this->db->where('class_id', $class_id);
    $query = $this->db->get('sections');
    $sections = $query->result_array();

    // Check if any sections are found
    if (!empty($sections)) {
        $this->output
             ->set_status_header(200)
             ->set_content_type('application/json')
             ->set_output(json_encode(['status' => 'success', 'sections' => $sections]));
    } else {
        $this->output
             ->set_status_header(404)
             ->set_output(json_encode(['status' => 'error', 'message' => 'No sections found for the given class ID']));
    }
}

public function get_subject_id_by_name_get($subject_name = "")
{
    // Validate input
    if (empty($subject_name)) {
        $this->output
            ->set_status_header(400)
            ->set_output(json_encode(['status' => 'error', 'message' => 'Subject name is required']));
        return;
    }

    // Sanitize input
    $subject_name = urldecode($subject_name);

    // Query to get subject ID by name
    $this->db->select('id');
    $this->db->from('subjects');
    $this->db->where('name', $subject_name);
    $query = $this->db->get();
    $result = $query->row_array();

    // Check if any subject found
    if ($result) {
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => 'success', 'subject_id' => $result['id']]));
    } else {
        $this->output
            ->set_status_header(404)
            ->set_output(json_encode(['status' => 'error', 'message' => 'Subject not found']));
    }
}


//End Marks

////BOOKS

public function books_by_school_id_get($school_id, $page = 1)
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

////
/////Book issue



/////////////////////////////////
//TeAcher Permission//
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
//End of Expense

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
        try {
            $userdata = $this->admin_model->login();
    
            if ($userdata['validity'] == 1) {
                $userdata['token'] = $this->tokenHandler->GenerateToken($userdata);
            }
    
            return $this->set_response($userdata, REST_Controller::HTTP_OK);
        } catch (Exception $e) {
            log_message('error', 'Error occurred in login_post: ' . $e->getMessage());
            return $this->set_response([
                'status' => 500,
                'message' => 'An error occurred. Please try again later.'
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
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
