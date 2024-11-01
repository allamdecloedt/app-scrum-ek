<?php $school_data = $this->settings_model->get_current_school_data(); ?>
<div class="row justify-content-md-center">
        <div class="col-xl-10 col-lg-10 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title"><?php echo get_phrase('school_settings') ;?></h4>
                    <form method="POST" class="col-12 schoolForm" action="<?php echo route('school_settings/update') ;?>" id = "schoolForm">
                        <!-- Champ caché pour le jeton CSRF -->
                        <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                        <div class="col-12">
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label" for="school_name"> <?php echo get_phrase('school_name') ;?><span class="required"> * </span></label>
                                <div class="col-md-9">
                                    <input type="text" id="school_name" name="school_name" class="form-control"  value="<?php echo $school_data['name'] ;?>" required>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label" for="description"><?php echo get_phrase('description'); ?><span class="required"> * </span></label>
                                <div class="col-md-9">
                                <textarea class="form-control"  id="description"  name = "description" rows="5" required><?php echo $school_data['description']; ?></textarea>
                                <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_description'); ?></small>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label" for="phone"><?php echo get_phrase('phone') ;?><span class="required"> * </span></label>
                                <div class="col-md-9">
                                    <input type="text" id="phone" name="phone" class="form-control"  value="<?php echo $school_data['phone'] ;?>" required>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label" for="access"><?php echo get_phrase('Access'); ?><span class="required"> * </span></label>
                                <div class="col-md-9">
                                <select name="access" id="access" class="form-control select2" data-toggle = "select2" required>
                                    <option value=""><?php echo get_phrase('select_a_access'); ?></option>
                                    <option <?php if ($school_data['access'] == 1): ?> selected <?php endif; ?> value="1"><?php echo get_phrase('public'); ?></option>
                                    <option <?php if ($school_data['access'] == 0): ?> selected <?php endif; ?> value="0"><?php echo get_phrase('privé'); ?></option>
                                
                                </select>
                                <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_access'); ?></small>
                                </div>
                           </div>

                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label" for="address"> <?php echo get_phrase('address') ;?></label>
                                <div class="col-md-9">
                                    <textarea class="form-control" id="address" name = "address" rows="5" required><?php echo $school_data['address'] ;?></textarea>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label"  for="access"><?php echo get_phrase('Category'); ?><span class="required"> * </span></label>
                                <div class="col-md-9">
                                    <select name="category" id="category" class="form-control select2" data-toggle = "select2" required>
                                        <option value=""><?php echo get_phrase('select_a_category'); ?></option>
                                        <?php $categories = $this->db->get_where('categories', array())->result_array(); ?>
                                        <?php foreach ($categories as $categorie): ?>
                                            <option <?php if ($school_data['category'] == $categorie['name']): ?> selected <?php endif; ?> value="<?php echo $categorie['name']; ?>"><?php echo $categorie['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small id="" class="form-text text-muted"><?php echo get_phrase('provide_admin_category'); ?></small>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label" for="example-fileinput"><?php echo get_phrase('school_profile_image'); ?></label>
                                <div class="col-md-9 custom-file-upload">
                                    <div class="wrapper-image-preview" style="margin-left: -6px;">
                                        <div class="box" style="width: 250px;">
                                            <div class="js--image-preview" style="background-image: url(<?php echo $this->user_model->get_school_image($school_data['id']); ?>); background-color: #F5F5F5;"></div>
                                            <div class="upload-options">
                                                <label for="school_image" class="btn"> <i class="mdi mdi-camera"></i> <?php echo get_phrase('upload_an_image'); ?> </label>
                                                <input id="school_image" style="visibility:hidden;" type="file" class="image-upload" name="school_image" accept="image/*">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                           </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-secondary col-xl-4 col-lg-4 col-md-12 col-sm-12" onclick="updateSchoolInfo()"><?php echo get_phrase('update_settings') ;?></button>
                            </div>
                        </div>
                    </form>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div>
    </div>
