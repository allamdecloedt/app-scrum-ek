<?php
$date_from = strtotime(date('Y-m-01')." 00:00:00"); // hard-coded '01' for first day
$date_to   = strtotime(date('Y-m-t')." 23:59:59");
?>
<table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%">
    <thead>
        <tr>
            <th><?php echo  get_phrase('student') ; ?></th>
            <th><?php echo  get_phrase('class') ; ?></th>
            <th><?php echo  get_phrase('invoice_title') ; ?></th>
            <th><?php echo  get_phrase('total_amount') ; ?></th>
            <th><?php echo  get_phrase('paid_amount') ; ?></th>
            <th><?php echo  get_phrase('status') ; ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $invoices = $this->crud_model->get_invoice_by_date_range($date_from, $date_to, 'all', 'all')->result_array();
        foreach ($invoices as $invoice): ?>
        <tr>
            <td>
                <?php
                    $student_details = $this->user_model->get_student_details_by_id('student', $invoice['student_id']);
                 echo  $student_details['name'] ; ?>
            </td>
            <td>
                <?php
                    $class = $this->crud_model->get_classes($invoice['class_id'])->row_array();
                    echo $class['name'];
                 ?>
            </td>
            <td>
                <?php echo  $invoice['title'] ; ?>
            </td>
            <td>
                <?php echo  currency($invoice['total_amount']) ; ?>
            </td>
            <td>
                <?php echo  currency($invoice['paid_amount']) ; ?>
            </td>
            <td>
                <?php echo  ucfirst($invoice['status']) ; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
</table>
