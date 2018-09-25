<?php
/**
 * Plugin Name: VPM Bulk Commands
 * Plugin URI: https://www.vanpattenmedia.com/
 * Version: 3.0.0
 * Description: Execute custom commands in bulk in the WordPress admin area
 * Author: Tomodomo
 * Author URI: https://tomodomo.co/
*/

// Register the options page in the menu
add_action('admin_menu', [$this, 'addPage']);

// Execute bulk commands
add_action('admin_post_repeating-task-runner', [$this, 'executeCommand']);
