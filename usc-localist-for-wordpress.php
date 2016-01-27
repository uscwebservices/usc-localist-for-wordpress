<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://itservices.usc.edu/webservices/
 * @since             1.0.0
 * @package           Usc_Localist_For_Wordpress
 *
 * @wordpress-plugin
 * Plugin Name:       USC Localist for WordPress
 * Plugin URI:        https://bitbucket.org/uscwebservices/usc-localist-for-wordpress
 * Description:       Localist API Shortcodes for WordPress
 * Version:           1.0.0
 * Author:            USC Web Services
 * Author URI:        http://itservices.usc.edu/webservices/
 * License:           MIT
 * License URI:       http://opensource.org/licenses/MIT
 * Text Domain:       usc-localist-for-wordpress
 * Domain Path:       /languages
 */

// block direct requests
defined( 'ABSPATH' ) or die( 'sorry, no tampering' );

// match the Plugin version.
define( 'USC_LFWP__VERSION', '1.0.0' );

// set a global variable for the path to the plugin
define( 'USC_LFWP__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-usc-localist-for-wordpress-activator.php
 */
function activate_usc_localist_for_wordpress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-usc-localist-for-wordpress-activator.php';
	USC_Localist_for_WordPress_Activator::activate();
}

register_activation_hook( __FILE__, 'activate_usc_localist_for_wordpress' );


/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-usc-localist-for-wordpress-deactivator.php
 */
function deactivate_usc_localist_for_wordpress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-usc-localist-for-wordpress-deactivator.php';
	USC_Localist_for_WordPress_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_usc_localist_for_wordpress' );
register_deactivation_hook( __FILE__, 'deactivate_usc_localist_for_wordpress' );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-usc-localist-for-wordpress.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_usc_localist_for_wordpress() {

	$plugin = new USC_Localist_For_Wordpress();

}
run_usc_localist_for_wordpress();

?>
