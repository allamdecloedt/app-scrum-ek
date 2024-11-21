<!--title-->
<div class="row ">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body py-2">
        <h4 class="page-title d-inline-block">
          <i class="mdi mdi-account-circle title_icon"></i> <?php echo get_phrase('school'); ?>
        </h4>
        <button type="button" class="btn btn-outline-primary btn-rounded align-middle mt-1 float-end" onclick="rightModal('<?php echo site_url('modal/popup/school/create'); ?>', '<?php echo get_phrase('create_school'); ?>')"> <i class="mdi mdi-plus"></i> <?php echo get_phrase('create_School'); ?></button>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body admin_content">
        <?php include 'list.php'; ?>
      </div>
    </div>
  </div>
</div>

<script>
var showAllSchools = function () {
  var url = '<?php echo route('school_crud/list'); ?>';

  $.ajax({
    type : 'GET',
    url: url,
    success : function(response) {
      $('.admin_content').html(response);
      initDataTable('basic-datatable');
    }
  });
}
</script>
