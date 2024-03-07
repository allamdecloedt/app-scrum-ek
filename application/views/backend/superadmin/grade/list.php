<?php $check_data = $this->db->get_where('grades', array('school_id' => school_id(), 'session' => active_session()));
if($check_data->num_rows() > 0):?>
<table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%">
    <thead>
        <tr style="background-color: #313a46; color: #ababab;">
            <th><?php echo get_phrase('grade'); ?></th>
            <th><?php echo get_phrase('grade_point'); ?></th>
            <th><?php echo get_phrase('mark_from'); ?></th>
            <th><?php echo get_phrase('mark_upto'); ?></th>
            <th><?php echo get_phrase('options'); ?></th>
        </tr>
    </thead>
    <tbody>
	<?php
		$grades = $this->db->get_where('grades', array('school_id' => school_id(), 'session' => active_session()))->result_array();
		foreach($grades as $grade){
	?>
	<tr>
	    <td><?php echo $grade['name']; ?></td>
	    <td><?php echo $grade['grade_point']; ?></td>
	    <td><?php echo $grade['mark_from']; ?></td>
	    <td><?php echo $grade['mark_upto']; ?></td>
	    <td>
				<div class="dropdown text-center">
					<button type="button" class="btn btn-sm btn-icon btn-rounded btn-outline-secondary dropdown-btn dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></button>
					<div class="dropdown-menu dropdown-menu-end">
						<!-- item-->
						<a href="javascript:void(0);" class="dropdown-item" onclick="rightModal('<?php echo site_url('modal/popup/grade/edit/'.$grade['id'])?>', '<?php echo get_phrase('update_grade'); ?>');"><?php echo get_phrase('edit'); ?></a>
						<!-- item-->
						<a href="javascript:void(0);" class="dropdown-item" onclick="confirmModal('<?php echo route('grade/delete/'.$grade['id']); ?>', showAllGrades )"><?php echo get_phrase('delete'); ?></a>
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
