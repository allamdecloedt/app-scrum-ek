<!-- start page title -->
<div class="row ">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body py-2">
        <h4 class="page-title d-inline-block">
          <i class="mdi mdi-database title_icon"></i> <?php echo get_phrase('expense'); ?>
        </h4>
        <button type="button" class="btn btn-outline-primary btn-rounded alignToTitle float-end mt-1" onclick="rightModal('<?php echo site_url('modal/popup/expense/create'); ?>', '<?php echo get_phrase('add_new_expense'); ?>')"> <i class="mdi mdi-plus"></i> <?php echo get_phrase('add_new_expense'); ?></button>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>
    <!-- end page title -->

    <div class="row ">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-md-center" style="margin-bottom: 10px;">
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mb-3 mb-lg-0">
                            <div class="form-group">
                                <div id="reportrange" class="form-control" data-toggle="date-picker-range" data-target-display="#selectedValue"  data-cancel-class="btn-light">
                                    <i class="mdi mdi-calendar"></i>&nbsp;
                                    <span id="selectedValue"> <?php echo date('F d, Y', strtotime(' -30 day')).' - '.date('F d, Y'); ?> </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mb-3 mb-lg-0">
                            <select class="form-control select2" data-toggle = "select2" name="expense_category_id" id="expense_category_id">
                                <option value="all"><?php echo get_phrase('expense_category'); ?></option>
                                <?php
                                $expense_categories = $this->crud_model->get_expense_categories()->result_array();
                                foreach ($expense_categories as $expense_category): ?>
                                <option value="<?php echo $expense_category['id']; ?>"><?php echo $expense_category['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 mb-3 mb-lg-0">
                        <button type="button" class="btn btn-icon btn-secondary form-control" onclick="showAllExpenses()"><?php echo get_phrase('filter'); ?></button>
                    </div>
                </div>
                <div class="expense_content">
                    <?php include 'list.php'; ?>
                </div> <!-- end table-responsive-->
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>


<script>
$(document).ready(function() {
    $('select.select2:not(.normal)').each(function () { $(this).select2({ dropdownParent: '#right-modal' }); }); //initSelect2(['#expense_category_id']);
});
var showAllExpenses = function () {
    var url = '<?php echo route('expense/list'); ?>';
    $.ajax({
        type : 'GET',
        url: url,
        data : {date : $('#selectedValue').text(), expense_category_id : $('#expense_category_id').val()},
        success : function(response) {
            $('.expense_content').html(response);
            initDataTable("basic-datatable");
        }
    });
}
</script>
