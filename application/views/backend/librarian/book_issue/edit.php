<?php $book_issue_details = $this->crud_model->get_book_issue_by_id($param1); ?>

<form method="POST" class="d-block ajaxForm" action="<?php echo route('book_issue/update/'.$param1); ?>">
  <div class="form-group row mb-3">
    <label class="col-md-3 col-form-label" for="issue_date"><?php echo get_phrase('issue_date'); ?></label>
    <div class="col-md-9">
      <input type="text" class="form-control date" id="issue_date" data-bs-toggle="date-picker" data-single-date-picker="true" name = "issue_date" value="<?php echo date('m/d/Y', $book_issue_details['issue_date']); ?>" required>
    </div>
  </div>

  <div class="form-group row mb-3">
    <label class="col-md-3 col-form-label" for="class_id"><?php echo get_phrase('class'); ?></label>
    <div class="col-md-9">
      <select name="class_id" id="class_id_on_modal" class="form-control select2" data-bs-toggle="select2"  required onchange="classWiseStudentOnCreate(this.value)">
        <option value=""><?php echo get_phrase('select_a_class'); ?></option>
        <?php $classes = $this->crud_model->get_classes()->result_array(); ?>
        <?php foreach($classes as $class): ?>
          <option value="<?php echo $class['id']; ?>" <?php if ($book_issue_details['class_id'] == $class['id']): ?> selected <?php endif; ?>><?php echo $class['name']; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <div class="form-group row mb-3">
    <label class="col-md-3 col-form-label" for="student_id"> <?php echo get_phrase('student'); ?></label>
    <div class="col-md-9" id = "student_content">
      <select name="student_id" id="student_id_on_modal" class="form-control select2" data-bs-toggle="select2" required >
        <option value=""><?php echo get_phrase('select_a_student'); ?></option>
        <?php $enrolments = $this->user_model->get_student_details_by_id('class', $book_issue_details['class_id']);
        foreach ($enrolments as $enrolment): ?>
        <option value="<?php echo $enrolment['student_id']; ?>" <?php if ($book_issue_details['student_id'] == $enrolment['student_id']): ?>selected<?php endif; ?>><?php echo $enrolment['name']; ?></option>
      <?php endforeach; ?>
    </select>
  </div>
</div>

<div class="form-group row mb-3">
  <label class="col-md-3 col-form-label" for="book_id"> <?php echo get_phrase('book'); ?></label>
  <div class="col-md-9">
    <select name="book_id" id="book_id_on_modal" class="form-control" required>
      <option value=""><?php echo get_phrase('select_book'); ?></option>
      <?php
      $books = $this->crud_model->get_books()->result_array();
      foreach ($books as $book): ?>
      <option value="<?php echo $book['id']; ?>" <?php if ($book_issue_details['book_id'] == $book['id']): ?> selected <?php endif; ?>><?php echo $book['name']; ?></option>
    <?php endforeach; ?>
  </select>
</div>
</div>

<div class="form-group  col-md-12">
  <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('update_book_issue_info'); ?></button>
</div>
</form>

<script>
$(document).ready(function() {
  $('#issue_date').daterangepicker();
});

$(".ajaxForm").validate({}); // Jquery form validation initialization
$(".ajaxForm").submit(function(e) {
  var form = $(this);
  ajaxSubmit(e, form, showAllBookIssues);
});

$(document).ready(function () {
  $('select.select2:not(.normal)').each(function () { $(this).select2({ dropdownParent: '#right-modal' }); }); //initSelect2(['#class_id_on_modal', '#student_id_on_modal', '#book_id_on_modal']);
});

function classWiseStudentOnCreate(classId) {
  $.ajax({
    url: "<?php echo route('invoice/student/'); ?>"+classId,
    success: function(response){
      $('#student_id_on_modal').html(response);
    }
  });
}
</script>
