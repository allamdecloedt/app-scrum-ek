<?php if (get_common_settings('recaptcha_status')): ?>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php endif; ?>
<!-- ========== MAIN ========== -->
<main id="content" role="main">
  <!-- Header Section -->
  <div class="general-container container-fluid">
    <div class="row general-header align-items-center">
      <h1 class='col-6 text_fade text-uppercase text-center '> contact us </h1>
      <!-- Div Section For Header Background Fade In-Out Animation-->
      <div></div>
      <div></div>
      <div></div>
      <!-- End Div Section-->
    </div>
    <img class="ct-img rellax " data-rellax-speed="1.5"
      src="<?php echo base_url('assets/frontend/ultimate/img/contact us/cu-img-top.jpg') ?>" alt="">
          <div class="general-container-ol"></div>

  </div>
  <!-- End Header Section -->
  <!-- Contenu de votre page -->
  <?php $this->load->view('frontend/alert_view'); ?>
      <!-- Autres contenus de la page -->

  <!-- Contact Content Section -->
  <div class="fluid-container card-main-container">
    <div class="container card-container">
      <div class="row">
        <div class="col-12 ">
          <div class="row  justify-content-center card-row">
            <div class="col-9 col-lg-4 ">
              <div class="card text-center">
                <svg class="card-img-top my-3" xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                  fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                  <path fill-rule="evenodd"
                    d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z" />
                </svg>
                <div class="card-body">
                  <h5 class="card-title text-uppercase">Phone</h5>
                  <h6 class="card-subtitle mb-2 text-muted">Casablanca Office</h6>
                  <p class="card-text py-3">+212 6 15 15 15 16</p>
                  <div class="card-bar"></div>
                </div>
              </div>
            </div>
            <div class="col-9 col-lg-4 ">
              <div class="card text-center">
                <svg class="card-img-top my-3" xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                  fill="currentColor" class="bi bi-chat-left-text-fill" viewBox="0 0 16 16">
                  <path
                    d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4.414a1 1 0 0 0-.707.293L.854 15.146A.5.5 0 0 1 0 14.793zm3.5 1a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1zm0 2.5a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1zm0 2.5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1z" />
                </svg>
                <div class="card-body">
                  <h5 class="card-title text-uppercase">Email</h5>
                  <h6 class="card-subtitle mb-2 text-muted">Casablanca Office</h6>
                  <p class="card-text py-3">contact@decloedtacademy.com</p>
                  <div class="card-bar"></div>
                </div>
              </div>
            </div>
            <div class="col-9 col-lg-4 mb-11 mb-lg-0">
              <div class="card text-center">
                <svg class="card-img-top my-3" xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                  fill="currentColor" class="bi bi-globe" viewBox="0 0 16 16">
                  <path
                    d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m7.5-6.923c-.67.204-1.335.82-1.887 1.855A8 8 0 0 0 5.145 4H7.5zM4.09 4a9.3 9.3 0 0 1 .64-1.539 7 7 0 0 1 .597-.933A7.03 7.03 0 0 0 2.255 4zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a7 7 0 0 0-.656 2.5zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5zM8.5 5v2.5h2.99a12.5 12.5 0 0 0-.337-2.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5zM5.145 12q.208.58.468 1.068c.552 1.035 1.218 1.65 1.887 1.855V12zm.182 2.472a7 7 0 0 1-.597-.933A9.3 9.3 0 0 1 4.09 12H2.255a7 7 0 0 0 3.072 2.472M3.82 11a13.7 13.7 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5zm6.853 3.472A7 7 0 0 0 13.745 12H11.91a9.3 9.3 0 0 1-.64 1.539 7 7 0 0 1-.597.933M8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855q.26-.487.468-1.068zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.7 13.7 0 0 1-.312 2.5m2.802-3.5a7 7 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7 7 0 0 0-3.072-2.472c.218.284.418.598.597.933M10.855 4a8 8 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4z" />
                </svg>
                <div class="card-body">
                  <h5 class="card-title text-uppercase">Address</h5>
                  <h6 class="card-subtitle mb-2 text-muted">Casablanca Office</h6>

                  <p class="card-text py-3">4 Rue al Kassar <br>
                    20520 Casablanca Morocco</p>
                  <div class="card-bar"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container-fluid form-section">
    <div class="row">
      <div class="col-12 col-lg-6 text-center">
        <h1 class="text-light pt-11 text-break form-text-title display-6 text-uppercase">Message us </h1>
        <p class="py-10 text-break form-text ">Lorem ipsum dolor sit amet consectetur adipisicing elit. Sunt itaque
          doloribus
          expedita possimus enim, harum commodi distinctio architecto quaerat culpa, nesciunt reprehenderit, inventore
          molestias libero fugiat in iusto eius deserunt beatae dolore! Aliquam velit distinctio maiores. Impedit,
          nisi est? Repudiandae tenetur quae autem eaque!</p>
      </div>
      <!-- Contacts Form -->
      <div class="form col-12 col-lg-6 form container g-0 my-3">
        <div class="container">
          <form class="pt-8" action="<?php echo site_url('home/contact/send'); ?>"
        method="post" class="js-validate">
        <!-- Champ caché pour le jeton CSRF -->
    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
    
            <div class="row">
              <!-- Input -->
              <div class="col-12 col-md-6">
                <div class="js-form-message">

                  <div class="input-group mb-3">
                    <span class="input-group-text">
                      <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-person-lines-fill" viewBox="0 0 16 16">
                        <path
                          d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z" />
                      </svg>
                    </span>
                    <input type="text" class="form-control shadow-none"
                      placeholder="<?php echo get_phrase('First name'); ?>" name="first_name" required
                      data-msg="Please enter your first name." data-error-class="u-has-error"
                      data-success-class="u-has-success">
                    <span class="text-danger required">*</span>
                  </div>
                </div>
              </div>
              <!-- End Input -->

              <!-- Input -->
              <div class="col-12 col-md-6">
                <div class="js-form-message">

                  <div class="input-group mb-3">
                    <span class="input-group-text">
                      <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-person-lines-fill" viewBox="0 0 16 16">
                        <path
                          d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z" />
                      </svg>
                    </span>
                    <input type="text" class="form-control shadow-none"
                      placeholder="<?php echo get_phrase('Last name'); ?>" name="last_name" required
                      data-msg="Please enter your last name." data-error-class="u-has-error"
                      data-success-class="u-has-success">
                    <span class="text-danger required">*</span>
                  </div>
                </div>
              </div>
              <!-- End Input -->
            </div>
            <div class="row">
              <!-- Input -->
              <div class="col-12 col-md-6">
                <div class="js-form-message">
                  <div class="mb-3 input-group">
                    <span class="input-group-text">
                      <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-envelope-at-fill" viewBox="0 0 16 16">
                        <path
                          d="M2 2A2 2 0 0 0 .05 3.555L8 8.414l7.95-4.859A2 2 0 0 0 14 2zm-2 9.8V4.698l5.803 3.546zm6.761-2.97-6.57 4.026A2 2 0 0 0 2 14h6.256A4.5 4.5 0 0 1 8 12.5a4.49 4.49 0 0 1 1.606-3.446l-.367-.225L8 9.586zM16 9.671V4.697l-5.803 3.546.338.208A4.5 4.5 0 0 1 12.5 8c1.414 0 2.675.652 3.5 1.671" />
                        <path
                          d="M15.834 12.244c0 1.168-.577 2.025-1.587 2.025-.503 0-1.002-.228-1.12-.648h-.043c-.118.416-.543.643-1.015.643-.77 0-1.259-.542-1.259-1.434v-.529c0-.844.481-1.4 1.26-1.4.585 0 .87.333.953.63h.03v-.568h.905v2.19c0 .272.18.42.411.42.315 0 .639-.415.639-1.39v-.118c0-1.277-.95-2.326-2.484-2.326h-.04c-1.582 0-2.64 1.067-2.64 2.724v.157c0 1.867 1.237 2.654 2.57 2.654h.045c.507 0 .935-.07 1.18-.18v.731c-.219.1-.643.175-1.237.175h-.044C10.438 16 9 14.82 9 12.646v-.214C9 10.36 10.421 9 12.485 9h.035c2.12 0 3.314 1.43 3.314 3.034zm-4.04.21v.227c0 .586.227.8.581.8.31 0 .564-.17.564-.743v-.367c0-.516-.275-.708-.572-.708-.346 0-.573.245-.573.791" />
                      </svg>
                    </span>
                    <input type="email" class="form-control shadow-none" name="email"
                      placeholder="<?php echo get_phrase('Your email address'); ?>" required
                      data-msg="Please enter a valid email address." data-error-class="u-has-error"
                      data-success-class="u-has-success">
                    <span class="text-danger required">*</span>
                  </div>
                </div>
              </div>
              <!-- End Input -->

              <!-- Input -->
              <div class="col-12 col-md-6">
                <div class="js-form-message">

                  <div class="input-group mb-3">
                    <span class="input-group-text ">
                      <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-telephone-fill" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                          d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z" />
                      </svg>
                    </span>
                    <input type="tel" class="form-control shadow-none" placeholder=" +212 622 22 22 22" name="phone"
                      required data-msg="Please enter a valid phone number." data-error-class="u-has-error"
                      data-success-class="u-has-success">
                    <span class="text-danger required">*</span>
                  </div>
                </div>
              </div>
              <!-- End Input -->

            </div>

            <div class="row">
              <!-- Input -->
              <div class="col-12 ">
                <div class="js-form-message">
                  <div class="input-group mb-3">
                    <span class="input-group-text">
                      <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-book-fill" viewBox="0 0 16 16">
                        <path
                          d="M8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783" />
                      </svg>
                    </span>
                    <input type="text" class="form-control shadow-none"
                      placeholder="<?php echo get_phrase('Location'); ?>" name="address" required
                      data-msg="Please enter your location." data-error-class="u-has-error"
                      data-success-class="u-has-success">
                    <span class="text-danger required">*</span>
                  </div>
                </div>
              </div>
              <!-- End Input -->
            </div>
            <div class="row">
              <!-- Input -->
              <div class="col-12">
                <div class="js-form-message">
                  <div class="input-group mb-3">
                    <span class="input-group-text">
                      <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-chat-right-text-fill" viewBox="0 0 16 16">
                        <path
                          d="M16 2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h9.586a1 1 0 0 1 .707.293l2.853 2.853a.5.5 0 0 0 .854-.353zM3.5 3h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1 0-1m0 2.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1 0-1m0 2.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1" />
                      </svg>
                    </span>
                    <textarea type="text" class="form-control shadow-none" rows="5"
                      placeholder="<?php echo get_phrase('comments_or_questions'); ?>" name="comment" required
                      data-msg="Please enter your message." data-error-class="u-has-error"
                      data-success-class="u-has-success"></textarea>
                    <span class="text-danger required">*</span>
                  </div>
                </div>
              </div>
              <!-- End Input -->
            </div>
            <div class="row justify-content-center">
              <?php if (get_common_settings('recaptcha_status')): ?>
                <div class="js-form-message mb-3">
                  <div class="form-group">
                    <div class="g-recaptcha" data-sitekey="<?php echo get_common_settings('recaptcha_sitekey'); ?>"></div>
                  </div>
                </div>
              <?php endif; ?>
              <button type="submit" class="btn btn-secondary btn-wide col-3 mb-3 ">Send</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="img-mid-ol"></div>
    <img class="cm-img rellax pb-11 " data-rellax-speed="1.5"
      src="<?php echo base_url('assets/frontend/ultimate/img/contact us/cu-img-mid.jpg') ?>" alt="">
  </div>
  <div class="container-fluid location-container">
    <div class="row">
      <h1 class="office-title text-center text-break py-10 text-uppercase">Decloedt Academy Location</h1>
    </div>
    <div class="row">
      <div id="map" class="g-0 col-12"></div>
    </div>
  </div>
  <!-- End Contact Content Section -->
</main>
