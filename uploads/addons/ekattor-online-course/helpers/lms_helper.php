<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
*  @author   : Creativeitem
*  date      : November, 2019
*  Ekattor School Management System With Addons
*  http://codecanyon.net/user/Creativeitem
*  http://support.creativeitem.com
*/

if ( ! function_exists('get_video_extension')){
    // Checks if a video is youtube, vimeo or any other
    function get_video_extension($url) {
        if (strpos($url, '.mp4') > 0) {
            return 'mp4';
        } elseif (strpos($url, '.webm') > 0) {
            return 'webm';
        } else {
            return 'unknown';
        }
    }
}

if ( ! function_exists('lesson_progress')){
    function lesson_progress($lesson_id = "", $user_id = "") {
        $CI =&  get_instance();
        $CI->load->database();
        if ($user_id == "") {
            $user_id = $CI->session->userdata('user_id');
        }
        $user_details = $CI->user_model->get_all_users($user_id)->row_array();
        $watch_history_array = json_decode($user_details['watch_history'], true);
        for ($i = 0; $i < count($watch_history_array); $i++) {
          $watch_history_for_each_lesson = $watch_history_array[$i];
          if ($watch_history_for_each_lesson['lesson_id'] == $lesson_id) {
              return $watch_history_for_each_lesson['progress'];
          }
        }
        return 0;
    }
}

// Human readable time
if ( ! function_exists('readable_time_for_humans')){
    function readable_time_for_humans($duration) {
        if ($duration) {
            $duration_array = explode(':', $duration);
            $hour   = $duration_array[0];
            $minute = $duration_array[1];
            $second = $duration_array[2];
            if ($hour > 0) {
                $duration = $hour.' '.get_phrase('hr').' '.$minute.' '.get_phrase('min');
            }elseif ($minute > 0) {
                if ($second > 0) {
                    $duration = ($minute+1).' '.get_phrase('min');
                }else{
                    $duration = $minute.' '.get_phrase('min');
                }
            }elseif ($second > 0){
                $duration = $second.' '.get_phrase('sec');
            }else {
                $duration = '00:00';
            }
        }else {
            $duration = '00:00';
        }
        return $duration;
    }
}

if ( ! function_exists('course_progress')){
    function course_progress($course_id = "", $user_id = "") {
        $CI =&  get_instance();
        $CI->load->database();
        if ($user_id == "") {
            $user_id = $CI->session->userdata('user_id');
        }
        $watch_history = $CI->user_model->get_user_details($user_id, 'watch_history');

        // this array will contain all the completed lessons from different different courses by a user
        $completed_lessons_ids = array();

        // this variable will contain number of completed lessons for a certain course. Like for this one the course_id
        $lesson_completed = 0;

        // User's watch history
        $watch_history_array = json_decode($watch_history, true);
        // desired course's lessons
        $lessons_for_that_course = $CI->lms_model->get_lessons('course', $course_id);
        // total number of lessons for that course
        $total_number_of_lessons = $lessons_for_that_course->num_rows();
        // arranging completed lesson ids
        for ($i = 0; $i < count($watch_history_array); $i++) {
          $watch_history_for_each_lesson = $watch_history_array[$i];
          if ($watch_history_for_each_lesson['progress'] == 1) {
              array_push($completed_lessons_ids, $watch_history_for_each_lesson['lesson_id']);
          }
        }

        foreach ($lessons_for_that_course->result_array() as $row) {
          if (in_array($row['id'], $completed_lessons_ids)) {
              $lesson_completed++;
          }
        }
        if($total_number_of_lessons == 0){
            $total_number_of_lessons = 1;
        }

        // calculate the percantage of progress
        $course_progress = ($lesson_completed / $total_number_of_lessons) * 100;
        return $course_progress;
    }
}