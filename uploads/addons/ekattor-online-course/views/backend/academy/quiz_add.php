<?php $course_sections = $this->lms_model->get_section('course', $param1)->result_array(); ?>
<form action="<?php echo site_url('addons/courses/quizes/'.$param1.'/add'); ?>" method="post">
    <div class="form-group mb-2">
        <label for="title"><?php echo get_phrase('quiz_title'); ?></label>
        <input class="form-control" type="text" name="title" id="title" required>
    </div>
    <div class="form-group mb-2">
        <label for="section_id"><?php echo get_phrase('section'); ?></label>
        <select class="form-control select2" data-toggle="select2" name="section_id" id="section_id" required>
            <?php foreach ($course_sections as $section): ?>
                <option value="<?php echo $section['id']; ?>"><?php echo $section['title']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group mb-2">
        <label><?php echo get_phrase('instruction'); ?></label>
        <textarea name="summary" class="form-control"></textarea>
    </div>
    <div class="text-center">
        <button class = "btn btn-success" type="submit" name="button"><?php echo get_phrase('submit'); ?></button>
    </div>
</form>
<script type="text/javascript">
$(document).ready(function() {
    $('.select2').select2({dropdownParent: $('#scrollable-modal')});
});
</script>
