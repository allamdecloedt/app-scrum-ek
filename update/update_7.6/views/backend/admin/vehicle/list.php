<?php
$school_id = school_id();
$check_data = $this->db->get_where('vehicles', array('school_id' => $school_id));
if ($check_data->num_rows() > 0) : ?>
<table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
    <thead>
        <tr style="background-color: #313a46; color: #ababab;">
            <th><?php echo get_phrase('#'); ?></th>
            <th><?php echo get_phrase('vh_number'); ?></th>
            <th><?php echo get_phrase('vh_chassis'); ?></th>
            <th><?php echo get_phrase('vh_model'); ?></th>
            <th><?php echo get_phrase('capacity'); ?></th>
            <th><?php echo get_phrase('route'); ?></th>
            <th><?php echo get_phrase('assigned_driver'); ?></th>
            <th><?php echo get_phrase('action'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
            $vehicles = $this->db->where('school_id', $school_id)->order_by('id', 'desc')->get('vehicles')->result_array();
            foreach ($vehicles as $key => $vehicle) {
                $this->db->select('drivers.*, users.name');
                $this->db->from('drivers');
                $this->db->join('users', 'drivers.user_id = users.id');
                $this->db->where('drivers.school_id', school_id());
                $this->db->where('drivers.id', $vehicle['driver']);
                $driver = $this->db->get()->row_array();
                $booked_seat = $this->db->where('vehicle_id', $vehicle['id'])->get('assign_students')->num_rows();
            ?>
        <tr>
            <td><?php echo $key + 1; ?></td>
            <td><?php echo $vehicle['vh_num']; ?></td>
            <td><?php echo $vehicle['vh_chassis']; ?></td>
            <td><?php echo $vehicle['vh_model']; ?></td>
            <td><?php echo $booked_seat . '/' . $vehicle['capacity']; ?></td>
            <td><?php echo $vehicle['route']; ?></td>
            <td><?php echo $driver['name']; ?></td>
            <td>
                <div class="dropdown text-center">
                    <button type="button" class="btn btn-sm btn-icon btn-rounded btn-outline-secondary dropdown-btn dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown"
                        aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a href="javascript:void(0);" class="dropdown-item"
                            onclick="rightModal('<?php echo site_url('modal/popup/vehicle/edit/' . $vehicle['id']); ?>', '<?php echo get_phrase('update_vehicle'); ?>')"><?php echo get_phrase('edit'); ?></a>
                        <a href="javascript:void(0);" class="dropdown-item"
                            onclick="confirmModal('<?php echo route('vehicle/delete/' . $vehicle['id']); ?>', showAllVehicles )"><?php echo get_phrase('delete'); ?></a>
                    </div>
                </div>
            </td>
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
