<?php $student_lists = $this->user_model->get_student_list_of_logged_in_parent(); ?>
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
				<div class="col-md-3 mb-1"></div>
				<div class="col-md-4 mb-1">
					<select name="student_id" id="student_id" name="student_id" class="form-control select2" data-bs-toggle="select2" required onchange="studentWiseClassId(this.value)">
						<option value=""><?php echo get_phrase('select_a_student'); ?></option>
						<?php foreach ($student_lists as $student_list): ?>
							<option value="<?php echo $student_list['id']; ?>"><?php echo $student_list['name']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<input type="hidden" name="class_id" id="class_id" value="">
				<input type="hidden" name="secion_id" id="section_id" value="">
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

$('document').ready(function(){
	$('select.select2:not(.normal)').each(function () { $(this).select2({ dropdownParent: '#right-modal' }); }); //initSelect2(['#student_id']);
});

function studentWiseClassId(student_id) {
	if (student_id > 0) {
		$.ajax({
			url: "<?php echo route('get_student_details_by_id/class_id/'); ?>"+student_id,
			success: function(response){
				$('#class_id').val(response);
				studentWiseSectionId(student_id);
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

function filter_class_routine(){
	var class_id = $('#class_id').val();
	var section_id = $('#section_id').val();
	if(class_id != "" && section_id!= ""){
		getFilteredClassRoutine();
	}else{
		toastr.error('<?php echo get_phrase('please_select_a_class_and_section'); ?>');
	}
}

var getFilteredClassRoutine = function() {
	var class_id = $('#class_id').val();
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
</script>
