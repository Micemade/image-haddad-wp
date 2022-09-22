<?php
/**
 * Pushes completed orders into a javascript object
 * named datalayer for Google Tag Manager to use.
 *
 * https://github.com/framedigital/woocommerce-order-datalayer
 *
 * @package  Woocommerce Order Datalayer
 * @category Woocommerce
 * @author   Frame Creative, Micemade
 */

/**
 * Data layer class.
 */
class Image_Haddad_WC_Order_Datalayer {

	/**
	 * Instance
	 *
	 * @var [type]
	 */
	protected static $_instance = null;

	/**
	 * Array of vars for dataLayer JS object
	 *
	 * @var array
	 */
	public $data_layer = array();

	/**
	 * Class construct
	 */
	public function __construct() {
		if ( ! $this->has_valid_requirements() ) {
			add_action( 'admin_notices', array( $this, 'admin_notice' ) );
			return;
		}
		add_action( 'wp_head', array( $this, 'init' ), 5 );
	}

	/**
	 * Initialize class
	 *
	 * @return void
	 */
	public function init() {
		if ( ! is_order_received_page() ) {
			return;
		}
		$this->setup_data_layer();
		$this->add_to_wphead();
	}

	/**
	 * Check requirements WC is active and version
	 *
	 * @return boolean
	 */
	private function has_valid_requirements() {
		return class_exists( 'WooCommerce' ) && floatval( WC()->version ) >= 2.6;
	}

	/**
	 * Admin notice
	 *
	 * @return void
	 */
	public function admin_notice() {
		echo "<div class='notice notice-warning is-dismissible'>
                <p>Woocommerce Order Datalayer requires at least Woocommerce 2.6</p>
                <button type='button' class='notice-dismiss'>
                    <span class='screen-reader-text'>Dismiss this notice.</span>
                </button>
            </div>";
	}

	/**
	 * Add JS outpu into head tag
	 *
	 * @return void
	 */
	private function add_to_wphead() {
		add_action( 'wp_head', array( $this, 'output' ), 30 );
	}

	/**
	 * Output the JS
	 *
	 * @return void
	 */
	public function output() {
		$data_layer = apply_filters( 'woocommerce_order_datalayer', $this->data_layer );

		if ( ! empty( $data_layer ) ) {
			$encoded_datalayer = json_encode( $data_layer );
			$script_tag        = '<script data-cfasync="false" type="text/javascript">dataLayer.push( %s );</script>';
			echo sprintf( $script_tag, $encoded_datalayer );
		}
	}

	/**
	 * Setting up datalayer
	 *
	 * @return void
	 */
	private function setup_data_layer() {
		$order_id  = $this->get_order_id();
		$order_key = apply_filters( 'woocommerce_thankyou_order_key', empty( $_GET['key'] ) ? '' : wc_clean( $_GET['key'] ) );

		if ( $order_id > 0 ) {
			$this->order = new WC_Order( $order_id );

			if ( $this->get_order_key() !== $order_key ) {
				unset( $this->order );
			}
		}

		// Make sure the order is only tracked once.
		$_ga_tracked = get_post_meta( $order_id, '_ga_tracked', true );
		if ( true === (bool) $_ga_tracked ) {
			unset( $this->order );
		}

		// If order is set, set order objects.
		if ( isset( $this->order ) ) {
			$this->set_general_order_objects();
			$this->set_order_items_objects();
			// update post meta to check in case of multiple tracking.
			update_post_meta( $order_id, '_ga_tracked', 1 );
		}
	}

	/**
	 * Get the order ID
	 *
	 * @return string $order_id
	 */
	private function get_order_id() {
		$order_id = empty( $_GET['order'] ) ? ( $GLOBALS['wp']->query_vars['order-received'] ? $GLOBALS['wp']->query_vars['order-received'] : 0 ) : absint( $_GET['order'] );

		$order_id_filtered = apply_filters( 'woocommerce_thankyou_order_id', $order_id );
		if ( '' !== $order_id_filtered ) {
			$order_id = $order_id_filtered;
		}
		return $order_id;
	}

