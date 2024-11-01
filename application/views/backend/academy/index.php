<!--title-->
<?php if($this->session->userdata('student_login') != 1): ?>
    <div class="row ">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body py-2">
            <h4 class="page-title">
              <i class="mdi mdi-calendar-range title_icon"></i> <?php echo get_phrase('all_courses'); ?>
              <a href="<?php echo site_url('addons/courses/course_add'); ?>" type="button" class="btn btn-outline-primary btn-rounded alignToTitle" > <i class="mdi mdi-plus"></i> <?php echo get_phrase('create_new_course'); ?></a>
            </h4>
          </div> <!-- end card body-->
        </div> <!-- end card -->
      </div><!-- end col-->
    </div>
<?php endif; ?>
<div class="row academy_content">
    <?php if($this->session->userdata('superadmin_login') == 1 || $this->session->userdata('admin_login') == 1 || $this->session->userdata('teacher_login') == 1): ?>
        <?php include 'list.php'; ?>
    <?php else: ?>
      <?php include 'grid_view_for_student.php'; ?>
    <?php endif; ?>
</div>

<?php include 'common_scripts.php'; ?>