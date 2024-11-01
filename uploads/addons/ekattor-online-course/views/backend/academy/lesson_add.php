<?php $course_sections = $this->lms_model->get_section('course', $param1)->result_array(); ?>
<form action="<?php echo site_url('addons/courses/lessons/'.$param1.'/add'); ?>" method="post" enctype="multipart/form-data">
    <div class="form-group mb-2">
        <label><?php echo get_phrase('title'); ?></label>
        <input type="text" name = "title" class="form-control" required>
    </div>

    <input type="hidden" name="course_id" value="<?php echo $param1; ?>">

    <div class="form-group mb-2">
        <label for="section_id"><?php echo get_phrase('section'); ?></label>
        <select class="form-control select2" data-toggle="select2" name="section_id" id="section_id" required>
            <?php foreach ($course_sections as $section): ?>
                <option value="<?php echo $section['id']; ?>"><?php echo $section['title']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group mb-2">
        <label for="section_id"><?php echo get_phrase('lesson_type'); ?></label>
        <select class="form-control select2" data-toggle="select2" name="lesson_type" id="lesson_type" required onchange="show_lesson_type_form(this.value)">
            <option value=""><?php echo get_phrase('select_type_of_lesson'); ?></option>
            <option value="video-url"><?php echo get_phrase('video'); ?></option>
            <?php if (addon_status('amazon-s3')): ?>
                <option value="s3-video"><?php echo get_phrase('video_file'); ?></option>
            <?php endif;?>
            <option value="other-txt"><?php echo get_phrase('text_file'); ?></option>
            <option value="other-pdf"><?php echo get_phrase('pdf_file'); ?></option>
            <option value="other-doc"><?php echo get_phrase('document_file'); ?></option>
            <option value="other-img"><?php echo get_phrase('image_file'); ?></option>
        </select>
    </div>

    <div class="dv_none" id="video">

        <div class="form-group mb-2">
            <label for="lesson_provider"><?php echo get_phrase('lesson_provider'); ?>( <?php echo get_phrase('for_web_application'); ?> )</label>
            <select class="form-control select2" data-toggle="select2" name="lesson_provider" id="lesson_provider" onchange="check_video_provider(this.value)">
                <option value=""><?php echo get_phrase('select_lesson_provider'); ?></option>
                <option value="youtube"><?php echo get_phrase('youtube'); ?></option>
                <option value="vimeo"><?php echo get_phrase('vimeo'); ?></option>
                <option value="html5">HTML5</option>
            </select>
        </div>



        <div class="dv_none" id = "youtube_vimeo">
            <div class="form-group mb-2">
                <label><?php echo get_phrase('video_url'); ?>( <?php echo get_phrase('for_web_application'); ?> )</label>
                <input type="text" id = "video_url" name = "video_url" class="form-control" onblur="ajax_get_video_details(this.value)" placeholder="<?php echo get_phrase('this_video_will_be_shown_on_web_application'); ?>">
                <label class="form-label" id = "perloader" style ="margin-top: 4px; display: none;"><i class="mdi mdi-spin mdi-loading">&nbsp;</i><?php echo get_phrase('analyzing_the_url'); ?></label>
                <label class="form-label" id = "invalid_url" style ="margin-top: 4px; color: red; display: none;"><?php echo get_phrase('invalid_url').'. '.get_phrase('your_video_source_has_to_be_either_youtube_or_vimeo'); ?></label>
            </div>

            <div class="form-group mb-2">
                <label><?php echo get_phrase('duration'); ?>( <?php echo get_phrase('for_web_application'); ?> )</label>
                <input type="text" name = "duration" id = "duration" class="form-control" autocomplete="off">
            </div>
        </div>

        <div class="dv_none" id = "html5">
            <div class="form-group mb-2">
                <label><?php echo get_phrase('video_url'); ?>( <?php echo get_phrase('for_web_application'); ?> )</label>
                <input type="text" id = "html5_video_url" name = "html5_video_url" class="form-control" placeholder="<?php echo get_phrase('this_video_will_be_shown_on_web_application'); ?>">
            </div>

            <div class="form-group mb-2">
                <label><?php echo get_phrase('duration'); ?>( <?php echo get_phrase('for_web_application'); ?> )</label>
                <input type="text" class="form-control" data-toggle='timepicker' data-minute-step="5" name="html5_duration" id = "html5_duration" data-show-meridian="false" value="00:00:00">
            </div>

            <div class="form-group mb-2">
                <label><?php echo get_phrase('thumbnail'); ?> <small>(<?php echo get_phrase('the_image_size_should_be'); ?>: 979 x 551)</small> </label>
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="thumbnail" name="thumbnail" onchange="changeTitleOfImageUploader(this)">
                        <label class="custom-file-label" for="thumbnail"><?php echo get_phrase('thumbnail'); ?></label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="dv_none" id = "other">
        <div class="form-group mb-2">
            <label> <?php echo get_phrase('attachment'); ?></label>
            <div class="input-group">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="attachment" name="attachment" onchange="changeTitleOfImageUploader(this)">
                    <label class="custom-file-label" for="attachment"><?php echo get_phrase('attachment'); ?></label>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group mb-2">
        <label><?php echo get_phrase('summary'); ?></label>
        <textarea name="summary" class="form-control"></textarea>
    </div>

    <div class="text-center">
        <button class = "btn btn-success" type="submit" name="button"><?php echo get_phrase('add_lesson'); ?></button>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function() {
        //$('select.select2:not(.normal)').each(function () { $(this).select2(); });
        $('.select2').select2({
            dropdownParent: $('#scrollable-modal')
        });
        initTimepicker();
    });
</script>