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
                    
                <div class="col-md-3 mb-1">
                            <select class="form-control select2" data-toggle="select2" name="school_id" id="school_id" onchange="schoolWiseClasse(this.value)">
                                    <option value=""><?php echo get_phrase('select_a_schools'); ?></option>                                      
                                      <?php 
                                        $user_id   = $this->session->userdata('user_id');
                          
                                        $schools =  $this->db->select('*,schools.id as id');
                                        $this->db->from('schools');
                                        $this->db->join('students', 'schools.id = students.school_id', 'left');
                                        $this->db->where('students.user_id', $user_id);
                                        $query = $this->db->get()->result_array();
                                        ?>
                                        <?php foreach ($query as $school): ?>
                                            <option value="<?php echo $school['id']; ?>" <?php if($selected_school_id == $school['id']) echo 'selected'; ?>>   <?php echo  $school['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                
                </div>
                    <div class="col-md-3 mb-1">
                        <select name="class" id="class_id_syllabus" class="form-control select2" data-toggle = "select2" onchange="classWiseSection(this.value)" required>

                            <option value=""><?php echo get_phrase('select_a_class'); ?></option>

                    </select>
                </div>
                <div class="col-md-3 mb-1">
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
    var class_id = $('#class_id_syllabus').val();
    var section_id = $('#section_id').val();
    if(class_id != "" && section_id!= ""){
        showAllSyllabuses();
    }else{
        toastr.error('<?php echo get_phrase('please_select_a_class_and_section'); ?>');
    }
}

var showAllSyllabuses = function () {
    var class_id = $('#class_id_syllabus').val();
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

function schoolWiseClasse(school_id) {
    $.ajax({
        url: "<?php echo route('academy/list/'); ?>"+school_id,
        success: function(response){
            $('#class_id_syllabus').html(response);
        }
    });
}
</script>
