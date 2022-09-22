<?php
/**
 * Create admin page and (sub)menu item (under "WooCommerce")
 * Table list of
 *
 * @package WordPress
 * @subpackage Haumea Child
 * @since Haumea 1.0.0
 */

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * CREATE A PACKAGE CLASS
 *
 * Create a new list table package that extends the core WP_List_Table class.
 * WP_List_Table contains most of the framework for generating the table, but we
 * need to define and override some methods so that our data can be displayed
 * exactly the way we need it to be.
 */
class Image_Haddad_Order_Products extends WP_List_Table {

	/**
	 * Array of products and its data
	 *
	 * @var array
	 */
	private $product_data = array();

	/**
	 * WC order status
	 *
	 * @var string
	 */
	public $status = 'wc-new-order';

	/** ************************************************************************
	 * REQUIRED. Set up a constructor that references the parent constructor. We
	 * use the parent reference to set some default configs.
	 ***************************************************************************/
	public function __construct() {
		global $status, $page;

		// Set parent defaults.
		parent::__construct(
			array(
				'singular' => __( 'Product', 'haumea-child' ),     // singular name of the listed records
				'plural'   => __( 'Products', 'haumea-child' ),    // plural name of the listed records
				'ajax'     => false,         // does this table support ajax?
			)
		);

	}


