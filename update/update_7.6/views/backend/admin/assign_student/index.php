<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body py-2">
                <h4 class="page-title d-inline-block">
                    <span class="mdi mdi-clipboard-account"></span> <?php echo get_phrase('assign_student'); ?>
                </h4>
                <button type="button" class="btn btn-outline-primary btn-rounded alignToTitle float-end mt-1" onclick="rightModal('<?php echo site_url('modal/popup/assign_student/add'); ?>', '<?php echo get_phrase('assign_student'); ?>')"> <i class="mdi mdi-plus"></i>
                    <?php echo get_phrase('assign_student'); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form action="" class="filter_list">
                <div class="row mt-3">
                    <div class="col-md-1 mb-1"></div>
                    <div class="col-md-4 mb-1">
                        <select name="parent_category" id="category_id" class="form-control select2" data-toggle="select2" required onchange="getAdditionalCategory(this.value)">
                            <?php if ($parent_category) : ?>
                                <option value="<?php echo $parent_category; ?>"><?php echo ucfirst(get_phrase($parent_category)); ?></option>
                            <?php endif; ?>
                            <option value=""><?php echo get_phrase('select_a_category'); ?></option>
                            <option value="vehicle"><?php echo get_phrase('vehicle'); ?></option>
                            <option value="class"><?php echo get_phrase('class'); ?></option>
                            <option value="driver"><?php echo get_phrase('driver'); ?></option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-1">
                        <select name="child_category" id="additional_category" class="form-control select2" data-toggle="select2" required>
                            <?php if ($child_category) : ?>
                                <option value="<?php echo $child_category; ?>"><?php echo ucfirst(get_phrase($child_category)); ?></option>
                            <?php endif; ?>
                            <option value=""><?php echo get_phrase('first_select_a_category'); ?></option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-block btn-secondary"><?php echo get_phrase('filter'); ?></button>
                    </div>
                </div>
            </form>

            <div class="card-body assigned_student_content">
                <?php include 'list.php'; ?>
            </div>
        </div>
    </div>
</div>

<style>
    span.mdi {
        font-size: 20px !important;
    }
</style>

<script>
    var showAllAssignedStudents = function() {
        var url = '<?php echo route('assign_student/list'); ?>';

        $.ajax({
            type: 'GET',
            url: url,
            success: function(response) {
                $('.assigned_student_content').html(response);
                initDataTable('basic-datatable');
            }
        });
    }

    function getAdditionalCategory(classId) {
        $.ajax({
            url: "<?php echo route('getAdditionalCategory/'); ?>" + classId,
            success: function(response) {
                $('#additional_category').html(response);
            }
        });
    }

    $(document).ready(function() {
        $('.filter_list').submit(function(e) {
            e.preventDefault();

            let parent_category = $('#category_id').val();
            let child_category = $('#additional_category').val();

            $.ajax({
                type: 'GET',
                url: '<?php echo route('assign_student/filter/') ?>' + parent_category + '/' + child_category,
                success: function(response) {
                    $('.assigned_student_content').html(response);
                    initDataTable('basic-datatable');
                }
            });
        });
    });
</script>