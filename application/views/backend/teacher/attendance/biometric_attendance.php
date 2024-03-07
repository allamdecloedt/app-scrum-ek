<?php $school_id = school_id(); ?>
<form method="POST" class="d-block ajaxForm responsive_media_query" action="<?php echo site_url('addons/biometric_attendance/biometric_attendance'); ?>" enctype="multipart/form-data">
    <div class="form-row">
        <div class="form-group mb-1">
            <label for="biometric_attendance_file"><?php echo get_phrase('choose_biometric_attendance_file'); ?></label>
            <div class="custom-file-upload d-inline-block">
                <input type="file" class="form-control" id="biometric_attendance_file" name = "biometric_attendance_file" required>
            </div>
        </div>

        <div class="form-group col-md-12 mt-4" id = "updateAttendanceDiv">
            <button class="btn w-100 btn-primary" type="submit"><?php echo get_phrase('upload_file'); ?></button>
        </div>
    </div>
</form>

<script>
initCustomFileUploader();

$(".ajaxForm").validate({}); // Jquery form validation initialization
$(".ajaxForm").submit(function(e) {
    var form = $(this);
    ajaxSubmit(e, form, getDailtyAttendance);
});

</script>
