<?php if(get_common_settings('recaptcha_status')): ?>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php endif; ?>
<!-- ========== MAIN ========== -->
<main id="content" role="main">
  <!-- Hero Section -->
  <div class="gradient-half-primary-v1">
    <div class="container text-center space-top-4 space-top-md-4 space-top-lg-3 space-bottom-1">
      <!-- Title -->
      <div class="w-md-80 w-lg-50 mx-auto mb-5">
        <h1 class="h1 text-white">
          <span class="font-weight-semi-bold"><?php echo get_phrase('online_admission'); ?></span>
        </h1>
      </div>
      <!-- End Title -->
    </div>
  </div>
  <!-- End Hero Section -->

  <hr class="my-0">

  <!-- Admission Form Section -->
  <div class="container space-1 space-md-2">
    <!-- Title -->
    <div class="w-md-80 w-lg-50 text-center mx-md-auto mb-9">
      <span class="btn btn-xs btn-soft-success btn-pill mb-2"><?php echo get_phrase('admission_form'); ?></span>
      <h2 class="text-primary font-weight-normal">
        <?php echo get_phrase('apply_for_admission'); ?>
      </h2>
    </div>
    <!-- End Title -->

    <div class="w-lg-80 mx-auto">
      <!-- Contacts Form -->
      <form action="<?php echo site_url('home/online_admission/submit');?>" method="post" class="js-validate realtime-form" enctype="multipart/form-data">
        <div class="row">
          <!-- Input -->
          <div class="col-12"><h4 class="mx-0 pb-5"><?php echo get_phrase('student_information'); ?></h4></div>
          <div class="col-sm-6 mb-6">
            <div class="js-form-message">
              <label class="form-label">
                <?php echo get_phrase('student_name'); ?>
                <span class="text-danger">*</span>
              </label>

              <input type="text" class="form-control" name="name"  required
                     data-msg="Please enter your first name."
                     data-error-class="u-has-error"
                     data-success-class="u-has-success">
            </div>
          </div>
          <!-- End Input -->

          <!-- Input -->
          <div class="col-sm-6 mb-6">
            <div class="js-form-message">
              <label class="form-label">
                <?php echo get_phrase('student_email'); ?>
                <span class="text-danger">*</span>
              </label>

              <input type="email" class="form-control" name="email" required
                     data-msg="Please enter a valid email address."
                     data-error-class="u-has-error"
                     data-success-class="u-has-success">
            </div>
          </div>
          <!-- End Input -->

          <!-- Input -->
          <div class="col-sm-6 mb-6">
            <div class="js-form-message">
              <label class="form-label">
                <?php echo get_phrase('phone'); ?>
                <span class="text-muted">(<?php echo get_phrase('optional'); ?>)</span>
              </label>

              <input type="text" class="form-control" name="phone"
                     data-msg="Please enter a valid phone number."
                     data-error-class="u-has-error"
                     data-success-class="u-has-success">
            </div>
          </div>
          <!-- End Input -->


          <!-- Input -->
          <div class="col-sm-6 mb-6">
            <div class="js-form-message">
              <label class="form-label">
                <?php echo get_phrase('date_of_birth'); ?>
              </label>

              <input type="date" class="form-control" name="date_of_birth" required
                     data-msg="Please enter your date of birth"
                     data-error-class="u-has-error"
                     data-success-class="u-has-success">
            </div>
          </div>
          <!-- End Input -->

          <!-- Input -->
          <div class="col-sm-6 mb-6">
            <div class="js-form-message">
              <label class="form-label">
                <?php echo get_phrase('gender'); ?>
              </label>

              <select name="gender" id="gender" class="form-control"  required>
                    <option value=""><?php echo get_phrase('select_your_gender'); ?></option>
                    <option value="Male"><?php echo get_phrase('male'); ?></option>
                    <option value="Female"><?php echo get_phrase('female'); ?></option>
                    <option value="Others"><?php echo get_phrase('others'); ?></option>
                </select>
            </div>
          </div>
          <!-- End Input -->

          <!-- Input -->
          <div class="col-sm-6 mb-6">
            <div class="js-form-message">
              <label class="form-label">
                <?php echo get_phrase('blood_group'); ?>
              </label>

              <select name="blood_group" id="blood_group" class="form-control select2" data-toggle = "select2"  required>
                <option value=""><?php echo get_phrase('select_your_blood_group'); ?></option>
                <option value="a+">A+</option>
                <option value="a-">A-</option>
                <option value="b+">B+</option>
                <option value="b-">B-</option>
                <option value="ab+">AB+</option>
                <option value="ab-">AB-</option>
                <option value="o+">O+</option>
                <option value="o-">O-</option>
              </select>
            </div>
          </div>
          <!-- End Input -->

          <!-- Input -->
          <div class="col-sm-6 mb-6">
            <div class="js-form-message">
              <label class="form-label">
                <?php echo get_phrase('your_photo'); ?>
                <span class="text-danger">*</span>
              </label>

              <input id="student_image" type="file" class="image-upload" name="student_image" accept="image/*" required>
            </div>
          </div>
          <!-- End Input -->

          <!-- Input -->
          <div class="col-sm-6 mb-6">
            <div class="js-form-message">
              <label class="form-label">
                <?php echo get_phrase('educational_qualifications'); ?>
                <span class="text-muted">(<?php echo get_phrase('PDF'); ?>)</span>
              </label>

              <input id="pdf" type="file" class="image-upload" name="educational_qualifications" accept=".pdf" required>
            </div>
          </div>
          <!-- End Input -->

          <div class="w-100"></div>

          <!-- Input -->
          <div class="col-sm-12 mb-6">
            <div class="js-form-message">
              <label class="form-label">
                <?php echo get_phrase('address'); ?>
                <span class="text-danger">*</span>
              </label>

              <textarea class="form-control" rows="3" name="address" required
                      data-msg="Please enter your address."
                      data-error-class="u-has-error"
                      data-success-class="u-has-success"></textarea>
            </div>
          </div>
          <!-- End Input -->

          <div class="col-12"><h4 class="mx-0 pb-5"><?php echo get_phrase('parent_informations'); ?></h4></div>
          <div class="col-sm-6 mb-6">
            <div class="js-form-message">
              <label class="form-label">
                <?php echo get_phrase('name_of_parent'); ?>
                <span class="text-danger">*</span>
              </label>

              <input type="text" class="form-control" name="name_of_parent"  required
                     data-msg="Please enter your parent's name."
                     data-error-class="u-has-error"
                     data-success-class="u-has-success">
            </div>
          </div>
          <!-- End Input -->

          <!-- Input -->
          <div class="col-sm-6 mb-6">
            <div class="js-form-message">
              <label class="form-label">
                <?php echo get_phrase('email_of_parent'); ?>
                <span class="text-danger">*</span>
              </label>

              <input type="email" class="form-control" name="email_of_parent" required
                     data-msg="Please enter a valid email address."
                     data-error-class="u-has-error"
                     data-success-class="u-has-success">
            </div>
          </div>
          <!-- End Input -->

          <!-- Input -->
          <div class="col-sm-6 mb-6">
            <div class="js-form-message">
              <label class="form-label">
                <?php echo get_phrase('phone_number_of_parent'); ?>
                <span class="text-danger">*</span>
              </label>

              <input type="text" class="form-control" name="phone_number_of_parent" required
                     data-msg="Please enter a valid phone number."
                     data-error-class="u-has-error"
                     data-success-class="u-has-success">
            </div>
          </div>
          <!-- End Input -->

          <!-- Input -->
          <div class="col-sm-6 mb-6">
            <div class="js-form-message">
              <label class="form-label">
                <?php echo get_phrase('gender'); ?>
              </label>

              <select name="parent_gender" id="parent_gender" class="form-control"  required>
                    <option value=""><?php echo get_phrase('select_a_gender'); ?></option>
                    <option value="Male"><?php echo get_phrase('male'); ?></option>
                    <option value="Female"><?php echo get_phrase('female'); ?></option>
                    <option value="Others"><?php echo get_phrase('others'); ?></option>
                </select>
            </div>
          </div>
          <!-- End Input -->
        </div>

        <?php if(get_common_settings('recaptcha_status')): ?>
          <div class="js-form-message mb-6">
            <div class="form-group">
              <div class="g-recaptcha" data-sitekey="<?php echo get_common_settings('recaptcha_sitekey'); ?>"></div>
            </div>
          </div>
        <?php endif; ?>

        <div class="text-center">
          <button type="submit" id="submitBtn" class="btn btn-primary btn-wide transition-3d-hover mb-4"><?php echo get_phrase('Submit'); ?></button>
          <button type="reset" id="resetBtn" style="display: none;"></button>
        </div>

      </form>
      <!-- End Contacts Form -->
    </div>
  </div>
  <!-- End Contact Form Section -->



<script type="text/javascript">
  $(function() {
      $('.realtime-form').ajaxForm({
          beforeSend: function() {
          },
          uploadProgress: function(event, position, total, percentComplete) {
              
          },
          complete: function(xhr) {
              setTimeout(function(){
                  var jsonResponse = JSON.parse(xhr.responseText);
                  if(jsonResponse.status == 1){
                    success_notify(jsonResponse.message);
                    $('#resetBtn').click();
                  }else{
                    error_notify(jsonResponse.message);
                  }
              }, 500);
          },
          error: function()
          {
              //You can write here your js error message
          }
      });
  });
</script>