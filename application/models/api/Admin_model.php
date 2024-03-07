<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*  @author   : Creativeitem
*  date      : December, 2019
*  Ekattor School Management System With Addons
*  http://codecanyon.net/user/Creativeitem
*  http://support.creativeitem.com
*/

class Admin_model extends CI_Model {

  public function __construct()
  {
    parent::__construct();
  }

  // Login mechanism
  public function login() {
    $response = array();
    $credential = array('email' => $_POST['email'], 'password' => sha1($_POST['password']));
    $query = $this->db->get_where('users', $credential);
    if ($query->num_rows() > 0) {
      $row = $query->row_array();
      $response['status'] = 200;
      $response['message'] = 'Loggedin Successfully';
      $response['user_id'] = $row['id'];
      $response['name'] = $row['name'];
      $response['email'] = $row['email'];
      $response['role'] = strtolower($row['role']);
      $response['school_id'] = $row['school_id'];
      $response['address'] = $row['address'];
      $response['phone'] = $row['phone'];
      $response['birthday'] = date('d-M-Y', $row['birthday']);
      $response['gender'] = strtolower($row['gender']);
      $response['blood_group'] = strtolower($row['blood_group']);
      $response['validity'] = true;

    }else{
      $response['status'] = 404;
      $response['message'] = 'Not Found';
      $response['validity'] = false;
    }
    return $response;
  }

  //FORGOT PASSWORD RETREIVING
  public function forgot_password() {
    $response = array();
    $email = $_POST['email'];
    $query = $this->db->get_where('users', array('email' => $email));
    if ($query->num_rows() > 0) {
      $query = $query->row_array();
      $new_password = substr( md5( rand(100000000,20000000000) ) , 0,7);

      // updating the database
      $updater = array(
        'password' => sha1($new_password)
      );
      $this->db->where('id', $query['id']);
      $this->db->update('users', $updater);

      // sending mail to user
      $this->email_model->password_reset_email($new_password, $query['id']);

      $response['status'] = 200;
      $response['message'] = 'Password Reset Successfully';
    }else{
      $response['status'] = 404;
      $response['message'] = 'Not Found';
    }

    return $response;
  }

  // USERDATA GET
  public function get_userdata($user_id = "") {
    $response = array();
    $credential = array('id' => $user_id);
    $query = $this->db->get_where('users', $credential);
    if ($query->num_rows() > 0) {
      $row = $query->row_array();
      $response['status'] = 200;
      $response['message'] = 'Password Reset Successfully';
      $response['user_id'] = $row['id'];
      $response['name'] = $row['name'];
      $response['email'] = $row['email'];
      $response['role'] = strtolower($row['role']);
      $response['school_id'] = $row['school_id'];
      $response['address'] = $row['address'];
      $response['phone'] = $row['phone'];
      $response['birthday'] = date('d-M-Y', $row['birthday']);
      $response['gender'] = strtolower($row['gender']);
      $response['blood_group'] = strtolower($row['blood_group']);
    }else{
      $response['status'] = 404;
      $response['message'] = get_phrase('user_not_found');
    }
    return $response;
  }

  // GET DASHBOARD DATA
  public function get_dashboard_data($user_id = "") {
    $response = array();
    $credential = array('id' => $user_id);
    $query = $this->db->get_where('users', $credential);
    if ($query->num_rows() > 0) {
      $row = $query->row_array();
      $response['status'] = 200;
      $response['message'] = 'Data Fetched Successfully';
      $school_id = api_school_id($row['id']);
      $active_session = api_active_session($row['id']);
      $response['total_number_of_students'] = $this->db->get_where('enrols', array('school_id' => $school_id, 'session' => $active_session))->num_rows();
      $response['total_number_of_teachers'] = $this->db->get_where('teachers', array('school_id' => $school_id))->num_rows();
      $attendance_checker = array(
        'timestamp' => strtotime(date('Y-m-d')),
        'school_id' => $school_id,
        'status'    => 1
      );
      $todays_attendance = $this->db->get_where('daily_attendances', $attendance_checker);
      $response['total_number_of_student_attending_today'] = $todays_attendance->num_rows();
      $response['total_number_of_unpaid_invoices'] = $this->db->get_where('invoices', array('school_id' => $school_id, 'session' => $active_session, 'status' => 'unpaid'))->num_rows();
    }else{
      $response['status'] = 404;
      $response['message'] = get_phrase('user_not_found');
    }
    return $response;
  }

