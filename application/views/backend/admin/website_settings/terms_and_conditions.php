<div class="card">
  <div class="card-body">
    <h4 class="header-title"><?php echo get_phrase('terms_and_conditions_settings') ;?></h4>
    <form method="POST" class="col-12 termsAndConditionSettings" action="<?php echo route('terms_and_conditions/update') ;?>" id = "terms_and_conditions_settings">
      <div class="row justify-content-left">
        <div class="col-12">
          <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="about_us"> <?php echo get_phrase('terms_and_conditions') ;?></label>
            <div class="col-md-9">
              <textarea name="terms_and_conditions" id="terms_and_conditions" class="form-control" rows="8" cols="80"><?php echo get_frontend_settings('terms_conditions'); ?></textarea>
            </div>
          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-secondary col-xl-4 col-lg-4 col-md-12 col-sm-12" onclick="updateTermsAndConditionSettings()"><?php echo get_phrase('update_settings') ;?></button>
          </div>
        </div>
      </div>
    </form>

  </div> <!-- end card body-->
</div>

<script type="text/javascript">
$(document).ready(function () {
  initSummerNote(['#terms_and_conditions']);
});
</script>
