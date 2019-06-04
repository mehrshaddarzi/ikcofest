<?php

class Ui {

	//Admin Bar varible
	protected static $instance = NULL;
	public $wp_admin_bar;
	public $my_admin_page;
	const DESIGN_COMPANY_SITE = "http://ayanderohan.net";
	const DESIGN_COMPANY = "آینده روشن ایرانیان";
	const PAGE_NOT_SHOW_SYSTEM = false; //this optional for page not show system
	const CAT_NOT_DELETE = [];

	public $PRIMARY_COLOR;
	public $SECOND_COLOR;
	public $color_scheme;
	const ColorScheme = [
		'blue' => ['#3498db','#2581bf'],
		'black' => ['#565656','#565656'],
		'red' => ['#d22323','#800000'],
		'green' => ['#35B621','#35B621'],
	];
	//const PRIMARY_COLOR = "#3498db";
	//const SECOND_COLOR = "#2581bf";
	/* Green 35B621 107300 */
	/*  black 565656 040303 */
	/*  red  d22323 800000 */

	public function __construct() {

		$this->PRIMARY_COLOR = self::ColorScheme[BASE_COLOR_SCHEME_ADMIN][0];
		$this->SECOND_COLOR = self::ColorScheme[BASE_COLOR_SCHEME_ADMIN][1];
		$this->wp_admin_bar = & $GLOBALS['wp_admin_bar'];
		$this->my_admin_page = & $GLOBALS['my_admin_page'];


		add_action_once( 'admin_enqueue_scripts', array( $this, 'load_custom_wp_admin_style') );
		add_filter('clean_url',array( $this,'unclean_url'),10,3);
		add_action_once('admin_footer', array( $this,'do_action_footer_sript'));
		add_action_once('jquery_admin_footer', array( $this,'exit_jquery_sweet_alert'));
		add_filter( 'update_footer', array( $this,'my_footer_version'), 11 );
		add_filter('admin_footer_text', array( $this,'remove_footer_admin'));
		add_filter('admin_title', array( $this,'my_admin_title'), 10, 2);
		add_action_once( 'admin_enqueue_scripts', array( $this, 'admin_theme_style') );
		add_filter( 'update_footer', '__return_empty_string', 11 );
		add_action_once('admin_head', array( $this, 'remove_help_tabs'));
		add_action_once('admin_head', array( $this, 'add_icon_site'));
		add_action_once('current_screen', array( $this, 'add_default_icon_wrap'));
		add_action_once('admin_footer', array( $this,'loading_page_div'));
		add_action_once('admin_footer', array( $this,'add_checkbox_all_in_post'));

		// Page Not Show
		if(self::PAGE_NOT_SHOW_SYSTEM ===true) {
			if(get_option("hide_page_list") ===false) { update_option("hide_page_list",array()); }
			add_filter( 'parse_query', array( $this,'exclude_pages_from_admin') );
		}

		//if(count(self::CAT_NOT_DELETE) >1) { add_action_once("init", [ $this, "not_allow_remove_category"]); }
	}


	/*
	 * get_instance for Get Varible
	 */
	public static function get()
	{
		if ( NULL === self::$instance )
			self::$instance = new self;
		return self::$instance;
	}

	/*
	 * Add Icon to Admin Wordpress
	 */
	public function add_icon_site() {
		echo '<link rel="icon" href="'.$GLOBALS['LOGIN_PAGE_LOGO']['icon'].'" type="image/png">'."\n";
	}

	//remove help tab admin
	function remove_help_tabs() {
		$screen = get_current_screen();
		$screen->remove_help_tabs();
	}

