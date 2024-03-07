<?php $school_id = school_id(); ?>
<form method="POST" class="col-md-12 ajaxForm" action="<?php echo route('student/create_excel'); ?>" id = "student_admission_form" enctype="multipart/form-data">
    <div class="row justify-content-md-center">
        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mb-3 mb-lg-0">
            <select name="class_id" id="class_id" class="form-control select2" data-toggle = "select2" onchange="classWiseSection(this.value)" required>
                <option value=""><?php echo get_phrase('select_a_class'); ?></option>
                <?php $classes = $this->db->get_where('classes', array('school_id' => $school_id))->result_array(); ?>
                <?php foreach($classes as $class){ ?>
                    <option value="<?php echo $class['id']; ?>"><?php echo $class['name']; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mb-3 mb-lg-0" id = "section_content">
            <select name="section_id" id="section_id" class="form-control select2" data-toggle = "select2" required >
                <option value=""><?php echo get_phrase('select_section'); ?></option>
            </select>
        </div>
        <div class="col-md-8 mt-4">
            <div class="row">
                <div class="col-12">
                    <a href="<?php echo base_url('assets/csv_file/student.generate.csv'); ?>" class="btn btn-success btn-sm mb-1" download><?php echo get_phrase('generate_csv_file'); ?><i class="mdi mdi-download"></i></a>
                    <button href="#" class="btn btn-dark btn-sm mb-1 mdi mdi-eye-outline" onclick="largeModal('<?php echo site_url('modal/popup/student/csv_preview'); ?>', 'CSV Format');" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="<?php echo get_phrase('preview_csv_format'); ?>"></button>

                </div>
            </div>
            <br>
            <div class="form-group">
                <label class="m-0"><?php echo get_phrase('upload').'CSV'; ?></label>
                <div class="custom-file-upload d-inline-block">
                    <input type="file" id="csv_file" class="form-control" name="csv_file" required>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-secondary col-md-4 col-sm-12 mb-4 mt-3"><?php echo get_phrase('add_students'); ?></button>
    </div>
</form>

<script>
$(document).ready(function(){
    initCustomFileUploader();
});

var form;
$(".ajaxForm").submit(function(e) {
  form = $(this);
  ajaxSubmit(e, form, refreshForm);
});
var refreshForm = function () {
    form.trigger("reset");
}
</script>
