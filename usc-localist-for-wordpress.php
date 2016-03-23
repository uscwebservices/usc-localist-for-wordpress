<?php

/**
 * Plugin Name:       USC Localist for WordPress
 * Plugin URI:        https://bitbucket.org/uscwebservices/usc-localist-for-wordpress
 * Description:       Localist API Shortcodes for WordPress
 * Version:           1.1.6
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
define( 'USC_LFWP__VERSION', '1.1.6' );

// set a global variable for the path to the plugin
define( 'USC_LFWP__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// set timezone to Los Angeles for strtotime functions
date_default_timezone_set('America/Los_Angeles');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-usc-localist-for-wordpress-activator.php
 */
function activate_USC_Localist_For_Wordpress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-usc-localist-for-wordpress-activator.php';
	USC_Localist_For_Wordpress_Activator::activate();
}

register_activation_hook( __FILE__, 'activate_USC_Localist_For_Wordpress' );


/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-usc-localist-for-wordpress-deactivator.php
 */
function deactivate_USC_Localist_For_Wordpress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-usc-localist-for-wordpress-deactivator.php';
	USC_Localist_For_Wordpress_Deactivator::deactivate();
}

register_deactivation_hook( __FILE__, 'deactivate_USC_Localist_For_Wordpress' );


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
function run_USC_Localist_For_Wordpress() {

	$plugin = new USC_Localist_For_Wordpress();
	$plugin->run();

}
run_USC_Localist_For_Wordpress();

?>
