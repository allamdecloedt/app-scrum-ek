<?php $base_url = base_url(); ?>


<!-- Google Fonts -->
<link href="//fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">

<!-- CSS Implementing Plugins -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/frontend/<?php echo $theme;?>/vendor/font-awesome/css/fontawesome-all.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/frontend/<?php echo $theme;?>/vendor/animate.css/animate.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/frontend/<?php echo $theme;?>/vendor/fancybox/jquery.fancybox.css">
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<link rel="stylesheet" href="<?php echo base_url();?>assets/frontend/<?php echo $theme;?>/vendor/cubeportfolio/css/cubeportfolio.min.css">

<!-- CSS Front Template -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/frontend/<?php echo $theme;?>/css/theme.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/toastr/toastr.min.css">



<link rel="stylesheet" href="<?php echo base_url();?>assets/frontend/ultimate/css/theme.css">

<!-- CSS Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


<!-- CSS Owl carousel -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- CSS Leaflet -->

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />



<!-- JS Script in need of first loading -->

<script src="<?php echo base_url();?>assets/frontend/<?php echo $theme;?>/vendor/jquery/dist/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/rellax/1.12.1/rellax.min.js"
  integrity="sha512-f5HTYZYTDZelxS7LEQYv8ppMHTZ6JJWglzeQmr0CVTS70vJgaJiIO15ALqI7bhsracojbXkezUIL+35UXwwGrQ=="
  crossorigin="anonymous" referrerpolicy="no-referrer"></script>



<!-- Custom CSS-->


<link rel="stylesheet" href="<?php echo base_url();?>assets/frontend/ultimate/css/footer.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/frontend/ultimate/css/navigation.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/frontend/ultimate/css/general.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/frontend/ultimate/css/toastr.min.css">


<?php
if ($page_name == "home") {

  echo '<link rel="stylesheet" href="' . $base_url . 'assets/frontend/ultimate/css/custom.css">';

} elseif ($page_name == "about") {

  echo '<link rel="stylesheet" href="' . $base_url . 'assets/frontend/ultimate/css/about-page.css">';

} elseif ($page_name == "contact") {

  echo '<link rel="stylesheet" href="' . $base_url . 'assets/frontend/ultimate/css/contact-page.css">';

}elseif ($page_name == "online_admission"){

    echo '<link rel="stylesheet" href="' . $base_url . 'assets/frontend/ultimate/css/online-admission-page.css">';
}
elseif ($page_name == "online_admission_student"){

  echo '<link rel="stylesheet" href="' . $base_url . 'assets/frontend/ultimate/css/online-admission-page.css">';
}elseif ($page_name == "courses"){

    echo '<link rel="stylesheet" href="' . $base_url . 'assets/frontend/ultimate/css/courses-page.css">';
}elseif ($page_name == "course_details"){

    echo '<link rel="stylesheet" href="' . $base_url . 'assets/frontend/ultimate/css/course-details-page.css">';
}
?>
