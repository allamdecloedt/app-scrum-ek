<?php
$school_id = school_id();
$check_data = $this->db->get_where('trips', array('school_id' => $school_id, 'driver_id' => driver_id(), 'status' => 0));
if ($check_data->num_rows() > 0) : ?>
<h4 class="mb-3"><?php echo get_phrase('trip_list'); ?></h4>
<table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
    <thead>
        <tr style="background-color: #313a46; color: #ababab;">
            <th><?php echo get_phrase('#'); ?></th>
            <th><?php echo get_phrase('vehicle_info'); ?></th>
            <th><?php echo get_phrase('route'); ?></th>
            <th><?php echo get_phrase('start_time'); ?></th>
            <th><?php echo get_phrase('end_time'); ?></th>
        </tr>
    </thead>

    <tbody>
        <?php
            $this->db->select('trips.*, vehicles.*');
            $this->db->from('trips');
            $this->db->join('vehicles', 'trips.vehicle_id = vehicles.id');
            $this->db->order_by('trips.id', 'desc');
            $this->db->where('trips.school_id', school_id());
            $this->db->where('trips.status', 0);
            $trips = $this->db->get()->result_array();
            foreach ($trips as $key => $trip) {
            ?>
        <tr>
            <td><?php echo $key + 1; ?></td>
            <td><?php echo $trip['vh_num'] . ', ' . $trip['vh_model']; ?></td>
            <td><?php echo $trip['route']; ?></td>
            <td><?php echo date('m-d-y h:i a', $trip['start_time']); ?></td>
            <td><?php echo date('m-d-y h:i a', $trip['end_time']); ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<?php else : ?>
<?php include APPPATH . 'views/backend/empty.php'; ?>
<?php endif; ?>
<style>
td {
    vertical-align: middle;
    height: 40px;
}

</style>
