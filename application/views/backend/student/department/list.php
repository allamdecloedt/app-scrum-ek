<?php
$school_id = school_id();
$departments = $this->db->get_where('departments', array('school_id' => $school_id))->result_array();
if (count($departments) > 0): ?>
<table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%">
    <thead>
        <tr style="background-color: #313a46; color: #ababab;">
            <th><?php echo get_phrase('name'); ?></th>
            <th><?php echo get_phrase('options'); ?></th>
        </tr>
    </thead>
    <tbody>
	<?php foreach($departments as $department): ?>
	<tr>
	    <td><?php echo $department['name']; ?></td>
	    <td>
	    	<button type="button" class="btn btn-icon btn-secondary btn-sm btn-dark" style="margin-right:5px;" onclick="rightModal('<?php echo site_url('modal/popup/department/edit/'.$department['id'])?>', '<?php echo get_phrase('update_department'); ?>');" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="<?php echo get_phrase('update_department_info'); ?>"> <i class="mdi mdi-wrench"></i></button>

	    	<button type="button" class="btn btn-icon btn-secondary btn-sm" style="margin-right:5px;" onclick="confirmModal('<?php echo route('department/delete/'.$department['id']); ?>', showAllDepartments)" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="<?php echo get_phrase('delete_department'); ?>"> <i class="mdi mdi-window-close"></i></button>
	    </td>
	</tr>
<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>
<?php include APPPATH.'views/backend/empty.php'; ?>
<?php endif; ?>
