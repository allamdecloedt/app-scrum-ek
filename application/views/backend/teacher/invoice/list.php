<table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%">
    <thead class="thead-dark">
        <tr>
            <th><?php echo get_phrase('student'); ?></th>
            <th><?php echo get_phrase('class'); ?></th>
            <th><?php echo get_phrase('invoice_title'); ?></th>
            <th><?php echo get_phrase('total_amount'); ?></th>
            <th><?php echo get_phrase('paid_amount'); ?></th>
            <th><?php echo get_phrase('status'); ?></th>
            <th><?php echo get_phrase('creation_date'); ?></th>
            <th><?php echo get_phrase('option'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php $invoices = $this->crud_model->get_invoice_by_date_range($date_from, $date_to)->result_array();
        foreach ($invoices as $invoice):
            $student_details = $this->user_model->get_student_details_by_id('student', $invoice['student_id']);
            $class_details = $this->crud_model->get_class_details_by_id($invoice['class_id'])->row_array(); ?>
            <tr>
                <td> <?php echo $student_details['name']; ?> </td>
                <td> <?php echo $class_details['name']; ?> </td>
                <td> <?php echo $invoice['title']; ?> </td>
                <td> <?php echo $invoice['total_amount']; ?> </td>
                <td> <?php echo $invoice['paid_amount']; ?> </td>
                <td>
                    <?php if (strtolower($invoice['status']) == 'unpaid'): ?>
                        <span class="badge badge-danger-lighten"><?php echo ucfirst($invoice['status']); ?></span>
                    <?php else: ?>
                        <span class="badge badge-success-lighten"><?php echo ucfirst($invoice['status']); ?></span>
                    <?php endif; ?>
                </td>
                <td> <?php echo date('D, d-M-Y', $invoice['created_at']); ?> </td>
                <td>
                    <div class="dropdown text-center">
                        <button type="button" class="btn btn-sm btn-icon btn-rounded btn-outline-secondary dropdown-btn dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item" onclick="rightModal('<?php echo site_url('modal/popup/invoice/edit/'.$invoice['id']); ?>', '<?php echo get_phrase('update_invoice'); ?>');"><?php echo get_phrase('edit'); ?></a>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item" onclick="confirmModal('<?php echo route('invoice/delete/'.$invoice['id']); ?>', showAllInvoices )"><?php echo get_phrase('delete'); ?></a>
                        </div>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
