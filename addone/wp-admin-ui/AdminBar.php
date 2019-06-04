<?php
class AdminBar {

	//Admin Bar varible
	public $wp_admin_bar;

	public function __construct() {

		$this->wp_admin_bar = & $GLOBALS['wp_admin_bar'];

		/* remove */
		add_action_once( 'wp_before_admin_bar_render', array( $this, 'remove_admin_bar_item' ));

		/* Add item */
		add_action_once('admin_bar_menu', array( $this, 'admin_bar_exit_and_username'), 80);
		add_action_once( 'admin_bar_menu', array( $this,'show_time_top_bar'), 100 );

		//Remove Admin Bar
		add_filter( 'show_admin_bar', '__return_false' );
		remove_action( 'wp_footer', 'wp_admin_bar_render', 1000 );
	}

	/*
	 * Remove Admin_bar item
	 */
	function remove_admin_bar_item() {

		/* default Wordpress */
		$this->wp_admin_bar->remove_menu('wp-logo');          // Remove the WordPress logo
		$this->wp_admin_bar->remove_menu('about');            // Remove the about WordPress link
		$this->wp_admin_bar->remove_menu('wporg');            // Remove the WordPress.org link
		$this->wp_admin_bar->remove_menu('documentation');    // Remove the WordPress documentation link
		$this->wp_admin_bar->remove_menu('support-forums');   // Remove the support forums link
		$this->wp_admin_bar->remove_menu('feedback');         // Remove the feedback link
		$this->wp_admin_bar->remove_menu('site-name');        // Remove the site name menu
		$this->wp_admin_bar->remove_menu('view-site');        // Remove the view site link
		$this->wp_admin_bar->remove_menu('updates');          // Remove the updates link
		$this->wp_admin_bar->remove_menu('comments');         // Remove the comments link
		$this->wp_admin_bar->remove_menu('new-content');      // Remove the content link
		$this->wp_admin_bar->remove_menu('w3tc');             // If you use w3 total cache remove the performance link

		$this->wp_admin_bar->remove_menu('my-account');       // Remove the user details tab
		$this->wp_admin_bar->remove_menu('edit-profile');

		/* Plugin Extra */
		$this->wp_admin_bar->remove_menu('all-in-one-seo-pack');

	}

	/*
	 * Show Time in Admin Bar
	 */
	public function show_time_top_bar() {

		$this->wp_admin_bar->add_menu( array(
			'id' => 'show-time-top-bar',
			'parent' => 'top-secondary',
			'title' => $this->show_parsidate_top_bar(),
			'meta'   => array(
				'class'    => 'no-hover-topbar',
			),
		) );
	}
	public function show_parsidate_top_bar(){
		$t = '<script src="'.URL_ADMIN_IU.'/time.js"></script>';
		$t .=  '<span id="show_time_online">';
		$t .=  parsidate("l j F ماه Y","now","per")." , ";
		$t .=  '<span id="timespan"><script>show_time_online();</script></span>';
		$t .=  '</span>';
		return $t;
	}


	/*
	 * Show exit and User item in Admin Bar
	 */
	public function admin_bar_exit_and_username($admin_bar){
		global $wp_roles;

		$user_id      = get_current_user_id();
		$current_user = wp_get_current_user();
		$u = get_userdata($user_id);
		$role = array_shift($u->roles);

		if ( current_user_can( 'read' ) ) {
			$profile_url = get_edit_profile_url( $user_id );
		} elseif ( is_multisite() ) {
			$profile_url = get_dashboard_url( $user_id, 'profile.php' );
		} else {
			$profile_url = false;
		}

		if ( ! $user_id )
			return;

		/* Exit Left Menr */
		$admin_bar->add_menu( array(
			'id'    => 'exit-left-menu',
			'parent' => 'top-secondary',
			'title' => '<div class="top-tooltip exit_tooltip">خروج</div><span class="ab-icon dashicons dashicons-migrate" style="margin-left:0px;margin-top: -1px;" id="exit_view"></span>',
			'href'  =>  '#',
		));

		/* View in site */
		$admin_bar->add_menu( array(
			'id'    => 'show-site-left-menu',
			'parent' => 'top-secondary',
			'title' => '<div class="top-tooltip view_tooltip">نمایش وب سایت</div><span class="ab-icon dashicons dashicons-desktop" style="margin-left:0px;margin-top: -1px;" id="tooltip_view"></span>',
			'href'  =>  home_url(),
			'meta'  => array(
				'target' => '_blank',
			)
		));

		
		/* Filter Show User role Name */
		if($wp_roles->roles[$role]['name'] =='Super Admin') { $user_role = 'مدیر ارشد'; }
		elseif($wp_roles->roles[$role]['name'] =='Administrator') { $user_role = 'مدیر سایت'; }
		elseif($wp_roles->roles[$role]['name'] =='Editor') { $user_role = 'ویرایشگر'; }
		elseif($wp_roles->roles[$role]['name'] =='Subscriber') { $user_role = 'مشترک'; }
		elseif($wp_roles->roles[$role]['name'] =='Contributor') { $user_role = 'مشارکت کننده'; }
		elseif($wp_roles->roles[$role]['name'] =='Author') {
			$user_role = 'نویسنده';
		}
		
		$admin_bar->add_menu( array(
			'id'    => 'account-item',
			'title' => '<span class="ab-icon dashicons dashicons-admin-users" style="margin-left:0px;margin-top: 3px;padding-bottom: 0px;"></span> '.$current_user->user_firstname.' '.$current_user->user_lastname.' خوش آمدید ! <span class="label-bootstrap">'.$user_role. '</span>',
			'href'  => '#',
		));

    $admin_bar->add_menu( array(
        'id'    => 'edit-profile-sub-item',
        'parent' => 'account-item',
        'title' => '<span class="ab-icon dashicons-arrow-left" style="margin-left:0px;margin-top: -1px;"></span>'.'ویرایش مشخصات کاربری',
        'href'  => $profile_url,
        'meta'  => array(
	        'tabindex' => -1,
        ),
    ));

	}


}