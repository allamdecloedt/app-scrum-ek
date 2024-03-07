<form method="POST" class="d-block ajaxForm" action="<?php echo route('frontend_gallery/create'); ?>">
  <div class="form-row">
    <div class="form-group mb-1">
      <label for="title"><?php echo get_phrase('gallery_title'); ?></label>
      <input type="text" class="form-control" id="title" name = "title" required>
      <small id="name_help" class="form-text text-muted"><?php echo get_phrase('provide_title_name'); ?></small>
    </div>

    <div class="form-group mb-1">
      <label for="title"><?php echo get_phrase('description'); ?></label>
      <textarea name="description" class="form-control" rows="8" cols="80" required></textarea>
      <small id="description_help" class="form-text text-muted"><?php echo get_phrase('provide_description'); ?></small>
    </div>

    <div class="form-group mb-1">
      <label class="col-form-label" for="date_added"><?php echo get_phrase('date'); ?></label>
      <input type="text" class="form-control date" id="date_added" data-bs-toggle="date-picker" data-single-date-picker="true" name = "date_added"   value="<?php echo date('m/d/Y'); ?>" required>
    </div>

    <div class="form-group mb-1">
        <label for="show_on_website"><?php echo get_phrase('show_on_website'); ?></label>
        <select name="show_on_website" id="show_on_website" class="form-control select2" data-toggle = "select2">
            <option value="1"><?php echo get_phrase('show'); ?></option>
            <option value="0"><?php echo get_phrase('no_need_to_show'); ?></option>
        </select>
        <small id="" class="form-text text-muted"><?php echo get_phrase('visibility_on_website'); ?></small>
    </div>

    <div class="form-group mb-1">
        <label for="cover_image"><?php echo get_phrase('cover_image'); ?></label>
        <div class="custom-file-upload d-inline-block">
            <input type="file" class="form-control" id="cover_image" name = "cover_image" required>
        </div>
    </div>

    <div class="form-group  col-md-12">
      <button class="btn d-block btn-primary mt-5" type="submit"><?php echo get_phrase('save_gallery'); ?></button>
    </div>
  </div>
</form>

<script>
$(document).ready(function() {

});
$(".ajaxForm").validate({}); // Jquery form validation initialization
$(".ajaxForm").submit(function(e) {
  var form = $(this);
  ajaxSubmit(e, form, showAllGallery);
});

$('select.select2:not(.normal)').each(function () { $(this).select2({ dropdownParent: '#right-modal' }); }); //initSelect2(['#show_on_website']);
initCustomFileUploader();
$("#date_added").daterangepicker();
</script>
