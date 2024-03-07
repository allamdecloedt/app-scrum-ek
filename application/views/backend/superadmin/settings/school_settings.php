<?php $school_data = $this->settings_model->get_current_school_data(); ?>
<div class="row justify-content-md-center">
        <div class="col-xl-10 col-lg-10 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title"><?php echo get_phrase('school_settings') ;?></h4>
                    <form method="POST" class="col-12 schoolForm" action="<?php echo route('school_settings/update') ;?>" id = "schoolForm">
                        <div class="col-12">
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label" for="school_name"> <?php echo get_phrase('school_name') ;?></label>
                                <div class="col-md-9">
                                    <input type="text" id="school_name" name="school_name" class="form-control"  value="<?php echo $school_data['name'] ;?>" required>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label" for="phone"><?php echo get_phrase('phone') ;?></label>
                                <div class="col-md-9">
                                    <input type="text" id="phone" name="phone" class="form-control"  value="<?php echo $school_data['phone'] ;?>" required>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label" for="address"> <?php echo get_phrase('address') ;?></label>
                                <div class="col-md-9">
                                    <textarea class="form-control" id="address" name = "address" rows="5" required><?php echo $school_data['address'] ;?></textarea>
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
