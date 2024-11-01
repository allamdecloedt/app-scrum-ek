<!-- start page title -->
<div class="row ">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body py-2">
        <h4 class="page-title d-inline-block">
          <i class="mdi mdi-power-plug title_icon"></i> <?php echo get_phrase('manage_addons'); ?>
        </h4>
        <button type="button" class="btn btn-outline-primary btn-rounded alignToTitle float-end mt-1" onclick="rightModal('<?php echo site_url('modal/popup/addon/create') ?>', '<?php echo get_phrase('add_new_addon'); ?>')"> <i class="mdi mdi-plus"></i> <?php echo get_phrase('add_new_addon'); ?></button>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>
<!-- end page title -->

<div class="row ">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body">
        <h4 class="header-title mt-3"><?php echo get_phrase('installed_addons'); ?></h4>
        <div class="table-responsive-sm addon_content">
          <?php include 'list.php'; ?>
        </div> <!-- end table-responsive-->
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>

<script>
var showAllAddons = function () {
  var url = '<?php echo route('addon_manager/list'); ?>';

  $.ajax({
    type : 'GET',
    url: url,
    success : function(response) {
      $('.addon_content').html(response);
      initDataTable("basic-datatable");
      setTimeout(
        function()
        {
          location.reload();
        }, 1000);
      }
    });
  }
  </script>
