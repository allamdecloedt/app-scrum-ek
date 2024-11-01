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
       <!-- Contenu de votre page -->
   <?php $this->load->view('frontend/alert_view'); ?>
      <!-- Autres contenus de la page -->
    <div class="container">


        <div class="row justify-content-center">
            <!-- Course Details Grand Section -->
            <div class="col-12 col-lg-8">
                <div class="details-section container mt-5 mb-5 p-0">

                    <!-- Course Details Header Section -->
                    <div class="row details-img">
                        <img src="<?php echo $this->user_model->get_school_image($school_id); ?>" alt="">
                    </div>



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




                    <div class="row mt-4 p-2  mb-5 mb-md-0 justify-content-center m-auto course-information">
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
                                echo $course_students_count;
                            else
                                echo "0" ?></span>
                                <p class="details-text text-uppercase">students</p>
                            </div>

                            <div class="col-auto  d-none d-md-block v-divider my-0"></div>




                            <div class="col-12 col-md-auto justify-content-center grand-pill  mb-sm-0 mt-2 mt-md-0">
                                <div class="mt-2 mb-3 mb-md-0">
                                    <img class=" teacher-img mr-1"
                                        src="<?php echo $this->user_model->get_school_image($school_id); ?>" alt="">
                                <span
                                    class="text-white d-inline"><?php echo $this->user_model->get_school_admin($school_id)["name"]; ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="g-0 mb-5 mt-3 h-divider d-none d-md-block "></div>

                    <!--END Course Details Pills Section -->


                    <!-- Course Details Description Section -->

                    <div class="row mb-4">
                        <span class="text-white px-8"> <?php echo $school["description"] ?> </span>
                    </div>

                    <!-- END Course Details Description Section -->

                    <!-- Course Details Curriculum Section -->
                    <div class="px-8  courses-slider">
                        <?php $courses = $this->frontend_model->get_school_courses($school_id) ?>
                        <?php if ($courses) { ?>
                            <?php foreach ($courses as $course) { ?>
                                <div class="course-slider-item align-items-center justify-content-center">
                                    <div>

                                        <img src="<?php base_url($this->frontend_model->get_course_image($course["thumbnail"])) ?>"
                                            alt="">
                                        <p class="text-grey text-center pt-2"><?php echo $course["title"] ?></p>
                                        <div class="course-slider-description">
                                            <?php echo $course["description"] ?>
                                        </div>



                                    </div>

                                </div>

                            <?php }
                        } ?>

                    </div>
                    <!-- END Course Details Curriculum Section -->
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
                        <p class=" mt-2 mb-2 col-12 details text-white"><?php echo $school["title"] ?>
                        </p>
                    </div>

                    <div class="row justify-content-center course-information ">

                        <div class=" col-auto align-content-center mr-1 text-white ">
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
                        <div class=" col-auto v-divider my-0 m-1"></div>

                        <div class=" col-auto small-pill align-content-center ml">
                            <span class="text-white"> <?php echo $school["category"] ?></span>
                        </div>
                    </div>

                    <div class="h-divider mt-2"></div>

                    <div class="row justify-content-center ">

                        <div class="col-4 col-md-4 text-center grand-pill mb-2 mb-sm-0">
                            <span class="text-white font-weight-bold"><?php if ($course_students_count > 0)
                                echo $course_students_count;
                            else
                                echo "0" ?></span>
                                <p class="details-text text-uppercase">students</p>
                            </div>
                            <div class="col-4 v-divider"></div>

                            <div class="col-4 text-center">
                                <span class="text-white font-weight-bold">1$</span>
                                <p class="details-text text-uppercase">Price</p>
                            </div>

                        </div>

                        <div class="h-divider"></div>

                        <div class=" mt-4 mb-2 row justify-content-center">
                            <div class="col-auto teacher-pill">
                                <img class=" teacher-img "
                                    src="<?php echo $this->user_model->get_school_image($school_id); ?>" alt="">
                            <p class="text-white d-inline text-uppercase">
                                <?php echo $this->user_model->get_school_admin($school_id)["name"]; ?>
                            </p>
                        </div>
                    </div>



                    <div class="row justify-content-center">
                        <form action="<?php echo base_url('home/join_school/' . $school_id); ?>" method="post">
                            
                                <!-- Champ caché pour le jeton CSRF -->
                            <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                            <button id="join-button" type="submit" class="join-button text-uppercase"
                                style="display:none"><?php echo htmlspecialchars(get_phrase("join")); ?></button>
                        </form>
                        <button id="login-join-button" class="join-button text-uppercase"
                            style="display: none;"><?php echo htmlspecialchars(get_phrase("login")); ?></button>
                    </div>
                </div>
            </div>

            <!--END  Course Details Grand Section -->

        </div>
    </div>




</main>

<script>

    if (document.getElementById("login-join-button")) {
        document.getElementById("login-join-button").addEventListener("click", function () {
            document.querySelector('.login-toggle').click();
        })
    }




    $(document).ready(function () {
        $('.courses-slider').slick({
            fade: true,
            autoplay: true,
            autoplaySpeed: 4000,
            arrows: false,
            infininte: true,
            pauseOnFocus: false,
            addaptiveHeight: true,

        });

        const descs = document.querySelectorAll(".course-slider-description")
        descs.forEach(desc => {
            const pars = desc.getElementsByTagName("p");
            Array.from(pars).forEach(par => {
                par.classList.add("text-white")
                par.classList.add("text-center")
            })

        });
    });



</script>

<script>
    $(document).ready(function () {
        function updateButton() {
            $.ajax({
                url: "<?php echo base_url('home/check_student_status_ajax/' . $school_id); ?>",
                method: "GET",
                dataType: "json",
                success: function (response) {
                    var button = $("#join-button");
                    var loginButton = $("#login-join-button");

                    if (response.status === null) {
                        loginButton.show();
                        button.hide();
                    } else {
                        loginButton.hide();
                        button.show();
                        if (response.status == 1) {
                            button.prop("disabled", true).text("<?php echo htmlspecialchars(get_phrase('enrolled')); ?>");
                        } else if (response.status == 0) {
                            button.prop("disabled", true).text("<?php echo htmlspecialchars(get_phrase('pending')); ?>");
                        } else if (response.status == 2) {
                            button.prop("disabled", true).text("<?php echo htmlspecialchars(get_phrase('no_student_account')); ?>");
                        } else {
                            button.prop("disabled", false).text("<?php echo htmlspecialchars(get_phrase('join')); ?>");
                        }
                    }
                }
            });
        }

        // Check status immediately on page load
        updateButton();


        // Optionally, you can refresh the status every few seconds
        setInterval(updateButton, 5000); // 5000 milliseconds = 5 seconds
    });


</script>