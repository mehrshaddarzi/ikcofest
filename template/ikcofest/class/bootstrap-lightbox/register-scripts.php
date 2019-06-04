<?php

function Register_lightbox_bootstrap(){
	
	//if ( is_single() )
	//wp_enqueue_script('lightbox-js', get_template_directory_uri() . '/class/bootstrap-lightbox/prettyphoto/js/jquery.prettyPhoto.js', array('jquery'), '', false);

	//if ( is_single() )
	//wp_enqueue_script('lightbox-work-js', get_template_directory_uri() . '/class/bootstrap-lightbox/work.lightbox.js', array('jquery','lightbox-js'), '', false);
	
	
	if (is_single() || is_page())
	wp_enqueue_style('lightbox-style', get_stylesheet_directory_uri() . '/class/bootstrap-lightbox/prettyphoto/css/prettyPhoto.css', 'style');

}	
add_action( 'wp_enqueue_scripts', 'Register_lightbox_bootstrap');

//For active pretty photo single image
function prettyphoto_rel_replace ($content)
{	global $post;
	$pattern = "/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
  	$replacement = '<a$1href=$2$3.$4$5 rel="prettyPhoto[%LIGHTID%]"$6>';
    $content = preg_replace($pattern, $replacement, $content);
	$content = str_replace("%LIGHTID%", $post->ID, $content);
    return $content;
}
add_filter('the_content', 'prettyphoto_rel_replace');