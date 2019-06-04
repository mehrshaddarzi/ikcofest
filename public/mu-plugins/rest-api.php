<?php
/**
 * Plugin Name: WordPress REST API Management
 * Description: A WordPress Plugin For REST API Management
 * Version:     1.0.0
 * License:     MIT
 *
 * @package wp-cli-application
 */

# Remove All WordPress Default Rest API Route# Removed All WordPress Rest API Route\n";
add_filter( 'rest_endpoints', function ( $endpoints ) {
	return $endpoints = array();
} );

# Remove Action in WordPress Theme
remove_action( 'wp_head', 'rest_output_link_wp_head' );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'template_redirect', 'rest_output_link_header' );

# Change WordPress Rest API Prefix
add_filter( 'rest_url_prefix', function ( $slug ) {
	return 'P0E8lNE8lcfgg651frtddsrtr965594df';
} );
