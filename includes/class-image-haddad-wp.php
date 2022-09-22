<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://micemade.com
 * @since      1.0.0
 *
 * @package    Image_Haddad_Wp
 * @subpackage Image_Haddad_Wp/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Image_Haddad_Wp
 * @subpackage Image_Haddad_Wp/includes
 * @author     Micemade <alen@micemade.com>
 */
class Image_Haddad_Wp {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Image_Haddad_Wp_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Plugin public class instance.
	 *
	 * @var object
	 */
	private $plugin_public;

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
	 * The plugin path.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $path    This plugin path.
	 */
	private $path;

	/**
	 * Custom Google Analytics code.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      bool   boolean if custom GA is enabled.
	 */
	private $custom_google_analytics = false;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		if ( defined( 'IMAGE_HADDAD_WP_VERSION' ) ) {
			$this->version = IMAGE_HADDAD_WP_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'image-haddad-wp';

		// Plugin path ( TO DO: change with the constant).
		$this->path = plugin_dir_path( dirname( __FILE__ ) );

		// Get options, grouped in sections.
		$this->ihwp_wc_basic      = get_option( 'ihwp_wc_basic' );
		$this->ihwp_wc_options    = get_option( 'ihwp_wc' );
		$this->ihwp_css_options   = get_option( 'ihwp_css' );
		$this->ihwp_other_options = get_option( 'ihwp_other' );

		// Set GA option here for couple of methods using it.
		if ( isset( $this->ihwp_wc_basic['enable_ga'] ) && 'on' === $this->ihwp_wc_basic['enable_ga'] ) {
			$this->custom_google_analytics = true;
		}

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		// Plugin compatiblities: Facet WP, Aelia Currency Switcher.
		add_action( 'plugins_loaded', array( $this, 'plugins' ) );

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Image_Haddad_Wp_Loader. Orchestrates the hooks of the plugin.
	 * - Image_Haddad_Wp_I18n. Defines internationalization functionality.
	 * - Image_Haddad_Wp_Admin. Defines all hooks for the admin area.
	 * - Image_Haddad_Wp_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once $this->path . 'includes/class-image-haddad-wp-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once $this->path . 'includes/class-image-haddad-wp-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once $this->path . 'admin/class-image-haddad-wp-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once $this->path . 'public/class-image-haddad-wp-public.php';

		$this->loader = new Image_Haddad_Wp_Loader();

		/**
		 * The class add js dataLayer object on order confirmation page.
		 * Require the file if set in plugin settings.
		 */
		if ( file_exists( $this->path . 'includes/class-image-haddad-wc-order-datalayer.php' ) && $this->custom_google_analytics ) {
			require_once $this->path . 'includes/class-image-haddad-wc-order-datalayer.php';
		}

		/**
		 * The class for (eventual) social sharing in single product page.
		 */
		if ( file_exists( $this->path . 'includes/class-image-haddad-product-sharing.php' ) ) {
			require_once $this->path . 'includes/class-image-haddad-product-sharing.php';
		}

		/**
		 * The class for admin menu and page with listing ordered products.
		 */
		if ( file_exists( $this->path . 'includes/class-image-haddad-order-products.php' ) ) {
			require_once $this->path . 'includes/class-image-haddad-order-products.php';
		}

		/**
		 * The class filter WC orders by enabled payment.
		 */
		if ( file_exists( $this->path . 'includes/class-image-haddad-wc-filter-orders-by-payment.php' ) ) {
			require_once $this->path . 'includes/class-image-haddad-wc-filter-orders-by-payment.php';
		}
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Image_Haddad_Wp_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Image_Haddad_Wp_I18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Image_Haddad_Wp_Admin( $this->get_plugin_name(), $this->get_version() );

		// Load settings for this plugin.
		$this->loader->add_action( 'init', $plugin_admin, 'load_settings' );

		// Load admin styles and scripts.
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// ==== Plugins page ==== /
		// Add "Settings" action link in plugins page.
		$this->loader->add_filter( 'plugin_action_links_' . IMAGE_HADDAD_BASENAME, $plugin_admin, 'plugins_page_settings_link', 10, 1 );
		// Add row under the this plugin, in the plugins list table.
		$this->loader->add_action( 'after_plugin_row_' . IMAGE_HADDAD_BASENAME, $plugin_admin, 'plugins_page_add_row', 10, 3 );
		// Additional meta for plugin row (under the plugin description).
		$this->loader->add_filter( 'plugin_row_meta', $plugin_admin, 'plugins_page_row_meta', 10, 4 );
		// --- end plugins page hooks.

		// ==== PLUGIN: WOOCOMMERCE =====/
		// ==== Custom shop order statuses ====.
		// Add link to shop order status filters (order admin listing page top).
		$this->loader->add_filter( 'views_edit-shop_order', $plugin_admin, 'haddad_new_order_filter' );
		// Add Custom Order Status to the Dropdown @ Single order.
		$this->loader->add_filter( 'wc_order_statuses', $plugin_admin, 'haddad_show_custom_order_status' );
		// Add Custom Order Status to the Dropdown "Bulk Actions" @ Orders.
		$this->loader->add_filter( 'bulk_actions-edit-shop_order', $plugin_admin, 'haddad_get_custom_order_status_bulk' );
		// Replace "Processing" orders count with "New order" (custom order status) count in WC menu.
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'haddad_menu_order_count' );
		// --- end WC custom order status methods.

