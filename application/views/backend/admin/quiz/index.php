<!--title-->
<div class="row ">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body py-2">
        <h4 class="page-title d-inline-block">
          <i class="mdi mdi-format-list-numbered title_icon"></i><?php echo get_phrase('manage_quiz'); ?>
        </h4>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>
<div class="row ">
    <div class="col-12">
        <div class="card ">
            <div class="row mt-3 justify-content-center">
                <div class="col-md-1 mb-1"></div>

                <div class="col-md-2 mb-1">
                    <select name="class" id="class_id_cours" class="form-control select2" data-toggle = "select2" required onchange="classWiseCours(this.value)">
                        <option value=""><?php echo get_phrase('select_a_class'); ?></option>
                        <?php
                        $classes = $this->db->get_where('classes', array('school_id' =>  $school_id))->result_array();
                        // $school_id = school_id();
                        foreach($classes as $class){
                            $this->db->where('class_id', $class['id']);
                            $this->db->where('school_id', $school_id);
                            $total_student = $this->db->get('enrols');
                            ?>
                            <option value="<?php echo $class['id']; ?>">
                                <?php echo $class['name']; ?>
                                <?php echo "(".$total_student->num_rows().")"; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-2 mb-1">
                    <select name="quiz" id="quiz_id" class="form-control select2" data-toggle = "select2" required>
                        <option value=""><?php echo get_phrase('select_quiz'); ?></option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-block btn-secondary" onclick="filter_attendance()" ><?php echo get_phrase('filter'); ?></button>
                </div>
            </div>
            <div class="card-body quiz_content">
                <div class="empty_box text-center">
                    <img class="mb-3" width="150px" src="<?php echo base_url('assets/backend/images/empty_box.png'); ?>" />
                    <br>
                    <span class=""><?php echo get_phrase('no_data_found'); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Fenêtre modale -->
<div class="modal fade" id="quizResultModal" tabindex="-1" role="dialog" aria-labelledby="quizResultModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="quizResultModalLabel">Quiz Result</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="quiz-result-content">
        <!-- Le contenu des résultats du quiz sera chargé ici -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeModal()">Close</button>
      </div>
    </div>
  </div>
</div>

<?php
		// include APPPATH.'views/lessons/lessons.php';
		// include APPPATH.'views/lessons/includes_bottom.php';
		// include 'lessons/common_scripts.php';
	?>
<!-- Bootstrap CSS -->
<!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> -->
<!-- jQuery -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
<!-- Bootstrap JS -->
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->

<script>


$('document').ready(function(){
    $('select.select2:not(.normal)').each(function () { $(this).select2({ dropdownParent: '#right-modal' }); }); //initSelect2(['#class_id', '#exam_id', '#section_id', '#subject_id']);
});

function classWiseCours(classId) {

    $.ajax({
        url: "<?php echo route('quiz/list/'); ?>"+classId,
        success: function(response){
            $('#quiz_id').html(response); 
        }
    });
}


function filter_attendance(){
  
    var class_id = $('#class_id_cours').val();
    var cours_id = $('#cours_id').val();
    var quiz_id = $('#quiz_id').val();
    // Récupérer le nom et la valeur du jeton CSRF depuis l'input caché
    var csrfName = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').attr('name');
    var csrfHash = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').val();

    if(class_id != "" && cours_id != "" && quiz_id != "" ){
        $.ajax({
            type: 'POST',
            url: '<?php echo route('quiz_result/list') ?>',
            data: {class_id : class_id, cours_id : cours_id, quiz_id : quiz_id ,[csrfName]: csrfHash},
            dataType: 'json',
            success: function(response){
                $('.quiz_content').html(response.status);
                // Mettre à jour le jeton CSRF avec le nouveau jeton renvoyé dans la réponse
                var newCsrfName = response.csrf.csrfName;
                var newCsrfHash = response.csrf.csrfHash;
                $('input[name="' + newCsrfName + '"]').val(newCsrfHash); // Mise à jour du token CSRF
            }
        });
    }else{
        toastr.error('<?php echo get_phrase('please_select_in_all_fields !'); ?>');
    }
}

// function check_result() {
//   quizSubmitted = true;
//   var lesson_id = '<?php echo $lesson_id; ?>';
// //   document.getElementById(lesson_id).checked = true
// //   markThisLessonAsCompleted(lesson_id);
//     $.ajax({
//         url: '<?php echo site_url('addons/lessons/check_result'); ?>',
//         type: 'post',
//         data: $('form#quiz_form').serialize(),
//         success: function(response) {
//             $('#quiz-body').hide();
//             $('#quiz-result').html(response);
//         }
//     });
// }
function openQuizResultModal(quiz_id, user_id) {
    // Ouvrir la fenêtre modale
    $('#quizResultModal').modal('show');
    // Récupérer le nom et la valeur du jeton CSRF depuis l'input caché
    var csrfName = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').attr('name');
    var csrfHash = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').val();
    // alert(quiz_id);
    // Charger les données via AJAX
    $.ajax({
        url: '<?php echo site_url('addons/lessons/check_result_pop_up'); ?>',
        type: 'post',
        data: {
            quiz_id: quiz_id,
            user_id: user_id,
            [csrfName]: csrfHash
        },
        dataType: 'json',
        success: function(response) {
           
            // Injecter le contenu dans la modale
            $('#quiz-result-content').html(response.status);
            // Mettre à jour le jeton CSRF avec le nouveau jeton renvoyé dans la réponse
            var newCsrfName = response.csrf.csrfName;
            var newCsrfHash = response.csrf.csrfHash;
            $('input[name="' + newCsrfName + '"]').val(newCsrfHash); // Mise à jour du token CSRF
        },
        error: function() {
            $('#quiz-result-content').html('<p>Error loading quiz results. Please try again.</p>');
        }
    });
}
function enableNextButton(quizID) {
    $('#next-btn-'+quizID).prop('disabled', false);
}
function closeModal() {
    // document.getElementById("quizResultModal").style.display = "none";
    $('#quizResultModal').modal('hide');
  }
</script>
