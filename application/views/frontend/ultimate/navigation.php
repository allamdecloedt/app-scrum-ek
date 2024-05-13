<?php
$logo_light = base_url('uploads/images/decloedt/logo/white-logo.svg');
$system_name = get_frontend_settings('website_title');
?>
<!-- ========== HEADER ========== -->
<header id="header">
  <div>
    <div class="d-flex justify-content-center nav-container">
      <!-- Nav -->
      <nav class="navbar navbar-expand-lg container sticky-nav nav-home">
        <div class="container">
          <!-- Logo -->
          <a class="logo navbar-brand pl-3" href="<?php echo site_url('home'); ?>">
            <img src="<?php echo $logo_light; ?>" />
          </a>
          <!-- End Logo -->
          <!-- Responsive Toggle Button -->
          <button class="hidden toggle" data-bs-toggle="collapse" data-bs-target="#navBar" aria-controls="navBar"
            aria-expanded="false" aria-label="Toggle navigation">
            <input type="checkbox" id="menu_checkbox">
            <label for="menu_checkbox">
              <div></div>
              <div></div>
              <div></div>
            </label>
          </button>
          <!-- End Responsive Toggle Button -->
          <!-- Navigation -->
          <div id="navBar" class="collapse navbar-collapse container justify-content-end pr-10  ">
            <div class="navbar-nav ">
              <a class="nav-link <?php if ($page_name === 'home')
                echo 'active'; ?>" href="<?php echo site_url('home'); ?>"><?php echo get_phrase('Home'); ?>
              </a>
             <!-- <a class="nav-link <?php if ($page_name == 'noticeboard' || $page_name == 'notice_details')
                echo 'active'; ?>"
                href="<?php echo site_url('home/noticeboard'); ?>"><?php echo get_phrase('Noticeboard'); ?>
              </a>-->
              <a class="nav-link  <?php if ($page_name == 'about')
                echo 'active'; ?>" href="<?php echo site_url('home/about'); ?>"><?php echo get_phrase('About'); ?>
              </a>
              <a class="nav-link <?php if ($page_name == 'courses')
                echo 'active'; ?>"
                href="<?php echo site_url('home/courses'); ?>"><?php echo get_phrase('Courses'); ?>
              </a>
              <a class="nav-link  <?php if ($page_name == 'contact')
                echo 'active'; ?>" href="<?php echo site_url('home/contact'); ?>"><?php echo get_phrase('Contact'); ?>
              </a>
            </div>
          </div>
          <!-- End Navigation -->
        </div>
      </nav>
      <!-- End Nav -->
    </div>
  </div>
</header>
<!-- ========== END HEADER ========== -->