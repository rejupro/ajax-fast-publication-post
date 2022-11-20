<?php

/**
 * Fired during plugin activation
 *
 * @link       https://https://facebook.com/rejutpi
 * @since      1.0.0
 *
 * @package    Ajax_Fast_Publication_Post
 * @subpackage Ajax_Fast_Publication_Post/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Ajax_Fast_Publication_Post
 * @subpackage Ajax_Fast_Publication_Post/includes
 * @author     Reedwanul Haque <reedwanultpi@gmail.com>
 */
class Ajax_Fast_Publication_Post_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
 		$table_name = $wpdb->prefix . 'fastpublication_publisher';
		$tableinsert = "CREATE TABLE `$table_name` ( `id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(151) NOT NULL , `email` VARCHAR(151) NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB"; 
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $tableinsert );
	}

}
