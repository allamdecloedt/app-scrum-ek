<?php
$notice = $this->db->get_where('noticeboard', array('id' => $param1))->row_array();
?>
<div class="notice-action-portion">
  <div class="row">
    <div class="form-group  col-md-6">
      <button class="btn btn-block btn-primary" type="submit" onclick="showNoticeEditPortion()"><?php echo get_phrase('edit_notice'); ?></button>
    </div>
    <div class="form-group  col-md-6">
      <a href="javascript:void(0)" class="btn btn-block btn-danger" onclick="showNoticeDeleteModal()"><?php echo get_phrase('delete_this_notice'); ?></a>
    </div>
  </div>
</div>
<div class="notice-edit-portion hidden">
  <form method="POST" class="d-block ajaxForm" action="<?php echo route('noticeboard/update/'.$param1); ?>">
    <div class="form-row">

      <div class="form-group mb-1">
        <label for="notice_title"><?php echo get_phrase('notice_title'); ?></label>
        <input type="text" class="form-control" id="notice_title" name = "notice_title" value="<?php echo $notice['notice_title']; ?>" required>
        <small id="name_help" class="form-text text-muted"><?php echo get_phrase('provide_title_name'); ?></small>
      </div>
      <div class="form-group mb-1">
        <label for="date"><?php echo get_phrase('date'); ?></label>
        <input type="text" value="<?php echo date('m/d/Y', strtotime($notice['date'])); ?>" class="form-control" id="date" name = "date" data-provide = "datepicker" required>
        <small id="name_help" class="form-text text-muted"><?php echo get_phrase('provide_date'); ?></small>
      </div>

      <div class="form-group mb-1">
        <label for="notice"><?php echo get_phrase('notice'); ?></label>
        <textarea name="notice" class="form-control" rows="8" cols="80" required><?php echo $notice['notice']; ?></textarea>
        <small id="name_help" class="form-text text-muted"><?php echo get_phrase('provide_notice_details'); ?></small>
      </div>

      <div class="form-group mb-1">
        <label for="show_on_website"><?php echo get_phrase('show_on_website'); ?></label>
        <select name="show_on_website" id="show_on_website" class="form-control select2" data-toggle = "select2">
          <option value="1" <?php if($notice['show_on_website'] == 1) echo 'selected'; ?>><?php echo get_phrase('show'); ?></option>
          <option value="0" <?php if($notice['show_on_website'] == 0) echo 'selected'; ?>><?php echo get_phrase('do_not_need_to_show'); ?></option>
        </select>
        <small id="" class="form-text text-muted"><?php echo get_phrase('notice_status'); ?></small>
      </div>

      <div class="form-group mb-1">
        <label for="notice_photo"><?php echo get_phrase('upload_notice_photo'); ?></label>
        <div class="custom-file-upload d-inline-block">
          <input type="file" class="form-control" id="notice_photo" name = "notice_photo">
        </div>
      </div>

      <div class="form-group  col-md-12">
        <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('save_notice'); ?></button>
      </div>
    </div>
  </form>
</div>


<script>
$(document).ready(function() {

});
$(".ajaxForm").validate({}); // Jquery form validation initialization
$(".ajaxForm").submit(function(e) {
  var form = $(this);
  ajaxSubmit(e, form, showAllNotices);
});

$('select.select2:not(.normal)').each(function () { $(this).select2({ dropdownParent: '#right-modal' }); }); //initSelect2(['#status', '#show_on_website']);
initCustomFileUploader();

function showNoticeEditPortion() {
  $('.notice-edit-portion').show();
  $('.notice-action-portion').hide();
}

function showNoticeDeleteModal() {
  $('#right-modal').modal('hide');
  confirmModal('<?php echo route('noticeboard/delete/'.$param1); ?>', showAllNotices);
}
</script>
