<?php
$school_id = school_id();
$check_data = $this->db->get_where('drivers', array('school_id' => $school_id));
if ($check_data->num_rows() > 0) : ?>
<table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
    <thead>
        <tr style="background-color: #313a46; color: #ababab;">
            <th><?php echo get_phrase('#'); ?></th>
            <th><?php echo get_phrase('image'); ?></th>
            <th><?php echo get_phrase('name'); ?></th>
            <th><?php echo get_phrase('email'); ?></th>
            <th><?php echo get_phrase('phone'); ?></th>
            <th><?php echo get_phrase('vehicles'); ?></th>
            <th><?php echo get_phrase('options'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
            $this->db->select('drivers.*, users.name, users.email, users.phone');
            $this->db->from('drivers');
            $this->db->join('users', 'drivers.user_id = users.id');
            $this->db->where('drivers.school_id', school_id());
            $this->db->order_by('drivers.id', 'desc');
            $drivers = $this->db->get()->result_array();

            foreach ($drivers as $key => $driver) {
                $assigned_vehicles = $this->db->where(['school_id', $school_id, 'driver' => $driver['id']])->get('vehicles')->num_rows();
            ?>
        <tr>
            <td><?php echo $key + 1; ?></td>
            <td><img class="rounded-circle" width="50" height="50" src="<?php echo $this->user_model->get_user_image($driver['user_id']); ?>"></td>
            <td><?php echo $driver['name']; ?></td>
            <td><?php echo $driver['email']; ?></td>
            <td><?php echo $driver['phone']; ?></td>
            <td><?php echo $assigned_vehicles; ?></td>
            <td>
                <div class="dropdown text-center">
                    <button type="button" class="btn btn-sm btn-icon btn-rounded btn-outline-secondary dropdown-btn dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown"
                        aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item"
                            onclick="rightModal('<?php echo site_url('modal/popup/driver/edit/' . $driver['user_id']); ?>', '<?php echo get_phrase('update_driver'); ?>')"><?php echo get_phrase('edit'); ?></a>
                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item"
                            onclick="confirmModal('<?php echo route('driver/delete/' . $driver['user_id']); ?>', showAllDrivers)"><?php echo get_phrase('delete'); ?></a>
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
