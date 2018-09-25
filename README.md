# WP Repeating Task Runner

A WordPress plugin framework for executing iterating/repeating commands in the WordPress backend.


## Usage

Create a class with the following template:

```php
<?php

use Tomodomo\Plugin\RepeatingTaskRunner\TaskInterface;

class MyTask implements TaskInterface
{
    /**
     * Unique slug for your task
     *
     * @var string
     */
    public $slug = 'my-task';

    /**
     * Friendly name of your task
     *
     * @var string
     */
    public $name = 'My Task';

    /**
     * Execute the command
     *
     * @param int $start
     * @param int $iterations
     *
     * @return bool
     */
    public function execute(int $start, int $iterations)
    {
        // Your code here

        return true;
    }
}
```

To register the command, do the following:

```php
<?php

add_filter('Tomodomo\Plugin\RepeatingTaskRunner\tasks', function ($tasks) {
    $tasks->addCommand(new MyCommand);

    return $tasks;
});
```

## Notes

General implementation notes and tips:

+ This plugin is in development, and the API is subject to change.
+ This plugin is intended for environments where "better" alternatives (wp-cli solutions, direct database manipulation, etc.) are not possible or could be problematic.
+ Your command is responsible for determining how it iterates, and what the meaning of an 'iteration' is at all.
+ Strive for statelessness.

## About Tomodomo

Tomodomo is a creative agency for magazine publishers. We use custom design and technology to speed up your editorial workflow, engage your readers, and build sustainable subscription revenue for your business.

Learn more at [tomodomo.co](https://tomodomo.co) or email us: [hello@tomodomo.co](mailto:hello@tomodomo.co)

## License & Conduct

This project is licensed under the terms of the MIT License, included in `LICENSE.md`.

All open source Tomodomo projects follow a strict code of conduct, included in `CODEOFCONDUCT.md`. We ask that all contributors adhere to the standards and guidelines in that document.

Thank you!
