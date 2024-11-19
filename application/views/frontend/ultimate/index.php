<?php

  // Désactivation du cache navigateur
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");


  $school_title = get_settings('system_title');
  $theme        = get_frontend_settings('theme');
  $active_school_id = $this->frontend_model->get_active_school_id();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
  
    <?php include 'metas.php'; ?>
    <?php include 'stylesheets.php';?>
    
    
  </head>
  <body>

    <?php include 'navigation.php';?>

    <?php include $page_name . '.php';?>
    

    <?php include 'footer.php';?>

    <?php include 'javascripts.php'; ?>

  </body>
</html>
