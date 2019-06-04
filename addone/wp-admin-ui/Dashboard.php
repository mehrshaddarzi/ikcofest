<?php
class Dashboard {

	public function __construct() {

		/* Option */
		remove_action('welcome_panel', 'wp_welcome_panel');
		add_filter('screen_layout_columns', array( $this, 'shapeSpace_screen_layout_columns'));
		add_filter('get_user_option_screen_layout_dashboard', array( $this, 'shapeSpace_screen_layout_dashboard'));
		//add_action( 'admin_title' ,  array( $this, 'change_dashboard_title') );
		//add_action( 'admin_menu' , array( $this, 'change_dashboard_menu' ) );

		/* Remove dashboard */
		add_action_once( 'wp_dashboard_setup',  array( $this, 'remove_dashboard_widgets') );

		/* Add Extra */
		add_action_once('wp_dashboard_setup', array( $this, 'Add_Extra_Widget'), 25);

		/* Db Notice */
		add_action_once('admin_notices', [ $this, 'Handle_Dashboard_Request'] );
	}


	/*
    * change column Grid Meta Box in admin Panel
	 */
	public function shapeSpace_screen_layout_columns($columns) {
		$columns['dashboard'] = 1;
		$columns['dashboard'] = 2;
		return $columns;
	}

	/*
	 * add_filter('get_user_option_screen_layout_{$post_type}', 'your_callback' );
	 */
	public function shapeSpace_screen_layout_dashboard() { return 1; }

	/*
	 * Remove Dashboard
	 */
	public function remove_dashboard_widgets() {

	    //Remove All Dashboard Easy
	    global $wp_meta_boxes;
        unset($wp_meta_boxes['dashboard']);

		//Default Wordpress
//		remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );   // Right Now
//		remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );   // Right Now
//		remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' ); // Recent Comments
//		remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );  // Incoming Links
//		remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );   // Plugins
//		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );  // Quick Press
//		remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );  // Recent Drafts
//		remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );   // WordPress blog
//		remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );   // Other WordPress News
//		// use 'dashboard-network' as the second parameter to remove widgets from a network dashboard.
//
//		//Plugin wordpress
//		remove_meta_box( 'wpp_planet_widget', 'dashboard', 'normal' );
//		remove_meta_box( 'semperplugins-rss-feed', 'dashboard', 'normal' );

	}

	/*
	 * change title in Dashboard Page
	 */
	function change_dashboard_title( $admin_title ) {
		global $current_screen;
		global $title;
		if( $current_screen->id != 'dashboard' ) {
			return $admin_title;
		}
		$change_title = 'گزارش';
		$admin_title = str_replace( __( 'Dashboard' ) , $change_title , $admin_title );
		$title = $change_title;
		return $admin_title;
	}

	/*
	 * change Menu Name Dashboard
	 */
	function change_dashboard_menu() {
		global $menu;
		foreach( $menu as $key => $menu_setting ) {
			$menu_slug = $menu_setting[2];
			if( empty( $menu_slug ) ) {
				continue;
			}
			if( $menu_slug != 'index.php' ) {
				continue;
			}
			$menu[ $key ][0] = 'گزارش';
			break;
		}
	}


	/*
	 * add extra Dashboard
	 */
	public function Add_Extra_Widget() {
		//wp_add_dashboard_widget('dash_amar_site', per_number('آمار سیستم'), array( $this, 'dashboard_amar' ));
	}




	/*
	 * List Of function Dashboard
	 */
	public function dashboard_amar(){
		global $wpdb;
		echo '';
	}

	

	public function dashboard_kharid_asan(){
		global $wpdb;

		echo '
		<table class="widefat" cellspacing="0">
				<thead>
				<tr>
					<td>ردیف</td>
					<td style="text-align:center;">نام و نام خانوادگی</td>
					<td style="text-align:center;">شماره تماس</td>
					<td style="text-align:center;">تاریخ</td>
					<td style="text-align:center;">حذف</td>
				</tr>
				</thead>
				<tbody>';

		$i =1;
		$q = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."admin_notification` ORDER BY id DESC", ARRAY_A);
		if(count($q) ==0) {
			echo '
			<tr>
					<td colspan="5" style="text-align:center;">صندوق پیام خالی می باشد</td>
				</tr>
			';
		} else {
			foreach ( $q as $item )
			{
				//update to read
				$wpdb->update(
					$wpdb->prefix."admin_notification",
					array( 'read' => 2),
					array( 'id' => $item['id'] )
				);

				echo '
			<tr>
					<td>'.per_number($i).'</td>
					<td style="text-align:center;">'.$item['user_name'].'</td>
					<td style="text-align:center;">'.per_number($item['tel']).'</td>
					<td style="text-align:center;">'.parsidate("l j F Y", $item['create_date']).'</td>
					<td style="text-align:center;"><a href="index.php?del_kharid_asan='.$item['id'].'"><i class="fa fa-close"></i></a></td>
				</tr>
			';

				$i++;
			}
		}

		echo '
			</tbody>
			</table>
		';
	}


	/*
	 * Dashboar Notice
	 */
	public function Handle_Dashboard_Request() {
	global $wpdb;

		//Remove kharid Asan
		if(isset($_GET['del_kharid_asan'])) {
			//remove
			$wpdb->delete( $wpdb->prefix."admin_notification", array( 'id' => $_GET['del_kharid_asan'] ) );

			//Show
			echo Ui::Notice("پیام با موفقیت حذف گردید", "success");
		}


		//Remove Hoshadar
		if(isset($_GET['del_hoshdar'])) {
			//remove
			$wpdb->delete( $wpdb->prefix."hoshdar", array( 'id' => $_GET['del_hoshdar'] ) );

			//Show
			echo Ui::Notice("پیام با موفقیت حذف گردید", "success");
		}


	}

}