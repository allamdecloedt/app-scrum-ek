<!-- Modal -->
<?php if ($course['course_overview_url'] != ""):
    $provider = "";
    $video_details = array();
    if ($course['course_overview_provider'] == "html5") {
        $provider = 'html5';
    }else {
        $video_details = $this->video_model->getVideoDetails($course['course_overview_url']);
        $provider = $video_details['provider'];
    } ?>

  <div class="course-preview-video-wrap">
      <div class="embed-responsive embed-responsive-16by9">
        <?php if (strtolower(strtolower($provider)) == 'youtube'): ?>
          <!------------- PLYR.IO ------------>
          <link rel="stylesheet" href="<?php echo base_url();?>assets/global/plyr/plyr.css">

          <div class="plyr__video-embed" id="player">
            <iframe height="500" src="<?php echo $course['course_overview_url'];?>?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1" allowfullscreen allowtransparency allow="autoplay"></iframe>
          </div>

          <script src="<?php echo base_url();?>assets/global/plyr/plyr.js"></script>
          <script>const player = new Plyr('#player');</script>
          <!------------- PLYR.IO ------------>
        <?php elseif (strtolower($provider) == 'vimeo'): ?>
          <!------------- PLYR.IO ------------>
          <link rel="stylesheet" href="<?php echo base_url();?>assets/global/plyr/plyr.css">
          <div class="plyr__video-embed" id="player">
            <iframe height="500" src="https://player.vimeo.com/video/<?php echo $video_details['video_id']; ?>?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media" allowfullscreen allowtransparency allow="autoplay"></iframe>
          </div>

          <script src="<?php echo base_url();?>assets/global/plyr/plyr.js"></script>
          <script>const player = new Plyr('#player');</script>
          <!------------- PLYR.IO ------------>
        <?php else :?>
          <!------------- PLYR.IO ------------>
          <link rel="stylesheet" href="<?php echo base_url();?>assets/global/plyr/plyr.css">
          <video poster="<?php echo $course_thumbnail;?>" id="player" playsinline controls>
        <?php if (get_video_extension($course['course_overview_url']) == 'mp4'): ?>
          <source src="<?php echo $course['course_overview_url']; ?>" type="video/mp4">
          <?php elseif (get_video_extension($course['course_overview_url']) == 'webm'): ?>
            <source src="<?php echo $course['course_overview_url']; ?>" type="video/webm">
            <?php else: ?>
              <h4><?php get_phrase('video_url_is_not_supported'); ?></h4>
            <?php endif; ?>
          </video>

          <style media="screen">
          .plyr__video-wrapper {
            height: 450px;
          }
          </style>

          <script src="<?php echo base_url();?>assets/global/plyr/plyr.js"></script>
          <script>const player = new Plyr('#player');</script>
          <!------------- PLYR.IO ------------>
        <?php endif; ?>
      </div>
  </div>
                
<?php endif; ?>
<!-- Modal -->

<style media="screen">
  .embed-responsive-16by9::before {
    padding-top : 0px;
  }
</style>