	/** ************************************************************************
	 * Recommended. This method is called when the parent class can't find a method
	 * specifically build for a given column. Generally, it's recommended to include
	 * one method for each column you want to render, keeping your package class
	 * neat and organized. For example, if the class needs to process a column
	 * named 'title', it would first see if a method named $this->column_title()
	 * exists - if it does, that method will be used. If it doesn't, this one will
	 * be used. Generally, you should try to use custom column methods as much as
	 * possible.
	 *
	 * Since we have defined a column_title() method later on, this method doesn't
	 * need to concern itself with any column with a name of 'title'. Instead, it
	 * needs to handle everything else.
	 *
	 * For more detailed insight into how columns are handled, take a look at
	 * WP_List_Table::single_row_columns()
	 *
	 * @param array $item A singular item (one full row's worth of data)
	 * @param array $column_name The name/slug of the column to be processed
	 * @return string Text or HTML to be placed inside the column <td>
	 **************************************************************************/
	protected function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'sku':
			case 'price':
			case 'order':
			case 'date':
				return $item[ $column_name ];
			default:
				return print_r( $item, true ); // Show the whole array for troubleshooting purposes.
		}
	}


	/** ************************************************************************
	 * Recommended. This is a custom column method and is responsible for what
	 * is rendered in any column with a name/slug of 'title'. Every time the class
	 * needs to render a column, it first looks for a method named
	 * column_{$column_title} - if it exists, that method is run. If it doesn't
	 * exist, column_default() is called instead.
	 *
	 * This example also illustrates how to implement rollover actions. Actions
	 * should be an associative array formatted as 'slug'=>'link html' - and you
	 * will need to generate the URLs yourself. You could even ensure the links
	 *
	 * @see WP_List_Table::::single_row_columns()
	 * @param array $item A singular item (one full row's worth of data)
	 * @return string Text to be placed inside the column <td> (movie title only)
	 **************************************************************************/
	public function column_title( $item ) {

		// Return the title contents.
		return sprintf(
			'<span style="display: block;padding-left:10px;"><strong><a href="%s">%s</a></strong> <small style="color:#999; text-transform: uppercase;">Narudžba br: %s - <a href="%s">Uredi</a></small></span>',
			'post.php?post=' . $item['ID'] . '&action=edit', // Product edit link
			$item['title'], // Product title.
			$item['order'], // Order number
			'post.php?post=' . $item['order'] . '&action=edit' // Order edit link
			// $this->row_actions( $actions )
		);
	}

	/** ************************************************************************
	 * REQUIRED! This method dictates the table's columns and titles. This should
	 * return an array where the key is the column slug (and class) and the value
	 * is the column's title text. If you need a checkbox for bulk actions, refer
	 * to the $columns array below.
	 *
	 * The 'cb' column is treated differently than the rest. If including a checkbox
	 * column in your table you must create a column_cb() method. If you don't need
	 * bulk actions or checkboxes, simply leave the 'cb' entry out of your array.
	 *
	 * @see WP_List_Table::::single_row_columns()
	 * @return array An associative array containing column information: 'slugs'=>'Visible names'
	 **************************************************************************/
	public function get_columns() {
		$columns = array(
			// 'cb'       => '<input type="checkbox" />', //Render a checkbox instead of text
			'title' => __( 'Title', 'haumea-child' ),
			'sku'   => __( 'SKU', 'haumea-child' ),
			'price' => __( 'Price', 'haumea-child' ),
			'order' => __( 'Order ID', 'haumea-child' ),
			'date'  => __( 'Order date', 'haumea-child' ),
		);
		return $columns;
	}


	/** ************************************************************************
	 * Optional. If you want one or more columns to be sortable (ASC/DESC toggle),
	 * you will need to register it here. This should return an array where the
	 * key is the column that needs to be sortable, and the value is db column to
	 * sort by. Often, the key and value will be the same, but this is not always
	 * the case (as the value is a column name from the database, not the list table).
	 *
	 * This method merely defines which columns should be sortable and makes them
	 * clickable - it does not handle the actual sorting. You still need to detect
	 * the ORDERBY and ORDER querystring variables within prepare_items() and sort
	 * your data accordingly (usually by modifying your query).
	 *
	 * @return array An associative array containing all the columns that should be sortable: 'slugs'=>array('data_values',bool)
	 **************************************************************************/
	protected function get_sortable_columns() {
		$sortable_columns = array(
			'title' => array( 'title', false ), // true means it's already sorted
			'sku'   => array( 'sku', false ),
			'price' => array( 'price', false ),
			'order' => array( 'order', false ),
			'date'  => array( 'date', false ),
		);
		return $sortable_columns;
	}


	/** ************************************************************************
	 * REQUIRED! This is where you prepare your data for display. This method will
	 * usually be used to query the database, sort and filter the data, and generally
	 * get it ready to be displayed. At a minimum, we should set $this->items and
	 * $this->set_pagination_args(), although the following properties and methods
	 * are frequently interacted with here...
	 *
	 * @global WPDB $wpdb
	 * @uses $this->_column_headers
	 * @uses $this->items
	 * @uses $this->get_columns()
	 * @uses $this->get_sortable_columns()
	 * @uses $this->get_pagenum()
	 * @uses $this->set_pagination_args()
	 **************************************************************************/
	public function prepare_items() {

		/**
		 * First, lets decide how many records per page to show
		 */
		$per_page = 100;

		/**
		 * REQUIRED. Now we need to define our column headers. This includes a complete
		 * array of columns to be displayed (slugs & titles), a list of columns
		 * to keep hidden, and a list of columns that are sortable. Each of these
		 * can be defined in another method (as we've done here) before being
		 * used to build the value for our _column_headers property.
		 */
		$columns = $this->get_columns();
		$hidden  = array();
		// $sortable = $this->get_sortable_columns();

		/**
		 * REQUIRED. Finally, we build an array to be used by the class for column
		 * headers. The $this->_column_headers property takes an array which contains
		 * 3 other arrays. One for all columns, one for hidden columns, and one
		 * for sortable columns.
		 */
		// $this->_column_headers = array( $columns, $hidden, $sortable );
		$this->_column_headers = array( $columns, $hidden );

		$data = $this->list_orders();

		/**
		 * REQUIRED for pagination. Let's figure out what page the user is currently
		 * looking at. We'll need this later, so you should always include it in
		 * your own package classes.
		 */
		$current_page = $this->get_pagenum();

		/**
		 * REQUIRED for pagination. Let's check how many items are in our data array.
		 * In real-world use, this would be the total number of items in your database,
		 * without filtering. We'll need this later, so you should always include it
		 * in your own package classes.
		 */
		$total_items = count( $data );

		/**
		 * The WP_List_Table class does not handle pagination for us, so we need
		 * to ensure that the data is trimmed to only the current page. We can use
		 * array_slice() to
		 */
		$data = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );

		/**
		 * REQUIRED. Now we can add our *sorted* data to the items property, where
		 * it can be used by the rest of the class.
		 */
		$this->items = $data;

		/**
		 * REQUIRED. We also have to register our pagination options & calculations.
		 */
		$this->set_pagination_args(
			array(
				'total_items' => $total_items, // WE have to calculate the total number of items.
				'per_page'    => $per_page, // WE have to determine how many items to show on a page.
				'total_pages' => ceil( $total_items / $per_page ), // WE have to calculate the total number of pages.
			)
		);
	}

	/**
	 * Generates content for a single row of the table.
	 *
	 * @since 3.1.0
	 *
	 * @param object|array $item The current item
	 */
	public function single_row( $item ) {
		$order = substr( $item['order'], -1 );

		echo '<tr style="box-shadow: inset 10px 0 0 1px ' . esc_attr( $this->row_colors( $order ) ) . '">';
		$this->single_row_columns( $item );
		echo '</tr>';
	}

	/**
	 * Color grouping row by order.
	 *
	 * @param int $num - last number from order id.
	 * @return string
	 */
	public function row_colors( $num ) {
		if ( '0' === $num ) {
			return '#95f7f7';
		} elseif ( '1' === $num ) {
			return '#ffd8a5';
		} elseif ( '2' === $num ) {
			return '#c5d8fd';
		} elseif ( '3' === $num ) {
			return '#b7f595';
		} elseif ( '4' === $num ) {
			return '#95e3f5';
		} elseif ( '5' === $num ) {
			return '#fdc5ea';
		} elseif ( '6' === $num ) {
			return '#fff07a';
		} elseif ( '7' === $num ) {
			return '#6ff5e2';
		} elseif ( '8' === $num ) {
			return '#f5cd6f';
		} elseif ( '9' === $num ) {
			return '#c4c5ff';
		}
	}

	/**
	 * Generates the table rows.
	 *
	 * @since 3.1.0
	 */
	public function display_rows() {
		foreach ( $this->items as $item ) {
			$this->single_row( $item );
			$order = $item['order'];
		}
	}

	/**
	 * List orders
	 *
	 * @return $products
	 */
	private function list_orders() {

		$order_products = array();
		$products       = array();

		// Get all orders, depeding on status.
		$query     = new WC_Order_Query(
			array(
				'limit'   => 1000,
				'status'  => $this->status,
				'orderby' => 'date',
				'order'   => 'DESC',
				'return'  => 'ids',
			)
		);
		$order_ids = $query->get_orders();

		// List all orders, by args, and ID's.
		foreach ( $order_ids as $order_id ) {

			$order      = wc_get_order( $order_id );
			$items      = $order->get_items();
			$order_date = $order->get_date_created()->format( 'd.m.Y' );

			foreach ( $items as $item ) {

				$product_name   = $item->get_name();
				$product_id     = $item->get_product_id();
				$product_var_id = $item->get_variation_id();

				if ( $product_var_id ) {
					$id = $product_var_id;
				} else {
					$id = $product_id;
				}

				$order_products[ $product_name . '-' . $order_id ] = array( $id, $order_id, $order_date );
			}
		}

		// Each product from orders.
		foreach ( $order_products as $key => $ids_array ) {

			$_product = wc_get_product( $ids_array[0] );
			if ( ! is_object( $_product ) ) {
				continue;
			}
			$name     = $_product->get_name();
			$sku      = $_product->get_sku();
			$currency = get_woocommerce_currency_symbol();
			$price    = $_product->get_price() . $currency;

			$products[] = array(
				'ID'    => $ids_array[0],
				'title' => $name,
				'price' => $price,
				'sku'   => $sku,
				'order' => $ids_array[1],
				'date'  => $ids_array[2],
				'row_c' => str_pad( substr( $ids_array[1], -2 ), 8, 'f8', STR_PAD_BOTH ),
			);

		}

		return $products;

	}
}

