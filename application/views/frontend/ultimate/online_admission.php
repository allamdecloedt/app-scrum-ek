<?php if (get_common_settings('recaptcha_status')): ?>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php endif; ?>

<!-- ========== MAIN ========== -->
<main id="content" role="main">

  <!-- Header Section -->
  <div class="general-container container-fluid">
    <div class="row general-header align-items-center">
      <h1 class='col-6 display-4 text_fade text-uppercase text-center '> <?php echo get_phrase('online_admission'); ?>
      </h1>
      <!-- Div Section For Header Background Fade In-Out Animation-->
      <div></div>
      <div></div>
      <div></div>
      <!-- End Div Section-->
    </div>
    <img class="ct-img rellax " data-rellax-speed="1.5"
      src="<?php echo base_url('uploads/images/contact/images/cu-img-top.jpg') ?>" alt="">
    <div class="general-container-ol"></div>
  </div>
  <!-- End Header Section -->



  <!-- Admission Form Section -->
  <div class="container-fluid form-section pt-10">


    <!-- Title -->
    <div class="text-center my-10">
      <h2 class=" display-6 text-white text-uppercase admission-title">
        <?php echo get_phrase('apply_for_admission'); ?>
      </h2>
    </div>
    <!-- End Title -->


    <!-- Student/School selector-->
    <form id="selectorform">
      <div class="row justify-content-center ">
        <div class="col-sm-1 text-center text-white"> <input id="studentselector" value="1" type="radio"
            name="formselector" checked></input> Student
        </div>
        <div class="col-sm-1 text-center text-white pt-sm-0 p-3"><input id="schoolselector" value="2" type="radio"
            name="formselector"></input> School
        </div>
      </div>
    </form>
  </div>

  <!-- End Student/School selector-->


  <div id="schoolform"></div>

  <!-- Contacts Form -->
  <form action="<?php echo site_url('home/online_admission/submit'); ?>" method="post" id="studentform"
    class="js-validate studentform realtime-form container" enctype="multipart/form-data">

    <div class="row justify-content-center">
      <h4 class="col h2 pb-11 text-uppercase d-flex justify-content-center form-title">student admission</h4>
    </div>

    <div class="row justify-content-center">

      <!-- Input -->
      <div class="col-sm-4 col-11">
        <div class="js-form-message">
          <label class="form-label text-white">
            <?php echo get_phrase('student_name'); ?>
            <span class="text-danger">*</span>
          </label>
          <div class="input-group mb-5 pt-1">
            <span class="input-group-text">
              <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-person-lines-fill" viewBox="0 0 16 16">
                <path
                  d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z" />
              </svg>
            </span>
            <input type="text" placeholder="<?php echo get_phrase('student_name'); ?>"
              class="form-control shadow-none rounded-end" name="name" required data-msg="Please enter your first name."
              data-error-class="u-has-error" data-success-class="u-has-success">
          </div>
        </div>
      </div>
      <!-- End Input -->

      <!-- Input -->
      <div class="col-sm-4 col-12">
        <div class="js-form-message">
          <label class="form-label text-white">
            <?php echo get_phrase('student_email'); ?>
            <span class="text-danger">*</span>
          </label>
          <div class="input-group mb-5 pt-1">
            <span class="input-group-text">
              <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-envelope-at-fill" viewBox="0 0 16 16">
                <path
                  d="M2 2A2 2 0 0 0 .05 3.555L8 8.414l7.95-4.859A2 2 0 0 0 14 2zm-2 9.8V4.698l5.803 3.546zm6.761-2.97-6.57 4.026A2 2 0 0 0 2 14h6.256A4.5 4.5 0 0 1 8 12.5a4.49 4.49 0 0 1 1.606-3.446l-.367-.225L8 9.586zM16 9.671V4.697l-5.803 3.546.338.208A4.5 4.5 0 0 1 12.5 8c1.414 0 2.675.652 3.5 1.671" />
                <path
                  d="M15.834 12.244c0 1.168-.577 2.025-1.587 2.025-.503 0-1.002-.228-1.12-.648h-.043c-.118.416-.543.643-1.015.643-.77 0-1.259-.542-1.259-1.434v-.529c0-.844.481-1.4 1.26-1.4.585 0 .87.333.953.63h.03v-.568h.905v2.19c0 .272.18.42.411.42.315 0 .639-.415.639-1.39v-.118c0-1.277-.95-2.326-2.484-2.326h-.04c-1.582 0-2.64 1.067-2.64 2.724v.157c0 1.867 1.237 2.654 2.57 2.654h.045c.507 0 .935-.07 1.18-.18v.731c-.219.1-.643.175-1.237.175h-.044C10.438 16 9 14.82 9 12.646v-.214C9 10.36 10.421 9 12.485 9h.035c2.12 0 3.314 1.43 3.314 3.034zm-4.04.21v.227c0 .586.227.8.581.8.31 0 .564-.17.564-.743v-.367c0-.516-.275-.708-.572-.708-.346 0-.573.245-.573.791" />
              </svg>
            </span>
            <input type="email" placeholder="<?php echo get_phrase('student_email'); ?>"
              class="form-control rounded-end shadow-none" name="email" required
              data-msg="Please enter a valid email address." data-error-class="u-has-error"
              data-success-class="u-has-success">
          </div>
        </div>
      </div>
      <!-- End Input -->
    </div>
    <div class="row justify-content-center">

      <!-- Input -->
      <div class="col-sm-4 col-12">
        <div class="js-form-message">
          <label class="form-label text-white">
            <?php echo get_phrase('phone'); ?>
            <span class="text-secondary">(<?php echo get_phrase('optional'); ?>)</span>
          </label>
          <div class="input-group mb-5 pt-1">
            <span class="input-group-text ">
              <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-telephone-fill" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                  d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z" />
              </svg>
            </span>
            <input type="text" pattern="(?=(?:\D*\d){7,15}\D*$)\+?\d+\s?\d{1,3}\s?\d{1,4}\s?\d{1,4}\s?\d{1,4}\s?\d{1,4}"
              placeholder="+212 622 22 22 22" class="form-control rounded-end shadow-none" name="phone"
              data-msg="Please enter a valid phone number." data-error-class="u-has-error"
              data-success-class="u-has-success">
          </div>
        </div>
      </div>
      <!-- End Input -->

      <!-- Input -->
      <div class="col-sm-4 col-12">
        <div class="js-form-message">
          <label class="form-label text-white">
            <?php echo get_phrase('date_of_birth'); ?>
            <span class="text-danger">*</span>

          </label>
          <div class="input-group mb-5 pt-1">
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
      <div class="col-12 col-sm-4">
        <div class="js-form-message">
          <label class="form-label text-white">
            <?php echo get_phrase('gender'); ?>
            <span class="text-danger">*</span>
          </label>

          <div class="input-group mb-5 pt-1">
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
      <div class="col-12 col-sm-4">
        <div class="js-form-message">
          <label class="form-label text-white">
            <?php echo get_phrase('blood_group'); ?>
            <span class="text-danger  ">*</span>

          </label>
          <div class="input-group mb-5 pt-1">
            <span class="input-group-text">
              <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-hospital" viewBox="0 0 16 16">
                <path
                  d="M8.5 5.034v1.1l.953-.55.5.867L9 7l.953.55-.5.866-.953-.55v1.1h-1v-1.1l-.953.55-.5-.866L7 7l-.953-.55.5-.866.953.55v-1.1zM13.25 9a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25zM13 11.25a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25zm.25 1.75a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25zm-11-4a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5A.25.25 0 0 0 3 9.75v-.5A.25.25 0 0 0 2.75 9zm0 2a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25zM2 13.25a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25z" />
                <path
                  d="M5 1a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1a1 1 0 0 1 1 1v4h3a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h3V3a1 1 0 0 1 1-1zm2 14h2v-3H7zm3 0h1V3H5v12h1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1zm0-14H6v1h4zm2 7v7h3V8zm-8 7V8H1v7z" />
              </svg>
            </span>
            <select name="blood_group" id="blood_group" class="form-control selec2 rounded-end shadow-none"
              data-toggle="select2" required>
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
      </div>
      <!-- End Input -->
    </div>

    <div class="row justify-content-center">

      <!-- Input -->
      <div class="col-12 col-lg-4 mt-4 pl-3 d-flex justify-content-center ">
        <div class="js-form-message">
          <div class="mb-3">
            <p class="pb-3 form-label text-white text-center"><?php echo get_phrase('your_photo'); ?> <span
                class="text-danger">*</span>
            </p>
            <label for="student_image" class="btn btn-sm button-label form-label text-white ml-7">
              <svg class="" xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor"
                class="bi bi-filetype-png" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                  d="M14 4.5V14a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zm-3.76 8.132q.114.23.14.492h-.776a.8.8 0 0 0-.097-.249.7.7 0 0 0-.17-.19.7.7 0 0 0-.237-.126 1 1 0 0 0-.299-.044q-.427 0-.665.302-.234.301-.234.85v.498q0 .351.097.615a.9.9 0 0 0 .304.413.87.87 0 0 0 .519.146 1 1 0 0 0 .457-.096.67.67 0 0 0 .272-.264q.09-.164.091-.363v-.255H8.82v-.59h1.576v.798q0 .29-.097.55a1.3 1.3 0 0 1-.293.458 1.4 1.4 0 0 1-.495.313q-.296.111-.697.111a2 2 0 0 1-.753-.132 1.45 1.45 0 0 1-.533-.377 1.6 1.6 0 0 1-.32-.58 2.5 2.5 0 0 1-.105-.745v-.506q0-.543.2-.95.201-.406.582-.633.384-.228.926-.228.357 0 .636.1.281.1.48.275.2.176.314.407Zm-8.64-.706H0v4h.791v-1.343h.803q.43 0 .732-.172.305-.177.463-.475a1.4 1.4 0 0 0 .161-.677q0-.374-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.381.57.57 0 0 1-.238.24.8.8 0 0 1-.375.082H.788v-1.406h.66q.327 0 .512.182.185.181.185.521m1.964 2.666V13.25h.032l1.761 2.675h.656v-3.999h-.75v2.66h-.032l-1.752-2.66h-.662v4z" />
              </svg>
              <div class="file-spacer"></div>
              <span class="file-name-photo file-name">Choose a file...</span>
            </label>
            <input id="student_image" type="file" class="inputfile text-white pt-2" name="student_image"
              accept="image/*" required>

          </div>
        </div>
      </div>
      <!-- End Input -->

      <!-- Input -->
      <div class="col-12 col-lg-4 mt-4 d-flex justify-content-center">
        <div class="js-form-message">
          <div class="mb-3">
            <p class="form-label pb-3 mt- mt-sm-0 text-white text-center">
              <?php echo get_phrase('educational_qualifications'); ?>
              <span class="text-secondary">(<?php echo get_phrase('PDF'); ?>)</span>
              <span class="text-danger">*</span>
            </p>
            <label for="pdf" class="form-label btn btn-sm button-label text-white ml-7">
              <svg class="" xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor"
                class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                  d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z" />
              </svg>
              <div class="file-spacer"></div>
              <span class="file-name-pdf file-name">Choose a file...</span>
            </label>

            <input id="pdf" type="file" class="inputfile text-white pt-2" name="educational_qualifications"
              accept=".pdf" required>
          </div>
        </div>
      </div>
      <!-- End Input -->


    </div>

    <div class="row justify-content-center">
      <!-- Input -->
      <div class="col-sm-6 mb-6 mt-4">
        <div class="js-form-message">
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
            <textarea class="form-control shadow-none" rows="3" name="address" required
              data-msg="Please enter your address." data-error-class="u-has-error"
              data-success-class="u-has-success"></textarea>
          </div>
        </div>
      </div>
      <!-- End Input -->
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
        class="btn btn-secondary btn-wide mb-4"><?php echo get_phrase('Submit'); ?></button>
      <button type="reset" id="resetBtn" style="display: none;"></button>
    </div>

  </form>
  <!-- End Contacts Form -->
  </div>
  </div>
  <!-- End Contact Form Section -->



  <script type="text/javascript">
    $(function () {
      $('.realtime-form').ajaxForm({
        beforeSend: function () {
        },
        uploadProgress: function (event, position, total, percentComplete) {

        },
        complete: function (xhr) {
          setTimeout(function () {
            var jsonResponse = JSON.parse(xhr.responseText);
            if (jsonResponse.status == 1) {
              success_notify(jsonResponse.message);
              $('#resetBtn').click();
            } else {
              error_notify(jsonResponse.message);
            }
          }, 500);
        },
        error: function () {
          //You can write here your js error message

        }
      });
    });
  </script>