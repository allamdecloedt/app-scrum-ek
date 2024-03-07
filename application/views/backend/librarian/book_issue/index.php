<!-- start page title -->
<div class="row ">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body py-2">
        <h4 class="page-title d-inline-block">
          <i class="mdi mdi-library title_icon"></i> <?php echo get_phrase('books_issue'); ?>
        </h4>
        <button type="button" class="btn btn-outline-primary btn-rounded alignToTitle float-end mt-1" onclick="rightModal('<?php echo site_url('modal/popup/book_issue/create'); ?>', '<?php echo get_phrase('issue_book'); ?>')"> <i class="mdi mdi-plus"></i> <?php echo get_phrase('issue_book'); ?></button>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>
<!-- end page title -->

<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mt-3"><?php echo get_phrase('issues_book_list'); ?></h4>
                <div class="row justify-content-md-center" style="margin-bottom: 10px;">
                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mb-3 mb-lg-0">
                        <div class="form-group">
                            <div id="reportrange" class="form-control" data-toggle="date-picker-range" data-target-display="#selectedValue"  data-cancel-class="btn-light">
                                <i class="mdi mdi-calendar"></i>&nbsp;
                                <span id="selectedValue"> <?php echo date('F d, Y', strtotime(' -30 day')).' - '.date('F d, Y'); ?> </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 mb-3 mb-lg-0">
                        <button type="button" class="btn btn-icon btn-secondary form-control" onclick="showAllBookIssues()"><?php echo get_phrase('filter'); ?></button>
                    </div>
                </div>

                <div class="table-responsive-sm book_issue_content">
                    <?php include 'list.php'; ?>
                </div> <!-- end table-responsive-->
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<script>
var showAllBookIssues = function () {
    var url = '<?php echo route('book_issue/list'); ?>';
    $.ajax({
        type : 'GET',
        url: url,
        data : {date : $('#selectedValue').text()},
        success : function(response) {
            $('.book_issue_content').html(response);
            initDataTable("basic-datatable");
        }
    });
}
</script>
