<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://yousefifar.dev
 * @since      1.0.0
 *
 * @package    Wp_Tag_Transporter
 * @subpackage Wp_Tag_Transporter/inc
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Tag_Transporter
 * @subpackage Wp_Tag_Transporter/inc
 * @author     Daniel Yousefi Far <daniyousefifar@gmail.com>
 */
class Wp_Tag_Transporter_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-tag-transporter',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
