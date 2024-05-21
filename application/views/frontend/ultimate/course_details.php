<!-- ========== MAIN ========== -->
<main id="content" role="main">

    <!-- Header Section -->
    <div class="general-container container-fluid">
        <div class="general-header align-items-center">
            <h1 class='col-6 display-4 text_fade text-uppercase text-center  text-sm-break'>
                <?php echo get_phrase('Discover_our_Courses'); ?>
            </h1>
            <!-- Div Section For Header Background Fade In-Out Animation-->
            <div></div>
            <div></div>
            <div></div>
            <!-- End Div Section-->
        </div>
        <img class="ct-img rellax " data-rellax-speed="1.5"
            src="<?php echo base_url('assets/frontend/ultimate/img/online admission/oa-img-top.jpg') ?>" alt="">
        <div class="general-container-ol"></div>
    </div>
    <!-- End Header Section -->

    <div class="details-section container  mt-5 mb-5">
        <div class="row g-0 mt-2 details-img">
            <img src="<?php echo $this->user_model->get_school_image($school_id); ?>" alt="">
        </div>
        <div class="row">

        </div>
    </div>








    <div class="general-container g-0 container-fluid">
        <img class="ct-img rellax " data-rellax-speed="1.5"
            src="<?php echo base_url('assets/frontend/ultimate/img/online admission/oa-img-bot.jpg') ?>" alt="">
        <div class="general-container-ol-bot"></div>

    </div>

</main>