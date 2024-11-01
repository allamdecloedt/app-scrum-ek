
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
    <title><?php echo $this->lms_model->get_course_by_id($course_id)['title']; ?> | <?php echo $this->db->get_where('schools', array('id' => school_id()))->row('name'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Creativeitem" name="author" />

    <!-- App favicon -->
	<link rel="shortcut icon" href="<?php echo $this->settings_model->get_favicon(); ?>">
	<?php include 'includes_top.php';?>
</head>
<body class="gray-bg">
	<?php
		include 'lessons.php';
		include 'includes_bottom.php';
		include 'common_scripts.php';
	?>
</body>
</html>
