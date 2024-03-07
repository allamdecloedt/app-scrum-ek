<div class="row mb-3">
    <div class="col-md-4"></div>
    <div class="col-md-4 toll-free-box text-center text-white pb-2" style="background-color: #6c757d; border-radius: 10px;">
        <h4><?php echo get_phrase('manage_marks'); ?></h4>
        <span><?php echo get_phrase('class'); ?> : <?php echo $this->db->get_where('classes', array('id' => $class_id))->row('name'); ?></span><br>
        <span><?php echo get_phrase('section'); ?> : <?php echo $this->db->get_where('sections', array('id' => $section_id))->row('name'); ?></span><br>
        <span><?php echo get_phrase('subject'); ?> : <?php echo $this->db->get_where('subjects', array('id' => $subject_id))->row('name'); ?></span>
    </div>
</div>
<?php
$school_id = school_id();
$marks = $this->crud_model->get_marks($class_id, $section_id, $subject_id, $exam_id)->result_array();
?>
<?php if (count($marks) > 0): ?>
    <table class="table table-bordered table-responsive-sm" width="100%">
        <thead class="thead-dark">
            <tr>
                <th><?php echo get_phrase('student_name'); ?></td>
                <th><?php echo get_phrase('mark'); ?></td>
                <th><?php echo get_phrase('comment'); ?></td>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach($marks as $mark):
                if ($mark['student_id'] == $student_id):
                $student = $this->db->get_where('students', array('id' => $mark['student_id']))->row_array(); ?>
                    <tr>
                        <td><?php echo $this->user_model->get_user_details($student['user_id'], 'name'); ?></td>
                        <td><input class="form-control readonly" type="number" id="mark-<?php echo $mark['student_id']; ?>" name="mark" placeholder="mark" min="0" value="<?php echo $mark['mark_obtained']; ?>" required></td>
                        <td><input class="form-control readonly" type="text" id="comment-<?php echo $mark['student_id']; ?>" name="comment" placeholder="comment" value="<?php echo $mark['comment']; ?>"></td>
                    </tr>
                <?php endif; ?>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
<?php include APPPATH.'views/backend/empty.php'; ?>
<?php endif; ?>

<script>
    function mark_update(student_id){
        var class_id = '<?php echo $class_id; ?>';
        var section_id = '<?php echo $section_id; ?>';
        var subject_id = '<?php echo $subject_id; ?>';
        var exam_id = '<?php echo $exam_id; ?>';
        var mark = $('#mark-' + student_id).val();
        var comment = $('#comment-' + student_id).val();
        if(subject_id != ""){
            $.ajax({
                type : 'POST',
                url : '<?php echo route('mark/mark_update'); ?>',
                data : {student_id : student_id, class_id : class_id, section_id : section_id, subject_id : subject_id, exam_id : exam_id, mark : mark, comment : comment},
                success : function(response){
                    success_notify('<?php echo get_phrase('mark_hass_been_updated_successfully'); ?>');
                }
            });
        }else{
            toastr.error('<?php echo get_phrase('required_mark_field'); ?>');
        }
    }
</script>
