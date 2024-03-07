<?php
if (isset($class_id)):
  $school_id  = school_id();
  $check_data = $this->db->get_where('subjects', array('school_id' => $school_id, 'session' => active_session(), 'class_id' => $class_id))->result_array();
  if (count($check_data) > 0):?>
  <table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%">
    <thead>
      <tr style="background-color: #313a46; color: #ababab;">
        <th><?php echo get_phrase('name'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php
      $school_id = school_id();
      $subjects = $this->db->get_where('subjects', array('school_id' => $school_id, 'session' => active_session(), 'class_id' => $class_id))->result_array();
      foreach($subjects as $subject){
        ?>
        <tr>
          <td><?php echo $subject['name']; ?></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
<?php else: ?>
  <?php include APPPATH.'views/backend/empty.php'; ?>
<?php endif; ?>
<?php else: ?>
  <?php include APPPATH.'views/backend/empty.php'; ?>
<?php endif; ?>
