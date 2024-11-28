<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*  @author   : Creativeitem
*  date      : November, 2019
*  Ekattor School Management System With Addons
*  http://codecanyon.net/user/Creativeitem
*  http://support.creativeitem.com
*/

class Lessons extends CI_Controller {
    public function __construct(){

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
        $this->load->model('addons/Lms_model','lms_model');
        $this->load->model('addons/Video_model','video_model');

        $user_login_type = $this->session->userdata('user_login_type');
        if($user_login_type != 1)
        redirect(site_url('login'), 'refresh');
    }

    public function index(){

    }

    public function play($slug = "", $course_id = "", $lesson_id = "") {
        $course_details = $this->lms_model->get_course_by_id($course_id);
        $sections = $this->lms_model->get_section('course', $course_id);
        if ($sections->num_rows() > 0) {
            $page_data['sections'] = $sections->result_array();
            if ($lesson_id == "") {
                $default_section = $sections->row_array();
                $page_data['section_id'] = $default_section['id'];
                $lessons = $this->lms_model->get_lessons('section', $default_section['id']);
                if ($lessons->num_rows() > 0) {
                    $default_lesson = $lessons->row_array();
                    $lesson_id = $default_lesson['id'];
                    $page_data['lesson_id']  = $default_lesson['id'];
                }else {
                    $page_data['page_name'] = 'empty';
                    $page_data['page_title'] = get_phrase('no_lesson_found');
                    $page_data['page_body'] = get_phrase('no_lesson_found');
                }
            }else {
                $page_data['lesson_id']  = $lesson_id;
                $section_id = $this->db->get_where('lesson', array('id' => $lesson_id))->row('section_id');
                $page_data['section_id'] = $section_id;
            }

        }else {
            $page_data['sections'] = array();
            $page_data['page_name'] = 'empty';
            $page_data['page_title'] = get_phrase('no_section_found');
            $page_data['page_body'] = get_phrase('no_section_found');
        }


        $page_data['course_id']  = $course_id;
        $page_data['page_name']  = 'lessons';
        $page_data['page_title'] = $course_details['title'];
        $this->load->view('lessons/index', $page_data);
    }

