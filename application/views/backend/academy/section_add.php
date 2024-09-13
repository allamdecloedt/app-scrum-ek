<form action="<?php echo site_url('addons/courses/course_sections/'.$param1.'/add'); ?>" method="post">
    <div class="form-group">
        <label for="title"><?php echo get_phrase('title'); ?><span class="required"> * </span></label>
        <input class="form-control" type="text" name="title" id="title" required>
        <small class="text-muted"><?php echo get_phrase('provide_a_section_name'); ?></small>
    </div>
    <div class="text-right">
        <button class = "btn btn-success" type="submit" name="button"><?php echo get_phrase('submit'); ?></button>
    </div>
</form>
