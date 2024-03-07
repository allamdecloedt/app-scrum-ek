<?php $check_data = $this->db->get_where('grades', array('school_id' => school_id(), 'session' => active_session()));
if($check_data->num_rows() > 0):?>
<table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%">
    <thead>
        <tr style="background-color: #313a46; color: #ababab;">
            <th><?php echo get_phrase('grade'); ?></th>
            <th><?php echo get_phrase('grade_point'); ?></th>
            <th><?php echo get_phrase('mark_from'); ?></th>
            <th><?php echo get_phrase('mark_upto'); ?></th>
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
	</tr>
	<?php } ?>
	</tbody>
</table>
<?php else: ?>
	<?php include APPPATH.'views/backend/empty.php'; ?>
<?php endif; ?>
