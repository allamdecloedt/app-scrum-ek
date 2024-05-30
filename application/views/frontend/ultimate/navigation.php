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
          <a class="logo navbar-brand " href="<?php echo site_url('home'); ?>">
            <img src="<?php echo $logo_light; ?>" />
          </a>
          <!-- End Logo -->
          <!-- Responsive Toggle Button -->
          <button id="navToggle" class="hidden toggle">
            <input type="checkbox" id="menu_checkbox">
            <label for="menu_checkbox">
              <div></div>
              <div></div>
              <div></div>
            </label>
          </button>
          <!-- End Responsive Toggle Button -->
          <!-- Navigation -->
          <div id="navBar"
            class=" nav-bar-items navbar container-fluid justify-content-start justify-content-lg-end collapse-nav ">
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

                <div class="user-section "><span
                    class="text-capitalize ml-2 ml-lg-3  align-content-center text-white"><?php echo $this->session->user_name; ?></span>

                  <img src="<?php echo $this->user_model->get_user_image($this->session->userdata('user_id')); ?>"
                    alt="user-image" class=" rounded-circle nav-user-img">
                </div>
                <?php include 'components/navigation-components/user_loggedin_component.php'; ?>

              <?php } else { ?>

                <a class="nav-link login-toggle"><?php echo get_phrase('Login'); ?> </a>

              <?php } ?>
            </div>
          </div>
          <?php if ($this->session->userdata('user_id')) { ?>

          <?php } else { ?>

            <?php include 'components/navigation-components/login_component.php'; ?>

          <?php } ?>
          <!-- End Navigation -->
        </div>
      </nav>
      <!-- End Nav -->
    </div>
  </div>
</header>
<!-- ========== END HEADER ========== -->