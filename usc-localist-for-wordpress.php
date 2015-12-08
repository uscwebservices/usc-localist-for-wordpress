<?php
/*
Plugin Name: USC Localist for WordPress
Plugin URI: http://bitbucket.org/uscwebservices/
Description: Localist API Widgets and Shortcodes for WordPress
Version: 1.0
Author: USC Web Services
Author URI: http://bitbucket.org/uscwebservices/
Text Domain: usc-usc-localist-for-wordpress
License: MIT
*/

// Block direct requests
defined( 'ABSPATH' ) or die( 'sorry, no tampering' );

/*
	Match the Plugin version.
*/

define( 'USC_LFWP__VERSION', '0.2.2' );
define( 'USC_LFWP__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( USC_LFWP__PLUGIN_DIR . 'inc/config.php' );
require_once( USC_LFWP__PLUGIN_DIR . 'inc/functions.php' );
require_once( USC_LFWP__PLUGIN_DIR . 'admin/admin.php' );
require_once( USC_LFWP__PLUGIN_DIR . 'widgets/widget.localist.php' );
require_once( USC_LFWP__PLUGIN_DIR . 'shortcodes/shortcodes.localist.php' );


?>
