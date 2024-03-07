<!--title-->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">
            	<i class="mdi mdi-account-circle title_icon"></i> <?php echo get_phrase('parents'); ?>
            	<button type="button" class="btn btn-icon btn-success btn-rounded mb-1 mt-3 alignToTitle float-end" onclick="rightModal('<?php echo site_url('modal/popup/parent/create'); ?>', '<?php echo get_phrase('create_parent'); ?>')"> <i class="mdi mdi-plus"></i> <?php echo get_phrase('add_parent'); ?></button>
        	</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body parent_content">
                <?php include 'list.php'; ?>
            </div>
        </div>
    </div>
</div>


<script>
    var showAllParents = function () {
        var url = '<?php echo route('parent/list'); ?>';

        $.ajax({
            type : 'GET',
            url: url,
            success : function(response) {
                $('.parent_content').html(response);
                initDataTable('basic-datatable');
            }
        });
    }
</script>
