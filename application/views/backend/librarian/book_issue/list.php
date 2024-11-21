<?php $book_issues = $this->crud_model->get_book_issues($date_from, $date_to)->result_array(); ?>
<?php if (count($book_issues) > 0): ?>
    <table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%">
        <thead class="thead-dark">
            <tr>
                <th><?php echo get_phrase('book_name'); ?></th>
                <th><?php echo get_phrase('issue_date'); ?></th>
                <th><?php echo get_phrase('student'); ?></th>
                <th><?php echo get_phrase('class'); ?></th>
                <th><?php echo get_phrase('status'); ?></th>
                <th><?php echo get_phrase('option'); ?></th>
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
                    <td>
                        <div class="dropdown text-center">
                            <button type="button" class="btn btn-sm btn-icon btn-rounded btn-outline-secondary dropdown-btn dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <?php if (!$book_issue['status']): ?>
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item" onclick="rightModal('<?php echo site_url('modal/popup/book_issue/edit/'.$book_issue['id'])?>', '<?php echo get_phrase('update_book_issue_information'); ?>');"><?php echo get_phrase('edit'); ?></a>
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item" onclick="confirmModal('<?php echo route('book_issue/return/'.$book_issue['id']); ?>', showAllBookIssues )"><?php echo get_phrase('return_this_book'); ?></a>
                                <?php endif; ?>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item" onclick="confirmModal('<?php echo route('book_issue/delete/'.$book_issue['id']); ?>', showAllBookIssues )"><?php echo get_phrase('delete'); ?></a>
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
