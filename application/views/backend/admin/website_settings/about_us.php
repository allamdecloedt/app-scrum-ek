<div class="card">
  <div class="card-body">
    <h4 class="header-title"><?php echo get_phrase('about_us_settings') ;?></h4>
    <form method="POST" class="col-12 aboutUsSettings" action="<?php echo route('about_us/update') ;?>" id = "about_us_settings">
      <div class="row justify-content-left">
        <div class="col-12">
          <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="about_us"> <?php echo get_phrase('about_us') ;?></label>
            <div class="col-md-9">
              <textarea name="about_us" id="about_us" class="form-control" rows="8" cols="80"><?php echo get_frontend_settings('about_us'); ?></textarea>
            </div>
          </div>
          <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="example-fileinput"><?php echo get_phrase('about_us_banner'); ?></label>
            <div class="col-md-9 custom-file-upload">
              <div class="wrapper-image-preview" style="margin-left: -6px;">
                <div class="box" style="width: 250px;">
                  <div class="js--image-preview" style="background-image: url(<?php echo $this->frontend_model->get_about_image(); ?>); background-color: #F5F5F5;"></div>
                  <div class="upload-options">
                    <label for="about_us_image" class="btn"> <i class="mdi mdi-camera"></i> <?php echo get_phrase('upload_about_us_image'); ?> </label>
                    <input id="about_us_image" style="visibility:hidden;" type="file" class="image-upload" name="about_us_image" accept="image/*">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-secondary col-xl-4 col-lg-4 col-md-12 col-sm-12" onclick="updateAboutUsSettings()"><?php echo get_phrase('update_settings') ;?></button>
          </div>
        </div>
      </div>
    </form>

  </div> <!-- end card body-->
</div>

<script type="text/javascript">
$(document).ready(function () {
  initSummerNote(['#about_us']);
});
</script>
