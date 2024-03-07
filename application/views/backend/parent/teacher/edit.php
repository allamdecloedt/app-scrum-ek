<?php
    $users = $this->db->get_where('users', array('id' => $param1))->result_array();
    foreach($users as $user):
        $teacher = $this->db->get_where('teachers', array('user_id' => $user['id']))->row_array();
?>
    <form method="POST" class="d-block ajaxForm" action="<?php echo route('teacher/update/'.$param1); ?>">
        <div class="form-row">
            <div class="form-group mb-1">
                <input type="hidden" name="school_id" value="<?php echo school_id(); ?>">
                <label for="name"><?php echo get_phrase('name'); ?></label>
                <input type="text" value="<?php echo $user['name']; ?>" class="form-control" id="name" name = "name" required>
                <small id="" class="form-text text-muted"><?php echo get_phrase('provide_teacher_name'); ?></small>
            </div>

            <div class="form-group mb-1">
                <label for="email"><?php echo get_phrase('email'); ?></label>
                <input type="email" value="<?php echo $user['email']; ?>" class="form-control" id="email" name = "email" required>
                <small id="" class="form-text text-muted"><?php echo get_phrase('provide_teacher_email'); ?></small>
            </div>

            <div class="form-group mb-1">
                <label for="designation"><?php echo get_phrase('designation'); ?></label>
                <input type="text" value="<?php echo $teacher['designation']; ?>" class="form-control" id="designation" name = "designation" required>
                <small id="" class="form-text text-muted"><?php echo get_phrase('provide_teacher_designation'); ?></small>
            </div>

            <div class="form-group mb-1">
                <label for="department"><?php echo get_phrase('department'); ?></label>
                <select name="department" id="department" class="form-control" required>
                    <option value=""><?php echo get_phrase('select_a_department'); ?></option>
                    <?php $departments = $this->db->get_where('departments', array('school_id' => school_id()))->result_array();
                        foreach($departments as $department){
                    ?>
                        <option value="<?php echo $department['id']; ?>" <?php if($department['id'] == $teacher['department_id']) echo 'selected'; ?>><?php echo $department['name']; ?></option>
                    <?php } ?>
                </select>
                <small id="" class="form-text text-muted"><?php echo get_phrase('provide_a_department'); ?></small>
            </div>

            <div class="form-group mb-1">
                <label for="phone"><?php echo get_phrase('phone_number'); ?></label>
                <input type="text" value="<?php echo $user['phone']; ?>" class="form-control" id="phone" name = "phone" required>
                <small id="" class="form-text text-muted"><?php echo get_phrase('provide_teacher_phone_number'); ?></small>
            </div>

            <div class="form-group mb-1">
                <label for="gender"><?php echo get_phrase('gender'); ?></label>
                <select name="gender" id="gender" class="form-control">
                    <option value=""><?php echo get_phrase('select_a_gender'); ?></option>
                    <option value="Male" <?php if($user['gender'] == 'Male') echo 'selected'; ?>><?php echo get_phrase('male'); ?></option>
                    <option value="Female" <?php if($user['gender'] == 'Female') echo 'selected'; ?>><?php echo get_phrase('female'); ?></option>
                    <option value="Others" <?php if($user['gender'] == 'Others') echo 'selected'; ?>><?php echo get_phrase('others'); ?></option>
                </select>
                <small id="" class="form-text text-muted"><?php echo get_phrase('provide_teacher_gender'); ?></small>
            </div>

            <div class="form-group mb-1">
                <label for="blood_group"><?php echo get_phrase('blood_group'); ?></label>
                <select name="blood_group" id="blood_group" class="form-control">
                    <option value=""><?php echo get_phrase('select_a_blood_group'); ?></option>
                    <option value="a+" <?php if($user['blood_group'] == 'a+') echo 'selected'; ?>>A+</option>
                    <option value="a-" <?php if($user['blood_group'] == 'a-') echo 'selected'; ?>>A-</option>
                    <option value="b+" <?php if($user['blood_group'] == 'b+') echo 'selected'; ?>>B+</option>
                    <option value="b-" <?php if($user['blood_group'] == 'b-') echo 'selected'; ?>>B-</option>
                    <option value="ab+" <?php if($user['blood_group'] == 'ab+') echo 'selected'; ?>>AB+</option>
                    <option value="ab-" <?php if($user['blood_group'] == 'ab-') echo 'selected'; ?>>AB-</option>
                    <option value="o+" <?php if($user['blood_group'] == 'o+') echo 'selected'; ?>>O+</option>
                    <option value="o-" <?php if($user['blood_group'] == '0-') echo 'selected'; ?>>O-</option>
                </select>
                <small id="" class="form-text text-muted"><?php echo get_phrase('provide_teacher_blood_group'); ?></small>
            </div>

            <div class="form-group mb-1">
                <label for="phone"><?php echo get_phrase('address'); ?></label>
                <textarea class="form-control" id="address" name = "address" rows="5" required><?php echo $user['address']; ?></textarea>
                <small id="" class="form-text text-muted"><?php echo get_phrase('provide_teacher_address'); ?></small>
            </div>

            <div class="form-group mt-2 col-md-12">
                <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('update_teacher'); ?></button>
            </div>
        </div>
    </form>
<?php endforeach; ?>

<script>
    $(".ajaxForm").validate({}); // Jquery form validation initialization
    $(".ajaxForm").submit(function(e) {
        var form = $(this);
        ajaxSubmit(e, form, showAllTeachers);
    });
</script>
