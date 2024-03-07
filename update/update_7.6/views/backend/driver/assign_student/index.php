<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body py-2">
                <h4 class="page-title d-inline-block">
                    <span class="mdi mdi-clipboard-account"></span> <?php echo get_phrase('assigned_student'); ?>
                </h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form class="filter_list px-3">
                <div class="row mt-3">
                    <h5><?php echo get_phrase('select_a_vehicle'); ?></h5>
                    <div class="col-md-4 mb-1">
                        <select name="filter_vehicle_id" id="filter_by_vehicle" class="form-control select2" data-toggle="select2" required>
                            <option value=""><?php echo get_phrase('select_a_vehicle'); ?></option>
                            <?php foreach ($this->db->where('driver', driver_id())->get('vehicles')->result_array() as $vehicle) : ?>
                            <option value="<?php echo $vehicle['id']; ?>">
                                <?php echo $vehicle['vh_num'] . ', ' . $vehicle['vh_model']; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-block btn-secondary"><?php echo get_phrase('filter'); ?></button>
                    </div>
                </div>
            </form>

            <div class="card-body assigned_student_content">
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
$(document).ready(function() {
    $('.filter_list').submit(function(e) {
        e.preventDefault();

        let vehicle_id = $('#filter_by_vehicle').val();
        $.ajax({
            type: "POST",
            url: "<?php echo route('assigned_student/filter/') ?>" + vehicle_id,
            success: function(response) {
                $('.assigned_student_content').html(response);
                initDataTable('basic-datatable');
            }
        });
    });
});
</script>
