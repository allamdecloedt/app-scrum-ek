<?php
$school_id = school_id();
$check_data = $this->db->get_where('users', array('school_id' => $school_id, 'role' => 'librarian'));
if($check_data->num_rows() > 0):?>
<table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%">
	<thead>
		<tr style="background-color: #313a46; color: #ababab;">
			<th><?php echo get_phrase('name'); ?></th>
			<th><?php echo get_phrase('email'); ?></th>
			<th><?php echo get_phrase('options'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		$users = $this->db->get_where('users', array('school_id' => $school_id, 'role' => 'librarian'))->result_array();
		foreach($users as $user):?>
		<tr>
			<td><?php echo $user['name']; ?></td>
			<td><?php echo $user['email']; ?></td>
			<td>
				<div class="dropdown text-center">
					<button type="button" class="btn btn-sm btn-icon btn-rounded btn-outline-secondary dropdown-btn dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></button>
					<div class="dropdown-menu dropdown-menu-end">
						<!-- item-->
						<a href="javascript:void(0);" class="dropdown-item" onclick="rightModal('<?php echo site_url('modal/popup/librarian/edit/'.$user['id'])?>', '<?php echo get_phrase('update_librarian'); ?>');"><?php echo get_phrase('edit'); ?></a>
						<!-- item-->
						<a href="javascript:void(0);" class="dropdown-item" onclick="confirmModal('<?php echo route('librarian/delete/'.$user['id']); ?>', showAllLibrarians )"><?php echo get_phrase('delete'); ?></a>
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
