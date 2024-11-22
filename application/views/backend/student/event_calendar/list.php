<div class="row">
	<div class="col-md-6">
		<div class="card">
			<div class="card-body">
				<div id="calendar"></div>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="card">
			<div class="card-body">
				<?php

					$user_id = $this->session->userdata('user_id');
					$student_datas = $this->db->get_where('students', array('user_id' => $user_id))->result_array();
					if($student_datas){
						$school_ids = array();		
						foreach ($student_datas as $student_data) {
							$school_ids[] = $student_data['school_id'];
						}
						$this->db->where_in('school_id', $school_ids);

						
					}
					$event_calendars = $this->db->get('event_calendars')->num_rows();

				?>
				<?php if($event_calendars > 0): ?>
					<table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%">
						<thead>
							<tr style="background-color: #313a46; color: #ababab;">
								<th><?php echo get_phrase('event_title'); ?></th>
								<th><?php echo get_phrase('from'); ?></th>
								<th><?php echo get_phrase('to'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach($student_datas as $student_data){
								$enrols_datas = $this->db->get_where('enrols', array('student_id' => $student_data['id'],'school_id' => $student_data['school_id']))->num_rows();
								$school_name = $this->db->get_where('schools', array('id' => $student_data['school_id']))->row('name');
								if($enrols_datas > 0){
								$event_calendars = $this->db->get_where('event_calendars', array('school_id' => $student_data['school_id'], 'session' => active_session()))->result_array();
								
								foreach($event_calendars as $event_calendar){
								?>
								<tr>
									<td><?php echo $event_calendar['title'] ." - ".$school_name; ?></td>
									<td><?php echo date('D, d M Y', strtotime($event_calendar['starting_date'])); ?></td>
									<td><?php echo date('D, d M Y', strtotime($event_calendar['ending_date'])); ?></td>
								</tr>
							<?php } ?>
							<?php } ?>
							<?php }  ?>
							
						</tbody>
					</table>
				<?php else: ?>
					<?php include APPPATH.'views/backend/empty.php'; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
