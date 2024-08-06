<option value=""><?php echo get_phrase('select_a_class'); ?></option>
<?php
$user_id = $this->session->userdata('user_id');
$exam = $this->db->get_where('exams', array('id' => $exam_id))->row_array();
$classes = $this->db->get_where('classes', array('school_id' => $exam['school_id']))->result_array();
$student_id = $this->db->get_where('students', array('user_id' => $user_id, 'school_id' => $exam['school_id']))->row('id');

if (count($classes) > 0):
    // Get all enrollments in one query to minimize database hits
    $enrolls = $this->db->select('class_id, COUNT(*) as total_students')
                        ->where('school_id', $exam['school_id'])
                        ->group_by('class_id')
                        ->get('enrols')
                        ->result_array();
    
    // Convert enrollments to a lookup table
    $enrolls_lookup = array_column($enrolls, 'total_students', 'class_id');
    
    foreach ($classes as $class):
        $class_id = $class['id'];
        // Check if the student is enrolled in the current class
        $is_enrolled = $this->db->where('class_id', $class_id)
                                ->where('school_id', $exam['school_id'])
                                ->where('student_id', $student_id)
                                ->count_all_results('enrols') > 0;
        
        if ($is_enrolled):
            $total_students = isset($enrolls_lookup[$class_id]) ? $enrolls_lookup[$class_id] : 0;
?>
            <option value="<?php echo $class_id; ?>"><?php echo $class['name'] . " (" . $total_students . ")"; ?></option>
<?php 
        endif;
    endforeach; 
else: 
?>
    <option value=""><?php echo get_phrase('no_classes_found'); ?></option>
<?php endif; ?>