  // GET SUBJECTS AGAINST CLASS
  public function get_subjects($user_id = "") {
    $response = array();
    $school_id = api_school_id($user_id);
    $active_session = api_active_session($user_id);
    $class_id = $_GET['class_id'];
    $checker = array('class_id' => $class_id, 'school_id' => $school_id, 'session' => $active_session);
    $response['subjects'] = $this->db->get_where('subjects', $checker)->result_array();
    $response['status'] = 200;
    $response['message'] = 'Data Fetched Successfully';
    return $response;
  }

  //GET STUDENT LIST
  public function get_students($user_id = "") {
    $response = array();
    $school_id = api_school_id($user_id);
    $active_session = api_active_session($user_id);
    $class_id = $_GET['class_id'];
    $checker = array('class_id' => $class_id, 'school_id' => $school_id, 'session' => $active_session);
    $response['students'] = $this->student_data('class', $class_id, $school_id, $active_session);
    $response['status'] = 200;
    $response['message'] = 'Data Fetched Successfully';
    return $response;
  }

  // GET STUDENT'S ALL THE NECESSARY INFORMATION FROM ONE SINGLE FUNCTION
  public function student_data($lookUpType = "", $lookUpId = "", $school_id = "", $active_session = "") {
    $response = array();
    if ($lookUpType == 'class') {
      $checker = array(
        'class_id' => $lookUpId,
        'session' => $active_session,
        'school_id' => $school_id
      );
      $enrolments = $this->db->get_where('enrols', $checker)->result_array();
      foreach ($enrolments as $key => $enrolment) {
        $student_details = $this->db->get_where('students', array('id' => $enrolment['student_id']))->row_array();
        $response[$key]['student_id'] = $student_details['id'];
        $response[$key]['student_code'] = $student_details['code'];
        $response[$key]['user_id'] = $student_details['user_id'];
        $response[$key]['parent_id'] = $student_details['parent_id'];
        $user_details = $this->db->get_where('users', array('id' => $student_details['user_id']))->row_array();
        $response[$key]['name'] = $user_details['name'];
        $response[$key]['email'] = $user_details['email'];
        $response[$key]['role'] = $user_details['role'];
        $response[$key]['address'] = $user_details['address'];
        $response[$key]['phone'] = $user_details['phone'];
        $response[$key]['birthday'] = $user_details['birthday'];
        $response[$key]['gender'] = $user_details['gender'];
        $response[$key]['blood_group'] = $user_details['blood_group'];
        $class_details = $this->crud_model->get_class_details_by_id($enrolment['class_id'])->row_array();
        $section_details = $this->crud_model->get_section_details_by_id('section', $enrolment['section_id'])->row_array();
        $response[$key]['class_name'] = $class_details['name'];
        $response[$key]['section_name'] = $section_details['name'];
        $response[$key]['school_id']  = $enrolment['school_id'];
        $response[$key]['image_url']  = $this->user_model->get_user_image($student_details['user_id']);
      }
    }
    elseif ($lookUpType == 'student') {
      $checker = array(
        'student_id' => $lookUpId,
        'session' => $active_session,
        'school_id' => $school_id
      );
      $enrolment = $this->db->get_where('enrols', $checker)->row_array();
      $student_details = $this->db->get_where('students', array('id' => $lookUpId))->row_array();
      $response['student_id'] = $student_details['id'];
      $response['student_code'] = $student_details['code'];
      $response['user_id'] = $student_details['user_id'];
      $response['parent_id'] = $student_details['parent_id'];
      $user_details = $this->db->get_where('users', array('id' => $student_details['user_id']))->row_array();
      $response['name'] = $user_details['name'];
      $response['email'] = $user_details['email'];
      $response['role'] = $user_details['role'];
      $response['address'] = $user_details['address'];
      $response['phone'] = $user_details['phone'];
      $response['birthday'] = $user_details['birthday'];
      $response['gender'] = $user_details['gender'];
      $response['blood_group'] = $user_details['blood_group'];
      $class_details = $this->crud_model->get_class_details_by_id($enrolment['class_id'])->row_array();
      $section_details = $this->crud_model->get_section_details_by_id('section', $enrolment['section_id'])->row_array();
      $response['class_name'] = $class_details['name'];
      $response['section_name'] = $section_details['name'];
      $response['school_id']  = $enrolment['school_id'];
      $response['image_url']  = $this->user_model->get_user_image($student_details['user_id']);
    }

    return $response;
  }

