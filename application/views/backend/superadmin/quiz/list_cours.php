<?php

$course = $this->db->get_where('course', array('class_id' => $class_id))->result_array();


?>
  <option value=""><?php echo get_phrase('select_cours'); ?></option>
<?php
if (count($course) > 0):
  
  foreach ($course as $cours): ?>

    <option value="<?php echo $cours['id']; ?>"><?php echo $cours['title']; ?></option>
  <?php endforeach; ?>
<?php else: ?>
  <option value=""><?php echo get_phrase('no_section_found'); ?></option>
<?php endif; ?>
