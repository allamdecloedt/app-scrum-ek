
<form method="POST" class="d-block ajaxForm" action="<?php echo route('manage_class/section/'.$param1); ?>">
    <?php $count = 0; ?>
    <?php $sections = $this->db->get_where('sections', array('class_id' => $param1))->result_array(); ?>
    <?php foreach($sections as $section){
        $count++; ?>
        <?php if($count == 1){ ?>
            <div class="input-group me-2 mb-2">
                    <input type="hidden" class="form-control" id="section" name = "section_id[]" value="<?php echo $section['id']; ?>">
                    <input type="text" class="form-control" id="name" name = "name[]" value="<?php echo $section['name']; ?>" required>

                    <!-- <button class="btn btn-icon btn-danger" type="button" onclick="deleteSection(this)"><i class="mdi mdi-window-close"></i></button> -->
                    <button class="btn btn-icon btn-success" type="button" onclick="appendSection()"><i class="mdi mdi-plus"></i></button>
            </div>
        <?php } ?>

        <?php if($count != 1){ ?>
            <div class="input-group me-2 mb-2" id="sectionDatabase<?php echo $section['id']; ?>">
                    <input type="hidden" class="form-control" id="section<?php echo $section['id']; ?>" name = "section_id[]" value="<?php echo $section['id']; ?>">
                    <input type="text" class="form-control" name = "name[]" value="<?php echo $section['name']; ?>" required>

                    <button class="btn btn-icon btn-danger" type="button" onclick="removeSectionDatabase('<?php echo $section['id']; ?>')"><i class="mdi mdi-window-close"></i></button>
            </div>
        <?php } ?>

    <?php } ?>
    <div id = "section_area"></div>
    <div class="row no-gutters">
    <div class="form-group  col-md-12 p-0 mt-2">
        <button class="btn btn-block btn-primary ms-2" type="submit"><?php echo get_phrase('update'); ?></button>
    </div>
</div>
</form>

<div id = "blank_section">
    <div class="input-group me-2 mb-2">

            <input type="hidden" class="form-control" name = "section_id[]" value="0">
            <input type="text" class="form-control" name = "name[]" value="" required>

            <button class="btn btn-icon btn-danger" type="button" onclick="removeSection(this)"><i class="mdi mdi-window-close"></i></button>
    </div>
</div>


<script>

//update form
 // Jquery form validation initialization
$(".ajaxForm").validate({});
$(".ajaxForm").submit(function(e) {
    var form = $(this);
    ajaxSubmit(e, form, showAllClasses);
});

var blank_section_field = $('#blank_section').html();

$(document).ready(function() {
    $('#blank_section').hide();
});


function appendSection() {
    $('#section_area').append(blank_section_field);
}

function removeSection(elem) {
    $(elem).closest('.input-group').remove();
}

function removeSectionDatabase(section_id) {
    $('#sectionDatabase'+section_id).hide();
    $('#section'+section_id).val(section_id+'delete');
}
</script>
