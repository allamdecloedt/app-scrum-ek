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
    <div class="container">

        <div class="row justify-content-center">
            <!-- Course Details Grand Section -->
            <div class="col-12 col-lg-8">
                <div class="details-section container mt-5 mb-5 p-0">

                    <!-- Course Details Header Section -->
                    <div class="row details-img">
                        <img src="<?php echo $this->user_model->get_school_image($school_id); ?>" alt="">
                    </div>

                    <div class="row g-0 h-divider"></div>


                    <div class="row  mt-4 justify-content-center ">
                        <img class="col-auto course-icon"
                            src="<?php echo base_url("uploads/schools/placeholder.jpg") ?>" alt="">
                        <span class=" my-4 col-auto text-start font-weight-normal details text-white display-6">
                            <?php echo $school["name"] ?>
                        </span>
                    </div>

                    <!-- END Course Details Header Section -->
                    <script> console.log("<?php echo $school["id"]; ?>")</script>


                    <!-- Course Details Pills Section -->



                    <div class="row mt-4  mb-5 mb-md-0 justify-content-center m-auto course-information">
                        <div class="col-4 col-md-auto align-content-center grand-pill ml-md-3 mb-2 mb-sm-0">
                            <div class=" d-flex justify-content-center ">
                                <?php if ($school["access"] > 0) { ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-unlock-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M11 1a2 2 0 0 0-2 2v4a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h5V3a3 3 0 0 1 6 0v4a.5.5 0 0 1-1 0V3a2 2 0 0 0-2-2" />
                                    </svg>
                                    <span class="text-end text-white ">
                                        <?php echo get_phrase("public"); ?>
                                    </span>

                                <?php } ?>

                                <?php if ($school["access"] == 0) { ?>

                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-lock-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2" />
                                    </svg>

                                    <span class="text-center text-white ">

                                        <?php echo get_phrase("private"); ?>
                                    </span>

                                <?php } ?>
                            </div>
                        </div>

                        <div class=" v-divider my-0"></div>

                        <div class="col-4 col-md-auto  align-content-center grand-pill mb-2 mb-sm-0">
                            <span class="text-white"> <?php echo $school["category"] ?></span>
                            <p class="details-text text-uppercase">category</p>

                        </div>

                        <div class="d-none d-md-block v-divider my-0"></div>

                        <div class="col-4 col-md-auto  align-content-center text-white grand-pill mb-2 mb-sm-0">
                            <span>Free</span>
                            <p class="details-text text-uppercase">price</p>

                        </div>



                        <div class=" v-divider my-0"></div>


                        <div class="col-4 col-md-auto  text-center grand-pill mb-2 mb-sm-0">
                            <span class="text-white font-weight-bold"><?php if ($course_students_count > 0)
                                echo $course_students_count  ;
                            else
                                echo "0" ?></span>
                                <p class="details-text text-uppercase">students</p>
                            </div>

                            <div class="col-auto  d-none d-md-block v-divider my-0"></div>




                            <div class="col-12 col-md-auto justify-content-center grand-pill mb- mb-sm-0 mt-2 mt-md-0">
                                <div class="mt-2 mb-3 mb-md-0">
                                    <img class=" teacher-img mr-1"
                                        src="<?php echo base_url("uploads/schools/placeholder.jpg") ?>" alt="">
                                <span class="text-white d-inline">Teacher</span>
                            </div>
                        </div>
                    </div>

                    <div class="g-0 mb-5 mt-3 h-divider d-none d-md-block "></div>

                    <!--END Course Details Pills Section -->


                    <!-- Course Details Description Section -->

                    <div class="row">
                        <span class="text-white px-8"> <?php echo $school["description"] ?> </span>
                    </div>

                    <!-- END Course Details Description Section -->
                </div>
            </div>

            <!-- END Course Details Grand Section -->



            <!-- Course Details Small Section -->

            <div class="col-8 col-lg-3">
                <div class="container g-0 details-signup-card mt-5 mb-5">
                    <div class="row g-0 details-signup-img">
                        <img class="d-none d-lg-block"
                            src="<?php echo $this->user_model->get_school_image($school_id); ?>" alt="">
                    </div>

                    <div class="row g-0 justify-content-center ">
                        <p class=" mt-2 mb-2 col-12 details text-white"><?php echo $school["name"] ?>
                        </p>
                    </div>

                    <div class="row justify-content-center card-details  ">

                        <div class=" col-auto align-content-center text-white small-pill">
                            <div class="d-flex ">

                                <?php if ($school["access"] > 0) { ?>

                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-unlock-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M11 1a2 2 0 0 0-2 2v4a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h5V3a3 3 0 0 1 6 0v4a.5.5 0 0 1-1 0V3a2 2 0 0 0-2-2" />
                                    </svg>

                                    <span class="">
                                        <?php echo get_phrase("public"); ?>
                                    </span>

                                <?php } ?>

                                <?php if ($school["access"] == 0) { ?>

                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-lock-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2" />
                                    </svg>

                                    <span class="">
                                        <?php echo get_phrase("private"); ?>
                                    </span>

                                <?php } ?>
                            </div>



                        </div>

                        <div class="col-auto small-pill align-content-center">
                            <span class="text-white"> <?php echo $school["category"] ?></span>
                        </div>
                    </div>

                    <div class="h-divider mt-2"></div>

                    <div class="row justify-content-center">

                         <div class="col-4 col-md-auto  text-center grand-pill mb-2 mb-sm-0">
                            <span class="text-white font-weight-bold"><?php if ($course_students_count > 0)
                                echo $course_students_count  ;
                            else
                                echo "0" ?></span>
                                <p class="details-text text-uppercase">students</p>
                            </div>
                            <div class="col-auto v-divider mx-4"></div>

                            <div class="col-auto text-center">
                                <span class="text-white font-weight-bold">1$</span>
                                <p class="details-text text-uppercase">Price</p>
                            </div>

                        </div>

                        <div class="h-divider"></div>

                        <div class=" mt-4 mb-2 row justify-content-center">
                            <div class="col-auto teacher-pill">
                                <img class=" teacher-img " src="<?php echo base_url("uploads/schools/placeholder.jpg") ?>"
                                alt="">
                            <p class="text-white d-inline">Teacher</p>
                        </div>
                    </div>


                    <div class="row justify-content-center">
                        <button class="join-button text-uppercase"><?php echo get_phrase("join") ?></button>
                    </div>
                </div>
            </div>

            <!--END  Course Details Grand Section -->

        </div>
    </div>




</main>