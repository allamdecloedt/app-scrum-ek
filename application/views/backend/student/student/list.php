<?php $school_id = school_id(); ?>
<table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%">
    <thead>
        <tr style="background-color: #313a46; color: #ababab;">
            <th><?php echo get_phrase('code'); ?></th>
            <th><?php echo get_phrase('photo'); ?></th>
            <th><?php echo get_phrase('name'); ?></th>
            <th><?php echo get_phrase('options'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
        $school_id = school_id();
        $enrols = $this->db->get_where('enrols', array('class_id' => $class_id, 'section_id' => $section_id, 'school_id' => $school_id, 'session' => active_session()))->result_array();
        foreach($enrols as $enroll){
        $student = $this->db->get_where('students', array('id' => $enroll['student_id']))->row_array();
    ?>
        <tr>
            <td><?php echo $student['code']; ?></td>
            <td>
                <img class="rounded-circle" width="50" height="50" src="<?php echo $this->user_model->get_user_image($student['user_id']); ?>">
            </td>
            <td><?php echo $this->user_model->get_user_details($student['user_id'], 'name'); ?></td>
            <td>
                <button type="button" class="btn btn-icon btn-secondary btn-sm btn-dark" style="margin-right:5px;" onclick="largeModal('<?php echo site_url('modal/popup/student/profile/'.$student['id'])?>', '<?php echo $this->db->get_where('schools', array('id' => $school_id))->row('name'); ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="<?php echo get_phrase('student_profile'); ?>"> <i class="dripicons-checklist"></i></button>

                <a href="<?php echo route('student/edit/'.$student['id']); ?>" class="btn btn-icon btn-secondary btn-sm btn-dark" style="margin-right:5px;" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="<?php echo get_phrase('update_student_information'); ?>"> <i class="mdi mdi-wrench"></i></a>

                <button id="uname" type="button" class="btn btn-icon btn-secondary btn-sm tooltip_togle" style="margin-right:5px;" onclick="confirmModal('<?php echo route('student/delete/'.$student['id'].'/'.$student['user_id']); ?>', showAllStudents)" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="<?php echo get_phrase('delete_student'); ?>"> <i class="mdi mdi-window-close"></i></button>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<script type="text/javascript">
    initDataTable('basic-datatable');

    // $(function () {
    //   $('[data-bs-toggle="tooltip"]').popover();
    // });
</script>
