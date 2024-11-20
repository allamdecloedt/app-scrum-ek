<?php
$logo_dark = base_url('uploads/images/decloedt/logo/white-logo.png');
$social = get_frontend_settings('social_links');
$links = json_decode($social);
?>
<!-- ========== FOOTER ========== -->
<footer class="border-top footer">
  <div class="container space-0 footer">
    <div class="row gx-0 justify-content-between ">
      <div class="col-sm-4 d-flex justify-content-center text-center ">
       

        <!-- Address -->
        <div>
          <ul class="list-group list-group-flush list-group-borderless text-break ">
           <h4 class="h6 font-weight-semi-bold pb-4 pt-4 pt-sm-0 light-border-bottom">Contact</h4>
            <li class="list-group-item text-light custom-list-group-item">
              <?php echo get_settings('phone'); ?>
            </li>
            <li class="list-group-item custom-list-group-item">
              <a class="footer-email" href="mailto:<?php echo get_settings('system_email'); ?>">
                <?php echo get_settings('system_email'); ?>
              </a>
            </li>
            <li class="list-group-item custom-list-group-item">
              <?php echo get_settings('address'); ?>
            </li>
          </ul>
        </div>
        <!-- End Address -->


      </div>
      <div class="col-sm-4 d-flex justify-content-center text-center">
        <!-- List Group -->
        <ul class="list-group list-group-flush list-group-borderless mb-0 text-break">
         <h4 class="h6 font-weight-semi-bold pb-4 pt-4 pt-sm-0 light-border-bottom">About</h4>
          <li><a class="list-group-item  custom-list-group-item" href="<?php echo site_url('home/about'); ?>">About</a>
          </li>
          <li><a class="list-group-item  custom-list-group-item" href="<?php echo site_url('home#mentors-section'); ?>">Mentors
            </a></li>
 
        </ul>
        <!-- End List Group -->
      </div>

      <div class="col-sm-4 d-flex justify-content-center text-center">
        

        <!-- List Group -->
        <ul class=" footer-section list-group list-group-flush list-group-borderless mb-0 text-break">
        <h4 class="h6 font-weight-semi-bold pb-4 pt-4 pt-sm-0 light-border-bottom">Resources</h4>
          <li><a class="list-group-item list-group-item-action custom-list-group-item "
              href="<?php echo site_url('home/terms_conditions'); ?>">Terms & Conditions</a></li>
          <li><a class="list-group-item list-group-item-action custom-list-group-item "
              href="<?php echo site_url('home/privacy_policy'); ?>">Privacy Policy</a></li>
        
        </ul>
        <!-- End List Group -->
      </div>
      <!-- End Social Networks -->
    </div>
    <div class="row gx-0 justify-content-between light-border-top pt-4">
        <!-- Logo -->
        <div class="d-flex justify-content-center col-12 col-sm-4 mb-4 mb-sm-0">
          <a href="<?php echo base_url(); ?>">
            <img src="<?php echo $logo_dark; ?>" style="height:45px;" />
          </a>
        </div>
        <!-- End Logo -->

        <div class="d-flex justify-content-center col-12 col-sm-4 mb-3 mb-sm-0 ">
          <p class="small text-light">©
            <?php echo get_frontend_settings('copyright_text'); ?>
          </p>
        </div>

        <!-- Social Networks -->
        <div class="d-flex justify-content-center col-12 col-sm-4">
          <ul class="list-inline mb-0">
            <!-- <li class="list-inline-item mx-0">
              <a class="btn btn-sm btn-icon btn-soft-secondary rounded-circle" href="<?php //echo $links[0]->facebook; ?>"
                target="_blank">
                <span class="fab fa-facebook-f btn-icon__inner"></span>
              </a>
            </li>
            <li class="list-inline-item mx-0">
              <a class="btn btn-sm btn-icon btn-soft-secondary rounded-circle"
                href="<?php //echo $links[0]->instagram; ?>" target="_blank">
                <span class="fab fa-instagram btn-icon__inner"></span>
              </a>
            </li> -->
            <!-- <li class="list-inline-item mx-0">
              <a class="btn btn-sm btn-icon btn-soft-secondary rounded-circle" href="<?php //echo $links[0]->twitter; ?>"
                target="_blank">
                <span class=""></span>
                <svg class="btn-icon__inner" xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor"
                      class="bi bi-twitter-x" viewBox="0 0 16 16">
                      <path
                        d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z" />
                    </svg>
              </a>
            </li> -->
            <!-- <li class="list-inline-item mx-0">
              <a class="btn btn-sm btn-icon btn-soft-secondary rounded-circle" href="<?php //echo $links[0]->google; ?>"
                target="_blank">
                <span class="fab fa-google btn-icon__inner"></span>
              </a>
            </li> -->
            
            <!-- <li class="list-inline-item mx-0">
              <a class="btn btn-sm btn-icon btn-soft-secondary rounded-circle" href="<?php //echo $links[0]->linkedin; ?>"
                target="_blank">
                <span class="fab fa-linkedin btn-icon__inner"></span>
              </a>
            </li> -->
          </ul>
        </div>
      </div>
  </div>
  </div>
</footer>
<!-- ========== END FOOTER ========== -->

<!-- Go to Top -->
<a class="js-go-to u-go-to" href="#" data-position='{"bottom": 15, "right": 15 }' data-type="fixed"
  data-offset-top="400" data-compensation="#header" data-show-effect="slideInUp" data-hide-effect="slideOutDown">
  <span class="fas fa-arrow-up u-go-to__inner"></span>
</a>
<!-- End Go to Top -->


<script type="text/javascript">
document.addEventListener("DOMContentLoaded", function () {
  const links = document.querySelectorAll("a[href^='#']");
  links.forEach(link => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      const targetId = this.getAttribute("href").substring(1);
      const targetElement = document.getElementById(targetId);
      if (targetElement) {
        targetElement.scrollIntoView({ behavior: "smooth" });
      }
    });
  });
});

</script>