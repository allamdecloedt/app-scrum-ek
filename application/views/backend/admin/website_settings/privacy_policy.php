<div class="card">
  <div class="card-body">
    <h4 class="header-title"><?php echo get_phrase('privacy_policy_settings') ;?></h4>
    <form method="POST" class="col-12 privacyPolicySettings" action="<?php echo route('privacy_policy/update') ;?>" id = "privacy_policy_settings">
      <div class="row justify-content-left">
        <div class="col-12">
          <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="about_us"> <?php echo get_phrase('privacy_policy') ;?></label>
            <div class="col-md-9">
              <textarea name="privacy_policy" id="privacy_policy" class="form-control" rows="8" cols="80"><?php echo get_frontend_settings('privacy_policy'); ?></textarea>
            </div>
          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-secondary col-xl-4 col-lg-4 col-md-12 col-sm-12" onclick="updatePrivactPolicySettings()"><?php echo get_phrase('update_settings') ;?></button>
          </div>
        </div>
      </div>
    </form>

  </div> <!-- end card body-->
</div>

<script type="text/javascript">
$(document).ready(function () {
  initSummerNote(['#privacy_policy']);
});
</script>