  // GET STUDENT WISE EXAM & MARKS
  public function get_student_wise_marks($user_id = "") {
      $response = array();
      $school_id = api_school_id($user_id);
      $active_session = api_active_session($user_id);
      $student_id = $_GET['student_id'];
      $enrolment_checker = array(
          'student_id' => $student_id,
          'session' => $active_session,
          'school_id' => $school_id
      );
      $enrolment = $this->db->get_where('enrols', $enrolment_checker)->row_array();
      $class_details = $this->crud_model->get_class_details_by_id($enrolment['class_id'])->row_array();
      $section_details = $this->crud_model->get_section_details_by_id('section', $enrolment['section_id'])->row_array();

      $exam_checker = array(
          'school_id' => $school_id,
          'session' => $active_session
      );
      $exams = $this->db->get_where('exams', $exam_checker)->result_array();
      foreach ($exams as $key => $exam) {
          $exam_array = array();
          $exam_array['name'] = $exam['name'];


          /* GET MARKS BY EXAM IDS */
          $mark_checker = array(
              'student_id' => $student_id,
              'session' => $active_session,
              'school_id' => $school_id,
              'class_id' => $enrolment['class_id'],
              'section_id' => $enrolment['section_id'],
              'exam_id' => $exam['id']
          );

          $marks = $this->db->get_where('marks', $mark_checker)->result_array();
          $mark_array = array();
          foreach ($marks as $mark_key => $mark) {
              $subject_details = $this->crud_model->get_subject_by_id($mark['subject_id']);
              $mark_obtained = 0;
              if ($mark['mark_obtained'] > 0) {
                  $mark_obtained = $mark['mark_obtained'];
              }
              $mark_array[$mark_key]['subject'] = $subject_details['name'];
              $mark_array[$mark_key]['mark_obtained'] = $mark_obtained;
              $mark_array[$mark_key]['comment'] = $mark['comment'];

              // FIND THE MARK WISE GRADE
              $this->db->where('mark_from <=', $mark_obtained);
              $this->db->where('mark_upto >=', $mark_obtained);
              $grade_details = $this->db->get('grades')->row_array();
              $mark_array[$mark_key]['grade'] = $grade_details['name'];

          }
          $exam_array['marks'] = $mark_array;
          $response['exam'][$key] = $exam_array;
      }

      //
      // foreach ($marks as $key => $mark) {
      //     $subject_details = $this->crud_model->get_subject_by_id($mark['subject_id']);
      //     $response[$key]['student_id'] => $mark['student_id'];
      //     $response[$key]['subject_id'] => $mark['subject_id'];
      //     $response[$key]['subject_name'] => $subject_details['name'];
      //     $response[$key]['mark_obtained'] => $mark['mark_obtained'];
      //     $response[$key]['mark_obtained'] => $mark['mark_obtained'];
      //     $response[$key]['class_name'] => $class_details['name'];
      //     $response[$key]['section_name'] => $section_details['name'];
      // }
      $response['status'] = 200;
      $response['message'] = 'Data Fetched Successfully';
      return $response;
  }
}
