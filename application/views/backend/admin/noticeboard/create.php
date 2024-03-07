<?php
  if(isset($param1) && !empty($param1)){
    $timestamp = strtotime($param1);
  }else{
    $timestamp = strtotime(date('m/d/Y'));
  }
 ?>
<form method="POST" class="d-block ajaxForm" action="<?php echo route('noticeboard/create'); ?>">
  <div class="form-row">

    <div class="form-group mb-1">
      <label for="notice_title"><?php echo get_phrase('notice_title'); ?></label>
      <input type="text" class="form-control" id="notice_title" name = "notice_title" required>
      <small id="name_help" class="form-text text-muted"><?php echo get_phrase('provide_title_name'); ?></small>
    </div>
    <div class="form-group mb-1">
      <label for="date"><?php echo get_phrase('date'); ?></label>
      <input type="text" value="<?php echo date('m/d/Y', $timestamp); ?>" class="form-control" id="date" name = "date" data-provide = "datepicker" required>
      <small id="name_help" class="form-text text-muted"><?php echo get_phrase('provide_date'); ?></small>
    </div>

    <div class="form-group mb-1">
      <label for="notice"><?php echo get_phrase('notice'); ?></label>
      <textarea name="notice" class="form-control" rows="8" cols="80" required></textarea>
      <small id="name_help" class="form-text text-muted"><?php echo get_phrase('provide_notice_details'); ?></small>
    </div>

    <div class="form-group mb-1">
        <label for="show_on_website"><?php echo get_phrase('show_on_website'); ?></label>
        <select name="show_on_website" id="show_on_website" class="form-control select2" data-toggle = "select2">
            <option value="1"><?php echo get_phrase('show'); ?></option>
            <option value="0"><?php echo get_phrase('do_not_need_to_show'); ?></option>
        </select>
        <small id="" class="form-text text-muted"><?php echo get_phrase('notice_status'); ?></small>
    </div>

    <div class="form-group mb-1 d-inline-block">
      <style type="text/css">.file-upload-input{width: 260px !important;}</style>
        <label for="notice_photo"><?php echo get_phrase('upload_notice_photo'); ?></label>
        <input type="file" class="form-control" id="notice_photo" name = "notice_photo">
    </div>

    <div class="form-group  col-md-12">
      <button class="btn btn-primary" type="submit"><?php echo get_phrase('save_notice'); ?></button>
    </div>
  </div>
</form>

<script>
$(document).ready(function() {

});
$(".ajaxForm").validate({}); // Jquery form validation initialization
$(".ajaxForm").submit(function(e) {
  var form = $(this);
  ajaxSubmit(e, form, showAllNotices);
});

$('select.select2:not(.normal)').each(function () { $(this).select2({ dropdownParent: '#right-modal' }); }); //initSelect2(['#status', '#show_on_website']);
initCustomFileUploader();
</script>
