<?php
    $course_details = $this->lms_model->get_course_by_id($param2);
    $section_details = $this->lms_model->get_section('section', $param1)->row_array();
?>
<form action="<?php echo site_url('addons/courses/course_sections/'.$param2.'/edit/'.$param1); ?>" method="post">
    <div class="form-group">
        <label for="title"><?php echo get_phrase('title'); ?></label>
        <input class="form-control" type="text" name="title" id="title" value="<?php echo $section_details['title']; ?>" required>
        <small class="text-muted"><?php echo get_phrase('provide_a_section_name'); ?></small>
    </div>
    <div class="text-right">
        <button class = "btn btn-success" type="submit" name="button"><?php echo get_phrase('submit'); ?></button>
    </div>
</form>
