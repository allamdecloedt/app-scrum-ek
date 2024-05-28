<div class="login-section ">
    <a class="nav-link login-toggle"><?php echo get_phrase('Login'); ?>
    </a>
    <div class="login-dropdown hidden-section display-none">
        <form class="login-form" action="<?php echo site_url('login/validate_login_frontend'); ?>" method="post">
            <div class="mb-4 mt-4 login-input">
                <label for="loginEmail" class="login-input-label text-uppercase">Email address</label>
                <input type="email" class="form-control" id="loginEmail" aria-describedby="emailHelp" name="login_email">
             
            </div>
            <div class="mb-3 login-input">
                <label for="loginPassword" class=" login-input-label text-uppercase" >Password</label>
                <input type="password" class="form-control" id="loginPassword" name="login_password">
            </div>
           
            <button type="submit" class="login-button text-uppercase mb-3"><?php echo get_phrase("login")?></button>
        </form>
    </div>
</div>