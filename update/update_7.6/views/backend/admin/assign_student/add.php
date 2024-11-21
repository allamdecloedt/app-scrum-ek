<?php
$vehicles = $this->db->where('school_id', school_id())->get('vehicles')->result_array();
$classes = $this->db->where('school_id', school_id())->get('classes')->result_array();
$students = $this->db->where('school_id', school_id())->get('students')->result_array();
?>

<form method="POST" class="d-block ajaxForm" action="<?php echo route('assign_student/add'); ?>" enctype="multipart/form-data">
    <div class="form-row">
        <input type="hidden" name="school_id" value="<?php echo school_id(); ?>">
        <label for="assign_by" class="d-block"><?php echo get_phrase('assign_by'); ?></label>
        <div class="form-group mb-1 d-flex gap-2 mb-2">
            <div class="pointer">
                <input type="radio" class="assign_type" name="assign_by" value="by_class" id="by_class" checked>
                <label for="by_class"><?php echo get_phrase('by_class'); ?></label>
            </div>
            <div class="pointer">
                <input type="radio" class="assign_type" name="assign_by" value="individual" id="individual">
                <label for="individual"><?php echo get_phrase('individual'); ?></label>
            </div>
        </div>

        <div class="form-group mb-1">
            <label for="vehicle"><?php echo get_phrase('vehicle'); ?></label>
            <select name="vehicle" id="vehicle" class="form-control select2" data-toggle="select2" required>
                <option value=""><?php echo get_phrase('select_a_vehicle'); ?></option>
                <?php foreach ($vehicles as $vehicle) : ?>
                <option value="<?php echo $vehicle['id'] ?>"><?php echo $vehicle['vh_num'] ?></option>
                <?php endforeach; ?>
            </select>
            <small id="" class="form-text text-muted"><?php echo get_phrase('select_vehicle'); ?></small>
        </div>

        <div class="form-group mb-1">
            <label for="class_id"><?php echo get_phrase('class'); ?></label>
            <select name="class_id" id="class_id" class="form-control select2" data-toggle="select2" onchange="classWiseStudent(this.value)" required>
                <option value=""><?php echo get_phrase('select_a_class'); ?></option>
                <?php foreach ($classes as $class) : ?>
                <option value="<?php echo $class['id'] ?>"><?php echo $class['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <small id="" class="form-text text-muted"><?php echo get_phrase('select_vehicle'); ?></small>
        </div>

        <div class="form-group mb-1 select_student d-none">
            <label for="student_id"><?php echo get_phrase('Student'); ?></label>
            <select id="student_id" class="form-control select2" data-toggle="select2" required>
                <option value=""><?php echo get_phrase('first_select_class'); ?></option>
            </select>
            <small id="" class="form-text text-muted"><?php echo get_phrase('select_student'); ?></small>
        </div>

        <div class="form-group mt-2 col-md-12">
            <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('assign'); ?></button>
        </div>
    </div>
</form>

<script>
function classWiseStudent(classId) {
    $.ajax({
        url: "<?php echo route('class_wise_student/'); ?>" + classId,
        success: function(response) {
            $('#student_id').html(response);
        }
    });
}
$(document).ready(function() {
    $('select.select2:not(.normal)').each(function() {
        $(this).select2({
            dropdownParent: '#right-modal'
        });
    });

    $('.assign_type').click(function(e) {
        const type = $(this).attr('id');
        if (type == 'individual') {
            $('.select_student').removeClass('d-none');
            $('.select_student select').attr('name', 'student_id');
        } else if (type == 'by_class') {
            $('.select_student').addClass('d-none');
            $('.select_student select').attr('name', '');
        }
    });
});


$(".ajaxForm").validate({}); // Jquery form validation initialization
$(".ajaxForm").submit(function(e) {
    var form = $(this);
    ajaxSubmit(e, form, showAllAssignedStudents);
});
</script>
