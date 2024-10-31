<!--title-->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">
                <i class="mdi mdi-update title_icon"></i> <?php echo get_phrase('student_update_form'); ?>
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card pt-0">
            <div class="card-body">
                <?php $school_id = school_id(); ?>
                <?php $student = $this->db->get_where('students', array('id' => $student_id))->row_array(); ?>
                <?php 
                $enroll = $this->db->get_where('enrols', array('student_id' => $student_id))->row_array(); 
                $selected_classes = $this->db->get_where('enrols', array('student_id' => $student_id))->result_array();
                $selected_class_ids = array();
                foreach ($selected_classes as $selected_class) {
                    $selected_class_ids[] = $selected_class['class_id'];
                }             


                $selected_sections = $this->db->get_where('enrols', array('student_id' => $student_id))->result_array();
                $selected_section_ids = array();
                foreach ($selected_sections as $section) {
                    if (!isset($selected_section_ids[$section['class_id']])) {
                        $selected_section_ids[$section['class_id']] = array();
                    }
                    $selected_section_ids[$section['class_id']][] = $section['section_id'];
                }

                ?>
                <h4 class="text-center mx-0 py-2 mt-0 mb-3 px-0 text-white bg-primary"><?php echo get_phrase('update_student_information'); ?></h4>
                <form method="POST" class="col-12 d-block ajaxForm" action="<?php echo route('student/updated/'.$student_id.'/'.$student['user_id']); ?>" id = "student_update_form" enctype="multipart/form-data">
                    <!-- Champ caché pour le jeton CSRF -->
                    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />

                    <div class="col-md-12">
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="name"><?php echo get_phrase('name'); ?></label>
                            <div class="col-md-9">
                                <input type="text" id="name" name="name" class="form-control"  value="<?php echo $this->user_model->get_user_details($student['user_id'], 'name'); ?>" placeholder="name" required>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="email"><?php echo get_phrase('email'); ?></label>
                            <div class="col-md-9">
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $this->user_model->get_user_details($student['user_id'], 'email'); ?>" placeholder="email" required>
                            </div>
                        </div>



                        <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label" for="class_id"><?php echo get_phrase('class'); ?></label>
                                <div class="col-md-9">
                                    <select name="class_id[]" id="class_id" class=" form-control"  onchange="classWiseSectionOnStudentEdit(this.value)" multiple="multiple" required data-live-search="true">
                                        <option value=""><?php echo get_phrase('select_classes'); ?></option>
                                        <?php                                        
                                        $classes = $this->db->get_where('classes', array('school_id' => $school_id))->result_array(); 
                                        foreach($classes as $class){ ?>
                                            <option value="<?php echo $class['id']; ?>" data-class-name="<?php echo $class['name']; ?>"  <?php if (in_array($class['id'], $selected_class_ids)) echo 'selected'; ?>><?php echo $class['name']; ?></option>
                                        <?php } ?>


                                        
                                    </select>
                                </div>
                        </div>

                        <div class="form-group row mb-3">
                            <div id="section_selects_container">
                                <?php foreach($selected_class_ids as $class_id) {
                                    $class_name = $this->db->get_where('classes', array('id' => $class_id))->row()->name;

                                    ?>
                                    <div class="form-group row mb-3 section-select " id="section_select_<?php echo $class_id; ?>">
                                        <label class="col-md-3 col-form-label"><?php echo get_phrase('section_for_class') . ' ' . $class_name; ?></label>
                                        <div class="col-md-9">
                                            <select name="section_id_<?php echo $class_id; ?>"  id="section_id_<?php echo $class_id; ?>" class=" form-control" >
                                                <option value=""><?php echo get_phrase('select_a_section'); ?></option>
                                                <?php 
                                                $sections = $this->db->get_where('sections', array('class_id' => $class_id))->result_array(); 
                                                foreach($sections as $section) { ?>
                                                    <option value="<?php echo $section['id']; ?>" <?php if (isset($selected_section_ids[$class_id]) && in_array($section['id'], $selected_section_ids[$class_id])) echo 'selected'; ?>>
                                                        <?php echo $section['name']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="birthdatepicker"><?php echo get_phrase('birthday'); ?></label>
                            <div class="col-md-9">
                                <div class=" position-relative" id="datepicker4">
                                  <!-- <input type="text" class="form-control" data-provide="datepicker"  placeholder="<?php echo get_phrase('birthday'); ?>" data-date-autoclose="true" data-date-container="#datepicker4" name = "birthday"   value="<?php //if($this->user_model->get_user_details($student['user_id'], 'birthday') != "") echo date('m/d/Y', strtotime($this->user_model->get_user_details($student['user_id'], 'birthday'))); ?>" > -->
                                  <?php 
                                    $birthday = $this->user_model->get_user_details($student['user_id'], 'birthday');
                                    $formattedBirthday = $birthday ? date('m/d/Y', strtotime($birthday)) : '';
                                ?>
                                <input type="text" class="form-control date" id="birthdatepicker" data-bs-toggle="date-picker" data-single-date-picker="true" name="birthday" value="<?php echo $formattedBirthday; ?>" required>

                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="gender"><?php echo get_phrase('gender'); ?></label>
                            <div class="col-md-9">
                                <select name="gender" id="gender" class="form-control" required>
                                    <option value=""><?php echo get_phrase('select_gender'); ?></option>
                                    <option value="Male" <?php if($this->user_model->get_user_details($student['user_id'], 'gender') == 'Male') echo 'selected'; ?>><?php echo get_phrase('male'); ?></option>
                                    <option value="Female" <?php if($this->user_model->get_user_details($student['user_id'], 'gender') == 'Female') echo 'selected'; ?>><?php echo get_phrase('female'); ?></option>
                                    <option value="Others" <?php if($this->user_model->get_user_details($student['user_id'], 'gender') == 'Others') echo 'selected'; ?>><?php echo get_phrase('others'); ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="example-textarea"><?php echo get_phrase('address'); ?></label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="example-textarea" rows="5" name = "address" placeholder="address"><?php echo $this->user_model->get_user_details($student['user_id'], 'address'); ?></textarea>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="phone"><?php echo get_phrase('phone'); ?></label>
                            <div class="col-md-9">
                                <input type="text" id="phone" name="phone" class="form-control" value="<?php echo $this->user_model->get_user_details($student['user_id'], 'phone'); ?>" placeholder="phone" required>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="example-fileinput"><?php echo get_phrase('student_profile_image'); ?></label>
                            <div class="col-md-9 custom-file-upload">
                                <div class="wrapper-image-preview" style="margin-left: -6px;">
                                    <div class="box" style="width: 250px;">
                                        <div class="js--image-preview" style="background-image: url(<?php echo $this->user_model->get_user_image($student['user_id']); ?>); background-color: #F5F5F5;"></div>
                                        <div class="upload-options">
                                            <label for="student_image" class="btn"> <i class="mdi mdi-camera"></i> <?php echo get_phrase('upload_an_image'); ?> </label>
                                            <input id="student_image" style="visibility:hidden;" type="file" class="image-upload" name="student_image" accept="image/*">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-secondary col-md-4 col-sm-12 mb-4"><?php echo get_phrase('update_student_information'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
var form;
$(".ajaxForm").submit(function(e) {
    form = $(this);
    ajaxSubmit(e, form, refreshForm);
});
var refreshForm = function () {

}


function classWiseSectionOnStudentEdit() {
            var classIds = $('#class_id').val(); // Récupère les IDs des classes sélectionnées
            // alert(classIds);
            var sectionContainer = $('#section_selects_container');
             sectionContainer.empty(); // Vider le conteneur des selects de section

            if (classIds.length > 0) {
                classIds.forEach(function(classId) {
                    var className = $('#class_id option[value="' + classId + '"]').data('class-name'); // Récupère le nom de la classe

                    $.ajax({
                        url: "<?php echo site_url('superadmin/get_sections_by_class'); ?>",
                        type: 'POST',
                        data: {class_ids: [classId]}, // Passer un tableau contenant un seul ID de classe
                        success: function(response) {

                            var sections = JSON.parse(response);
                            var selectedSections = <?php echo json_encode($selected_section_ids); ?>;
                            sectionOptions = '<option value=""><?php echo get_phrase('select_a_section'); ?></option>';
                      
                             if (sections.length > 0) {
                                sections.forEach(function(section) {
                                    var selected = (selectedSections[classId] && selectedSections[classId].includes(section.id)) ? 'selected' : '';
                                    sectionOptions += '<option value="' + section.id + '" ' + selected + ' >' + section.name + '</option>';
                                });
                            } else {
                                sectionOptions += '<option value="" disabled><?php echo get_phrase('no_section_found'); ?></option>';
                            }
                          
                            
                           

                            var sectionSelect = `
                                <div class="form-group row mb-3 section-select" id="${classId}">
                                    <label class="col-md-3 col-form-label"><?php echo get_phrase('section_for_class'); ?> ${className}</label>
                                    <div class="col-md-9">
                                        <select name="section_id_${classId}" id="section_id_${classId}" class=" form-control" >
                                           ${sectionOptions}
                                        </select>
                                    </div>
                                </div>
                            `;
                         
                            sectionContainer.append(sectionSelect);

                        },
                        error: function(xhr, status, error) {
                            console.error("Erreur pour la classe ", classId, ": ", error);
                        }
                    });
                });
            }
}
</script>

