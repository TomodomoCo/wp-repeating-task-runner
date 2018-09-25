<?php

namespace Tomodomo\Plugin\RepeatingTaskRunner;

class Framework extends Base
{
    /**
     * @var array
     */
    public $tasks;

    /**
     * Register a task
     *
     * @param object $task
     *
     * @return void
     */
    public function addTask($task)
    {
        if (($this->tasks[$task->slug] ?? false) !== false) {
            throw new \Exception('Task slug already exists.');
        }

        $this->tasks[$task->slug] = $task;

        return;
    }

    /**
     * Get a task for a given slug
     *
     * @param string $slug
     *
     * @return object
     */
    public function getTask(string $slug)
    {
        $tasks = $this->getTasks();

        if (($tasks[$slug] ?? false) === false) {
            throw new \Exception('Task does not exist.');
        }

        return $tasks[$slug];
    }

    /**
     * Get the registered tasks
     *
     * @return array
     */
    public function getTasks()
    {
        // Make the command list filterable and pass it on
        apply_filters('Tomodomo\Plugin\RepeatingTaskRunner\tasks', $this);

        return $this->tasks;
    }
}
