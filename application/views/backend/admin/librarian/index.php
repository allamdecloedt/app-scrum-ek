<!--title-->
<div class="row ">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body py-2">
        <h4 class="page-title d-inline-block">
            <i class="mdi mdi-account-circle title_icon"></i> <?php echo get_phrase('content_creator'); ?>
        </h4>
        <button type="button" class="btn btn-outline-primary btn-rounded alignToTitle float-end mt-1" onclick="rightModal('<?php echo site_url('modal/popup/librarian/create'); ?>', '<?php echo get_phrase('create_librarian'); ?>')"> <i class="mdi mdi-plus"></i> <?php echo get_phrase('add_librarian'); ?></button>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body librarian_content">
                <?php include 'list.php'; ?>
            </div>
        </div>
    </div>
</div>

<script>
    var showAllLibrarians = function () {
        var url = '<?php echo route('librarian/list'); ?>';

        $.ajax({
            type : 'GET',
            url: url,
            success : function(response) {
                $('.librarian_content').html(response);
                initDataTable('basic-datatable');
            }
        });
    }
</script>
