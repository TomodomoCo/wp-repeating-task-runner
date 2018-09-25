<?php

use Pimple\Container;
use Tomodomo\Plugin\RepeatingTaskRunner\Admin;
use Tomodomo\Plugin\RepeatingTaskRunner\Framework;

// Instantiate a Pimple container. Probably slightly overkill
// for our usecase, but it doesn't hurt either.
$container = new Container();

// Add the framework for adding and retrieving tasks
$container['framework'] = function ($c) {
	return new Framework($c);
};

// Add the admin area for the plugin
$container['admin'] = function ($c) {
	return new Admin($c);
};

// Initialise the admin
$container['admin']->init();
