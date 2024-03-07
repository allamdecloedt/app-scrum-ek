<form method="POST" class="d-block ajaxForm" action="<?php echo route('book/create'); ?>">
    <div class="form-row">
        <div class="form-group mb-1">
            <label for="name"><?php echo get_phrase('book_name'); ?></label>
            <input type="text" class="form-control" id="name" name = "name" required>
        </div>

        <div class="form-group mb-1">
            <label for="author"><?php echo get_phrase('author'); ?></label>
            <input type="text" class="form-control" id="author" name = "author" required>
        </div>

        <div class="form-group mb-1">
            <label for="copies"><?php echo get_phrase('number_of_copy'); ?></label>
            <input type="number" class="form-control" id="copies" name = "copies" min="0" required>
        </div>

        <div class="form-group  col-md-12">
            <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('save_book_info'); ?></button>
        </div>
    </div>
</form>

<script>
    $(".ajaxForm").validate({});
    $(".ajaxForm").submit(function(e) {
        var form = $(this);
        ajaxSubmit(e, form, showAllBooks);
    });
</script>
