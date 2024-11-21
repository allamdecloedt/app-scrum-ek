<form action="<?php echo site_url('addons/courses/quiz_questions/'.$param1.'/add'); ?>" method="post" id = 'mcq_form'>
    <input type="hidden" name="question_type" value="mcq">
    <div class="form-group mb-2">
        <label for="title"><?php echo get_phrase('question_title'); ?></label>
        <input class="form-control" type="text" name="title" id="title" required>
    </div>
    <div class="form-group mb-2" id='multiple_choice_question'>
        <label for="number_of_options"><?php echo get_phrase('number_of_options'); ?></label>
        <div class="input-group">
            <input type="number" class="form-control" name="number_of_options" id="number_of_options" data-validate="required" data-message-required="Value Required" oninput ="showOptions(jQuery(this).val())"min="0" max="20">
        </div>
    </div>
    <div class="text-center">
        <button class = "btn btn-success" id = "submitButton" type="button" name="button" data-dismiss="modal"><?php echo get_phrase('submit'); ?></button>
    </div>
</form>

<script type="text/javascript">
    $('#submitButton').click( function(event) {
        $.ajax({
            url: '<?php echo site_url('addons/courses/quiz_questions/'.$param1.'/add'); ?>',
            type: 'post',
            data: $('form#mcq_form').serialize(),
            success: function(response) {
               if (response == 1) {
                   success_notify('<?php echo get_phrase('question_has_been_added'); ?>');
               }else {
                   error_notify('<?php echo get_phrase('no_options_can_be_blank_and_there_has_to_be_atleast_one_answer'); ?>');
               }
             }
        });
        largeModal('<?php echo site_url('modal/popup/academy/quiz_questions/'.$param1); ?>', '<?php echo get_phrase('manage_quiz_questions'); ?>');
    });
</script>
