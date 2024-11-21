<?php $student_data = $this->user_model->get_logged_in_student_details(); ?>
<!--title-->
<div class="row ">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body py-2">
        <h4 class="page-title d-inline-block"><i class="mdi mdi-calendar-today title_icon"></i> <?php echo get_phrase('class_routine'); ?></h4>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="row mt-3">
				<div class="col-md-1 mb-1"></div>
				<div class="col-md-3 mb-1">
                            <select class="form-control select2" data-toggle="select2" name="school_id" id="school_id" onchange="schoolWiseClasse(this.value)">
                                    <option value=""><?php echo get_phrase('schools'); ?></option>                                      
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
					<select name="class" id="class_id_routine" class="form-control select2" data-bs-toggle="select2" onchange="classWiseSection(this.value)" required>

						<option value=""><?php echo get_phrase('select_a_class'); ?></option>

					</select>
				</div>
				<div class="col-md-3 mb-1">
					<select name="section" id="section_id" class="form-control select2" data-bs-toggle="select2" required>
						<option value=""><?php echo get_phrase('select_section'); ?></option>
						<option value="<?php echo $student_data['section_id']; ?>"><?php echo $student_data['section_name']; ?></option>
					</select>
				</div>
				<div class="col-md-2">
					<button class="btn btn-block btn-secondary" onclick="filter_class_routine()" ><?php echo get_phrase('filter'); ?></button>
				</div>
			</div>
			<div class="card-body class_routine_content">
				<?php include 'list.php'; ?>
			</div>
		</div>
	</div>
</div>

<script>

function classWiseSection(classId) {
	$.ajax({
		url: "<?php echo route('section/list/'); ?>"+classId,
		success: function(response){
			$('#section_id').html(response);
		}
	});
}

function filter_class_routine(){
	var class_id = $('#class_id_routine').val();
	var section_id = $('#section_id').val();
	if(class_id != "" && section_id!= ""){
		getFilteredClassRoutine();
	}else{
		toastr.error('<?php echo get_phrase('please_select_a_class_and_section'); ?>');
	}
}

var getFilteredClassRoutine = function() {
	var class_id = $('#class_id_routine').val();
	var section_id = $('#section_id').val();
	if(class_id != "" && section_id!= ""){
		$.ajax({
			url: '<?php echo route('routine/filter/') ?>'+class_id+'/'+section_id,
			success: function(response){
				$('.class_routine_content').html(response);
			}
		});
	}
}
function schoolWiseClasse(school_id) {
    $.ajax({
        url: "<?php echo route('academy/list/'); ?>"+school_id,
        success: function(response){
            $('#class_id_routine').html(response);
        }
    });
}
</script>
