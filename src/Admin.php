<?php

namespace Tomodomo\Plugin\RepeatingTaskRunner;

class Admin extends Base
{
    /**
     * @return void
     */
    public function init()
    {
        // Add the menu page
        add_action('admin_menu', [$this, 'addPage']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);

        // Run commands on POST
        add_action('admin_post_repeating-task-runner', [$this, 'executeTask']);

        return;
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
        $tasks = $this->container['framework']->getTasks();

        // Simplify the tasks into an array
        $tasks = array_map(function ($task) use ($slug){
            return [
                'slug'     => sanitize_title($task->slug),
                'name'     => esc_html($task->name),
                'selected' => ($task->slug === $slug) ? 'selected' : '',
            ];
        }, $tasks);

        $selectDefaultOption = empty($slug) ? 'selected' : '';

        // Load the template
        require trailingslashit(dirname(__DIR__)) . 'views/page.php';

        return;
    }

    /**
     * Enqueue JavaScript file for the admin page
     *
     * @return void
     */
    public function enqueueScripts()
    {
        wp_enqueue_script(
            'repeating-task-runner',
            plugins_url('assets/repeating-task-runner.js', dirname(__FILE__)),
            ['jquery'],
            false,
            true
        );

        return;
    }

    /**
     * Execute a task and redirect back to the admin page
     *
     * @return void
     */
    public function executeTask()
    {
        // Verify the nonce
        if (wp_verify_nonce($_POST['_wpnonce'], 'repeating-task-runner') === false) {
            wp_die('Nonce validation error. Something strange is going on… :(');
        }

        // Get the requested task slug
        $slug = sanitize_title($_POST['repeating-task-runner-task'] ?? '');

        // Try to retreivew the task
        try {
            $task = $this->container['framework']->getTask($slug);
        } catch (\Exception $e) {
            wp_die('The task you tried to execute has not been registered.');
        }

        // Clean up the submitted values
        $start      = absint($_POST['repeating-task-runner-start'] ?? 0);
        $iterations = absint($_POST['repeating-task-runner-iterations'] ?? 1);
        $continue   = absint($_POST['repeating-task-runner-continue'] ?? 0);

        // Execute the task
        try {
            $output = $task->execute($start, $iterations);
        } catch (\Exception $e) {
            // @todo provide a generic task exception class
            wp_die('Something went wrong while executing this task.');
        }

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
}
