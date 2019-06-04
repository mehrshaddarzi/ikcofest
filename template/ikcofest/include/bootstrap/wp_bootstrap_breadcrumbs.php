<?php
// -------------------
// breadcrumbs bootstrap for wordpress
// -------------------
function bootstrap_breadcrumbs($home_name) {
global $gcb_option;

$showOnHome = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
$delimiter = '<li>'; // delimiter between crumbs
$home = $home_name; // text for the 'Home' link
$showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
$before = '<li class="active">'; // tag before the current crumb
$after = '</li>'; // tag after the current crumb

global $post;
$homeLink = get_bloginfo('url');

if (is_home() || is_front_page()) {

if ($showOnHome == 1) echo '<ol class="breadcrumb"><li><a href="' . $homeLink . '">' . $home . '</a></li></ol>';

} else {

echo '<ol class="breadcrumb"><li><i class="fa fa-home"></i> <a href="' . $homeLink . '">' . $home . '</a> </li> ';

if ( is_category() ) {
$thisCat = get_category(get_query_var('cat'), false);
if ($thisCat->parent != 0) { $parents = get_category_parents($thisCat->parent, TRUE, '</li><li>'); $sub = substr($parents, 0, strlen($parents) -9 ); echo $delimiter.$sub.$after;  }
echo $before . '' . single_cat_title('', false) . '' . $after;


} elseif ( is_search() ) {
echo $before . $gcb_option['search-title'].' : "' . get_search_query() . '"' . $after;

} elseif ( is_day() ) {
echo '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . '</li> ';
echo '<li><a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . '</li> ';
echo $before . get_the_time('d') . $after;

} elseif ( is_month() ) {
echo '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . '</li> ';
echo $before . get_the_time('F') . $after;

} elseif ( is_year() ) {
echo $before . get_the_time('Y') . $after;

} elseif ( is_single() && !is_attachment() ) {
if ( get_post_type() != 'post' ) {
$post_type = get_post_type_object(get_post_type());
$slug = $post_type->rewrite;
echo '<li><a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a></li>';
if ($showCurrent == 1) echo ' ' . $delimiter . '</li> ' . $before . get_the_title() . $after;
} else {

$thisCat = get_the_category(); 
$cat = $thisCat[0];
$parents = get_category_parents($cat, TRUE, '</li><li>'); $sub = substr($parents, 0, strlen($parents) -9 ); echo $delimiter.$sub.$after;
//echo $before . get_the_title() . $after;	with title
//echo $before;	

//$cat = array_reverse(get_the_category()); 
//$cats = get_category_parents($cat, TRUE, '' . $delimiter . '');
//if ($showCurrent == 0) $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
//echo $cats;

//foreach( $cat as $category ) {
//echo $delimiter.'<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . esc_html( $category->name ) . '">' . esc_html( $category->name ) . '</a>'.$after;
//}

//if ($showCurrent == 1) echo $before . get_the_title() . $after;
}

} elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
$post_type = get_post_type_object(get_post_type());
echo $before . $post_type->labels->singular_name . $after;

} elseif ( is_attachment() ) {
$parent = get_post($post->post_parent);
$cat = get_the_category($parent->ID); $cat = $cat[0];
echo get_category_parents($cat, TRUE, ' ' . $delimiter . '</li> ');
echo '<li><a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>';
if ($showCurrent == 1) echo ' ' . $delimiter . '</li> ' . $before . get_the_title() . $after;

} elseif ( is_page() && !$post->post_parent ) {
if ($showCurrent == 1) echo $before . get_the_title() . $after;

} elseif ( is_page() && $post->post_parent ) {
$parent_id = $post->post_parent;
$breadcrumbs = array();
while ($parent_id) {
$page = get_page($parent_id);
$breadcrumbs[] = '<li><a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
$parent_id = $page->post_parent;
}
$breadcrumbs = array_reverse($breadcrumbs);
for ($i = 0; $i < count($breadcrumbs); $i++) {
echo $breadcrumbs[$i];
if ($i != count($breadcrumbs)-1) echo ' ' . $delimiter . '</li> ';
}
if ($showCurrent == 1) echo ' ' . $delimiter . '</li> ' . $before . get_the_title() . $after;

} elseif ( is_tag() ) {
echo $before . 'برچسب پست : "' . single_tag_title('', false) . '"' . $after;

} elseif ( is_author() ) {
global $author;
$userdata = get_userdata($author);
echo $before . 'آرشیو بر اساس ' . $userdata->display_name . $after;

} elseif ( is_404() ) {
echo $before . 'یافت نشد' . $after;
}

if ( get_query_var('paged') ) {
if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
echo ' صفحه ' . get_query_var('paged');
if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
}

echo '</ol>';
//echo '<div class="clearfix"></div>';

}
} // end dimox_breadcrumbs()