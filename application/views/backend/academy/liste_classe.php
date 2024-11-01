<option value="<?php echo 'all'; ?>" ><?php echo get_phrase('all'); ?></option>
<?php
$classes = $this->db->get_where('classes', array('school_id' => $school_id))->result_array();
if (count($classes) > 0):
  foreach ($classes as $classe): ?>
    <option value="<?php echo $classe['id']; ?>" ><?php echo $classe['name']; ?></option>
  <?php endforeach; ?>
<?php else: ?>
  <option value=""><?php echo get_phrase('no_classe_found'); ?></option>
<?php endif; ?>
