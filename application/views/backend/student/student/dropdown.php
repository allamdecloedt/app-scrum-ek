<?php if (count($enrolments) > 0): ?>
  <?php foreach ($enrolments as $enrolment): ?>
    <option value="<?php echo $enrolment['student_id']; ?>"><?php echo $enrolment['name']; ?></option>
  <?php endforeach; ?>
<?php else: ?>
  <option value=""><?php echo get_phrase('no_student_found'); ?></option>
<?php endif; ?>
