# VPM Bulk Commands

A hacky framework for executing 'bulk' commands in the WordPress backend.

Create a class with the following template:

```php
<?php

class MyCommand {
	/**
	 * Must be `sanitize_title` safe
	 */
	public $slug = 'my-command';

	/**
	 * Users see this
	 */
	public $name = 'My Command';

	/**
	 * Must be named 'execute'
	 */
	public function execute($start, $iterations) {
		// Your code here

		return true;
	}
}
```

To register the command, do the following:

```php
<?php

add_action('vpm_bulk_register', function () {
	vpm_bulk_commands()->addCommand(new MyCommand);
});
```

## Notes

This plugin is in development, and the API is subject to change.

This plugin is intended for environments where better alternatives (wp-cli solutions, direct database manipulation, etc.) are not possible or could be problematic.

Your command is responsible for determining how it iterates, and what the meaning of an 'iteration' is at all.

Strive for statelessness.

## License & Conduct

This project is licensed under the terms of the MIT License, included in `LICENSE.md`.

All Van Patten Media Inc. open source projects follow a strict code of conduct, included in `CODEOFCONDUCT.md`. We ask that all contributors adhere to the standards and guidelines in that document.

Thank you!
