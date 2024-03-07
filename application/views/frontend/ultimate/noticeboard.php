<?php
  $checker = array(
    'show_on_website' => 1,
    'school_id' => $active_school_id,
    'session' => active_session()
  );
  $this->db->where($checker);
  $this->db->order_by('date', 'DESC');
  $query = $this->db->get('noticeboard');
  $notices = $query->result_array();
?>
<!-- ========== MAIN ========== -->
<main id="content" role="main">
  <!-- Hero Section -->
  <div class="gradient-half-primary-v1">
    <div class="container text-center space-top-4 space-top-md-4 space-top-lg-3 space-bottom-1">
      <!-- Title -->
      <div class="w-md-80 w-lg-50 mx-auto mb-5">
        <h1 class="h1 text-white">
          <span class="font-weight-semi-bold"><?php echo get_phrase('Noticeboard'); ?></span>
        </h1>
      </div>
      <!-- End Title -->
    </div>
  </div>
  <!-- End Hero Section -->

  <!-- Notice section starts -->
  <div class="bg-light">
    <div class="container space-2 space-md-2">

      <!-- Title -->
      <div class="w-md-80 w-lg-50 text-center mx-md-auto mb-9">
        <span class="btn btn-xs btn-soft-success btn-pill mb-2"><?php echo get_phrase('Notices'); ?></span>
        <h2 class="text-primary"><?php echo get_phrase('Follow up school notices'); ?></h2>
      </div>
      <!-- End Title -->

      <div class="container u-cubeportfolio">
        <!-- Content -->
        <div class="cbp mb-7"
             data-layout="mosaic"
             data-animation="quicksand"
             data-x-gap="30"
             data-y-gap="30"
             data-media-queries='[
              {"width": 480, "cols": 1}
          ]'>

          <?php foreach ($notices as $row) { ?>
          <!-- Item -->
          <div class="cbp-item">
            <div class="cbp-caption">
              <!-- Blog Card -->
              <article class="card border-0">
                <div class="card-body p-0">
                  <div class="row align-items-stretch no-gutters">
                    <div class="col-md-6 bg-img-hero min-height-300 rounded" style="background-image: url(<?php echo $this->crud_model->get_noticeboard_image($row['image']); ?>);"></div>
                    <div class="col-md-6">
                      <div class="p-5">
                        <!-- Post Info -->
                        <ul class="list-inline small text-muted mb-1">
                          <li class="list-inline-item">
                            <?php echo date('d M, Y', strtotime($row['date'])); ?>
                          </li>
                        </ul>
                        <!-- End Post Info -->

                        <!-- Info -->
                        <div class="mb-4">
                          <h2 class="h5 mb-3">
                            <a href="<?php echo site_url('home/notice_details/'.$row['id']);?>">
                              <?php echo $row['notice_title'];?>
                            </a>
                          </h2>
                          <p><?php echo substr($row['notice'], 0, 100); ?></p>
                        </div>
                        <!-- End Info -->

                        <!-- Labels -->
                        <ul class="list-inline mb-0">
                          <li class="list-inline-item g-mb-10">
                            <a class="btn btn-xs btn-soft-primary"
                              href="<?php echo site_url('home/notice_details/'.$row['id']);?>">
                              Read more</a>
                          </li>
                        </ul>
                        <!-- End Labels -->
                      </div>
                    </div>
                  </div>
                </div>
              </article>
              <!-- End Blog Card -->
            </div>
          </div>
          <!-- End Item -->
          <?php } ?>

        </div>
        <!-- End Content -->
      </div>

    </div>
  </div>
  <!-- Notice section ends -->
</main>
  <!-- ========== END MAIN ========== -->
