<form method="POST" class="d-block ajaxForm" action="<?php echo route('grade/create'); ?>">
    <div class="form-row">
        <div class="form-group mb-1">
            <label for="grade"><?php echo get_phrase('grade'); ?></label>
            <input type="text" class="form-control" id="grade" name = "grade" placeholder="<?php echo get_phrase('grade'); ?>" required>
        </div>

        <div class="form-group mb-1">
            <label for="grade_point"><?php echo get_phrase('grade_point'); ?></label>
            <input type="number" class="form-control" id="grade_point" name = "grade_point" placeholder="<?php echo get_phrase('grade_point'); ?>" required>
        </div>

        <div class="form-group mb-1">
            <label for="mark_from"><?php echo get_phrase('mark_from'); ?></label>
            <input type="number" class="form-control" id="mark_from" name = "mark_from" placeholder="<?php echo get_phrase('mark_from'); ?>" required>
        </div>

        <div class="form-group mb-1">
            <label for="mark_upto"><?php echo get_phrase('mark_upto'); ?></label>
            <input type="number" class="form-control" id="mark_upto" name = "mark_upto" placeholder="<?php echo get_phrase('mark_upto'); ?>" required>
        </div>

        <div class="form-group  col-md-12">
            <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('create_grade'); ?></button>
        </div>
    </div>
</form>

<script>
    $(".ajaxForm").validate({}); // Jquery form validation initialization
    $(".ajaxForm").submit(function(e) {
        var form = $(this);
        ajaxSubmit(e, form, showAllGrades);
    });
</script>
