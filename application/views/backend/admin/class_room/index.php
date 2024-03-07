<!--title-->
<div class="row ">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body py-2">
        <h4 class="page-title d-inline-block">
          <i class="mdi mdi-library title_icon title_icon"></i> <?php echo get_phrase('class_room'); ?>
        </h4>
        <button type="button" class="btn btn-outline-primary btn-rounded alignToTitle float-end mt-1" onclick="rightModal('<?php echo site_url('modal/popup/class_room/create'); ?>', '<?php echo get_phrase('create_class_room'); ?>')"> <i class="mdi mdi-plus"></i> <?php echo get_phrase('add_class_room'); ?></button>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body class_room_content">
                <?php include 'list.php'; ?>
            </div>
        </div>
    </div>
</div>

<script>
    var showAllClassRooms = function () {
        var url = '<?php echo route('class_room/list'); ?>';

        $.ajax({
            type : 'GET',
            url: url,
            success : function(response) {
                $('.class_room_content').html(response);
                initDataTable('basic-datatable');
            }
        });
    }
</script>
