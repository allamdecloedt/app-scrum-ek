<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title"> <i class="mdi mdi-account-switch title_icon"></i><?php echo get_phrase('student_promotion'); ?>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row ">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">

                    <div class="row justify-content-md-center d-print-none" style="margin-bottom: 10px;">
                        <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 mb-3 mb-lg-0">
                            <label for="session_from"><?php echo get_phrase('current_session'); ?></label>
                            <select class="form-control select2" data-toggle = "select2" id = "session_from" name="session_from">
                                <option value=""><?php echo get_phrase('session_from'); ?></option>
                                <?php
                                $sessions = $this->crud_model->get_session()->result_array();
                                foreach ($sessions as $session): ?>
                                <option value="<?php echo $session['id']; ?>"><?php echo $session['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 mb-3 mb-lg-0">
                        <label for="session_to"><?php echo get_phrase('next_session'); ?></label>
                        <select class="form-control select2" data-toggle = "select2" id = "session_to" name="session_to">
                            <option value=""><?php echo get_phrase('session_to'); ?></option>
                            <?php
                            $sessions = $this->crud_model->get_session()->result_array();
                            foreach ($sessions as $session): ?>
                            <option value="<?php echo $session['id']; ?>"><?php echo $session['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 mb-3 mb-lg-0">
                    <label for="class_id_from"><?php echo get_phrase('promoting_from'); ?></label>
                    <select name="class_id_from select2" data-toggle = "select2" id="class_id_from" class="form-control" required>
                        <option value=""><?php echo get_phrase('promoting_from'); ?></option>
                        <?php
                        $classes = $this->crud_model->get_classes()->result_array();
                        foreach ($classes as $class): ?>
                        <option value="<?php echo $class['id']; ?>"><?php echo $class['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 mb-3 mb-lg-0">
                <label for="class_id_to"><?php echo get_phrase('promoting_to'); ?></label>
                <select name="class_id_to" class="form-control select2" data-toggle = "select2" id="class_id_to" required>
                    <option value=""><?php echo get_phrase('promoting_to'); ?></option>
                    <?php
                    $classes = $this->crud_model->get_classes()->result_array();
                    foreach ($classes as $class): ?>
                    <option value="<?php echo $class['id']; ?>"><?php echo $class['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 mb-3 mb-lg-0">
            <label for="manage_student" style="color: white;"><?php echo get_phrase('manage_button') ?></label>
            <button type="button" class="btn btn-icon btn-secondary form-control" id = "manage_student" onclick="manageStudent()"><?php echo get_phrase('manage_promotion'); ?></button>
        </div>
    </div>

    <div class="table-responsive-sm student_to_promote_content">
        <?php include 'list.php'; ?>
    </div>
</div> <!-- end card body-->
</div> <!-- end card -->
</div><!-- end col-->
</div>

<script type="text/javascript">
$('document').ready(function(){
    $('select.select2:not(.normal)').each(function () { $(this).select2({ dropdownParent: '#right-modal' }); }); //initSelect2(['#session_from', '#session_to', '#class_id_from', '#class_id_to']);
});

function manageStudent() {
    var session_from   = $('#session_from').val();
    var session_to     = $('#session_to').val();
    var class_id_from  = $('#class_id_from').val();
    var class_id_to    = $('#class_id_to').val();
    if(session_from > 0 && session_to > 0 && class_id_from > 0 && class_id_to > 0 ) {
        var url = '<?php echo route('promotion/list'); ?>';
        $.ajax({
            type : 'POST',
            url: url,
            data : { session_from : session_from, session_to : session_to, class_id_from : class_id_from, class_id_to : class_id_to, _token : '{{ @csrf_token() }}' },
            success : function(response) {
                $('.student_to_promote_content').html(response);
            }
        });
    }else {
        toastr.error('<?php echo get_phrase('please_make_sure_to_fill_all_the_necessary_fields'); ?>');
    }
}

function enrollStudent(promotion_data, enroll_id) {
    $.ajax({
        type : 'get',
        url: '<?php echo route('promotion/promote/'); ?>'+promotion_data,
        success : function(response) {
            if (response) {
                $("#success_"+enroll_id).show();
                $("#danger_"+enroll_id).hide();
                success_notify('<?php echo get_phrase('student_promoted_successfully'); ?>');
            }else{
                toastr.error('<?php echo get_phrase('an_error_occured'); ?>');
            }
        }
    });
}
</script>
