<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://sitesafe-wp.com
 * @since             1.0.0
 * @package           Site_Safe
 *
 * @wordpress-plugin
 * Plugin Name:       Site Safe
 * Plugin URI:        https://sitesafe-wp.com
 * Description:       Site Safe plugin is the easiest way to secure All Media Files and NFT uploads.
 * Version:           1.0.0
 * Author:            Jeffrey Shepard
 * Author URI:        https://profiles.wordpress.org/42techskylight/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       site-safe
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
define( 'SITE_SAFE_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-site-safe-activator.php
 */
function activate_site_safe() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-site-safe-activator.php';
	Site_Safe_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-site-safe-deactivator.php
 */
function deactivate_site_safe() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-site-safe-deactivator.php';
	Site_Safe_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_site_safe' );
register_deactivation_hook( __FILE__, 'deactivate_site_safe' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-site-safe.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_site_safe() {

	$plugin = new Site_Safe();
	$plugin->run();

}
run_site_safe();
