<div class="row">
  <div class="col-xl-7 col-lg-12 col-md-12 col-sm-12">
    <div class="card">
      <div class="card-body py-2">
        <h4 class="header-title d-inline-block"><?php echo get_phrase('system_settings') ;?></h4>
        <form method="POST" class="col-12 systemAjaxForm" action="<?php echo route('system_settings/update') ;?>" id = "system_settings">
          <div class="col-12">
            <div class="form-group row mb-3">
              <label class="col-md-3 col-form-label" for="system_name"> <?php echo get_phrase('system_name') ;?></label>
              <div class="col-md-9">
                <input type="text" id="system_name" name="system_name" class="form-control"  value="<?php echo get_settings('system_name') ;?>" required>
              </div>
            </div>

            <div class="form-group row mb-3">
              <label class="col-md-3 col-form-label" for="system_title"><?php echo get_phrase('title') ;?></label>
              <div class="col-md-9">
                <input type="text" id="system_title" name="system_title" class="form-control"  value="<?php echo get_settings('system_title') ;?>" required>
              </div>
            </div>

            <div class="form-group row mb-3">
              <label class="col-md-3 col-form-label" for="system_email"> <?php echo get_phrase('school_email') ;?></label>
              <div class="col-md-9">
                <input type="email" id="system_email" name="system_email" class="form-control"  value="<?php echo get_settings('system_email') ;?>" required>
              </div>
            </div>
            <div class="form-group row mb-3">
              <label class="col-md-3 col-form-label" for="phone"> <?php echo get_phrase('phone') ;?></label>
              <div class="col-md-9">
                <input type="text" id="phone" name="phone" class="form-control"  value="<?php echo get_settings('phone') ;?>" required>
              </div>
            </div>

            <div class="form-group row mb-3">
              <label class="col-md-3 col-form-label" for="fax"> <?php echo get_phrase('fax') ;?></label>
              <div class="col-md-9">
                <input type="text" id="fax" name="fax" class="form-control"  value="<?php echo get_settings('fax') ;?>" required>
              </div>
            </div>

            <div class="form-group row mb-3">
              <label class="col-md-3 col-form-label" for="address"> <?php echo get_phrase('address') ;?></label>
              <div class="col-md-9">
                <textarea class="form-control" id="address" name = "address" rows="5" required><?php echo get_settings('address') ;?></textarea>
              </div>
            </div>
            <div class="form-group row mb-3">
              <label class="col-md-3 col-form-label" for="purchase_code"> <?php echo get_phrase('purchase_code') ;?></label>
              <div class="col-md-9">
                <input type="text" id="purchase_code" name="purchase_code" class="form-control"  value="<?php echo get_settings('purchase_code') ;?>" required>
              </div>
            </div>
            <div class="form-group row mb-3">
              <label class="col-md-3 col-form-label" for="timezone"> <?php echo get_phrase('timezone') ;?></label>

              <div class="col-md-9">
                <select class="form-control select2" data-bs-toggle="select2" id="timezone" name="timezone">
                  <?php $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL); ?>
                  <?php foreach ($tzlist as $tz): ?>
                    <option value="<?php echo $tz ;?>" <?php if(get_settings('timezone') == $tz) echo 'selected'; ?>><?php echo $tz ;?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="form-group row mb-3">
              <label class="col-md-3 col-form-label" for="footer_text"> <?php echo get_phrase('footer_text') ;?></label>
              <div class="col-md-9">
                <input type="text" id="footer_text" name="footer_text" class="form-control"  value="<?php echo get_settings('footer_text') ;?>" required>
              </div>
            </div>

            <div class="form-group row mb-3">
              <label class="col-md-3 col-form-label" for="footer_link"><?php echo get_phrase('footer_link') ;?></label>
              <div class="col-md-9">
                <input type="text" id="footer_link" name="footer_link" class="form-control"  value="<?php echo get_settings('footer_link') ;?>" required>
              </div>
            </div>

            <?php if(addon_status('online_courses')): ?>
              <div class="form-group row mb-3">
                <label class="col-md-3 col-form-label" for="youtube_api_key"><?php echo get_phrase('youtube_api_key') ;?></label>
                <div class="col-md-9">
                  <input type="text" id="youtube_api_key" placeholder="<?php echo get_phrase('youtube_api_key') ;?>" name="youtube_api_key" class="form-control"  value="<?php echo get_settings('youtube_api_key') ;?>">
                </div>
              </div>

              <div class="form-group row mb-3">
                <label class="col-md-3 col-form-label" for="vimeo_api_key"><?php echo get_phrase('vimeo_api_key') ;?></label>
                <div class="col-md-9">
                  <input type="text" id="vimeo_api_key" placeholder="<?php echo get_phrase('vimeo_api_key') ;?>" name="vimeo_api_key" class="form-control"  value="<?php echo get_settings('vimeo_api_key') ;?>">
                </div>
              </div>
            <?php endif; ?>

            <div class="text-center">
              <button type="submit" class="btn btn-secondary col-xl-4 col-lg-4 col-md-12 col-sm-12" onclick="updateSystemInfo($('#system_name').val())"><?php echo get_phrase('update_settings') ;?></button>
            </div>
          </div>
        </form>

      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div>
  <div class="col-xl-5 col-lg-12 col-md-12 col-sm-12">
    <div class="card">
      <div class="card-body">
        <h4 class="header-title"><?php echo get_phrase('product_update') ;?></h4>
        <form action="<?php echo site_url('updater/update'); ?>" method="post" enctype="multipart/form-data">
          <label for="file_name"><?php echo get_phrase('file'); ?></label>
          <input type="file" class="form-control" name="file_name" id="file_name">
          <button class="btn btn-secondary mt-3 float-end"><?php echo get_phrase('update'); ?></button>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body">
        <h4 class="header-title"><?php echo get_phrase('system_logo') ;?></h4>
        <form method="POST" class="col-12 systemLogoAjaxForm" action="<?php echo route('system_settings/logo_update') ;?>" id = "system_settings" enctype="multipart/form-data">

          <div class="row justify-content-center">
            <div class="col-xl-4">
              <div class="form-group row mb-3">
                <label class="col-md-3 col-form-label" for="example-fileinput"><?php echo get_phrase('regular_logo'); ?></label>
                <div class="col-md-9 custom-file-upload">
                  <div class="wrapper-image-preview" style="margin-left: -6px;">
                    <div class="box" style="width: 250px;">
                      <div class="js--image-preview" style="background-image: url(<?php echo $this->settings_model->get_logo_dark(); ?>); background-color: #F5F5F5;"></div>
                      <div class="upload-options">
                        <label for="dark_logo" class="btn"> <i class="mdi mdi-camera"></i> <?php echo get_phrase('upload_logo'); ?> <small>(600 X 150)</small></label>
                        <input id="dark_logo" style="visibility:hidden;" type="file" class="image-upload" name="dark_logo" accept="image/*">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-4">
              <div class="form-group row mb-3">
                <label class="col-md-3 col-form-label" for="example-fileinput"><?php echo get_phrase('light_logo'); ?></label>
                <div class="col-md-9 custom-file-upload">
                  <div class="wrapper-image-preview" style="margin-left: -6px;">
                    <div class="box" style="width: 250px;">
                      <div class="js--image-preview" style="background-image: url(<?php echo $this->settings_model->get_logo_light(); ?>); background-color: #F5F5F5;"></div>
                      <div class="upload-options">
                        <label for="light_logo" class="btn"> <i class="mdi mdi-camera"></i> <?php echo get_phrase('upload_logo'); ?> <small>(600 X 150)</small></label>
                        <input id="light_logo" style="visibility:hidden;" type="file" class="image-upload" name="light_logo" accept="image/*">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-4">
              <div class="form-group row mb-3">
                <label class="col-md-3 col-form-label" for="example-fileinput"><?php echo get_phrase('small_logo'); ?></label>
                <div class="col-md-9 custom-file-upload">
                  <div class="wrapper-image-preview" style="margin-left: -6px;">
                    <div class="box" style="width: 250px;">
                      <div class="js--image-preview" style="background-image: url(<?php echo $this->settings_model->get_logo_light('small'); ?>); background-color: #F5F5F5;"></div>
                      <div class="upload-options">
                        <label for="small_logo" class="btn"> <i class="mdi mdi-camera"></i> <?php echo get_phrase('upload_small_logo'); ?> <small>(80 X 80)</small></label>
                        <input id="small_logo" style="visibility:hidden;" type="file" class="image-upload" name="small_logo" accept="image/*">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-4">
              <div class="form-group row mb-3">
                <label class="col-md-3 col-form-label" for="example-fileinput"><?php echo get_phrase('favicon'); ?></label>
                <div class="col-md-9 custom-file-upload">
                  <div class="wrapper-image-preview" style="margin-left: -6px;">
                    <div class="box" style="width: 250px;">
                      <div class="js--image-preview" style="background-image: url(<?php echo $this->settings_model->get_favicon(); ?>); background-color: #F5F5F5;"></div>
                      <div class="upload-options">
                        <label for="favicon" class="btn"> <i class="mdi mdi-camera"></i> <?php echo get_phrase('upload_favicon'); ?> <small>(80 X 80)</small></label>
                        <input id="favicon" style="visibility:hidden;" type="file" class="image-upload" name="favicon" accept="image/*">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-secondary col-xl-4 col-lg-6 col-md-12 col-sm-12" onclick="updateSystemLogo()"><?php echo get_phrase('update_logo') ;?></button>
          </div>
        </form>
      </div> <!-- end card body-->
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function () {
  $('select.select2:not(.normal)').each(function () { $(this).select2({ dropdownParent: '#right-modal' }); }); //initSelect2(['#timezone']);
});
</script>
