<?php $school_id = school_id(); ?>
<form method="POST" class="d-block responsive_media_query" action="<?php echo site_url('superadmin/online_admission/assigned'); ?>">
    <!-- Champ caché pour le jeton CSRF -->
    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
    

    <input type="hidden" name="student_id" value="<?php echo $param1; ?>">
    
    

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