<?php $expense_categories = $this->crud_model->get_expense_categories()->result_array(); ?>
<?php if (count($expense_categories) > 0): ?>
    <div class="table-responsive-sm">
        <table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%">
            <thead class="thead-dark">
                <tr>
                    <th><?php echo get_phrase('name'); ?></th>
                    <th><?php echo get_phrase('option'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($expense_categories as $expense_category): ?>
                    <tr>
                        <td><?php echo $expense_category['name']; ?></td>
                        <td>
                            <div class="dropdown text-center">
                                <button type="button" class="btn btn-sm btn-icon btn-rounded btn-outline-secondary dropdown-btn dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item" onclick="rightModal('<?php echo site_url('modal/popup/expense_category/edit/'.$expense_category['id'])?>', '<?php echo get_phrase('update_expense_category'); ?>');"><?php echo get_phrase('edit'); ?></a>
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item" onclick="confirmModal('<?php echo route('expense_category/delete/'.$expense_category['id']); ?>', showAllExpenseCategories )"><?php echo get_phrase('delete'); ?></a>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <?php include APPPATH.'views/backend/empty.php'; ?>
<?php endif; ?>
