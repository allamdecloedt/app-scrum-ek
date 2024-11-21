<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
*  @author   : Creativeitem
*  date      : November, 2019
*  Ekattor School Management System With Addons
*  http://codecanyon.net/user/Creativeitem
*  http://support.creativeitem.com
*/

class Driver_model extends CI_Model
{
    protected $school_id;
    protected $active_session;

    public function __construct()
    {
        parent::__construct();
        $this->school_id = school_id();
        $this->active_session = active_session();
    }

    // Check user duplication
    public function check_duplication($action = "", $email = "", $user_id = "")
    {
        $duplicate_email_check = $this->db->get_where('users', array('email' => $email));

        if ($action == 'on_create') {
            if ($duplicate_email_check->num_rows() > 0) {
                return false;
            } else {
                return true;
            }
        } elseif ($action == 'on_update') {
            if ($duplicate_email_check->num_rows() > 0) {
                if ($duplicate_email_check->row()->id == $user_id) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        }
    }

    // 1. add new drive
    public function create_driver()
    {
        $data['school_id'] = html_escape($this->input->post('school_id'));
        $data['name'] = html_escape($this->input->post('name'));
        $data['email'] = html_escape($this->input->post('email'));
        $data['password'] = sha1($this->input->post('password'));
        $data['phone'] = html_escape($this->input->post('phone'));
        $data['gender'] = html_escape($this->input->post('gender'));
        $data['blood_group'] = html_escape($this->input->post('blood_group'));
        $data['address'] = html_escape($this->input->post('address'));
        $data['role'] = 'driver';

        // check email duplication
        $duplication_status = $this->check_duplication('on_create', $data['email']);
        if ($duplication_status) {
            $this->db->insert('users', $data);

            $driver_id = $this->db->insert_id();
            $driver_table_data['user_id'] = $driver_id;
            $driver_table_data['about'] = html_escape($this->input->post('about'));
            $social_links = array(
                'facebook' => $this->input->post('facebook_link'),
                'twitter' => $this->input->post('twitter_link'),
                'linkedin' => $this->input->post('linkedin_link')
            );
            $driver_table_data['social_links'] = json_encode($social_links);
            $driver_table_data['school_id'] = html_escape($this->input->post('school_id'));

            $this->db->insert('drivers', $driver_table_data);

            if ($_FILES['image_file']['name'] != "") {
                move_uploaded_file($_FILES['image_file']['tmp_name'], 'uploads/users/' . $driver_id . '.jpg');
            }

            $response = array(
                'status' => true,
                'notification' => get_phrase('driver_added_successfully')
            );
        } else {
            $response = array(
                'status' => false,
                'notification' => get_phrase('sorry_this_email_has_been_taken')
            );
        }

        return json_encode($response);
    }

    // 2. updated driver data
    public function update_driver($param1 = '')
    {
        $data['name'] = html_escape($this->input->post('name'));
        $data['email'] = html_escape($this->input->post('email'));
        $data['phone'] = html_escape($this->input->post('phone'));
        $data['gender'] = html_escape($this->input->post('gender'));
        $data['blood_group'] = html_escape($this->input->post('blood_group'));
        $data['address'] = html_escape($this->input->post('address'));

        // check email duplication
        $duplication_status = $this->check_duplication('on_update', $data['email'], $param1);
        if ($duplication_status) {
            $this->db->where('id', $param1);
            $this->db->where('school_id', $this->input->post('school_id'));
            $this->db->update('users', $data);

            $teacher_table_data['about'] = html_escape($this->input->post('about'));
            $social_links = array(
                'facebook' => $this->input->post('facebook_link'),
                'twitter' => $this->input->post('twitter_link'),
                'linkedin' => $this->input->post('linkedin_link')
            );
            $teacher_table_data['social_links'] = json_encode($social_links);
            $this->db->where('school_id', $this->input->post('school_id'));
            $this->db->where('user_id', $param1);
            $this->db->update('drivers', $teacher_table_data);

            if ($_FILES['image_file']['name'] != "") {
                move_uploaded_file($_FILES['image_file']['tmp_name'], 'uploads/users/' . $param1 . '.jpg');
            }

            $response = array(
                'status' => true,
                'notification' => get_phrase('driver_has_been_updated_successfully')
            );
        } else {
            $response = array(
                'status' => false,
                'notification' => get_phrase('sorry_this_email_has_been_taken')
            );
        }

        return json_encode($response);
    }

    // 3. delete driver
    public function delete_driver($param1 = '', $param2)
    {
        $this->db->where('id', $param1);
        $this->db->delete('users');

        $this->db->where('user_id', $param1);
        $this->db->delete('drivers');

        $response = array(
            'status' => true,
            'notification' => get_phrase('driver_has_been_deleted_successfully')
        );
        return json_encode($response);
    }


    // 4. add new vehicle
    public function create_vehicle()
    {
        // check duplicate vehicle chassis
        $vehicle_exists = $this->db->where('vh_chassis', $this->input->post('vh_chassis'))->get('vehicles')->row_array();
        if ($vehicle_exists) {
            $response = array(
                'status' => false,
                'notification' => get_phrase('sorry_vehicle_chassis_already_in_use')
            );
        } else {
            $data['school_id'] = html_escape($this->input->post('school_id'));
            $data['driver'] = html_escape($this->input->post('driver'));
            $data['vh_num'] = html_escape($this->input->post('vh_num'));
            $data['vh_model'] = html_escape($this->input->post('vh_model'));
            $data['vh_chassis'] = html_escape($this->input->post('vh_chassis'));
            $data['capacity'] = html_escape($this->input->post('capacity'));
            $data['route'] = html_escape($this->input->post('route'));

            $this->db->insert('vehicles', $data);
            $response = array(
                'status' => true,
                'notification' => get_phrase('vehicle_added_successfully')
            );
        }
        return json_encode($response);
    }

    // 5. vehicle update
    public function update_vehicle($param1 = '')
    {
        // check duplicate vehicle chassis
        $vehicle_exists = $this->db
            ->where('school_id', $this->input->post('school_id'))
            ->where('id != ', $param1)
            ->where('vh_chassis', $this->input->post('vh_chassis'))
            ->get('vehicles')->row_array();

        if ($vehicle_exists) {
            $response = array(
                'status' => false,
                'notification' => get_phrase('sorry_vehicle_chassis_already_in_use')
            );
        } else {
            $data['school_id'] = html_escape($this->input->post('school_id'));
            $data['driver'] = html_escape($this->input->post('driver'));
            $data['vh_num'] = html_escape($this->input->post('vh_num'));
            $data['vh_model'] = html_escape($this->input->post('vh_model'));
            $data['vh_chassis'] = html_escape($this->input->post('vh_chassis'));
            $data['capacity'] = html_escape($this->input->post('capacity'));
            $data['route'] = html_escape($this->input->post('route'));

            $this->db->where('id', $param1)->update('vehicles', $data);
            $response = array(
                'status' => true,
                'notification' => get_phrase('vehicle_updated_successfully')
            );
        }
        return json_encode($response);
    }

    // 6. delete vehicle
    public function delete_vehicle($param1 = '')
    {
        $this->db->where('id', $param1);
        $this->db->delete('vehicles');

        $response = array(
            'status' => true,
            'notification' => get_phrase('vehicle_has_been_deleted_successfully')
        );
        return json_encode($response);
    }

    // 7. assign student to vehicle
    function add_to_vehicle()
    {
        $status = false;
        $msg = '';

        $data['school_id'] = html_escape($this->input->post('school_id'));
        $data['vehicle_id'] = html_escape($this->input->post('vehicle'));
        $data['class_id'] = html_escape($this->input->post('class_id'));

        // check assign type
        $type = html_escape($this->input->post('assign_by'));
        // $type = 'individual';
        $vehicle_details = $this->db->where('id', $data['vehicle_id'])->get('vehicles')->row_array();
        $data['driver_id'] = $vehicle_details['driver'];

        // check vehicle seat capacity
        $booked_seat = $this->db->where('vehicle_id', $data['vehicle_id'])->get('assign_students')->num_rows();

        if ($type == 'individual') {
            $data['student_id'] = html_escape($this->input->post('student_id'));

            // check student exists or not
            $has_existence = $this->db->where('student_id', $data['student_id'])->get('assign_students')->row_array();
            if ($has_existence) {
                $msg = 'selected_student_exists';
            } else {
                if ($vehicle_details['capacity'] <= $booked_seat) {
                    $msg = 'sorry_vehicle_capacity_full';
                } else {
                    $data['date_added'] = time();
                    $this->db->insert('assign_students', $data);
                    $status = true;
                    $msg = 'student_added_successfully';
                }
            }
        } elseif ($type == 'by_class') {
            $num_of_students = $this->db->where('class_id', $data['class_id'])->get('enrols')->result_array();
            $available_seat = $vehicle_details['capacity'] - $booked_seat;

            // check vehicle capacity
            if (count($num_of_students) < 1) {
                $msg = 'no_students_in_selected_class';
            } elseif ($available_seat < count($num_of_students)) {
                $msg = 'sorry_vehicle_capacity_full_(' . $available_seat . '_available)';
            } else {
                foreach ($num_of_students as $student) {
                    $data['student_id'] = $student['student_id'];
                    $existence = $this->db->where('student_id', $student['student_id'])->get('assign_students')->row_array();
                    if (!$existence) {
                        $status = true;
                        $msg = 'student_added_successfully';
                        $this->db->insert('assign_students', $data);
                        continue;
                    }
                    $msg = 'students_already_exist';
                }
            }
        }

        return json_encode(['status' => $status, 'notification' => get_phrase($msg)]);
    }

    // 8. remove student from vehicle
    public function remove_from_vehicle($param1 = '')
    {
        $this->db->where('id', $param1);
        $this->db->delete('assign_students');

        $response = array(
            'status' => true,
            'notification' => get_phrase('student_has_been_removed_successfully')
        );
        return json_encode($response);
    }

    // get students by driver
    function get_students_by_driver($driver_id = '')
    {
        $user_id = $driver_id != '' ? $driver_id : driver_id();
        return $this->db->where('driver_id', $user_id)->get('assign_students')->num_rows();
    }

    function get_vehicle_by_driver($driver_id = '')
    {
        $user_id = $driver_id != '' ? $driver_id : driver_id();
        return $this->db->where('driver', $user_id)->get('vehicles')->num_rows();
    }

    function get_students_by_vehicle($vehicle_id)
    {
        $assigned_students = $this->db->get_where('assign_students', ['vehicle_id' => $vehicle_id])->result_array();
        return $assigned_students;
    }

    function get_trips_by_driver($driver_id = '')
    {
        $user_id = $driver_id != '' ? $driver_id : driver_id();
        return $this->db->where('driver_id', $user_id)->get('trips')->num_rows();
    }

    function add_new_trip()
    {
        $data['school_id'] = school_id();
        $data['driver_id'] = driver_id();
        $data['vehicle_id'] = $this->input->post('vehicle_id');
        $data['start_time'] = time();

        // check if a vehicle is empty or not
        $assigned_students = $this->get_students_by_vehicle($data['vehicle_id']);
        if (count($assigned_students) < 1) {
            return json_encode(['status' => false, 'notification' => get_phrase('selected_vehicle_is_empty')]);
        }

        // check driver has another trip or not
        $trip_details = $this->db->where(['driver_id' => driver_id(), 'status' => 1])->order_by('id', 'desc')->get('trips')->row_array();
        if ($trip_details) {
            return json_encode(['status' => false, 'notification' => get_phrase('another_trip_is_going_on')]);
        }

        $this->db->insert('trips', $data);
        return json_encode(['status' => true, 'notification' => get_phrase('trip_added_successfully'), 'refresh' => true]);
    }

    function end_trip($param)
    {
        $status = $refresh = false;
        $msg = 'data_not_found';
        if (is_numeric($param) && $param > 0) {
            $trip_details = $this->db->where('id', $param)->get('trips')->row_array();
            if ($trip_details) {
                $this->db->where('id', $param)->update('trips', ['end_time' => time(), 'status' => 0]);
                $msg = 'trip_has_been_completed';
                $refresh = $status = true;
            }
        }

        $response = ['status' => $status, 'notification' => get_phrase($msg), 'refresh' => $refresh];
        return json_encode($response);
    }

    function test()
    {
        return 'driver model';
    }
}
