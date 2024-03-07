<form method="POST" class="d-block ajaxForm" action="<?php echo route('session_manager/create'); ?>">
    <div class="form-row">
        <input type="hidden" name="school_id" value="<?php echo school_id(); ?>">
        <div class="form-group mb-1">
            <label for="name"><?php echo get_phrase('session_title'); ?></label>
            <input type="text" class="form-control" id="name" name = "session_title" required>
            <small id="name_help" class="form-text text-muted"><?php echo get_phrase('provide_session_title'); ?></small>
        </div>

        <div class="form-group  col-md-12">
            <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('create_session'); ?></button>
        </div>
    </div>
</form>

<script>
    $(".ajaxForm").validate({}); // Jquery form validation initialization
    $(".ajaxForm").submit(function(e) {
        var form = $(this);
        ajaxSubmit(e, form, showAllSessions);
    });
</script>
