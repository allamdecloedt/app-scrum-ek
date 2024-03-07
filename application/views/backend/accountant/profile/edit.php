<?php
$profile_data = $this->user_model->get_profile_data();
?>
<div class="row justify-content-md-center">
    <div class="col-xl-10 col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title"><?php echo get_phrase('update_profile') ; ?></h4>
                <form method="POST" class="col-12 profileAjaxForm" action="<?php echo route('profile/update_profile') ; ?>" id = "profileAjaxForm" enctype="multipart/form-data">
                    <div class="col-12">
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="name"> <?php echo get_phrase('name') ; ?></label>
                            <div class="col-md-9">
                                <input type="text" id="name" name="name" class="form-control"  value="<?php echo $profile_data['name']; ?>" required>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="email"><?php echo get_phrase('email') ; ?></label>
                            <div class="col-md-9">
                                <input type="email" id="email" name="email" class="form-control"  value="<?php echo $profile_data['email']; ?>" required>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="phone"> <?php echo get_phrase('phone') ; ?></label>
                            <div class="col-md-9">
                                <input type="text" id="phone" name="phone" class="form-control"  value="<?php echo $profile_data['phone']; ?>">
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="address"> <?php echo get_phrase('address') ; ?></label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="address" name = "address" rows="5"><?php echo $profile_data['address']; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="example-fileinput"><?php echo get_phrase('profile_image'); ?></label>
                            <div class="col-md-9 custom-file-upload">
                                <div class="wrapper-image-preview" style="margin-left: -6px;">
                                    <div class="box" style="width: 250px;">
                                        <div class="js--image-preview" style="background-image: url(<?php echo $this->user_model->get_user_image($this->session->userdata('user_id')); ?>); background-color: #F5F5F5;"></div>
                                        <div class="upload-options">
                                            <label for="profile_image" class="btn"> <i class="mdi mdi-camera"></i> <?php echo get_phrase('upload_an_image'); ?> </label>
                                            <input id="profile_image" style="visibility:hidden;" type="file" class="image-upload" name="profile_image" accept="image/*">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-secondary col-xl-4 col-lg-4 col-md-12 col-sm-12" onclick="updateProfileInfo()"><?php echo get_phrase('update_profile') ; ?></button>
                        </div>
                    </div>
                </form>

            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div>

    <div class="col-xl-10 col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title"><?php echo get_phrase('change_password') ; ?></h4>
                <form method="POST" class="col-12 changePasswordAjaxForm" action="<?php echo route('profile/update_password') ; ?>" id = "changePasswordAjaxForm" enctype="multipart/form-data">
                    <div class="col-12">
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="current_password"> <?php echo get_phrase('current_password') ; ?></label>
                            <div class="col-md-9">
                                <input type="password" id="current_password" name="current_password" class="form-control"  value="" required>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="new_password"> <?php echo get_phrase('new_password') ; ?></label>
                            <div class="col-md-9">
                                <input type="password" id="new_password" name="new_password" class="form-control"  value="" required>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label" for="confirm_password"> <?php echo get_phrase('confirm_password') ; ?></label>
                            <div class="col-md-9">
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control"  value="" required>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-secondary col-xl-4 col-lg-4 col-md-12 col-sm-12" onclick="changePassword()"><?php echo get_phrase('change_password') ; ?></button>
                        </div>
                    </div>
                </form>

            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div>
</div>
