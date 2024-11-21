<?php
$school_id = school_id();
$classes = $this->db->get_where('classes', array('school_id' => $school_id))->result_array();
if (count($classes) > 0): ?>
<table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%">
    <thead>
        <tr style="background-color: #313a46; color: #ababab;">
            <th><?php echo get_phrase('name'); ?></th>
            <th><?php echo get_phrase('section'); ?></th>
            <th><?php echo get_phrase('options'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($classes as $class): ?>
            <tr>
                <td><?php echo $class['name']; ?></td>
                <td>
                    <ul>
                        <?php
                        $sections = $this->db->get_where('sections', array('class_id' => $class['id']))->result_array();
                        foreach($sections as $section){
                            echo '<li>'.$section['name'].'</li>';
                        }
                        ?>
                    </ul>
                </td>
                <td>
                    <div class="dropdown text-center">
                        <button type="button" class="btn btn-sm btn-icon btn-rounded btn-outline-secondary dropdown-btn dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item" onclick="rightModal('<?php echo site_url('modal/popup/class/sections/'.$class['id'])?>', '<?php echo get_phrase('sctions'); ?>');"><?php echo get_phrase('sections'); ?></a>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item" onclick="rightModal('<?php echo site_url('modal/popup/class/edit/'.$class['id'])?>', '<?php echo get_phrase('update_class'); ?>');"><?php echo get_phrase('edit'); ?></a>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item" onclick="confirmModal('<?php echo route('manage_class/delete/'.$class['id']); ?>', showAllClasses)"><?php echo get_phrase('delete'); ?></a>
                        </div>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
    <?php include APPPATH.'views/backend/empty.php'; ?>
<?php endif; ?>
