<?php if($working_page == 'filter'): ?>
    <!--title-->
    <div class="row d-print-none">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body py-2">
                    <h4 class="page-title d-inline-block">
                        <i class="mdi mdi-calendar-today title_icon"></i> <?php echo get_phrase('student'); ?>
                    </h4>
                    <a href="<?php echo route('student/create'); ?>" class="btn btn-outline-primary btn-rounded alignToTitle float-end mt-1"> <i class="mdi mdi-plus"></i> <?php echo get_phrase('add_new_student'); ?></a>
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>

    <div class="row d-print-none">
        <div class="col-12">
            <div class="card ">
                <div class="row mt-3">
                    <div class="col-md-1 mb-1"></div>
                    <div class="col-md-4 mb-1">
                        <select name="class" id="class_id" class="form-control select2" data-toggle = "select2" required onchange="classWiseSection(this.value)">
                            <option value=""><?php echo get_phrase('select_a_class'); ?></option>
                            <?php
                            $classes = $this->db->get_where('classes', array('school_id' => school_id()))->result_array();
                            $school_id = school_id();
                            foreach($classes as $class){
                                $this->db->where('class_id', $class['id']); 
                                $this->db->where('school_id', $school_id);
                                $total_student = $this->db->get('enrols');
                                ?>
                                <option value="<?php echo $class['id']; ?>" <?php if($class['id'] == $class_id) echo 'selected'; ?>>
                                    <?php echo $class['name']; ?>
                                    <?php echo "(".$total_student->num_rows().")"; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-1">
                        <select name="section" id="section_id" class="form-control select2" data-toggle = "select2" required>
                            <?php if($class_id !=""){
                                $sections = $this->db->get_where('sections', array('class_id' => $class_id))->result_array(); ?>
                                <?php foreach($sections as $section): ?>
                                    <option value="<?php echo $section['id']; ?>" <?php if($section['id'] == $section_id) echo 'selected'; ?>><?php echo $section['name']; ?></option>
                                <?php endforeach; ?>
                            <?php } else { ?>
                                <option value=""><?php echo get_phrase('select_section'); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-block btn-secondary" onclick="filter_student()" ><?php echo get_phrase('filter'); ?></button>
                    </div>
                </div>
                <div class="card-body student_content">
                    <?php if($class_id !="") { ?>
                        <?php include 'list.php'; ?>
                    <?php } else { ?>
                        <div class="empty_box text-center">
                            <img class="mb-3" width="150px" src="<?php echo base_url('assets/backend/images/empty_box.png'); ?>" />
                            <br>
                            <span class=""><?php echo get_phrase('no_data_found'); ?></span>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?php elseif($working_page == 'create'): ?>
    <?php include 'create.php'; ?>
<?php elseif($working_page == 'edit'): ?>
    <?php include 'update.php'; ?>
<?php endif; ?>

<script>
$('document').ready(function(){

});

function classWiseSection(classId) {
    $.ajax({
        url: "<?php echo route('section/list/'); ?>"+classId,
        success: function(response){
            $('#section_id').html(response);
        }
    });
}

function filter_student(){
    var class_id = $('#class_id').val();
    var section_id = $('#section_id').val();
    if(class_id != "" && section_id!= ""){
        showAllStudents();
    }else{
        toastr.error('<?php echo get_phrase('please_select_a_class_and_section'); ?>');
    }
}

var showAllStudents = function() {
    var class_id = $('#class_id').val();
    var section_id = $('#section_id').val();
    if(class_id != "" && section_id!= ""){
        $.ajax({
            url: '<?php echo route('student/filter/') ?>'+class_id+'/'+section_id,
            success: function(response){
                $('.student_content').html(response);
            }
        });
    }
}
</script>
