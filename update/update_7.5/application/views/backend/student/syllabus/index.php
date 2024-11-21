<?php $student_data = $this->user_model->get_logged_in_student_details(); ?>
<!--title-->
<div class="row ">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body py-2">
        <h4 class="page-title d-inline-block"><i class="mdi mdi-chart-timeline title_icon"></i> <?php echo get_phrase('syllabus'); ?></h4>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-1 mb-1"></div>
                    <div class="col-md-4 mb-1">
                        <select name="class" id="class_id" class="form-control select2" data-toggle = "select2" required>
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
                <div class="col-md-4 mb-1">
                    <select name="section" id="section_id" class="form-control select2" data-toggle = "select2" required>
                        <option value=""><?php echo get_phrase('select_section'); ?></option>
                        <option value="<?php echo $student_data['section_id']; ?>"><?php echo $student_data['section_name']; ?></option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-block btn-secondary" onclick="filter_syllabus()" ><?php echo get_phrase('filter'); ?></button>
                </div>
            </div>
            <div class="syllabus_content">
                <?php  include 'list.php'; ?>
            </div>
        </div>
    </div>
</div>
</div>
<script>

$('document').ready(function(){
    $('select.select2:not(.normal)').each(function () { $(this).select2({ dropdownParent: '#right-modal' }); }); //initSelect2(['#class_id', '#section_id']);
});

function classWiseSection(classId) {
    $.ajax({
        url: "<?php echo route('section/list/'); ?>"+classId,
        success: function(response){
            $('#section_id').html(response);
        }
    });
}

function filter_syllabus(){
    var class_id = $('#class_id').val();
    var section_id = $('#section_id').val();
    if(class_id != "" && section_id!= ""){
        showAllSyllabuses();
    }else{
        toastr.error('<?php echo get_phrase('please_select_a_class_and_section'); ?>');
    }
}

var showAllSyllabuses = function () {
    var class_id = $('#class_id').val();
    var section_id = $('#section_id').val();
    if(class_id != "" && section_id!= ""){
        $.ajax({
            url: '<?php echo route('syllabus/list/') ?>'+class_id+'/'+section_id,
            success: function(response){
                $('.syllabus_content').html(response);
                initDataTable('basic-datatable');
            }
        });
    }
}
</script>
