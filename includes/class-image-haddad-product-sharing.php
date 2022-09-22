<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main Image_Haddad_Product_Sharing Class
 *
 * @class Image_Haddad_Product_Sharing
 * @package Image_Haddad_Product_Sharing
 *
 * Based on Storfront Product Sharing plugin:
 * https://woocommerce.com/products/storefront-product-sharing/
 */
final class Image_Haddad_Product_Sharing {
	/**
	 * Image_Haddad_Product_Sharing The single instance of Image_Haddad_Product_Sharing.
	 *
	 * @var     object
	 * @access  private
	 * @since   1.0.0
	 */
	private static $_instance = null;

	/**
	 * Constructor function.
	 *
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'product_sharing_setup' ) );
	}

	/**
	 * Main Image_Haddad_Product_Sharing Instance
	 *
	 * Ensures only one instance of Image_Haddad_Product_Sharing is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see image_haddad_product_sharing()
	 * @return Main Image_Haddad_Product_Sharing instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	} // End instance()


	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?' ), '1.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?' ), '1.0.0' );
	}

	/**
	 * Setup all the sharing.
	 *
	 * @return void
	 */
	public function product_sharing_setup() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_social_icons' ), 999 );
		add_action( 'woocommerce_single_product_summary', array( $this, 'product_sharing_html' ), 55 );
		add_filter( 'body_class', array( $this, 'body_classes' ) );
	}

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @since  1.0.6
	 * @param  array $classes Classes for the body element.
	 * @return array
	 */
	public function body_classes( $classes ) {
		$classes[] = 'haddad-social-sharing';
		return $classes;
	}

	/**
	 * Enqueue Social Icons.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function enqueue_social_icons() {
		wp_enqueue_style( 'font-awesome-5-brands', '//use.fontawesome.com/releases/v5.0.13/css/brands.css' );
	}

	/**
	 * Determine which Whatsapp share url to use
	 *
	 * @since    3.2.13
	 */
	private function whatsapp_share_api() {
		if ( $this->check_if_mobile() ) {
			return 'api';
		}
		return 'web';
	}

	/**
	 * Check if webpage is being visited in a mobile device
	 *
	 * @since    3.3.9
	 */
	private function check_if_mobile() {

		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
			// detect the device for Whatsapp share API
			$iphone  = strpos( $_SERVER['HTTP_USER_AGENT'], 'iPhone' );
			$android = strpos( $_SERVER['HTTP_USER_AGENT'], 'Android' );
			$palmpre = strpos( $_SERVER['HTTP_USER_AGENT'], 'webOS' );
			$berry   = strpos( $_SERVER['HTTP_USER_AGENT'], 'BlackBerry' );
			$ipod    = strpos( $_SERVER['HTTP_USER_AGENT'], 'iPod' );
			// Check if it's a mobile.
			if ( $iphone || $android || $palmpre || $ipod || $berry == true ) {
				return true;
			}
		}

		return false;

	}

	/**
	 * Product sharing links
	 */
	public function product_sharing_html() {
		$product_title = get_the_title();
		$product_url   = get_permalink();
		$product_img   = wp_get_attachment_url( get_post_thumbnail_id() );

		$facebook_url  = 'https://www.facebook.com/sharer/sharer.php?u=' . $product_url;
		$twitter_url   = 'https://twitter.com/intent/tweet?status=' . rawurlencode( $product_title ) . '+' . $product_url;
		$pinterest_url = 'https://pinterest.com/pin/create/bookmarklet/?media=' . $product_img . '&url=' . $product_url . '&is_video=false&description=' . rawurlencode( $product_title );
		$wapp_url      = 'https://' . $this->whatsapp_share_api() . '.whatsapp.com/send?text=' . rawurlencode( $product_title . ' ' . $product_url );
		$email_url     = 'mailto:?subject=' . rawurlencode( $product_title ) . '&body=' . $product_url;
		$face_text     = __( 'Share on Facebook', 'haumea-child' );
		$twit_text     = __( 'Share on Twitter', 'haumea-child' );
		$pint_text     = __( 'Pin this product', 'haumea-child' );
		$wapp_text     = __( 'Share on WhatsApp', 'haumea-child' );
		$email_text    = __( 'Share via Email', 'haumea-child' );

		$is_mobile = $this->check_if_mobile();
		$mob_css   = $is_mobile ? ' is-mobile' : '';
		?>
		<div class="ihwp-product-sharing<?php echo esc_attr( $mob_css ); ?>">
			<ul>
				<li class="twitter"><a href="<?php echo esc_url( $twitter_url ); ?>" class="desc tip-top" target="_blank" rel="noopener noreferrer" title="<?php echo esc_attr( $twit_text ); ?>"><strong><?php echo esc_html( $twit_text ); ?></strong></a></li>
				<li class="facebook"><a href="<?php echo esc_url( $facebook_url ); ?>" class="desc tip-top" target="_blank" rel="noopener noreferrer" title="<?php echo esc_attr( $face_text ); ?>"><strong><?php echo esc_html( $face_text ); ?></strong></a></li>
				<?php
				// If it's mobile, include the Whatsapp button.
				if ( $is_mobile ) {
					?>
					<li class="whatsapp"><a href="<?php echo esc_url( $wapp_url ); ?>" class="desc tip-top" target="_blank" rel="noopener noreferrer" title="<?php echo esc_attr( $wapp_text ); ?>" data-action="share/whatsapp/share"><strong><?php echo esc_html( $wapp_text ); ?></strong></a></li>
				<?php } ?>

				<li class="pinterest"><a href="<?php echo esc_url( $pinterest_url ); ?>" class="desc tip-top" target="_blank" rel="noopener noreferrer" title="<?php echo esc_attr( $pint_text ); ?>"><strong><?php echo esc_html( $pint_text ); ?></strong></a></li>
				<li class="email"><a href="<?php echo esc_url( $email_url ); ?>" class="desc tip-top" title="<?php echo esc_attr( $email_text ); ?>"><strong><?php echo esc_html( $email_text ); ?></strong></a></li>
			</ul>
		</div>
		<?php
	}
} // End Class
