<?php $check_data = $this->db->get('sessions');
if($check_data->num_rows() > 0): ?>

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3 header-title mdi mdi-library-video"> <?php echo get_phrase('online_course'); ?></h4>
                <form class="row justify-content-center" action="javascript:void(0)" method="get">
                    <div class="col-md-10">
                        <div class="row justify-content-center">
                            <!-- Course Teacher -->
                                <div class="col-md-3" <?php if($this->session->userdata('teacher_login') == 1) echo 'hidden'; ?>>
                                    <div class="form-group">
                                        <label for="user_id"><?php echo get_phrase('instructor'); ?></label>
                                        <select class="form-control select2" data-toggle="select2" name="user_id" id = 'user_id'>
                                            <option value="all" <?php if($selected_user_id == 'all') echo 'selected'; ?>><?php echo get_phrase('all'); ?></option>
                                            <?php foreach ($all_teachers->result_array() as $teacher): ?>
                                                <option value="<?php echo $teacher['id']; ?>" <?php if($selected_user_id == $teacher['id']) echo 'selected'; ?>><?php echo $teacher['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                            <!-- Course subject -->
                                <div class="col-md-3"<?php if($this->session->userdata('student_login') != 1) echo 'hidden'; ?>>
                                    <div class="form-group">
                                        <?php $class_id = $this->lms_model->get_class_id_by_user($this->session->userdata('user_id'));; ?>
                                            <?php $subjects = $this->lms_model->get_subject_by_class_id($class_id); ?>
                                        <label for="subject_id"><?php echo get_phrase('subject'); ?></label>
                                        <select class="form-control select2" data-toggle="select2" name="subject" id = 'subject_id'>
                                            <option value="all" <?php if($selected_subject == 'all') echo 'selected'; ?>><?php echo get_phrase('all'); ?></option>
                                            
                                            <?php foreach($subjects as $subject){ ?>
                                                <option value="<?php echo $subject['id']; ?>" <?php if($selected_subject == $subject['id']) echo 'selected'; ?>><?php echo $subject['name']; ?></option>
                                            <?php } ?>
                                            
                                        </select>
                                    </div>
                                </div>

                            <div class="col-md-3">
                                <label for=".." class="text-white">..</label>
                                <button type="submit" class="btn btn-primary btn-block" onclick="filterCourse()" name="button"><?php echo get_phrase('filter'); ?></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Simple card -->
        <?php if (count($courses) > 0): ?>
            <div class="row">
                <?php foreach ($courses as $key => $course):
                    $teacher_details = $this->user_model->get_user_details($course['user_id']);
                    $class_details = $this->crud_model->get_classes($course['class_id'])->row_array();
                    $sections = $this->lms_model->get_section('course', $course['id']);
                    $subject = $this->crud_model->get_subject_by_id($course['subject_id']);
                    $lessons = $this->lms_model->get_lessons('course', $course['id']); ?>
                    
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="card d-block">
                            <?php if(file_exists('uploads/course_thumbnail/'.$course['thumbnail'])):
                              $course_thumbnail = base_url("uploads/course_thumbnail/".$course["thumbnail"]);
                            else:
                              $course_thumbnail = base_url("uploads/course_thumbnail/placeholder.png");
                            endif; ?>
                            <div class="w-100 bg_course_thumbnail" style="background-image: url('<?php echo $course_thumbnail; ?>');">
                                <span class="badge badge-success float-right mt-2 mr-2 p-1"><?php echo $subject['name']; ?></span>
                            </div>
                            <div class="card-body ">
                                <h4 class="card-title"><?php echo $course['title']; ?></h4>
                                <div class="w-100 mb-3">
                                    <div class="media">
                                        <img class="mr-2 rounded-circle" src="<?= $this->user_model->get_user_image($course['user_id']); ?>" width="30" alt="Generic placeholder image">
                                        <div class="media-body pt-1">
                                            <span class="font-13 text-muted"><?php echo $this->user_model->get_user_details($course['user_id'], 'name'); ?></span>

                                            <div class="btn-group float-right">
                                                <div class="btn-group">
                                                    <button class="mdi mdi-dots-vertical bg-white border-0" data-toggle="dropdown" aria-expanded="false"></button>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item pl-1" onclick="previewModal('<?php echo site_url('addons/courses/course_preview/'.$course['id']); ?>', '<?php echo $course['title']; ?>');" href="javascript:void(0)">
                                                            <i class="mdi mdi-eye-outline"></i>
                                                            <?php echo get_phrase('course_preview'); ?>
                                                        </a>
                                                        <a class="dropdown-item mdi pl-1" onclick="showAjaxModal('<?php echo site_url('addons/courses/course_information/'.$course['id']); ?>', '<?php echo get_phrase('course_informations'); ?>');" href="javascript:void(0)">
                                                            <i class="mdi mdi-information-outline"></i>
                                                            <?php echo get_phrase('course_information'); ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php $progress_value = course_progress($course['id']); ?>
                                <div class="row">
                                    <div class="col-sm-10 col-md-10">
                                        <?php if($progress_value >= 100): ?>
                                            <div class="progress mb-2 h-5px">
                                                <div class="progress-bar bg-green-low" role="progressbar" style="width: <?php echo $progress_value; ?>%;" aria-valuenow="<?php echo $progress_value; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        <?php else: ?>
                                            <div class="progress mb-2 h-5px">
                                                <div class="progress-bar" role="progressbar" style="width: <?php echo $progress_value; ?>%;" aria-valuenow="<?php echo $progress_value; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-sm-2 col-md-2 text-left p-0 progress_value_count"><p><?php echo ceil($progress_value); ?>%</p></div>
                                </div>
                                <div class="w-100 text-center">
                                    <?php if($progress_value > 0): ?>
                                        <a href="<?php echo site_url('addons/lessons/play/'.slugify($course['title']).'/'.$course['id'].'/'.$lessons->row('id')); ?>" class="btn btn-secondary mw-50"><?php echo get_phrase('continue_lesson'); ?></a>
                                    <?php else: ?>
                                        <a href="<?php echo site_url('addons/lessons/play/'.slugify($course['title']).'/'.$course['id'].'/'.$lessons->row('id')); ?>" class="btn btn-primary mw-50"><?php echo get_phrase('start_course'); ?></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <?php include APPPATH.'views/backend/empty.php'; ?>
        <?php endif; ?>
    </div>

<?php else: ?>
    <?php include APPPATH.'views/backend/empty.php'; ?>
<?php endif; ?>

<script>
    $('.select2').select2();
</script>