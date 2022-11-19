<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://https://facebook.com/rejutpi
 * @since      1.0.0
 *
 * @package    Ajax_Fast_Publication_Post
 * @subpackage Ajax_Fast_Publication_Post/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Ajax_Fast_Publication_Post
 * @subpackage Ajax_Fast_Publication_Post/includes
 * @author     Reedwanul Haque <reedwanultpi@gmail.com>
 */
class Ajax_Fast_Publication_Post_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'ajax-fast-publication-post',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
