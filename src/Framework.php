<?php

namespace Tomodomo\Plugin\RepeatingTaskRunner;

class Framework
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
        if (!($this->tasks[$task->slug] ?? false)) {
            throw new \Exception('Task slug already exists.');
        }

        $this->tasks[$task->slug] = $task;

        return;
    }

    /**
     */
    public function getTasks()
    {
        // Make the command list filterable and pass it on
        apply_filters('Tomodomo\Plugin\RepeatingTaskRunner\tasks', $this);

        return $this->tasks;
    }
}
