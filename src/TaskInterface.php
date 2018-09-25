<?php

namespace Tomodomo\Plugin\RepeatingTaskRunner;

interface TaskInterface
{
    /**
     * Execute the task
     *
     * @param int $start      Where to start the iteration
     * @param int $iterations How many iterations to execute
     *
     * @return bool
     */
    public function execute(int $start, int $iterations);
}
