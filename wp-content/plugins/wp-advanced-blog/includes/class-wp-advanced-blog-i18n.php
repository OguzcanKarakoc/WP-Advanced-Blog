<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://maxencode.com
 * @since      1.0.0
 *
 * @package    Wp_Advanced_Blog
 * @subpackage Wp_Advanced_Blog/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Advanced_Blog
 * @subpackage Wp_Advanced_Blog/includes
 * @author     MaxEncode <oguzcan.karakoc@maxencode.com>
 */
class Wp_Advanced_Blog_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-advanced-blog',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
