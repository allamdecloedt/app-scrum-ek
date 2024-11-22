<!-- start page title -->
<div class="row ">

<?php
        $user_id = $this->session->userdata('user_id');
        $student_data = $this->db->get_where('students', array('user_id' => $user_id));
       
    if($student_data->num_rows() != 0){ 
?>
<div class="alert alert-warning" role="alert" style="font-size: 15px;">
	<i class="dripicons-information me-2"></i> 
    <?php echo get_phrase('no_course_or_school'); ?> 
    <strong><a style="color: black; font-weight: bold;text-decoration: underline !important;" target="_blank"  href="<?php echo site_url('home/courses'); ?>"><?php echo get_phrase('click_here'); ?></a>
    </strong>.
</div>
<?php
    }
?>

  <div class="col-xl-12">
    <div class="card">
      <div class="card-body py-2">
        <h4 class="page-title"> <i class="mdi mdi-view-dashboard title_icon"></i> <?php echo get_phrase('dashboard'); ?> </h4>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>
<!-- end page title -->

<div class="row ">
    <div class="col-xl-12">
        <div class="row">
            <div class="col-xl-8">
              <div class="row">
                  <div class="col-lg-6">
                      <div class="card widget-flat" id="student" style="on">
                          <div class="card-body">
                              <div class="float-end">
                                  <i class="mdi mdi-account-multiple widget-icon"></i>
                              </div>
                              <h5 class="text-muted font-weight-normal mt-0" title="Number of Student"> <i class="mdi mdi-account-group title_icon"></i>  <?php echo get_phrase('schools'); ?> <a href="" style="color: #6c757d; display: none;" id = "student_list"><i class = "mdi mdi-export"></i></a></h5>
                              <h3 class="mt-3 mb-3">
                                  <?php
                                   
                                    echo $student_data->num_rows();
                                  ?>
                              </h3>
                              <p class="mb-0 text-muted">
                                  <span class="text-nowrap"><?php echo get_phrase('total_number_of_school'); ?></span>
                              </p>
                          </div> <!-- end card-body-->
                      </div> <!-- end card-->
                  </div> <!-- end col-->

                  <div class="col-lg-6">
                      <div class="card widget-flat" id="teacher" style="on">
                          <div class="card-body">
                              <div class="float-end">
                                  <i class="mdi mdi-account-multiple widget-icon"></i>
                              </div>
                              <h5 class="text-muted font-weight-normal mt-0" title="Number of Teacher"> <i class="mdi mdi-account-group title_icon"></i><?php echo get_phrase('classes'); ?>  <a href="" style="color: #6c757d; display: none;" id = "teacher_list"><i class = "mdi mdi-export"></i></a></h5>
                              <h3 class="mt-3 mb-3">
                                  <?php
                                  if($student_data->num_rows() > 0 ){
                                    $student_list =  $student_data->result_array();
                                    $student_ids = array();
                                    foreach ($student_list as $student) {
                                        if(!in_array($student['id'], $student_ids)){
                                            array_push($student_ids, $student['id']);
                                        }
                                    }
                                
                                    $this->db->where_in('student_id', $student_ids);
                                    $student_cours = $this->db->get('enrols')->num_rows();
                                     echo $student_cours;
                                  }else{
                                    echo $student_data->num_rows();
                                  }
                                 
                                   ?>
                              </h3>
                              <p class="mb-0 text-muted">
                                  <span class="text-nowrap"><?php echo get_phrase('total_number_of_class'); ?></span>
                              </p>
                          </div> <!-- end card-body-->
                      </div> <!-- end card-->
                  </div> <!-- end col-->
              </div> <!-- end row -->

   
            </div> <!-- end col -->
            <div class="col-xl-4">
                <!-- <div class="card bg-primary">
                    <div class="card-body">
                        <h4 class="header-title text-white mb-2"><?php echo get_phrase('todays_attendance'); ?></h4>
                        <div class="text-center">
                            <h3 class="font-weight-normal text-white mb-2">
                                <?php echo $this->crud_model->get_todays_attendance(); ?>
                            </h3> -->
                            <!-- <p class="text-light text-uppercase font-13 font-weight-bold"><?php echo $this->crud_model->get_todays_attendance(); ?> <?php echo get_phrase('students_are_attending_today'); ?></p> -->
                            <!-- <a href="<?php echo route('attendance'); ?>" class="btn btn-outline-light btn-sm mb-1"><?php echo get_phrase('go_to_attendance'); ?>
                                <i class="mdi mdi-arrow-right ms-1"></i>
                            </a> -->

                        <!-- </div>
                    </div>
                </div> -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title"><?php echo get_phrase('recent_events'); ?><a href="<?php echo route('event_calendar'); ?>" style="color: #6c757d;"><i class = "mdi mdi-export"></i></a></h4>
                        <?php include 'event.php'; ?>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end col-->
</div>

<script>
$(document).ready(function() {
    initDataTable("expense-datatable");
});
</script>
