<?php
$school_id = school_id();
$exams = $this->db->get_where('exams', array('school_id' => $school_id, 'session' => active_session()))->result_array();

if (count($exams) > 0): ?>
<table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%">
    <thead>
        <tr style="background-color: #313a46; color: #ababab;">
            <th><?php echo get_phrase('exam_name'); ?></th>
            <th><?php echo get_phrase('starting_date'); ?></th>
            <th><?php echo get_phrase('ending_date'); ?></th>
        </tr>
    </thead>
    <tbody>
	<?php foreach($exams as $exam):?>
	<tr>
	    <td><?php echo $exam['name']; ?></td>
	    <td><?php echo date('D, d-M-Y', $exam['starting_date']); ?></td>
	    <td><?php echo date('D, d-M-Y', $exam['ending_date']); ?></td>
	</tr>
<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>
	<?php include APPPATH.'views/backend/empty.php'; ?>
<?php endif; ?>
