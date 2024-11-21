<?php $check_data = $this->db->get('sessions');
if($check_data->num_rows() > 0): ?>

    <div class="col-sm-12 col-md-6 text-center">
        <div class="card">
            <div class="card-body">
                <a href="javascript:void(0)" class="text-secondary">
                    <i class="dripicons-link text-muted font-size-24"></i>
                    <h3><span><?php echo $status_wise_courses['active']->num_rows(); ?></span></h3>
                    <p class="text-muted"><?php echo get_phrase('active_courses'); ?></p>
                </a>
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-md-6 text-center">
        <div class="card">
            <div class="card-body">
                <a href="javascript:void(0)" class="text-secondary w-100">
                    <i class="dripicons-link-broken text-muted font-size-24"></i>
                    <h3><span><?php echo $status_wise_courses['inactive']->num_rows(); ?></span></h3>
                    <p class="text-muted"><?php echo get_phrase('inactive_courses'); ?></p>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3 header-title"><?php echo get_phrase('course_list'); ?></h4>
                <form class="row justify-content-center" action="javascript:void(0)" method="get">
                    <div class="col-md-10">
                        <div class="row justify-content-center">
                            <!-- Course Categories -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="class_id"><?php echo get_phrase('classes'); ?></label>
                                    <select class="form-control select2" data-toggle="select2" name="class_id" id="class_id">
                                        <option value="<?php echo 'all'; ?>" <?php if($selected_class_id == 'all') echo 'selected'; ?>><?php echo get_phrase('all'); ?></option>
                                        <?php foreach ($classes->result_array() as $class): ?>
                                            <option value="<?php echo $class['id']; ?>" <?php if($selected_class_id == $class['id']) echo 'selected'; ?>><?php echo $class['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

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

                            <!-- Course Status -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status"><?php echo get_phrase('status'); ?></label>
                                    <select class="form-control select2" data-toggle="select2" name="status" id = 'course_status'>
                                        <option value="all" <?php if($selected_status == 'all') echo 'selected'; ?>><?php echo get_phrase('all'); ?></option>
                                        <option value="active" <?php if($selected_status == 'active') echo 'selected'; ?>><?php echo get_phrase('active'); ?></option>
                                        <option value="inactive" <?php if($selected_status == 'inactive') echo 'selected'; ?>><?php echo get_phrase('inactive'); ?></option>
                                    </select>
                                </div>
                            </div>
                        
                            <div class="col-md-3">
                                <label for=".." class="text-white w-100">..</label>
                                <button type="submit" class="btn btn-primary btn-block w-100" onclick="filterCourse()" name="button"><?php echo get_phrase('filter'); ?></button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="table-responsive-sm mt-4">
                    <?php if (count($courses) > 0): ?>
                        <table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%" data-page-length='25'>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo get_phrase('title'); ?></th>
                                    <th><?php echo get_phrase('class'); ?></th>
                                    <th><?php echo get_phrase('lesson_and_section'); ?></th>
                                    <th><?php echo get_phrase('status'); ?></th>
                                    <th><?php echo get_phrase('actions'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($courses as $key => $course):
                                    $teacher_details = $this->user_model->get_user_details($course['user_id']);
                                    $class_details = $this->crud_model->get_classes($course['class_id'])->row_array();
                                    $sections = $this->lms_model->get_section('course', $course['id']);
                                    $lessons = $this->lms_model->get_lessons('course', $course['id']);
                                    // if ($course['status'] == 'inactive' && $selected_status == 'all') {
                                    //     continue;
                                    // }
                                ?>
                                    <tr>
                                        <td><?php echo ++$key; ?></td>
                                        <td>
                                            <strong><a href="<?php echo site_url('addons/courses/course_edit/'.$course['id']); ?>"><?php echo ellipsis($course['title']); ?></a></strong><br>
                                            <small class="text-muted"><?php echo get_phrase('teacher').': <b>'.$teacher_details['name'].'</b>'; ?></small>
                                        </td>

                                        <td>
                                            <span class="badge badge-dark-lighten"><?php echo $class_details['name']; ?></span>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?php echo '<b>'.get_phrase('total_section').'</b>: '.$sections->num_rows(); ?></small><br>
                                            <small class="text-muted"><?php echo '<b>'.get_phrase('total_lesson').'</b>: '.$lessons->num_rows(); ?></small><br>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($course['status'] == 'active'): ?>
                                                <span class="badge badge-success-lighten"><?php echo get_phrase('active'); ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-dark-lighten"><?php echo get_phrase('inactive'); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="dropright dropright">
                                                <button type="button" class="btn btn-sm btn-outline-primary btn-rounded btn-icon" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                  <i class="mdi mdi-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                  <li><a class="dropdown-item" href="<?php echo site_url('addons/lessons/play/'.slugify($course['title']).'/'.$course['id'].'/'.$lessons->row('id')); ?>" target="_blank"><?php echo get_phrase('view_course_on_frontend');?></a></li>
                                                  <li><a class="dropdown-item" href="<?php echo site_url('addons/courses/course_edit/'.$course['id']); ?>"><?php echo get_phrase('edit_this_course');?></a></li>
                                                  <li><a class="dropdown-item" href="<?php echo site_url('addons/courses/course_edit/'.$course['id']); ?>"><?php echo get_phrase('lesson_and_quiz');?></a></li>
                                                  <li>
                                                      <?php if ($course['status'] == 'active'): ?>
                                                          <a class="dropdown-item" href="javascript:void(0)" onclick="course_activity('<?= $course['id']; ?>')">
                                                              <?php echo get_phrase('mark_as_inactive');?>
                                                          </a>
                                                      <?php else: ?>
                                                          <a class="dropdown-item" href="javascript:void(0)" onclick="course_activity('<?= $course['id']; ?>')">
                                                              <?php echo get_phrase('mark_as_active');?>
                                                          </a>
                                                      <?php endif; ?>
                                                  </li>
                                                  <li><a class="dropdown-item" href="#" onclick="confirmModal('<?php echo site_url('addons/courses/index/delete/'.$course['id']); ?>', filterCourse)"><?php echo get_phrase('delete'); ?></a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                    <?php if (count($courses) == 0): ?>
                        <?php include APPPATH.'views/backend/empty.php'; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>
    <?php include APPPATH.'views/backend/empty.php'; ?>
<?php endif; ?>

<script type="text/javascript">
    $('select.select2:not(.normal)').each(function () { $(this).select2(); });
</script>