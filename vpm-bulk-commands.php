<?php
/*
Plugin Name: VPM Bulk Commands
Version: 1.0.0
Description: Execute custom commands in bulk in the WordPress admin area
Author: Van Patten Media Inc.
Author URI: https://www.vanpattenmedia.com/
Plugin URI: https://www.vanpattenmedia.com/
*/

class VpmBulkCommands
{
	/**
	 */
	public $commands;

	/**
	 */
	private static $instance;

	/**
	 */
	public static function instance()
	{
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 */
	public function init()
	{
		// Define those variables
		$this->plugin_dir_url = trailingslashit( plugins_url( '', __FILE__ ) );
		$this->plugin_version = '1.0.0';

		// Register the options page in the menu
		add_action('admin_menu', [$this, 'addPage']);

		// Execute bulk commands
		add_action('admin_post_vpm-bulk-commands', [$this, 'executeCommands']);
	}

	/**
	 */
	public function executeCommands()
	{
		// Get the list of commands
		$commands = $this->getCommands();

		// Verify the nonce
		if (!wp_verify_nonce($_POST['_wpnonce'], 'vpm-bulk-commands')) {
			wp_die('Nonce validation error. Something strange is going on… :(');
		}

		// Get the submitted values
		$start      = intval($_POST['vpm-bulk-start']);
		$iterations = intval($_POST['vpm-bulk-iterations']);
		$slug       = sanitize_title($_POST['vpm-bulk-command']);

		// Get the command class
		$class = $commands[$slug];

		// Execute the command
		$output = $class->execute($start, $iterations);


		// Build args for the URL
		$args = [
			'page'                => 'vpm-bulk-commands',
			'vpm-bulk-start'      => $start + $iterations,
			'vpm-bulk-iterations' => $iterations,
			'vpm-bulk-command'    => $slug,
		];

		// Redirect
		wp_safe_redirect(add_query_arg($args, admin_url('tools.php')));
		return;
	}

	/**
	 */
	public function addPage()
	{
		add_submenu_page(
			'tools.php',
			'Bulk Commands',
			'Bulk Commands',
			'edit_posts',
			'vpm-bulk-commands',
			[$this, 'page']
		);
	}

	/**
	 */
	public function addCommand($class)
	{
		$this->commands[$class->slug] = $class;
	}

	/**
	 */
	public function getCommands()
	{
		do_action('vpm_register_command');

		return apply_filters('vpm_bulk_commands', $this->commands);
	}

	/**
	 */
	public function page()
	{
		echo '<div class="wrap">';
		echo '<h2>Bulk Commands</h2>';

		$slug       = '';
		$start      = 1;
		$iterations = 50;

		if (isset($_GET['vpm-bulk-start']))
			$start = intval($_GET['vpm-bulk-start']);

		if (isset($_GET['ecf-iterations']))
			$iterations = intval($_GET['vpm-bulk-iterations']);

		if (isset($_GET['vpm-bulk-command']))
			$slug = $_GET['vpm-bulk-command'];

		echo '<form method="post" action="admin-post.php" id="vpm-bulk-commands">';
		echo '<h3>Execute a bulk/iteratable task</h3>';
		echo '<p>Iterates progressively through a command</p>';
		echo '<table class="form-table">';
			echo '<tr>';
				echo '<th><label for="vpm-bulk-command">Select a command<label></th>';
				echo '<td>';
					echo '<select id="vpm-bulk-command" name="vpm-bulk-command">';
						$commands = $this->getCommands();
						foreach ( $commands as $command ) {
							if ( $command->slug === $slug ) {
								$selected = ' selected';
							}

							echo '<option value="' . $command->slug . '" ' . $selected . '>' . $command->name . '</option>';
						}
					echo '</select>';
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<th><label for="vpm-bulk-start">Start at</label></th>';
				echo '<td>';
					echo '<input type="textbox" name="vpm-bulk-start" id="vpm-bulk-start" value="' . $start . '">';
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<th><label for="vpm-bulk-iterations">Iterations to execute</label></th>';
				echo '<td>';
					echo '<input type="textbox" name="vpm-bulk-iterations" id="vpm-bulk-iterations" value="' . $iterations . '">';
				echo '</td>';
			echo '</tr>';
		echo '</table>';

		submit_button();
		wp_nonce_field('vpm-bulk-commands');
		echo '<input type="hidden" name="action" value="vpm-bulk-commands">';
		echo '</form>';

		echo '</div>';
	}

}

/**
 */
function vpm_bulk_commands() {
	return VpmBulkCommands::instance();
}

vpm_bulk_commands()->init();
