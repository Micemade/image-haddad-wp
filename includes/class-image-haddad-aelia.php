<?php
/**
 * Aelia Currency Switcher functions
 *
 * @see     https://gist.github.com/mgibbs189/2c394563084ad3fc8c39df1e4b67ead7
 * @author  FacetWP, LLC, Micemade
 * @package Image Haddad WP/Includes
 * @version 1.0.0
 */

defined( 'ABSPATH' ) or exit;

/**
 * Class Image_Haddad_Aelia
 */
class Image_Haddad_Aelia {

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Intialize
	 */
	public function init() {

		// Ajax WC cart fragments on checkout page.
		add_filter( 'wc_cart_fragments_params', array( $this, 'aelia_checkout_wc_cart_fragments' ), 10, 1 );

		// Setting (forcing) checkout currency.
		add_filter( 'wc_aelia_cs_selected_currency', array( $this, 'set_checkout_currency' ), 50, 1 );

		// Add some inline code to wp_head.
		add_action( 'wp_head', array( $this, 'wp_head_output' ) );

		// Add some inline code to wp_footer.
		add_action( 'wp_footer', array( $this, 'wp_footer_output' ), 30 );

		// Check for Aelia Foundation Classes (common/shared classes for Aelia plugins).
		if ( class_exists( 'WC_AeliaFoundationClasses' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice' ) );
		}

		// Themes support.
		$this->themes();

	}

	/**
	 * Currency switcher shortcode
	 *
	 * @return void
	 */
	function currency_switcher_hook() {
		// If Customizer lang selector is off, turn off the currency switcher too.
		$lang_sel = get_theme_mod( 'display_currency', false );
		if ( ! $lang_sel ) {
			return;
		}
		echo '<div class="header-button to-sticky">';
		echo do_shortcode( '[aelia_currency_selector_widget title="" widget_type="buttons"]' );
		echo '</div>';
	}

	/**
	 * Checkout ajax for cart fragments.
	 *
	 * @param array $params
	 * @return $params
	 */
	public function aelia_checkout_wc_cart_fragments( $params ) {
		if ( is_checkout() ) {
			if ( strpos( $params['wc_ajax_url'], '?' ) > 0 ) {
				$params['wc_ajax_url'] .= '&';
			} else {
				$params['wc_ajax_url'] .= '?';
			}
			// Add a parameter to the Ajax request, to keep track that it was triggered
			// on the checkout page.
			$params['wc_ajax_url'] .= 'aelia_context=checkout';
		}
		return $params;
	}

	/**
	 * Determines if we are processing an Ajax request triggered on the checkout page.
	 *
	 * @return bool
	 */
	public function is_checkout_ajax_request() {
		if ( defined( 'DOING_AJAX' ) ) {
			// The "update_order_review" request updates the totals on the checkout page.
			if ( 'update_order_review' === $_REQUEST['wc-ajax'] ) {
				return true;
			}

			// The "get_refreshed_fragments" updates the minicart. We only need to take
			// it into account if we are on the checkout page.
			if ( ( 'get_refreshed_fragments' === $_REQUEST['wc-ajax'] ) && ( 'checkout' === $_REQUEST['aelia_context'] ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Forces the checkout currency (to USD, for example).
	 *
	 * @param string selected_currency The currency selected originally.
	 * @return string
	 */
	function set_checkout_currency( $selected_currency ) {
		// If we are on the checkout page, or handling an Ajax request from that page,
		// force the currency to USD
		if ( is_checkout() || $this->is_checkout_ajax_request() ) {
			$selected_currency = 'USD';
		}
		return $selected_currency;
	}

	/**
	 * Add inline JS for FWP
	 *
	 * @return void
	 */
	public function wp_head_output() {
		?>
		<script>
			(function($) {
				$(function() {
					// Add some jQuery code here.
				});

			})(jQuery);
		</script>
		<?php
	}

	/**
	 * Put some inline code to WP_footer
	 */
	public function wp_footer_output() {
		?>
		<script>
			(function($) {
				$(function() {
					// Add some jQuery code here.
				});

			})(jQuery);
		</script>
		<?php
	}


	/**
	 * Warning to install Aelia Foundation Classes for WooCommerce.
	 */
	public function admin_notice() {
		$afc_plugin_url = 'https://bit.ly/WC_AFC_S3';
		?>
		<div class="error">
			<p><code>Aelia Currency Switcher for WooCommerce</code> requires additional <a href="<?php echo esc_url( $afc_plugin_url ); ?>plugin-install.php?tab=search&s=aelia">Aelia Foundation Classes for WooCommerce</a> plugin. Please install and/or activate it.</p>
		</div>
		<?php
	}

	/**
	 * Themes support.
	 *
	 * @return void
	 */
	public function themes() {

		$current_theme = wp_get_theme()->get( 'Template' );
		$theme_path    = get_parent_theme_file_path();

		// Legacy for Haumea theme.
		if ( 'haumea' === $current_theme ) {
			// Haumea theme - hook to add curr. switcher to top header buttons.
			add_action( 'haumea_topbar_buttons', array( $this, 'currency_switcher_hook' ), 4 );
		}

	}

}

/**
 * Instantiate the class.
 *
 * @return void
 */
function image_haddad_aelia() {
	return new Image_Haddad_Aelia();
}
// Initialize the class.
image_haddad_aelia();
