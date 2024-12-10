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
    <div class="container g-0 courses-section ">

        <div class="row justify-content-center pt-5">
            <form class="col-10 col-lg-8 search-bar " action="<?php echo site_url('home/courses_search'); ?>" method="get">


            <div class="input-group pb-5 shadow-sm rounded">
                <!-- Dropdown des catégories -->
           
                    <select name="categories" id="categories"  class="form-select border-0 select_course"  onchange="location = this.value;">
                        <!-- Option pour "All" -->
                        <option value="<?php echo base_url('home/courses/'); ?>" <?php echo empty($selected_category) ? 'selected' : ''; ?>>
                            <?php echo get_phrase('All'); ?>
                        </option>

                        <!-- Boucle sur les catégories -->
                        <?php foreach ($categories as $category): ?>
                            <?php 
                            $cat_formated = $this->frontend_model->get_category_formated($category['name']); 
                            ?>
                            <option value="<?php echo base_url('home/courses/' . $cat_formated); ?>" 
                                <?php echo ($selected_category == $category['name']) ? 'selected' : ''; ?>>
                                <?php echo $category['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

            <div style="margin-left: 10px;"></div>

                <!-- Champ de recherche -->
                <input name="search" type="search" class="form-control border-0" placeholder="<?php echo get_phrase('Search'); ?>"
                    aria-label="Search" aria-describedby="search-addon" value="<?php if ($input_search) echo ($input_search); ?>" />

                <!-- Bouton de recherche -->
                <button type="submit" class=" input-group-text px-4 rounded-end" id="search-addon">
                    <i class="fas fa-search"></i>
                </button>
            </div>




            </form>
        </div>

        
        <div class="row justify-content-around">
            <div class="row justify-content-center">
                <div id="category-section" class="row justify-content-center category-section">
                    <!-- <div class="col-auto g-1 d-flex align-content-center ">
                        <a id="more-button"
                            class="category text-capitalize font-weight-bold py-1 "><?php //echo get_phrase('more') ?>
                            <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-arrow-bar-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M6 8a.5.5 0 0 0 .5.5h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L12.293 7.5H6.5A.5.5 0 0 0 6 8m-2.5 7a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5" />
                            </svg>
                        </a>
                    </div> -->
                    <!-- <div class="col-auto g-1 ">
                        <a id="less-button" class="category text-capitalize font-weight-bold py-1">
                            <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-arrow-bar-left" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M12.5 15a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5M10 8a.5.5 0 0 1-.5.5H3.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L3.707 7.5H9.5a.5.5 0 0 1 .5.5" />
                            </svg>
                            <?php //echo get_phrase('less') ?>
                        </a>
                    </div> -->
                    <!-- <div class="col-auto g-1 d-flex align-content-center ">
                        <a href="<?php //echo base_url('home/courses/') ?>"
                            class="category text-capitalize <?php // echo ($selected_category == $category['name']) ? 'active-cat' : ''; ?> py-1"><?php //echo get_phrase('All') ?></a>
                    </div> -->
                    <?php

                    // foreach ($categories as $category) {
                    //     $cat_formated = $this->frontend_model->get_category_formated($category['name'])
                          
                          ?>
                        <!-- <div class="col-auto g-1 d-flex align-content-center ">
                            <a href="<?php // echo base_url('home/courses/' . $cat_formated) ?>"
                                class="category text-capitalize py-1 option <?php //echo ($selected_category == $category['name']) ? 'active-cat' : ''; ?>"><?php //echo $category['name'] ?></a>
                        </div> -->
                        <?php
                    // }
                    ?>
                    

                </div>
            </div>

        </div>






        <div class="container mt-11">
            <div class="row justify-content-sm-start justify-content-center">
                <?php if ($schools == null) ?>
                <p class="text-white text-center"><?php echo $no_courses_found ?></p>
                <?php if ($schools != null) {
                    $courses_array = $schools->result_array();
                    foreach ($courses_array as $c) {
                        ?>
                        <!-- Course Card Start -->
                        <a href="<?php echo base_url('home/course_details/' . $c['id']) ?>"
                            class="col-11 col-sm-10 col-md-5 col-lg-3 pt-4 pb-4 ">
                            <div class=" course-card row g-0 ">
                                <div class="course-category text-break text-capitalize">
                                    <span><?php echo $c['category'] ?></span>
                                </div>
                                <div class="course-access text-break">
                                    <?php if ($c["access"] == 1)
                                        echo get_phrase('public');
                                    else
                                        echo get_phrase('private'); ?>
                                </div>
                                <div class=course-card-overlay></div>
                                <img class="course-card-img" src="<?php echo $this->user_model->get_school_image($c['id']); ?>"
                                    alt="">
                                <div class="container-fluid">
                                    <div>
                                        <h3 class="course-title text-uppercase text-break"><?php echo $c['name'] ?></h3>
                                        <p class="course-description text-break"><?php echo $c['description'] ?></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <!-- Course Card Start -->
                        <?php
                    }
                    ?>

                </div>

            </div>

            <div class="row justify-content-center">
                <div class="col-auto">
                    <?php echo $links; ?>
                </div>
            </div>
            <?php
                }

                ?>
    </div>

    </div>
    </div>

    <div class="general-container g-0 container-fluid mt-11">
        <img id="img-bot" class="ct-img rellax " data-rellax-speed="1.5"
            src="<?php echo base_url('assets/frontend/ultimate/img/online admission/oa-img-bot.jpg') ?>" alt="">
        <div class="general-container-ol-bot"></div>

    </div>

    </main>