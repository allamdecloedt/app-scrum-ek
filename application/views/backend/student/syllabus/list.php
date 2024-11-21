<?php
$school_id = school_id();
if (isset($class_id) && isset($section_id)):
    $syllabuses = $this->db->get_where('syllabuses', array('class_id' => $class_id, 'section_id' => $section_id, 'session_id' => active_session()))->result_array();
    if(count($syllabuses) > 0):?>
    <table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%">
        <thead>
            <tr style="background-color: #313a46; color: #ababab;">
                <th><?php echo get_phrase('title'); ?></th>
                <th><?php echo get_phrase('syllabus'); ?></th>
                <th><?php echo get_phrase('subject'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($syllabuses as $syllabus):?>
                <tr>
                    <td><?php echo $syllabus['title']; ?></td>
                    <td><a href="<?php echo base_url('uploads/syllabus/'.$syllabus['file']); ?>" class="btn btn-info mdi mdi-download" download><?php echo get_phrase('download'); ?></a></td>
                    <td><?php echo $this->db->get_where('subjects', array('id' => $syllabus['subject_id']))->row('name'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <?php include APPPATH.'views/backend/empty.php'; ?>
    <?php endif; ?>
<?php else: ?>
    <?php include APPPATH.'views/backend/empty.php'; ?>
<?php endif; ?>
