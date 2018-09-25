<?php

namespace Tomodomo\Plugin\RepeatingTaskRunner;

class Base
{
    /**
     * Make the class container available
     *
     * @param $container
     *
     * @return void
     */
    public function __construct($container)
    {
        $this->container = $container;

        return;
    }
}
