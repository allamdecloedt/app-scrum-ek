<?php
$this->db->select('drivers.*, users.name');
$this->db->from('drivers');
$this->db->join('users', 'drivers.user_id = users.id');
$this->db->where('drivers.school_id', school_id());
$drivers = $this->db->get()->result_array(); ?>

<form method="POST" class="d-block ajaxForm" action="<?php echo route('vehicle/create'); ?>" enctype="multipart/form-data">
    <div class="form-row">
        <div class="form-group mb-1">
            <input type="hidden" name="school_id" value="<?php echo school_id(); ?>">
            <label for="vehicle_number"><?php echo get_phrase('vehicle_number'); ?></label>
            <input type="text" class="form-control" id="vehicle_number" name="vh_num" required>
            <small id="" class="form-text text-muted"><?php echo get_phrase('provide_vehicle_number'); ?></small>
        </div>

        <div class="form-group mb-1">
            <label for="vehicle_model"><?php echo get_phrase('vehicle_model'); ?></label>
            <input type="text" class="form-control" id="vehicle_model" name="vh_model" required>
            <small id="" class="form-text text-muted"><?php echo get_phrase('provide_vehicle_model'); ?></small>
        </div>

        <div class="form-group mb-1">
            <label for="chassis_number"><?php echo get_phrase('chassis_number'); ?></label>
            <input type="text" class="form-control" id="chassis_number" name="vh_chassis" required>
            <small id="" class="form-text text-muted"><?php echo get_phrase('provide_chassis_number'); ?></small>
        </div>

        <div class="form-group mb-1">
            <label for="seat_capacity"><?php echo get_phrase('seat_capacity'); ?></label>
            <input type="number" class="form-control" id="seat_capacity" name="capacity" required>
            <small id="" class="form-text text-muted"><?php echo get_phrase('seat_capacity'); ?></small>
        </div>

        <div class="form-group mb-1">
            <label for="driver"><?php echo get_phrase('driver'); ?></label>
            <select name="driver" id="driver" class="form-control select2" data-toggle="select2" required>
                <option value=""><?php echo get_phrase('select_a_driver'); ?></option>
                <?php foreach ($drivers as $driver) : ?>
                <option value="<?php echo $driver['id'] ?>"><?php echo $driver['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <small id="" class="form-text text-muted"><?php echo get_phrase('select_driver'); ?></small>
        </div>

        <div class="form-group mb-1">
            <label for="route"><?php echo get_phrase('define_route'); ?></label>
            <input type="text" class="form-control" id="route" name="route" required>
            <small id="" class="form-text text-muted"><?php echo get_phrase('define_route'); ?></small>
        </div>

        <div class="form-group mt-2 col-md-12">
            <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('create_vehicle'); ?></button>
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
});


$(".ajaxForm").validate({}); // Jquery form validation initialization
$(".ajaxForm").submit(function(e) {
    var form = $(this);
    ajaxSubmit(e, form, showAllVehicles);
});

// initCustomFileUploader();
</script>
