<form method="POST" class="d-block ajaxForm" action="<?php echo route('expense_category/create'); ?>">
  <!-- Champ caché pour le jeton CSRF -->
  <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
  
  <div class="form-group mb-2">
    <label for="name"><?php echo get_phrase('expense_category_name'); ?><span class="required"> * </span></label>
    <input type="text" class="form-control" id="name" name = "name" required>
  </div>

  <div class="form-group">
    <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('save_expense_category'); ?></button>
  </div>
</form>

<script>
$(".ajaxForm").validate({}); // Jquery form validation initialization
$(".ajaxForm").submit(function(e) {
  var form = $(this);
  ajaxSubmit(e, form, showAllExpenseCategories);
});
</script>
