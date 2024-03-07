<?php $student_lists = $this->user_model->get_student_list_of_logged_in_parent(); ?>
<!--title-->
<div class="row ">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body py-2">
        <h4 class="page-title d-inline-block"> <i class="mdi mdi-format-list-numbered title_icon"></i> <?php echo get_phrase('manage_marks'); ?> </h4>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="row mt-3">
                <div class="col-md-2 mb-1"></div>
                <div class="col-md-2 mb-1">
                    <select name="exam" id="exam_id" class="form-control select2" data-toggle = "select2" required>
                        <option value=""><?php echo get_phrase('select_a_exam'); ?></option>
                        <?php $school_id = school_id();
                        $exams = $this->db->get_where('exams', array('school_id' => $school_id, 'session' => active_session()))->result_array();
                        foreach($exams as $exam){ ?>
                            <option value="<?php echo $exam['id']; ?>"><?php echo $exam['name'];?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-2 mb-1">
                    <select name="student_id" id="student_id" name="student_id" class="form-control select2" data-bs-toggle="select2" required onchange="studentWiseClassId(this.value)">
                        <option value=""><?php echo get_phrase('select_a_student'); ?></option>
                        <?php foreach ($student_lists as $student_list): ?>
                            <option value="<?php echo $student_list['id']; ?>"><?php echo $student_list['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <input type="hidden" name="class_id" id = "class_id" value="">
                <input type="hidden" name="section_id" id = "section_id" value="">

                <div class="col-md-2 mb-1">
                    <select name="subject" id="subject_id" class="form-control select2" data-toggle = "select2" required>
                        <option value=""><?php echo get_phrase('select_subject'); ?></option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-block btn-secondary" onclick="filter_attendance()" ><?php echo get_phrase('filter'); ?></button>
                </div>
            </div>
            <div class="card-body mark_content">
                <div class="empty_box text-center">
                    <img class="mb-3" width="150px" src="<?php echo base_url('assets/backend/images/empty_box.png'); ?>" />
                    <br>
                    <span class=""><?php echo get_phrase('no_data_found'); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$('document').ready(function(){
    $('select.select2:not(.normal)').each(function () { $(this).select2({ dropdownParent: '#right-modal' }); }); //initSelect2(['#student_id', '#exam_id', '#subject_id']);
});
function studentWiseClassId(student_id) {
    if (student_id > 0) {
        $.ajax({
            url: "<?php echo route('get_student_details_by_id/class_id/'); ?>"+student_id,
            success: function(response){
                $('#class_id').val(response);
                studentWiseSectionId(student_id);
                classWiseSubject(response);
            }
        });
    }else{
        $('#class_id').val("");
        $('#section_id').val("");
    }
}

function studentWiseSectionId(student_id) {
    $.ajax({
        url: "<?php echo route('get_student_details_by_id/section_id/'); ?>"+student_id,
        success: function(response){
            $('#section_id').val(response);
        }
    });
}

function classWiseSubject(classId) {
    $.ajax({
        url: "<?php echo route('class_wise_subject/'); ?>"+classId,
        success: function(response){
            $('#subject_id').html(response);
        }
    });
}

function filter_attendance(){
    var exam = $('#exam_id').val();
    var class_id = $('#class_id').val();
    var section_id = $('#section_id').val();
    var subject = $('#subject_id').val();
    var student_id = $('#student_id').val();
    if(class_id != "" && section_id != "" && exam != "" && subject != "" && student_id != ""){
        $.ajax({
            type: 'POST',
            url: '<?php echo route('mark/list') ?>',
            data: {class_id : class_id, section_id : section_id, subject : subject, exam : exam, student_id : student_id},
            success: function(response){
                $('.mark_content').html(response);
            }
        });
    }else{
        toastr.error('<?php echo get_phrase('please_select_in_all_fields !'); ?>');
    }
}
</script>
