<?php

class LoginPage {

	public $color;

	public function __construct() {
		global $admin_ui;

		$this->color = [
			'base'   => '#3498db',
			'second' => '#2581bf',
			'a'      => '#2581bf',
		];

		add_filter( 'login_errors', array( $this, 'login_error_override' ) );
		add_action_once( 'login_head', array( $this, 'hide_login_nav' ) );
		add_filter( 'login_title', array( $this, 'change_title' ), 99 );
		add_action_once( 'login_enqueue_scripts', array( $this, 'themeslug_enqueue_script' ), 1 );
		add_action_once( 'login_enqueue_scripts', array( $this, 'themeslug_enqueue_style' ), 10 );
		add_filter( 'login_headerurl', array( $this, 'custom_login_header_url' ) );
		add_filter( 'login_headertitle', array( $this, 'my_login_logo_url_title' ) );
		add_action_once( 'login_footer', array( $this, 'footer_text' ) );
		add_action_once( 'init', array( $this, 'change_login_title' ) );
		add_action( 'login_form', array( $this, 'wpse_159462_login_form') );

		//add_filter("login_redirect", array( $this, "admin_login_redirect"), 10, 3);
	}

	public function wpse_159462_login_form(  ) {
		echo <<<html
<script>
    document.getElementById( "user_pass" ).autocomplete = "off";
    document.getElementById( "user_login" ).autocomplete = "off";
    document.getElementById( "loginform" ).autocomplete = "off";
</script>
html;
	}

	public function change_title() {
		//return get_bloginfo( 'name' );
        return 'ورود به سیستم';
	}

