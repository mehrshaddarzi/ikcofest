<?php
/*Get Page title*/
function get_page_title_wp($id) {
	$post_id=get_post($id); 
	return $post_id->post_title;
}

/*Get Template Directory Path*/
function get_theme_path($variable ="") {
	if ($variable =="yes") { 
	return get_bloginfo('template_directory'); } 
	else { echo get_bloginfo('template_directory'); }
}

/*Filter For Wp-title*/
function mysite_wp_title( $title, $sep ) {
	global $paged, $page;
	if ( is_feed() )
		return $title;
	$title .= get_bloginfo( 'name' );
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'twentytwelve' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'mysite_wp_title', 10, 2 );


/*Change Gravator class*/
add_filter('get_avatar','change_avatar_css');
function change_avatar_css($class) {
$class = str_replace("class='avatar", "class='site_gravatar", $class) ;
return $class;
}

?>