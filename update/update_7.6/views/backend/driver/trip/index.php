<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body py-2">
                <h4 class="page-title d-inline-block">
                    <span class="mdi mdi-road-variant"></span> <?php echo get_phrase('Trips'); ?>
                </h4>
                <button type="button" class="btn btn-outline-primary btn-rounded alignToTitle float-end mt-1"
                    onclick="rightModal('<?php echo site_url('modal/popup/trip/add'); ?>', '<?php echo get_phrase('new_trip'); ?>')"> <i class="mdi mdi-plus"></i>
                    <?php echo get_phrase('new_trip'); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <?php if ($old_trip) : ?>
        <div class="card">
            <div class="card-body trip_content">
                <?php include 'ongoing_trip.php'; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body trip_content">
                <?php include 'list.php'; ?>
            </div>
        </div>
    </div>
</div>

<style>
span.mdi {
    font-size: 20px;
}

</style>

<script>
var showAllTrips = function() {
    var url = '<?php echo route('trip/list'); ?>';

    $.ajax({
        type: 'GET',
        url: url,
        success: function(response) {
            $('.trip_content').html(response);
            initDataTable('basic-datatable');
        }
    });
}
</script>
