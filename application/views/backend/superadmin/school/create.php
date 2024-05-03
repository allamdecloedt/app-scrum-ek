<form method="POST" class="d-block ajaxForm" action="<?php echo route('school_crud/create'); ?>">
    <div class="form-row">
        <div class="form-group mb-1">
            <label for="name"><?php echo get_phrase('name'); ?></label>
            <input type="text" class="form-control" id="name" name = "name" required>
            <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_name'); ?></small>
        </div>

        

        <div class="form-group mb-1">
            <label for="description"><?php echo get_phrase('description'); ?></label>
            <textarea class="form-control" id="description" name = "description" rows="5" required></textarea>
            <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_description'); ?></small>
        </div>

       
        <div class="form-group mb-1">
            <label for="phone"><?php echo get_phrase('phone_number'); ?></label>
            <input type="text" class="form-control" id="phone" name = "phone" required>
            <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_phone_number'); ?></small>
        </div>

        <div class="form-group mb-1">
            <label for="access"><?php echo get_phrase('Access'); ?></label>
            <select name="access" id="access" class="form-control select2" data-toggle = "select2">
                <option value=""><?php echo get_phrase('select_a_access'); ?></option>
                <option value="1"><?php echo get_phrase('public'); ?></option>
                <option value="2"><?php echo get_phrase('privé'); ?></option>
              
            </select>
            <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_access'); ?></small>
        </div>


        <div class="form-group mb-1">
            <label for="access"><?php echo get_phrase('Category'); ?></label>
            <select name="category" id="category" class="form-control select2" data-toggle = "select2">
                <option value=""><?php echo get_phrase('select_a_category'); ?></option>
                <?php $categories = $this->db->get_where('categories', array())->result_array(); ?>
                <?php foreach ($categories as $categorie): ?>
                    <option value="<?php echo $categorie['name']; ?>"><?php echo $categorie['name']; ?></option>
                <?php endforeach; ?>    
            </select>
            <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_category'); ?></small>
        </div>

        

        <div class="form-group mb-1">
            <label for="phone"><?php echo get_phrase('address'); ?></label>
            <textarea class="form-control" id="address" name = "address" rows="5" required></textarea>
            <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_address'); ?></small>
        </div>

        <div class="form-group mt-2 col-md-12">
            <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('create_school'); ?></button>
        </div>
    </div>
</form>

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