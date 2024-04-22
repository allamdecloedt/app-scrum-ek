<!-- ========== MAIN ========== -->
<?php
$slider = get_frontend_settings('slider_images');
$slider_images = json_decode($slider);
$upcoming_events = $this->frontend_model->get_frontend_upcoming_events();
?>
<main class="" id="content" role="main">

  <!-- Intro Section -->
  <nav class="nav-home"></nav>

  <div class=" intro-section">
    <div id="intro-container" class="intro-container" >

  
      <div class="container pt-5 section-height">
        <div class="row">
          <div class="col-lg-6 mb-7 mb-lg-0 align-content-center intro-container-content-front">
            <div class="pr-md-4">
              <!-- Title -->
              <div class="mb-7 intro-text-section">
                <h2 class="display-3  text-md-start text-center text-break text-uppercase">Un apprentissage qui vous
                  ressemble</h2>

                <!-- <?php echo get_frontend_settings('homepage_note_title'); ?>-->

                <p class="h6 text-center text-break intro-container-content-back">
                  <!--<?php echo htmlspecialchars_decode(get_frontend_settings('homepage_note_description')); ?>-->
                  <span class="intro-container-content-back">des competence pour aujourd'hui (et demain). nous vous
                    accompagnons dans vos premiere pas.</span>
                </p>
              </div>
              <!-- End Title -->
              <!-- Buttons -->
              <div class="">
                <div class="row">
                  <div class="col-sm-auto  w-sm-50  col-12 align-self-center pb-sm-0 pb-4 d-flex ">
                    <a class="text-light  btn btn-change5 btn-sm w-100 btn-wide"
                      href="<?php echo site_url('home/about'); ?>">
                      ONLINE ADMISSION</a>
                  </div>
                  <div class="col-sm-auto  w-50 col align-self-center ">
                    <a class="text-light intro-container-content-front btn btn-change5 btn-sm w-100 btn-wide"
                      href="<?php echo site_url('home/about'); ?>">
                      DÉCOUVREZ</a>
                  </div>
                </div>
              </div>
              <!-- End Buttons -->
            </div>
          </div>
          <!-- Intro section image-->
          <div class="col-lg-6 intro-container-content-back">
            <div class="intro-img intro-container-content-back">
              <img class="" src="uploads/images/decloedt/home/19198419.jpg" alt="Image">
            </div>
          </div>
          <!-- End Intro section image -->
          <!-- Social Media Buttons -->
          <div class="container">
            <div class="row">
              <div class="">
                <p class="social-media-main-text text-uppercase intro-container-content-front">Suivez Nous!</p>
              </div>
              <div class="col-12 social-media-buttons intro-container-content-front ">
                <ul class=" intro-container-content-front">
                  <li>
                    <a class="facebook  intro-container-content-front" href="#">
                      <span></span>
                      <span></span>
                      <span></span>
                      <span></span>
                      <svg class="mb-2  intro-container-content-front" xmlns="http://www.w3.org/2000/svg" width="16"
                        height="16" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                        <path
                          d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951" />
                      </svg>
                    </a>
                  </li>
                  <li>
                    <a class="twitter-x  intro-container-content-front" href="#">
                      <span></span>
                      <span></span>
                      <span></span>
                      <span></span>
                      <svg class="mb-2  intro-container-content-front" xmlns="http://www.w3.org/2000/svg" width="16"
                        height="16" fill="currentColor" class="bi bi-twitter-x" viewBox="0 0 16 16">
                        <path
                          d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z" />
                      </svg>
                    </a>
                  </li>
                  <li>
                    <a class="instagram  intro-container-content-front" href="#">
                      <span></span>
                      <span></span>
                      <span></span>
                      <span></span>
                      <svg class="mb-2  intro-container-content-front" xmlns="http://www.w3.org/2000/svg" width="16"
                        height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                        <path
                          d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334" />
                      </svg>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <!-- End Social Media Buttons -->
        </div>
      </div>
    </div>
    <!-- End Intro Section -->
    <svg id="visual" class="spacer-top" viewBox="0 0 900 100" xmlns="http://www.w3.org/2000/svg"
      xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1">
      <path
        d="M0 47L75 39L150 16L225 22L300 49L375 43L450 29L525 11L600 17L675 39L750 16L825 41L900 14L900 101L825 101L750 101L675 101L600 101L525 101L450 101L375 101L300 101L225 101L150 101L75 101L0 101Z"
        fill="#3a3c43"></path>
      <path
        d="M0 45L75 47L150 31L225 38L300 33L375 56L450 34L525 42L600 56L675 63L750 34L825 62L900 29L900 101L825 101L750 101L675 101L600 101L525 101L450 101L375 101L300 101L225 101L150 101L75 101L0 101Z"
        fill="#33353a"></path>
      <path
        d="M0 51L75 58L150 44L225 58L300 64L375 74L450 41L525 43L600 73L675 62L750 66L825 66L900 53L900 101L825 101L750 101L675 101L600 101L525 101L450 101L375 101L300 101L225 101L150 101L75 101L0 101Z"
        fill="#2d2e32"></path>
      <path
        d="M0 66L75 63L150 69L225 65L300 56L375 60L450 79L525 64L600 75L675 60L750 77L825 56L900 58L900 101L825 101L750 101L675 101L600 101L525 101L450 101L375 101L300 101L225 101L150 101L75 101L0 101Z"
        fill="#272729"></path>
      <path
        d="M0 92L75 82L150 83L225 88L300 78L375 93L450 73L525 79L600 94L675 81L750 82L825 90L900 82L900 101L825 101L750 101L675 101L600 101L525 101L450 101L375 101L300 101L225 101L150 101L75 101L0 101Z"
        fill="#212121"></path>
    </svg>
  </div>




  <!-- Teacher Section -->
  <div class="section-height teacher-section">
    <!-- Title -->
    <h2 class="social-media-main-text">Our Professional Teachers</h2>
    <!-- End Title -->


    <!-- Teacher Cards Carousel Start-->

    <!-- This is a place holder carousel -->


    <div class="container teacher-carousel-container">
      <div class="owl-carousel owl-theme  justify-content-center">

        <div class="teacher-card ">
          <div class="teacher-card-img">
            <img src="https://images.pexels.com/photos/771742/pexels-photo-771742.jpeg?auto=compress&cs=tinysrgb&w=800">
          </div>
          <div class="teacher-card-desc">
            <h6 class="teacher-card-primary-text">Fattah</h6>
            <h6 class="teacher-card-secondary-text">Full Stack Developer</h6>
          </div>
          <button class="teacher-card-primary-text">View Profile</button>
          <div class="teacher-card-details">
            <div class="">
              <a class="teacher-card-social-button" href="">
                <svg xmlns="http://www.w3.org/2000/svg" class=" teacher-linkedin" width="30" height="30"
                  fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
                  <path
                    d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854zm4.943 12.248V6.169H2.542v7.225zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248S2.4 3.226 2.4 3.934c0 .694.521 1.248 1.327 1.248zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016l.016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225z" />
                </svg>
              </a>
            </div>

          </div>
        </div>

        <div class="teacher-card ">
          <div class="teacher-card-img">
            <img
              src="https://images.pexels.com/photos/1704488/pexels-photo-1704488.jpeg?auto=compress&cs=tinysrgb&w=800">
          </div>
          <div class="teacher-card-desc">
            <h6 class="teacher-card-primary-text">Mehdi</h6>
            <h6 class="teacher-card-secondary-text">Full Stack Developer</h6>
          </div>
          <button class="teacher-card-primary-text">View Profile</button>
          <div class="teacher-card-details">
            <div class="">
              <a class="teacher-card-social-button" href="">
                <svg xmlns="http://www.w3.org/2000/svg" class=" teacher-linkedin" width="30" height="30"
                  fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
                  <path
                    d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854zm4.943 12.248V6.169H2.542v7.225zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248S2.4 3.226 2.4 3.934c0 .694.521 1.248 1.327 1.248zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016l.016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225z" />
                </svg>
              </a>
            </div>

          </div>
        </div>

        <div class="teacher-card">
          <div class="teacher-card-img">
            <img
              src="https://images.pexels.com/photos/1043471/pexels-photo-1043471.jpeg?auto=compress&cs=tinysrgb&w=800">
          </div>
          <div class="teacher-card-desc">
            <h6 class="teacher-card-primary-text">Mohamed</h6>
            <h6 class="teacher-card-secondary-text">Full Stack Developer</h6>
          </div>
          <button class="teacher-card-primary-text">View Profile</button>
          <div class="teacher-card-details">
            <div class="">
              <a class="teacher-card-social-button" href="">
                <svg xmlns="http://www.w3.org/2000/svg" class=" teacher-linkedin" width="30" height="30"
                  fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
                  <path
                    d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854zm4.943 12.248V6.169H2.542v7.225zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248S2.4 3.226 2.4 3.934c0 .694.521 1.248 1.327 1.248zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016l.016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225z" />
                </svg>
              </a>
            </div>

          </div>
        </div>

        <div class="teacher-card ">
          <div class="teacher-card-img">
            <img src="https://dl.dropbox.com/s/u3j25jx9tkaruap/Webp.net-resizeimage.jpg?raw=1">
          </div>
          <div class="teacher-card-desc">
            <h6 class="teacher-card-primary-text">Olivia</h6>
            <h6 class="teacher-card-secondary-text">Full Stack Developer</h6>
          </div>
          <button class="teacher-card-primary-text">View Profile</button>
          <div class="teacher-card-details">
            <div>
              <a class="teacher-card-social-button" href="">
                <svg xmlns="http://www.w3.org/2000/svg" class=" teacher-linkedin" width="30" height="30"
                  fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
                  <path
                    d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854zm4.943 12.248V6.169H2.542v7.225zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248S2.4 3.226 2.4 3.934c0 .694.521 1.248 1.327 1.248zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016l.016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225z" />
                </svg>
              </a>

            </div>
          </div>
        </div>

        <div class="teacher-card ">
          <div class="teacher-card-img">
            <img
              src="https://images.pexels.com/photos/1759530/pexels-photo-1759530.jpeg?auto=compress&cs=tinysrgb&w=800">
          </div>
          <div class="teacher-card-desc">
            <h6 class="teacher-card-primary-text">Arran</h6>
            <h6 class="teacher-card-secondary-text">Full Stack Developer</h6>
          </div>
          <button class="teacher-card-primary-text">View Profile</button>
          <div class="teacher-card-details">
            <div class="">
              <a class="teacher-card-social-button" href="">
                <svg xmlns="http://www.w3.org/2000/svg" class=" teacher-linkedin" width="30" height="30"
                  fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
                  <path
                    d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854zm4.943 12.248V6.169H2.542v7.225zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248S2.4 3.226 2.4 3.934c0 .694.521 1.248 1.327 1.248zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016l.016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225z" />
                </svg>
              </a>


            </div>

          </div>
        </div>
      </div>
    </div>

    <!-- Teacher Cards Carousel End-->





  </div>
  <!-- End Teacher Section -->
  <div class="text-section">
    <svg id="visual" class="spacer-3" viewBox="0 0 1920 60" xmlns="http://www.w3.org/2000/svg"
      xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1">
      <path
        d="M0 23L40 19.2C80 15.3 160 7.7 240 5.3C320 3 400 6 480 7.8C560 9.7 640 10.3 720 13.3C800 16.3 880 21.7 960 21.2C1040 20.7 1120 14.3 1200 10.2C1280 6 1360 4 1440 8.3C1520 12.7 1600 23.3 1680 26.7C1760 30 1840 26 1880 24L1920 22L1920 0L1880 0C1840 0 1760 0 1680 0C1600 0 1520 0 1440 0C1360 0 1280 0 1200 0C1120 0 1040 0 960 0C880 0 800 0 720 0C640 0 560 0 480 0C400 0 320 0 240 0C160 0 80 0 40 0L0 0Z"
        fill="#212121" stroke-linecap="round" stroke-linejoin="miter"></path>
    </svg>
    <div class="split left">
      <div class="centered">
        <div class="split-text">
          <p class="">Améliorez les compétences de votre équipe Donnez à votre équipe les connaissances, l'expériance et
            la
            confiance dont elle a besoin pour résoudre n'importe quel problème</p>
        </div>
      </div>
    </div>
    <div class="split right">
      <div class="split-img">
        <img class=" centered"
          src="uploads/images/decloedt/home/homme-affaires-senior-donnant-presentation-collegues-bureau.jpg"
          alt="image of teacher teaching scrum">
      </div>
    </div>
    <svg id="visual" class="spacer-4" viewBox="0 0 1920 60" xmlns="http://www.w3.org/2000/svg"
      xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1">
      <path
        d="M0 35L40 39C80 43 160 51 240 53.3C320 55.7 400 52.3 480 50.3C560 48.3 640 47.7 720 44.7C800 41.7 880 36.3 960 36.8C1040 37.3 1120 43.7 1200 47.8C1280 52 1360 54 1440 49.7C1520 45.3 1600 34.7 1680 31.3C1760 28 1840 32 1880 34L1920 36L1920 61L1880 61C1840 61 1760 61 1680 61C1600 61 1520 61 1440 61C1360 61 1280 61 1200 61C1120 61 1040 61 960 61C880 61 800 61 720 61C640 61 560 61 480 61C400 61 320 61 240 61C160 61 80 61 40 61L0 61Z"
        fill="#212121" stroke-linecap="round" stroke-linejoin="miter"></path>
    </svg>
  </div>


  <!-- Events Section -->
  <!-- Title -->
  <div class="event-title-section">
    <div class=" text-center event-title-section-text">
      <h2 class="">
        <?php echo get_phrase('Upcomig Events'); ?>
      </h2>
      <div class="content">
        <svg id="more-arrows">
          <polygon class="arrow-top" points="37.6,27.9 1.8,1.3 3.3,0 37.6,25.3 71.9,0 73.7,1.3 " />
          <polygon class="arrow-middle" points="37.6,45.8 0.8,18.7 4.4,16.4 37.6,41.2 71.2,16.4 74.5,18.7 " />
          <polygon class="arrow-bottom" points="37.6,64 0,36.1 5.1,32.8 37.6,56.8 70.4,32.8 75.5,36.1 " />
        </svg>
      </div>
    </div>
    <svg id="visual" class="spacer-bottom-event-section" viewBox="0 0 1920 100" xmlns="http://www.w3.org/2000/svg"
      xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1">
      <path
        d="M0 50L137 63L274 53L411 63L549 44L686 73L823 55L960 66L1097 68L1234 75L1371 71L1509 61L1646 69L1783 41L1920 43L1920 0L1783 0L1646 0L1509 0L1371 0L1234 0L1097 0L960 0L823 0L686 0L549 0L411 0L274 0L137 0L0 0Z"
        fill="#3a3c43"></path>
      <path
        d="M0 41L137 39L274 36L411 56L549 52L686 61L823 40L960 32L1097 32L1234 36L1371 50L1509 52L1646 48L1783 59L1920 51L1920 0L1783 0L1646 0L1509 0L1371 0L1234 0L1097 0L960 0L823 0L686 0L549 0L411 0L274 0L137 0L0 0Z"
        fill="#33353a"></path>
      <path
        d="M0 39L137 24L274 48L411 29L549 45L686 34L823 38L960 38L1097 45L1234 37L1371 47L1509 31L1646 25L1783 51L1920 51L1920 0L1783 0L1646 0L1509 0L1371 0L1234 0L1097 0L960 0L823 0L686 0L549 0L411 0L274 0L137 0L0 0Z"
        fill="#2d2e32"></path>
      <path
        d="M0 32L137 35L274 36L411 34L549 26L686 23L823 20L960 27L1097 34L1234 36L1371 33L1509 32L1646 34L1783 29L1920 22L1920 0L1783 0L1646 0L1509 0L1371 0L1234 0L1097 0L960 0L823 0L686 0L549 0L411 0L274 0L137 0L0 0Z"
        fill="#272729"></path>
      <path
        d="M0 18L137 13L274 12L411 18L549 21L686 17L823 16L960 19L1097 11L1234 23L1371 12L1509 10L1646 17L1783 19L1920 12L1920 0L1783 0L1646 0L1509 0L1371 0L1234 0L1097 0L960 0L823 0L686 0L549 0L411 0L274 0L137 0L0 0Z"
        fill="#212121"></path>
    </svg>
  </div>

  <!-- End Title -->

  <div class="event-section">

    <!-----------------------------This Is a temporary visual-------------------------------------------->
    <div id="eventCarousel" class="event-carousel carousel carousel-dark slide carousel-fade " data-ride="carousel">
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#eventCarousel" data-bs-slide-to="0" class="active" aria-current="true"
          aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#eventCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#eventCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
      </div>
      <div class="carousel-inner">
        <div class="carousel-item active" class="d-block">
          <div class="event-overlay">
            <img src="uploads/images/decloedt/placeholders/worksm.jpg">
          </div>
          <div class="carousel-caption d-md-block">
            <div>
              <h5 class="event-content-title">Work Smarter, Not Harder Online Conference</h5>
              <p class="event-content-desc">Some representative placeholder content for the first slide.</p>
            </div>

          </div>
        </div>
        <div class="carousel-item" class="d-block">
          <div class="event-overlay">
            <img src="uploads/images/decloedt/placeholders/akw.jpg">
          </div>

          <div class="carousel-caption d-md-block">
            <h5 class="event-content-title">From Awkward To Awesome: Secrets To Succes</h5>
            <p class="event-content-desc">Some representative placeholder content for the second slide.</p>
          </div>
        </div>
        <div class="carousel-item" class="d-block">
          <div class="event-overlay">
            <img src="uploads/images/decloedt/placeholders/elearning.jpg">
          </div>

          <div class="carousel-caption d-md-block">
            <h5 class="event-content-title">Virtual Learning In Modern Scrum Enviroments</h5>
            <p class="event-content-desc">Some representative placeholder content for the third slide.</p>
          </div>
        </div>
      </div>
      <div class="btn-background carousel-control-prev" type="button" data-bs-target="#eventCarousel"
        data-bs-slide="prev">

        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
        </button>
      </div>

      <div class="btn-background carousel-control-next" type="button" data-bs-target="#eventCarousel"
        data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
        </button>
      </div>

    </div>

    <!------------------------------------------------------------------------------------------------>

    


</main>
<!-- ========== END MAIN ========== -->