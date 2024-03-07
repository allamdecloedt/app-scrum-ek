<?php
$student_details = $this->db->get_where('students', array('user_id' => $this->session->userdata('user_id')))->row_array();
$book_issues = $this->crud_model->get_book_issues_by_student_id($student_details['id'])->result_array(); ?>
<?php if (count($book_issues) > 0): ?>
    <table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%">
        <thead class="thead-dark">
            <tr>
                <th><?php echo get_phrase('book_name'); ?></th>
                <th><?php echo get_phrase('issue_date'); ?></th>
                <th><?php echo get_phrase('student'); ?></th>
                <th><?php echo get_phrase('class'); ?></th>
                <th><?php echo get_phrase('status'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($book_issues as $book_issue):
                $book_details = $this->crud_model->get_book_by_id($book_issue['book_id']);
                $student_details = $this->user_model->get_student_details_by_id('student', $book_issue['student_id']);
                $class_details = $this->crud_model->get_class_details_by_id($book_issue['class_id'])->row_array();
                ?>
                <tr>
                    <td><?php echo $book_details['name']; ?></td>
                    <td>
                        <?php echo date('D, d/M/Y', $book_issue['issue_date']); ?>
                    </td>
                    <td>
                        <?php echo $student_details['name']; ?><br> <small style="font-size: 10px; color: #9E9E9E;"><?php echo get_phrase('student_code'); ?>: <?php echo $student_details['code']; ?></small>
                    </td>
                    <td>
                        <?php echo $class_details['name']; ?>
                    </td>
                    <td>
                        <?php if ($book_issue['status']): ?>
                            <i class="mdi mdi-circle text-success"></i> <?php echo get_phrase('returned'); ?>
                        <?php else: ?>
                            <i class="mdi mdi-circle text-disable"></i> <?php echo get_phrase('pending'); ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
<?php include APPPATH.'views/backend/empty.php'; ?>
<?php endif; ?>
