<?php
$logo_light = base_url('uploads/images/decloedt/logo/white-logo.png');
$system_name = get_frontend_settings('website_title');
?>
<!-- ========== HEADER ========== -->
<header id="header">
  <nav class="navbar position-relative navbar-expand-lg container-fluid navbar-dark bg-dark sticky-top sticky-nav">
    <div class="container-fluid">
      <!-- Logo -->
      <a class="navbar-brand" href="<?php echo site_url('home'); ?>">
        <img src="<?php echo $logo_light; ?>" alt="Logo" style="width: 84px;">
      </a>
      <!-- End Logo -->

      <!-- Toggle Button for Mobile -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- End Toggle Button -->

      <!-- Navigation Links -->
      <div class="collapse navbar-collapse" id="navbarContent" style="background-color:rgb(33 37 41);;">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link <?php if ($page_name === 'home') echo 'active'; ?>" href="<?php echo site_url('home'); ?>">
              <?php echo get_phrase('Home'); ?>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php if ($page_name === 'about') echo 'active'; ?>" href="<?php echo site_url('home/about'); ?>">
              <?php echo get_phrase('About_us'); ?>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php if ($page_name === 'courses') echo 'active'; ?>" href="<?php echo site_url('home/courses'); ?>">
              <?php echo get_phrase('Courses'); ?>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php if ($page_name === 'contact') echo 'active'; ?>" href="<?php echo site_url('home/contact'); ?>">
              <?php echo get_phrase('Contact_us'); ?>
            </a>
          </li>
          <?php if ($this->session->userdata('user_id')) { ?>
               
               <a href="<?php echo route('dashboard'); ?>" target="" class=" btn btn-outline-light website-button ml-2 ml-lg-3  d-md-inline-block">
                 <?php echo get_phrase('visit_dashboard'); ?></a>
               

               <div class=" v-divider-nav" style="margin-left: 20px;"></div>

               <div class="user-section ">
                 <span
                   class="text-capitalize ml-2 ml-lg-3  align-content-center text-white"><?php echo $this->session->user_name; ?></span>

                 <img src="<?php echo $this->user_model->get_user_image($this->session->userdata('user_id')); ?>"
                   alt="user-image" class=" rounded-circle nav-user-img">
               </div>
               <?php include 'components/navigation-components/user_loggedin_component.php'; ?>

             <?php } else { ?>
               <div class=" v-divider-nav" style="margin-left: 20px;"></div>
               <a class="nav-link login-toggle"><?php echo get_phrase('Login'); ?> </a>

             <?php } ?>



          <?php if ($this->session->userdata('user_id')) { ?>

          <?php } else { ?>

            <?php include 'components/navigation-components/login_register_component.php'; ?>

          <?php } ?>
        </ul>
      </div>
      <!-- End Navigation Links -->
    </div>
  </nav>

</header>
<!-- ========== END HEADER ========== -->




