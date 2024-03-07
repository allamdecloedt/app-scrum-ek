<!-- start page title -->
<div class="row ">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body py-2">
        <h4 class="page-title"> <i class="mdi mdi-file-document-box title_icon"></i> <?php echo get_phrase('student_fee_manager'); ?></h4>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>
<!-- end page title -->
<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mt-3"><?php echo get_phrase('student_fee_report'); ?></h4>
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
