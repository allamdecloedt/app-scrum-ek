<?php if (get_common_settings('recaptcha_status')): ?>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php endif; ?>

<?php
?>

<!-- ========== MAIN ========== -->
<main id="content" role="main">

  <!-- Header Section -->
  <div class="general-container container-fluid">
    <div class="general-header align-items-center">
      <h1 class='col-6 display-4 text_fade text-uppercase text-center  text-sm-break'>
        <?php echo get_phrase('start_your_journey'); ?>
      </h1>
      <!-- Div Section For Header Background Fade In-Out Animation-->
      <div></div>
      <div></div>
      <div></div>
      <!-- End Div Section-->
    </div>
    <img class="ct-img rellax " data-rellax-speed="1.5"
      src="<?php echo base_url('assets/frontend/ultimate/img/online admission/oa-img-top.jpg') ?>" alt="">
    <div class="general-container-ol"></div>
  </div>

  <!-- Admission Form Section -->
  <div class="container-fluid form-section pt-10">
    <!-- Display Error -->
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <?php echo $this->session->flashdata('error');$this->session->unset_userdata('error');  ?>
            
        </div>
    <?php endif; ?>

    <!-- Display Success -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?php echo $this->session->flashdata('success'); $this->session->unset_userdata('success'); ?>
        </div>
    <?php endif; ?>

    </div>

    <!-- Student Admission Form -->

    <form action="<?php echo site_url('admission/online_admission_student/submit/student'); ?>" method="post" id="studentform"
      class="js-validate studentform realtime-form container" enctype="multipart/form-data">
          <!-- Champ caché pour le jeton CSRF -->
     <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />

      <div class="row justify-content-center">
        <h4 class="col h2 pb-11 text-uppercase d-flex justify-content-center form-title">
          <?php echo get_phrase('student_admission'); ?>
        </h4>
        <p class="text-white h5 pb-5 text-uppercase d-flex justify-content-center form-label">
          <?php echo get_phrase('student_information'); ?>
        </p>

      </div>


      <div class="row justify-content-center">

        <!-- Input -->
        <div class="col-sm-4 col-11">
          <div class="js-form-message mb-5">
            <label class="form-label text-white">
              <?php echo get_phrase('first_name'); ?>
              <span class="text-danger">*</span>
            </label>
            <div class="input-group pt-1">
              <span class="input-group-text">
                <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                  class="bi bi-person-vcard" viewBox="0 0 16 16">
                  <path
                    d="M5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4m4-2.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5M9 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4A.5.5 0 0 1 9 8m1 2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5" />
                  <path
                    d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zM1 4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H8.96q.04-.245.04-.5C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 1 1 12z" />
                </svg>
              </span>
              <input type="text" placeholder="<?php echo get_phrase('first_name'); ?>"
                class="form-control shadow-none rounded-end" name="first_name" required
                data-msg="Please enter your first name." data-error-class="u-has-error"
                data-success-class="u-has-success">
            </div>
          </div>
        </div>
        <!-- End Input -->

        <!-- Input -->
        <div class="col-sm-4 col-11">
          <div class="js-form-message mb-5">
            <label class="form-label text-white">
              <?php echo get_phrase('last_name'); ?>
              <span class="text-danger">*</span>
            </label>
            <div class="input-group pt-1">
              <span class="input-group-text">
                <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                  class="bi bi-person-vcard" viewBox="0 0 16 16">
                  <path
                    d="M5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4m4-2.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5M9 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4A.5.5 0 0 1 9 8m1 2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5" />
                  <path
                    d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zM1 4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H8.96q.04-.245.04-.5C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 1 1 12z" />
                </svg>
              </span>
              <input type="text" placeholder="<?php echo get_phrase('last_name'); ?>"
                class="form-control shadow-none rounded-end" name="last_name" required
                data-msg="Please enter your last name." data-error-class="u-has-error"
                data-success-class="u-has-success">
            </div>
          </div>
        </div>
        <!-- End Input -->

      </div>

        <div class="row justify-content-center">

          <!-- Input -->
          <div class="col-sm-4 col-11">
            <div class="js-form-message mb-5">
              <label class="form-label text-white">
                <?php echo get_phrase('student_email'); ?>
                <span class="text-danger">*</span>
              </label>
              <div class="input-group pt-1">
                <span class="input-group-text">
                  <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-envelope-at" viewBox="0 0 16 16">
                    <path
                      d="M2 2a2 2 0 0 0-2 2v8.01A2 2 0 0 0 2 14h5.5a.5.5 0 0 0 0-1H2a1 1 0 0 1-.966-.741l5.64-3.471L8 9.583l7-4.2V8.5a.5.5 0 0 0 1 0V4a2 2 0 0 0-2-2zm3.708 6.208L1 11.105V5.383zM1 4.217V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v.217l-7 4.2z" />
                    <path
                      d="M14.247 14.269c1.01 0 1.587-.857 1.587-2.025v-.21C15.834 10.43 14.64 9 12.52 9h-.035C10.42 9 9 10.36 9 12.432v.214C9 14.82 10.438 16 12.358 16h.044c.594 0 1.018-.074 1.237-.175v-.73c-.245.11-.673.18-1.18.18h-.044c-1.334 0-2.571-.788-2.571-2.655v-.157c0-1.657 1.058-2.724 2.64-2.724h.04c1.535 0 2.484 1.05 2.484 2.326v.118c0 .975-.324 1.39-.639 1.39-.232 0-.41-.148-.41-.42v-2.19h-.906v.569h-.03c-.084-.298-.368-.63-.954-.63-.778 0-1.259.555-1.259 1.4v.528c0 .892.49 1.434 1.26 1.434.471 0 .896-.227 1.014-.643h.043c.118.42.617.648 1.12.648m-2.453-1.588v-.227c0-.546.227-.791.573-.791.297 0 .572.192.572.708v.367c0 .573-.253.744-.564.744-.354 0-.581-.215-.581-.8Z" />
                  </svg>
                </span>
                <input type="email" placeholder="<?php echo get_phrase('email'); ?>"
                  class="form-control rounded-end shadow-none " name="student_email" required
                  data-msg="Please enter a valid email address." data-error-class="u-has-error"
                  data-success-class="u-has-success">
              </div>
            </div>
          </div>
          <!-- End Input -->

          <!-- Input -->
          <div class="col-sm-4 col-11">
            <div class="js-form-message mb-5">
              <label class="form-label text-white">
                <?php echo get_phrase('phone'); ?>
                <span class="text-secondary">(<?php echo get_phrase('optional'); ?>)</span>
              </label>
              <div class="input-group pt-1">
                <span class="input-group-text ">
                  <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-telephone" viewBox="0 0 16 16">
                    <path
                      d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.6 17.6 0 0 0 4.168 6.608 17.6 17.6 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.68.68 0 0 0-.58-.122l-2.19.547a1.75 1.75 0 0 1-1.657-.459L5.482 8.062a1.75 1.75 0 0 1-.46-1.657l.548-2.19a.68.68 0 0 0-.122-.58zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z" />
                  </svg>
                </span>
                <input type="text"
                  pattern="(?=(?:\D*\d){7,15}\D*$)\+?\d+\s?\d{1,3}\s?\d{1,4}\s?\d{1,4}\s?\d{1,4}\s?\d{1,4}"
                  placeholder="+971 22 222 2222" class="form-control rounded-end shadow-none" name="phone"
                  data-msg="Please enter a valid phone number." data-error-class="u-has-error"
                  data-success-class="u-has-success">
              </div>
            </div>
          </div>
          <!-- End Input -->

        </div>




    

      <div class="row justify-content-center">
        <!-- Input -->
        <div class="col-11 col-sm-4">
          <div class="js-form-message mb-5">
            <label class="form-label text-white">
              <?php echo get_phrase('gender'); ?>
              <span class="text-danger">*</span>
            </label>

            <div class="input-group pt-1">
              <span class="input-group-text">
                <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                  class="bi bi-gender-ambiguous" viewBox="0 0 16 16">
                  <path fill-rule="evenodd"
                    d="M11.5 1a.5.5 0 0 1 0-1h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V1.707l-3.45 3.45A4 4 0 0 1 8.5 10.97V13H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V14H6a.5.5 0 0 1 0-1h1.5v-2.03a4 4 0 1 1 3.471-6.648L14.293 1zm-.997 4.346a3 3 0 1 0-5.006 3.309 3 3 0 0 0 5.006-3.31z" />
                </svg>
              </span>
              <select name="gender" id="gender" class="form-control rounded-end shadow-none" required>
                <option value=""><?php echo get_phrase('select_your_gender'); ?></option>
                <option value="Male"><?php echo get_phrase('male'); ?></option>
                <option value="Female"><?php echo get_phrase('female'); ?></option>
                <option value="Others"><?php echo get_phrase('others'); ?></option>
              </select>
            </div>
          </div>
        </div>
        <!-- End Input -->

        <!-- Input -->
        <div class="col-sm-4 col-11">
          <div class="js-form-message mb-5">
            <label class="form-label text-white">
              <?php echo get_phrase('date_of_birth'); ?>
              <span class="text-danger">*</span>

            </label>
            <div class="input-group pt-1">
              <span class="input-group-text">
                <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                  class="bi bi-calendar3" viewBox="0 0 16 16">
                  <path
                    d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2M1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857z" />
                  <path
                    d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2" />
                </svg>
              </span>
              <input type="date" class="form-control rounded-end shadow-none" name="date_of_birth" required
                data-msg="Please enter your date of birth" data-error-class="u-has-error"
                data-success-class="u-has-success">
            </div>
          </div>
        </div>
        <!-- End Input -->
      </div>

      <div class="row justify-content-center">
        <!-- Input -->
        <div class="col-sm-8 col-11 ">
          <div class="js-form-message mb-6">
            <label class="form-label text-white">
              <?php echo get_phrase('address'); ?>
              <span class="text-danger">*</span>
            </label>
            <div class="input-group pt-1">
              <span class="input-group-text">
                <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                  class="bi bi-globe" viewBox="0 0 16 16">
                  <path
                    d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m7.5-6.923c-.67.204-1.335.82-1.887 1.855A8 8 0 0 0 5.145 4H7.5zM4.09 4a9.3 9.3 0 0 1 .64-1.539 7 7 0 0 1 .597-.933A7.03 7.03 0 0 0 2.255 4zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a7 7 0 0 0-.656 2.5zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5zM8.5 5v2.5h2.99a12.5 12.5 0 0 0-.337-2.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5zM5.145 12q.208.58.468 1.068c.552 1.035 1.218 1.65 1.887 1.855V12zm.182 2.472a7 7 0 0 1-.597-.933A9.3 9.3 0 0 1 4.09 12H2.255a7 7 0 0 0 3.072 2.472M3.82 11a13.7 13.7 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5zm6.853 3.472A7 7 0 0 0 13.745 12H11.91a9.3 9.3 0 0 1-.64 1.539 7 7 0 0 1-.597.933M8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855q.26-.487.468-1.068zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.7 13.7 0 0 1-.312 2.5m2.802-3.5a7 7 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7 7 0 0 0-3.072-2.472c.218.284.418.598.597.933M10.855 4a8 8 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4z" />
                </svg>
              </span>
              <input class="form-control shadow-none" rows="3" name="address" required
                data-msg="Please enter your address." data-error-class="u-has-error" data-success-class="u-has-success">
            </div>
          </div>
        </div>
        <!-- End Input -->
      </div>

      <div class="row justify-content-center">

        <!-- Input -->
        <div class="col-sm-4 col-11">
          <div class="js-form-message mb-5">
            <label class="form-label text-white">
              <?php echo get_phrase('password'); ?>
              <span class="text-danger">*</span>

            </label>
            <div class="input-group pt-1">
              <span class="input-group-text">
                <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                  class="bi bi-key" viewBox="0 0 16 16">
                  <path
                    d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8m4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5" />
                  <path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0" />
                </svg>
              </span>
              <input type="password" id="password-student" class="form-control rounded-end shadow-none"
                name="password-student" required data-msg="Please enter a password" data-error-class="u-has-error"
                data-success-class="u-has-success">
            </div>
          </div>
        </div>
        <!-- End Input -->

        <div class="col-sm-4 col-11">
          <div class="js-form-message mb-5" id="password-repeat-div">
            <label class="form-label text-white">
              <?php echo get_phrase('repeat_password'); ?>
              <span class="text-danger">*</span>

            </label>
            <div class="input-group pt-1">
              <span class="input-group-text">
                <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                  class="bi bi-key" viewBox="0 0 16 16">
                  <path
                    d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8m4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5" />
                  <path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0" />
                </svg>
              </span>
              <input type="password" id="repeat-password-student" class="form-control rounded-end shadow-none"
                name="repeat-password-student" required data-msg="Please repeat your password"
                data-error-class="u-has-error" data-success-class="u-has-success">
            </div>
            <span id="errorMessage"
              class="text-danger display-none"><?php echo get_phrase('passwords_need_to_match'); ?>.</span>
          </div>
        </div>
        <!-- End Input -->
      </div>

      <div class="row justify-content-center mt-4">

        <!-- Input -->
        <div class="photo-modal display-none">
          <div class="modal-content">
            <div class="photo-container">
              <img class="photo-preview" src="" alt="">
              <span class="close-photo">
                <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor"
                  class="bi bi-eye-slash-fill" viewBox="0 0 16 16">
                  <path
                    d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7 7 0 0 0 2.79-.588M5.21 3.088A7 7 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474z" />
                  <path
                    d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12z" />
                </svg></span>
              <p class="loading text-uppercase"><?php echo get_phrase('loading_photo'); ?>...</p>
            </div>
          </div>
        </div>


        <div class="col-11 col-sm-8 col-lg-4">
          <div class="js-form-message">
            <div class="mb-3">
              <p class="pb-3 form-label text-white " style="text-align: center;"><?php echo get_phrase('your_photo'); ?> 
              
              </p>
              <div id="photo-preview" class="photo-preview">
                <!-- L'image sélectionnée apparaîtra ici -->
                <img src="<?php echo base_url() . 'uploads/users/placeholder.jpg' ?>" alt="Default Avatar" id="default-avatar">

              </div>
              
              <label for="student_image" class="btn btn-sm button-label form-label text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor"
                  class="bi bi-filetype-png" viewBox="0 0 16 16">
                  <path fill-rule="evenodd"
                    d="M14 4.5V14a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zm-3.76 8.132q.114.23.14.492h-.776a.8.8 0 0 0-.097-.249.7.7 0 0 0-.17-.19.7.7 0 0 0-.237-.126 1 1 0 0 0-.299-.044q-.427 0-.665.302-.234.301-.234.85v.498q0 .351.097.615a.9.9 0 0 0 .304.413.87.87 0 0 0 .519.146 1 1 0 0 0 .457-.096.67.67 0 0 0 .272-.264q.09-.164.091-.363v-.255H8.82v-.59h1.576v.798q0 .29-.097.55a1.3 1.3 0 0 1-.293.458 1.4 1.4 0 0 1-.495.313q-.296.111-.697.111a2 2 0 0 1-.753-.132 1.45 1.45 0 0 1-.533-.377 1.6 1.6 0 0 1-.32-.58 2.5 2.5 0 0 1-.105-.745v-.506q0-.543.2-.95.201-.406.582-.633.384-.228.926-.228.357 0 .636.1.281.1.48.275.2.176.314.407Z"/>
                </svg>
                <div class="file-spacer"></div>
                <span class="file-name-photo file-name"><?php echo get_phrase('choose_a_file'); ?>...</span>
              </label>
              <input id="student_image" type="file" class="inputfile" name="student_image" accept=".jpg, .jpeg, .png" >
            </div>
          </div>
        </div>

        <!-- End Input -->


      </div>

      <div class="row ">


      </div>



      <?php if (get_common_settings('recaptcha_status')): ?>
        <div class="js-form-message mb-6">
          <div class="form-group">
            <div class="g-recaptcha" data-sitekey="<?php echo get_common_settings('recaptcha_sitekey'); ?>"></div>
          </div>
        </div>
      <?php endif; ?>

      <div class="text-center">
        <button type="submit" id="submitBtn"
          class="btn btn-wide mb-11 text-uppercase submit-button"><?php echo get_phrase('apply'); ?></button>
        <button type="reset" id="resetBtn" style="display: none;"></button>
      </div>

    </form>
    <!-- End Student Admission Form -->







  </div>
  </div>
  <!-- End Contact Form Section -->

  <div class="general-container g-0 container-fluid">
    <img class="ct-img rellax " data-rellax-speed="1.5"
      src="<?php echo base_url('assets/frontend/ultimate/img/online admission/oa-img-bot.jpg') ?>" alt="">
    <div class="general-container-ol-bot"></div>

  </div>





  <script>

  const studentform = document.getElementById("studentform");


  if (studentform) {
    document.getElementById('submitBtn').addEventListener('click', function (event) {
   
     if (studentform.checkValidity()) {
     
       
       setTimeout(function () {
        studentform.reset(); 
        location.reload();
      }, 500);

      } else {
        
        studentform.reportValidity(); 
      }
    });
  }
</script>


 <script>

  document.getElementById('student_image').addEventListener('change', function(event) {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      const preview = document.getElementById('photo-preview');
      preview.innerHTML = '<img src="' + e.target.result + '" alt="Photo preview" />';
    };
    reader.readAsDataURL(file);
  }
});

</script>


 