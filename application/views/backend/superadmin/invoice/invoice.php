<?php
  $invoice_details = $this->crud_model->get_invoice_by_id($invoice_id);
  $student_details = $this->user_model->get_student_details_by_id('student', $invoice_details['student_id']);
 ?>

<!--title-->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">
            	<i class="mdi mdi-grease-pencil title_icon"></i> <?php echo get_phrase('invoice'); ?>
        	</h4>
        </div>
    </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">

        <!-- Invoice Logo-->
        <div class="clearfix">
          <div class="float-start mb-3">
            <img src="<?php echo $this->settings_model->get_logo_dark(); ?>" alt="" height="18">
          </div>
        </div>

        <!-- Invoice Detail-->
        <div class="row">
          <div class="col-sm-6">
            <div class="float-start mt-3">
              <p><b><?php echo get_phrase('hello'); ?>, <?php echo $student_details['name']; ?></b></p>
              <p class="text-muted font-13"><?php echo get_phrase('please_find_below_the_invoice'); ?>.</p>
            </div>

          </div><!-- end col -->
          <div class="col-sm-4 offset-sm-2">
            <div class="mt-3 float-sm-right">
              <p class="font-13"><strong><?php echo get_phrase('invoice_no'); ?>: </strong> &nbsp;&nbsp;&nbsp; <?php echo sprintf('%08d', $invoice_details['id']); ?></p>
              <p class="font-13"><strong><?php echo get_phrase('date'); ?>: </strong> &nbsp;&nbsp;&nbsp; <?php echo date('D, d-M-Y'); ?></p>
              <p class="font-13"><strong><?php echo get_phrase('status'); ?>: </strong>
                <?php if (strtolower($invoice_details['status']) == 'paid'): ?>
                  <span class="badge bg-success float-end"><?php echo get_phrase('paid'); ?></span></p>
                <?php else: ?>
                  <span class="badge bg-danger float-end"><?php echo get_phrase('unpaid'); ?></span></p>
                <?php endif; ?>
            </div>
          </div><!-- end col -->
        </div>
        <!-- end row -->

        <div class="row mt-4">
          <div class="col-sm-4">
            <h6><?php echo get_phrase('billing_details'); ?></h6>
            <address>
              <?php echo $student_details['name']; ?><br>
              <?php echo $student_details['address'] == "" ? '('.get_phrase('address_not_found').')' : $student_details['address']; ?><br>
              <abbr title="Phone">P:</abbr> <?php echo $student_details['phone'] == "" ? '('.get_phrase('phone_number_not_found').')' : $student_details['phone']; ?><br>
            </address>
          </div> <!-- end col-->
        </div>
        <!-- end row -->

        <div class="row">
          <div class="col-12">
            <div class="table-responsive">
              <table class="table mt-4">
                <thead>
                  <tr><th>#</th>
                    <th><?php echo get_phrase('invoice_title'); ?></th>
                    <th><?php echo get_phrase('total_amount'); ?></th>
                    <th><?php echo get_phrase('paid_amount'); ?></th>
                    <th class="text-end"><?php echo get_phrase('due_amount'); ?></th>
                  </tr></thead>
                  <tbody>
                    <tr>
                      <td>1</td>
                      <td>
                        <b><?php echo get_phrase('student_fee'); ?></b> <br/>
                        <?php echo get_phrase('created_at').' : '.date('D, d-M-Y', $invoice_details['created_at']); ?>
                      </td>
                      <td><?php echo currency($invoice_details['total_amount']); ?></td>
                      <td><?php echo currency($invoice_details['paid_amount']); ?></td>
                      <td class="text-end"><?php echo currency($invoice_details['total_amount'] - $invoice_details['paid_amount']); ?></td>
                    </tr>
                  </tbody>
                </table>
              </div> <!-- end table-responsive-->
            </div> <!-- end col -->
          </div>
          <!-- end row -->

          <div class="row">
            <div class="col-sm-6">
              <div class="clearfix pt-3">
                <h6 class="text-muted"></h6>
                <small>

                </small>
              </div>
            </div> <!-- end col -->
            <div class="col-sm-6">
              <div class="float-end mt-3 mt-sm-0">
                <p><b><?php echo get_phrase('total_amount'); ?> :&nbsp;</b> <span class="float-end"><?php echo currency($invoice_details['total_amount']); ?></span></p>
                <p><b><?php echo get_phrase('due_amount'); ?> : </b> <span class="float-end"><?php echo currency($invoice_details['total_amount'] - $invoice_details['paid_amount']); ?></span></p>
                <h3><?php echo currency($invoice_details['total_amount'] - $invoice_details['paid_amount']); ?></h3>
              </div>
              <div class="clearfix"></div>
            </div> <!-- end col -->
          </div>
          <!-- end row-->

          <div class="d-print-none mt-4">
            <div class="text-end">
              <a href="javascript:window.print()" class="btn btn-primary"><i class="mdi mdi-printer"></i> <?php echo get_phrase('print'); ?></a>
            </div>
          </div>
          <!-- end buttons -->

        </div> <!-- end card-body-->
      </div> <!-- end card -->
    </div> <!-- end col-->
  </div>
