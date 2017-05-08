<?php
/**
 * Settings Instructions: Main
 *
 * @package	USC Localist For_Wordpress
 * @subpackage USC Localist For_Wordpress/instructions
 * @author	 USC Web Services <webhelp@usc.edu>
 */

/**
 * Get the url parameter 'tab'.
 *
 * @since   1.3.0
 * @return  string  Value of 'tab' url parameter (default 'api_options').
 */
function usc_lfwp_active_tab() {
	return isset( $_GET['tab'] ) ? $_GET['tab'] : 'api_options';
}

/**
 * Echo active tab class if 'tab' url parameter equals $tab_name.
 *
 * @since   1.3.0
 * @param   string $tab_name  URL parameter to check if matched.
 * @return  void              Nav tab acive class (default: '').
 */
function usc_lfwp_active_tab_class( $tab_name ) {
	echo usc_lfwp_active_tab() === $tab_name ? 'nav-tab-active' : '';
}

?>
<style type="text/css">

	/* headings */
	.usc_lfwp_wrap h1 { font-size: 2.5rem; }
	.usc_lfwp_wrap h2 { font-size: 2.25rem; }
	.usc_lfwp_wrap h3 { font-size: 1.75rem; }
	.usc_lfwp_wrap h4 { font-size: 1.3rem; }
	.usc_lfwp_wrap h5 { font-size: 1.0rem; }
	.usc_lfwp_wrap h6 { font-size: 0.7rem; }

	/* code style */
	.usc_lfwp_wrap pre {
		padding: 1rem;
		background: #23282d;
		color: #fff;
		white-space: pre-wrap;		/* css-3 */
		white-space: -moz-pre-wrap;	/* Mozilla, since 1999 */
		white-space: -pre-wrap;		/* Opera 4-6 */
		white-space: -o-pre-wrap;	/* Opera 7 */
		word-wrap: break-word;		/* Internet Explorer 5.5+ */
	}

	/* list style */
	.usc_lfwp_wrap ul {
		padding: .25rem 0 0 1rem;
	}

	.usc_lfwp_wrap table ul {
		padding: 0;
	}

</style>
<div class="usc_lfwp_wrap">

	<h1 id="settings-bookmark-usc-localist-for-wordpress">USC Localist for WordPress</h1>

	<div class="nav-tab-wrapper">
		<a href="?page=usc-localist-for-wordpress-admin&tab=api_options" class="nav-tab <?php usc_lfwp_active_tab_class( 'api_options' ); ?>">Shortcode Options</a>
		<a href="?page=usc-localist-for-wordpress-admin&tab=widget" class="nav-tab <?php usc_lfwp_active_tab_class( 'widget' ); ?>">Widget</a>
		<a href="?page=usc-localist-for-wordpress-admin&tab=templates" class="nav-tab <?php usc_lfwp_active_tab_class( 'templates' ); ?>">Templates</a>
		<a href="?page=usc-localist-for-wordpress-admin&tab=data" class="nav-tab <?php usc_lfwp_active_tab_class( 'data' ); ?>">Data</a>
		<a href="?page=usc-localist-for-wordpress-admin&tab=links" class="nav-tab <?php usc_lfwp_active_tab_class( 'links' ); ?>">Links</a>
		<a href="?page=usc-localist-for-wordpress-admin&tab=dates" class="nav-tab <?php usc_lfwp_active_tab_class( 'dates' ); ?>">Dates</a>
		<a href="?page=usc-localist-for-wordpress-admin&tab=photos" class="nav-tab <?php usc_lfwp_active_tab_class( 'photos' ); ?>">Photos</a>
		<a href="?page=usc-localist-for-wordpress-admin&tab=notes" class="nav-tab <?php usc_lfwp_active_tab_class( 'notes' ); ?>">Admin Notes</a>
	</div>

<?php
if ( 'api_options' === usc_lfwp_active_tab() ) {
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'instructions/instructions-usc-localist-for-wordpress-api-shortcode.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'instructions/instructions-usc-localist-for-wordpress-api-shortcode-custom.php';
}
if ( 'widget' === usc_lfwp_active_tab() ) {
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'instructions/instructions-usc-localist-for-wordpress-widget.php';
}
if ( 'templates' === usc_lfwp_active_tab() ) {
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'instructions/instructions-usc-localist-for-wordpress-templates.php';
}
if ( 'data' === usc_lfwp_active_tab() ) {
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'instructions/instructions-usc-localist-for-wordpress-data.php';
}
if ( 'links' === usc_lfwp_active_tab() ) {
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'instructions/instructions-usc-localist-for-wordpress-links.php';
}
if ( 'dates' === usc_lfwp_active_tab() ) {
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'instructions/instructions-usc-localist-for-wordpress-dates.php';
}
if ( 'photos' === usc_lfwp_active_tab() ) {
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'instructions/instructions-usc-localist-for-wordpress-photos.php';
}
if ( 'notes' === usc_lfwp_active_tab() ) {
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'instructions/instructions-usc-localist-for-wordpress-admin-notes.php';
}
