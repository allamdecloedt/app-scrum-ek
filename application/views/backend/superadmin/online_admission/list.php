

<?php
  $school_id = school_id();
?>
<table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%">
  <thead>
    <tr style="background-color: #313a46; color: #ababab;">
      <th><?php echo get_phrase('photo'); ?></th>
      <th><?php echo get_phrase('name'); ?></th>
      <th><?php echo get_phrase('email'); ?></th>
      <th><?php echo get_phrase('education_qualification'); ?></th>
      <th><?php echo get_phrase('options'); ?></th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach($applications->result_array() as $application){
      $student = $this->db->get_where('students', array('user_id' => $application['id']))->row_array();
      ?>
      <tr>
        <td>
          <img class="rounded-circle" width="50" src="<?php echo $this->user_model->get_user_image($application['id']); ?>">
        </td>
        <td><?php echo $application['name']; ?></td>
        <td><?php echo $application['email']; ?></td>
        <td><a href="<?php echo base_url('uploads/admission_docs/'.$application['id'].'.pdf'); ?>" download><?php echo get_phrase('education_qualification'); ?> <i class="mdi mdi-download"></i></td>
        <td>
          <div class="dropdown text-center">
            <button type="button" class="btn btn-sm btn-icon btn-rounded btn-outline-secondary dropdown-btn dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></button>
            <div class="dropdown-menu dropdown-menu-right">
              <a href="javascript:void(0);" class="dropdown-item"  onclick="largeModal('<?php echo site_url('modal/popup/student/profile/'.$student['id'])?>', '<?php echo $this->db->get_where('schools', array('id' => $school_id))->row('name'); ?>')"><?php echo get_phrase('profile'); ?></a>
              <!-- item-->
              <a href="javascript:;" onclick="rightModal('<?php echo site_url('modal/popup/online_admission/add/'.$student['id'])?>', '<?php echo get_phrase('assign_class_and_section'); ?>');" class="dropdown-item"><?php echo get_phrase('approved'); ?></a>
              <!-- item -->
              <a href="javascript:;" class="dropdown-item" onclick="confirmModalRedirect('<?php echo site_url('superadmin/online_admission/delete/'.$application['id']); ?>')"><?php echo get_phrase('delete'); ?></a>
            </div>
          </div>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>