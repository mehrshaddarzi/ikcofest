<?php

// Disable Show Admin Bar
show_admin_bar( false );
add_filter( 'show_admin_bar', '__return_false' );

/* Register jQuery Scripts and CSS Styles */
include( get_template_directory() . '/php/asset.php' );

/* Edit Admin view Wordpress */
define( 'DESIGN_COMPANY', "آینده روشن ایرانیان" );
define( 'DESIGN_COMPANY_SITE', "#" );
define( 'PAGE_NOT_SHOW', '' );
define( 'CAT_NOT_DELETE', '' );
define( 'HIDE_CAT_ADMIN', '' );
include( 'include/wordpress/wordpress-admin.php' );

/* Get Wordpress Function for theme*/
include( 'include/wordpress/wordpress-class.php' );

/*Add Widget in Site*/
include( 'include/widget.php' );

/* Use Parent Category Template For Sub category */
include( 'include/wordpress/parent-category-template.php' );

/*include Template Function*/
include( 'include/wordpress/remove-row-gallery-only-4.php' );

// Load attachment Hook
include( 'php/attachment.php' );

// Ajax Form
include( 'php/ajax.php' );

// List Entry Page
include( 'php/admin/entry.php' );

/*Compress Image wp_handle_upload*/
function filter_site_upload_size_limit( $size ) {
	$size = 1024 * 50000;
	return $size;
}

add_filter( 'upload_size_limit', 'filter_site_upload_size_limit', 20 );

function my_prefix_regenerate_thumbnail_quality() {
	return 80;
}

add_filter( 'jpeg_quality', 'my_prefix_regenerate_thumbnail_quality' );

/* Bootstrap Component Function */
include( 'include/bootstrap/wp_bootstrap_breadcrumbs.php' );
include( 'include/bootstrap/wp_page_navi.php' );
include( 'class/bootstrap-lightbox/register-scripts.php' );
add_filter( 'the_content', 'wpse8170_add_custom_table_class' );
function wpse8170_add_custom_table_class( $content ) {
	return str_replace( '<table', '<table class="table table-bordered table-striped table-hover" ', $content );
}

/* Active */
add_theme_support( 'post-thumbnails' );
function wpdocs_theme_setup() {
	//add_image_size( 'thumb-owl', 205, 205, true );
	//add_image_size( 'thumb-150', 150, 150, true);

}

add_action( 'init', 'wpdocs_theme_setup' );

// Use all Pages as Home Page
add_filter( 'template_include', 'portfolio_page_template', 99 );
function portfolio_page_template( $template ) {
	//$template = locate_template( array( 'index.php' ) );
	return $template;
}


// Session Start
add_action( 'init', 'register_my_session' );
function register_my_session() {
	if ( ! session_id() ) {
		session_start();
	}
}