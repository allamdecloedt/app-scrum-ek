<?php
$school_id = school_id();
$condition['school_id'] = school_id();
$condition['driver_id'] = driver_id();

if (isset($vehicle_id)) {
    $condition['vehicle_id'] = $vehicle_id;
}

$check_data = $this->db->get_where('assign_students', $condition);
if ($check_data->num_rows() > 0) : ?>
<h5 class="mb-3"><?php echo get_phrase('trip_list'); ?></h5>
<table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
    <thead>
        <tr style="background-color: #313a46; color: #ababab;">
            <th><?php echo get_phrase('#'); ?></th>
            <th><?php echo get_phrase('student_name'); ?></th>
            <th><?php echo get_phrase('email'); ?></th>
            <th><?php echo get_phrase('phone'); ?></th>
            <th><?php echo get_phrase('class'); ?></th>
            <th><?php echo get_phrase('vehicle'); ?></th>
        </tr>
    </thead>

    <tbody>
        <?php
            $driver_id = driver_id();
            $this->db->select('assign_students.*, vehicles.vh_num, vehicles.vh_model, classes.name as class_name, users.name as student_name, users.email as student_email, users.phone as student_phone');
            $this->db->from('assign_students');
            $this->db->join('students', 'assign_students.student_id = students.id');
            $this->db->join('classes', 'assign_students.class_id = classes.id');
            $this->db->join('users', 'students.user_id = users.id');
            $this->db->join('vehicles', 'assign_students.vehicle_id = vehicles.id');
            $this->db->order_by('assign_students.id', 'desc');
            $this->db->where('assign_students.school_id', school_id());
            $this->db->where('assign_students.driver_id', $driver_id);
            if ($filter != '') {
                $this->db->where('assign_students.vehicle_id', $filter);
            }
            $assigned_students = $this->db->get()->result_array();
            foreach ($assigned_students as $key => $student) {
            ?>
        <tr>
            <td><?php echo $key + 1; ?></td>
            <td><?php echo $student['student_name']; ?></td>
            <td><?php echo $student['student_email']; ?></td>
            <td><?php echo $student['student_phone']; ?></td>
            <td><?php echo $student['class_name']; ?></td>
            <td><?php echo $student['vh_num'] . ', ' . $student['vh_model']; ?></td>
        </tr>
        <?php } ?>
    </tbody>

</table>
<?php else : ?>
<?php include APPPATH . 'views/backend/empty.php'; ?>
<?php endif; ?>
<style>
td {
    vertical-align: middle;
    height: 40px;
}

</style>
