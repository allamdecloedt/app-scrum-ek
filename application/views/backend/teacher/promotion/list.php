<?php if (isset($enrolments)): ?>
  <?php if (count($enrolments) > 0): ?>
    <div class="row justify-content-md-center">
            <div class="col-md-4 mt-2">
                <div class="card text-white bg-secondary">
                    <div class="card-body">
                        <div class="toll-free-box text-center">
                            <h4> <i class="mdi mdi-chart-bar-stacked"></i> <?php echo get_phrase('promote_student'); ?></h4>
                            <h5><?php echo get_phrase('class_from').': '.$class_from_details['name'].' '.get_phrase('to').' : '.$class_to_details['name']; ?></h5>
                            <h5><?php echo get_phrase('session_from').': '.$session_from_details['name'].' '.get_phrase('to').' : '.$session_to_details['name']; ?></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-md-center">
            <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 mb-3 mb-lg-0">
                <div class="table-responsive-sm">
                    <table class="table table-bordered table-striped dt-responsive nowrap" width="100%">
                        <thead class="thead-dark">
                        <tr>
                            <th><?php echo get_phrase('image'); ?></th>
                            <th><?php echo get_phrase('student_name'); ?></th>
                            <th><?php echo get_phrase('section'); ?></th>
                            <th><?php echo get_phrase('status'); ?></th>
                            <th><?php echo get_phrase('action'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($enrolments as $enrolment):
                                  $student_details = $this->user_model->get_student_details_by_id('student', $enrolment['student_id']);
                                  $section_details = $this->crud_model->get_section_details_by_id('section', $student_details['section_id'])->row_array(); ?>
                              <tr>
                                  <td class="text-center">
                                    <img src="<?php echo $this->user_model->get_user_image($student_details['id']); ?>" height="50" alt=""><br>
                                  </td>
                                  <td>
                                    <?php echo $student_details['name']; ?>
                                    <br>
                                    <small><b><?php echo get_phrase('student_code'); ?>:</b><?php echo $student_details['code']; ?></small>
                                  </td>
                                  <td>
                                    <?php echo $section_details['name']; ?>
                                  </td>
                                  <td style="text-align: center;">
                                      <span class="badge badge-info-lighten" id = "success_<?php echo $student_details['id']; ?>" style="display: none;"><?php echo get_phrase('prmoted'); ?></span>
                                      <span class="badge badge-light"  id = "danger_<?php echo $student_details['id'] ?>"><?php echo get_phrase('not_promoted_yet'); ?></span>
                                  </td>
                                  <td style="text-align: center;">
                                      <button type="button" class="btn btn-icon btn-success btn-sm" onclick="enrollStudent('<?php echo $student_details['id'].'-'.$class_id_to.'-'.$session_to; ?>', '<?php echo $enrolment['id']; ?>')"> <?php echo get_phrase('enroll_to'); ?> <strong> <?php echo $class_to_details['name']; ?> </strong> </button>
                                      <button type="button" class="btn btn-icon btn-secondary btn-sm" onclick="enrollStudent('<?php echo $student_details['id'].'-'.$class_id_from.'-'.$session_to; ?>', '<?php echo $enrolment['id']; ?>')"> <?php echo get_phrase('enroll_to'); ?> <strong> <?php echo $class_from_details['name']; ?> </strong> </button>
                                  </td>
                              </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
  <?php else: ?>
      <?php include APPPATH.'views/backend/empty.php'; ?>
  <?php endif; ?>
<?php else: ?>
  <?php include APPPATH.'views/backend/empty.php'; ?>
<?php endif; ?>
