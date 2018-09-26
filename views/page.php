<div class="wrap">
    <h2>Repeating Task Runner</h2>

    <form method="post" action="admin-post.php" id="repeating-task-runner">
        <p>Executes a given task with a starting offset and iteration count</p>

        <table class="form-table">
            <tr>
                <th><label for="repeating-task-runner-task">Select a task<label></th>
                <td>
                    <select id="repeating-task-runner-task" name="repeating-task-runner-task">
                        <?php if (empty($tasks)) : ?>
                            <option value="" selected disabled>No tasks registered</option>
                        <?php else : ?>
                            <option value="" <?php echo $selectDefaultOption; ?> disabled>Select a task to run</option>
                        <?php endif; ?>

                        <?php foreach ($tasks as $task) : ?>
                            <option value="<?php echo $task['slug']; ?>" <?php echo $task['selected']; ?>>
                                <?php echo $task['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="repeating-task-runner-start">Start at</label></th>
                <td>
                    <input type="number" min="0" name="repeating-task-runner-start" id="repeating-task-runner-start" value="<?php echo $start; ?>">
                </td>
            </tr>
            <tr>
                <th><label for="repeating-task-runner-iterations">Iterations to execute</label></th>
                <td>
                    <input type="number" min="1" name="repeating-task-runner-iterations" id="repeating-task-runner-iterations" value="<?php echo $iterations; ?>">
                </td>
            </tr>
            <tr>
                <th><label for="repeating-task-runner-continue">Continue executing automatically</label></th>
                <td>
                    <input type="checkbox" name="repeating-task-runner-continue" id="repeating-task-runner-continue" value="1" <?php echo $continue; ?>>
                </td>
            </tr>
        </table>

        <p>
            <?php submit_button('Execute Task', 'primary', 'execute', false); ?>

            <?php if ($continue) : ?>
                 <input type="button" value="Stop Auto-Execution" class="button secondary" id="repeating-task-runer-pause">
            <?php endif; ?>
        </p>

        <?php wp_nonce_field('repeating-task-runner'); ?>
        <input type="hidden" name="action" value="repeating-task-runner">
    </form>
</div>
