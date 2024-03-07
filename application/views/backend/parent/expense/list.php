<?php
$expenses = array();
if (isset($expense_category_id) && $expense_category_id > 0) {
  if ($expense_category_id != 'all') {
    $expenses = $this->crud_model->get_expense($date_from, $date_to, $expense_category_id)->result_array();
  }else{
    $expenses = $this->crud_model->get_expense($date_from, $date_to)->result_array();
  }
}else{
  $expenses = $this->crud_model->get_expense($date_from, $date_to)->result_array();
}
if (count($expenses) > 0): ?>
<div class="table-responsive-sm">
  <table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%">
    <thead class="thead-dark">
      <tr>
        <th><?php echo get_phrase('date'); ?></th>
        <th><?php echo get_phrase('amount'); ?></th>
        <th><?php echo get_phrase('expense_category'); ?></th>
        <th><?php echo get_phrase('option'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($expenses as $expense): ?>
        <tr>
          <td>
            <?php echo date('D, d-M-Y', $expense['date']); ?>
          </td>
          <td>
            <?php echo $expense['amount']; ?>
          </td>
          <td>
            <?php
            $expense_category_details = $this->db->get_where('expense_categories', array('id' => $expense['expense_category_id']))->row_array();
            echo $expense_category_details['name'];
            ?>
          </td>
          <td>
            <div class="dropdown text-center">
    					<button type="button" class="btn btn-sm btn-icon btn-rounded btn-outline-secondary dropdown-btn dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></button>
    					<div class="dropdown-menu dropdown-menu-end">
    						<!-- item-->
    						<a href="javascript:void(0);" class="dropdown-item" onclick="rightModal('<?php echo site_url('modal/popup/expense/edit/'.$expense['id'])?>', '<?php echo get_phrase('update_expense'); ?>');"><?php echo get_phrase('edit'); ?></a>
    						<!-- item-->
    						<a href="javascript:void(0);" class="dropdown-item" onclick="confirmModal('<?php echo route('expense/delete/'.$expense['id']); ?>', showAllExpenses )"><?php echo get_phrase('delete'); ?></a>
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
