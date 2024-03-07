<?php
  $expense_details = $this->crud_model->get_expense_by_id($param1);
 ?>
<form method="POST" class="d-block ajaxForm" action="<?php echo route('expense/update/'.$param1); ?>">
  <div class="form-row">
    <div class="form-group mb-1">
      <label for="date"><?php echo get_phrase('date'); ?></label>
      <input type="text" class="form-control date" id="date" data-bs-toggle="date-picker" data-single-date-picker="true" name = "date" value="<?php echo date('m/d/Y', $expense_details['date']) ?>" required>
    </div>

    <div class="form-group mb-1">
      <label for="amount"><?php echo get_phrase('amount').' ('.currency_code_and_symbol('code').')'; ?></label>
      <input type="text" class="form-control" id="amount" name = "amount" value="<?php echo $expense_details['amount']; ?>" required>
    </div>

    <div class="form-group mb-1">
      <label for="expense_category_id"><?php echo get_phrase('expense_category'); ?></label>
      <select class="form-control select2" data-toggle = "select2" name="expense_category_id" id = "expense_category_id_on_update" required>
        <option value=""><?php echo get_phrase('select_an_expense_category'); ?></option>
        <?php
        $expense_categories = $this->crud_model->get_expense_categories()->result_array();
        foreach ($expense_categories as $expense_category): ?>
        <option value="<?php echo $expense_category['id']; ?>" <?php if($expense_details['expense_category_id'] == $expense_category['id']):?> selected <?php endif; ?>><?php echo $expense_category['name']; ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="form-group  col-md-12">
    <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('update_expense'); ?></button>
  </div>
</div>
</form>

<script>
$(document).ready(function() {
  $('select.select2:not(.normal)').each(function () { $(this).select2({ dropdownParent: '#right-modal' }); }); //initSelect2(['#expense_category_id_on_update']);
  $('#date').daterangepicker();
});

$(".ajaxForm").validate({}); // Jquery form validation initialization
$(".ajaxForm").submit(function(e) {
  var form = $(this);
  ajaxSubmit(e, form, showAllExpenses);
});
</script>
