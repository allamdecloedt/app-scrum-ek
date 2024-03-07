<?php
$course_details = $this->lms_model->get_course_by_id($course_id);
?>
<div class="container-fluid course_container">
    <!-- Top bar -->
    <div class="row">
        <div class="col-lg-9 course_header_col">
            <h5>
                <img src="<?php echo base_url().'uploads/system/logo/logo-light-sm.png';?>" height="25"> <?php echo get_phrase('online_course'); ?> |
                <?php echo $course_details['title']; ?>
            </h5>
        </div>
        <div class="col-lg-3 course_header_col">
            <a href="javascript::" class="course_btn" onclick="toggle_lesson_view()"><i class="dripicons-expand"></i></a>
            <a href="<?php echo site_url('addons/courses'); ?>" class="course_btn"> <i class="dripicons-backspace"></i> <?php echo get_phrase('back_to_courses'); ?></a>
        </div>
    </div>

    <div class="row" id = "lesson-container">
        <?php if (isset($lesson_id)): ?>
            <!-- Course content, video, quizes, files starts-->
            <?php include 'course_content_body.php'; ?>
            <!-- Course content, video, quizes, files ends-->
        <?php endif; ?>

        <!-- Course sections and lesson selector sidebar starts-->
        <?php include 'course_content_sidebar.php'; ?>
        <!-- Course sections and lesson selector sidebar ends-->
    </div>
</div>
