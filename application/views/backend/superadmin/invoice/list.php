<table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%">
    <thead class="thead-dark">
        <tr>
            <th><?php echo get_phrase('invoice_no'); ?></th>
            <th><?php echo get_phrase('student'); ?></th>
            <th><?php echo get_phrase('invoice_title'); ?></th>
            <th><?php echo get_phrase('total_amount'); ?></th>
            <th><?php echo get_phrase('paid_amount'); ?></th>
            <th><?php echo get_phrase('status'); ?></th>
            <th><?php echo get_phrase('option'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php $invoices = $this->crud_model->get_invoice_by_date_range($date_from, $date_to, $selected_class, $selected_status)->result_array();
        foreach ($invoices as $invoice):
            $student_details = $this->user_model->get_student_details_by_id('student', $invoice['student_id']);
            $class_details = $this->crud_model->get_class_details_by_id($invoice['class_id'])->row_array(); ?>
            <tr>
                <td> <?php echo sprintf('%08d', $invoice['id']); ?> </td>
                <td>
                    <?php echo $student_details['name']; ?> <br>
                    <small> <strong><?php echo get_phrase('class'); ?> :</strong> <?php echo $class_details['name']; ?></small>
                </td>
                <td> <?php echo $invoice['title']; ?> </td>
                <td>
                    <?php echo currency($invoice['total_amount']); ?> <br>
                    <small> <strong> <?php echo get_phrase('created_at'); ?> : </strong> <?php echo date('d-M-Y', $invoice['created_at']); ?> </small>
                </td>
                <td>
                    <?php echo currency($invoice['paid_amount']); ?> <br>
                    <small>
                        <strong> <?php echo get_phrase('payment_date'); ?> : </strong>
                        <?php if ($invoice['updated_at'] > 0): ?>
                            <?php echo date('d-M-Y', $invoice['updated_at']); ?>
                        <?php else: ?>
                            <?php echo get_phrase('not_found'); ?>
                        <?php endif; ?>

                    </small>
                </td>
                <td>
                    <?php if (strtolower($invoice['status']) == 'unpaid'): ?>
                        <span class="badge badge-danger-lighten"><?php echo ucfirst($invoice['status']); ?></span>
                    <?php else: ?>
                        <span class="badge badge-success-lighten"><?php echo ucfirst($invoice['status']); ?></span>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="dropdown text-center">
                        <button type="button" class="btn btn-sm btn-icon btn-rounded btn-outline-secondary dropdown-btn dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="<?php echo route('invoice/invoice/'.$invoice['id']); ?>" class="dropdown-item" target="_blank"><?php echo get_phrase('print_invoice'); ?></a>
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
