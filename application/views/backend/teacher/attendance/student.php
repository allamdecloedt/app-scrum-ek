<?php $check_permission = has_permission($class_id, $section_id, 'attendance'); ?>
<?php if ($check_permission): ?>
    <?php $school_id = school_id(); ?>
    <div class="row" style="margin-bottom: 10px; width: 100%;">
        <div class="col-6"><a href="javascript:" class="btn btn-sm btn-secondary" onclick="present_all()"><?php echo get_phrase('present_all'); ?></a></div>
        <div class="col-6"><a href="javascript:" class="btn btn-sm btn-secondary float-end" onclick="absent_all()"><?php echo get_phrase('absent_all'); ?></a></div>
    </div>

    <div class="table-responsive-sm row col-md-12" style="padding-right: 0px;">
        <table class="table table-bordered table-centered mb-0">
            <thead>
                <tr>
                    <th><?php echo get_phrase('name'); ?></th>
                    <th><?php echo get_phrase('status'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $enrols = $this->db->get_where('enrols', array('class_id' => $class_id, 'section_id' => $section_id, 'school_id' => $school_id, 'session' => active_session()))->result_array(); ?>
                <?php foreach($enrols as $enroll): ?>
                    <tr>
                        <td>
                            <?php echo $this->user_model->get_user_details($this->db->get_where('students', array('id' => $enroll['student_id']))->row('user_id'), 'name'); ?>
                        </td>
                        <td>
                            <input type="hidden" name="student_id[]" value="<?php echo $enroll['student_id']; ?>">
                            <div class="custom-control custom-radio">
                                <?php $update_attendance = $this->db->get_where('daily_attendances', array('timestamp' => $attendance_date, 'class_id' => $class_id, 'section_id' => $section_id, 'session_id' => active_session(), 'school_id' => $school_id, 'student_id' => $enroll['student_id'])); ?>
                                <?php if($update_attendance->num_rows() > 0): ?>
                                    <?php $row = $update_attendance->row(); ?>
                                    <input type="hidden" name="attendance_id[]" value="<?php echo $row->id; ?>">
                                    <input type="radio" id="" name="status-<?php echo $enroll['student_id']; ?>" value="1" class="present" <?php if($row->status == 1) echo 'checked'; ?> required> <?php echo get_phrase('present'); ?> &nbsp;
                                    <input type="radio" id="" name="status-<?php echo $enroll['student_id']; ?>" value="0" class="absent" <?php if($row->status != 1) echo 'checked'; ?> required> <?php echo get_phrase('absent'); ?>
                                <?php else: ?>
                                    <input type="radio" id="" name="status-<?php echo $enroll['student_id']; ?>" value="1" class="present" required> <?php echo get_phrase('present'); ?> &nbsp;
                                    <input type="radio" id="" name="status-<?php echo $enroll['student_id']; ?>" value="0" class="absent" required> <?php echo get_phrase('absent'); ?>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="form-group col-md-12 mt-4" id = "updateAttendanceDiv" style="display: none;">
        <button class="btn w-100 btn-primary" type="submit"><?php echo get_phrase('update_attendance'); ?></button>
    </div>
<?php else: ?>
    <div class="col-md-12 text-center">
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading"><?php echo get_phrase('access_denied'); ?>!</h4>
            <hr>
            <p class="mb-0"><?php echo get_phrase('sorry_you_are_not_permitted_to_access_this_view').'. <br/>'.get_phrase('admin_handles_it'); ?>.</p>
        </div>
    </div>
<?php endif; ?>



<script type="text/javascript">
function present_all() {
    $(".present").prop('checked', true);
}

function absent_all() {
    $(".absent").prop('checked',true);
}
</script>
