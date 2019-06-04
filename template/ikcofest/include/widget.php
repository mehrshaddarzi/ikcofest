<?php
// Declare sidebar widget zone
add_action( 'widgets_init', 'WPTemplate_widgets_init' );

function WPTemplate_widgets_init() {
	
register_sidebar(array(
		'name' => 'سایدبار اصلی سایت',
    	'id'   => 'sidebar-right-widgets',
    	'description'   => 'محل ابزارک های سایدبار',
			
'before_widget' => '',
'before_title' => '',
'after_title' => '',
'after_widget' => ''
		
		) );
	
}



//Add Option For Widget
function kk_in_widget_form($t,$return,$instance){
    $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '', 'boxstyle' => '') );
   
  //  if ( !isset($instance['texttest']) ) $instance['texttest'] = null;
	
	 if ( !isset($instance['boxstyle']) ) $instance['boxstyle'] = null;
	 if ( !isset($instance['icon']) ) $instance['icon'] = 'fa-chevron-left';
	// if ( !isset($instance['margin']) ) $instance['margin'] = null;
	//if ( !isset($instance['backcolor']) ) $instance['backcolor'] = null;
	//if ( !isset($instance['titlecolor']) ) $instance['titlecolor'] = null;
	//if ( !isset($instance['bordercolor']) ) $instance['bordercolor'] = null;
    ?>
	 
	 
<br>
<hr>
<h3><b>تنظیمات نمایشی ابزارک</b></h3>
<p>
        <label for="<?php echo $t->get_field_id('boxstyle'); ?>">سبک نمایش</label>
        <select style="margin-right:25px;" id="<?php echo $t->get_field_id('boxstyle'); ?>" name="<?php echo $t->get_field_name('boxstyle'); ?>">
            <option <?php selected($instance['boxstyle'], 'style1');?> value="style1">استایل شماره یک</option>
        </select>
</p>
<p>
  <label for="<?php echo $t->get_field_id('icon'); ?>">آیکون</label>
   <input type="text" style="text-align:left; direction:ltr; margin-right:25px;" name="<?php echo $t->get_field_name('icon'); ?>" id="<?php echo $t->get_field_id('icon'); ?>" value="<?php echo $instance['icon'];?>" />
   <span style="display:block;margin:4px;"><a style='text-decoration:none;' href='http://fontawesome.io/cheatsheet/' target='_blank'>نمایش لیست آیکون فونت</a></span>
</p>
    <?php
    $retrun = null;
    return array($t,$return,$instance);
}

function kk_in_widget_form_update($instance, $new_instance, $old_instance){
    //$instance['width'] = isset($new_instance['width']);
    $instance['boxstyle'] = $new_instance['boxstyle'];
   // $instance['texttest'] = strip_tags($new_instance['texttest']);
    //$instance['backcolor'] = strip_tags($new_instance['backcolor']);
    $instance['icon'] = strip_tags($new_instance['icon']);
    //$instance['margin'] = strip_tags($new_instance['margin']);
    //$instance['titlecolor'] = strip_tags($new_instance['titlecolor']);
    //$instance['bordercolor'] = strip_tags($new_instance['bordercolor']);
    return $instance;
}

function kk_dynamic_sidebar_params($params){
    global $wp_registered_widgets;
    $widget_id = $params[0]['widget_id'];
    $widget_obj = $wp_registered_widgets[$widget_id];
    $widget_opt = get_option($widget_obj['callback'][0]->option_name);
    $widget_num = $widget_obj['params'][0]['number'];
	
	
	//Get instance Widget
	//$backcolor = $widget_opt[$widget_num]['backcolor'];
	//$titlecolor = $widget_opt[$widget_num]['titlecolor'];
	$icon = $widget_opt[$widget_num]['icon'];
	//$bordercolor = $widget_opt[$widget_num]['bordercolor'];
	//$margin = $widget_opt[$widget_num]['margin'];
	
	//$params[0]['before_widget']
	//$params[0]['before_title']
	//$params[0]['after_title']
	//$params[0]['after_widget']
	
	//Style 1
	if ($widget_opt[$widget_num]['boxstyle'] =="style1") {
		
		$wow = array(1 => 'Up',2 => 'Down',3 => 'Right',4 => 'Left');
		$rand_keys = array_rand($wow, 1);
		
		$before_widget = "";
		if ($widget_opt[$widget_num]['title'] =="") { $before_widget = '<div class="panel-body wow fadeIn'.$wow[$rand_keys].'" data-wow-delay="1.2s"><div class="matn">'; }
		$params[0]['before_widget'] ='<div class="panel panel-default" id="'.$widget_id.'">'.$before_widget;
		
		if ($icon =="") { $icon = 'fa-chevron-left'; }
		$params[0]['before_title'] ='<div class="panel-heading">
    <h3 class="panel-title wow fadeInUp" data-wow-delay="1s"><i class="fa '.$icon.'"></i> ';
		//$params[0]['after_title'] ='</h3></div><div class="panel-body wow fadeIn'.$wow[$rand_keys].'" data-wow-delay="1.2s"><div class="matn">';
		$params[0]['after_title'] ='</h3></div><div class="panel-body"><div class="matn">';
		
	$params[0]['after_widget'] = '
	</div>
</div>
</div>
	';
	}
	
    return $params;
}


//Add input fields(priority 5, 3 parameters)
add_action('in_widget_form', 'kk_in_widget_form',5,3);
//Callback function for options update (priorität 5, 3 parameters)
add_filter('widget_update_callback', 'kk_in_widget_form_update',5,3);
//add class names (default priority, one parameter)
add_filter('dynamic_sidebar_params', 'kk_dynamic_sidebar_params');

?>