    public function submit_quiz($from = "") {
        $submitted_quiz_info = array();
        $container = array();
        $quiz_id = $this->input->post('lesson_id');
        $quiz_questions = $this->lms_model->get_quiz_questions($quiz_id)->result_array();
        $total_correct_answers = 0;
        foreach ($quiz_questions as $quiz_question) {
            $submitted_answer_status = 0;
            $correct_answers = json_decode($quiz_question['correct_answers']);
            $submitted_answers = array();
            foreach ($this->input->post($quiz_question['id']) as $each_submission) {
                if (isset($each_submission)) {
                    array_push($submitted_answers, $each_submission);
                }
            }
            sort($correct_answers);
            sort($submitted_answers);
            if ($correct_answers == $submitted_answers) {
                $submitted_answer_status = 1;
                $total_correct_answers++;
            }
            $container = array(
                "question_id" => $quiz_question['id'],
                'submitted_answer_status' => $submitted_answer_status,
                "submitted_answers" => json_encode($submitted_answers),
                "correct_answers"  => json_encode($correct_answers),
            );
            array_push($submitted_quiz_info, $container);
            $user_id = $this->session->userdata('user_id');
            $this->db->where('quiz_id', $quiz_id);
            $this->db->where('question_id', $quiz_question['id']);
            $this->db->where('user_id', $user_id);
            $query = $this->db->get('quiz_responses');
            if ($query->num_rows() == 0) {  // Si aucune entrée n'existe, insérer la nouvelle réponse

             // Insérer la réponse dans la table 'quiz_responses'
                $response_data = array(
                    'user_id' => $user_id,
                    'quiz_id' => $quiz_id,
                    'question_id' => $quiz_question['id'],
                    'submitted_answers' => json_encode($submitted_answers),
                    'correct_answers' => json_encode($correct_answers),
                    'submitted_answer_status' => $submitted_answer_status
                );

                $this->db->insert('quiz_responses', $response_data);
            }
        }
        $page_data['submitted_quiz_info']   = $submitted_quiz_info;
        $page_data['total_correct_answers'] = $total_correct_answers;
        $page_data['total_questions'] = count($quiz_questions);
        $this->load->view('lessons/quiz_result', $page_data);
    }
    public function check_result() {
        $submitted_quiz_info = array();
                $container = array();
                $quiz_id = $this->input->post('lesson_id');
                $user_id = $this->session->userdata('user_id'); // Assurez-vous d'obtenir l'ID de l'utilisateur connecté

                // Récupérer les réponses soumises depuis la table 'quiz_responses'
                $this->db->where('user_id', $user_id);
                $this->db->where('quiz_id', $quiz_id);
                $query = $this->db->get('quiz_responses');
                $quiz_responses = $query->result_array();

                // Récupérer les questions du quiz
                $quiz_questions = $this->lms_model->get_quiz_questions($quiz_id)->result_array();
                $total_correct_answers = 0;

                foreach ($quiz_questions as $quiz_question) {
                    $submitted_answer_status = 0;
                    $correct_answers = json_decode($quiz_question['correct_answers']);
                    $submitted_answers = array();

                    // Rechercher les réponses de l'utilisateur pour la question actuelle
                    foreach ($quiz_responses as $response) {
                        if ($response['question_id'] == $quiz_question['id']) {
                            $submitted_answers = json_decode($response['submitted_answers']);
                            $submitted_answer_status = $response['submitted_answer_status'];
                            break;
                        }
                    }

                    if ($correct_answers == $submitted_answers) {
                        $submitted_answer_status = 1;
                        $total_correct_answers++;
                    }
                    
                    $container = array(
                        "question_id" => $quiz_question['id'],
                        "question_title" => $quiz_question['title'], // Optionnel : afficher la question
                        'submitted_answer_status' => $submitted_answer_status,
                        "submitted_answers" => json_encode($submitted_answers),
                        "correct_answers"  => json_encode($correct_answers),
                    );
                    array_push($submitted_quiz_info, $container);
                }

                $page_data['submitted_quiz_info']   = $submitted_quiz_info;
                $page_data['total_correct_answers'] = $total_correct_answers;
                $page_data['total_questions'] = count($quiz_questions);
                $this->load->view('lessons/quiz_result', $page_data);
    }
    public function check_result_pop_up() {
        $submitted_quiz_info = array();
        $container = array();
        $quiz_id = $this->input->post('quiz_id');
        $user_id = $this->input->post('user_id');
        // print_r($quiz_id ) ;die;
        // Vérification des valeurs de $quiz_id et $user_id
        // log_message('debug', 'Quiz ID: ' . $quiz_id);
        // log_message('debug', 'User ID: ' . $user_id);
    
        // Récupérer les réponses soumises depuis la table 'quiz_responses'
        $this->db->where('user_id', $user_id);
        $this->db->where('quiz_id', $quiz_id);
        $query = $this->db->get('quiz_responses');
        $quiz_responses = $query->result_array();
       
        // Vérification des réponses récupérées
        log_message('debug', 'Quiz Responses: ' . print_r($quiz_responses, true));
    
        // Récupérer les questions du quiz
        $quiz_questions = $this->lms_model->get_quiz_questions($quiz_id)->result_array();
        $total_correct_answers = 0;
    
        // Vérification des questions récupérées
        log_message('debug', 'Quiz Questions: ' . print_r($quiz_questions, true));
    
        foreach ($quiz_questions as $quiz_question) {
            $submitted_answer_status = 0;
            $correct_answers = json_decode($quiz_question['correct_answers']);
            $submitted_answers = array();
    
            // Rechercher les réponses de l'utilisateur pour la question actuelle
            foreach ($quiz_responses as $response) {
                if ($response['question_id'] == $quiz_question['id']) {
                    $submitted_answers = json_decode($response['submitted_answers']);
                    $submitted_answer_status = $response['submitted_answer_status'];
                    break;
                }
            }
    
            if ($correct_answers == $submitted_answers) {
                $submitted_answer_status = 1;
                $total_correct_answers++;
            }
    
            $container = array(
                "question_id" => $quiz_question['id'],
                "question_title" => $quiz_question['title'],
                'submitted_answer_status' => $submitted_answer_status,
                "submitted_answers" => json_encode($submitted_answers),
                "correct_answers"  => json_encode($correct_answers),
            );
            array_push($submitted_quiz_info, $container);
        }
    
        $page_data['submitted_quiz_info']   = $submitted_quiz_info;
        $page_data['total_correct_answers'] = $total_correct_answers;
        $page_data['total_questions'] = count($quiz_questions);
    
        // Chargement de la vue
        // $this->load->view('backend/superadmin/quiz/quiz_popup_result', $page_data);

        // Charger la vue mise à jour
		$response_html = $this->load->view('backend/superadmin/quiz/quiz_popup_result', $page_data, TRUE);
		// Préparer le nouveau jeton CSRF
		$csrf = array(
					'csrfName' => $this->security->get_csrf_token_name(),
					'csrfHash' => $this->security->get_csrf_hash(),
				);
			
		// Renvoyer la réponse JSON avec le HTML mis à jour et le nouveau jeton CSRF
		echo json_encode(array('status' => $response_html, 'csrf' => $csrf));
    }
    

}