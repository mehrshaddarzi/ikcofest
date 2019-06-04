<?php
/**
 * Plugin Name: Change Themes folder
 * Description: A WordPress Plugin For Change themes folder
 * Version:     1.0.0
 * License:     MIT
 *
 * @package wp-cli-application
 */
/*  if in Content_folder
register_theme_directory( '/template' );
register_theme_directory( ABSPATH . 'wp-includes/widgets' ); //@see wp-includes/theme.php:661
add_filter( 'theme_root', function () { return WP_CONTENT_DIR . '/template';  });
add_filter( 'theme_root_uri', function () { return content_url( 'template' ); }, 10, 1 );
*/

/* if in Main folder */
register_theme_directory( ABSPATH . '/template' );
register_theme_directory( ABSPATH . 'wp-includes/widgets' ); //@see wp-includes/theme.php:661
add_filter( 'theme_root', function () {
	return ABSPATH . '/template';
} );
add_filter( 'theme_root_uri', function () {
	return home_url( 'template' );
}, 10, 1 );