<?php $classes = $this->db->get_where('classes', array('id' => $param1))->result_array(); ?>
<?php foreach($classes as $class){ ?>
<form method="POST" class="d-block ajaxForm" action="<?php echo route('manage_class/update/'.$param1); ?>">
    <div class="form-row">
    <div class="form-group mb-1 col-md-12">
            <label for="name"><?php echo get_phrase('class_name'); ?></label>
            <input type="text" class="form-control" value="<?php echo $class['name']; ?>" id="name" name = "name" required>
            <small id="name_help" class="form-text text-muted"><?php echo get_phrase('provide_class_name'); ?></small>
        </div>
        <div class="form-group mb-1 col-md-12">
            <label for="price"><?php echo get_phrase('class_price'); ?></label>
            <?php $currencies = $this->db->get_where('settings_school', array('school_id' => school_id()))->row('system_currency'); ?>

            <div class="form-inline">
            <input type="text" class="form-control col-md-10" value="<?php echo $class['price']; ?>" id="price" name = "price" required>
            <input type="text" class="form-control currency-input col-md-2" value="<?php echo $currencies; ?>" disabled>
            <input type="hidden"  id = "currency" name="currency" value="<?php echo $currencies; ?>" >
        </div>
           
            <small id="price_help" class="form-text text-muted"><?php echo get_phrase('provide_class_price'); ?></small>
        </div>


        <div class="form-group  col-md-12">
            <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('update_class'); ?></button>
        </div>
    </div>
</form>
<?php } ?>

<script>
  $(".ajaxForm").validate({}); // Jquery form validation initialization
  $(".ajaxForm").submit(function(e) {
      var form = $(this);
      ajaxSubmit(e, form, showAllClasses);
  });
</script>
<style>
    .form-inline .form-control {
        display: inline-block;
        width: 75%;
        vertical-align: middle;
    }
    .form-inline .currency-input {
        max-width: 80px;
    }
</style>