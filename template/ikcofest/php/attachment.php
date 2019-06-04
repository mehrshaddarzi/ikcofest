<?php

/* Used Before wp_media_handle_upload */
//remove_all_filters( 'intermediate_image_sizes_advanced' );
//add_filter( 'intermediate_image_sizes_advanced', '_remove_default_images' );

/**
 * Remove All size Without 'thumbnail'
 *
 * @param $sizes
 * @return mixed
 */
function _remove_default_images( $sizes ) {
	foreach ( wp_get_list_image_size() as $image_size ) {
		if ( $image_size != "thumbnail" ) {
			unset ( $sizes[ $image_size ] );
		}
	}

	return $sizes;
}

/**
 * Get list image size site
 *
 * @return array
 */
function wp_get_list_image_size() {
	global $_wp_additional_image_sizes;
	$sizes = array();
	foreach ( get_intermediate_image_sizes() as $_size ) {
		if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
			$sizes[] = $_size;
		} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
			$sizes[] = $_size;
		}
	}
	return $sizes;
}