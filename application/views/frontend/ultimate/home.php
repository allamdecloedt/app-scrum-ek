<!-- ========== MAIN ========== -->
<?php
  $slider = get_frontend_settings('slider_images');
  $slider_images = json_decode($slider);
  $upcoming_events = $this->frontend_model->get_frontend_upcoming_events();
?>
  <main id="content" role="main">

    <!-- Slider Section -->
    <div class="u-hero-v1">
      <!-- Slick carousal starts -->
      <div class="js-slick-carousel u-slick"
           data-autoplay="true"
           data-speed="10000"
           data-infinite="true"
           data-adaptive-height="true"
           data-arrows-classes="d-none d-lg-inline-block u-slick__arrow-classic u-slick__arrow-centered--y rounded-circle"
           data-arrow-left-classes="fas fa-arrow-left u-slick__arrow-classic-inner u-slick__arrow-classic-inner--left ml-lg-2 ml-xl-4"
           data-arrow-right-classes="fas fa-arrow-right u-slick__arrow-classic-inner u-slick__arrow-classic-inner--right mr-lg-2 mr-xl-4">

        <?php for ($i=0; $i < count($slider_images); $i++) { ?>
        <div class="js-slide bg-img-hero-center" style="background-image: url(<?php echo base_url('uploads/images/slider/'.$slider_images[$i]->image); ?>);">
          <div class="text-center space-3">
            <h2 class="text-white font-weight-light mb-2"
                data-scs-animation-in="fadeInUp" style="padding-top: 100px;">
              <?php echo $slider_images[$i]->title; ?>
            </h2>

            <p class="text-white mx-auto w-50 d-none d-sm-block"
               data-scs-animation-in="fadeInUp"
               data-scs-animation-delay="200">
                 <?php echo htmlspecialchars_decode(stripslashes($slider_images[$i]->description)); ?>
               </p>
          </div>
        </div>
        <?php } ?>

      </div>
      <!-- Slick carousal ends -->
    </div>
    <!-- End Slider Section -->

    <hr class="my-0">

    <!-- Intro Section -->
    <div class="overflow-hidden">
      <div class="container space-2 space-md-2">
        <div class="row justify-content-between align-items-center">
          <div class="col-lg-7 mb-7 mb-lg-0">
            <div class="pr-md-4">
              <!-- Title -->
              <div class="mb-7">
                <span class="btn btn-xs btn-soft-success btn-pill mb-2"><?php echo get_phrase('About'); ?></span>
                <h2 class="text-primary">
                  <?php echo get_frontend_settings('homepage_note_title'); ?>
                </h2>
                <p>
                  <?php echo htmlspecialchars_decode(get_frontend_settings('homepage_note_description')); ?>
                </p>
              </div>
              <!-- End Title -->

              <a class="btn btn-sm btn-primary btn-wide transition-3d-hover"
                href="<?php echo site_url('home/about');?>">
                  <?php echo get_phrase('learn_more'); ?> <span class="fas fa-angle-right ml-2"></span></a>
            </div>
          </div>

          <div class="col-lg-5 position-relative">
            <!-- SVG Mockup -->
            <figure class="ie-ellipse-mockup">
              <img class="js-svg-injector" src="<?php echo base_url();?>assets/frontend/<?php echo $theme;?>/svg/illustrations/ellipse-mockup.svg" alt="Image Description"
                   data-img-paths='[
                     {"targetId": "#SVGellipseMockupImg1", "newPath": "<?php echo base_url();?>assets/frontend/<?php echo $theme;?>/img/home_promo_1.png"}
                   ]'
                   data-parent="#SVGellipseMockup">
            </figure>
            <!-- End SVG Mockup -->
          </div>
        </div>
      </div>
    </div>
    <!-- End Intro Section -->


    <!-- Teacher Section -->
    <div class="container space-2 space-md-3">
      <!-- Title -->
      <div class="w-md-80 w-lg-50 text-center mx-md-auto mb-9">
        <span class="btn btn-xs btn-soft-success btn-pill mb-2">Teachers</span>
        <h2 class="text-primary">Our Professional Teachers</span></h2>
      </div>
      <!-- End Title -->

      <!-- Slick Carousel -->
      <div class="js-slick-carousel u-slick u-slick--gutters-3 mb-7"
           data-slides-show="2"
           data-slides-scroll="2"
           data-pagi-classes=""
           data-responsive='[{
             "breakpoint": 554,
             "settings": {
               "slidesToShow": 1
             }
           }]'>

          <?php
            $checker = array('role' => 'teacher', 'school_id' => $active_school_id);
            $query = $this->db->get_where('users', $checker);
            $teachers = $query->result_array();
            $show_counter = 0;
            foreach ($teachers as $row) {
              if ($show_counter == 2)break;
              $show_counter++;
              $teacher = $this->db->get_where('teachers', array('user_id' => $row['id']))->row_array();
              $links = json_decode($teacher['social_links'], true);
              ?>
              <div class="js-slide px-3">
              <!-- Team -->
              <div class="row">
                <div class="col-sm-6 d-sm-flex align-content-sm-start flex-sm-column text-center text-sm-left mb-7 mb-sm-0">
                  <div class="w-100">
                    <h3 class="h5 mb-4">
                      <?php echo $row['name']; ?>
                    </h3>
                  </div>
                  <div class="d-inline-block">
                    <span class="badge badge-primary badge-pill badge-bigger mb-3">
                      <?php echo $teacher['designation']; ?>
                    </span>
                  </div>
                  <p class="font-size-1"><?php echo $teacher['about']; ?></p>

                  <!-- Social Networks -->
                  <ul class="list-inline mt-auto mb-0">
                    <li class="list-inline-item mx-0">
                      <a class="btn btn-sm btn-icon btn-soft-secondary"
                        href="<?php echo $links['facebook'];?>">
                        <span class="fab fa-facebook-f btn-icon__inner"></span>
                      </a>
                    </li>
                    <li class="list-inline-item mx-0">
                      <a class="btn btn-sm btn-icon btn-soft-secondary"
                        href="<?php echo $links['linkedin'];?>">
                        <span class="fab fa-linkedin btn-icon__inner"></span>
                      </a>
                    </li>
                    <li class="list-inline-item mx-0">
                      <a class="btn btn-sm btn-icon btn-soft-secondary"
                        href="<?php echo $links['twitter'];?>">
                        <span class="fab fa-twitter btn-icon__inner"></span>
                      </a>
                    </li>
                  </ul>
                  <!-- End Social Networks -->
                </div>
                <div class="col-sm-6">
                  <img class="img-fluid rounded mx-auto"
                    src="<?php echo $this->user_model->get_user_image($row['id']); ?>"
                    alt="<?php echo $row['name']; ?>">
                </div>
              </div>
              <!-- End Team -->
              </div>
              <?php
            }
            ?>



        </div>
        <center>
        <a class="btn btn-sm btn-primary btn-wide transition-3d-hover pull-right"
          href="<?php echo site_url('home/teachers');?>">
            Learn More <span class="fas fa-angle-right ml-2"></span></a>
        </center>
        </div>
        <!-- End Slick Carousel -->
    </div>
    <!-- End Teacher Section -->

    <!-- Events Section -->
    <div class="bg-light">
          <div class="container space-2 space-md-3">
            <!-- Title -->
            <div class="w-md-80 w-lg-50 text-center mx-md-auto mb-9">
              <span class="btn btn-xs btn-soft-success btn-pill mb-2"><?php echo get_phrase('Events'); ?></span>
              <h2 class="text-primary"><?php echo get_phrase('Upcomig Events'); ?></h2>
            </div>
            <!-- End Title -->

            <!-- News Carousel -->
            <div class="js-slick-carousel u-slick u-slick--equal-height u-slick--gutters-2 mb-7"
                 data-slides-show="4"
                 data-slides-scroll="1"
                 data-pagi-classes=""
                 data-responsive='[{
                   "breakpoint": 1200,
                   "settings": {
                     "slidesToShow": 3
                   }
                 }, {
                   "breakpoint": 554,
                   "settings": {
                     "slidesToShow": 1
                   }
                 }]'>
              <!-- Blog Grid -->
              <?php
              foreach ($upcoming_events as $row) { ?>
                <div class="js-slide card border-0 mb-3">
                  <div class="card-body p-5">
                    <small class="d-block text-muted mb-2">
                      <?php echo date('d M, Y', $row['timestamp']); ?>
                    </small>
                    <h2 class="h5">
                      <a href="javascript:void(0)"><?php echo $row['title']; ?></a>
                    </h2>
                  </div>
                  <?php $created_by = $this->user_model->get_user_details($row['created_by']); ?>
                  <div class="card-footer pb-5 px-0 mx-5">
                    <div class="media align-items-center">
                      <div class="u-sm-avatar mr-3">
                        <img class="img-fluid rounded-circle" src="<?php echo $this->user_model->get_user_image($created_by['id']); ?>" alt="Image Description">
                      </div>
                      <div class="media-body">
                        <h4 class="small mb-0"><a href="javascript:void(0)"><?php echo ucfirst($created_by['name']); ?></a></h4>
                      </div>
                    </div>
                  </div>
                  <!-- End Blog Grid -->
                </div>
              <?php } ?>

            </div>
            <!-- End News Carousel -->
            <center>
            <a class="btn btn-sm btn-primary btn-wide transition-3d-hover pull-right"
              href="<?php echo site_url('home/events');?>">
                Learn More <span class="fas fa-angle-right ml-2"></span></a>
            </center>
          </div>
        </div>
    <!-- End Events Section -->


  </main>
  <!-- ========== END MAIN ========== -->
