<?php
/**
 * USC Localist for WordPress Plugin Class.
 *
 * @package    Usc_Localist_For_Wordpress
 * @author     USC Web Services <webhelp@usc.edu>
 */

/**
 * Plugin Name:       USC Localist for WordPress
 * Plugin URI:        https://github.com/uscwebservices/usc-localist-for-wordpress
 * Description:       Localist API Shortcodes for WordPress
 * Version:           1.4.2
 * Author:            USC Web Services
 * Author URI:        https://itservices.usc.edu/webservices/
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       usc-localist-for-wordpress
 * Domain Path:       /languages
 */

// Block direct requests.
defined( 'ABSPATH' ) || die( 'sorry, no tampering' );

// Match the Plugin version.
define( 'USC_LFWP__VERSION', '1.4.0' );

// Set a global variable for the path to the plugin.
define( 'USC_LFWP__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// Set timezone to Los Angeles for strtotime functions.
date_default_timezone_set( 'America/Los_Angeles' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-usc-localist-for-wordpress-activator.php
 */
function activate_usc_localist_for_wordpress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-usc-localist-for-wordpress-activator.php';
	$plugin = new USC_Localist_For_Wordpress_Activator();
	$plugin->activate();
}

register_activation_hook( __FILE__, 'activate_usc_localist_for_wordpress' );


/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-usc-localist-for-wordpress-deactivator.php
 */
function deactivate_usc_localist_for_wordpress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-usc-localist-for-wordpress-deactivator.php';
	$plugin = new USC_Localist_For_Wordpress_Deactivator();
	$plugin->deactivate();
}

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
	$plugin->run();

}
run_usc_localist_for_wordpress();
