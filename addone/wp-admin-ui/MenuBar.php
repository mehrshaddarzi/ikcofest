<?php
class MenuBar {

	public function __construct() {

		add_action_once( 'admin_menu', array( $this, 'remove_menus') );
		//add_action('admin_init', array( $this, 'nwcm_admin_init') );
	}


	/*
	 * Remove Select menu
	 */
	function remove_menus(){
        global $submenu, $menu;

		//remove_submenu_page( 'index.php', 'update-core.php' );
		remove_menu_page( 'index.php' );                  //Dashboard
		remove_menu_page( 'jetpack' );                    //Jetpack*
//		remove_menu_page( 'edit.php' );                   //Posts
        remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=post_tag');
        remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=category');
//		remove_menu_page( 'upload.php' );                 //Media
//		remove_menu_page( 'edit.php?post_type=page' );    //Pages
		remove_menu_page( 'edit-comments.php' );          //Comments
		remove_menu_page( 'themes.php' );                 //Appearance
		remove_menu_page( 'plugins.php' );                //Plugins
//		remove_menu_page( 'users.php' );                  //Users
		remove_menu_page( 'tools.php' );                  //Tools
//		remove_menu_page( 'options-general.php' );        //Settings


		unset($submenu['options-general.php'][15]);
		unset($submenu['options-general.php'][20]);
		unset($submenu['options-general.php'][25]);
		unset($submenu['options-general.php'][30]);
		unset($submenu['options-general.php'][40]);
		unset($submenu['options-general.php'][45]);

        unset($submenu['themes.php'][6]); // Customize
        //unset($submenu['themes.php'][10]); // Editor Theme
        unset($submenu['plugins.php'][10]); //add plugin
        unset($submenu['plugins.php'][15]); //Editor plugin

        //Remove All in one Seo

		echo "<pre>";
        var_dump($submenu);
        exit;
	}

	/*
	 * remove all menu Seprate a plugin list
	 * This option for Developer worpdress
	 */
	function nwcm_admin_init()
	{
		// Remove unnecessary menus
		$menus_to_stay = array(
			// Dashboard
			'index.php',
			'this_menu_slug_as',
			'signout'
		);
		foreach ($GLOBALS['menu'] as $key => $value) {
			if (!in_array($value[2], $menus_to_stay)) remove_menu_page($value[2]);
		}
	}




}