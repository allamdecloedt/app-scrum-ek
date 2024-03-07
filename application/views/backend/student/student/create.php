<!--title-->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">
                <i class="mdi mdi-account-multiple-plus title_icon"></i> <?php echo get_phrase('student_admission_form'); ?>
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card pt-0">
            <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                <li class="nav-item">
                    <a href="<?php echo route('student/create'); ?>" class="nav-link rounded-0 <?php if($aria_expand == 'single') echo 'active'; ?>">
                        <i class="mdi mdi-home-variant d-lg-none d-block me-1"></i>
                        <span class="d-none d-lg-block"><?php echo get_phrase('single_student_admission'); ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo route('student/create/bulk'); ?>" class="nav-link rounded-0 <?php if($aria_expand == 'bulk') echo 'active'; ?>">
                        <i class="mdi mdi-account-circle d-lg-none d-block me-1"></i>
                        <span class="d-none d-lg-block"><?php echo get_phrase('bulk_student_admission'); ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo route('student/create/excel'); ?>" class="nav-link rounded-0 <?php if($aria_expand == 'excel') echo 'active'; ?>">
                        <i class="mdi mdi-settings-outline d-lg-none d-block me-1"></i>
                        <span class="d-none d-lg-block"><?php echo get_phrase('excel_upload'); ?></span>
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active">
                    <?php
                        if($aria_expand == 'single'):
                            include 'single_student_admission.php';
                        elseif($aria_expand == 'bulk'):
                            include 'bulk_student_admission.php';
                        elseif($aria_expand == 'excel'):
                            include 'excel_student_admission.php';
                        endif;
                    ?>
                </div>
            </div>   
        </div>
    </div>
</div>