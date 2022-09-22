<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://micemade.com
 * @since      1.0.0
 *
 * @package    Image_Haddad_Wp
 * @subpackage Image_Haddad_Wp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Image_Haddad_Wp
 * @subpackage Image_Haddad_Wp/admin
 * @author     Micemade <alen@micemade.com>
 */
class Image_Haddad_Wp_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The plugin options (basic woocommerce settings).
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $ihwp_wc_basic   The plugin options (settings).
	 */
	private $ihwp_wc_basic;

	/**
	 * The plugin options (woocommerce settings).
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $ihwp_wc_options    The plugin options (settings).
	 */
	private $ihwp_wc_options;

	/**
	 * The plugin options (custom css settings).
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $ihwp_css_options    The plugin options (settings).
	 */
	private $ihwp_css_options;

	/**
	 * Other options.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $ihwp_other_options    The plugin options (settings).
	 */
	private $ihwp_other_options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		// Get options, grouped in sections.
		$this->ihwp_wc_basic      = get_option( 'ihwp_wc_basic' );
		$this->ihwp_wc_options    = get_option( 'ihwp_wc' );
		$this->ihwp_css_options   = get_option( 'ihwp_css' );
		$this->ihwp_other_options = get_option( 'ihwp_other' );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Image_Haddad_Wp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Image_Haddad_Wp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/image-haddad-wp-admin.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Image_Haddad_Wp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Image_Haddad_Wp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/image-haddad-wp-admin.js', array( 'jquery' ), $this->version, false );

	}

	// /////////////// DEBUG ////////////////////////
	/**
	 * Test.
	 *
	 * @param object $order - order object.
	 * @return void
	 */
	public function order_meta( $order ) {
		if ( ! current_user_can( 'administrator' ) ) {
			return;
		}
		$order_meta = get_post_meta( $order->get_id(), '_haddad_to_new_order_init', true );
		$meta       = $order_meta ? '😃😃😃 <span style="color: green">HADDAD NEW ORDER META</span>' : '<span style="color: red">NO haddad new order meta</span> 😡';
		echo '<p class="form-field form-field-wide" style="font-weight: bold; padding-top: 40px">';
		print_r( $meta );
		echo '</p>';
		$transient = get_transient( 'haddad_new_order_' . $order->get_id() );
		$trans     = $transient ? '😃😃😃 <span style="color: green">TRANSIENT EXISTS</span>' : '<span style="color: red">NO TRANSIENT</span> 😡';
		echo '<p class="form-field form-field-wide" style="font-weight: bold; padding-top: 40px">';
		print_r( $trans );
		echo '</p>';
	}
	// /////////////// DEBUG ////////////////////////

	/**
	 * Add settings link to plugin actions
	 *
	 * @param  array $actions
	 * @since  1.0
	 * @return array
	 *
	 * https://developer.wordpress.org/reference/hooks/plugin_action_links_plugin_file/
	 */
	public function plugins_page_settings_link( $actions ) {
		$mylinks = array(
			sprintf( __( '<a href="%s">Settings</a>', 'image-haddad-wp' ), esc_url( admin_url( 'admin.php?page=ihwp_settings' ) ) ),
		);
		$actions = array_merge( $actions, $mylinks );
		return $actions;
	}

	/**
	 * Additional row for this plugin, in plugins admin page.
	 *
	 * @param string $plugin_file
	 * @param array  $plugin_data
	 * @param string $status
	 * @return void
	 */
	public function plugins_page_add_row( $plugin_file, $plugin_data, $status ) {
		echo '<tr class="active is-uninstallable"><td colspan="4"><div class="notice inline notice-success notice-alt" style="padding: 15px; margin: 0 10px 10px;">Dodatak (plugin) <strong>"Image Haddad WP"</strong> je napravljen samo za upotrebu na https://www.haddad.hr stranici, i služi za dodavanje custom funkcionalnosti stranica (tema i pluginova) pomoću custom koda i funkcija. Za postavke, kliknite na link <strong>"Postavke"</strong> iznad, za rješavanje eventualnih problema s dodatnim funkcionalostima, kontaktirajte developera klikom na link <strong>"Micemade"</strong> iznad.</div></td></tr>';
	}

	/**
	 * Plugin additional meta data.
	 *
	 * @access  public
	 * @param   array  $plugin_meta - An array of the plugin's metadata.
	 * @param   string $plugin_file - Path to the plugin file.
	 * @param   array  $plugin_data - An array of plugin data.
	 * @param   string $status - Status of the plugin.
	 * @return  array  $plugin_meta
	 */
	public function plugins_page_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {

		if ( 'image-haddad-wp/image-haddad-wp.php' === $plugin_file ) {
			$new_links   = array(
				'doc'    => '<a href="doc_url" target="_blank">Documentation</a>',
			);
			$plugin_meta = array_merge( $plugin_meta, $new_links );
		}
		return $plugin_meta;
	}

	/**
	 * Add link in admin shop order filters
	 *
	 * @param array $views
	 * @return $views
	 */
	public function haddad_new_order_filter( $views ) {

		$current_screen = get_current_screen();
		// Get the posts number.
		$count_posts     = wp_count_posts( 'shop_order' );
		$count_posts_arr = get_object_vars( $count_posts );

		$new_orders = array();
		if ( 'edit-shop_order' === $current_screen->id ) {

			// Link to new order and creation filter.
			$new_orders_link = admin_url( 'edit.php?post_status=wc-new-order&post_type=shop_order', 'https' );
			$creation_link   = admin_url( 'edit.php?post_status=wc-creation&post_type=shop_order', 'https' );

			// If 'wc-new-orders' filter link is already set, unset it.
			if ( isset( $views['wc-new-order'] ) ) {
				unset( $views['wc-new-order'] );
			}
			// If 'wc-creation' filter link is already set, unset it.
			if ( isset( $views['wc-creation'] ) ) {
				unset( $views['wc-creation'] );
			}

			// If current filter is 'wc-new-order'.
			$current_new = false;
			if ( isset( $_GET['post_status'] ) && 'wc-new-order' === $_GET['post_status'] ) {
				$current_new = true;
			}
			// If current filter is 'wc-creation'.
			$current_creation = false;
			if ( isset( $_GET['post_status'] ) && 'wc-creation' === $_GET['post_status'] ) {
				$current_creation = true;
			}

			$new_orders         = array();
			$in_creation_orders = array();
			// Add custom link to 'wc-new-orders' filter link.
			$new_orders = array(
				'wc-new-order' => '<span class="new-orders-wrap"><a href="' . esc_url_raw( $new_orders_link ) . '"' . ( $current_new ? ' class="current" aria-current="page"' : '' ) . '>' . __( 'New Orders', 'image-haddad-wp' ) . '</a>' . ' (' . esc_html( $count_posts_arr['wc-new-order'] ) . ')</span>',
			);
			// Add custom link to 'wc-creation' filter link.
			$in_creation_orders = array(
				'wc-creation' => '<span class="creation-wrap"><a href="' . esc_url_raw( $creation_link ) . '"' . ( $current_creation ? ' class="current" aria-current="page"' : '' ) . '>' . __( 'In creation', 'image-haddad-wp' ) . '</a>' . ' (' . esc_html( $count_posts_arr['wc-creation'] ) . ')</span>',
			);

			// Put new orders first, in creation last.
			$extended_views = array_merge( $new_orders, $views, $in_creation_orders );
		}

		return $extended_views;
	}

	/**
	 * Add Custom Order Status to the Dropdown @ Single Order
	 *
	 * @param [type] $order_statuses - array of statuses.
	 * @return $order_statuses
	 */
	public function haddad_show_custom_order_status( $order_statuses ) {
		$order_statuses['wc-new-order'] = _x( 'New Order', 'Order status', 'image-haddad-wp' );
		$order_statuses['wc-caution']   = _x( 'Caution!', 'Order status', 'image-haddad-wp' );
		$order_statuses['wc-creation']  = _x( 'In creation', 'Order status', 'image-haddad-wp' );
		return $order_statuses;
	}

	/**
	 * Add Custom Order Status to the Dropdown "Bulk Actions" @ Orders
	 *
	 * @param array $bulk_actions - array of bulk actions.
	 * @return $bulk_actions
	 */
	public function haddad_get_custom_order_status_bulk( $bulk_actions ) {
		// Note: "mark_" must be there instead of "wc".
		$bulk_actions['mark_new-order']  = __( 'Change status to "New order"', 'image-haddad-wp' );
		$bulk_actions['mark_caution']    = __( 'Change status to "Caution!"', 'image-haddad-wp' );
		$bulk_actions['mark_creation']   = __( 'Change status to "In creation"', 'image-haddad-wp' );
		$bulk_actions['mark_processing'] = __( 'Change status to "Processing"', 'image-haddad-wp' );
		return $bulk_actions;
	}

	/**
	 * Replace "Processing" orders count with "New order" count in WC menu.
	 *
	 * 'haddad_menu_order_count' is a WC_Admin_Menus class 'menu_order_count' method copy.
	 * woocommerce/includes/admin/class-wc-admin-menus.php
	 *
	 * @return void
	 */
	public function haddad_menu_order_count() {
		global $submenu;

		// Disable "Processing" count badge in menu.
		add_filter( 'woocommerce_include_processing_order_count_in_menu', '__return_false' );

		if ( isset( $submenu['woocommerce'] ) ) {

			$order_count = wc_orders_count( 'new-order' );

			// Add count if user has access.
			if ( current_user_can( 'manage_woocommerce' ) && $order_count ) {
				foreach ( $submenu['woocommerce'] as $key => $menu_item ) {
					if ( 0 === strpos( $menu_item[0], _x( 'Orders', 'Admin menu name', 'woocommerce' ) ) ) {
						$submenu['woocommerce'][ $key ][0] .= ' <span class="awaiting-mod update-plugins count-' . esc_attr( $order_count ) . '"><span class="count-new-orders">' . number_format_i18n( $order_count ) . '</span></span>'; // WPCS: override ok.
						break;
					}
				}
			}
		}
	}

	/**
	 * Custom action(s) added to Order actions meta box
	 *
	 * @param array $actions - order actions array.
	 * @return $actions
	 *
	 * Adapted code from:
	 * https://chaladi.com/php/cms/wordpress/woocommerce/add-woocommerce-custom-order-actions/
	 */
	public function custom_order_actions( $actions ) {
		global $theorder;
		// Check if the order has been paid for or this action has been run.
		if ( ! $theorder->is_paid() ) {
			return $actions;
		}
		// Add send order confirmation to customer.
		$actions['wc_customer_resend_order_confirmation'] = __( 'Custom: Resend order confirmation to customer', 'image-haddad-wp' );
		return $actions;
	}
	/**
	 * Re-send processing order confirmation to customer.
	 *
	 * @param object $order - the order object.
	 * @return void
	 *
	 * Based on woocommerce/includes/admin/meta-boxes/class-wc-meta-box-order-actions.php
	 * WC_Meta_Box_Order_Actions->save()
	 */
	public function customer_resend_order_confirmation( $order ) {

		do_action( 'woocommerce_before_resend_order_emails', $order, 'customer_processing_order' );

		// Send the customer invoice email.
		WC()->payment_gateways();
		WC()->shipping();
		WC()->mailer()->emails['WC_Email_Customer_Processing_Order']->trigger( $order->get_id(), $order, true );

		// Note the event.
		$order->add_order_note( __( 'Order details manually sent to customer.', 'woocommerce' ), false, true );

		do_action( 'woocommerce_after_resend_order_email', $order, 'customer_processing_order' );

		// Change the post saved message.
		add_filter( 'redirect_post_location', array( 'WC_Meta_Box_Order_Actions', 'set_email_sent_message' ) );

		// Add the flag.
		update_post_meta( $order->get_id(), '_wc_customer_resend_order_confirmation', 'yes' );

	}

	/**
	 * Product limit on ajax search
	 *
	 * Filter 'woocommerce_json_search_limit' in method WC_AJAX->json_search_products()
	 * in /woocommerce/includes/class-wc-ajax.php file.
	 *
	 * @return integer
	 */
	public function haddad_product_search_limit() {
		return 150;
	}

	/**
	 * Notice for removed upsells from single product page.
	 *
	 * @return void
	 */
	public function haddad_removed_upsells_notice() {
		printf(
			'<p class="form-field removed-upsells-notice">%s<br>%s<br><a href="%s">Image Haddad WP (link)</a></p>',
			esc_html__( 'Upsell product are temporarily hidden on frontend via "Image Haddad WP" plugin. ', 'image-haddad-wp' ),
			esc_html__( 'To enable upsells visibility on frontend, please go to "Image Haddad WP" plugin settings, tab "WooCommerce" and re-enable the upsells.', 'image-haddad-wp' ),
			esc_url(
				add_query_arg(
					array( 'page' => 'ihwp_settings' ),
					admin_url( 'admin.php' )
				)
			)
		);
	}

	/**
	 * Automatsko dodavanje terma (pojma) taksonomije na spremanju objave (proizvoda)
	 *
	 * @param object $post - post objekt.
	 * @return void
	 *
	 * "hookano" na 'draft_to_publish' - funkcija se pokrece samo na prvu objavu proizvoda
	 * automatski se dodaje 'novo-u-web-shopu' ('new-arrivals') objavljenom proizvodu
	 */
	public function haddad_assign_term_on_save( $post ) {

		if ( 'product' === $post->post_type ) {

			$post_id  = $post->ID;
			$tax_type = 'product_cat';// ili 'product_tag'.

			// Multiple terms for more (other languages?) terms.
			$terms_to_add = array( 'novo-u-web-shopu' );
			// Append (last arg must be true) term to post terms.
			wp_set_object_terms( $post_id, $terms_to_add, $tax_type, true );
		}
	}

	/**
	 * Scheduled Action Hook
	 *
	 * @return void
	 * Un-assign the terms from products.
	 * Scheduled to 20 days after product publishing date.
	 */
	public function haddad_remove_term_f() {
		// Set defaults.
		$has_terms          = false;
		$cached_product_ids = array();
		$tax_type           = 'product_cat';

		$wq_args = array(
			'post_type'        => 'product',
			'post_status'      => 'publish', // changed from 'any', 'any' will get all the drafts, not neccesary...
			'posts_per_page'   => -1,
			'suppress_filters' => false,
			'fields'           => 'ids',
			// 'cache_results'    => false, // suppress errors when large number of posts (memory).
		);

		$cached_product_ids = get_transient( 'haddad_remove_term_new' );

		if ( empty( $cached_product_ids ) ) {
			$wq_products = new WP_Query( $wq_args );
			set_transient( 'haddad_remove_term_new', $wq_products->posts, 60 * 60 * 24 );
		} else {
			$wq_products = new WP_Query( array( 'post__in' => $cached_product_ids ) );
		};

		$today = date( 'Y-m-d', time() );
		// Add more items for multiple removals (multilang).
		$remove_terms = array( 'novo-u-web-shopu', 'new-arrivals' );

		if ( $wq_products->have_posts() ) {
			while ( $wq_products->have_posts() ) :
				$wq_products->the_post();

				$id          = get_the_ID();
				$post_date   = mysql2date( 'Y-m-d', get_the_date( 'Y-m-d' ) );
				$expiry_date = date( 'Y-m-d', strtotime( $post_date ) + 20 * DAY_IN_SECONDS );
				$has_terms   = has_term( $remove_terms, $tax_type, $id );

				// Check if today's date is higher then products expiry date.
				if ( $today > $expiry_date ) {
					// Remove the terms for $remove_terms, if they exsit.
					if ( $has_terms ) {
						wp_remove_object_terms( $id, $remove_terms, $tax_type );
					}
				}
			endwhile;
		}
		wp_reset_postdata();

	}

	/**
	 * Schedule Cron Job Event
	 *
	 * @return void
	 */
	public function haddad_schedule_remove_term() {
		if ( ! wp_next_scheduled( 'haddad_remove_term' ) ) {
			wp_schedule_event( time(), 'twicedaily', array( 'haddad_remove_term' ) );
		}
	}

	/**
	 * Register admin page and WooCommerce submenu
	 *
	 * Uses image_haddad_wp_wc_admin_page() in includes/class-image-haddad-order-products.php.
	 *
	 * @return void
	 */
	public function haddad_admin_wc_order_products() {
		add_submenu_page(
			'woocommerce',
			__( 'Order products', 'image-haddad-wp' ),
			__( 'Order products', 'image-haddad-wp' ),
			'manage_options', // <-- capability
			'wc-order-products',
			'image_haddad_wp_wc_admin_page'
		);
	}

	/**
	 * Returns the One True Instance of Image_Haddad_WC_Filter_Orders_By_Payment
	 *
	 * @since 1.0.0
	 * @return WC_Filter_Orders_By_Payment
	 */
	public function haddad_wc_filter_orders_by_payment() {
		return Image_Haddad_WC_Filter_Orders_By_Payment::instance();
	}

	/**
	 * Disable coupon cache.
	 *
	 * @return void
	 */
	public function haddad_coupon_price_cache() {
		add_meta_box( 'haddad_coupon_price_cache', 'Kuponska cijena na proizvodima', array( $this, 'haddad_coupon_price_cache_html' ), 'shop_coupon', 'side', 'default' );
	}

	/**
	 * HTML with coupon price cache notice.
	 *
	 * @return void
	 */
	public function haddad_coupon_price_cache_html() {
		if ( is_admin() && current_user_can( 'customize' ) ) {
			echo '<div class="woocommerce_options_panel coupon-cache"><span class="text">';
			printf(
				'Da bi privremeno onemogućili cache kupona (testiranja kupona i sl.), otiđite na <strong><a href="%1$s">%2$s</a></strong> da bi u opcijama stranice onemogućili cache.<br>NAPOMENA: nakon završetka uređivanja kupona i objavljivanja istog, nemojte se zaboraviti vratiti na stranicu prilagodbe i ponovno uključiti cache.',
				esc_url(
					add_query_arg(
						array(
							'page' => 'ihwp_settings',
							// array( 'autofocus' => array( 'control' => 'disable_coupon_cache' ) ),
							// 'return' => urlencode( remove_query_arg( wp_removable_query_args(), wp_unslash( $_SERVER['REQUEST_URI'] ) ) ),
						),
						admin_url( 'admin.php' )
					)
				),
				'stranicu postavki "Image Haddad WP"'
			);
			echo '</span></div>';
		}
	}

	/**
	 * Coupon edit page additional tab.
	 *
	 * @param array $tabs - array of tabs.
	 * @return void
	 */
	public function haddad_coupon_data( $tabs ) {
		$tabs['haddad_coupon_data'] = array(
			'label'  => __( 'Haddad settings', 'image-haddad-wp' ),
			'target' => 'haddad_coupon_data',
			'class'  => 'haddad_coupon_data',

		);
		return $tabs;
	}

	/**
	 * Additional tab pane with coupon settings.
	 *
	 * @param string $coupon_id - coupon ID number.
	 * @param object $coupon - coupon object.
	 * @return void
	 */
	public function haddad_coupon_data_panel( $coupon_id, $coupon ) {
		?>
		<div id="haddad_coupon_data" class="panel woocommerce_options_panel">
		<?php
		// Coupon display on catalog products checkbox.
		$args               = array(
			'id'          => 'coupon_prices_on_products',
			'label'       => __( 'Coupon prices on products', 'image-haddad-wp' ),
			'description' => __( 'Show discounted price under regular prices, with coupon code label.', 'image-haddad-wp' ),
		);
		// If auto saved coupon (just created), make checkbox checked bt default.
		if ( strpos( $coupon->get_code(), 'auto ' ) !== false ) {
			$args['custom_attributes'] = array( 'checked' => 'checked' );
		}

		woocommerce_wp_checkbox( $args );

		// Coupon background color control.
		$color_args = array(
			'id'    => 'coupon_back_color',
			'title' => 'Coupon background color',
			'class' => 'colorpick',
			'label' => __( 'Coupon background color', 'image-haddad-wp' ),
			'type'  => 'text',
		);
		woocommerce_wp_text_input( $color_args );

		// Coupon font color control.
		$font_color_args = array(
			'id'    => 'coupon_font_color',
			'title' => 'Coupon font color',
			'class' => 'colorpick',
			'label' => __( 'Coupon font color', 'image-haddad-wp' ),
			'type'  => 'text',
		);
		woocommerce_wp_text_input( $font_color_args );
		?>
		<script>
			(function( $ ) {
				// Add Color Picker to all inputs that have 'color-field' class
				$(function() {
					$('#coupon_back_color').wpColorPicker();
					$('#coupon_font_color').wpColorPicker();
				});
			})( jQuery );
		</script>
		</div>
		<?php
	}

	/**
	 * Save the coupon fields.
	 *
	 * @param string $post_id - unique post id.
	 * @return void
	 */
	public function haddad_coupon_save_data( $post_id ) {
		$coupon_fields = array(
			'coupon_prices_on_products',
			'coupon_back_color',
			'coupon_font_color',
		);
		foreach ( $coupon_fields as $field ) {
			$value = isset( $_POST[ $field ] ) ? $_POST[ $field ] : '';
			update_post_meta( $post_id, $field, $value );
		}
	}

	/**
	 * Add field for order printing.
	 *
	 * @param array  $fields - array of feilds in order.
	 * @param object $order - order object.
	 * @return $fields
	 */
	public function haddad_custom_orderprint_fields( $fields, $order ) {

		$new_fields = array();
		$coupons    = $order->get_items( array( 'coupon' ) );

		if ( $coupons ) {
			$num_of_coupons = count( $coupons );
			foreach ( $coupons as $item_id => $item ) {

				$coupon_code = $item->get_code();

				$new_fields[ $coupon_code ] = array(
					// translators: The (%s) placeholder is for the coupon code.
					'label' => sprintf( _n( 'Coupon code', '%s coupon codes', $num_of_coupons, 'image-haddad-wp' ), $num_of_coupons ),
					'value' => $coupon_code,
				);
			}
		}

		return array_merge( $fields, $new_fields );
	}

	/**
	 * Add product image to custom print order.
	 *
	 * @param object $product - php object with products.
	 * @return void
	 */
	public function haddad_custom_orderprint_product_image( $product ) {
		if ( isset( $product->id ) && has_post_thumbnail( $product->id ) ) {
			echo get_the_post_thumbnail( $product->id, 'shop_thumbnail' );
		}
	}

	/**
	 * WP-CLI - delete posts older than.
	 *
	 * @param  array $args
	 * @param  array $assoc_args
	 *
	 * @return void
	 *
	 * WP-CLI komanda za brisanje CPT-ova starijih od određenog datuma
	 * Sintaksa: wp haddad-delete-before <post_type> <post_status> <year> <month> <day> < --number >
	 * Primjer (brisanje skica proizvoda starijih od 01.01.2020.):
	 * wp haddad-delete-before draft 2020 1 1 --number=100[opcionalno] --date=post_date[opcionalno]
	 * Assoc argument "date" brise po kriteriju datuma ili zadnje izmjene ('post_date_gmt' ili 'post_modified_gmt')
	 */
	public function wpcli_delete_before( $args, $assoc_args ) {

		// Ako je prazan array $args i ima nema tocno 5 stavki - error poruka i prekid.
		if ( empty( $args ) || count( $args ) !== 5 ) {
			WP_CLI::error( 'Svi parametri: POST TYPE, POST STATUS, GODINA, MJESEC I DAN su obavezni (po tom redosljedu), a nisu definirani. Ispravna komanda je: "wp haddad-delete-before <post_type> <post_status> <year> <month> <day> < --number=100 > < --date=post_date >".' );
			return;
		}

		$post_type   = $args[0];
		$post_status = $args[1];
		$year        = $args[2];
		$month       = $args[3];
		$day         = $args[4];

		// Provjera post typea, ako ne postoji, error poruka i prekid.
		if ( ! post_type_exists( $post_type ) ) {
			WP_CLI::error(
				sprintf(
					'Ne postoji post type "%s", molimo provjerite parametar "post type"',
					$post_type
				)
			);
			return;
		}
		// Provjera post statusa, ako ne postoji, error poruka i prekid.
		$statuses = get_available_post_statuses();
		if ( ! in_array( $post_status, $statuses, true ) ) {
			WP_CLI::error(
				sprintf(
					'Ne postoji post status "%s", molimo provjerite parametar "post status"',
					$post_status
				)
			);
		}
		// U slucaju "attachment"-a, forsirati "inherit" status.
		if ( 'attachment' === $post_type && 'inherit' !== $post_status ) {
			WP_CLI::log(
				sprintf(
					'Attachmenti mogu imati samo "inherit" post status. Argument "%s" promijenjen u "inherit"',
					$post_status
				)
			);
			$post_status = 'inherit';
		}

		// Provjera datuma - parametri: month, day, year.
		if ( ! checkdate( (int) $month, (int) $day, (int) $year ) ) {
			WP_CLI::error(
				sprintf(
					'Upisali ste nevažeći datum, po Gregorijanskom kalendaru. Provjerite datum -Godina: %s, Mjesec: %s, Dan: %s. Ispravna komanda - "wp haddad-delete-before <post_type> <post_status> <year> <month> <day>".',
					$year,
					$month,
					$day
				)
			);
		}

		// Limit za brisanje objava, ako je assoc_arg "number" (opcionalno) definiran.
		$posts_num = '-1'; // Defaultno sve.
		if ( isset( $assoc_args['number'] ) ) {
			WP_CLI::log(
				sprintf(
					'Broj stavki za brisanje je limitiran na "%s"',
					$assoc_args['number']
				)
			);
			$posts_num = $assoc_args['number'];
		}

		// Opcionalno - brisanje po datumu objave ili zadnje izmjene ( 'post_date_gmt' ili 'post_modified_gmt' ).
		$post_date = isset( $assoc_args['date'] ) ? $assoc_args['date'] : 'post_date_gmt';
		// Validacija za date query "column".
		$columns   = array( 'post_date_gmt', 'post_modified_gmt', 'post_date', 'post_modified' );
		if ( ! in_array( $post_date, $columns, true ) ) {
			WP_CLI::error(
				sprintf(
					'Argument "%s" za datum nije valjan. Moguce vrijednosti: "post_date_gmt", "post_modified_gmt", "post_date", ili "post_modified".',
					$post_date
				)
			);
		}

		// Dobivanje labela za post typeove, koristeci Post type object.
		$post_type_obj      = get_post_type_object( $post_type );
		$post_type_plural   = $post_type_obj->labels->name; // Mnozina.
		$post_type_singular = $post_type_obj->labels->singular_name; // Jednina.

		// Log radnje u WP CLIu. Poruka da funkcija starta.
		WP_CLI::log(
			sprintf(
				'Brisati će se %s sa statusom "%s", kreirani prije datuma: %s.%s.%s.',
				$post_type_plural, // post types.
				$post_status, // post status.
				$day, // day.
				$month, // month.
				$year // year.
			)
		);

		// WP_Query arguments.
		$query_args = array(
			'fields'         => 'ids', // Samo post ID-evi, zbog boljih performansi.
			'post_type'      => $post_type, // post, product ili drugi CPT.
			'posts_per_page' => $posts_num,
			'post_status'    => $post_status, // publish, draft, pending ...
			'date_query'     => array(
				'column' => $post_date,
				'before' => array(
					'year'  => (int) $year,
					'month' => (int) $month,
					'day'   => (int) $day,
				),
			),
		);

		// The Query.
		$query       = new WP_Query( $query_args );
		$posts_found = $query->found_posts;

		if ( 0 === $posts_found ) {
			WP_CLI::warning( 'Nema stavki po zadanim parametrima. Provjerite parametre pa pokušajte ponovo.' );
			die();
		} else {
			// Upozorenje za attachmente.
			if ( 'attachment' === $post_type ) {
				WP_CLI::confirm( '➡️  Brisanje attachmenta ce takodjer brisati i pripadajuce datoteke iz "wp-content/uploads" direktorija. Takodjer, neke novije objave bi mogle koristiti ove attachmente. Jeste li sigurni da zelite nastaviti?' );
			}
			// Zadnja šansa za odustajanje... ;) ;).
			WP_CLI::confirm(
				sprintf(
					'Nađen broj stavki je %s. Jeste li stvarno sigurni da ih želite pobrisati? Još jednom razmislite, provjerite sve parametre - ova radnja se ne može poništiti.',
					$query->found_posts
				),
				$assoc_args
			);
		}

		$successful = 0;
		$failed     = 0;
		// The Loop.
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();

				$id    = get_the_ID();
				$title = get_the_title();

				// KOD ZA BRISANJE
				// Za custom post typove:
				// wp_trash_post( get_the_ID() );
				// Log radnje u WP CLIu.
				WP_CLI::log(
					sprintf(
						'Briše se %s "%s" (ID: %s), objavljeno: %s. Zadnja izmjena: %s',
						strtolower( $post_type_singular ),
						$title,
						$id,
						get_the_date( 'd.m.Y' ),
						get_the_modified_date()
					)
				);

				// Različite delete funckcije ovisno o tome je li attachment ili ostalo.
				if ( 'attachment' === $post_type ) {
					// Brisanje iz media library-a i fajla iz "uploads" foldera.
					$result = wp_delete_attachment( $id, true );
				} else {
					// Brisanje ostalih post typeova.
					$result = wp_delete_post( $id, true );
				}
				if ( $result ) {
					$successful++;
					WP_CLI::log( sprintf( '✅  Izbrisana stavka "%s", %s od %s.', $title, $successful, $posts_found ) );

				} else {
					WP_CLI::log( sprintf( '❌  Greška! Stavka "%s" se nije mogla izbrisati', $title ) );
					$failed++;
				}
				// Razmaknica.
				WP_CLI::log( '================================' );
			}
			// Log radnje u WP CLIu.
			WP_CLI::log( sprintf( 'Izbrisano %s stavki.%s.', $successful, $failed ? ' Neuspješno brisanje ' . $failed . ' stavki.' : '' ) );
		}

		wp_reset_postdata();

		die();
	}

	/**
	 * Optimize database.
	 *
	 * @param array $args - arguments.
	 * @param array $assoc_args - arguments(?).
	 * @return void
	 *
	 * USE THIS WP CLI COMMAND WITH CAUTION - TEST IT FIRST ON LOCAL/STAGING ENVIRNOMENT,
	 * THEN, BEFORE USING ON LIVE SITE, MAKE A BACKUP.
	 */
	public function wpcli_optimize_db( $args, $assoc_args ) {

		WP_CLI::log( '-----------------' );
		WP_CLI::confirm( 'Ovom komandom ćete pokrenuti optimizaciju baze. Jeste li napravili backup baze?' );

		$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

		// Check connection.
		if ( mysqli_connect_errno() ) {
			WP_CLI::error( printf( "Konekcija sa bazom neuspješna: %s\n", mysqli_connect_error() ) );
			exit();
		}

		$query = "SELECT CONCAT('OPTIMIZE TABLE ',
		GROUP_CONCAT(CONCAT(table_schema,'.',table_name)),';') 
		INTO @optimizecmd FROM information_schema.tables
		WHERE table_schema=database(); 
		PREPARE s1 FROM @optimizecmd; EXECUTE s1;";

		if ( $mysqli->multi_query( $query ) ) {
			do {
				/* store first result set */
				if ( $result = $mysqli->store_result() ) {
					while ( $row = $result->fetch_row() ) {
						WP_CLI::log( sprintf( 'Optimizirano: "%s"', $row[0] ) );
					}
					$result->free();
				}
				if ( $mysqli->more_results() ) {
					WP_CLI::log( '-----------------' );
				}
			}
			while ( $mysqli->next_result() );
		}
		$mysqli->close();
		WP_CLI::log( '-----------------' );
		WP_CLI::log( 'Optimizacija gotova.' );

		die();
	}

	/**
	 * Fire a callback only when "product" CPT's are transitioned to 'publish'.
	 *
	 * @param array $data New post data.
	 * @param array $postarr Old post data.
	 *
	 * Info: https://wordpress.org/support/topic/post-status-transition-with-quick-edit-bulk-action/ .
	 */
	public function haddad_product_status_update_log_json( $data, $postarr ) {

		if ( 'publish' === $data['post_status'] && 'publish' !== get_post_status( $postarr['ID'] ) && 'product' === $data['post_type'] ) {

			$format        = 'd.m.Y. H:i:s';
			$id            = $postarr['ID'];
			$title         = get_the_title( $id );
			$editor_id     = get_post_meta( $id, '_edit_last', true );
			$sku           = get_post_meta( $id, '_sku', true );
			$date_modified = get_the_modified_date( $format, $id );
			$current_time  = current_time( $format );

			$current_user_id   = get_current_user_id();
			$current_user_data = get_userdata( $current_user_id );
			$last_user_data    = get_userdata( $editor_id );

			// Current editor.
			$current_edit      = $current_user_data->user_login;
			$current_edit_role = implode( ', ', $current_user_data->roles );
			// Previous (last) editor.
			$last_edit      = $last_user_data->user_login;
			$last_user_role = implode( ', ', $last_user_data->roles );

			/**
			 * JSON LOG.
			 */
			// New data.
			$log_array     = array(
				array(
					'curr_time' => $current_time,
					'mod_time'  => $date_modified,
					'title'     => $title,
					'id'        => $id,
					'sku'       => $sku,
					'curr_edit' => $current_edit,
					'curr_role' => $current_edit_role,
					'last_edit' => $last_edit,
					'last_role' => $last_user_role,
				),
			);

			// File read/write operations, using wp_filesystem.
			$access_type = get_filesystem_method();
			if ( 'direct' === $access_type ) {
				// Safely run request_filesystem_credentials() without any issues and don't need to worry about passing in a URL.
				$creds = request_filesystem_credentials( site_url() . '/wp-admin/', '', false, false, array() );

				// Initialize the API.
				if ( ! WP_Filesystem( $creds ) ) {
					return false;// If problems, abort.
				}

				global $wp_filesystem;
				$upload_dir    = wp_upload_dir();
				$log_json_file = trailingslashit( $upload_dir['basedir'] ) . 'haddad_product_statuses/log.json';
				// If file exists, add new entry to existing data.
				if ( $wp_filesystem->exists( $log_json_file ) ) {
					// Get existing content of JSON file.
					$current_json_arr = json_decode( $wp_filesystem->get_contents( $log_json_file ) );
					// Join new data with the existing.
					$joined_arr = array_merge( $current_json_arr, $log_array );
					// JSON encode it.
					$json_updated = wp_json_encode( $joined_arr );
					// Write the file.
					if ( ! $wp_filesystem->put_contents( $log_json_file, $json_updated, FS_CHMOD_FILE ) ) {
						add_action( 'admin_notices', $this->wp_filesystem_notice( __( 'Cannot write to JSON file.', 'image-haddad-wp' ) ) );
					};
				} else {
					// If file doesn't exist, create it and put only new entry.
					$wp_filesystem->put_contents( $log_json_file, $log_array );
				}

			} else {
				// Don't have direct write access. Prompt user with our notice.
				add_action( 'admin_notices', $this->wp_filesystem_notice( __( 'No direct files access type allowed.', 'image-haddad-wp' ) ) );
			}
			// ---> end log json.
		}

		return $data;
	}

	/**
	 * Admin notice for errors in saving JSON log file.
	 *
	 * @param string $message - error message.
	 * @return void
	 */
	public function wp_filesystem_notice( $message = '' ) {
		$class = 'error updated settings-error notice is-dismissible';
		echo '<div class="' . esc_attr( $class ) . '"><p>' . esc_html( $message ) . '</p></div>';
	}

	/**
	 * Create MySQL trigger for logging product changes (draft > publish).
	 *
	 * Assumes that wpdb_prefix is "hd_" (in "hd_mm_product_updates" table") .
	 * Table "hd_mm_product_updates" table should be created in PhpMyAdmin or MySQL CLI commands.
	 *
	 * @return void
	 */
	public function haddad_product_status_update_log_db() {

		// If MySql (MariaDB) trigger already enabled, dont't re-create the trigger.
		// This is additional check, alredy is checked in includes/class-image-haddad-wp.php.
		$option = get_option( 'haddad_log_product_updates', 'disabled' );
		if ( 'enabled' === $option ) {
			return;
		}

		$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

		// Check connection.
		if ( mysqli_connect_errno() ) {
			printf( "Konekcija sa bazom neuspješna: %s\n", esc_html( mysqli_connect_error() ) );
			exit();
		}

		// First, create table hd_mm_product_updates if it alrady doesn't exist
		// Then create MySQL trigger for logging product updates.
		$mysqli->multi_query(
			"CREATE TABLE IF NOT EXISTS hd_mm_product_updates (
				id   INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				timestamp   TIMESTAMP,
				post_id  INT(15) NOT NULL,
				post_title VARCHAR(255) NOT NULL,
				post_status VARCHAR(255) NOT NULL,
				post_type VARCHAR(255) NOT NULL,
				author VARCHAR(255) DEFAULT NULL
			);
			CREATE TRIGGER micemade_product_updates_log
			AFTER UPDATE
			ON hd_posts FOR EACH ROW
			BEGIN
				IF NEW.post_status = 'publish' AND old.post_status = 'draft' AND old.post_type = 'product' THEN
					INSERT INTO hd_mm_product_updates(post_id,post_title,post_status,post_type,author)
					VALUES(old.ID,old.post_title, new.post_status, old.post_type,( SELECT display_name FROM hd_users WHERE ID = new.post_author ) );
				END IF;
			END;"
		);
		// Flush multi_queries.
		while ( $mysqli->next_result() ) {
			if ( ! $mysqli->more_results() ) {
				break;
			}
		}
		$mysqli->close();

		update_option( 'haddad_log_product_updates', 'enabled', true );

	}

	/**
	 * Remove MySql trigger for logging product changes.
	 *
	 * @return void
	 */
	public function haddad_remove_log_db_trigger() {
		$option = get_option( 'haddad_log_product_updates', 'disabled' );
		// If logging is enabled (option in WP options), and trigger exists, remove it.
		if ( 'enabled' === $option ) {
			global $wpdb;
			$wpdb->query( 'DROP TRIGGER IF EXISTS micemade_product_updates_log' );
			update_option( 'haddad_log_product_updates', 'disabled', false );
		}
	}

	/**
	 * Plugin settings.
	 *
	 * @return void
	 */
	public function load_settings() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/settings/settings-init.php';
	}

}