	//check login page is not lockdown install
	public function is_login_page() {
		return in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) );
	}

	//change Error title
	public function login_error_override() {
		return 'نام کابری یا رمز عبور نادرست است';
	}


	/** Redirect User After Login */
	function admin_login_redirect( $redirect_to, $request, $user ) {
		global $user;
		if ( isset( $user->roles ) && is_array( $user->roles ) ) {
			if ( in_array( "administrator", $user->roles ) ) {
				return $redirect_to;
			} else {
				return home_url();
			}
		} else {
			return $redirect_to;
		}
	}

	//login page Class
	public function hide_login_nav() {
		?>
        <style>
            /*
			* color scheme :
            <?php echo $this->color['base']; ?>

						*/
            #login_error a {
                display: none;
            }

            #login {
                width: 320px;
                padding: 3.8% 0 0;
                margin: auto;
            }

            .login #nav {
                margin: 12px 0 0;
                font-size: 12px;
            }

            .login #backtoblog a, .login #nav a {
                text-decoration: none;
                color: #909090;
            }

            body {
                background: #ffffff;
            }

            .wp-core-ui .button-primary {
                background: <?php echo $this->color['base']; ?>;
                border-color: <?php echo $this->color['base']; ?> !important;
                -webkit-box-shadow: 0 0px 0 #006799;
                box-shadow: none !important;
                color: #fff;
                text-decoration: none;
                text-shadow: none !important;
            }

            .wp-core-ui .button-primary.focus, .wp-core-ui .button-primary.hover, .wp-core-ui .button-primary:focus, .wp-core-ui .button-primary:hover {
                background: <?php echo $this->color['second']; ?> !important;
                border-color: <?php echo $this->color['second']; ?> !important;
                color: #fff;
            }

            input[type=text], input[type=password] {
                margin-top: 10px !important;
                font-family: Trebuchet Ms;
                color: #696969 !important;
            }

            input[type=text]:focus, input[type=search]:focus, input[type=radio]:focus, input[type=tel]:focus, input[type=time]:focus, input[type=url]:focus, input[type=week]:focus, input[type=password]:focus, input[type=checkbox]:focus, input[type=color]:focus, input[type=date]:focus, input[type=datetime]:focus, input[type=datetime-local]:focus, input[type=email]:focus, input[type=month]:focus, input[type=number]:focus, select:focus, textarea:focus {
                border-color: <?php echo $this->color['base']; ?> !important;
                -webkit-box-shadow: none !important;
                box-shadow: none !important;
            }

            input[type=text], input[type=search], input[type=radio], input[type=tel], input[type=time], input[type=url], input[type=week], input[type=password], input[type=checkbox], input[type=color], input[type=date], input[type=datetime], input[type=datetime-local], input[type=email], input[type=month], input[type=number], select, textarea {
                border: 1px solid #ddd;
                -webkit-box-shadow: none !important;
                box-shadow: none !important;;
                background-color: #fff;
                color: #32373c;
                outline: 0;
                -webkit-transition: 50ms border-color ease-in-out;
                transition: 50ms border-color ease-in-out;
            }

            .login form {
                margin-top: 0px !important;
                margin-right: 0;
                padding: 8px 24px 0px;
                background: transparent;
                box-shadow: none !important;
            }

            p.forgetmenot {
                display: block;
                width: 100%;
            }

            a {
                text-decoration: none;
                color: <?php echo $this->color['a']; ?>;
            }

            input[name="wp-submit"] {
                width: 100% !important;
                height: 35px !important;
                margin-top: 15px !important;
                font-size: 13px !important;
                line-height: 10px !important;
            }

            #backtoblog {
                display: none !important;
            }

            .login h1 a:hover {
                transform: scale(0.8);
            }

            .login h1 a {
                transition: all 1s;
                background-image: url(<?php echo $GLOBALS['LOGIN_PAGE_LOGO']['url']; ?>);
                background-size: 100%;
                background-repeat: no-repeat;
                width: <?php echo $GLOBALS['LOGIN_PAGE_LOGO']['width']; ?>px;
                height: <?php echo $GLOBALS['LOGIN_PAGE_LOGO']['height']; ?>px;
                text-indent: -9999px;
                outline: 0;
                overflow: hidden;
                padding-bottom: 0px;
                display: block;
            }

            .login label {
                direction: ltr;
                font-weight: 100;
            }

            input[type=checkbox]:checked:before {
                margin: -3px 2px 0 0;
                color: <?php echo $this->color['base']; ?>;
            }

            a:focus {
                color: #124964;
                -webkit-box-shadow: none !important;
                box-shadow: none !important;
            }

            .cptch_reload_button {
                width: 2rem;
                height: 25px;
                font-size: 20px;
                margin: 7px 5px 0px 0px;
                vertical-align: middle;
                color: #868282;
            }

            input[name=cptch_number] {
                margin-top: -5px !important;
                height: 25px;
            }
        </style>
        <script>
            jQuery(document).ready(function () {
                jQuery("input[value=ورود]").val('ورود به سیستم');
                jQuery('label[for=user_login]').html(jQuery('label[for=user_login]').html().replace('نام کاربری یا نشانی ایمیل', '<i class="fa fa-user"></i> نام کاربری'));
                jQuery('label[for=user_pass]').html(jQuery('label[for=user_pass]').html().replace('رمز', ' <i class="fa fa-unlock-alt" style="vertical-align: -2px;"></i> رمز عبور'));
                jQuery('p#nav a').html(jQuery('p#nav a').html().replace('رمزتان را گم کرده‌اید؟', ' <i class="fa fa-caret-left" style="vertical-align: -2px;"></i> رمز عبور خود را فراموش کردم ؟'));
            });
        </script>
		<?php
	}

	//add jquery to login page
	public function themeslug_enqueue_script() {
		wp_enqueue_script( 'jquery-core' );
	}

	//add font awesome to login page
	public function themeslug_enqueue_style() {
		wp_enqueue_style( 'awesome-font', URL_ADMIN_IU . '/font-awesome-4.7.0/css/font-awesome.min.css', false );
	}

	//change logo Url
	public function custom_login_header_url( $url ) {
		return home_url();
	}

	//change title logo
	public function my_login_logo_url_title() {
		return ' ';
	}

	//add footer text in login page
	public function footer_text() {
		echo '
    <div style="text-align: center;margin-top: 45px;font-size: 11px;">
    تمامی حقوق مادی و معنوی محفوظ است
     <br>
    <a href="#"><i class="fa fa-code" style="vertical-align: -1px;"></i> طراحی و پشتیبانی : آینده روشن </a>
    </div>
    ';
	}


	//change title name loginpage
	public function change_login_title() {
		//check login Page if install Lockdown plugin
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		if ( is_plugin_active( 'lockdown-wp-admin/lockdown-wp-admin.php' ) ) {
			$login_base  = get_option( 'ld_login_base' );
			$blog_url    = trailingslashit( get_bloginfo( 'url' ) );
			$schema      = is_ssl() ? 'https://' : 'http://';
			$current_url = $schema . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			$request_url = str_replace( $blog_url, '', $current_url );
			$request_url = str_replace( 'index.php/', '', $request_url );
			$url_parts   = explode( '?', $request_url, 2 );
			$base        = $url_parts[0];
			$base        = rtrim( $base, '/' );
			$exp         = explode( '/', $base, 2 );
			$super_base  = end( $exp );

			// Is this the "login" url?
			if ( $base == $login_base ) {

				/*add_filter('option_blogname', 'custom_option_description', 10, 1);
				function custom_option_description($value) {
					return 'ورود به سیستم';
				}*/

				ob_start();
				add_action_once( 'shutdown', function () {
					$final  = '';
					$levels = ob_get_level();
					for ( $i = 0; $i < $levels; $i ++ ) {
						$final .= ob_get_clean();
					}
					echo apply_filters( 'final_output', $final );
				}, 0 );
				add_filter( 'final_output', function ( $output ) {
					$output = preg_replace( array( '/<title>(.*)<\/title>/i' ), array( '<title>ورود به سیستم</title>' ), $output );
					return $output;
				} );

			}
		}
	}
}