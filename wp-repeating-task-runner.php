<?php
/**
 * Plugin Name: Tomodomo › Repeating Task Runner
 * Plugin URI: https://www.vanpattenmedia.com/
 * Version: 3.0.0
 * Description: Execute custom commands in bulk in the WordPress admin area
 * Author: Tomodomo
 * Author URI: https://tomodomo.co/
 */

// Potentially load the Composer autoloader
if (file_exists('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
}

// Initialise the plugin
require_once 'init.php';
