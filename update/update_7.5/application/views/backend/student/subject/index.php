<?php $student_data = $this->user_model->get_logged_in_student_details(); ?>
<!--title-->
<div class="row ">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body py-2">
        <h4 class="page-title d-inline-block"><i class="mdi mdi-book-open-page-variant title_icon"></i> <?php echo get_phrase('subject'); ?></h4>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="row mt-3">
                <div class="col-md-3"></div>
                <div class="col-md-4">
                    <select name="class_id" id="class_id" class="form-control select2" data-toggle = "select2" required>
                        <?php 
                        $school_id = school_id();
                        $this->db->where('class_id', $student_data['class_id']);
                        $this->db->where('school_id', $school_id);
                        $total_student = $this->db->get('enrols');
                        ?>
                        <option value=""><?php echo get_phrase('select_a_class'); ?></option>
                        <option value="<?php echo $student_data['class_id']; ?>">
                            <?php echo $student_data['class_name']; ?>
                            <?php echo "(".$total_student->num_rows().")"; ?>
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-block btn-secondary" onclick="filter_class()" ><?php echo get_phrase('filter'); ?></button>
                </div>
            </div>
            <div class="card-body subject_content">
                <?php include 'list.php'; ?>
            </div>
        </div>
    </div>
</div>


<script>
function filter_class(){
    var class_id = $('#class_id').val();
    if(class_id != ""){
        showAllSubjects();
    }else{
        toastr.error('<?php echo get_phrase('please_select_a_class'); ?>');
    }
}

var showAllSubjects = function () {
    var class_id = $('#class_id').val();
    if(class_id != ""){
        $.ajax({
            url: '<?php echo route('subject/list/') ?>'+class_id,
            success: function(response){
                $('.subject_content').html(response);
            }
        });
    }
}
</script>
