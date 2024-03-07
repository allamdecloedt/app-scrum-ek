<form method="POST" class="d-block ajaxForm" action="<?php echo route('events/create'); ?>">
  <div class="form-row">
    <div class="form-group mb-1">
      <label for="title"><?php echo get_phrase('event_title'); ?></label>
      <input type="text" class="form-control" id="title" name = "title" required>
      <small id="name_help" class="form-text text-muted"><?php echo get_phrase('provide_title_name'); ?></small>
    </div>
    <div class="form-group mb-1">
      <label for="timestamp"><?php echo get_phrase('date'); ?></label>
      <input type="text" value="<?php echo date('m/d/Y'); ?>" class="form-control" id="timestamp" name = "timestamp" data-provide = "datepicker" required>
      <small id="name_help" class="form-text text-muted"><?php echo get_phrase('provide_date'); ?></small>
    </div>

    <div class="form-group mb-1">
        <label for="status"><?php echo get_phrase('status'); ?></label>
        <select name="status" id="status" class="form-control select2" data-toggle = "select2">
            <option value="1"><?php echo get_phrase('active'); ?></option>
            <option value="0"><?php echo get_phrase('inactive'); ?></option>
        </select>
        <small id="" class="form-text text-muted"><?php echo get_phrase('visibility_on_website'); ?></small>
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

$('select.select2:not(.normal)').each(function () { $(this).select2({ dropdownParent: '#right-modal' }); }); //initSelect2(['#status']);
</script>
