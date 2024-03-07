
<!-- ========== MAIN ========== -->
<main id="content" role="main">
  <!-- Hero Section -->
  <div class="gradient-half-primary-v1">
    <div class="container text-center space-top-4 space-top-md-4 space-top-lg-3 space-bottom-1">
      <!-- Title -->
      <div class="w-md-80 w-lg-50 mx-auto mb-5">
        <h1 class="h1 text-white">
          <span class="font-weight-semi-bold"><?php echo get_phrase('About Us'); ?></span>
        </h1>
      </div>
      <!-- End Title -->
    </div>
  </div>
  <!-- End Hero Section -->

  <!-- About section starts -->
  <div class="gradient-half-primary-v3">
    <div class="container space-2 space-md-2">


      <img class="rounded" alt="About Us" style="width: 100%;"
        src="<?php echo $this->frontend_model->get_about_image(); ?>">

      <?php
      $about_us_text  = get_frontend_settings('about_us');
      echo htmlspecialchars_decode(stripslashes($about_us_text));
      ?>

    </div>
  </div>
  <!-- About section ends -->
</main>
  <!-- ========== END MAIN ========== -->
