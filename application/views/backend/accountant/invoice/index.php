<!-- start page title -->
<div class="row ">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body py-2">
        <h4 class="page-title d-inline-block">
          <i class="mdi mdi-file-document-box title_icon"></i> <?php echo get_phrase('student_fee_manager'); ?>
        </h4>
        <button type="button" class="btn btn-outline-primary btn-rounded alignToTitle float-end mt-1" onclick="rightModal('<?php echo site_url('modal/popup/invoice/single'); ?>', '<?php echo get_phrase('add_single_invoice'); ?>')"> <i class="mdi mdi-plus"></i> <?php echo get_phrase('add_single_invoice'); ?></button>
          <button type="button" class="btn btn-outline-success btn-rounded alignToTitle float-end my-1" style="margin-right: 10px;" onclick="rightModal('<?php echo site_url('modal/popup/invoice/mass'); ?>', '<?php echo get_phrase('add_mass_invoice'); ?>')"> <i class="mdi mdi-plus"></i> <?php echo get_phrase('add_mass_invoice'); ?></button>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>
<!-- end page title -->
<div class="row">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body">
        <div class="row justify-content-md-center" style="margin-bottom: 10px;">
          <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 mb-3 mb-lg-0">
            <div class="form-group">
              <div id="reportrange" class="form-control text-center" data-toggle="date-picker-range" data-target-display="#selectedValue"  data-cancel-class="btn-light">
                <i class="mdi mdi-calendar"></i>&nbsp;
                <span id="selectedValue" style = "text-align: center;"> <?php echo date('F d, Y', $date_from).' - '.date('F d, Y', $date_to); ?> </span> <i class="mdi mdi-menu-down"></i>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 mb-3 mb-lg-0">
            <div class="form-group">
              <select name="class" id="class_id" class="form-control select2" data-bs-toggle="select2">
                <option value="all"><?php echo get_phrase('all_class'); ?></option>
                <?php
                $classes = $this->db->get_where('classes', array('school_id' => school_id()))->result_array();
                $school_id = school_id();
                foreach($classes as $class){
                  $this->db->where('class_id', $class['id']);
                  $this->db->where('school_id', $school_id);
                  $total_student = $this->db->get('enrols');
                  ?>
                  <option value="<?php echo $class['id']; ?>">
                    <?php echo $class['name']; ?>
                    <?php echo "(".$total_student->num_rows().")"; ?>
                  </option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 mb-3 mb-lg-0">
            <div class="form-group">
              <select name="status" id="status" class="form-control select2" data-bs-toggle="select2">
                <option value="all"><?php echo get_phrase('all_status'); ?></option>
                <option value="paid"><?php echo get_phrase('paid'); ?></option>
                <option value="unpaid"><?php echo get_phrase('unpaid'); ?></option>
              </select>
            </div>
          </div>
          <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 mb-3 mb-lg-0">
            <button type="button" class="btn btn-icon btn-secondary form-control" onclick="showAllInvoices()"><?php echo get_phrase('filter'); ?></button>
          </div>
        </div>

        <div class="row justify-content-md-center" style="margin-bottom: 10px;">
          <div class="col-xl-3 col-lg-4 col-md-12 col-sm-12 mb-3 mb-lg-0">
            <button type="button" class="btn btn-icon btn-primary form-control dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><?php echo get_phrase('export_report'); ?></button>
            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 37px, 0px);">
              <a class="dropdown-item" id="export-csv" href="javascript:0" onclick="getExportUrl('csv')">CSV</a>
              <a class="dropdown-item" id="export-pdf" href="javascript:0" onclick="getExportUrl('pdf')">PDF</a>
              <a class="dropdown-item" id="export-print" href="javascript:0" onclick="getExportUrl('print')">Print</a>
            </div>
          </div>
        </div>
        <div class="table-responsive-sm">
          <div class="invoice_content">
            <?php include 'list.php'; ?>
          </div>
        </div> <!-- end table-responsive-->
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>

<script>
var showAllInvoices = function () {
  var url = '<?php echo route('invoice/list'); ?>';
  var dateRange = $('#selectedValue').text();
  var selectedClass = $('#class_id').val();
  var selectedStatus = $('#status').val();
  $.ajax({
    type : 'GET',
    url: url,
    data : {date : dateRange, selectedClass : selectedClass, selectedStatus : selectedStatus},
    success : function(response) {
      $('.invoice_content').html(response);
      initDataTable("basic-datatable");
    }
  });
}


function getExportUrl(type) {
  var url = '<?php echo route('export/url'); ?>';
  var dateRange = $('#selectedValue').text();
  var selectedClass = $('#class_id').val();
  var selectedStatus = $('#status').val();
  $.ajax({
    type : 'post',
    url: url,
    data : {type : type, dateRange : dateRange, selectedClass : selectedClass, selectedStatus : selectedStatus},
    success : function(response) {
      if (type == 'csv') {
        window.open(response, '_self');
      }else{
        window.open(response, '_blank');
      }
    }
  });
}
</script>
