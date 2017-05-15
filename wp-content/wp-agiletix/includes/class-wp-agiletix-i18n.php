<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/ErnieAtLYD
 * @since      1.0.0
 *
 * @package    Wp_Agiletix
 * @subpackage Wp_Agiletix/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Agiletix
 * @subpackage Wp_Agiletix/includes
 * @author     Ernie Hsiung <e@erniehsiung.com>
 */
class Wp_Agiletix_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-agiletix',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
