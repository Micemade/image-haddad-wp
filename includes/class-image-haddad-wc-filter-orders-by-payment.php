<?php
/**
 * Filters WC orders by payment methods,
 * only enabled method listed.
 *
 * https://gist.github.com/bekarice/41bce677437cb8f312ed77e9f226a812
 *
 * @package  Woocommerce Filter Order by Payment Methods
 * @category Woocommerce
 * @author   SkyVerge, Micemade
 */

defined( 'ABSPATH' ) or exit;

/**
 * Main plugin class
 *
 * @since 1.0.0
 */
class Image_Haddad_WC_Filter_Orders_By_Payment {

	const VERSION = '1.0.0';

	/** @var WC_Filter_Orders_By_Payment single instance of this plugin */
	protected static $instance;

	/**
	 * Main constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		global $typenow;

		if ( is_admin() && 'shop_order' === $typenow ) {
			// Add bulk order filter for exported / non-exported orders.
			add_action( 'restrict_manage_posts', array( $this, 'filter_orders_by_payment_method' ), 20 );
			add_filter( 'request', array( $this, 'filter_orders_by_payment_method_query' ) );
		}
	}

	/**
	 * Add bulk filter for orders by payment method
	 *
	 * @since 1.0.0
	 */
	public function filter_orders_by_payment_method() {

		// Get all payment methods, even inactive ones.
		$gateways = WC()->payment_gateways->payment_gateways();

		?>
		<select name="_shop_order_payment_method" id="dropdown_shop_order_payment_method">
			<option value="">
				<?php esc_html_e( 'Enabled Payment Methods', 'haumea-child' ); ?>
			</option>

			<?php foreach ( $gateways as $id => $gateway ) : ?>

				<?php if ( isset( $gateway->enabled ) && 'yes' === $gateway->enabled ) : ?>

					<option value="<?php echo esc_attr( $id ); ?>" <?php echo esc_attr( isset( $_GET['_shop_order_payment_method'] ) ? selected( $id, $_GET['_shop_order_payment_method'], false ) : '' ); ?>>
						<?php echo esc_html( $gateway->get_method_title() ); ?>
					</option>

				<?php endif; ?>
			<?php endforeach; ?>
		</select>
		<?php

	}


	/**
	 * Process bulk filter order payment method
	 *
	 * @since 1.0.0
	 *
	 * @param array $vars query vars without filtering
	 * @return array $vars query vars with (maybe) filtering
	 */
	public function filter_orders_by_payment_method_query( $vars ) {

		if ( isset( $_GET['_shop_order_payment_method'] ) && ! empty( $_GET['_shop_order_payment_method'] ) ) {

			$vars['meta_key']   = '_payment_method';
			$vars['meta_value'] = wc_clean( $_GET['_shop_order_payment_method'] );
		}

		return $vars;
	}

	/**
	 * Main WC_Filter_Orders_By_Payment Instance, ensures only one instance is/can be loaded
	 *
	 * @since 1.0.0
	 * @see image_haddad_wc_filter_orders_by_payment()
	 * @return Image_Haddad_WC_Filter_Orders_By_Payment
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}
