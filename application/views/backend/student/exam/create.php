<form method="POST" class="d-block ajaxForm" action="<?php echo route('exam/create'); ?>">
    <div class="form-row">
        <div class="form-group mb-1">
            <label for="exam_name"><?php echo get_phrase('exam_name'); ?></label>
            <input type="text" class="form-control" id="exam_name" name = "exam_name" placeholder="name" required>
            <small id="name_help" class="form-text text-muted"><?php echo get_phrase('provide_exam_name'); ?></small>
        </div>

        <div class="form-group mb-1">
            <label for="starting_date"><?php echo get_phrase('starting_date'); ?></label>
            <input type="text" class="form-control date" id="starting_date" data-bs-toggle="date-picker" data-single-date-picker="true" name = "starting_date" value="<?php echo date('m/d/Y'); ?>" required>
            <small id="name_help" class="form-text text-muted"><?php echo get_phrase('provide_starting_date'); ?></small>
        </div>

        <div class="form-group mb-1">
            <label for="ending_date"><?php echo get_phrase('ending_date'); ?></label>
            <input type="text" class="form-control date" id="ending_date" data-bs-toggle="date-picker" data-single-date-picker="true" name = "ending_date"   value="<?php echo date('m/d/Y'); ?>" required>
            <small id="name_help" class="form-text text-muted"><?php echo get_phrase('provide_ending_date'); ?></small>
        </div>

        <div class="form-group  col-md-12">
            <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('create_exam'); ?></button>
        </div>
    </div>
</form>

<script>
    $(".ajaxForm").validate({}); // Jquery form validation initialization
    $(".ajaxForm").submit(function(e) {
        var form = $(this);
        ajaxSubmit(e, form, showAllExams);
    });
    $("#starting_date" ).daterangepicker();
    $("#ending_date" ).daterangepicker();
</script>
