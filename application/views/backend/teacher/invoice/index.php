<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title"> <i class="mdi mdi-file-document-box title_icon"></i> <?php echo get_phrase('student_fee_manager'); ?>
                <button type="button" class="btn btn-icon btn-success btn-rounded alignToTitle" onclick="rightModal('<?php echo site_url('modal/popup/invoice/single'); ?>', '<?php echo get_phrase('add_single_invoice'); ?>')"> <i class="mdi mdi-plus"></i> <?php echo get_phrase('add_single_invoice'); ?></button>
                <button type="button" class="btn btn-icon btn-primary btn-rounded alignToTitle" style="margin-right: 10px;" onclick="rightModal('<?php echo site_url('modal/popup/invoice/mass'); ?>', '<?php echo get_phrase('add_mass_invoice'); ?>')"> <i class="mdi mdi-plus"></i> <?php echo get_phrase('add_mass_invoice'); ?></button>
            </h4>
        </div>
    </div>
</div>
<!-- end page title -->
<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mt-3"><?php echo get_phrase('student_fee_report'); ?></h4>

                <div class="row justify-content-md-center" style="margin-bottom: 10px;">
                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mb-3 mb-lg-0">
                        <div class="form-group">
                            <div id="reportrange" class="form-control" data-toggle="date-picker-range" data-target-display="#selectedValue"  data-cancel-class="btn-light">
                                <i class="mdi mdi-calendar"></i>&nbsp;
                                <span id="selectedValue"> <?php echo date('F d, Y', strtotime(' -30 day')).' - '.date('F d, Y'); ?> </span> <i class="mdi mdi-menu-down"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 mb-3 mb-lg-0">
                        <button type="button" class="btn btn-icon btn-secondary form-control" onclick="showAllInvoices()"><?php echo get_phrase('filter'); ?></button>
                    </div>
                </div>

                <div class="table-responsive-sm">
                    <div class="invoice_content">
                        <?php include 'list.php'; ?>
                    </div>
                </div> <!-- end table-responsive-->
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<script>
var showAllInvoices = function () {
    var url = '<?php echo route('invoice/list'); ?>';
    $.ajax({
        type : 'GET',
        url: url,
        data : {date : $('#selectedValue').text()},
        success : function(response) {
            $('.invoice_content').html(response);
            initDataTable("basic-datatable");
        }
    });
}
</script>