/**
 * Create tabs for order statuses.
 *
 * @return $order_status_tabs
 */
function image_haddad_wp_order_status_tabs() {
	$order_status_tabs = wc_get_order_statuses();
	return apply_filters( 'image_haddad_wp_order_status_tabs', $order_status_tabs );
}

/**
 * Render admin page
 *
 * @return void
 */
function image_haddad_wp_wc_admin_page() {

	// Create an instance of our package class.
	$order_products_list = new Image_Haddad_Order_Products();
	?>
	<div class="wrap">

		<h1 class="wp-heading-inline"><?php echo esc_html( get_admin_page_title() ); ?></h1>

		<nav class="nav-tab-wrapper">
			<?php
			$order_status_tabs = image_haddad_wp_order_status_tabs();
			// Put 'wc-new-order' to first position.
			if ( isset( $order_status_tabs['wc-new-order'] ) ) {
				$order_new         = array( 'wc-new-order' => $order_status_tabs['wc-new-order'] );
				$order_status_tabs = $order_new + $order_status_tabs;
			}
			// Set tabs.
			$current       = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'wc-new-order';
			$current_title = isset( $_GET['title'] ) ? sanitize_text_field( $_GET['title'] ) : __( 'New orders', 'haumea-child' );

			if ( ! empty( $order_status_tabs ) ) {
				foreach ( $order_status_tabs as $key => $value ) {

					$url = add_query_arg(
						array(
							'page'  => 'wc-order-products',
							'tab'   => $key,
							'title' => $value,
						),
						''
					);

					$class = ( $key === $current ) ? ' nav-tab-active' : '';
					echo '<a href="' . esc_url_raw( $url ) . '" class="nav-tab' . esc_attr( $class ) . '">' . esc_html( $value ) . '</a>';
				}
			}
			?>
		</nav>

		<?php
		// Data - list of products from orders according to order status.
		$order_products_list->status = $current;
		// Fetch, prepare, sort, and filter our data.
		$order_products_list->prepare_items();
		?>

		<?php echo '<h3 class="wp-heading-inline">' . esc_html( $current_title ) . '</h3>'; ?>

		<!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
		<form id="processing-orders-products" method="get">
			<!-- For plugins, we also need to ensure that the form posts back to our current page -->
			<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
			<!-- Now we can render the completed list table -->
			<?php $order_products_list->display(); ?>
		</form>

	</div>

	<?php
}
