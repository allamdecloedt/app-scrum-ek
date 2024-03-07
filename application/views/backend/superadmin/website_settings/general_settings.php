<div class="card">
  <div class="card-body">
    <h4 class="header-title"><?php echo get_phrase('general_settings') ;?></h4>
    <form method="POST" class="col-12 generalSettingsAjaxForm" action="<?php echo route('website_update/general_settings') ;?>" id = "general_settings">
      <div class="row justify-content-left">
        <div class="col-12">
          <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="website_title"> <?php echo get_phrase('website_title') ;?></label>
            <div class="col-md-9">
              <input type="text" id="website_title" name="website_title" class="form-control"  value="<?php echo get_frontend_settings('website_title') ;?>" required>
            </div>
          </div>

          <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="social_links"> <?php echo get_phrase('social_links') ;?></label>
            <div class="col-md-9">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="mdi mdi-facebook"></i></span>
                </div>
                <input type="text" class="form-control" name="facebook_link" value="<?php echo get_frontend_settings('facebook'); ?>">
              </div>
            </div>
          </div>

          <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for=""></label>
            <div class="col-md-9">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="mdi mdi-twitter"></i></span>
                </div>
                <input type="text" class="form-control" name="twitter_link" value="<?php echo get_frontend_settings('twitter'); ?>">
              </div>
            </div>
          </div>

          <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for=""></label>
            <div class="col-md-9">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="mdi mdi-linkedin"></i></span>
                </div>
                <input type="text" class="form-control" name="linkedin_link" value="<?php echo get_frontend_settings('linkedin'); ?>">
              </div>
            </div>
          </div>

          <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for=""></label>
            <div class="col-md-9">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="mdi mdi-google"></i></span>
                </div>
                <input type="text" class="form-control" name="google_link" value="<?php echo get_frontend_settings('google'); ?>">
              </div>
            </div>
          </div>

          <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for=""></label>
            <div class="col-md-9">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="mdi mdi-youtube"></i></span>
                </div>
                <input type="text" class="form-control" name="youtube_link" value="<?php echo get_frontend_settings('youtube'); ?>">
              </div>
            </div>
          </div>

          <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for=""></label>
            <div class="col-md-9">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="mdi mdi-instagram"></i></span>
                </div>
                <input type="text" class="form-control" name="instagram_link" value="<?php echo get_frontend_settings('instagram'); ?>">
              </div>
            </div>
          </div>

          <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="homepage_note_title"> <?php echo get_phrase('homepage_note_title') ;?></label>
            <div class="col-md-9">
              <input type="text" id="homepage_note_title" name="homepage_note_title" class="form-control"  value="<?php echo get_frontend_settings('homepage_note_title') ;?>" required>
            </div>
          </div>

          <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="homepage_note_description"> <?php echo get_phrase('homepage_note_description') ;?></label>
            <div class="col-md-9">
              <textarea name="homepage_note_description" id="homepage_note_description" class="form-control" rows="8" cols="80"><?php echo get_frontend_settings('homepage_note_description'); ?></textarea>
            </div>
          </div>

          <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="copyright_text"> <?php echo get_phrase('copyright_text') ;?></label>
            <div class="col-md-9">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="mdi mdi-copyright"></i></span>
                </div>
                <input type="text" class="form-control" name="copyright_text" value="<?php echo get_frontend_settings('copyright_text'); ?>">
              </div>
            </div>
          </div>

          <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="example-fileinput"><?php echo get_phrase('header_logo'); ?></label>
            <div class="col-md-9 custom-file-upload">
              <div class="wrapper-image-preview" style="margin-left: -6px;">
                <div class="box" style="width: 250px;">
                  <div class="js--image-preview" style="background-image: url(<?php echo $this->frontend_model->get_header_logo(); ?>); background-color: #F5F5F5;"></div>
                  <div class="upload-options">
                    <label for="header_logo" class="btn"> <i class="mdi mdi-camera"></i> <?php echo get_phrase('upload_header_logo'); ?> <small>(80 X 80)</small></label>
                    <input id="header_logo" style="visibility:hidden;" type="file" class="image-upload" name="header_logo" accept="image/*">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="example-fileinput"><?php echo get_phrase('footer_logo'); ?></label>
            <div class="col-md-9 custom-file-upload">
              <div class="wrapper-image-preview" style="margin-left: -6px;">
                <div class="box" style="width: 250px;">
                  <div class="js--image-preview" style="background-image: url(<?php echo $this->frontend_model->get_footer_logo(); ?>); background-color: #F5F5F5;"></div>
                  <div class="upload-options">
                    <label for="footer_logo" class="btn"> <i class="mdi mdi-camera"></i> <?php echo get_phrase('upload_footer_logo'); ?> <small>(80 X 80)</small></label>
                    <input id="footer_logo" style="visibility:hidden;" type="file" class="image-upload" name="footer_logo" accept="image/*">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-secondary col-xl-4 col-lg-4 col-md-12 col-sm-12" onclick="updateGeneralSettings()"><?php echo get_phrase('update_settings') ;?></button>
          </div>
        </div>
      </div>
    </form>

  </div> <!-- end card body-->
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('#homepage_note_description').summernote();
});
</script>