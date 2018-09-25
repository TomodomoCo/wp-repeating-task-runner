<?php

namespace Tomodomo\Plugin\RepeatingTaskRunner;


class Admin
{
    /**
     * Execute a task
     *
     * @return void
     */
    public function executeTask()
    {
        // Get the list of tasks
        $tasks = $this->getTasks();

        // Verify the nonce
        if (!wp_verify_nonce($_POST['_wpnonce'], 'repeating-task-runner')) {
            wp_die('Nonce validation error. Something strange is going on… :(');
        }

        // Get the submitted values
        $start      = intval($_POST['repeating-task-runner-start']);
        $iterations = intval($_POST['repeating-task-runner-iterations']);
        $slug       = sanitize_title($_POST['repeating-task-runner-task']);
        $continue   = intval($_POST['repeating-task-runner-continue']);

        // Test for the task class
        if (!isset($tasks[$slug])) {
            wp_die('The task you tried to execute has not been registered.');
        }

        // Get the task class
        $task = $tasks[$slug];

        // Execute the task
        $output = $task->execute($start, $iterations);

        // Build args for the URL
        $args = [
            'page'                             => 'repeating-task-runner',
            'repeating-task-runner-start'      => $start + $iterations,
            'repeating-task-runner-iterations' => $iterations,
            'repeating-task-runner-task'       => $slug,
            'repeating-task-runner-continue'   => $continue,
        ];

        // Redirect back to the page
        wp_safe_redirect(add_query_arg($args, admin_url('tools.php')));
        exit;
    }

    /**
     * Register the menu page
     *
     * @return void
     */
    public function addPage()
    {
        add_submenu_page(
            'tools.php',
            'Repeating Task Runner',
            'Repeating Task Runner',
            'edit_posts',
            'repeating-task-runner',
            [$this, 'page']
        );

        return;
    }

    /**
     * Form layout for the task runner page
     *
     * @return void
     */
    public function page()
    {
        // Where to start iterating
        $start = intval($_GET['repeating-task-runner-start'] ?? 0);

        // Iterations to execute
        $iterations = absint($_GET['repeating-task-runner-iterations'] ?? 1);

        // Grab the slug
        $slug = sanitize_title($_GET['repeating-task-runner-task'] ?? '');

        // Continue automatically
        $continue = ($_GET['repeating-task-runner-continue'] ?? false) ? ' checked' : '';

        // Grab the available tasks
        $tasks = $this->getTasks();

        // Simplify the tasks into an array
        $tasks = array_map(function ($task) {
            return [
                'slug' => sanitize_title($task->slug),
                'name' => esc_html($task->name),
            ];
        }, $tasks);

        // Load the template
        require dirname(__DIR__) . '../views/page.php';

        return;
    }
}
