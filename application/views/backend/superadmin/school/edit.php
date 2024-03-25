<?php
$schools = $this->db->get_where('schools', array('id' => $param1))->result_array();
foreach($schools as $school): ?>
<form method="POST" class="d-block ajaxForm" action="<?php echo route('school_crud/update/'.$param1); ?>">
  <div class="form-row">
    <div class="form-group mb-1">
      <label for="name"><?php echo get_phrase('name'); ?></label>
      <input type="text" value="<?php echo $school['name']; ?>" class="form-control" id="name" name = "name" required>
      <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_name'); ?></small>
    </div>



    <div class="form-group mb-1">
      <label for="phone"><?php echo get_phrase('phone_number'); ?></label>
      <input type="text" value="<?php echo $school['phone']; ?>" class="form-control" id="phone" name = "phone" required>
      <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_phone_number'); ?></small>
    </div>

   



    

    <div class="form-group mb-1">
      <label for="phone"><?php echo get_phrase('address'); ?></label>
      <textarea class="form-control" id="address" name = "address" rows="5" required><?php echo $school['address']; ?></textarea>
      <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_address'); ?></small>
    </div>

    <div class="form-group mt-2 col-md-12">
      <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('update_school'); ?></button>
    </div>
  </div>
</form>
<?php endforeach; ?>

<script>

  $(document).ready(function () {
    $('select.select2:not(.normal)').each(function () { $(this).select2({ dropdownParent: '#right-modal' }); });
  });
  $(".ajaxForm").validate({}); // Jquery form validation initialization
  $(".ajaxForm").submit(function(e) {
    var form = $(this);
    ajaxSubmit(e, form, showAllSchools);
  });
</script>
