<?php
  $school_title = get_settings('system_title');
  $theme        = get_frontend_settings('theme');
  $active_school_id = $this->frontend_model->get_active_school_id();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include 'metas.php'; ?>
    <?php include 'stylesheets.php';?>
        <?php include 'javascripts.php'; ?>

  </head>
  <body>

    <?php include 'navigation.php';?>

    <?php include $page_name . '.php';?>

    <?php include 'footer.php';?>

    <?php include 'javascripts.php'; ?>

  </body>
</html>
