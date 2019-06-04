<?php
/*
Plugin Name: wp-admin-ui
Plugin URI: http://realwp.ir
Description: Admin Ui wordpress
Author: Mehrshad Darzi souteh
Version: 1.0
Author URI: http://realwp.ir
*/

/* Load Add Action Once*/
require_once( 'action_once.php' );

/* include file */
require_once( 'AdminBar.php' );
require_once( 'Dashboard.php' );
require_once( 'MenuBar.php' );
require_once( 'LoginPage.php' );
require_once( 'UI.php' );

/* Base Url Plugin */
define( 'URL_ADMIN_IU', plugin_dir_url( __FILE__ ) . 'asset' );

/* Config */
define( 'BASE_COLOR_SCHEME_ADMIN', 'black' );
$GLOBALS['LOGIN_PAGE_LOGO'] = array(
	'url'    => URL_ADMIN_IU . '/login.png',
	'width'  => '226',
	'height' => '102',
	'icon'   => home_url( 'favicon.ico' )
);

/* Load class */
$admin_ui_adminbar  = new AdminBar;
$admin_ui_menubar   = new MenuBar;
$admin_ui_dashboard = new Dashboard;
$admin_ui           = new Ui;
$admin_login_page   = new LoginPage;


// Remove Custom Menu
add_action( 'admin_init', 'wpse_243070_hide_menu' );
function wpse_243070_hide_menu() {
	remove_menu_page( 'wp-parsi-settings' );
}

// Show All Menu and Submenu List
add_action( 'admin_init', function () {
//			global $submenu, $menu;
//			echo "<pre>";
//			var_dump($menu);
//			exit;
	/**
	 * array (size=7)
	 * 0 => string 'تنظیمات پارسی' (length=25)
	 * 1 => string 'manage_options' (length=14)
	 * 2 => string 'wp-parsi-settings' (length=17)
	 * 3 => string 'تنظیمات پارسی' (length=25)
	 * 4 => string 'menu-top toplevel_page_wp-parsi-settings menu-top-first menu-top-last' (length=69)
	 * 5 => string 'toplevel_page_wp-parsi-settings' (length=31)
	 * 6 => string 'dashicons-admin-site' (length=20)
	 */
} );

/* Redirect the user logging in to a custom admin page. */
add_action( 'wp_login', 'new_dashboard_home', 10, 2 );
function new_dashboard_home( $username, $user ) {
	wp_redirect( admin_url( 'admin.php?page=festival' ), 301 );
	exit;
}

// Remove Admin color Select
function admin_color_scheme() {
	global $_wp_admin_css_colors;
	$_wp_admin_css_colors = 0;
}
add_action('admin_head', 'admin_color_scheme');