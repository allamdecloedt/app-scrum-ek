<div class="col-lg-7  order-md-1 course_col" id = "video_player_area">
    <div class="text-center">
        <?php
        $lesson_details = $this->lms_model->get_lessons('lesson', $lesson_id)->row_array();
        $lesson_thumbnail_url = 'uploads/course_thumbnail/'.$this->lms_model->get_course_by_id($course_id)['thumbnail'];
        if (file_exists($lesson_thumbnail_url)){
            $lesson_thumbnail_url = base_url().$lesson_thumbnail_url;
        } else {
            $lesson_thumbnail_url = base_url().'uploads/course_thumbnail/placeholder.png';
        }
        $provider = $lesson_details['video_type'];
        $opened_section_id = $lesson_details['section_id'];
        // If the lesson type is video
        // i am checking the null and empty values because of the existing users does not have video in all video lesson as type
        if($lesson_details['lesson_type'] == 'video' || $lesson_details['lesson_type'] == '' || $lesson_details['lesson_type'] == NULL):
            $video_url = $lesson_details['video_url'];
            $provider = $lesson_details['video_type'];
            ?>

            <!-- If the video is youtube video -->
            <?php if (strtolower($provider) == 'youtube'): ?>
                <!------------- PLYR.IO ------------>
                <link rel="stylesheet" href="<?php echo base_url();?>assets/global/plyr/plyr.css">

                <div class="plyr__video-embed" id="player">
                    <iframe height="500" src="<?php echo $video_url;?>?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1" allowfullscreen allowtransparency allow="autoplay"></iframe>
                </div>

                <script src="<?php echo base_url();?>assets/global/plyr/plyr.js"></script>
                <script>const player = new Plyr('#player');</script>
                <!------------- PLYR.IO ------------>

                <!-- If the video is vimeo video -->
            <?php elseif (strtolower($provider) == 'vimeo'):
                $video_details = $this->video_model->getVideoDetails($video_url);
                $video_id = $video_details['video_id'];?>
                <!------------- PLYR.IO ------------>
                <link rel="stylesheet" href="<?php echo base_url();?>assets/global/plyr/plyr.css">
                <div class="plyr__video-embed" id="player">
                    <iframe height="500" src="https://player.vimeo.com/video/<?php echo $video_id; ?>?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media" allowfullscreen allowtransparency allow="autoplay"></iframe>
                </div>

                <script src="<?php echo base_url();?>assets/global/plyr/plyr.js"></script>
                <script>const player = new Plyr('#player');</script>
                <!------------- PLYR.IO ------------>
                <?php elseif (strtolower($provider) == 'mydevice'):; ?>
                   <link rel="stylesheet" href="<?php echo base_url();?>assets/global/plyr/plyr.css">
                   <video  poster="<?php echo $lesson_thumbnail_url;?>" width="900" height="800"  playsinline controls>
                    <source src="<?php echo base_url('uploads/videos/'.$lesson_details['video_uplaod']); ?>" type="video/mp4">
                </video>
            <?php else :?>
                <!------------- PLYR.IO ------------>
                <link rel="stylesheet" href="<?php echo base_url();?>assets/global/plyr/plyr.css">
                <video poster="<?php echo $lesson_thumbnail_url;?>" id="player" playsinline controls>
                    <?php if (get_video_extension($video_url) == 'mp4'): ?>
                        <source src="<?php echo $video_url; ?>" type="video/mp4">
                    <?php elseif (get_video_extension($video_url) == 'webm'): ?>
                        <source src="<?php echo $video_url; ?>" type="video/webm">
                    <?php else: ?>
                        <h4><?php get_phrase('video_url_is_not_supported'); ?></h4>
                    <?php endif; ?>
                </video>

                <script src="<?php echo base_url();?>assets/global/plyr/plyr.js"></script>
                <script>const player = new Plyr('#player');</script>
                <!------------- PLYR.IO ------------>
            <?php endif; ?>
        <?php elseif ($lesson_details['lesson_type'] == 'quiz'): ?>
            <div class="mt-5">
                <?php include 'quiz_view.php'; ?>
            </div>
        <?php else: ?>
       

                <!-- Section d'affichage du fichier -->
                <!-- <div class="mt-4"> -->
                    <!-- <h3>Preview:</h3> -->
                    <?php 
                    // $file_path = base_url().'uploads/lesson_files/'.$lesson_details['attachment']; 
                    // $file_extension = pathinfo($lesson_details['attachment'], PATHINFO_EXTENSION);

                    // Affichage en fonction du type de fichier
                    //if (in_array($file_extension, ['pdf'])): ?>
                        <!-- <iframe src="<?php // echo $file_path; ?>" style="width: 100%; height: 500px; border: none;"></iframe> -->
                    <?php //elseif (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                        <!-- <img src="<?php // echo $file_path; ?>" alt="Preview" style="max-width: 100%; height: auto;"> -->
                    <?php // elseif (in_array($file_extension, ['mp4', 'webm', 'ogg'])): ?>
                        <!-- <video controls style="width: 100%; height: auto;">
                            <source src="<?php // echo $file_path; ?>" type="video/<?php // echo $file_extension; ?>">
                            Votre navigateur ne supporte pas la lecture vidéo.
                        </video> -->
                    <?php // elseif (in_array($file_extension, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'])): ?>
                        <!-- Utiliser Google Drive Viewer pour ces formats -->
                        <!-- <iframe src="https://docs.google.com/gview?url=<?php echo $file_path; ?>&embedded=true"  -->
                                <!-- style="width: 100%; height: 500px;" frameborder="0"></iframe> -->
                    <?php // else: ?>
                        <!-- <p>Ce type de fichier ne peut pas être affiché. Vous pouvez le <a href="<?php // echo $file_path; ?>" download>télécharger ici</a>.</p> -->
                    <?php // endif; ?>
                <!-- </div> -->
                 <!-- Section d'affichage du fichier -->
                <div class="mt-4">
                    <h3>Preview:</h3>
                    <?php 
                    $file_path = base_url() . 'uploads/lesson_files/' . $lesson_details['attachment']. '?v=' . time(); 
                    $file_extension = strtolower(pathinfo($lesson_details['attachment'], PATHINFO_EXTENSION));

                    // Détection du type de fichier
                    switch ($file_extension):
                        case 'pdf': 
                            // Affichage pour les PDF
                    ?>
                        <object data="<?php echo $file_path; ?>" type="application/pdf" width="100%" height="500">
                            <p>Votre navigateur ne supporte pas les PDF intégrés. <a href="<?php echo $file_path; ?>" target="_blank">Téléchargez le fichier PDF ici</a>.</p>
                        </object>
                    <?php 
                        break;
                        case 'jpg': case 'jpeg': case 'png': case 'gif':
                            // Affichage pour les images
                    ?>
                        <div style="text-align: center;">
                            <img src="<?php echo $file_path; ?>" alt="Preview" style="max-width: 100%; height: auto; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);">
                        </div>
                    <?php 
                        break;
                        case 'mp4': case 'webm': case 'ogg':
                            // Affichage pour les vidéos
                    ?>
                        <video controls style="width: 100%; height: auto; border-radius: 8px; box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);">
                            <source src="<?php echo $file_path; ?>" type="video/<?php echo $file_extension; ?>">
                            Votre navigateur ne supporte pas la lecture vidéo. <a href="<?php echo $file_path; ?>" download>Téléchargez la vidéo ici</a>.
                        </video>
                    <?php 
                        break;
                        case 'doc': case 'docx': case 'xls': case 'xlsx': case 'ppt': case 'pptx':
                            // Affichage pour les documents bureautiques via Google Viewer
                    ?>
                        <!-- <iframe src="https://docs.google.com/gview?url=<?php echo $file_path; ?>&embedded=true" style="width: 100%; height: 500px; border: none;"></iframe> -->
                            <embed src="<?php echo $file_path; ?>" type="application/pdf" style="width: 100%; height: 500px; border: none;" />

                 <?php 
                        break;
                        default:
                            // Affichage pour les types de fichiers non pris en charge
                    ?>
                        <p>Prévisualisation indisponible pour ce type de fichier. <a href="<?php echo $file_path; ?>" download>Téléchargez le fichier ici</a>.</p>
                    <?php endswitch; ?>
                </div>

                <div class="mt-5">
                    <a href="<?php echo base_url().'uploads/lesson_files/'.$lesson_details['attachment']; ?>" class="btn btn-info text-white" download>
                        <i class="fa fa-download font-size-24"></i> <?php echo get_phrase('download').' '.$lesson_details['title']; ?>
                    </a>
                </div>


        <?php endif; ?>
    </div>

    <div class="margin-m" id = "lesson-summary">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo $lesson_details['lesson_type'] == 'quiz' ? get_phrase('instruction') : get_phrase("note"); ?>:</h5>
                <?php if ($lesson_details['summary'] == ""): ?>
                    <p class="card-text"><?php echo $lesson_details['lesson_type'] == 'quiz' ? get_phrase('no_instruction_found') : get_phrase("no_summary_found"); ?></p>
                <?php else: ?>
                    <p class="card-text"><?php echo $lesson_details['summary']; ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>