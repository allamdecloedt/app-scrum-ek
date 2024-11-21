<div class="frontend_gallery_content">
  <?php $frontend_gallery = $this->db->get_where('frontend_gallery', array('school_id' => school_id()))->result_array(); ?>

  <div class="row">
    <div class="col-12 mb-2">
      <button type="button" class="btn btn-outline-primary btn-rounded alignToTitle" onclick="rightModal('<?php echo site_url('modal/popup/website_settings/create_gallery'); ?>', '<?php echo get_phrase('create_gallery'); ?>')"> <i class="mdi mdi-plus"></i> <?php echo get_phrase('create_gallery'); ?></button>
    </div>
    <?php if (count($frontend_gallery) > 0): ?>
      <?php foreach($frontend_gallery as $gallery):?>
        <div class="col-md-6 col-xl-4">
          <!-- project card -->
          <div class="card d-block">
            <div class="card-body" style="height: 202px;">
              <div class="dropdown card-widgets">
                <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="dripicons-dots-3"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                  <!-- item-->
                  <a href="javascript:void(0);" class="dropdown-item" onclick="rightModal('<?php echo site_url('modal/popup/website_settings/edit_gallery/'.$gallery['frontend_gallery_id']); ?>', '<?php echo get_phrase('update_gallery'); ?>');"><i class="mdi mdi-pencil me-1"></i><?php echo get_phrase('edit'); ?></a>
                  <!-- item-->
                  <a href="javascript:void(0);" class="dropdown-item" onclick="confirmModal('<?php echo route('frontend_gallery/delete/'.$gallery['frontend_gallery_id']); ?>', showAllGallery)"><i class="mdi mdi-delete me-1"></i><?php echo get_phrase('delete'); ?></a>
                </div>
              </div>
              <!-- project title-->
              <h4 class="mt-0">
                <a href="<?php echo route('website_settings/gallery_image/'.$gallery['frontend_gallery_id']); ?>" class="text-title"><?php echo $gallery['title']; ?></a>
              </h4>
              <?php if ($gallery['show_on_website']): ?>
                  <div class="badge bg-success mb-3"><?php echo get_phrase('visible'); ?></div>
              <?php else: ?>
                  <div class="badge bg-danger mb-3"><?php echo get_phrase('not_visible'); ?></div>
              <?php endif; ?>


              <p class="text-muted font-13 mb-3">
                <?php echo ellipsis($gallery['description'], 150); ?>
              </p>
            </div> <!-- end card-body-->
            <ul class="list-group list-group-flush">
              <li class="list-group-item p-3">
                <div>
                  <?php $photos = $this->frontend_model->get_photos_by_gallery_id($gallery['frontend_gallery_id']); ?>
                  <?php if (count($photos) > 0): ?>
                    <?php foreach ($photos as $key => $photo): ?>
                      <?php if ($key <= 2): ?>
                        <a href="<?php echo route('website_settings/gallery_image/'.$gallery['frontend_gallery_id']); ?>" class="d-inline-block">
                          <img src="<?php echo $this->frontend_model->get_gallery_image($photo['image']); ?>" class="rounded-circle avatar-xs" alt="friend">
                        </a>
                      <?php endif; ?>
                    <?php endforeach; ?>
                    <?php if (count($photos) > 3): ?>
                      <a href="<?php echo route('website_settings/gallery_image/'.$gallery['frontend_gallery_id']); ?>" class="d-inline-block text-muted font-weight-bold ms-2">
                        +<?php echo (count($photos)-3).' '.get_phrase('more');  ?>
                      </a>
                    <?php endif; ?>
                  <?php else: ?>
                    <span><?php echo get_phrase('no_photos_found'); ?></span>
                  <?php endif; ?>
                </div>
              </li>
            </ul>
          </div> <!-- end card-->
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <?php include APPPATH.'views/backend/empty.php'; ?>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>


<script type="text/javascript">
var showAllGallery = function () {
   var url = '<?php echo route('frontend_gallery/gallery_list'); ?>';

   $.ajax({
      type : 'GET',
      url: url,
      success : function(response) {
         $('.frontend_gallery_content').html(response);
      }
   });
}
</script>
