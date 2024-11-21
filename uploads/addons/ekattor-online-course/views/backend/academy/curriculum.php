<div class="row justify-content-center">
    <div class="col-xl-12 mb-4 text-center mt-3">
        <a href="javascript::void(0)" class="btn btn-outline-primary btn-rounded btn-sm ml-1" onclick="showAjaxModal('<?php echo site_url('modal/popup/academy/section_add/'.$course['id']); ?>', '<?php echo get_phrase('add_new_section'); ?>')"><i class="mdi mdi-plus"></i> <?php echo get_phrase('add_section'); ?></a>
        <a href="javascript::void(0)" class="btn btn-outline-primary btn-rounded btn-sm ml-1" onclick="showAjaxModal('<?php echo site_url('modal/popup/academy/lesson_add/'.$course['id']); ?>', '<?php echo get_phrase('add_new_lesson'); ?>')"><i class="mdi mdi-plus"></i> <?php echo get_phrase('add_lesson'); ?></a>
        <a href="javascript::void(0)" class="btn btn-outline-primary btn-rounded btn-sm ml-1" onclick="showAjaxModal('<?php echo site_url('modal/popup/academy/quiz_add/'.$course['id']); ?>', '<?php echo get_phrase('add_new_quiz'); ?>')"><i class="mdi mdi-plus"></i> <?php echo get_phrase('add_quiz'); ?></a>
        <a href="javascript::void(0)" class="btn btn-outline-primary btn-rounded btn-sm ml-1" onclick="largeModal('<?php echo site_url('modal/popup/academy/sort_section/'.$course['id']); ?>', '<?php echo get_phrase('sort_sections'); ?>')"><i class="mdi mdi-sort-variant"></i> <?php echo get_phrase('sort_sections'); ?></a>
    </div>

    <div class="col-xl-8">
        <div class="row">
            <?php
            $lesson_counter = 0;
            $quiz_counter   = 0;
            foreach ($course_sections as $key => $section):?>
            <div class="col-xl-12">
                <div class="card bg-light text-seconday on-hover-action mb-3" id = "section-<?php echo $section['id']; ?>">
                    <div class="card-body">
                        <div class="w-100 display-none text-center mb-2" id = "widgets-of-section-<?php echo $section['id']; ?>">
                            <button type="button" class="btn btn-outline-secondary btn-rounded btn-sm" name="button" onclick="largeModal('<?php echo site_url('modal/popup/academy/sort_lesson/'.$section['id']); ?>', '<?php echo get_phrase('sort_lessons'); ?>')" ><i class="mdi mdi-sort-variant"></i> <?php echo get_phrase('sort_lesson'); ?></button>
                            <button type="button" class="btn btn-outline-secondary btn-rounded btn-sm ml-1" name="button" onclick="showAjaxModal('<?php echo site_url('modal/popup/academy/section_edit/'.$section['id'].'/'.$course['id']); ?>', '<?php echo get_phrase('update_section'); ?>')" ><i class="mdi mdi-pencil-outline"></i> <?php echo get_phrase('edit_section'); ?></button>

                            <button type="button" class="btn btn-outline-secondary btn-rounded btn-sm ml-1" name="button" onclick="confirmModal('<?php echo site_url('addons/courses/course_sections/'.$course['id'].'/delete'.'/'.$section['id']); ?>', reloadEditCoursePage )"><i class="mdi mdi-window-close"></i> <?php echo get_phrase('delete_section'); ?></button>
                            
                        </div>
                        <h5 class="card-title" class="m-0 py-1"><span class="font-weight-light"><?php echo get_phrase('section').' '.++$key; ?></span>: <?php echo $section['title']; ?>
                        </h5>

                        <div class="clearfix"></div>
                        <?php
                        $lessons = $this->lms_model->get_lessons('section', $section['id'])->result_array();
                        foreach ($lessons as $index => $lesson):?>
                        <!-- Portlet card -->
                        <div class="card text-secondary on-hover-action mb-2" id = "<?php echo 'lesson-'.$lesson['id']; ?>">
                            <div class="card-body thinner-card-body">
                                <div class="card-widgets display-none" id = "widgets-of-lesson-<?php echo $lesson['id']; ?>">
                                    <?php if ($lesson['lesson_type'] == 'quiz'): ?>
                                        <a href="javascript::" onclick="largeModal('<?php echo site_url('modal/popup/academy/quiz_questions/'.$lesson['id']); ?>', '<?php echo get_phrase('manage_quiz_questions'); ?>')"><i class="mdi mdi-comment-question-outline"></i></a>
                                        <a href="javascript::" onclick="showAjaxModal('<?php echo site_url('modal/popup/academy/quiz_edit/'.$lesson['id'].'/'.$course['id']); ?>', '<?php echo get_phrase('update_quiz_information'); ?>')"><i class="mdi mdi-pencil-outline"></i></a>
                                    <?php else: ?>
                                        <a href="javascript::" onclick="showAjaxModal('<?php echo site_url('modal/popup/academy/lesson_edit/'.$lesson['id'].'/'.$course['id']); ?>', '<?php echo get_phrase('update_lesson'); ?>')"><i class="mdi mdi-pencil-outline"></i></a>
                                    <?php endif; ?>
                                    <a href="javascript::" onclick="confirmModal('<?php echo site_url('addons/courses/lessons/'.$course['id'].'/delete'.'/'.$lesson['id']); ?>', reloadEditCoursePage);"><i class="mdi mdi-window-close"></i></a>
                                </div>
                                <h5 class="card-title mb-0">
                                    <span class="font-weight-light">
                                        <?php
                                        if ($lesson['lesson_type'] == 'quiz') {
                                            $quiz_counter++; // Keeps track of number of quiz
                                            $lesson_type = $lesson['lesson_type'];
                                        }else {
                                            $lesson_counter++; // Keeps track of number of lesson
                                            if ($lesson['attachment_type'] == 'txt' || $lesson['attachment_type'] == 'pdf' || $lesson['attachment_type'] == 'doc' || $lesson['attachment_type'] == 'img') {
                                                $lesson_type = $lesson['attachment_type'];
                                            }else {
                                                $lesson_type = 'video';
                                            }
                                        }
                                        ?>
                                        <img src="<?php echo base_url('assets/backend/lesson_icon/'.$lesson_type.'.png'); ?>" alt="" height = "16">
                                        <?php echo $lesson['lesson_type'] == 'quiz' ? get_phrase('quiz').' '.$quiz_counter : get_phrase('lesson').' '.$lesson_counter; ?>
                                    </span>: <?php echo $lesson['title']; ?>
                                </h5>
                            </div>
                        </div> <!-- end card-->
                        <?php endforeach; ?>

                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>