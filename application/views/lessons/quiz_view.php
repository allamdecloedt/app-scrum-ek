<?php
$quiz_questions = $this->lms_model->get_quiz_questions($lesson_details['id']);
$lesson_progress = lesson_progress($lesson_details['id']);
?>
<div id="quiz-body">
    <div class="" id="quiz-header">
        <?php echo get_phrase("quiz_title"); ?> : <strong><?php echo $lesson_details['title']; ?></strong><br>
        <?php echo get_phrase("number_of_questions"); ?> :
        <strong><?php echo count($quiz_questions->result_array()); ?></strong><br>
        <?php if (count($quiz_questions->result_array()) > 0): ?>
            <button type="button" name="button" class="btn btn-info mt-2 text-white" <?php if($lesson_progress == 1):?>  disabled <?php endif; ?>
                onclick="getStarted(1); ">
                <?php     if ($lesson_progress == 1) {
                                echo get_phrase("already_passed");
                            } else {
                                echo get_phrase("get_started");
                            } ?>
                </button>
                <?php     if ($lesson_progress == 1){?>
                <button type="button" name="button" class="btn btn-info mt-2 text-white" onclick="check_result(); ">
                <?php echo get_phrase("check_result");?>
                </button>
                <?php  } ?>
        <?php endif; ?>
    </div>

    <form class="" id="quiz_form" action="" method="post">
        <!-- Champ caché pour le jeton CSRF -->
    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
        <?php if (count($quiz_questions->result_array()) > 0): ?>
            <?php foreach ($quiz_questions->result_array() as $key => $quiz_question):
                $options = json_decode($quiz_question['options']);
                ?>
                <input type="hidden" name="lesson_id" value="<?php echo $lesson_details['id']; ?>">
                <div class="hidden"  id="question-number-<?php echo $key + 1; ?>">
                    <div class="row justify-content-center">
                        <div class="col-lg-4">
                            <div class="row card-body question-body align-items-center">
                                <h6 class="card-title"><?php echo get_phrase("question") . ' ' . ($key + 1); ?> :
                                    <strong><?php echo $quiz_question['title']; ?></strong>
                                </h6>

                            </div>
                            <div class="row align-items-center">
                                <button type="button" name="button" class=" quiz-button btn btn-info mt-2 mb-2 text-white"
                                    id="next-btn-<?php echo $quiz_question['id']; ?>" <?php if (count($quiz_questions->result_array()) == $key + 1): ?>onclick="submitQuiz()" <?php else: ?>onclick="showNextQuestion('<?php echo $key + 2; ?>') ;  " <?php endif; ?>
                                    disabled><?php echo count($quiz_questions->result_array()) == $key + 1 ? get_phrase("check_result") : get_phrase("submit_and_next"); ?></button>
                            </div>


                        </div>
                        <div class="col-lg-8">
                            <div class="card text-left quiz-card">
                            <div id="quiz-timer" class="text-right">
                                Temps restant : <span id="timer<?php echo $key + 1; ?>">00:15</span>
                            </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item quiz-options"><h5 class="text-capitalize"><?php echo get_phrase("Choose_your_answer")?></h5></li>
                                    <?php
                                    foreach ($options as $key2 => $option): ?>
                                        <li class="list-group-item quiz-options">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    name="<?php echo $quiz_question['id']; ?>[]" value="<?php echo $key2 + 1; ?>"
                                                    id="quiz-id-<?php echo $quiz_question['id']; ?>-option-id-<?php echo $key2 + 1; ?>"
                                                    onclick="enableNextButton('<?php echo $quiz_question['id']; ?>')">
                                                <label class="form-check-label"
                                                    for="quiz-id-<?php echo $quiz_question['id']; ?>-option-id-<?php echo $key2 + 1; ?>">
                                                    <?php echo $option; ?>
                                                </label>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </form>
</div>
<div id="quiz-result" class="text-left">

</div>