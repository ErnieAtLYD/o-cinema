<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/ErnieAtLYD
 * @since             1.0.0
 * @package           Wp_Agiletix
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress AgileTix Plugin
 * Plugin URI:        https://github.com/ErnieAtLYD/wp-agiletx
 * Description:       Integrates AgileTix XML data & The Events Calendar info.
 * Version:           1.0.0
 * Author:            Ernie Hsiung
 * Author URI:        https://github.com/ErnieAtLYD
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-agiletix
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-agiletix-activator.php
 */
function activate_wp_agiletix() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-agiletix-activator.php';
	Wp_Agiletix_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-agiletix-deactivator.php
 */
function deactivate_wp_agiletix() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-agiletix-deactivator.php';
	Wp_Agiletix_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_agiletix' );
register_deactivation_hook( __FILE__, 'deactivate_wp_agiletix' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-agiletix.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_agiletix() {

	$plugin = new Wp_Agiletix();
	$plugin->run();

}
run_wp_agiletix();
