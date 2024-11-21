<div class="container">
    <h3>Quiz Results</h3>
    <p><?php echo get_phrase('you_got') . ' ' . $total_correct_answers . ' ' . get_phrase('out_of') . ' ' . $total_questions . ' ' . get_phrase('correct'); ?>.</p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th><?php echo get_phrase('question'); ?></th>
                <th><?php echo get_phrase('correct_answers'); ?></th>
                <th><?php echo get_phrase('submit_answers'); ?></th>
                <th><?php echo get_phrase('status'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($submitted_quiz_info as $info): 
            
            ?>
            <tr>
                <td><?php echo $info['question_title']; ?></td>
                <td><?php echo implode(', ', json_decode($info['correct_answers'])); ?></td>
                <td><?php echo implode(', ', json_decode($info['submitted_answers'])); ?></td>
                <td><?php echo $info['submitted_answer_status'] ? 'Correct' : 'Incorrect'; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
