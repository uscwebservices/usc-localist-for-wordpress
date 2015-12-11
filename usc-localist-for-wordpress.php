<?php
/*
Plugin Name: 	USC Localist for WordPress
Plugin URI:		http://bitbucket.org/uscwebservices/
Description: 	Localist API Shortcodes for WordPress
Version: 		1.0
Author: 		USC Web Services
Author URI: 	http://bitbucket.org/uscwebservices/
License:     	MIT
Domain Path: 	/languages
Text Domain: 	usc-localist-for-wordpress
*/

// block direct requests
defined( 'ABSPATH' ) or die( 'sorry, no tampering' );

// match the Plugin version.
define( 'USC_LFWP__VERSION', '1.0' );

// set a global variable for the path to the plugin
define( 'USC_LFWP__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class.usc-lfwp-activator.php
 */
function activate_USC_Localist_for_WordPress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class.usc-lfwp-activator.php';
	USC_Localist_for_WordPress_Activator::activate();
}

register_activation_hook( __FILE__, 'activate_USC_Localist_for_WordPress' );


/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class.usc-lfwp-deactivator.php
 */
function deactivate_USC_Localist_for_WordPress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class.usc-lfwp-deactivator.php';
	USC_Localist_for_WordPress_Deactivator::deactivate();
}

register_deactivation_hook( __FILE__, 'deactivate_USC_Localist_for_WordPress' );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class.usc-lfwp.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_USC_Localist_for_WordPress() {

	$plugin = new USC_Localist_for_WordPress;
	$plugin->run();


}
run_USC_Localist_for_WordPress();

?>
