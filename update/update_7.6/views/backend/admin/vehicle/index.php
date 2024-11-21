<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body py-2">
                <h4 class="page-title d-inline-block">
                    <span class="mdi mdi-bus"></span> <?php echo get_phrase('vehicles'); ?>
                </h4>
                <button type="button" class="btn btn-outline-primary btn-rounded alignToTitle float-end mt-1" onclick="rightModal('<?php echo site_url('modal/popup/vehicle/create'); ?>', '<?php echo get_phrase('create_vehicle'); ?>')"> <i class="mdi mdi-plus"></i>
                    <?php echo get_phrase('create_vehicle'); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body vehicle_content">
                <?php include 'list.php'; ?>
            </div>
        </div>
    </div>
</div>

<style>
    span.mdi {
        font-size: 20px !important;
    }
</style>

<script>
    var showAllVehicles = function() {
        var url = '<?php echo route('vehicle/list'); ?>';

        $.ajax({
            type: 'GET',
            url: url,
            success: function(response) {
                $('.vehicle_content').html(response);
                initDataTable('basic-datatable');
            }
        });
    }
</script>