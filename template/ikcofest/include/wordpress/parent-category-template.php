<?php

/* Use Parent Category Template For Sub category */
add_action('template_redirect', 'inherit_cat_template');
function inherit_cat_template() {
if (is_category()) {
$catid = get_query_var('cat');
if ( file_exists(TEMPLATEPATH . '/category-' . $catid . '.php') ) {
include( TEMPLATEPATH . '/category-' . $catid . '.php');
exit;
}
$cat = &get_category($catid);
$parent = $cat->category_parent;
while ($parent){
$cat = &get_category($parent);
if ( file_exists(TEMPLATEPATH . '/category-' . $cat->cat_ID . '.php') ) {
include (TEMPLATEPATH . '/category-' . $cat->cat_ID . '.php');
exit;
}
$parent = $cat->category_parent;
}
}
}