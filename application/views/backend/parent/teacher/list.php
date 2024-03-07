<?php
$school_id = school_id();
$check_data = $this->db->get_where('teachers', array('school_id' => $school_id));
if($check_data->num_rows() > 0):?>
<table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%">
    <thead>
        <tr style="background-color: #313a46; color: #ababab;">
            <th><?php echo get_phrase('name'); ?></th>
            <th><?php echo get_phrase('department'); ?></th>
            <th><?php echo get_phrase('designation'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $teachers = $this->db->get_where('teachers', array('school_id' => $school_id))->result_array();
        foreach($teachers as $teacher){
            ?>
            <tr>
                <td><?php echo $this->db->get_where('users', array('id' => $teacher['user_id']))->row('name'); ?></td>
                <td><?php echo $this->db->get_where('departments', array('id' => $teacher['department_id']))->row('name'); ?></td>
                <td><?php echo $teacher['designation']; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<?php else: ?>
    <?php include APPPATH.'views/backend/empty.php'; ?>
<?php endif; ?>
