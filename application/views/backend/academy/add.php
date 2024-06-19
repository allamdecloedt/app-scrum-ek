<?php $school_id = school_id(); ?>
<form method="POST" class="d-block responsive_media_query" action="<?php echo site_url('student/online_admission/assigned'); ?>">

    <input type="hidden" name="student_id" value="<?php echo $param1; ?>">
    <input type="hidden" name="school_id" value="<?php echo $param3; ?>">
    <input type="hidden" name="class_id" id="class_id" value="<?php echo $param2; ?>">
    


    <div class="form-group row mb-3">
        <div class="col-md-12" id = "section_content_2">
            <label for="section_id_on_academy"><?php echo get_phrase('section'); ?></label>
            <select name="section_id" id="section_id_on_academy" class="form-control select2" data-bs-toggle="select2" required >
                <option value=""><?php echo get_phrase('select_section'); ?></option>
            </select>
        </div>
    </div>

    <div class="form-group col-md-12 mt-4">
        <button class="btn w-100 btn-primary" type="submit"><?php echo get_phrase('submit'); ?></button>
    </div>
</form>

<script type="text/javascript">

       $(document).ready(function () {
        classWiseSectionOnAcademy($('#class_id').val());
    });
    function classWiseSectionOnAcademy(classId) {
        $.ajax({
            url: "<?php echo route('section/list/'); ?>"+classId,
            success: function(response){
                $('#section_id_on_academy').html(response);
            }
        });
    }
</script>