<?php

/**
 * Fired during plugin activation
 *
 * @link       https://sitesafe-wp.com
 * @since      1.0.0
 *
 * @package    Site_Safe
 * @subpackage Site_Safe/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Site_Safe
 * @subpackage Site_Safe/includes
 * @author     https://sitesafe-wp.com <info@sitesafe-wp.com>
 */
class Site_Safe_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		flush_rewrite_rules();
	}

}
