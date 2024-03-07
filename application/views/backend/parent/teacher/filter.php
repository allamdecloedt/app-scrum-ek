<table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-4">
            <select name="department_id" id="department_id" class="form-control" required>
                <option value=""><?php echo get_phrase('select_a_department'); ?></option>
                    <?php
                        $departments = $this->db->get_where('departments', array('school_id' => school_id()))->result_array();
                        foreach($departments as $department){
                    ?>
                        <option value="<?php echo $department['id']; ?>" <?php if($department_id == $department['id']) echo 'selected'; ?>><?php echo $department['name']; ?></option>
                    <?php } ?>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-block btn-secondary" onclick="filter_department()" ><?php echo get_phrase('filter'); ?></button>
        </div>
    </div>
    <thead>
        <tr style="background-color: #313a46; color: #ababab;">
            <th><?php echo get_phrase('name'); ?></th>
            <th><?php echo get_phrase('department'); ?></th>
            <th><?php echo get_phrase('designation'); ?></th>
            <th><?php echo get_phrase('options'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
            $school_id = school_id();
            $teachers = $this->db->get_where('teachers', array('department_id' => $department_id, 'school_id' => $school_id))->result_array();
            foreach($teachers as $teacher){
        ?>
        <tr>
            <td><?php echo $this->db->get_where('users', array('id' => $teacher['user_id']))->row('name'); ?></td>
            <td><?php echo $this->db->get_where('departments', array('id' => $teacher['department_id']))->row('name'); ?></td>
            <td><?php echo $teacher['designation']; ?></td>
            <td>
                <button type="button" class="btn btn-icon btn-secondary btn-sm" style="margin-right:5px;" onclick="confirmModal('<?php echo site_url('modal/popup/syllabus/assign_permission'); ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="<?php echo get_phrase('assign_permission_for_teachers'); ?>"> <i class="dripicons-checklist"></i></button>
                <button type="button" class="btn btn-icon btn-secondary btn-sm" style="margin-right:5px;" onclick="rightModal('<?php echo site_url('modal/popup/teacher/update/'.$teacher['user_id']); ?>', '<?php echo get_phrase('update_teacher'); ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="<?php echo get_phrase('update_teacher'); ?>"> <i class="mdi mdi-wrench"></i></button>
                <button type="button" class="btn btn-icon btn-secondary btn-sm" style="margin-right:5px;" onclick="confirmModal('<?php echo site_url('modal/popup/teacher/delete/'.$teacher['user_id']); ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="<?php echo get_phrase('delete_teacher'); ?>"> <i class="mdi mdi-window-close"></i></button>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<script type="text/javascript">
    initDataTable('basic-datatable');
</script>
