<!-- start page title -->
<div class="row ">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body py-2">
        <h4 class="page-title">
          <i class="mdi mdi-settings title_icon"></i><?php echo ucfirst(get_phrase('website_settings')); ?>
        </h4>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>
<!-- end page title -->
<div class="row">
  <div class="col-md-3">
    <a href="<?php echo route('noticeboard'); ?>" class="btn <?php if ($page_content == 'noticeboard'): ?> btn-dark <?php else: ?> btn-secondary <?php endif; ?> btn-rounded d-block mb-1"><?php echo get_phrase('noticeboard'); ?> <i class="mdi mdi-arrow-right float-end"></i></a>
    <a href="<?php echo route('website_settings/events'); ?>" class="btn <?php if ($page_content == 'events'): ?> btn-dark <?php else: ?> btn-secondary <?php endif; ?> btn-rounded d-block mb-1"><?php echo get_phrase('events'); ?> <i class="mdi mdi-arrow-right float-end"></i></a>
    <a href="<?php echo route('teacher'); ?>" class="btn <?php if ($page_content == 'teachers'): ?> btn-dark <?php else: ?> btn-secondary <?php endif; ?> btn-rounded d-block mb-1"><?php echo get_phrase('teachers'); ?> <i class="mdi mdi-arrow-right float-end"></i></a>
    <a href="<?php echo route('website_settings/gallery'); ?>" class="btn <?php if ($page_content == 'gallery' || $page_content == 'gallery_image'): ?> btn-dark <?php else: ?> btn-secondary <?php endif; ?> btn-rounded d-block mb-1"><?php echo get_phrase('gallery'); ?> <i class="mdi mdi-arrow-right float-end"></i></a>
    <a href="<?php echo route('website_settings/about_us'); ?>" class="btn <?php if ($page_content == 'about_us'): ?> btn-dark <?php else: ?> btn-secondary <?php endif; ?> btn-rounded d-block mb-1"><?php echo get_phrase('about_us'); ?> <i class="mdi mdi-arrow-right float-end"></i></a>
    <a href="<?php echo route('website_settings/terms_and_conditions'); ?>" class="btn <?php if ($page_content == 'terms_and_conditions'): ?> btn-dark <?php else: ?> btn-secondary <?php endif; ?> btn-rounded d-block mb-1"><?php echo get_phrase('terms_and_conditions'); ?> <i class="mdi mdi-arrow-right float-end"></i></a>
    <a href="<?php echo route('website_settings/privacy_policy'); ?>" class="btn <?php if ($page_content == 'privacy_policy'): ?> btn-dark <?php else: ?> btn-secondary <?php endif; ?> btn-rounded d-block mb-1"><?php echo get_phrase('privacy_policy'); ?> <i class="mdi mdi-arrow-right float-end"></i></a>
    <a href="<?php echo route('website_settings/homepage_slider'); ?>" class="btn <?php if ($page_content == 'homepage_slider'): ?> btn-dark <?php else: ?> btn-secondary <?php endif; ?> btn-rounded d-block mb-1"><?php echo get_phrase('homepage_slider'); ?> <i class="mdi mdi-arrow-right float-end"></i></a>
    <a href="<?php echo route('website_settings/general_settings'); ?>" class="btn <?php if ($page_content == 'general_settings'): ?> btn-dark <?php else: ?> btn-secondary <?php endif; ?> btn-rounded d-block mb-1"><?php echo get_phrase('general_settings'); ?> <i class="mdi mdi-arrow-right float-end"></i></a>
    <a href="<?php echo route('website_settings/other_settings'); ?>" class="btn <?php if ($page_content == 'other_settings'): ?> btn-dark <?php else: ?> btn-secondary <?php endif; ?> btn-rounded d-block mb-1"><?php echo get_phrase('others'); ?> <i class="mdi mdi-arrow-right float-end"></i></a>
  </div>
  <div class="col-md-9 page_content">
    <?php include $page_content.'.php'; ?>
  </div>
</div>

<script type="text/javascript">
// FRONTEND FORM SUBMISSION STRATS FROM HERE
function updateGeneralSettings() {
  $(".generalSettingsAjaxForm").validate({});
  $(".generalSettingsAjaxForm").submit(function(e) {
    var form = $(this);
    ajaxSubmit(e, form, reload);
  });
}

function updateAboutUsSettings() {
  $(".aboutUsSettings").validate({});
  $(".aboutUsSettings").submit(function(e) {
    var form = $(this);
    ajaxSubmit(e, form, reload);
  });
}

function updatePrivactPolicySettings() {
  $(".privacyPolicySettings").validate({});
  $(".privacyPolicySettings").submit(function(e) {
    var form = $(this);
    ajaxSubmit(e, form, doNothing);
  });
}

function updateTermsAndConditionSettings() {
  $(".termsAndConditionSettings").validate({});
  $(".termsAndConditionSettings").submit(function(e) {
    var form = $(this);
    ajaxSubmit(e, form, doNothing);
  });
}

function updateHomepageSliderSettings() {
  $(".homepageSliderSettings").validate({});
  $(".homepageSliderSettings").submit(function(e) {
    var form = $(this);
    ajaxSubmit(e, form, reload);
  });
}

function updateOtherSettings() {
  $(".otherSettingsAjaxForm").validate({});
  $(".otherSettingsAjaxForm").submit(function(e) {
    var form = $(this);
    ajaxSubmit(e, form, reload);
  });
}

function updateRecaptchaSettings() {
  $(".updateRecaptchaSettings").validate({});
  $(".updateRecaptchaSettings").submit(function(e) {
    var form = $(this);
    ajaxSubmit(e, form, reload);
  });
}

// Show All The Events
var showAllEvents = function () {
    var url = '<?php echo route('events/list'); ?>';

    $.ajax({
        type : 'GET',
        url: url,
        success : function(response) {
            $('.page_content').html(response);
            initDataTable('basic-datatable');
        }
    });
}

function reload() {
  setTimeout(
    function()
    {
      location.reload();
    }, 1000);
}
function doNothing() {

}
</script>