		// ==== Custom order actions in admin meta box ====.
		$this->loader->add_action( 'woocommerce_order_actions', $plugin_admin, 'custom_order_actions' );
		$this->loader->add_action( 'woocommerce_order_action_wc_customer_resend_order_confirmation', $plugin_admin, 'customer_resend_order_confirmation' );
		// ==== end custom order actions ==== .

		// ==== COUPON CUSTOM METHODS ====.
		// Meta box on coupon edit.
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'haddad_coupon_price_cache' );
		// Additional tab in main coupon edit box.
		$this->loader->add_filter( 'woocommerce_coupon_data_tabs', $plugin_admin, 'haddad_coupon_data' );
		// Content box for additional tab.
		$this->loader->add_action( 'woocommerce_coupon_data_panels', $plugin_admin, 'haddad_coupon_data_panel', 10, 2 );
		// Save the additional coupon data.
		$this->loader->add_filter( 'woocommerce_process_shop_coupon_meta', $plugin_admin, 'haddad_coupon_save_data', 10, 1 );
		// --- end coupon methods.

		// Product limit on ajax search (on admin pages).
		$this->loader->add_filter( 'woocommerce_json_search_limit', $plugin_admin, 'haddad_product_search_limit' );

		// Automatic assign terms on post (product) save when changing status draft to publish.
		$this->loader->add_action( 'draft_to_publish', $plugin_admin, 'haddad_assign_term_on_save' );

		// ==== SCHEDULE (CRON JOBS) ====.
		// Automatic remove term(s) after time period.
		$this->loader->add_action( 'haddad_remove_term', $plugin_admin, 'haddad_remove_term_f' );
		// Schedule hook for term(s) removal.
		$this->loader->add_action( 'wp', $plugin_admin, 'haddad_schedule_remove_term' );
		// --- end schedule jobs.

		// Admin menu and page for listing products in orders.
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'haddad_admin_wc_order_products' );

		// Filter by enabled payment type in admin orders page.
		$this->loader->add_action( 'admin_init', $plugin_admin, 'haddad_wc_filter_orders_by_payment' );

		// SINGLE PRODUCT PAGE - hide upsell products notice.
		$options_wc   = $this->ihwp_wc_options;
		$hide_upsells = isset( $options_wc['hide_upsells'] ) ? $options_wc['hide_upsells'] : 'on';
		if ( 'on' === $hide_upsells ) {
			$this->loader->add_action( 'woocommerce_product_options_related', $plugin_admin, 'haddad_removed_upsells_notice' );
		}
		// --- end PLUGIN: WOOCOMMERCE.

		// ==== PLUGIN : Print Invoice & Delivery Notes for WooCommerce
		// Add the field for order printing
		$this->loader->add_filter( 'wcdn_order_info_fields', $plugin_admin, 'haddad_custom_orderprint_fields', 10, 2 );
		// Add the product image to custom print order.
		$this->loader->add_action( 'wcdn_order_item_before', $plugin_admin, 'haddad_custom_orderprint_product_image' );
		// Apply styles to "Print Invoice & Delivery Notes for WooCommerce" plugin output.
		$this->loader->add_action( 'wcdn_head', $plugin_admin, 'haddad_print_logo_style', 20 );
		// --- end plugin Print Invoice.

		// Custom WP CLI commands.
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			// Delete posts (or CPT's) older than given date.
			WP_CLI::add_command( 'haddad-delete-before', array( $plugin_admin, 'wpcli_delete_before' ) );
			// Optimize database (USE WITH CAUTION - backup database first!).
			WP_CLI::add_command( 'haddad-optimize-db', array( $plugin_admin, 'wpcli_optimize_db' ) );
		}

		// Log product edit changes (when product changes from draft to publish).
		$options_other = $this->ihwp_other_options;
		// Log in JSON file.
		$enable_json_log = ( isset( $options_other['enable_json_log'] ) && 'on' === $options_other['enable_json_log'] ) ? true : false;
		if ( $enable_json_log ) {
			$this->loader->add_filter( 'wp_insert_post_data', $plugin_admin, 'haddad_product_status_update_log_json', 10, 2 );
		}

		// Create log in database. Logs product changes when product status is set to publish from draft.
		$enable_db_log = ( isset( $options_other['enable_db_log'] ) && 'on' === $options_other['enable_db_log'] ) ? true : false;
		$option_db_log = get_option( 'haddad_log_product_updates', 'disabled' );
		// If logging is enabled in settings, and not enabled in WP options.
		if ( $enable_db_log && 'enabled' !== $option_db_log ) {
			$plugin_admin->haddad_product_status_update_log_db();
		}
		// Remove MySQL trigger, if not enabled in plugin, and WP options still show 'enabled'.
		if ( ! $enable_db_log && 'enabled' === $option_db_log ) {
			$plugin_admin->haddad_remove_log_db_trigger();
		}

		// /////////////// DEBUG ////////////////////////
		// Testing custom order meta.
		// $this->loader->add_action( 'woocommerce_admin_order_data_after_order_details', $plugin_admin, 'order_meta' );
		// /////////////// DEBUG ////////////////////////
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$this->plugin_public = new Image_Haddad_Wp_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $this->plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $this->plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_enqueue_scripts', $this->plugin_public, 'coupon_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $this->plugin_public, 'sale_price_styles' );

		// Including product categories and tags in search term.
		$this->loader->add_filter( 'posts_search', $this->plugin_public, 'woocommerce_search_product_tag_extended', 999, 2 );

		/**
		 * =======================
		 * ===== WOOCOMMERCE =====
		 * =======================
		 */

		// WooCommerce product query - ensure no draft products in wc loop.
		$this->loader->add_action( 'woocommerce_product_query', $this->plugin_public, 'haddad_no_drafts_in_wc_loop' );

		// Register custom shop order statuses.
		$this->loader->add_filter( 'woocommerce_register_shop_order_post_statuses', $this->plugin_public, 'haddad_register_custom_order_status' );
		// Set Custom Order Status @ WooCommerce Checkout Process.
		$this->loader->add_action( 'woocommerce_thankyou', $this->plugin_public, 'haddad_thankyou_change_order_status' );

		// Get plugin options (settings).
		$options_wc      = $this->ihwp_wc_options;
		$ccsp_priority_1 = isset( $options_wc['ccsp_priority_1'] ) ? $options_wc['ccsp_priority_1'] : 36; // Hook priority setting.
		$ccsp_priority_2 = isset( $options_wc['ccsp_priority_2'] ) ? $options_wc['ccsp_priority_2'] : 35; // Hook priority setting.

		/**
		 * WOOCOMMERCE SINGLE PRODUCT PAGE
		 */
		// SINGLE PRODUCT PAGE - Custom HTML to product summary - two additional settings with priority setting.
		$this->loader->add_action( 'woocommerce_single_product_summary', $this->plugin_public, 'sp_custom_html', $ccsp_priority_1 );
		$this->loader->add_filter( 'woocommerce_single_product_summary', $this->plugin_public, 'sp_custom_html_2', $ccsp_priority_2 );

		// SINGLE PRODUCT PAGE -- display universal size attribute, first (before all summary data).
		$this->loader->add_action( 'woocommerce_single_product_summary', $this->plugin_public, 'haddad_universal_size_attribute', 10 );

		// SINGLE PRODUCT PAGE -- Stanje u skladistima, putem ERP Api-a.
		$this->loader->add_action( 'woocommerce_single_product_summary', $this->plugin_public, 'init_stanje_u_skladistima', 35 );
		$this->loader->add_action( 'wp_ajax_nopriv_stanje_u_skladistima', $this->plugin_public, 'stanje_u_skladistima' ); // for NOT logged in users.
		$this->loader->add_action( 'wp_ajax_stanje_u_skladistima', $this->plugin_public, 'stanje_u_skladistima' ); // for logged in users.

		// SINGLE PRODUCT PAGE -- rearrange tabs.
		$this->loader->add_filter( 'woocommerce_product_tabs', $this->plugin_public, 'haddad_reorder_tabs', 98 );

		// SINGLE PRODUCT PAGE - hide upsell products.
		$hide_upsells = isset( $options_wc['hide_upsells'] ) ? $options_wc['hide_upsells'] : 'on';
		if ( 'on' === $hide_upsells ) {
			$this->loader->add_action( 'init', $this->plugin_public, 'haddad_remove_upsells' );
		}

		// SINGLE PRODUCT PAGE - Remove out of stock products from related products.
		$this->loader->add_filter( 'woocommerce_related_products', $this->plugin_public, 'haddad_no_out_of_stock_in_related', 10, 3 );

		// SINGLE PRODUCT PAGE - change titles of upsales and related (switch upsells and related text).
		$this->loader->add_filter( 'woocommerce_product_upsells_products_heading', $this->plugin_public, 'haddad_upsells_heading' );
		$this->loader->add_filter( 'woocommerce_product_related_products_heading', $this->plugin_public, 'haddad_related_heading' );
		// Add "local"variable, valid only for hooks inside the single product summary.
		$this->loader->add_action( 'woocommerce_before_single_product_summary', $this->plugin_public, 'start_product_summary', 10 );
		$this->loader->add_action( 'woocommerce_after_single_product_summary', $this->plugin_public, 'end_product_summary', 1 );
		$this->loader->add_action( 'woocommerce_single_product_summary', $this->plugin_public, 'haddad_fabriq_logo', 35 );

		/**
		 * Theme dependencies:
		 * - Subcategories menu on category pages, before loop.
		 * - Catalog/categories page - add badge "New" on catalog/categories products.
		 * - Fabriq/Image Haddad logo on catalog and single product.
		 * - Flatsome / YITH Wishlist icon.
		 */
		$current_theme = wp_get_theme()->get( 'Template' );
		if ( 'flatsome' === $current_theme ) {
			// Categories (catalog).
			$this->loader->add_action( 'woocommerce_before_main_content', $this->plugin_public, 'catalog_sub_categories', 10 );
			// Product loop.
			$this->loader->add_action( 'flatsome_woocommerce_shop_loop_images', $this->plugin_public, 'haddad_new_product', 12 );
			$this->loader->add_action( 'flatsome_woocommerce_shop_loop_images', $this->plugin_public, 'haddad_fabriq_logo', 13 );
			// Single product.
			$this->loader->add_action( 'woocommerce_after_add_to_cart_button', $this->plugin_public, 'flatsome_wishlist_icon', 2 );
			$this->loader->add_action( 'wp_enqueue_scripts', $this->plugin_public, 'flatsome_wishlist_icon_css' );

		} elseif ( 'haumea' === $current_theme ) {
			$this->loader->add_action( 'woocommerce_before_shop_loop_item_title', $this->plugin_public, 'haddad_new_product', 10, 2 );
			$this->loader->add_action( 'haumea_before_shop_loop_item_title', $this->plugin_public, 'haddad_fabriq_logo', 20 );
		}

		/**
		 * WOOCOMMERCE CART
		 */
		// Locate WC template in this plugin.
		$this->loader->add_filter( 'wc_get_template', $this->plugin_public, 'ihwp_wc_templates', 10, 5 );

		// CART - cart items with coupon prices.
		$this->loader->add_filter( 'woocommerce_cart_item_subtotal', $this->plugin_public, 'haddad_cart_item_coupon_subtotal', 99, 3 );

		// CART/products loop - show coupon prices in loop products.
		$this->loader->add_action( 'woocommerce_after_shop_loop_item_title', $this->plugin_public, 'haddad_coupon_after_price', 16 );
		$this->loader->add_action( 'woocommerce_single_product_summary', $this->plugin_public, 'haddad_coupon_after_price', 16 );

		// CART page cross-sells functions.
		$this->loader->add_filter( 'haddad_cross_sells', $this->plugin_public, 'haddad_get_related_to_cart' );
		$this->loader->add_filter( 'woocommerce_cross_sells_total', $this->plugin_public, 'flatsome_change_cross_sells_product_no' );
		$this->loader->add_filter( 'woocommerce_cross_sells_columns', $this->plugin_public, 'flatsome_change_cross_sells_columns' );

		// CART page added loop with sale products.
		$this->loader->add_action( 'woocommerce_cart_is_empty', $this->plugin_public, 'haddad_empty_cart_sale_loop', 20 );

		// CART Cross-sale title.
		$this->loader->add_filter( 'woocommerce_product_cross_sells_products_heading', $this->plugin_public, 'cross_sale_title' );

		/**
		 * WOOCOMMERCE CHECKOUT
		 */
		// CHECKOUT - hide coupon form on checkout page.
		$this->loader->add_action( 'woocommerce_before_checkout_form', $this->plugin_public, 'haddad_remove_checkout_coupon_form', 9 );
		// CHECKOUT - additional table row with coupon(s) discount applied to subtotals. Hook in this plugin WC template.
		$this->loader->add_action( 'haddad_coupon_subtotal', $this->plugin_public, 'haddad_coupons_applied_to_subtotal' );

		/**
		 * WOOCOMMERCE ORDER RECEIVED / THANK YOU PAGE
		 */
		// Custom text on order received page.
		$this->loader->add_filter( 'woocommerce_thankyou_order_received_text', $this->plugin_public, 'change_order_received_text', 10, 2 );
		// Attach PDF file with terms and conditions to email.
		$this->loader->add_filter( 'woocommerce_email_attachments', $this->plugin_public, 'haddad_attach_terms_conditions_pdf_to_email', 10, 3 );
		// Change (cancelled) order email notification heading and subject.
		$this->loader->add_action( 'woocommerce_order_status_cancelled', $this->plugin_public, 'haddad_notification_status_canceled', 20, 2 );

		// Remove product reviews.
		$this->loader->add_action( 'init', $this->plugin_public, 'haddad_remove_reviews' );

		// Exclude array of categories (terms) in Product categories widget.
		$this->loader->add_filter( 'woocommerce_product_categories_widget_args', $this->plugin_public, 'haddad_exclude_product_cat_widget' );

		/**
		 * ===== end WooCommerce hooks.
		 */

		// PLUGIN: "WooCommerce Table Rate Shipping" : apply limits after coupon are applied.
		add_filter( 'woocommerce_table_rate_compare_price_limits_after_discounts', '__return_true', 1 );

		// ==== GOOGLE TRACKING AND ANALYTICS ====.
		$this->loader->add_action( 'wp_head', $this->plugin_public, 'haddad_ga_gtagjs' );
		$this->loader->add_action( 'wp_head', $this->plugin_public, 'haddad_gtm' );
		$this->loader->add_action( 'wp_body_open', $this->plugin_public, 'haddad_gtm_iframe' );

		// ==== MULTILANGUAGE/MULTICURRENCY ====.
		$this->loader->add_filter( 'wpseo_robots', $this->plugin_public, 'haddad_hide_en_pages', 999 );

		// PLUGIN: "Kirki": disable telemetry.
		add_filter( 'kirki_telemetry', '__return_false' );

		// Disable WordPress lazy load images on homepage (product slider issue).
		$this->loader->add_filter( 'wp_lazy_loading_enabled', $this->plugin_public, 'no_lazy_load_on_homepage' );
		// Filter 'wp_lazy_loading_enabled' fallback.
		$this->loader->add_filter( 'wp_get_attachment_image_attributes', $this->plugin_public, 'nolazyload_fallback', 10, 3 );

		$this->loader->add_filter( 'wp_omit_loading_attr_threshold', $this->plugin_public, 'omit_lazyload_num' );

		// Remove reCaptcha from all pages except the ones with cf7 shortcode.
		$this->loader->add_action( 'wp_print_scripts', $this->plugin_public, 'haddad_recaptcha_only_cf7' );

		// Remove comments.
		$this->plugin_public->remove_comments();

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Image_Haddad_Wp_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Plugin custom codes.
	 *
	 * @since     1.0.0
	 * @return void
	 */
	public function plugins() {
		// PLUGIN: FWP i FWP/Polylang kompatibilnost.
		if ( file_exists( $this->path . 'includes/class-image-haddad-fwp.php' ) && function_exists( 'FWP' ) ) {
			include $this->path . 'includes/class-image-haddad-fwp.php';
		}

		/**
		 * PLUGIN: WC_Aelia_CurrencySwitcher.
		 */
		if ( file_exists( $this->path . 'includes/class-image-haddad-aelia.php' ) && class_exists( 'WC_Aelia_CurrencySwitcher' ) ) {
			include $this->path . 'includes/class-image-haddad-aelia.php';
		}// end if class_exists 'WC_Aelia_CurrencySwitcher'.
	}

}
