<?php $school_id = school_id(); ?>
<?php $subjects = $this->db->get_where('subjects', array('id' => $param1))->result_array(); ?>
<?php foreach($subjects as $subject){ ?>
<form method="POST" class="d-block ajaxForm" action="<?php echo route('subject/update/'.$param1); ?>">
    <div class="form-row">
        <div class="form-group mb-1">
            <label for="class"><?php echo get_phrase('class'); ?></label>
            <select name="class_id" id="class_id_on_create" class="form-control select2" data-bs-toggle="select2" required>
                <option value=""><?php echo get_phrase('select_a_class'); ?></option>
                    <?php
                        $classes = $this->db->get_where('classes', array('school_id' => $school_id))->result_array();
                        foreach($classes as $class){
                    ?>
                        <option value="<?php echo $class['id']; ?>" <?php if($class['id'] == $subject['class_id']){ echo 'selected'; } ?>><?php echo $class['name']; ?></option>
                    <?php } ?>
            </select>
            <small id="class_help" class="form-text text-muted"><?php echo get_phrase('select_a_class'); ?></small>
        </div>

        <div class="form-group mb-1">
            <label for="name"><?php echo get_phrase('subject_name'); ?></label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $subject['name']; ?>" required>
            <small id="name_help" class="form-text text-muted"><?php echo get_phrase('provide_subject_name'); ?></small>
        </div>

        <div class="form-group  col-md-12">
            <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('create_subject'); ?></button>
        </div>
    </div>
</form>
<?php } ?>

<script>
$(".ajaxForm").validate({}); // Jquery form validation initialization
$(".ajaxForm").submit(function(e) {
  var form = $(this);
  ajaxSubmit(e, form, showAllSubjects);
});

$(document).ready(function() {
  $('select.select2:not(.normal)').each(function () { $(this).select2({ dropdownParent: '#right-modal' }); }); //initSelect2(['#class_id_on_create']);
});
</script>
