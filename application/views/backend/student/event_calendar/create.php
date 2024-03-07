<form method="POST" class="d-block ajaxForm" action="<?php echo route('event_calendar/create'); ?>">
  <div class="form-row">
    <div class="form-group mb-1">
      <label for="title"><?php echo get_phrase('event_title'); ?></label>
      <input type="text" class="form-control" id="title" name = "title" required>
      <small id="name_help" class="form-text text-muted"><?php echo get_phrase('provide_title_name'); ?></small>
    </div>
    <div class="form-group mb-1">
      <label for="starting_date"><?php echo get_phrase('event_starting_date'); ?></label>
      <input type="text" value="<?php echo date('m/d/Y'); ?>" class="form-control" id="starting_date" name = "starting_date" data-provide = "datepicker" required>
      <small id="name_help" class="form-text text-muted"><?php echo get_phrase('provide_starting_date'); ?></small>
    </div>

    <div class="form-group mb-1">
      <label for="starting_date"><?php echo get_phrase('event_ending_date'); ?></label>
      <input type="text" value="<?php echo date('m/d/Y'); ?>" class="form-control" id="ending_date" name = "ending_date" data-provide = "datepicker" required>
      <small id="name_help" class="form-text text-muted"><?php echo get_phrase('provide_ending_date'); ?></small>
    </div>

    <div class="form-group  col-md-12">
      <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('save_event'); ?></button>
    </div>
  </div>
</form>

<script>
$(document).ready(function() {
  
});
$(".ajaxForm").validate({}); // Jquery form validation initialization
$(".ajaxForm").submit(function(e) {
  var form = $(this);
  ajaxSubmit(e, form, showAllEvents);
});
</script>
