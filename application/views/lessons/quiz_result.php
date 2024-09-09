<div class="row">
    <div class="col-lg-11">
        <div class="card text-white bg-quiz-result-info mb-3">
            <div class="card-body review-card">
                <h5 class="card-title"><?php echo get_phrase('review_the_course_materials_to_expand_your_learning'); ?>.
                </h5>
                <p class="card-text">
                    <?php echo get_phrase('you_got') . ' ' . $total_correct_answers . ' ' . get_phrase('out_of') . ' ' . $total_questions . ' ' . get_phrase('correct'); ?>
                    .
                </p>
            </div>
        </div>
    </div>
</div>

<?php foreach ($submitted_quiz_info as $each):
    $question_details = $this->lms_model->get_quiz_question_by_id($each['question_id'])->row_array();
    $options = json_decode($question_details['options']);
    $correct_answers = json_decode($each['correct_answers']);
    $submitted_answers = json_decode($each['submitted_answers']);
    ?>
    <div class="row mb-2">
        <div class="col-lg-11">
            <div class="card text-left bg-as-important row">
                <div class="col-12 card-body answers-body">
                    <div class="row ">
                        <h6 class="card-title mb-3 "><img class="answer_status_image"
                                src="<?php echo $each['submitted_answer_status'] == 1 ? base_url('assets/frontend/default/img/green-tick.png') : base_url('assets/frontend/default/img/red-cross.png'); ?>"
                                alt="" height="15px;"> <?php echo get_phrase("question") ?>:
                            <?php echo $question_details['title']; ?>
                        </h6>

                        <p class="card-text"> <strong><?php echo get_phrase("correct_answers"); ?>: </strong></p>
                        <?php for ($i = 0; $i < count($correct_answers); $i++): ?>
                            <p class="card-text row justify-content-end">
                                <svg class="col-auto d-flex justify-items-start" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#00922c"
                                    class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                </svg>

                                <span class="col-6 text-start"><?php echo $options[($correct_answers[$i] - 1)]; ?>
                            </p>
                        <?php endfor; ?>
                        <p class="card-text mt-3 "> <strong><?php echo get_phrase("submitted_answers"); ?>: </strong></p>
                        <p> <?php
                        $submitted_answers_as_csv = "";
                        for ($i = 0; $i < count($submitted_answers); $i++) {
                            $submitted_answers_as_csv .= $options[($submitted_answers[$i] - 1)] . ', ';
                        }
                        echo rtrim($submitted_answers_as_csv, ', ');
                        ?></p>

                    </div>

                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