	//color scheme wordpress Admin
	public function admin_theme_style() {

		$fau_primary    = Ui::get()->PRIMARY_COLOR;
		$fau_primary = apply_filters( 'primary_color_wp_admin', $fau_primary );

		$fau_secondary    = Ui::get()->SECOND_COLOR;
		$fau_secondary = apply_filters( 'second_color_wp_admin', $fau_secondary );

		wp_enqueue_style( 'fau-admin-bar-style', URL_ADMIN_IU . '/base/fau_styles_adminbar.css' );
		wp_enqueue_style( 'fau-admin-style', URL_ADMIN_IU . '/base/fau_styles_admin.css' );

		//Load Bootstrap ( Grid + Pagination + Button )
		wp_enqueue_style( 'admin-bootstrap', URL_ADMIN_IU . '/base/bootstrap.min.css' );

		//admin bar
		$admin_bar_css = "
    #wpadminbar {
      background: {$fau_primary};
    }
    #wpadminbar .menupop .ab-sub-wrapper,#wpadminbar .shortlink-input {
      background: {$fau_primary};
    }
    .top-tooltip {
    background: {$fau_primary} !important;
    }
  ";
		wp_add_inline_style( 'fau-admin-bar-style', $admin_bar_css );

		//theme
		$admin_css = "
    a,
    input[type=checkbox]:checked:before,
    .view-switch a.current:before {
      color: {$fau_primary}
    }
    a { text-decoration:none; }
    a:hover {
      color: {$fau_secondary}
    }

    #adminmenu li a:focus div.wp-menu-image:before,#adminmenu li.opensub div.wp-menu-image:before,#adminmenu li:hover div.wp-menu-image:before {
      color:  {$fau_primary}!important;
    }

    #adminmenu .wp-has-current-submenu .wp-submenu .wp-submenu-head,#adminmenu .wp-menu-arrow,#adminmenu .wp-menu-arrow div,#adminmenu li.current a.menu-top,#adminmenu li.wp-has-current-submenu a.wp-has-current-submenu,.folded #adminmenu li.current.menu-top,.folded #adminmenu li.wp-has-current-submenu,/* Hover actions */
    #adminmenu li.menu-top:hover,#adminmenu li.opensub>a.menu-top,#adminmenu li>a.menu-top:focus {
      background: {$fau_primary};
      background:#FFF
    }

    #adminmenu .opensub .wp-submenu li.current a,#adminmenu .wp-submenu li.current,#adminmenu .wp-submenu li.current a,#adminmenu .wp-submenu li.current a:focus,#adminmenu .wp-submenu li.current a:hover,#adminmenu a.wp-has-current-submenu:focus+.wp-submenu li.current a,#adminmenu .wp-submenu .wp-submenu-head,/* Dashicons */
    #adminmenu .current div.wp-menu-image:before,#adminmenu .wp-has-current-submenu div.wp-menu-image:before,#adminmenu a.current:hover div.wp-menu-image:before,#adminmenu a.wp-has-current-submenu:hover div.wp-menu-image:before,#adminmenu li.wp-has-current-submenu:hover div.wp-menu-image:before, #adminmenu li:hover div.wp-menu-image:before {
      color: {$fau_primary}
    }

    #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu div.wp-menu-name {
      color: {$fau_primary}
    }
  
    #adminmenu div.wp-menu-name {
    padding: 6px 0;
}
    .wrap .add-new-h2,.wrap .add-new-h2:active {
      background: {$fau_primary};
    }
    .wrap .add-new-h2:hover {
      background: {$fau_secondary}
    }
   /* div.updated { border-right: 5px solid  {$fau_primary}; } */
    .wp-core-ui .button:hover,.wp-core-ui .button-secondary:hover,.wp-core-ui .button-primary {
      background: {$fau_primary};
    }
    .wp-core-ui .button-primary.focus, .wp-core-ui .button-primary.hover, .wp-core-ui .button-primary:focus, .wp-core-ui .button-primary:hover {
      background: {$fau_primary};
      box-shadow: 0 1px 0 {$fau_primary}, 0 0 2px 1px {$fau_primary};
    }
    .composer-switch a,.composer-switch a:visited,.composer-switch a.wpb_switch-to-front-composer,.composer-switch a:visited.wpb_switch-to-front-composer,.composer-switch .logo-icon {
      background-color: {$fau_primary}!important;
    }
    .composer-switch .vc-spacer, .composer-switch a.wpb_switch-to-composer:hover, .composer-switch a:visited.wpb_switch-to-composer:hover, .composer-switch a.wpb_switch-to-front-composer:hover, .composer-switch a:visited.wpb_switch-to-front-composer:hover {
      background-color:  {$fau_secondary}!important;
    }
    .wrap h2 {
    color: {$fau_primary} !important;
    }
    .wrap h2 span {
    color: #23282d;
    }

  ";

		//Hide Menu
		/*$admin_css .='
        li#toplevel_page_all-in-one-seo-pack-aioseop_class, li#toplevel_page_bws_panel, li#toplevel_page_wp-postratings-postratings-manager, li#toplevel_page_lockdown-wp-admin, li#toplevel_page_edit-post_type-acf, .column-seotitle, .column-seodesc {
            display: none;
        }
        ';*/

		
		if(isset($_GET['post']) and $_GET['post'] =="108") {}

		wp_add_inline_style( 'fau-admin-style', $admin_css );
	}


	// Hide Admin Page From Admin
	public function exclude_pages_from_admin($query) {
		global $pagenow,$post_type;
		/*
		 * && $post_type =='post'
		 * if for only page
		 */
		if (is_admin() && $pagenow=='edit.php') {
			$query->query_vars['post__not_in'] = get_option("hide_page_list");
		}
	}

	//Add to Hide Page/post list
	public function add_to_hide_page_list($post_id, $type ='hide') {
		$opt_page = get_option("hide_page_list");
		if($type =="hide") {
			if(!in_array($post_id, $opt_page)) {
				$opt_page[] = $post_id;
			}
		} elseif($type =="remove") { //Remove From Hide List
			unset($opt_page[array_flip($opt_page)[$post_id]]);
		} elseif($type =="trash") { //Remove All item
			$opt_page = [];
		}
		update_option("hide_page_list",$opt_page);
	}


	//Add Css To Admin All Page
	public function load_custom_wp_admin_style() {

		//font awesome
		wp_register_style( 'awesome-font', URL_ADMIN_IU . '/font-awesome-4.7.0/css/font-awesome.min.css', false );
		wp_enqueue_style( 'awesome-font' );

		//load Pace
		wp_enqueue_script( 'pace-loading', URL_ADMIN_IU . '/pace/pace.min.js?data-pace-options=yes' );

		//load Swet Alert 2
		wp_register_style( 'sweet-alert-2', URL_ADMIN_IU . '/sweetalert2/sweetalert2.min.css', false );
		wp_enqueue_style( 'sweet-alert-2' );
		wp_enqueue_script( 'sweet-alert-2', URL_ADMIN_IU . '/sweetalert2/sweetalert2.min.js' );

		//wp_deregister_script( 'thickbox' );
		//wp_deregister_style( 'thickbox' );
	}

	/* Add option to load Pace in data attribute*/
	public function unclean_url( $good_protocol_url, $original_url, $_context){
		if (false !== strpos($original_url, 'data-pace-options')){
			remove_filter('clean_url', array( $this, 'unclean_url'),10,3);
			$url_parts = parse_url($good_protocol_url);
			if(is_admin()) {
				return $url_parts['scheme'] . '://' . $url_parts['host'] . $url_parts['path'] . "' data-pace-options='{ \"ajax\": false }";
			} else {
				return $url_parts['host'] . $url_parts['path'] . "' data-pace-options='{ \"ajax\": false }";
			}
		}
		return $good_protocol_url;
	}

	//admin footer Script do_action
	public function do_action_footer_sript(){
		?>
        <script>
            jQuery(document).ready(function($){
				<?php do_action('jquery_admin_footer'); ?>
            });
        </script>
		<?php
	}

	//add jquery exit From Admin
	public function exit_jquery_sweet_alert(){

		//Jquey Exit From Admin wordpress
		echo '
       $(document).on("click","li#wp-admin-bar-exit-left-menu a",function() {
           swal({
              title: \'خروج از مدیریت\',
              text: "آیا واقعا میخواهید از مدیریت خارج شوید ؟",
              type: \'warning\',
              showCancelButton: true,
              confirmButtonText: \'خروج\',
              cancelButtonText: \'بازگشت\',
            }).then(function () {
                window.location.href = "'.str_replace("&amp;","&", wp_logout_url() ).'";
            });
        });
       ';

		//remove text (jam kardan fehrest)
		echo 'jQuery("span.collapse-button-label").html("");';

		//change name setting page
		echo 'jQuery("button#show-settings-link").html("نمایش");';

		//add line after wrap h1
		echo '
if (jQuery("div.notice").length ==0) { 
if (jQuery("a.page-title-action").length >0) {
jQuery( "<div class=\'seprate_title\'></div>" ).insertAfter( "a.page-title-action" );
} else {
jQuery( "<div class=\'seprate_title\'></div>" ).insertAfter( ".wrap h1" );
}
 }
';

		//show loading after change page
		/*
		 * disable for any page
		 */
//        $submit_button = "";
//        if(isset($_GET['page']) and $_GET['page'] =="theme_option") {
//            $submit_button = ", input[type=submit]";
//        } //disable button in Redux Page
		echo 'jQuery("ul#adminmenu a, input[name=publish]").click(function(){ 
               jQuery("#wpcontent, #wpadminbar, #adminmenumain").css("opacity", "0.3");
                jQuery("div.loading_page").fadeIn("slow"); 
            });';

		//tooltip top button
		echo 'jQuery("span#exit_view").hover(function(){jQuery(".exit_tooltip").css("visibility","visible").hide().fadeTo("slow",1)},function(){jQuery(".exit_tooltip").fadeTo("slow",0),jQuery("#wpcontent").css("opacity","1")}),jQuery("span#tooltip_view").hover(function(){jQuery(".view_tooltip").css("visibility","visible").hide().fadeTo("slow",1)},function(){jQuery(".view_tooltip").fadeTo("slow",0)});';

		//icon in wrap h1
		if(has_action( 'adminpage_icon' )) {
			echo 'var page_name_wrap = jQuery( ".wrap h1" ).html();';
			echo 'jQuery( ".wrap h1" ).html("<i class=\'';
			do_action('adminpage_icon');
			echo ' wrap_h1_icon\'></i>" + page_name_wrap);';
		}

	}

	//change admin copyright
	public function remove_footer_admin ($text) {
		$text = ' طراحی و پشتیبانی : <a href="'.self::DESIGN_COMPANY_SITE.'" target="_blank">'.self::DESIGN_COMPANY.'</a> ';
		return $text;
	}
	public function my_footer_version() {return '';}

	//Admin title
	public function my_admin_title($admin_title, $title) {
		return get_bloginfo('name').' &bull; '.$title;
	}

	/*
	 * Add Default icon Wrap
	 */
	public function add_default_icon_wrap(){
		if (is_admin()) {
			$screen = get_current_screen();

			$array = [
				'dashboard' => 'fa-dashboard',
				'edit-post' => 'fa-pencil',
				'post' => 'fa-pencil',
				'edit-category' => 'fa-unlink',
				'edit-post_tag' => 'fa-tag',
				'upload' => 'fa-upload',
				'media' => 'fa-upload',
				'edit-page' => 'fa-pencil',
				'page' => 'fa-pencil',
				'edit-comments' => 'fa-comment',
				'themes' => 'fa-paint-brush',
				'widgets' => 'fa-th-large',
				'nav-menus' => 'fa-th-list',
				'plugins' => 'fa-plug',
				'users' => 'fa-users',
				'profile' => 'fa-user',
				'options-general' => 'fa-cog',
				'options-writing' => 'fa-cog',
				'options-reading' => 'fa-cog',
				'options-discussion' => 'fa-cog',
				'options-media' => 'fa-cog',
				'options-permalink' => 'fa-cog',

				//Extra page
				//'user_maharat' => 'fa-user'
			];
			foreach($array as $screen_id => $icon_wrap) {

				//Extrapage
				if (strlen(strstr($screen->id,'_page_')) >0) {
					if(strstr($screen->id,'_page_'.$screen_id)) {
						add_action_once('adminpage_icon', function() use ($screen_id, $array) {
							echo "fa {$array[$screen_id]}";
						});
					}
				}

				//base wordpress page
				if ($screen->id == $screen_id) {
					add_action_once('adminpage_icon', function() use ($screen_id, $array) {
						echo "fa {$array[$screen_id]}";
					});
				}
			}
		}
	}

	/*
	 * Add checkbox for all in Post
	 */
	public function add_checkbox_all_in_post()
	{
		if (is_admin()) {
			$screen = get_current_screen();
			if ($screen->id == "post") {

				echo '
<script>
jQuery(document).ready(function($){
  synchronize_child_and_parent_category($);
});
function synchronize_child_and_parent_category($) {
  $(\'#categorychecklist\').find(\'input\').each(function(index, input) {
    $(input).bind(\'change\', function() {
      var checkbox = $(this);
      var is_checked = $(checkbox).is(\':checked\');
      if(is_checked) {
        $(checkbox).parents(\'li\').children(\'label\').children(\'input\').attr(\'checked\', \'checked\');
      } else {
        $(checkbox).parentsUntil(\'ul\').find(\'input\').removeAttr(\'checked\');
      }
    });
  });
}
</script>
';
			}
		}


	}


	/*
	 * AdminNotice
	 */
	public function Notice($text, $model ="info" , $close_button = true, $style_extra = 'padding: 10px 1px;')
	{
		/*
		 * List of Model : error / warning / success / info
		 */
		return '
        <div class="notice notice-'.$model.''.($close_button ===true ? " is-dismissible" : "").'">
            <div style="'.$style_extra.'">
            '.$text.'
            </div>
        </div>
        ';
	}

	/*
	 * loading Page div
	 */
	public function loading_page_div()
	{
		echo '<div class="loading_page"> لطفا کمی صبر کنید ...</div>';
	}


	/*
	 * Not Allow Delete Category
	 */
	public function not_allow_remove_category() {
		return Block_Category::bootstrap(self::CAT_NOT_DELETE);
	}


	/*
	 * Jquery Redirect
	 */
	public function jqueryRedirect($url, $echo = false) {
		$text = '<SCRIPT language=JavaScript> this.location = "'.$url.'";  </SCRIPT>';
		if ($echo ===true) { echo $text; } else { return $text; }
	}

}