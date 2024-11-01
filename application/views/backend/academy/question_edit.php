<?php
    //$param1 = question id and $param2 = quiz id
    $question_details = $this->lms_model->get_quiz_question_by_id($param1)->row_array();
    if ($question_details['options'] != "" || $question_details['options'] != null) {
        $options = json_decode($question_details['options']);
    } else {
        $options = array();
    }
    if ($question_details['correct_answers'] != "" || $question_details['correct_answers'] != null) {
        $correct_answers= json_decode($question_details['correct_answers']);
    } else {
        $correct_answers = array();
    }
?>
<form action="<?php echo site_url('addons/courses/quiz_questions/'.$param2.'/edit/'.$param1); ?>" method="post" id = 'mcq_form'>
    <input type="hidden" name="question_type" value="mcq">
        <!-- Champ caché pour le jeton CSRF -->
        <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
    <div class="form-group mb-2">
        <label for="title"><?php echo get_phrase('question_title'); ?></label>
        <input class="form-control" type="text" name="title" id="title" value="<?php echo $question_details['title']; ?>" required>
    </div>
    <div class="form-group mb-2" id='multiple_choice_question'>
        <label for="number_of_options"><?php echo get_phrase('number_of_options'); ?></label>
        <div class="input-group">
            <input type="number" class="form-control" name="number_of_options" id="number_of_options" data-validate="required" data-message-required="Value Required" min="0"  oninput="showOptions(jQuery(this).val())" value="<?php echo $question_details['number_of_options']; ?>" >
        </div>
    </div>
    <?php for ($i = 0; $i < $question_details['number_of_options']; $i++):?>
        <div class="form-group mb-2 options">
            <label><?php echo get_phrase('option').' '.($i+1);?></label>
            <div class="input-group">
                <input type="text" class="form-control" name = "options[]" id="option_<?php echo $i; ?>" placeholder="<?php echo get_phrase('option_').$i; ?>" required value="<?php echo $options[$i]; ?>">
                <div class="input-group-append">
                    <span class="input-group-text d-block">
                        <input type='checkbox' name = "correct_answers[]" value = <?php echo ($i+1); ?> <?php if(in_array(($i+1), $correct_answers)) echo 'checked'; ?>>
                    </span>
                </div>
            </div>
        </div>
    <?php endfor;?>
    <div class="text-center">
        <button class = "btn btn-success" id = "submitButton" type="button" name="button" data-dismiss="modal"><?php echo get_phrase('submit'); ?></button>
    </div>
</form>

<script type="text/javascript">
    $('#submitButton').click( function(event) {

        var isValid = true;
        var message_title = "";
        var message_number_of_options = "";
        var correct_answers = "";
        

        console.log()
        // alert('1');
        // Vérifier que le titre de la question n'est pas vide
        if ($('#title').val().trim() === "") {
            isValid = false;
            // alert('2');
            message_title = '<?php echo get_phrase('the_question_title_is_required'); ?>';
            // confirmModal_alert('<?php echo get_phrase('the_question_title_is_required'); ?>');
      
        }

        // Vérifier que le nombre d'options est supérieur à 0
        if ($('#number_of_options').val() <= 0) {
            isValid = false;
            message_number_of_options = '<?php echo get_phrase('number_of_options_must_be_greater_than_zero'); ?>';
            
          
        }

            // Vérifier si au moins une case à cocher est cochée
            var atLeastOneChecked = false;
            $('input[name="correct_answers[]"]').each(function() {
                if ($(this).is(':checked')) {
                    atLeastOneChecked = true;
                }
            });

            if (!atLeastOneChecked) {
                isValid = false;
                message_number_of_options = '<?php echo get_phrase('You_must_select_at_least_one_correct_answer'); ?>';
            }


        if(message_title != "" || message_number_of_options != "" || message_number_of_options != "" ){

            confirmModal_alert(message_title+" "+message_number_of_options+" "+message_number_of_options);
        }else{

                $.ajax({
                    url: '<?php echo site_url('addons/courses/quiz_questions/'.$param2.'/edit/'.$param1); ?>',
                    type: 'post',
                    data: $('form#mcq_form').serialize(),
                    dataType: 'json',
                    success: function(response) {
                            // Mettre à jour le jeton CSRF avec le nouveau jeton renvoyé dans la réponse
                            var newCsrfName = response.csrf.csrfName;
                            var newCsrfHash = response.csrf.csrfHash;
                            $('input[name="' + newCsrfName + '"]').val(newCsrfHash); // Mise à jour du token CSRF

                        if (response.html == 1) {
                            
                            success_notify('<?php echo get_phrase('question_has_been_updated'); ?>');

                            $('#scrollable-modal').modal('hide');
        
                        }else {
                            error_notify('<?php echo get_phrase('no_options_can_be_blank_and_there_has_to_be_atleast_one_answer'); ?>');
                        }
                    }
                });
         }
        
        largeModal('<?php echo site_url('modal/popup/academy/quiz_questions/'.$param2); ?>', '<?php echo get_phrase('manage_quiz_questions'); ?>');
    });
</script>