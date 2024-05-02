

<?php
  $school_id = school_id();
?>
<table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%">
  <thead>
    <tr style="background-color: #313a46; color: #ababab;">
      <th><?php echo get_phrase('name'); ?></th>
      <th><?php echo get_phrase('email'); ?></th>
      <th><?php echo get_phrase('phone'); ?></th>
      <th><?php echo get_phrase('description'); ?></th>
      <th><?php echo get_phrase('category'); ?></th>
      <th><?php echo get_phrase('options'); ?></th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach($schools->result_array() as $schools){
      $user = $this->db->get_where('users', array('school_id' => $schools['id']))->row_array();
      // $student = $this->db->get_where('students', array('user_id' => $application['id']))->row_array();
      ?>
      <tr>
        <!-- <td>
          <img class="rounded-circle" width="50" src="<?php echo $this->user_model->get_user_image($application['id']); ?>">
        </td> -->
        <td><?php echo $schools['name']; ?></td>
        <td><?php echo $user['email']; ?></td>
        <td><?php echo $schools['phone']; ?></td>
        <td><?php echo $schools['description']; ?></td>
        <td><?php echo $schools['category']; ?></td>
        <td>
          <div class="dropdown text-center">
            <button type="button" class="btn btn-sm btn-icon btn-rounded btn-outline-secondary dropdown-btn dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></button>
            <div class="dropdown-menu dropdown-menu-right">
              <!-- item-->
              <a href="javascript:;" onclick="rightModal('<?php echo site_url('modal/popup/online_admission_school/add/'.$schools['id'])?>', '<?php echo get_phrase('approved_school'); ?>');" class="dropdown-item"><?php echo get_phrase('approved'); ?></a>
              <!-- item -->
              <a href="javascript:;" class="dropdown-item" onclick="confirmModalRedirect('<?php echo site_url('superadmin/online_admission_school/delete/'.$schools['id']); ?>')"><?php echo get_phrase('delete'); ?></a>
            </div>
          </div>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>