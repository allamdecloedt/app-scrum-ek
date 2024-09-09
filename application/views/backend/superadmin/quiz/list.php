<div class="row mb-3">
    <div class="col-md-4"></div>
    <div class="col-md-4 toll-free-box text-center text-white pb-2" style="background-color: #6c757d; border-radius: 10px;">
        <h4><?php echo get_phrase('manage_quiz'); ?></h4>
        <span><?php echo get_phrase('class'); ?> : <?php echo $this->db->get_where('classes', array('id' => $class_id))->row('name'); ?></span><br>
        <span><?php echo get_phrase('cours'); ?> : <?php echo $this->db->get_where('course', array('id' => $cours_id))->row('title'); ?></span><br>

    </div>
</div>
<?php
$school_id = school_id();
// $quiz_lists = $this->crud_model->get_quiz($quiz_id,"" );
//  print_r($quiz_lists);die;if (count($quiz_lists) > 0):
?>
<?php  ?>
    <table class="table table-bordered table-responsive-sm" width="100%">
        <thead class="thead-dark">
            <tr>
                <th><?php echo get_phrase('student_name'); ?></td>
                <th><?php echo get_phrase('quiz_result'); ?></td>
                <th><?php echo get_phrase('action'); ?></td>
            </tr>
        </thead>
        <tbody>
        <?php 
        $enrols = $this->db->get_where('enrols', array('class_id' => $class_id))->result_array();
        
        foreach ($enrols as $enrol):
            $student = $this->db->get_where('students', array('id' => $enrol['student_id']))->row_array();
            $quiz_lists = $this->crud_model->get_quiz($quiz_id ,$student['user_id'] )->row_array();
                 ?>
                 <input type="hidden" name="lesson_id" value="<?php echo $quiz_id; ?>">
                 <input type="hidden" name="user_id" value="<?php echo $student['user_id']; ?>">
                <tr>
                    <td><?php echo $this->user_model->get_user_details($student['user_id'], 'name'); ?></td>
                    <td>
                    <?php  if($quiz_lists): ?>
                    <?php echo $quiz_lists['correct_responses']; ?>/<?php echo $quiz_lists['total_responses']; ?>
                    <?php  else: ?>
                    <?php echo get_phrase('Not_realized'); ?>
                    <?php endif; ?>
                </td>
                    <td class="text-center" >
                <!-- Icône de l'œil pour voir les détails -->
                <a   
                <?php if (!$quiz_lists): ?> 
                    style="pointer-events: none; opacity: 0.5;" 
                <?php else: ?>
                    onclick="openQuizResultModal(<?php echo $quiz_id; ?>, <?php echo $student['user_id']; ?>);" 
                <?php endif; ?>
                
                
                 class="text-primary">
                    <i class="mdi mdi-beaker" style="font-size: 24px;"></i>
                </a>
            </td>
                    <!-- <td class="text-center"><button class="btn btn-success" ><i class="mdi mdi-checkbox-marked-circle"></i></button></td> -->
                </tr>
        <?php 
        
        endforeach;
        // endforeach;
        
        ?>
        </tbody>
    </table>

<!--  -->

