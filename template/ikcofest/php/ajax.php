<?php

function send_festival_form() {

	if ( isset( $_POST ) && defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		global $wpdb;

		// Check Refer Ajax WP_Rest
		check_ajax_referer( 'wp_rest', 'token' );

		// Set Default Params
		$result = array( 'error' => 'yes', 'text' => '' );

		// Upload Files
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/admin.php' );
		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		// remove All another WordPress Size
		remove_all_filters( 'intermediate_image_sizes_advanced' );
		add_filter( 'intermediate_image_sizes_advanced', '_remove_default_images' );

		// Upload files
		$files = array();
		for ( $i = 1; $i <= 5; $i ++ ) {

			if ( ! empty( $_FILES[ 'file_' . $i ]['name'] ) and ! empty( $_POST[ 'link_' . $i ] ) ) {

				$uploadedfile     = $_FILES[ 'file_' . $i ];
				$upload_overrides = array( 'test_form' => false );
				$movefile         = wp_handle_upload( $uploadedfile, $upload_overrides );
				if ( $movefile ) {

					$wp_filetype   = $movefile['type'];
					$filename      = $movefile['file'];
					$wp_upload_dir = wp_upload_dir();
					$attachment    = array(
						'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
						'post_mime_type' => $wp_filetype,
						'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
						'post_content'   => '',
						'post_status'    => 'inherit'
					);
					$attachment_id = wp_insert_attachment( $attachment, $filename );
					$attach_data   = wp_generate_attachment_metadata( $attachment_id, $filename );
					wp_update_attachment_metadata( $attachment_id, $attach_data );

					// Set Link Instagram
					add_post_meta( $attachment_id, 'link_instagram', sanitize_text_field( $_POST[ 'link_' . $i ] ) );

					// Push To List
					$files[] = $attachment_id;
				} else {

					$result['text'] = "خطا : ارسال تصاویر با خطا مواجه شده است لطفا دوباره تلاش کنید.";
					wp_send_json( $result );
					exit;
				}
			}
		}

		// Explode Birthday
		$birthday = $_POST['birthday'];
		$exp      = explode( "/", $birthday );

		// Send To MySQL
		$wpdb->insert(
			$wpdb->prefix . 'form',
			array(
				'date'          => current_time( 'mysql' ),
				'name'          => sanitize_text_field( $_POST['name'] ),
				'family'        => sanitize_text_field( $_POST['family'] ),
				'birthday'      => gregdate( "Y-m-d", $exp[2] . "/" . $exp[1] . "/" . $exp[0] ),
				'province'      => sanitize_text_field( $_POST['province'] ),
				'city'          => sanitize_text_field( $_POST['city'] ),
				'address'       => '',
				'picture_place' => sanitize_text_field( $_POST['picture_place'] ),
				'mobile'        => sanitize_text_field( $_POST['mobile'] ),
				'instagram'     => sanitize_text_field( $_POST['instagram'] ),
				'file'          => json_encode( $files, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK )
			)
		);

		// If Success
		$result['error'] = 'no';
		$result['text']  = array( 'name' => $_POST['name'], 'family' => $_POST['family'], 'ID' => number_format_i18n( $wpdb->insert_id ) );
		wp_send_json( $result );
		exit;

	}
	die();
}

add_action( 'wp_ajax_send_festival_form', 'send_festival_form' );
add_action( 'wp_ajax_nopriv_send_festival_form', 'send_festival_form' );


// Check Festival Security
function check_security_festival_form() {

	if ( isset( $_POST ) && defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		global $wpdb;

		// Check Require Params
		if ( ! isset( $_POST['mobile'] ) || ! isset( $_POST['captcha'] ) || ! isset( $_POST['instagram_id'] ) || ! isset( $_POST['token'] ) ) {
			exit;
		}

		// Check refer Ajax WP_Rest
		check_ajax_referer( 'wp_rest', 'token' );

		// Set Default Params
		$result = array( 'error' => 'yes', 'text' => '' );

		// Check User Captcha
		if ( $_POST['captcha'] !== $_SESSION['captcha-text-form-site'] ) {
			$result['text'] = "خطا : کد امنیتی را درست وارد نمایید";
			wp_send_json( $result );
			exit;
		}

		// Check duplicate Mobile number
		$count_mobile_exist = $wpdb->get_var( "SELECT COUNT(*) FROM `{$wpdb->prefix}form` WHERE `mobile` = '{$_POST['mobile']}'" );
		if ( $count_mobile_exist > 0 ) {
			$result['text'] = "خطا : این شماره همراه قبلا به ثبت رسیده است";
			wp_send_json( $result );
			exit;
		}

		//Check duplicate instagram ID
		$count_instagram_id_exist = $wpdb->get_var( "SELECT COUNT(*) FROM `{$wpdb->prefix}form` WHERE `instagram` = '{$_POST['instagram_id']}'" );
		if ( $count_instagram_id_exist > 0 ) {
			$result['text'] = "خطا : این آی دی اینستاگرام قبلا به ثبت رسیده است";
			wp_send_json( $result );
			exit;
		}

		// If Success
		wp_send_json( array( 'error' => 'no', 'text' => 'Ready to send Form.' ) );
		exit;
	}
	die();
}

add_action( 'wp_ajax_check_security_festival_form', 'check_security_festival_form' );
add_action( 'wp_ajax_nopriv_check_security_festival_form', 'check_security_festival_form' );

// Unit Test Birthday Wp-parsidate
//add_action( 'init', function () {
//	$birthday = '06/03/1398';
//	$exp      = explode( "/", $birthday );
//	//$birthday = '1398/02/01';
//	echo gregdate( "Y-m-d", $exp[2] . "/" . $exp[1] . "/" . $exp[0] );
//	exit;
//} );