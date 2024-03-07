
<!-- ========== MAIN ========== -->
<main id="content" role="main">
  <!-- Hero Section -->
  <div class="gradient-half-primary-v1">
    <div class="container text-center space-top-4 space-top-md-4 space-top-lg-3 space-bottom-1">
      <!-- Title -->
      <div class="w-md-80 w-lg-50 mx-auto mb-5">
        <h1 class="h1 text-white">
          <span class="font-weight-semi-bold">Our Teachers</span>
        </h1>
      </div>
      <!-- End Title -->
    </div>
  </div>
  <!-- End Hero Section -->

  <!-- About section starts -->
  <div class="bg-light">
    <!-- Teacher Section -->
    <div class="container space-2 space-md-2">
      <!-- Title -->
      <div class="w-md-80 w-lg-50 text-center mx-md-auto mb-9">
        <span class="btn btn-xs btn-soft-success btn-pill mb-2">Teachers</span>
        <h2 class="text-primary">Our Professional Teachers</span></h2>
      </div>
      <!-- End Title -->

        <div class="row">
          <?php
          $checker = array('role' => 'teacher', 'school_id' => $active_school_id);
          $query = $this->db->get_where('users', $checker);
          $teachers = $query->result_array();
          foreach ($teachers as $row) {
            $teacher = $this->db->get_where('teachers', array('user_id' => $row['id']))->row_array();
            if ($teacher['show_on_website'] != 1) {
              continue;
            }
            $social = $teacher['social_links'];
            $links = json_decode($social, true);

            ?>
            <div class="col-md-6" style="padding: 30px;">
              <!-- Slick Carousel -->
              <div class="js-slick-carousel u-slick u-slick--gutters-3"
                 data-slides-show="1"
                 data-slides-scroll="2"
                 data-pagi-classes=""
                 data-responsive='[{
                   "breakpoint": 554,
                   "settings": {
                     "slidesToShow": 1
                   }
                 }]'>


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
                    <div class="col-sm-5">
                      <img class="img-fluid rounded mx-auto"
                        src="<?php echo $this->user_model->get_user_image($row['id']); ?>"
                        alt="<?php echo $row['name']; ?>" style="border-radius: 100px !important;">
                    </div>
                  </div>
                  <!-- End Team -->
                </div>
              </div>
              <!-- End Slick Carousel -->
            </div>
            <?php
          }
          ?>
        </div>

      </div>

    </div>
    <!-- End Teacher Section -->

  </div>
  <!-- About section ends -->
</main>
  <!-- ========== END MAIN ========== -->
