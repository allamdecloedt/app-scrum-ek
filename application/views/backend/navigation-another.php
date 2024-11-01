<?php
$controller = "";
if($user_type == 'parent'){
  $controller = 'parents';
} else{
  $controller = $user_type;
}
?>
<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu left-side-menu-detached content-main">

  <div class="leftbar-user">
    <a href="javascript: void(0);">
      <img src="<?php echo $this->user_model->get_user_image($this->session->userdata('user_id')); ?>" alt="user-image" height="42" class="rounded-circle shadow-sm">
      <?php
      $user_details = $this->user_model->get_user_details($this->session->userdata('user_id'));
      ?>
      <span class="leftbar-user-name"><?php echo $user_details['name']; ?></span>
    </a>
  </div>
  <!--- Sidemenu -->
  <ul class="metismenu side-nav side-nav-light">
    <li class="side-nav-title side-nav-item"><?php echo get_phrase('navigation'); ?></li>
    <li class="side-nav-item">
      <a href="<?php echo site_url($controller.'/dashboard'); ?>" class="side-nav-link">
        <i class="dripicons-meter"></i>
        <span> <?php echo get_phrase('dashboard'); ?> </span>
      </a>
    </li>

    <?php
    $this->db->order_by('sort_order', 'asc');
    $main_menus = $this->db->get_where('menus', array('parent' => 0, 'status' => 1, $this->session->userdata('user_type').'_access' => 1))->result_array();
    foreach($main_menus as $main_menu){
      ?><li class="side-nav-item <?php if($main_menu['displayed_name'] == $navigate) echo 'active'; ?>"><?php
      $this->db->order_by('sort_order', 'asc');
      $check_menus = $this->db->get_where('menus', array('parent' => $main_menu['id'], 'status' => 1, $this->session->userdata('user_type').'_access' => 1));
      if($check_menus->num_rows() > 0){ ?>
        <a href="javascript: void(0);" class="side-nav-link <?php if($main_menu['displayed_name'] == $navigate) echo 'active'; ?>">
          <i class="<?php echo $main_menu['icon']; ?>"></i>
          <span><?php echo get_phrase($main_menu['displayed_name']); ?></span>
          <span class="menu-arrow"></span>
        </a>
        <ul class="side-nav-second-level" aria-expanded="false">
          <?php $this->db->order_by('sort_order', 'asc'); ?>
          <?php $menus = $this->db->get_where('menus', array('parent' => $main_menu['id'], 'status' => 1, $this->session->userdata('user_type').'_access' => 1))->result_array();
          foreach ($menus as $menu) {
            $this->db->order_by('sort_order', 'asc');
            $check_sub_menus = $this->db->get_where('menus', array('parent' => $menu['id'], 'status' => 1, $this->session->userdata('user_type').'_access' => 1));
            if($check_sub_menus->num_rows() > 0){ ?>
              <li class="side-nav-item">
                <a href="javascript: void(0);" aria-expanded="false"><?php echo get_phrase($menu['displayed_name']); ?>
                  <span class="menu-arrow"></span>
                </a>
                <ul class="side-nav-third-level" aria-expanded="false">
                  <?php
                  $this->db->order_by('sort_order', 'asc');
                  $sub_menus = $this->db->get_where('menus', array('parent' => $menu['id'], $this->session->userdata('user_type').'_access' => 1))->result_array();
                  foreach ($sub_menus as $sub_menu) {
                    ?>
                    <li>
                      <?php
                        if ($menu['is_addon']) {
                          $route = 'addons/'.$sub_menu['route_name'];
                        }else{
                          $route = $controller.'/'.$sub_menu['route_name'];
                        }
                       ?>
                      <a href="<?php echo site_url($route); ?>"><?php echo get_phrase($sub_menu['displayed_name']); ?></a>
                    </li>
                  <?php } ?>
                </ul>
              </li>
            <?php }else{ ?>
              <li class="<?php if($menu['displayed_name'] == $navigate) echo 'active'; ?>">
                <?php
                  if ($menu['is_addon']) {
                    $route = 'addons/'.$menu['route_name'];
                  }else{
                    $route = $controller.'/'.$menu['route_name'];
                  }
                 ?>
                <a href="<?php echo site_url($route); ?>" class="<?php if($menu['displayed_name'] == $navigate) echo 'active'; ?>"><?php echo get_phrase($menu['displayed_name']); ?></a>
              </li>
            <?php } ?>
          <?php } ?></ul><?php
        }else{ ?>
          <?php
            if ($menu['is_addon']) {
              $route = 'addons/'.$main_menu['route_name'];
            }else{
              $route = $controller.'/'.$main_menu['route_name'];
            }
           ?>
          <a href="<?php echo site_url($route); ?>" class="side-nav-link">
            <i class="<?php echo $main_menu['icon']; ?>"></i>
            <span><?php echo get_phrase($main_menu['displayed_name']); ?></span>
          </a>
        </li>
      <?php }
    }
    ?>
  </ul>
  <!-- End Sidebar -->

  <div class="clearfix"></div>
  <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->

<!-- ============================================================== -->
<!-- Start Page Content here
