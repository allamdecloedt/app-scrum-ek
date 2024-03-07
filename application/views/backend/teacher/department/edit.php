<?php $departments = $this->db->get_where('departments', array('id' => $param1))->result_array(); ?>
<?php foreach($departments as $department){ ?>
<form method="POST" class="d-block ajaxForm" action="<?php echo route('department/update/'.$param1); ?>">
    <div class="form-row">
        <div class="form-group mb-1">
            <label for="name"><?php echo get_phrase('department_name'); ?></label>
            <input type="text" class="form-control" value="<?php echo $department['name']; ?>" id="name" name = "name" required>
            <small id="name_help" class="form-text text-muted"><?php echo get_phrase('provide_department_name'); ?></small>
        </div>

        <div class="form-group  col-md-12">
            <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('update_department'); ?></button>
        </div>
    </div>
</form>
<?php } ?>

<script>
$(".ajaxForm").validate({}); // Jquery form validation initialization
$(".ajaxForm").submit(function(e) {
    var form = $(this);
    ajaxSubmit(e, form, showAllDepartments);
});
</script>
