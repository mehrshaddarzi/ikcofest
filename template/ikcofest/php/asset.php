<?php
/* Add Icon site */
add_action( 'wp_head', 'add_icon_site' );
function add_icon_site() {
	echo '<link rel="icon" href="' . home_url() . '/favicon.ico">' . "\n";
}

/**
 * Remove Block Library
 */
add_action( 'wp_enqueue_scripts', 'remove_block_css', 100 );
function remove_block_css() {
	wp_dequeue_style( 'wp-block-library' ); // Wordpress core
	wp_dequeue_style( 'wp-block-library-theme' ); // Wordpress core
	wp_dequeue_style( 'wc-block-style' ); // WooCommerce
	wp_dequeue_style( 'storefront-gutenberg-blocks' ); // Storefront theme
}

/**
 * Dequeue jQuery Migrate script in WordPress.
 */
add_filter( 'wp_default_scripts', 'isa_remove_jquery_migrate' );
function isa_remove_jquery_migrate( &$scripts ) {
	if ( ! is_admin() ) {
		$scripts->remove( 'jquery' );
		$scripts->add( 'jquery', false, array( 'jquery-core' ) );
	}
}

/**
 * Use Custom Url For JQuery
 */
add_action( 'wp', 'wps_replace_jquery' );
function wps_replace_jquery() {
	if ( ! is_admin() ) {
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', get_template_directory_uri() . '/dist/js/jquery.js', false, '1.11.3' );
		wp_enqueue_script( 'jquery' );
	}
}

/* Add Css and javascript site */
add_action( 'wp_enqueue_scripts', 'register_theme_assets' );
function register_theme_assets() {

	//Get Page Type
	$type = 'single';
	if ( is_home() || is_front_page() ) {
		$type = 'home';
	}
	if ( is_category() ) {
		$type = 'cat';
	}
	if ( is_404() ) {
		$type = '404';
	}
	$application = array(
		'url'   => home_url(),
		'token' => wp_create_nonce( 'wp_rest' ),
		'page'  => array(
			'type' => $type
		)
	);
	if ( get_queried_object_id() > 0 ) {
		$application['page']['id'] = get_queried_object_id();
	}

	// Add Css and Js
	wp_enqueue_style( 'app', get_template_directory_uri() . '/dist/css/app.min.css', 'style', '1.0.3' );
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/dist/js/bootstrap.min.js', array( 'jquery' ), '1.0.1', false );
	wp_enqueue_script( 'bootstrap-datepicker', get_template_directory_uri() . '/dist/js/bootstrap-datapicker/bootstrap-datepicker.min.js', array( 'jquery' ), '1.0.0', false );
	wp_enqueue_script( 'bootstrap-datepicker-persian', get_template_directory_uri() . '/dist/js/bootstrap-datapicker/bootstrap-datepicker.fa.min.js', array( 'jquery' ), '1.0.0', false );
	wp_enqueue_script( 'app', get_template_directory_uri() . '/dist/js/app.min.js', array( 'jquery' ), '1.0.3', true );
	wp_localize_script( 'app', 'application', $application );
}

/**
 * Remove ID From Js and Css
 */
add_filter( 'style_loader_tag', 'html5_style_tag' );
add_filter( 'script_loader_tag', 'html5_style_tag' );
function html5_style_tag( $tag ) {
	$tag = preg_replace( '~\s+type=["\'][^"\']++["\']~i', '', $tag );
	$tag = preg_replace( '~\s+id=["\'][^"\']++["\']~i', '', $tag );
	$tag = str_replace( ' media=\'all\' /', '', $tag );
	$tag = str_replace( '  ', ' ', $tag );
	return $tag;
}

/**
 * Dynamic Version For Debug
 */
add_filter( 'style_loader_src', 'sdt_remove_ver_css_js', 9999, 2 );
add_filter( 'script_loader_src', 'sdt_remove_ver_css_js', 9999, 2 );
function sdt_remove_ver_css_js( $src, $handle ) {
	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
		$handles_with_version = [ 'style' ]; // <-- Adjust to your needs!
		if ( strpos( $src, 'ver=' ) && ! in_array( $handle, $handles_with_version, true ) ) {
			$exp    = explode( "ver=", $src );
			$exp[1] = time();
			$src    = implode( 'ver=', $exp );
			//if Removed Version
			//$src = remove_query_arg( 'ver', $src );
		}
	}

	return $src;
}

/**
 * Clean WordPress Js
 */
remove_action('rest_api_init', 'wp_oembed_register_route');
remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
remove_action('wp_head', 'wp_oembed_add_host_js');
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
remove_action( 'wp_head', 'wp_resource_hints', 2 );