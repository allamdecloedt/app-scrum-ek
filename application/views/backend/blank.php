//title-->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">
            	<i class="mdi mdi-translate title_icon"></i> language_manager
            	<button type="button" class="btn btn-icon btn-success btn-rounded mb-1 mt-3 alignToTitle float-end" onclick="showAjaxModal('http://localhost:8888/laravel/ekattor-school-manager-laravel/language/create', 'Create new language')"> <i class="mdi mdi-plus"></i> Add language</button>
        	</h4>
        </div>
    </div>
</div>

//empty box-->
<div class="empty_box text-center">
    <img class="mb-3" width="150px" src="<?php echo base_url('assets/backend/images/empty_box.png'); ?>" />
    <br>
    <span class=""><?php echo get_phrase('no_data_found'); ?></span>
</div>

<!--Delete Form-->
<form class="ajaxForm" method="post" action="<?php echo route('manage_class/delete/'.$param1); ?>">
    <div class="form-group  col-md-12">
        <button type="button" class="btn btn-warning my-2 me-1" data-bs-dismiss="modal"><?php echo get_phrase('cancel'); ?></button>
        <button type="submit" class="btn btn-danger my-2 ms-1"><?php echo get_phrase('continue'); ?></button>
     </div>
</form>
<script>
    $(".ajaxForm").validate({}); // Jquery form validation initialization
    $(".ajaxForm").submit(function(e) {
        var form = $(this);
        ajaxSubmit(e, form, 'class_list', '<?php echo get_phrase('class_deleted_successfully'); ?>');
    });
</script>

<!--Button of Table-->
<td>
    <button type="button" class="btn btn-icon btn-secondary btn-sm btn-dark" style="margin-right:5px;" onclick="rightModal('<?php echo site_url('modal/popup/edit_phrase')?>', '<?php echo get_phrase('edit_phrase'); ?>');" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="<?php echo get_phrase('manage_sections'); ?>"> <i class="dripicons-checklist"></i></button>

    <button type="button" class="btn btn-icon btn-secondary btn-sm btn-dark" style="margin-right:5px;" onclick="rightModal('<?php echo site_url('modal/popup/class/update_class/'.$class['id'])?>', '<?php echo get_phrase('update_class'); ?>');" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="<?php echo get_phrase('update_class_info'); ?>"> <i class="mdi mdi-wrench"></i></button>

    <button type="button" class="btn btn-icon btn-secondary btn-sm" style="margin-right:5px;" onclick="confirmModal('<?php echo site_url('modal/popup/class/delete_class/'.$class['id']); ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="<?php echo get_phrase('delete_class'); ?>"> <i class="mdi mdi-window-close"></i></button>
</td>

<!--Table-->
<div class="row justify-content-md-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%">
                    <thead>
                        <tr style="background-color: #313a46; color: #ababab;">
                            <th><?php echo get_phrase('name'); ?></th>
                            <th><?php echo get_phrase('code'); ?></th>
                            <th><?php echo get_phrase('option'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Tiger Nixon</td>
                            <td>System Architect</td>
                            <td>
                            	<button type="button" class="btn btn-icon btn-secondary btn-sm btn-dark" style="margin-right:5px;" onclick="rightModal('<?php echo site_url('modal/popup/edit_phrase')?>', '<?php echo get_phrase('edit_phrase'); ?>');" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="update_phrases"> <i class="mdi mdi-update"></i></button>

                            	<button type="button" class="btn btn-icon btn-secondary btn-sm btn-dark" style="margin-right:5px;" onclick="rightModal();" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="update_language_info"> <i class="mdi mdi-wrench"></i></button>

                            	<button type="button" class="btn btn-icon btn-secondary btn-sm" style="margin-right:5px;" onclick="confirmModal();" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-original-title="delete_language"> <i class="mdi mdi-window-close"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div><!-- end row -->
