<?php $student_lists = $this->user_model->get_student_list_of_logged_in_parent(); ?>
<!--title-->
<div class="row d-print-none">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body">
        <h4 class="page-title"><i class="mdi mdi-calendar-today title_icon"></i> <?php echo get_phrase('daily_attendance'); ?></h4>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="row mt-3 d-print-none">
                <div class="col-md-2 mb-1"></div>
                <div class="col-md-2 mb-1">
                    <select name="month" id="month" class="form-control select2" data-bs-toggle="select2" required>
                        <option value=""><?php echo get_phrase('select_a_month'); ?></option>
                        <option value="Jan"<?php if(date('M') == 'Jan') echo 'selected'; ?>><?php echo get_phrase('january'); ?></option>
                        <option value="Feb"<?php if(date('M') == 'Feb') echo 'selected'; ?>><?php echo get_phrase('february'); ?></option>
                        <option value="Mar"<?php if(date('M') == 'Mar') echo 'selected'; ?>><?php echo get_phrase('march'); ?></option>
                        <option value="Apr"<?php if(date('M') == 'Apr') echo 'selected'; ?>><?php echo get_phrase('april'); ?></option>
                        <option value="May"<?php if(date('M') == 'May') echo 'selected'; ?>><?php echo get_phrase('may'); ?></option>
                        <option value="Jun"<?php if(date('M') == 'Jun') echo 'selected'; ?>><?php echo get_phrase('june'); ?></option>
                        <option value="Jul"<?php if(date('M') == 'Jul') echo 'selected'; ?>><?php echo get_phrase('july'); ?></option>
                        <option value="Aug"<?php if(date('M') == 'Aug') echo 'selected'; ?>><?php echo get_phrase('august'); ?></option>
                        <option value="Sep"<?php if(date('M') == 'Sep') echo 'selected'; ?>><?php echo get_phrase('september'); ?></option>
                        <option value="Oct"<?php if(date('M') == 'Oct') echo 'selected'; ?>><?php echo get_phrase('october'); ?></option>
                        <option value="Nov"<?php if(date('M') == 'Nov') echo 'selected'; ?>><?php echo get_phrase('november'); ?></option>
                        <option value="Dec"<?php if(date('M') == 'Dec') echo 'selected'; ?>><?php echo get_phrase('december'); ?></option>
                    </select>
                </div>
                <div class="col-md-2 mb-1">
                    <select name="year" id="year" class="form-control select2" data-bs-toggle="select2" required>
                        <option value=""><?php echo get_phrase('select_a_year'); ?></option>
                        <?php for($year = 2015; $year <= date('Y'); $year++){ ?>
                            <option value="<?php echo $year; ?>"<?php if(date('Y') == $year) echo 'selected'; ?>><?php echo $year; ?></option>
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

                <div class="col-md-2">
                    <button class="btn btn-block btn-secondary" onclick="filter_attendance()" ><?php echo get_phrase('filter'); ?></button>
                </div>
            </div>
            <div class="card-body attendance_content">
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
    $('select.select2:not(.normal)').each(function () { $(this).select2({ dropdownParent: '#right-modal' }); }); //initSelect2(['#month', '#year', '#student_id']);
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

function filter_attendance(){
    var month = $('#month').val();
    var year = $('#year').val();
    var class_id = $('#class_id').val();
    var section_id = $('#section_id').val();
    var student_id = $('#student_id').val();
    if(class_id != "" && section_id != "" && month != "" && year != "" && student_id != ""){
        getDailtyAttendance();
    }else{
        toastr.error('<?php echo get_phrase('please_select_in_all_fields !'); ?>');
    }
}

var getDailtyAttendance = function () {
    var month = $('#month').val();
    var year = $('#year').val();
    var class_id = $('#class_id').val();
    var section_id = $('#section_id').val();
    var student_id = $('#student_id').val();
    if(class_id != "" && section_id != "" && month != "" && year != "" && student_id != ""){
        $.ajax({
            type: 'POST',
            url: '<?php echo route('attendance/filter') ?>',
            data: {month : month, year : year, class_id : class_id, section_id : section_id, student_id : student_id},
            success: function(response){
                $('.attendance_content').html(response);
                initDataTable('basic-datatable');
            }
        });
    }
}
</script>
