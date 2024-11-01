<?php
$date_from = strtotime(date('m/01/Y')." 00:00:00"); // hard-coded '01' for first day
$date_to   = strtotime(date('m/t/Y')." 23:59:59");
?>
<div style="overflow-y: auto;" style="max-height: 171px;">
    <div class="timeline-alt pb-0">
        <?php $events = $this->crud_model->get_current_month_events()->result_array();?>
        <?php foreach ($events as $event): ?>
            <?php if (strtotime($event['starting_date']) >= $date_from && strtotime($event['starting_date']) <= $date_to): ?>
                <div class="timeline-item">
                    <i class="mdi mdi-upload bg-info-lighten text-info timeline-icon"></i>
                    <div class="timeline-item-info">
                        <a href="#" class="text-info font-weight-bold mb-1 d-block"><?php echo $event['title']; ?></a>
                        <p style="font-size: 12px;"><?php echo date('D, d-M-Y', strtotime($event['starting_date'])); ?> - <?php echo date('D, d-M-Y', strtotime($event['ending_date'])); ?></p>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
