<?php
$school_id = school_id();
$class_rooms = $this->db->get_where('class_rooms', array('school_id' => $school_id))->result_array();
if (count($class_rooms) > 0): ?>
<table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%">
	<thead>
		<tr style="background-color: #313a46; color: #ababab;">
			<th><?php echo get_phrase('name'); ?></th>
			<th><?php echo get_phrase('options'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($class_rooms as $class_room):?>
			<tr>
				<td><?php echo $class_room['name']; ?></td>
				<td>
					<button type="button" class="btn btn-icon btn-secondary btn-sm btn-dark" style="margin-right:5px;" onclick="rightModal('<?php echo site_url('modal/popup/class_room/edit/'.$class_room['id'])?>', '<?php echo get_phrase('update_class_room'); ?>');" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="<?php echo get_phrase('update_class_room_info'); ?>"> <i class="mdi mdi-wrench"></i></button>
					<button id="uname" type="button" class="btn btn-icon btn-secondary btn-sm" style="margin-right:5px;" onclick="confirmModal('<?php echo route('class_room/delete/'.$class_room['id']); ?>', showAllClassRooms)" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="<?php echo get_phrase('delete_class_room'); ?>"> <i class="mdi mdi-window-close"></i></button>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>
	<?php include APPPATH.'views/backend/empty.php'; ?>
<?php endif; ?>
