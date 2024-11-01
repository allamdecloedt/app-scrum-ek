<?php
  if ($action == 'pdf') {
    $action = get_phrase('export_pdf');
  }else{
    $action = get_phrase($action);
  }
  if ($selected_class == 'all') {
    $classNameForTitle = get_phrase('all_class');
  }else{
    $class_details = $this->crud_model->get_classes($selected_class)->row_array();
    $classNameForTitle = $class_details['name'];
  }
  if ($selected_status == 'all') {
    $selectedStatusForTitle = get_phrase('all');
  }else{
    $selectedStatusForTitle = ucfirst($selected_status);
  }
?>
<!DOCTYPE html>
<html>
<head>
  <title><?php echo $action.' '.get_phrase('student_fee_report'); ?></title>
  <link rel="shortcut icon" href="<?php echo $this->settings_model->get_favicon(); ?>">
  <style>
    <?php include FCPATH.'assets/backend/css/export.css'; ?>
  </style>
</head>
<body>

  <h2 style="text-align: center;"><?php echo get_phrase('student_fee_report'); ?></h2>
  <p style="text-align: center;"><?php echo get_phrase('from').' : '.date('d-M-Y', $date_from).' '.get_phrase('to').' : '.date('d-M-Y', $date_to); ?></p>
  <p style="text-align: center;"><?php echo get_phrase('class').' : '.$classNameForTitle; ?></p>
  <p style="text-align: center;"><?php echo get_phrase('status').' : '.$selectedStatusForTitle; ?></p>

  <table width="100%">
    <thead>
      <tr>
        <th><?php echo get_phrase('invoice_no'); ?></th>
        <th><?php echo get_phrase('student'); ?></th>
        <th><?php echo get_phrase('class'); ?></th>
        <th><?php echo get_phrase('invoice_title'); ?></th>
        <th><?php echo get_phrase('total_amount'); ?></th>
        <th><?php echo get_phrase('paid_amount'); ?></th>
        <th><?php echo get_phrase('creation_date'); ?></th>
        <th><?php echo get_phrase('payment_date'); ?></th>
        <th><?php echo get_phrase('status'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php $invoices = $this->crud_model->get_invoice_by_date_range($date_from, $date_to, $selected_class, $selected_status)->result_array();
      foreach ($invoices as $invoice):
        $student_details = $this->user_model->get_student_details_by_id('student', $invoice['student_id']);
        $class_details = $this->crud_model->get_class_details_by_id($invoice['class_id'])->row_array(); ?>
        <tr>
          <td> <?php echo sprintf('%08d', $invoice['id']); ?> </td>
          <td> <?php echo $student_details['name']; ?> </td>
          <td> <?php echo $class_details['name']; ?> </td>
          <td> <?php echo $invoice['title']; ?> </td>
          <td> <?php echo currency($invoice['total_amount']); ?> </td>
          <td> <?php echo currency($invoice['paid_amount']); ?> </td>
          <td> <?php echo date('d-M-Y', $invoice['created_at']); ?> </td>
          <td>
            <?php if ($invoice['updated_at'] > 0): ?>
              <?php echo date('d-M-Y', $invoice['updated_at']); ?>
            <?php else: ?>
              <?php echo get_phrase('not_found'); ?>
            <?php endif; ?>
          </td>
          <td> <?php echo ucfirst($invoice['status']); ?> </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
