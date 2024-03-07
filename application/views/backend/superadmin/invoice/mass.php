<form method="POST" class="d-block ajaxForm" action="<?php echo route('invoice/mass'); ?>">
    <div class="form-row">
        <div class="form-group mb-1">
            <label for="class_id_on_create"><?php echo get_phrase('class'); ?></label>
            <select name="class_id" id="class_id_on_create" class="form-control select2" data-bs-toggle="select2"  required onchange="classWiseSectionOnCreate(this.value)">
                <option value=""><?php echo get_phrase('select_a_class'); ?></option>
                <?php $classes = $this->crud_model->get_classes()->result_array(); ?>
                <?php foreach($classes as $class): ?>
                    <option value="<?php echo $class['id']; ?>"><?php echo $class['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group  mt-2">
            <label for="section_id_on_create" class="col-md-3 col-form-label"><?php echo get_phrase('section'); ?></label>
            <select name="section_id" id = "section_id_on_create" class="form-control select2" data-bs-toggle="select2" required>
                <option value=""><?php echo get_phrase('select_section'); ?></option>
            </select>
        </div>

        <div class="form-group mt-2">
            <label for="title"><?php echo get_phrase('invoice_title'); ?></label>
            <input type="text" class="form-control" id="title" name = "title" required>
        </div>

        <div class="form-group mt-2">
            <label for="total_amount"><?php echo get_phrase('total_amount').' ('.currency_code_and_symbol('code').')'; ?></label>
            <input type="number" class="form-control" id="total_amount" name = "total_amount" required>
        </div>

        <div class="form-group mt-2">
            <label for="paid_amount"><?php echo get_phrase('paid_amount').' ('.currency_code_and_symbol('code').')'; ?></label>
            <input type="number" class="form-control" id="paid_amount" name = "paid_amount" required>
        </div>

        <div class="form-group mt-2">
            <label for="status"><?php echo get_phrase('status'); ?></label>
            <select name="status" id="status" class="form-control select2" data-bs-toggle="select2" required >
                <option value=""><?php echo get_phrase('select_a_status'); ?></option>
                <option value="paid"><?php echo get_phrase('paid'); ?></option>
                <option value="unpaid"><?php echo get_phrase('unpaid'); ?></option>
            </select>
        </div>
    </div>
    <div class="form-group mt-2">
        <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('create_mass_invoice'); ?></button>
    </div>
</form>

<script>
$(".ajaxForm").validate({}); // Jquery form validation initialization
$(".ajaxForm").submit(function(e) {
    var form = $(this);
    ajaxSubmit(e, form, showAllInvoices);
});

$(document).ready(function () {
    $('select.select2:not(.normal)').each(function () { $(this).select2({ dropdownParent: '#right-modal' }); }); //initSelect2(['#class_id_on_create', '#section_id_on_create', '#status']);
});

function classWiseSectionOnCreate(classId) {
    $.ajax({
        url: "<?php echo route('section/list/'); ?>"+classId,
        success: function(response){
            $('#section_id_on_create').html(response);
        }
    });
}
</script>
