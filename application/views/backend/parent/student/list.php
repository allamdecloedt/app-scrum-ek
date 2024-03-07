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
          <div class="dropdown text-center">
  					<button type="button" class="btn btn-sm btn-icon btn-rounded btn-outline-secondary dropdown-btn dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></button>
  					<div class="dropdown-menu dropdown-menu-end">
  						<!-- item-->
  						<a href="javascript:void(0);" class="dropdown-item"  onclick="largeModal('<?php echo site_url('modal/popup/student/profile/'.$student['id'])?>', '<?php echo $this->db->get_where('schools', array('id' => $school_id))->row('name'); ?>')"><?php echo get_phrase('profile'); ?></a>
  						<!-- item-->
  						<a href="<?php echo route('student/edit/'.$student['id']); ?>" class="dropdown-item"><?php echo get_phrase('edit'); ?></a>
              <!-- item -->
              <a href="javascript:;" class="dropdown-item" onclick="confirmModal('<?php echo route('student/delete/'.$student['id'].'/'.$student['user_id']); ?>', showAllStudents)"><?php echo get_phrase('delete'); ?></a>
  					</div>
  				</div>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>

<script type="text/javascript">
  initDataTable('basic-datatable');
</script>
