<?php
  $this->db->where('status', 1);
  $this->db->where('school_id', $active_school_id);
  $this->db->order_by('timestamp', 'DESC');
  $query = $this->db->get('frontend_events');
  $events = $query->result_array();
?>
<!-- ========== MAIN ========== -->
<main id="content" role="main">
  <!-- Hero Section -->
  <div class="gradient-half-primary-v1">
    <div class="container text-center space-top-4 space-top-md-4 space-top-lg-3 space-bottom-1">
      <!-- Title -->
      <div class="w-md-80 w-lg-50 mx-auto mb-5">
        <h1 class="h1 text-white">
          <span class="font-weight-semi-bold"><?php echo get_phrase('School Events'); ?></span>
        </h1>
      </div>
      <!-- End Title -->
    </div>
  </div>
  <!-- End Hero Section -->

  <!-- Events Section -->
  <div class="bg-light">
    <div class="container space-2 space-md-2">
      <!-- Title -->
      <div class="w-md-80 w-lg-50 text-center mx-md-auto mb-9">
        <span class="btn btn-xs btn-soft-success btn-pill mb-2"><?php echo get_phrase('Events'); ?></span>
        <h2 class="text-primary"><?php echo get_phrase('Upcoming and past events'); ?></h2>
      </div>
      <!-- End Title -->

      <!-- News Carousel -->
      <div class="js-slick-carousel u-slick u-slick--equal-height u-slick--gutters-2"
           data-slides-show="4"
           data-slides-scroll="1"
           data-pagi-classes="text-center u-slick__pagination mt-7 mb-0"
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
        foreach ($events as $row) { ?>
          <?php $created_by = $this->user_model->get_user_details($row['created_by']); ?>
          <div class="js-slide card border-0 mb-3">
            <div class="card-body p-5">
              <small class="d-block text-muted mb-2">
                <?php echo date('d M, Y', $row['timestamp']); ?>
              </small>
              <h2 class="h5">
                <a href="#"><?php echo $row['title']; ?></a>
              </h2>
            </div>

            <div class="card-footer pb-5 px-0 mx-5">
              <div class="media align-items-center">
                <div class="u-sm-avatar mr-3">
                  <img class="img-fluid rounded-circle"
                    src="<?php echo $this->user_model->get_user_image($created_by['id']); ?>" alt="Image Description">
                </div>
                <div class="media-body">
                  <h4 class="small mb-0"><a href="#"><?php echo ucfirst($created_by['name']); ?></a></h4>
                </div>
              </div>
            </div>
            <!-- End Blog Grid -->
          </div>
        <?php } ?>

      </div>
      <!-- End News Carousel -->
    </div>
  </div>
  <!-- End Events Section -->
</main>
  <!-- ========== END MAIN ========== -->
