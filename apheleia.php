<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              minns.io
 * @since             1.0.0
 * @package           Apheleia
 *
 * @wordpress-plugin
 * Plugin Name:       Apheleia
 * Plugin URI:        https://github.com/Johnogram/Apheleia
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            John-O-Gram
 * Author URI:        minns.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       apheleia
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-apheleia-activator.php
 */
function activate_apheleia() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-apheleia-activator.php';
	Apheleia_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-apheleia-deactivator.php
 */
function deactivate_apheleia() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-apheleia-deactivator.php';
	Apheleia_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_apheleia' );
register_deactivation_hook( __FILE__, 'deactivate_apheleia' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-apheleia.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_apheleia() {

	$plugin = new Apheleia();
	$plugin->run();

}
run_apheleia();
