<?php
$school_id = school_id();
$conditions['school_id'] = $school_id;

if ($parent_category && $child_category) {
    $conditions[$parent_category . '_id'] = $child_category;
}

$check_data = $this->db->order_by('id', 'desc')->get_where('assign_students', $conditions);
$assigned_students = $check_data->result_array();
if ($check_data->num_rows() > 0) : ?>
    <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
        <thead>
            <tr style="background-color: #313a46; color: #ababab;">
                <th><?php echo get_phrase('#'); ?></th>
                <th><?php echo get_phrase('vehicle'); ?></th>
                <th><?php echo get_phrase('driver'); ?></th>
                <th><?php echo get_phrase('student'); ?></th>
                <th><?php echo get_phrase('class'); ?></th>
                <th><?php echo get_phrase('assigned_date'); ?></th>
                <th><?php echo get_phrase('action'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($assigned_students as $key => $assigned_student) :
                $vehicle_details = $this->db->where('id', $assigned_student['vehicle_id'])->order_by('id', 'desc')->get('vehicles')->row_array();

                $driver_details = $this->db->select('users.name')
                    ->from('drivers')->join('users', 'drivers.user_id = users.id')
                    ->where('drivers.id', $assigned_student['driver_id'])->get()->row_array();

                $student_details = $this->db->select('users.name')
                    ->from('students')->join('users', 'students.user_id = users.id')
                    ->where('students.id', $assigned_student['student_id'])->get()->row_array();

                $class_details = $this->db->where('id', $assigned_student['class_id'])->get('classes')->row_array();

                $booked_seat = $this->db->where('vehicle_id', $data['vehicle_id'])->get('assign_students')->num_rows();
            ?>
                <tr>
                    <td><?php echo $key + 1; ?></td>
                    <td><?php echo $vehicle_details['vh_num'] . ', ' . $vehicle_details['vh_model']; ?></td>
                    <td><?php echo $driver_details['name']; ?></td>
                    <td><?php echo $student_details['name']; ?></td>
                    <td><?php echo $class_details['name']; ?></td>
                    <td><?php echo date('d-M-y', $assigned_student['date_added']); ?></td>
                    <td>
                        <a href="javascript:void(0);" class="btn btn-primary" onclick="confirmModal('<?php echo route('assign_student/delete/' . $assigned_student['id']); ?>', showAllAssignedStudents)">Remove</a>
                    </td>
                </tr>
            <?php endforeach; ?>
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