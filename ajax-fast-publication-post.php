<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://facebook.com/rejutpi
 * @since             1.0.0
 * @package           Ajax_Fast_Publication_Post
 *
 * @wordpress-plugin
 * Plugin Name:       Ajax Fast Publication Post
 * Plugin URI:        https://https://facebook.com/rejutpi
 * Description:       This post for publication (custom post type).Here will custom meta box for publication pdf url & for publisher. Here you will get custom publish option. Then frontend we will give shortcode option for live search & other filtering options.
 * Version:           1.0.0
 * Author:            Reedwanul Haque
 * Author URI:        https://https://facebook.com/rejutpi
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ajax-fast-publication-post
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
define( 'AJAX_FAST_PUBLICATION_POST_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ajax-fast-publication-post-activator.php
 */
function activate_ajax_fast_publication_post() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ajax-fast-publication-post-activator.php';
	Ajax_Fast_Publication_Post_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ajax-fast-publication-post-deactivator.php
 */
function deactivate_ajax_fast_publication_post() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ajax-fast-publication-post-deactivator.php';
	Ajax_Fast_Publication_Post_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ajax_fast_publication_post' );
register_deactivation_hook( __FILE__, 'deactivate_ajax_fast_publication_post' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ajax-fast-publication-post.php';



/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ajax_fast_publication_post() {

	$plugin = new Ajax_Fast_Publication_Post();
	$plugin->run();

}
run_ajax_fast_publication_post();
