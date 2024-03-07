<?php $school_id = school_id(); ?>
<form method="POST" class="d-block responsive_media_query" action="<?php echo site_url('admin/online_admission/assigned'); ?>">

    <input type="hidden" name="student_id" value="<?php echo $param1; ?>">
    
    <div class="form-group mb-3">
        <div class="col-md-12">
            <label  for="class_id_on_taking_attendance"><?php echo get_phrase('class'); ?></label>
            <select name="class_id" id="class_id_on_taking_attendance" class="form-control select2" data-bs-toggle="select2" onchange="classWiseSectionOnTakingAttendance(this.value)" required>
                <option value=""><?php echo get_phrase('select_a_class'); ?></option>
                <?php $classes = $this->db->get_where('classes', array('school_id' => $school_id))->result_array(); ?>
                <?php foreach($classes as $class): ?>
                    <option value="<?php echo $class['id']; ?>"><?php echo $class['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-group row mb-3">
        <div class="col-md-12" id = "section_content_2">
            <label for="section_id_on_taking_attendance"><?php echo get_phrase('section'); ?></label>
            <select name="section_id" id="section_id_on_taking_attendance" class="form-control select2" data-bs-toggle="select2" required >
                <option value=""><?php echo get_phrase('select_section'); ?></option>
            </select>
        </div>
    </div>

    <div class="form-group col-md-12 mt-4">
        <button class="btn w-100 btn-primary" type="submit"><?php echo get_phrase('submit'); ?></button>
    </div>
</form>

<script type="text/javascript">
    function classWiseSectionOnTakingAttendance(classId) {
        $.ajax({
            url: "<?php echo route('section/list/'); ?>"+classId,
            success: function(response){
                $('#section_id_on_taking_attendance').html(response);
            }
        });
    }
</script>