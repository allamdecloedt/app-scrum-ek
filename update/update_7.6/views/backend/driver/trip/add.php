<?php $vehicles = $this->db->where('driver', driver_id())->get('vehicles')->result_array(); ?>

<form method="POST" class="d-block add_trip" action="" enctype="multipart/form-data">
    <div class="form-row">
        <input type="hidden" name="school_id" value="<?php echo school_id(); ?>">

        <div class="form-group mb-1">
            <label for="vehicle"><?php echo get_phrase('vehicle'); ?></label>
            <select name="vehicle_id" id="vehicle" class="form-control select2" data-toggle="select2" onchange="get_route_by_vehicle()" required>
                <option value=""><?php echo get_phrase('select_a_vehicle'); ?></option>
                <?php foreach ($vehicles as $vehicle) : ?>
                <option value="<?php echo $vehicle['id'] ?>"><?php echo $vehicle['vh_model'] . ', ' . $vehicle['vh_num'] ?></option>
                <?php endforeach; ?>
            </select>
            <small id="" class="form-text text-muted"><?php echo get_phrase('select_vehicle'); ?></small>
        </div>

        <div class="form-group mb-1">
            <label for="route"><?php echo get_phrase('route'); ?></label>
            <textarea class="form-control" name="route" id="route" cols="30" rows="3" disabled placeholder="<?php echo get_phrase('first_select_a_vehicle'); ?>"></textarea>
        </div>

        <div class="form-group mt-2 col-md-12">
            <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('start_trip'); ?></button>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    $('select.select2:not(.normal)').each(function() {
        $(this).select2({
            dropdownParent: '#right-modal'
        });
    }); //initSelect2(['#department', '#gender', '#blood_group', '#show_on_website']);

    $('.add_trip').submit(function(e) {
        e.preventDefault();

        let vehicle_id = $('#vehicle').val();

        $.ajax({
            type: "POST",
            url: "<?php echo route('trip/add'); ?>",
            data: {
                vehicle_id: vehicle_id
            },
            success: function(response) {
                let res = JSON.parse(response)
                if (res.status) {
                    success_notify(res.notification);
                } else {
                    error_notify(res.notification);
                }

                setTimeout(() => {
                    if (res.refresh) {
                        window.location.reload();
                    }
                }, 3000);

                console.log(res);
            }
        });
    });
});

function get_route_by_vehicle() {
    var vehicle_id = $('#vehicle').val();
    var url = '<?php echo route('trip/get_route_by_vehicle/'); ?>' + vehicle_id;

    $.ajax({
        type: 'GET',
        url: url,
        success: function(response) {
            $('#route').val(response);
        }
    });
}


$(".ajaxForm").validate({}); // Jquery form validation initialization
$(".ajaxForm").submit(function(e) {
    var form = $(this);
    ajaxSubmit(e, form, showAllTrips);
});

// initCustomFileUploader();
</script>
