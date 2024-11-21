<?php $student_data = $this->user_model->get_logged_in_student_details(); ?>
<!--title-->
<div class="row d-print-none">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body py-2">
        <h4 class="page-title"><i class="mdi mdi-calendar-today title_icon"></i> <?php echo get_phrase('daily_attendance'); ?></h4>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="row mt-4 d-print-none">
                <div class="col-md-1 mb-1"></div>
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
                <div class="col-md-2 mb-1">
                    <select name="class" id="class_id_attendance" class="form-control select2" data-bs-toggle="select2" onchange="classWiseSection(this.value)" required>
                    <option value=""><?php echo get_phrase('select_a_class'); ?></option>
                      
                        

                </select>
            </div>
            <div class="col-md-2 mb-1">
                <select name="section" id="section_id" class="form-control select2" data-bs-toggle="select2" required>
                    <option value=""><?php echo get_phrase('select_section'); ?></option>
                    <option value="<?php echo $student_data['section_id']; ?>"><?php echo $student_data['section_name']; ?></option>
                </select>
            </div>
            <div class="col-md-1">
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
    $('select.select2:not(.normal)').each(function () { $(this).select2({ dropdownParent: '#right-modal' }); }); //initSelect2(['#month', '#year', '#class_id', '#section_id']);
});

function classWiseSection(classId) {
    $.ajax({
        url: "<?php echo route('section/list/'); ?>"+classId,
        success: function(response){
            $('#section_id').html(response);
        }
    });
}

function filter_attendance(){
    var month = $('#month').val();
    var year = $('#year').val();
    var class_id = $('#class_id_attendance').val();
    var section_id = $('#section_id').val();
    if(class_id != "" && section_id != "" && month != "" && year != ""){
        getDailtyAttendance();
    }else{
        toastr.error('<?php echo get_phrase('please_select_in_all_fields !'); ?>');
    }
}

var getDailtyAttendance = function () {
    var month = $('#month').val();
    var year = $('#year').val();
    var class_id = $('#class_id_attendance').val();
    var section_id = $('#section_id').val();
    var school_id = $('#school_id').val();
    // Récupérer le nom et la valeur du jeton CSRF depuis l'input caché
    var csrfName = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').attr('name');
    var csrfHash = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').val();
    if(class_id != "" && section_id != "" && month != "" && year != ""){
        $.ajax({
            type: 'POST',
            url: '<?php echo route('attendance/filter') ?>',
            data: {month : month, year : year, class_id : class_id, section_id : section_id ,school_id : school_id , [csrfName]: csrfHash},
            dataType: 'json',
            success: function(response){
                $('.attendance_content').html(response.status);
                // Mettre à jour le jeton CSRF avec le nouveau jeton renvoyé dans la réponse
                var newCsrfName = response.csrf.csrfName;
                var newCsrfHash = response.csrf.csrfHash;
                $('input[name="' + newCsrfName + '"]').val(newCsrfHash); // Mise à jour du token CSRF
                initDataTable('basic-datatable');
            }
        });
    }
}

function schoolWiseClasse(school_id) {
    $.ajax({
        url: "<?php echo route('academy/list/'); ?>"+school_id,
        success: function(response){
            $('#class_id_attendance').html(response);
        }
    });
}
</script>
