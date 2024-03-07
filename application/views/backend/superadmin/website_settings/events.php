<div class="card">
  <div class="card-body">
    <h4 class="header-title">
      <?php echo get_phrase('events') ;?>
    </h4>
    <button type="button" class="btn btn-outline-primary btn-rounded alignToTitle float-end" onclick="rightModal('<?php echo site_url('modal/popup/website_settings/create_event'); ?>', '<?php echo get_phrase('create_event'); ?>')"> <i class="mdi mdi-plus"></i> <?php echo get_phrase('create_event'); ?></button>
    <br><br>
    <div class="alert alert-warning" role="alert">
      <i class="dripicons-information me-2"></i> <?php echo get_phrase('this_events_will_get_appeared_at'); ?> <strong><?php echo get_phrase('website'); ?> ( <?php echo get_phrase('frontend'); ?> ) <?php echo get_phrase('events'); ?></strong>.
    </div>
    <?php $school_id = school_id(); ?>
    <?php $events = $this->db->get_where('frontend_events', array('school_id' => $school_id)); ?>
    <?php if($events->num_rows() > 0): ?>
      <table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%">
        <thead>
          <tr style="background-color: #313a46; color: #ababab;">
            <th><?php echo get_phrase('event_title'); ?></th>
            <th><?php echo get_phrase('date'); ?></th>
            <th><?php echo get_phrase('status'); ?></th>
            <th><?php echo get_phrase('options'); ?></th>
          </tr>
        </thead>
        <tbody>
          <?php
          $events = $events->result_array();
          foreach($events as $event){
            ?>
            <tr>
              <td><?php echo $event['title']; ?></td>
              <td><?php echo date('D, d M Y', $event['timestamp']); ?></td>
              <td>
                <?php if ($event['status']): ?>
                  <span class="badge badge-success"><?php echo get_phrase('active'); ?></span>
                <?php else: ?>
                  <span class="badge bg-danger"><?php echo get_phrase('inactive'); ?></span>
                <?php endif; ?>
              </td>
              <td>
                <div class="dropdown text-center">
                  <button type="button" class="btn btn-sm btn-icon btn-rounded btn-outline-secondary dropdown-btn dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></button>
                  <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item" onclick="rightModal('<?php echo site_url('modal/popup/website_settings/edit_event/'.$event['frontend_events_id']); ?>', '<?php echo get_phrase('update_event'); ?>');"><?php echo get_phrase('edit'); ?></a>
                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item" onclick="confirmModal('<?php echo route('events/delete/'.$event['frontend_events_id']); ?>', showAllEvents)"><?php echo get_phrase('delete'); ?></a>
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
  </div> <!-- end card body-->
</div>
