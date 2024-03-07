<?php
$school_id = school_id();
$addons = $this->db->get('addons')->result_array();
if (count($addons) > 0): ?>
<table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%">
    <thead>
        <tr style="background-color: #313a46; color: #ababab;">
            <th><?php echo get_phrase('name'); ?></th>
            <th><?php echo get_phrase('status'); ?></th>
            <th><?php echo get_phrase('version'); ?></th>
            <th><?php echo get_phrase('options'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($addons as $addon): ?>
            <tr>
                <td><?php echo $addon['name']; ?></td>
                <td>
                    <?php if ($addon['status']): ?>
                        <span class="badge badge-success"><?php echo get_phrase('active'); ?></span>
                    <?php else: ?>
                        <span class="badge bg-danger"><?php echo get_phrase('deactivated'); ?></span>
                    <?php endif; ?>
                </td>
                <td><?php echo $addon['version']; ?></td>
                <td>
                    <div class="dropdown text-center">
                        <button type="button" class="btn btn-sm btn-icon btn-rounded btn-outline-secondary dropdown-btn dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <?php if ($addon['status']): ?>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item" onclick="confirmModal('<?php echo route('addon_manager/deactive/'.$addon['id']); ?>', showAllAddons)"><?php echo get_phrase('deactive'); ?></a>
                            <?php else: ?>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item" onclick="confirmModal('<?php echo route('addon_manager/activate/'.$addon['id']); ?>', showAllAddons)"><?php echo get_phrase('activate'); ?></a>
                            <?php endif; ?>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item" onclick="confirmModal('<?php echo route('addon_manager/delete/'.$addon['id']); ?>', showAllAddons)"><?php echo get_phrase('remove'); ?></a>
                        </div>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
    <?php include APPPATH.'views/backend/empty.php'; ?>
<?php endif; ?>
