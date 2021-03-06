<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://jumpoff.io/beta
 * @since             0.5.0
 * @package           Jumpoff
 *
 * @wordpress-plugin
 * Plugin Name:       JumpOff
 * Plugin URI:        http://jumpoff.io/beta
 * Description:       JumpOff is creative writing plugin that provides writing prompts and encourages freewriting.
 * Version:           0.5.0
 * Author:            Jesse Lee
 * Author URI:        http://jessequinnlee.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       jumpoff
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-jumpoff-activator.php
 */
function activate_jumpoff() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-jumpoff-activator.php';
	Jumpoff_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-jumpoff-deactivator.php
 */
function deactivate_jumpoff() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-jumpoff-deactivator.php';
	Jumpoff_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_jumpoff' );
register_deactivation_hook( __FILE__, 'deactivate_jumpoff' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-jumpoff.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.5.0
 */
function run_jumpoff() {

	$plugin = new Jumpoff();
	$plugin->run();

}
run_jumpoff();
