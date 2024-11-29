<form action="" method="" id = 'mcq_form'>
    <!-- Champ caché pour le jeton CSRF -->
     <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
    <input type="hidden" name="question_type" value="mcq">
    <div class="form-group mb-2">
        <label for="title"><?php echo get_phrase('question_title'); ?></label>
        <input class="form-control" type="text" name="title" id="title" >
    </div>
    <div class="form-group mb-2" id='multiple_choice_question'>
        <label for="number_of_options"><?php echo get_phrase('number_of_options'); ?></label>
        <div class="input-group">
            <input type="number" class="form-control" name="number_of_options" id="number_of_options" data-validate="required" data-message-required="Value Required" oninput ="showOptions(jQuery(this).val())"min="0" max="20">
        </div>
    </div>
    <div class="text-center">
        <button class = "btn btn-success" id ="submitButton" type="button" name="button" data-dismiss="modal"><?php echo get_phrase('submit'); ?></button>
    </div>
</form>

<script type="text/javascript">
    $('#submitButton').click( function(event) {
       
        var isValid = true;
        var correct_answers = "";
        

        let messages = [];

        // Titre de la question
        if ($('#title').val().trim() === "") {
            messages.push('<?php echo get_phrase('the_question_title_is_required'); ?>');
        }

        // Nombre d'options
        if ($('#number_of_options').val() <= 0) {
            messages.push('<?php echo get_phrase('number_of_options_must_be_greater_than_zero'); ?>');
        }

        // Au moins une réponse correcte
        let atLeastOneChecked = false;
        $('input[name="correct_answers[]"]').each(function() {
            if ($(this).is(':checked')) {
                atLeastOneChecked = true;
            }
        });
        if (!atLeastOneChecked) {
            messages.push('<?php echo get_phrase('You_must_select_at_least_one_correct_answer'); ?>');
        }

        // Afficher les erreurs si nécessaire
        if (messages.length > 0) {
            confirmModal_alert(messages.join(' , '));
            return;
        }else{


            if (isValid) {
                $.ajax({
                    url: '<?php echo site_url('addons/courses/quiz_questions/'.$param1.'/add'); ?>',
                    type: 'post',
                    data: $('form#mcq_form').serialize(),
                    dataType: 'json',
                    success: function(response) {
                    // Mettre à jour le jeton CSRF avec le nouveau jeton renvoyé dans la réponse
                    var newCsrfName = response.csrf.csrfName;
                    var newCsrfHash = response.csrf.csrfHash;
                    $('input[name="' + newCsrfName + '"]').val(newCsrfHash); // Mise à jour du token CSRF

                    
                    if (response.html == 1) {
                        success_notify('<?php echo get_phrase('question_has_been_added'); ?>');

                         // Fermer le modal après succès
                    $('#scrollable-modal').modal('hide');

                    }else {
                        error_notify('<?php echo get_phrase('no_options_can_be_blank_and_there_has_to_be_atleast_one_answer'); ?>');
                    }
                    }
                });
              
            }
        }
        largeModal('<?php echo site_url('modal/popup/academy/quiz_questions/'.$param1); ?>', '<?php echo get_phrase('manage_quiz_questions'); ?>');
    });
</script>
