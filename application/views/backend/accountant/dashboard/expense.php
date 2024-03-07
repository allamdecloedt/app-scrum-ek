<?php
$date_from = strtotime(date('Y-m-01')." 00:00:00"); // hard-coded '01' for first day
$date_to   = strtotime(date('Y-m-t')." 23:59:59");
$expenses = $this->crud_model->get_expense($date_from, $date_to)->result_array(); ?>
<div class="table-responsive-sm">
    <table class="table table-striped table-centered table-bordered mb-0 table-responsive">
        <thead>
            <tr>
                <th width = "60%"><?php echo get_phrase('expense') ;?></th>
                <th width = "40%"><?php echo get_phrase('amount') ;?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($expenses) > 0): ?>
                <?php foreach ($expenses as $expense): ?>
                    <tr>
                        <td>
                            <?php
                            $expense_category_details = $this->db->get_where('expense_categories', array('id' => $expense['expense_category_id']))->row_array();
                            echo $expense_category_details['name'] ;?>
                        </td>
                        <td><?php echo currency($expense['amount']) ;?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <td colspan="2"><?php echo get_phrase('No Data Found'); ?></td>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<style>
.table-responsive {
    display: inline-table;
}
</style>
