<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title"> <i class="mdi mdi-library title_icon"></i> <?php echo get_phrase('books_issue'); ?></h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mt-3"><?php echo get_phrase('issues_book_list'); ?></h4>
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
