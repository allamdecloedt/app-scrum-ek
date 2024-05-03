<?php
$school_id = school_id();
$check_data = $this->db->get_where('users', array('role' => 'admin'));
if($check_data->num_rows() > 0):?>
<table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%">
    <thead>
        <tr style="background-color: #313a46; color: #ababab;">
        <th><?php echo get_phrase('name'); ?></th>
        <th><?php echo get_phrase('email'); ?></th>
        <th><?php echo get_phrase('address'); ?></th>
        <th><?php echo get_phrase('phone'); ?></th>
        <th><?php echo get_phrase('description'); ?></th>
        <th><?php echo get_phrase('category'); ?></th>
        <th><?php echo get_phrase('options'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $admins = $this->db->get_where('schools', array('Etat' => 1 , 'status'=> 1))->result_array();
        foreach($admins as $admin){
            $user = $this->db->get_where('users', array('school_id' => $admin['id']))->row_array();
            ?>
            <tr>
                <td><?php echo $admin['name']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $admin['address']; ?></td>
                <td><?php echo $admin['phone']; ?></td>

                <td><?php echo $admin['description']; ?></td>
                <td><?php echo $admin['category']; ?></td>
 
                <td>
                    <div class="dropdown text-center">
                        <button type="button" class="btn btn-sm btn-icon btn-rounded btn-outline-secondary dropdown-btn dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item" onclick="rightModal('<?php echo site_url('modal/popup/school/edit/'.$admin['id']); ?>', '<?php echo get_phrase('update_school'); ?>')"><?php echo get_phrase('edit'); ?></a>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item" onclick="confirmModal('<?php echo route('school_crud/delete/'.$admin['id']); ?>', showAllSchools )"><?php echo get_phrase('delete'); ?></a>
                        </div>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<?php else: ?>
    <?php include APPPATH.'views/backend/empty.php'; ?>
<?php endif; ?>
