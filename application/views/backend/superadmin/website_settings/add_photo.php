<form method="POST" class="d-block ajaxForm" action="<?php echo route('frontend_gallery/gallery_photo_upload/'.$param1); ?>">
  <div class="form-row">
    <div class="form-group mb-1">
        <label for="addon_zip"><?php echo get_phrase('upload_gallery_photo'); ?></label>
        <div class="custom-file-upload d-inline-block">
            <input type="file" class="form-control" id="gallery_photo" name = "gallery_photo">
        </div>
    </div>

    <div class="form-group  col-md-12">
      <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('save_to_gallery'); ?></button>
    </div>
  </div>
</form>

<script>
$(".ajaxForm").validate({}); // Jquery form validation initialization
$(".ajaxForm").submit(function(e) {
  var form = $(this);
  ajaxSubmit(e, form, showAllGalleryPhotos);
});
initCustomFileUploader();
</script>
