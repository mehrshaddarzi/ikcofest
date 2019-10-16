<?php

// Create Admin Menu
add_action( 'admin_menu', 'wps_admin_menu' );
function wps_admin_menu() {
	$hook = add_menu_page( 'شرکت کنندگان', 'شرکت کنندگان', 'manage_options', 'festival', 'list_festival_entry', 'dashicons-universal-access', 1 );
	add_action( "load-$hook", 'wps_screen_option' );
	add_action( "admin_head-$hook", 'add_style_table' );
}

// Set Screen Option
add_filter( 'set-screen-option', 'wps_set_screen', 10, 3 );
function wps_set_screen( $status, $option, $value ) {
	return $value;
}

function wps_screen_option() {
	$option = 'per_page';
	$args   = [
		'label'   => 'تعداد در هر صفحه',
		'default' => 20,
		'option'  => 'customers_per_page'
	];
	add_screen_option( $option, $args );
	$GLOBALS['customer_list'] = new Customers_List();
	$GLOBALS['customer_list']->prepare_items();
}


// Add Custom Asset in this page
function add_style_table() {

	//Add Style
	echo '<style>
.tick_tbl {
    border-collapse: collapse;
    width: 95%;
    direction: rtl;
    text-align: right;
    padding: 25px;
    margin: 15px;
}

.tick_tbl td, .tick_tbl th {
  border: 1px solid #ddd;
  padding: 8px;
}

.tick_tbl tr:nth-child(even){background-color: #f2f2f2;}

.tick_tbl tr:hover {background-color: #ddd;}

.tick_tbl th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #4CAF50;
  color: white;
}
</style>';

	//Add Jquery Remove Password Field
	echo '
     <script type="text/javascript">         
        jQuery(document).ready( function($) {
  
        });
    </script>
    ';

	//Add Thick box
	wp_enqueue_script( 'thickbox' );
	wp_enqueue_style( 'thickbox' );
}

// Main Page Load function
function list_festival_entry() {
	?>
    <div class="wrap">
        <h1><i class="fa fa-dashboard wrap_h1_icon"></i> لیست شرکت کنندگان </h1>
        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-1">
                <div id="post-body-content">
                    <div class="meta-box-sortables ui-sortable">
                        <form method="post">
							<?php
							$GLOBALS['customer_list']->display();
							?>
                        </form>
                    </div>
                </div>
            </div>
            <br class="clear">
        </div>
    </div>
	<?php
}


// Class wp list table
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Customers_List extends WP_List_Table {

	/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Customer', 'sp' ), //singular name of the listed records
			'plural'   => __( 'Customers', 'sp' ), //plural name of the listed records
			'ajax'     => false //does this table support ajax?
		] );

	}

	/**
	 * Retrieve customers data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_customers( $per_page = 20, $page_number = 1 ) {

		global $wpdb;

		$sql = "SELECT * FROM {$wpdb->prefix}form";

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' DESC';
		} else {
			$sql .= ' ORDER BY ID DESC';
		}

		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

		$result = $wpdb->get_results( $sql, 'ARRAY_A' );
		return $result;
	}


	/**
	 * Delete a customer record.
	 *
	 * @param int $id customer ID
	 */
	public static function delete_customer( $id ) {
		global $wpdb;

		$wpdb->delete(
			"{$wpdb->prefix}form",
			[ 'ID' => $id ],
			[ '%d' ]
		);
	}

	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;
		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}form";
		return $wpdb->get_var( $sql );
	}


	/** Text displayed when no customer data is available */
	public function no_items() {
		_e( 'هیچ شخصی شرکت نکرده است', 'sp' );
	}


	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'address':
			case 'city':
				return $item[ $column_name ];
			default:
				return print_r( $item, true );
		}
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['ID']
		);
	}

	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = array(
			'cb'        => '<input type="checkbox" />',
			'user_name' => __( 'نام شرکت کننده', 'sp' ),
			'mobile'    => __( 'شماره همراه', 'sp' ),
			'date'      => __( 'تاریخ ارسال آثار', 'sp' ),
			'province'  => __( 'از استان', 'sp' ),
			'city'      => __( 'شهر', 'sp' ),
			'number'    => __( 'تعداد آثار', 'sp' ),
			'desc'      => ''
		);

		return $columns;
	}

	function column_user_name( $item ) {
		$delete_nonce = wp_create_nonce( 'sp_delete_customer' );
		$title        = '<strong>' . $item['name'] . " " . $item['family'] . '</strong>';
		$actions      = [
			'delete' => sprintf( '<a href="?page=%s&action=%s&ID=%s&_wpnonce=%s">حذف</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['ID'] ), $delete_nonce )
		];
		return $title . $this->row_actions( $actions );
	}

	function column_mobile( $item ) {
		return per_number( $item['mobile'] );
	}

	function column_date( $item ) {
		return date_i18n( "Y-m-d H:i:s", strtotime( $item['date'] ) );
	}

	function column_province( $item ) {
		global $wpdb;
		return $wpdb->get_var( "SELECT `name` FROM `province` WHERE `id` =" . $item['province'] );
	}

	function column_city( $item ) {
		global $wpdb;
		return $wpdb->get_var( "SELECT `name` FROM `city` WHERE `id` =" . $item['city'] );
	}

	function column_number( $item ) {
		$files = json_decode( $item['file'], true );
		return number_format_i18n( count( $files ) );
	}

	function column_desc( $item ) {
		global $wpdb;

		$t = '
        <div id="list_modal_' . $item['ID'] . '" style="display:none; text-align: right; direction: rtl;">
             <table border="0" class="tick_tbl">
                <tr>
                <td>نام</td>
                <td>' . $item['name'] . '</td>
                </tr>
                 <tr>
                <td>نام خانوادگی</td>
                <td>' . $item['family'] . '</td>
                </tr>
                <tr>
                <td>شماره همراه</td>
                <td>' . $item['mobile'] . '</td>
                </tr>
                <tr>
                <td>تاریخ تولد</td>
                <td>' . date_i18n( "Y-m-d", strtotime( $item['birthday'] ) ) . '</td>
                </tr>
                <tr>
                <td>استان</td>
                <td>' . $wpdb->get_var( "SELECT `name` FROM `province` WHERE `id` =" . $item['province'] ) . '</td>
                </tr>
                <tr>
                <td>شهر</td>
                <td>' . $wpdb->get_var( "SELECT `name` FROM `city` WHERE `id` =" . $item['city'] ) . '</td>
                </tr>
                <tr>
                <td>محل عکس</td>
                <td>' . $item['picture_place'] . '</td>
                </tr>
                <tr>
                <td>تاریخ ارسال آثار</td>
                <td>' . date_i18n( "Y-m-d H:i:s", strtotime( $item['date'] ) ) . '</td>
                </tr>
                <tr>
                <td>آی دی اینستاگرام</td>
                <td style="line-height: 25px;"><span dir="ltr" style="color:red;">' . $item['instagram'] . ' </span><hr><a href="https://www.instagram.com/' . str_replace( "@", "", $item['instagram'] ) . '/" target="_blank" style="color:green;">https://www.instagram.com/' . str_replace( "@", "", $item['instagram'] ) . '</a></td>
                </tr>
                ';
		if ( ! empty( $item['file'] ) ) {
			$files = json_decode( $item['file'], true );

			$i = 1;
			foreach ( $files as $f ) {
				$t .= '<tr>
                <td>فایل عکس ' . $i . '</td>
                <td style="line-height: 25px;"><a href="' . wp_get_attachment_url( $f ) . '" target="_blank">نمایش عکس</a><hr><a href="' . get_post_meta( $f, 'link_instagram', true ) . '" target="_blank" style="color:blue;">' . get_post_meta( $f, 'link_instagram', true ) . '</a></td>
                </tr>';
				$i ++;
			}
		}
		$t .= '
            </table>
        </div>
        <a href="#TB_inline?width=800&height=600&inlineId=list_modal_' . $item['ID'] . '" class="thickbox" style="color:red;">نمایش جزئیات</a>
        ';
		return $t;
	}

	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
//			'name' => array( 'name', true ),
//			'city' => array( 'city', false )
		);

		return $sortable_columns;
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = [
			'bulk-delete' => 'حذف'
		];

		return $actions;
	}


	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {

		$this->_column_headers = $this->get_column_info();

		/** Process bulk action */
		$this->process_bulk_action();

		$per_page     = $this->get_items_per_page( 'customers_per_page', 20 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );

		$this->items = self::get_customers( $per_page, $current_page );
	}

	public function process_bulk_action() {
		global $wpdb;

		// Delete One Item
		if ( ( isset( $_GET['action'] ) && $_GET['action'] == 'delete' ) and isset($_GET['ID']) ) {
			$wpdb->delete( $wpdb->prefix . 'form', array( 'ID' => $_GET['ID'] ) );
		}

		// If the delete bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' ) ) {

			$delete_ids = esc_sql( $_POST['bulk-delete'] );

			// loop over the array of record IDs and delete them
			foreach ( $delete_ids as $id ) {
				$wpdb->delete( $wpdb->prefix . 'form', array( 'ID' => $id ) );
			}

		}
	}

}