	/**
	 * Setting order items object
	 *
	 * @return void
	 */
	private function set_order_items_objects() {
		if ( $this->order->get_items() ) {
			$_products      = array();
			$_sumprice      = 0;
			$_product_ids   = array();
			$_product_names = array();

			foreach ( $this->order->get_items() as $item ) {
				$product     = $this->get_product( $item );
				$product_id  = $product->get_id();
				$product_sku = $product->get_sku();

				$product_categories = get_the_terms( $product_id, 'product_cat' );

				if ( ( is_array( $product_categories ) ) && ( count( $product_categories ) > 0 ) ) {
					$product_cat = array_pop( $product_categories );
					$product_cat = $product_cat->name;
				} else {
					$product_cat = '';
				}

				$product_price = $this->order->get_item_total( $item );
				$product_data  = array(
					'id'       => (string) $product_id,
					'name'     => $item['name'],
					'sku'      => $product_sku ? $product_sku : $product_id,
					'category' => $product_cat,
					'price'    => $product_price,
					'currency' => get_woocommerce_currency(),
					'quantity' => $item['qty'],
				);

				$_products[]      = $product_data;
				$_sumprice       += $product_price * $product_data['quantity'];
				$_product_ids[]   = $product_id;
				$_product_names[] = $item['name'];
			}

			$this->data_layer['transactionProductIDs']             = implode( ', ', $_product_ids );
			$this->data_layer['transactionProductNames']           = implode( ', ', $_product_names );
			$this->data_layer['transactionProducts']               = $_products;
			$this->data_layer['ecommerce']['purchase']['products'] = $_products;
			$this->data_layer['event']                             = 'orderCompleted';
			$this->data_layer['ecomm_prodid']                      = $_product_ids;
			$this->data_layer['ecomm_pagetype']                    = 'purchase';
			$this->data_layer['ecomm_totalvalue']                  = (float) $_sumprice;
		}
	}

	/**
	 * Set general order objects
	 *
	 * @return void
	 */
	private function set_general_order_objects() {
		$this->data_layer['transactionId']             = (string) $this->order->get_order_number();
		$this->data_layer['transactionDate']           = date( 'c' );
		$this->data_layer['transactionType']           = 'sale';
		$this->data_layer['transactionAffiliation']    = html_entity_decode( get_bloginfo( 'name' ), ENT_QUOTES, 'utf-8' );
		$this->data_layer['transactionTotal']          = $this->order->get_total();
		$this->data_layer['transactionShipping']       = $this->get_shipping_total();
		$this->data_layer['transactionTax']            = $this->order->get_total_tax();
		$this->data_layer['transactionPaymentType']    = $this->get_payment_method_title();
		$this->data_layer['transactionCurrency']       = get_woocommerce_currency();
		$this->data_layer['transactionShippingMethod'] = $this->order->get_shipping_method();
		$this->data_layer['transactionPromoCode']      = implode( ', ', $this->order->get_coupon_codes() );
	}

	/**
	 * Instance
	 *
	 * @return $_instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	// WooCommerce 2/3 compatibility methods.

	/**
	 * Check if WC version is higher then 3.0.0
	 *
	 * @return boolean
	 */
	private function is_woo_3() {
		return abs( (float) WC()->version ) >= 3;
	}

	/**
	 * Get order key (WC>= 3)
	 *
	 * @return void
	 */
	private function get_order_key() {
		return $this->is_woo_3() ? $this->order->get_order_key() : $this->order->order_key;
	}

	/**
	 * Get product
	 *
	 * @param [object] $item - php object.
	 * @return void
	 */
	private function get_product( $item ) {
		return $this->is_woo_3() ? $item->get_product() : $this->order->get_product_from_item( $item );
	}

	/**
	 * Get shipping total (WC>= 3)
	 *
	 * @return void
	 */
	private function get_shipping_total() {
		return $this->is_woo_3() ? $this->order->get_shipping_total() : $this->order->get_total_shipping();
	}

	/**
	 * Get payment method title (WC>= 3)
	 *
	 * @return void
	 */
	private function get_payment_method_title() {
		return $this->is_woo_3() ? $this->order->get_payment_method_title() : $this->order->payment_method_title;
	}
}

/**
 * Start the dataLayer Class
 *
 * @return void
 */
function image_haddad_wp_data_layer() {
	// Check for staging or local dev constant - if defined abort. Else, run DataLayer class.
	if ( defined( 'KINSTA_DEV_ENV' ) && KINSTA_DEV_ENV || defined( 'MICEMADE_DEV_ENV' ) && MICEMADE_DEV_ENV ) {
		return;
	} else {
		return Image_Haddad_WC_Order_Datalayer::instance();
	}

}

add_action( 'init', 'image_haddad_wp_data_layer', 1 );
