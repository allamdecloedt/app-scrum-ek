<script type="text/javascript">
    $(document).ready(function () {
        initSummerNote(['#description']);
        initSummerNote(['#outcomes_desc']);
    });

    function filterCourse(){
        var url         = '<?php echo site_url('addons/courses'); ?>';
        var class_id    = $('#class_id_course').val();
        var user_id     = $('#user_id').val();
        var status      = $('#course_status').val();
        // var subject_id  = $('#subject_id').val();
        var school_id  = $('#school_id').val();
        $.ajax({
            url: url+"?class_id="+class_id+"&user_id="+user_id+"&status="+status+"&school_id="+school_id+"&only_list=true",
            success : function(response) {
                $('.academy_content').html(response);
                initDataTable('basic-datatable');
                $('select.select2:not(.normal)').each(function () { $(this).select2(); });
            }
        });
    }
    function filterCourseFullPage(){
        var url         = '<?php echo site_url('addons/courses'); ?>';
        var class_id    = $('#class_id').val();
        var user_id     = $('#user_id').val();
        var status      = $('#course_status').val();
        var subject_id  = $('#subject_id').val();
        location.replace(url+"?class_id="+class_id+"&user_id="+user_id+"&status="+status+"&subject_id="+subject_id);
    }

    function course_activity(course_id){

        // Récupérer le nom et la valeur du jeton CSRF depuis l'input caché
        var csrfName = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').attr('name');
        var csrfHash = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').val();

        $.ajax({
        url: '<?= site_url('addons/courses/index/activity/'); ?>'+course_id,
        data: { [csrfName]: csrfHash }, // Ajout du jeton CSRF
        dataType: 'json',
        success : function(response) {
            var url = '<?php echo site_url('addons/courses'); ?>';
            var class_id = $('#class_id').val();
            var user_id  = $('#user_id').val();
            var status   = $('#course_status').val();

                // Mettre à jour le jeton CSRF avec le nouveau jeton renvoyé dans la réponse
                var newCsrfNamee = response.csrf.csrfName;
                var newCsrfHashh = response.csrf.csrfHash;
                $('input[name="' + newCsrfNamee + '"]').val(newCsrfHashh); // Mise à jour du token CSRF

            success_notify('<?php echo get_phrase('course_data_updated_successfully');?>');
            $.ajax({
                url: url+"?class_id="+class_id+"&user_id="+user_id+"&status="+status+"&only_list=true",
                success : function(response) {
                    $('.academy_content').html(response);
                    initDataTable('basic-datatable');
                }
            });
        }
      });
    }

    function reloadEditCoursePage(){
        location.reload();
    }

    function get_subject(){
        var class_id = $('#class_id_add_cours').val();
        $.ajax({
            url: "<?php echo site_url('addons/courses/get_subject_by_class/'); ?>"+class_id,
            success: function(response){
                $('#subject_id').html(response);
            }
        });
    }

    $('.on-hover-action').mouseenter(function() {
        var id = this.id;
        $('#widgets-of-'+id).show();
    });
    $('.on-hover-action').mouseleave(function() {
        var id = this.id;
        $('#widgets-of-'+id).hide();
    });


    function ajax_get_video_details(video_url) {
        $('#perloader').show();
        if(checkURLValidity(video_url)){
            $.ajax({
                url: '<?php echo site_url('addons/courses/ajax_get_video_details');?>',
                type : 'POST',
                data : {video_url : video_url},
                success: function(response)
                {
                    jQuery('#duration').val(response);
                    $('#perloader').hide();
                    $('#invalid_url').hide();
                }
            });
        }else {
            $('#invalid_url').show();
            $('#perloader').hide();
            jQuery('#duration').val('');

        }
    }

    function checkURLValidity(video_url) {
        var youtubePregMatch = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
        var vimeoPregMatch = /^(http\:\/\/|https\:\/\/)?(www\.)?(vimeo\.com\/)([0-9]+)$/;
        if (video_url.match(youtubePregMatch)) {
            return true;
        }
        else if (vimeoPregMatch.test(video_url)) {
            return true;
        }
        else {
            return false;
        }
    }

    function show_lesson_type_form(param) {
        var checker = param.split('-');
        var lesson_type = checker[0];
        if (lesson_type === "video") {
            $('#other').hide();
            $('#video').show();
        }else if (lesson_type === "other") {
            $('#video').hide();
            $('#other').show();
        }else {
            $('#video').hide();
            $('#other').hide();
        }
    }

    function check_video_provider(provider) {
        if (provider === 'youtube' || provider === 'vimeo') {
            $('#html5').hide();
            $('#youtube_vimeo').show();
            $('#mydevice').hide();
        }else if(provider === 'html5'){
            $('#youtube_vimeo').hide();
            $('#html5').show();
            $('#mydevice').hide();
        }else if(provider === 'mydevice'){
            $('#youtube_vimeo').hide();
            $('#html5').hide();
            $('#mydevice').show();
        }else {
            $('#youtube_vimeo').hide();
            $('#html5').hide();
            $('#mydevice').hide();
        }
    }


    function ajax_get_section(course_id) {
        $.ajax({
            url: '<?php echo site_url('addons/courses/ajax_get_section/');?>' + course_id ,
            success: function(response)
            {
                jQuery('#section_id').html(response);
            }
        });
    }

    function showOptions(number_of_options){

            // Save current option values
            var existingOptions = [];
            jQuery('.options').each(function(index) {
                var optionValue = jQuery(this).find('input[type="text"]').val();
                var isChecked = jQuery(this).find('input[type="checkbox"]').is(':checked');
                existingOptions.push({ value: optionValue, checked: isChecked });
            });
                // Récupérer le nom et la valeur du jeton CSRF depuis l'input caché
                var csrfName = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').attr('name');
                var csrfHash = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').val();
      

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('addons/courses/manage_multiple_choices_options'); ?>",
            data: {number_of_options : number_of_options , [csrfName]: csrfHash},
            dataType: 'json',
            success: function(response){
                // Mettre à jour le jeton CSRF avec le nouveau jeton renvoyé dans la réponse
                var newCsrfName = response.csrf.csrfName;
                var newCsrfHash = response.csrf.csrfHash;
                $('input[name="' + newCsrfName + '"]').val(newCsrfHash); // Mise à jour du token CSRF

                jQuery('.options').remove();
                jQuery('#multiple_choice_question').after(response.html);

                     // Repopulate saved options
                    jQuery('.options').each(function(index) {
                        if (existingOptions[index]) {
                            jQuery(this).find('input[type="text"]').val(existingOptions[index].value);
                            jQuery(this).find('input[type="checkbox"]').prop('checked', existingOptions[index].checked);
                        }
                    });
              
            }
        });
    }
</script>