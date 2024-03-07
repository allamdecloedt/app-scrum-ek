<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body py-2">
                <h4 class="page-title d-inline-block">
                    <span class="mdi mdi-road-variant"></span> <?php echo get_phrase('trips'); ?>
                </h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <form method="POST" class="d-block px-3 mb-3" action="<?php echo route('trips'); ?>" enctype="multipart/form-data">
                <div class="row mt-3">
                    <h5><?php echo get_phrase('select_a_child'); ?></h5>
                    <div class="col-md-4 mb-1">
                        <select name="filter_child_id" id="filter_child_id" class="form-control select2" data-toggle="select2" required>
                            <option value=""><?php echo get_phrase('select_a_child'); ?></option>
                            <?php
                            $school_id = school_id();
                            $parent_id = parent_id();
                            $this->db->select('students.*, users.name');
                            $this->db->from('students');
                            $this->db->join('users', 'students.user_id = users.id');
                            $this->db->where('students.school_id', $school_id);
                            $this->db->where('students.parent_id', $parent_id);
                            $children = $this->db->get()->result_array();

                            foreach ($children as $child) : ?>
                                <option value="<?php echo $child['id']; ?>" <?php if ($filter == $child['id']) : ?> selected <?php endif; ?>>
                                    <?php echo $child['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-block btn-secondary"><?php echo get_phrase('filter'); ?></button>
                    </div>
                </div>
            </form>

            <div class="card-body assigned_student_content">
                <?php include 'child_location.php'; ?>
            </div>
        </div>
    </div>
</div>