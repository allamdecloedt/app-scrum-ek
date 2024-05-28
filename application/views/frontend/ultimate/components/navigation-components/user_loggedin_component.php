<div class="user-section ">

    <span
        class="text-capitalize ml-2 ml-lg-3 align-content-center text-white"><?php echo $this->session->user_name; ?></span>

    <img src="<?php echo $this->user_model->get_user_image($this->session->userdata('user_id')); ?>" alt="user-image"
        class="rounded-circle nav-user-img">

    <div class="user-dropdown hidden-section">

        <!-- item-->
        <a href="<?php echo route('dashboard'); ?>" class="user-dropdown-item text-white">
            <span><svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-layers-half" viewBox="0 0 16 16">
                    <path
                        d="M8.235 1.559a.5.5 0 0 0-.47 0l-7.5 4a.5.5 0 0 0 0 .882L3.188 8 .264 9.559a.5.5 0 0 0 0 .882l7.5 4a.5.5 0 0 0 .47 0l7.5-4a.5.5 0 0 0 0-.882L12.813 8l2.922-1.559a.5.5 0 0 0 0-.882zM8 9.433 1.562 6 8 2.567 14.438 6z" />
                </svg><?php echo get_phrase('dashboard'); ?></span>
        </a>

        <!-- item-->
        <a href="<?php echo site_url('login/logout'); ?>" class="user-dropdown-item text-white">
            <span><svg class="m-1 " xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z" />
                    <path fill-rule="evenodd"
                        d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
                </svg><?php echo get_phrase('logout'); ?></span>
        </a>

    </div>
</div>