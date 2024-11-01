<!-- start page title -->
<div class="row ">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body py-2">
        <h4 class="page-title"> <i class="mdi mdi-settings title_icon"></i><?php echo get_phrase('manage_profile'); ?></h4>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>
<!-- end page title -->

<div class="row">
    <div id = "profile_content" class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <?php include 'edit.php'; ?>
    </div>
</div>

<script>
function updateProfileInfo() {
    $(".profileAjaxForm").validate({});
    $(".profileAjaxForm").submit(function(e) {
        var form = $(this);
        ajaxSubmit(e, form, reload);
    });
}

function changePassword() {
    $(".changePasswordAjaxForm").validate({});
    $(".changePasswordAjaxForm").submit(function(e) {
        var form = $(this);
        ajaxSubmit(e, form, reload);
    });
}

function reload() {
    setTimeout(
        function()
        {
            location.reload();
        }, 1000);
    }
    </script>
