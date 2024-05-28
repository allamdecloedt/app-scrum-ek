<?php
$logo_light = base_url('uploads/images/decloedt/logo/white-logo.svg');
$system_name = get_frontend_settings('website_title');
?>
<!-- ========== HEADER ========== -->
<header id="header">
  <div>
    <div class="d-flex justify-content-center nav-container">
      <!-- Nav -->
      <nav class="navbar position-relative navbar-expand-lg container-fluid sticky-nav nav-home">
        <div class="container-fluid">
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
          <div id="navBar" class=" nav-bar-items collapse navbar-collapse container-fluid justify-content-end">
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
                echo 'active'; ?>" href="<?php echo site_url('home/courses'); ?>"><?php echo get_phrase('Courses'); ?>
              </a>
              <a class="nav-link  <?php if ($page_name == 'contact')
                echo 'active'; ?>" href="<?php echo site_url('home/contact'); ?>"><?php echo get_phrase('Contact'); ?>
              </a>

              <div class=" v-divider-nav"></div>

              <?php if ($this->session->userdata('user_id')) { ?>
              
               
                <?php include 'components/navigation-components/user_loggedin_component.php'; ?>


              <?php } else { ?>

                <?php include 'components/navigation-components/login_component.php'; ?>

              <?php } ?>

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