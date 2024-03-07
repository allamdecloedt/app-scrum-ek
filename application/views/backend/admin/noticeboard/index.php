<!--title-->
<div class="row ">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body py-2">
        <h4 class="page-title d-inline-block">
          <i class="mdi mdi-calendar-clock title_icon"></i> <?php echo get_phrase('noticeboard_calendar'); ?>
        </h4>
        <button type="button" class="btn btn-outline-primary btn-rounded alignToTitle float-end mt-1" onclick="rightModal('<?php echo site_url('modal/popup/noticeboard/create'); ?>', '<?php echo get_phrase('add_new_notice'); ?>')"> <i class="mdi mdi-plus"></i> <?php echo get_phrase('add_new_notice'); ?></button>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>

<div class="row">
  <div class="col-12 noticeboard_content">
    <?php include 'list.php'; ?>
  </div>
</div>

<script>
$(document).ready(function() {
  refreshNoticeCalendar();
});

var showAllNotices = function () {
  var url = '<?php echo route('noticeboard/list'); ?>';

  $.ajax({
    type : 'GET',
    url: url,
    success : function(response) {
      $('.noticeboard_content').html(response);
      refreshNoticeCalendar();
    }
  });
}

var refreshNoticeCalendar = function () {
  var url = '<?php echo route('noticeboard/all_notices'); ?>';
  $.ajax({
    type : 'GET',
    url: url,
    dataType: 'json',
    success : function(response) {

      var notice_calendar = [];
      for(let i = 0; i < response.length; i++) {

        var obj;
        obj  = {"id": response[i].id, "title" : response[i].notice_title, "start" : response[i].date, "end" : response[i].date};
        notice_calendar.push(obj);
      }

      $('#calendar').fullCalendar({
        disableDragging: true,
        events: notice_calendar,
        displayEventTime: false,
        eventClick : function(info) {
          rightModal('<?php echo site_url('modal/popup/noticeboard/edit/'); ?>'+info.id, '<?php echo get_phrase('edit_notice'); ?>')
        },
        dayClick: function(date) {
            rightModal('<?php echo site_url('modal/popup/noticeboard/create/'); ?>'+date.format(), '<?php echo get_phrase('add_new_notice'); ?>')
        }
      });
    }
  });
}
</script>
