<div class="card">
  <div class="card-body">
    <h4 class="header-title"><?php echo get_phrase('recaptcha_settings') ;?></h4>
    <form method="POST" class="col-12 updateRecaptchaSettings" action="<?php echo route('update_recaptcha_settings') ;?>" enctype="multipart/form-data">
      <div class="row justify-content-left">
        <div class="col-12">
          <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="example-fileinput"><?php echo get_phrase('recaptcha_status'); ?></label>
            <div class="col-md-9">
              <input type="radio" id="recaptcha_active" value="1" name="recaptcha_status" <?php if(get_common_settings('recaptcha_status') == 1) echo 'checked'; ?>> <label for="recaptcha_active"><?php echo get_phrase('active'); ?></label>
                        &nbsp;&nbsp;
              <input type="radio" id="recaptcha_inactive" value="0" name="recaptcha_status" <?php if(get_common_settings('recaptcha_status') == 0) echo 'checked'; ?>> <label for="recaptcha_inactive"><?php echo get_phrase('inactive'); ?></label>
            </div>
          </div>
          <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="recaptcha_sitekey"><?php echo get_phrase('recaptcha_sitekey'); ?> (v2)</label>
            <div class="col-md-9">
              <input type="text" name="recaptcha_sitekey" class="form-control" id="recaptcha_sitekey" value="<?php echo get_common_settings('recaptcha_sitekey');  ?>" required>
            </div>
          </div>
          <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="recaptcha_secretkey"><?php echo get_phrase('recaptcha_secretkey'); ?> (v2)</label>
            <div class="col-md-9">
              <input type="text" name="recaptcha_secretkey" class="form-control" id="recaptcha_secretkey" value="<?php echo get_common_settings('recaptcha_secretkey');  ?>" required>
            </div>
          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-secondary col-xl-4 col-lg-4 col-md-12 col-sm-12" onclick="updateRecaptchaSettings()"><?php echo get_phrase('update_recaptcha_settings') ;?></button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <h4 class="header-title"><?php echo get_phrase('other_settings') ;?></h4>
    <form method="POST" class="col-12 otherSettingsAjaxForm" action="<?php echo route('other_settings_update') ;?>" enctype="multipart/form-data">
      <div class="row justify-content-left">
        <div class="col-12">
          <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="example-fileinput"><?php echo get_phrase('login_page_banner'); ?></label>
            <div class="col-md-9 custom-file-upload">
              <div class="wrapper-image-preview" style="margin-left: -6px;">
                <div class="box" style="width: 250px;">
                  <div class="js--image-preview" style="background-image: url(<?php echo base_url('assets/backend/images/bg-auth.jpg') ?>); background-color: #F5F5F5;"></div>
                  <div class="upload-options">
                    <label for="login_banner" class="btn"> <i class="mdi mdi-camera"></i> <?php echo get_phrase('upload_banner'); ?> <small>(2000 X 1350)</small></label>
                    <input id="login_banner" style="visibility:hidden;" type="file" class="image-upload" name="login_banner" accept="image/*">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-secondary col-xl-4 col-lg-4 col-md-12 col-sm-12" onclick="updateOtherSettings()"><?php echo get_phrase('update_settings') ;?></button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
