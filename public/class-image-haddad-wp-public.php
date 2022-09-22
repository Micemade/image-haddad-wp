<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://micemade.com
 * @since      1.0.0
 *
 * @package    Image_Haddad_Wp
 * @subpackage Image_Haddad_Wp/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Image_Haddad_Wp
 * @subpackage Image_Haddad_Wp/public
 * @author     Micemade <alen@micemade.com>
 */
class Image_Haddad_Wp_Public {

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
	 * The plugin options (other settings).
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $ihwp_other_options    The plugin options (settings).
	 */
	private $ihwp_other_options;

	/**
	 * Custom Google Analytics code.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      bool   boolean if custom GA is enabled.
	 */
	private $custom_google_analytics = false;

	/**
	 * Custom Google Tag Manager code.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    bool   boolean if custom GTM is enabled.
	 */
	private $custom_google_tag_manager = false;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$this->loader = new Image_Haddad_Wp_Loader();

		// Get options, grouped in sections.
		$this->ihwp_wc_basic      = get_option( 'ihwp_wc_basic' );
		$this->ihwp_wc_options    = get_option( 'ihwp_wc' );
		$this->ihwp_css_options   = get_option( 'ihwp_css' );
		$this->ihwp_other_options = get_option( 'ihwp_other' );

		// Set GA option here for couple of methods using it.
		if ( isset( $this->ihwp_wc_basic['enable_ga'] ) && 'on' === $this->ihwp_wc_basic['enable_ga'] ) {
			$this->custom_google_analytics = true;
		}

		// Set GTM option here for couple of methods using it.
		if ( isset( $this->ihwp_wc_basic['enable_gtm'] ) && 'on' === $this->ihwp_wc_basic['enable_gtm'] ) {
			$this->custom_google_tag_manager = true;
		}

		// Themes support.
		$this->themes();

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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
		// Add "timestamp" to versioning - ensure the latest child style is loaded.
		$today = date( 'YmdGi', time() );

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/image-haddad-wp-public.min.css', array(), $today, 'all' );

		// Custom CSS.
		$css        = $this->ihwp_css_options;
		$custom_css = isset( $css['custom_css_desk'] ) ? $css['custom_css_desk'] : '';
		$css_tab    = isset( $css['custom_css_tab'] ) ? $css['custom_css_tab'] : '';
		$css_mob    = isset( $css['custom_css_mob'] ) ? $css['custom_css_mob'] : '';
		$small_max  = '549px';
		$medium_max = '767px';

		$custom_css .= '/* Custom CSS Tablet */';
		$custom_css .= '@media (max-width: ' . $medium_max . '){' . $css_tab . '}';
		$custom_css .= '/* Custom CSS Mobile */';
		$custom_css .= '@media (max-width: ' . $small_max . '){' . $css_mob . '}';

		wp_add_inline_style( $this->plugin_name, $custom_css );

		// Font Awesome css.
		if ( ! wp_style_is( 'font-awesome', 'registered' ) ) {
			wp_register_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', '', '1.0.0', 'screen' );
		}
		wp_enqueue_style( 'font-awesome' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/image-haddad-wp-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script(
			$this->plugin_name,
			'ihwpJsVars',
			array(
				'ajaxurl' => esc_url( admin_url( 'admin-ajax.php' ) ),
				'nonce'   => wp_create_nonce( 'ihwp_nonce' ),
			)
		);

	}

	/**
	 * Disable lazy load images on homepage.
	 *
	 * https://developer.wordpress.org/reference/functions/wp_lazy_loading_enabled/#comment-4226
	 * @return boolean
	 *
	 * Used in filter 'wp_lazy_loading_enabled' in includes/class-image-haddad-wp.php.
	 */
	public function no_lazy_load_on_homepage() {
		if ( is_front_page() ) {
			return '';
		}
	}

	/**
	 * Fallback for no_lazy_load_on_homepage()
	 *
	 * After WP 5.9 update, add_filter( 'wp_lazy_loading_enabled',  '__return_false' );
	 * doesn't seem to work.
	 * https://developer.wordpress.org/reference/functions/wp_lazy_loading_enabled/#comment-4226
	 * removed if ( $attachment->post_mime_type === 'image/svg+xml' ) conditional.
	 * Used in filter 'wp_get_attachment_image_attributes' in includes/class-image-haddad-wp.php.
	 *
	 * @param array  $attr - img tag attributes.
	 * @param object $attachment WP_POST object.
	 * @param string $size - large, medium etc.
	 * @return $attr - img tag attributes.
	 */
	public function nolazyload_fallback( $attr, $attachment, $size ) {
		if ( is_front_page() || is_home() ) {
			unset( $attr['loading'] );
		}

		return $attr;
	}

	public function omit_lazyload_num() {
		return 30;
	}

	/**
	 * Remove comments support globally.
	 *
	 * @return void
	 */
	public function remove_comments() {
		$options_o   = $this->ihwp_other_options;
		$no_comments = ( isset( $options_o['no_comments'] ) && 'on' === $options_o['no_comments'] ) ? true : false;
		if ( $no_comments ) {
			// Removes comments from post and pages.
			add_action( 'init', array( $this, 'remove_comment_support' ), 100 );
			// Removes from admin menu.
			add_action( 'admin_menu', array( $this, 'remove_comments_admin_menus' ) );
			// Removes from admin bar.
			add_action( 'wp_before_admin_bar_render', array( $this, 'admin_bar_remove_comments' ) );
		}
	}

	/**
	 * Remove comments support for default post types.
	 *
	 * @return void
	 */
	public function remove_comment_support() {
		remove_post_type_support( 'post', 'comments' );
		remove_post_type_support( 'page', 'comments' );
	}

	/**
	 * Remove comment from admin menus.
	 *
	 * @return void
	 */
	public function remove_comments_admin_menus() {
		remove_menu_page( 'edit-comments.php' );
	}

	/**
	 * Remove comments from admin bar.
	 *
	 * @return void
	 */
	public function admin_bar_remove_comments() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'comments' );
	}

	/**
	 * Include WC taxomonies is search query.
	 *
	 * @param string $search - search term.
	 * @param object $query - query object.
	 * @return $search
	 *
	 * https://stackoverflow.com/questions/63131123/enable-custom-taxonomies-in-woocommerce-product-search
	 */
	public function woocommerce_search_product_tag_extended( $search, $query ) {
		global $wpdb, $wp;

		$qvars = $wp->query_vars;

		if ( is_admin() || empty( $search ) || ! ( isset( $qvars['s'] )
		&& isset( $qvars['post_type'] ) && ! empty( $qvars['s'] )
		&& 'product' === $qvars['post_type'] ) ) {
			return $search;
		}

		// WooCommerce custom taxonomies array.
		$taxonomies = array( 'product_tag', 'product_cat' );
		// Initializing tax query.
		$tax_query = array( 'relation' => 'OR' );

		// Loop through taxonomies to set the tax query.
		foreach ( $taxonomies as $taxonomy ) {
			$tax_query[] = array(
				'taxonomy' => $taxonomy,
				'field'    => 'name',
				'terms'    => esc_attr( $qvars['s'] ),
			);
		}

		// Get the product Ids.
		$ids = get_posts(
			array(
				'posts_per_page' => -1,
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'fields'         => 'ids',
				'tax_query'      => $tax_query,
			)
		);

		if ( count( $ids ) > 0 ) {
			$search = str_replace( 'AND (((', "AND ((({$wpdb->posts}.ID IN (" . implode( ',', $ids ) . ")) OR (", $search );
		}
		return $search;
	}

	/**
	 * Custom HTML on single product 1
	 *
	 * Haumea theme fallback - single product note. To deprecate $legacy_theme_note.
	 *
	 * @return void
	 */
	public function sp_custom_html() {
		$options           = $this->ihwp_wc_options;
		$single_note       = isset( $options['sp_custom_content_1'] ) ? $options['sp_custom_content_1'] : '';
		$legacy_theme_note = get_theme_mod( 'single_product_note', '' ); // legacy Haumea theme setting.

		$note = $single_note ? $single_note : $legacy_theme_note;
		if ( $note ) {
			echo '<div class="single-product-custom-note">' . wp_kses_post( $note ) . '</div>';
		}
	}

	/**
	 * Custom HTML on single product 2
	 *
	 * @since    1.0.0
	 */
	public function sp_custom_html_2() {
		$options = $this->ihwp_wc_options;
		$note    = isset( $options['sp_custom_content_2'] ) ? $options['sp_custom_content_2'] : '';

		if ( $note ) {
			echo '<div class="single-product-payment-methods">' . wp_kses_post( $note ) . '</div>';
		}
	}

	/**
	 * Heading of upsale products
	 *
	 * @return string
	 */
	public function haddad_upsells_heading() {
		return __( 'Related products', 'image-haddad-wp' );
	}

	/**
	 * Heading of related products
	 *
	 * @return string
	 */
	public function haddad_related_heading() {
		return __( 'You may also like&hellip;', 'image-haddad-wp' );
	}

	/**
	 * Remove up-sell products totally.
	 *
	 * @return void
	 */
	public function haddad_remove_upsells() {
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	}

	/**
	 * Custom "order received" message
	 *
	 * @param string $str - text for received order.
	 * @param string $order - order info.
	 * @return $otuput
	 */
	public function change_order_received_text( $str, $order ) {
		// Default custom text.
		$default = __( 'Thank you for your order. Order confirmation has been sent to tour email address, and the additional reciept will be sent once the parcel will be sent for delivery.', 'image-haddad-wp' );

		$options     = $this->ihwp_wc_options;
		$custom_text = isset( $options['order_received'] ) ? $options['order_received'] : '';
		$output      = $custom_text ? $custom_text : $default;

		return esc_html( $output );
	}


	/**
	 * Legacy sticker notice - for Haumea theme.
	 *
	 * @return void
	 */
	public function haumea_sticker_notice() {

		$options        = $this->ihwp_other_options;
		$sticker_legacy = get_theme_mod( 'sticker_note', '' );

		// If note is entered in Customizer.
		$sticker_note = isset( $options['sticker_note'] ) ? $options['sticker_note'] : '';
		$sticker_note = $sticker_legacy ? $sticker_legacy : $sticker_note;
		if ( ! $sticker_note ) {
			return;
		}

		echo '<div class="sticker-appended">';
		echo wp_kses_post( $sticker_note );
		echo '</div>';
	}

	/**
	 * Conversion tracking tag for Google Analytics
	 *
	 * Check if environment is staging or local - do not include conversion tracking tag
	 * on staging or local site to avoid false tracking results on GA
	 *
	 * @return void
	 * ---frontend general
	 */
	public function haddad_ga_gtagjs() {
		// Check for staging constant (defined in wp-config.php) - if defined abort.
		if (
			defined( 'KINSTA_DEV_ENV' ) && KINSTA_DEV_ENV ||
			defined( 'MICEMADE_DEV_ENV' ) && MICEMADE_DEV_ENV
		) {
			return;
		} elseif ( $this->custom_google_analytics ) {
			?>
			<!-- CUSTOM HADDAD.hr Global site tag (gtag.js) - Google Ads: 631101519 -->
			<script async src="https://www.googletagmanager.com/gtag/js?id=AW-631101519"></script>
			<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date()); gtag('config', 'AW-631101519');
			</script>
			<?php
			if ( ! class_exists( 'WooCommerce' ) ) {
				return;
			}
			if ( is_wc_endpoint_url( 'order-received' ) ) {
				$order_id = get_query_var( 'order-received', '' );
				?>
				<!-- CUSTOM HADDAD.hr Event snippet for Website sale conversion page -->
				<script> gtag('event', 'conversion', { 'send_to': 'AW-631101519/gkgxCLC2g9IBEM-w96wC', 'transaction_id': <?php echo esc_js( $order_id ); ?> });
				</script>
				<?php
			} // end if is wc endpoint.
		} // end if else KINSTA_DEV_ENV or MICEMADE_DEV_ENV.
	}

	/**
	 * Google Tag Manager Code
	 *
	 * Check if environment is staging or local - do not include google tag JS
	 * on staging or local site to avoid false tracking
	 *
	 * @return void
	 * ---frontend general
	 */
	public function haddad_gtm() {
		// Check for staging or local dev constants (defined in wp-config.php) - if defined abort.
		if (
			defined( 'KINSTA_DEV_ENV' ) && KINSTA_DEV_ENV ||
			defined( 'MICEMADE_DEV_ENV' ) && MICEMADE_DEV_ENV
		) {
			return;
		} elseif ( $this->custom_google_tag_manager ) {
			?>
			<!-- CUSTOM HADDAD.hr Google Tag Manager -->
			<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','GTM-NV2P9VB');</script>
			<!-- End Google Tag Manager -->
			<?php
		} // end if else KINSTA_DEV_ENV or MICEMADE_DEV_ENV.
	}

	/**
	 * Google Tag Manager iframe
	 *
	 * Check if environment is staging or local - do not include google tag iframe
	 * on staging or local site to avoid false tracking
	 *
	 * @return void
	 * ---frontend general
	 */
	public function haddad_gtm_iframe() {
		// Check for staging or local dev constants - if defined abort.
		if (
			defined( 'KINSTA_DEV_ENV' ) && KINSTA_DEV_ENV ||
			defined( 'MICEMADE_DEV_ENV' ) && MICEMADE_DEV_ENV
		) {
			return;
		} elseif ( $this->custom_google_tag_manager ) {
			?>
		<!-- CUSTOM HADDAD.hr Google Tag Manager (noscript) iframe -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NV2P9VB"
		height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->
			<?php
		} // end if else KINSTA_DEV_ENV or MICEMADE_DEV_ENV.
	}

	/**
	 * Themes support.
	 *
	 * @return void
	 */
	public function themes() {

		$current_theme = wp_get_theme()->get( 'Template' );
		$theme_path    = get_parent_theme_file_path();
		$options_wc    = $this->ihwp_wc_options;
		$options_o     = $this->ihwp_other_options;
		$sp_social     = isset( $options_wc['single_product_social'] ) ? $options_wc['single_product_social'] : 'ihwp_plugin';
		$polylang      = ( isset( $options_o['display_languages'] ) && 'on' === $options_o['display_languages'] ) ? true : false;

		// Legacy for Haumea theme.
		if ( 'haumea' === $current_theme ) {
			// Sticky bar notice.
			add_action( 'haumea_append_to_sticker', array( $this, 'haumea_sticker_notice' ) );

			// Social sharing on snigle product page.
			if ( 'none' !== $sp_social ) {
				$this->image_haddad_product_sharing();
			}

			// Badge "New" on catalog/categories products.
			add_action( 'haumea_before_shop_loop_item_title', array( $this, 'haddad_new_product' ), 10, 2 );

			// Cart page - reposition cart totals before cross-sales.
			add_action(
				'after_setup_theme',
				function () {
					remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );
					add_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 5 );
				}
			);

			// Polylang support.
			if ( $polylang ) {
				add_action( 'haumea_topbar_buttons', array( $this, 'haumea_child_lang_selector' ), 5 );
				add_filter( 'pll_the_languages', array( $this, 'haumea_child_lang_flags' ), 10, 2 );
			}
		} elseif ( 'flatsome' === $current_theme ) {
			// Social icons on single product page.
			if ( 'ihwp_plugin' === $sp_social || 'none' === $sp_social ) {
				add_action(
					'setup_theme',
					function() {
						remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
					}
				);
			}
			if ( 'ihwp_plugin' === $sp_social ) {
				$this->image_haddad_product_sharing();
			}

			// Add Flatsome theme options for cart cross sale products.
			if ( file_exists( $theme_path . '/inc/classes/class-flatsome-options.php' ) ) {
				add_action(
					'after_setup_theme',
					function() {
						Flatsome_Option::add_field(
							'',
							array(
								'type'     => 'custom',
								'settings' => 'cross_sells_title',
								'label'    => '',
								'section'  => 'cart-checkout',
								'priority' => '10',
								'default'  => '<div class="options-title-divider">Cross sells</div>',
							)
						);
						Flatsome_Option::add_field(
							'option',
							array(
								'type'        => 'slider',
								'settings'    => 'cross_sell_number',
								'transport'   => 'refresh',
								'label'       => __( 'Number of Cross-sell products', 'image-haddad-wp' ),
								'section'     => 'cart-checkout',
								'default'     => 4,
								'choices'     => array(
									'min'  => 1,
									'max'  => 8,
									'step' => 1,
								),
								'priority'    => '11',
								'description' => __( 'Cross-sell products appear on cart page.<br>Setting added by "Image Haddad WP" plugin', 'image-haddad-wp' ),
							)
						);
						Flatsome_Option::add_field(
							'option',
							array(
								'type'        => 'slider',
								'settings'    => 'cross_sell_columns',
								'transport'   => 'refresh',
								'label'       => __( 'Cross-sell product columns', 'image-haddad-wp' ),
								'section'     => 'cart-checkout',
								'default'     => 4,
								'choices'     => array(
									'min'  => 1,
									'max'  => 8,
									'step' => 1,
								),
								'priority'    => '11',
								'description' => __( 'Cross-sell product columns.<br>Setting added by "Image Haddad WP" plugin', 'image-haddad-wp' ),
							)
						);
					},
					10
				);
			}
		}
	}

	/**
	 * Flatsome theme (change number of cross sell products on cart page).
	 *
	 * @param int $limit - number of cross-sell products total.
	 * @return $limit
	 */
	public function flatsome_change_cross_sells_product_no( $limit ) {
		return get_theme_mod( 'cross_sell_number', 4 );
	}

	/**
	 *  Flatsome theme (change number of cross sell columns on cart page).
	 *
	 * @param int $columns - number of cross-sell products columns.
	 * @return $columns
	 */
	public function flatsome_change_cross_sells_columns( $columns ) {
		return get_theme_mod( 'cross_sell_columns', 4 );
	}

	/**
	 * Returns the main instance of Image_Haddad_Product_Sharing to prevent the need to use globals.
	 *
	 * @since  1.0.0
	 * @return object Image_Haddad_Product_Sharing
	 */
	public function image_haddad_product_sharing() {
		return Image_Haddad_Product_Sharing::instance();
	}

	/**
	 * Haumea Child legacy - Polylang language selector
	 *
	 * @return void
	 */
	public function haumea_child_lang_selector() {
		// If Customizer lang selector is off.
		$lang_sel = get_theme_mod( 'display_langs', false );
		if ( ! $lang_sel ) {
			return;
		}
		// Show Polylang language switcher.
		if ( function_exists( 'pll_the_languages' ) ) {
			echo '<div class="header-button to-sticky"><ul class="lang-selector">';
			$lang_args = array(
				'dropdown'         => 0,
				'show_names'       => 1,
				'show_flags'       => 0,
				'display_names_as' => 'slug',
			);
			pll_the_languages( $lang_args );
			echo '</ul></div>';
		}
	}

	/**
	 * Haumea Child legacy - Modify language selector output
	 *
	 * @param string $output - string containing lang flags selector.
	 * @param array  $args - lang selector arguments.
	 * @return $output
	 */
	public function haumea_child_lang_flags( $output, $args ) {

		$lang_list = pll_languages_list( array( 'fields' => 'locale' ) );

		if ( ! empty( $lang_list ) ) {
			foreach ( $lang_list  as $lang ) {
				// $file   = POLYLANG_DIR . "/flags/$lang.png";
				$file   = get_theme_file_path( 'assets/flags/' . $lang . '.png' );
				$locale = explode( '_', get_locale() );
				$value  = reset( $locale );
				$output = str_replace( "value='$value'", "value='$lang' title='$file'", $output );
				$output = str_replace( '<a ', '<a class="icon-button"', $output );
			}
		}
		return $output;
	}

	/**
	 *  Hide non-hr pages from SE robots
	 *
	 * @param  string $string - index, noindex, follow, nofollow.
	 * @return $sting
	 */
	public function haddad_hide_en_pages( $string = '' ) {

		$options = $this->ihwp_other_options;
		$hide    = ( isset( $options['hide_to_seo'] ) && 'on' === $options['hide_to_seo'] ) ? true : false;
		$locale  = get_locale();

		if ( $hide && 'hr' !== $locale ) {
			$string = 'noindex,nofollow';
		}
		return $string;
	}

	/**
	 * NEW ITEM BADGE
	 *
	 * @param integer $post_id id of post/product.
	 * @return void
	 */
	public function haddad_new_product( $post_id ) {
		global $product;
		$format    = 'Y-m-d';
		$post_date = get_the_date( $format, $post_id );
		$expiry    = date( $format, strtotime( $post_date ) + 20 * 24 * 60 * 60 );
		$today     = date( $format, time() );

		if ( $today < $expiry ) {
			echo '<span class="new-product">' . esc_html__( 'New!', 'image-haddad-wp' ) . '</span>';
		}
	}

	/**
	 * Ensure no drafts in products display
	 *
	 * @param object $q - query object.
	 * @return void
	 *
	 * https://www.kathyisawesome.com/woocommerce-modifying-product-query/
	 */
	public function haddad_no_drafts_in_wc_loop( $q ) {
		if ( ! is_admin() ) {
			$q->set( 'post_status', 'publish' );
		}
	}

	/**
	 * Remove reviews
	 *
	 * @return void
	 */
	public function haddad_remove_reviews() {
		$options = $this->ihwp_wc_basic;
		if ( isset( $options['disable_reviews'] ) && 'on' === $options['disable_reviews'] ) {
			remove_post_type_support( 'product', 'comments' );
		}
	}

	/**
	 * Haddad logo for printing orders, delivery notes.
	 * For "WooCommerce Delivery Notes plugin"
	 *
	 * @return void
	 */
	public function haddad_print_logo_style() {
		?>
		<style>
		.order-branding .company-logo img {
			width: 350px;
			height: auto;
		}
		</style>
		<?php
	}

	/**
	 * CUSTOM ORDER STATUS METHODS.
	 *
	 * @snippet       Custom Order Statuses
	 * @how-to        Watch tutorial @ https://businessbloomer.com/?p=19055
	 * @sourcecode    https://businessbloomer.com/?p=77911
	 * @author        Rodolfo Melogli
	 * @compatible    WooCommerce 3.5.4
	 */

	/**
	 * Register Order Status
	 *
	 * @param array $order_statuses - array of order statuses.
	 * @return $order_statuses
	 */
	public function haddad_register_custom_order_status( $order_statuses ) {
		// Status must start with "wc-".
		$order_statuses['wc-new-order'] = array(
			'label'                     => _x( 'New Order', 'Order status', 'image-haddad-wp' ),
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			// translators: The (%s) placeholder is for the orders count.
			'label_count'               => _n_noop( 'New Order <span class="count">(%s)</span>', 'New Orders <span class="count">(%s)</span>', 'image-haddad-wp' ),
		);
		$order_statuses['wc-caution']   = array(
			'label'                     => _x( 'Caution!', 'Order status', 'image-haddad-wp' ),
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			// translators: The (%s) placeholder is for the orders count.
			'label_count'               => _n_noop( 'Caution! <span class="count">(%s)</span>', 'Caution! <span class="count">(%s)</span>', 'image-haddad-wp' ),
		);
		$order_statuses['wc-creation']  = array(
			'label'                     => _x( 'In creation', 'Order status', 'image-haddad-wp' ),
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			// translators: The (%s) placeholder is for the orders count.
			'label_count'               => _n_noop( 'In creation <span class="count">(%s)</span>', 'In creation <span class="count">(%s)</span>', 'image-haddad-wp' ),
		);

		return $order_statuses;
	}

	/**
	 * Set Custom Order Status @ WooCommerce Checkout Process
	 *
	 * @param int $order_id - ID of order.
	 * @return void
	 * Resource:
	 * https://stackoverflow.com/questions/45985488/set-woocommerce-order-status-when-order-is-created-from-processing-to-pending
	 */
	public function haddad_thankyou_change_order_status( $order_id ) {
		// Ako stranica nema broja narudzbe, odustati odmah.
		if ( ! $order_id ) {
			return;
		}
		// Svi zabilljezeni podaci o narudzbi.
		$order = wc_get_order( $order_id );

		// Trenutni status narudzbe.
		$order_status = $order->get_status();

		// Statusi koji dozvoljavaju promjenu.
		$statuses = array(
			'pending',
			'processing',
			'on-hold',
			'new-order',
		);

		if ( ! $this->initial_new_order( $order_id ) && in_array( $order_status, $statuses, true ) ) {
			// Status update ide bez "wc-" prefixa.
			$order->update_status( 'new-order' );
			// Upis transienta da je status promijenjen u "Nova narudzba" (wc-new-order). Trajanje - tjedan dana.
			set_transient( 'haddad_new_order_' . $order_id, true, MONTH_IN_SECONDS );
			// Upisati u narudzbu da je napravljena pocetna promjena statusa.
			update_post_meta( $order_id, '_haddad_to_new_order_init', true );
		}

	}

	/**
	 * If order status has been initially set to "new-order".
	 *
	 * @param int $order_id - order unique ID.
	 * @return boolean $processed
	 */
	public function initial_new_order( $order_id ) {
		// Informacija je li promjena statusa narudzbe vec zabiljezena u transientima.
		$transient_cached = get_transient( 'haddad_new_order_' . $order_id );
		// Informacija je li promjena statusa narudzbe vec zabiljezena u meta podacima narudzbe.
		$new_order_initialized = get_post_meta( $order_id, '_haddad_to_new_order_init', true );
		$processed             = false;
		if ( $transient_cached || $new_order_initialized ) {
			$processed = true;
		}
		return apply_filters( 'haddad_order_initialized', $processed );
	}

	/**
	 * End custom order status methods.
	 */

	/**
	 * Exclude array of categories (terms) in Product categories widget.
	 *
	 * @param array $args array of arguments.
	 * @return $args
	 */
	public function haddad_exclude_product_cat_widget( $args ) {
		$options = $this->ihwp_wc_options;
		$ewc     = isset( $options['exclude_wc_cats'] ) ? $options['exclude_wc_cats'] : 'arhiva-proizvoda';

		$newlines = preg_replace( '/\n$/', '', preg_replace( '/^\n/', '', preg_replace( '/[\r\n]+/', "\n", $ewc ) ) );
		$terms    = explode( "\n", $newlines );

		$id_arr = array();
		foreach ( $terms as $term ) {
			$term = get_term_by( 'slug', $term, 'product_cat' );
			if ( is_object( $term ) ) {
				$id_arr[] = $term->term_id;
			}
		}
		$args['exclude'] = $id_arr;
		return $args;
	}

	/**
	 * Override WC template(s)
	 *
	 * @param string $located - template file located.
	 * @param string $template_name - name of template.
	 * @param array  $args - array of arguments.
	 * @param string $template_path - path to template file.
	 * @param string $default_path - default path to template file.
	 * @return $located
	 */
	public function ihwp_wc_templates( $located, $template_name, $args, $template_path, $default_path ) {

		$theme = wp_get_theme()->get( 'Template' );

		if ( 'cart/cross-sells.php' === $template_name && $this->wc_template( 'cart/cross-sells.php' ) ) {
			$located = IMAGE_HADDAD_WP_DIR . 'public/woocommerce/cart/cross-sells.php';
		} elseif ( 'cart/cart-totals.php' === $template_name && 'haumea' !== $theme && $this->wc_template( 'cart/cart-totals.php' ) ) {
			$located = IMAGE_HADDAD_WP_DIR . 'public/woocommerce/cart/cart-totals.php';
		} elseif ( 'checkout/review-order.php' === $template_name && 'haumea' !== $theme && $this->wc_template( 'checkout/review-order.php' ) ) {
			$located = IMAGE_HADDAD_WP_DIR . 'public/woocommerce/checkout/review-order.php';
		} elseif ( 'single-product/product-attributes.php' === $template_name ) {
			$located = IMAGE_HADDAD_WP_DIR . 'public/woocommerce/single-product/product-attributes.php';
		}
		return $located;
	}

	/**
	 * Helper method for overriding WC templates.
	 * check for template file existance.
	 *
	 * @param string $template - template dir/filename.
	 * @return boolean
	 */
	private function wc_template( $template ) {
		if ( file_exists( IMAGE_HADDAD_WP_DIR . 'public/woocommerce/' . $template ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Trazenje artikala povezanih sa artiklima iz kosarice.
	 *
	 * Funkcija koristena u "woocommerce/cart/cross-sells.php" WC predlosku.
	 * Ovaj plugin overridea template sa templateom u "public/woocommerce/cart/cross-sells.php
	 *
	 * @return $related_to_cart
	 */
	public function haddad_get_related_to_cart() {
		// Artikli iz kosarice.
		$cart_products = WC()->cart->get_cart();
		// Pocetni prazni array-evi za kategorije i tagove.
		$cats_array      = array();
		$tags_array      = array();
		$related_to_cart = array();

		foreach ( $cart_products as $item => $values ) {

			$product_id = $values['data']->get_id();
			// Term id-evi iz artikala (koje kategorije i tagovi su dodani artiklima).
			$get_cats = wc_get_product_term_ids( $product_id, 'product_cat' );
			$get_tags = wc_get_product_term_ids( $product_id, 'product_tag' );

			// Sve kategorije i tagovi iz artikala u kosarici.
			// Potrebno za tax_query filter ispod (argumenti).
			// $get_cats(tags) se u loopu dodaju u $cats_array (tj. $tags_array).
			$cats_array = array_merge( $cats_array, $get_cats );
			$tags_array = array_merge( $tags_array, $get_tags );

		}

		// Argumenti za trazenje artikala (nasumicno po kategorijama/tagovima iz kosarice).
		$num_items = apply_filters( 'woocommerce_cross_sells_total', '' );
		$args      = array(
			'posts_per_page'   => $num_items,
			'orderby'          => 'rand date',
			'post_type'        => 'product',
			'post_status'      => 'publish',
			'order'            => 'DESC',
			'suppress_filters' => false,
		);

		$args['tax_query'] = array( 'relation' => 'AND' );

		// Tax query za vidljivost artikala (iskljuciti "excluded from catalog", "outofstock").
		$product_visibility_terms  = wc_get_product_visibility_term_ids();
		$product_visibility_not_in = array( $product_visibility_terms['exclude-from-catalog'] );

		// Ako se zeli uzeti u obzir generalna WC postavka da se sakriju artikli,
		// ubaciti donji $product_visibility_not_in[] ... u kondiciju:
		// if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {} .

		$product_visibility_not_in[] = $product_visibility_terms['outofstock'];

		$args['tax_query'][] = array(
			'taxonomy' => 'product_visibility',
			'field'    => 'term_taxonomy_id',
			'terms'    => $product_visibility_not_in,
			'operator' => 'NOT IN',
		);

		// Tax query - filtriranje artikala po kategorijama i tagovima.
		if ( ! empty( $cats_array ) ) {
			$args['tax_query'][] = array(
				'taxonomy'         => 'product_cat',
				'field'            => 'id',
				'operator'         => 'IN',
				'terms'            => $cats_array,
				'include_children' => true,
			);
		}
		if ( ! empty( $tags_array ) ) {
			$args['tax_query'][] = array(
				'taxonomy'         => 'product_tag',
				'field'            => 'id',
				'operator'         => 'IN',
				'terms'            => $tags_array,
				'include_children' => true,
			);
		}

		// Get posts array sa artiklima povezanim s kosaricom.
		$cart_related = get_posts( $args );
		foreach ( $cart_related as $item ) {
			$ids[] = $item->ID;
		}
		// Product objects.
		$haddad_cross_sells = array_filter( array_map( 'wc_get_product', $ids ), 'wc_products_array_filter_visible' );

		return apply_filters( 'haddad_related_to_carts', $haddad_cross_sells );
	}

	/**
	 * Sale products loop on empty cart.
	 *
	 * @return void
	 */
	public function haddad_empty_cart_sale_loop() {

		$current_theme = wp_get_theme()->get( 'Template' );

		$product_ids_on_sale = wc_get_product_ids_on_sale();
		if ( ! empty( $product_ids_on_sale ) ) {
			?>
			<div class="notfound-sales">

				<h4><?php esc_html_e( 'Perhaps you would be interested in our sale products: ', 'image-haddad-wp' ); ?></h4>

				<?php
				$args = array(
					'post_type'      => 'product',
					'post__in'       => $product_ids_on_sale,
					'posts_per_page' => apply_filters( 'loop_shop_per_page', '' ),
				);
				$loop = new WP_Query( $args );
				if ( $loop->have_posts() ) {

					woocommerce_product_loop_start();

					// Calculate classes for Haumea theme product grid.
					if ( 'haumea' === $current_theme ) {
						add_filter(
							'post_class',
							function( $classes ) {
								$classes[] = haumea_calculate_products_grid_classes( haumea_loop_columns() );
								return $classes;
							}
						);
					}
					while ( $loop->have_posts() ) :
						$loop->the_post();
						wc_get_template_part( 'content', 'product' );
					endwhile;

					woocommerce_product_loop_end();

				} else {
					echo esc_html__( 'No sale products found', 'image-haddad-wp' );
				}
				wp_reset_postdata();
				?>

			</div>
			<?php
		}
	}

	/**
	 * Add attachment to order emails
	 *
	 * @param array  $attachments - attachments array.
	 * @param string $status - email status.
	 * @param string $order - order id.
	 * @return $attachments
	 *
	 * Based on:
	 * https://wordpress.org/support/topic/pdf-attach-terms-and-conditions-to-email#post-4620383
	 */
	public function haddad_attach_terms_conditions_pdf_to_email( $attachments, $status, $order ) {

		$allowed_statuses = array( 'new_order', 'customer_invoice', 'customer_processing_order', 'customer_completed_order', 'customer_on_hold_order' );

		$attachment_title = 'Haddad d.o.o Webshop - opći uvjeti - 2020';
		$options          = $this->ihwp_wc_basic;
		if ( isset( $options['terms_pdf'] ) && ! empty( $options['terms_pdf'] ) ) {
			$attachment_title = $options['terms_pdf'];
		}

		// Copy the attachment post type title (not file title).
		$attachment = $this->wp_get_attachment_by_post_name( $attachment_title );

		if ( isset( $status ) && in_array( $status, $allowed_statuses, true ) && $attachment ) {
			$pdf_path      = get_attached_file( $attachment->ID );
			$attachments[] = $pdf_path;
		}

		return $attachments;
	}

	/**
	 * Get attachment by post name
	 *
	 * @param string $post_name - post name.
	 * @return $get_attachment->posts[0]
	 *
	 * Used by this class' method haddad_attach_terms_conditions_pdf_to_email().
	 */
	private function wp_get_attachment_by_post_name( $post_name ) {
		$args           = array(
			'posts_per_page' => 1,
			'post_type'      => 'attachment',
			'name'           => trim( sanitize_title( $post_name ) ),
		);
		$get_attachment = new WP_Query( $args );
		if ( ! $get_attachment || ! isset( $get_attachment->posts, $get_attachment->posts[0] ) ) {
			return false;
		}
		return $get_attachment->posts[0];
	}

	/**
	 * Show universal size attributes for the product
	 * display between price and Add to Cart.
	 *
	 * Adapted code, exctracted from:
	 * wp_content/plugins/woocommerce/templates/single-product/product-attributes.php
	 *
	 * @return void
	 */
	public function haddad_universal_size_attribute() {

		global $product;
		$attributes = array_filter( $product->get_attributes() );

		if ( ! $attributes ) {
			return;
		}

		foreach ( $attributes as $attribute ) {

			$variation = $attribute->get_variation();
			$name      = $attribute->get_name();

			// If attribute is "velicina" ("size") AND attribut is
			// NOT USED FOR VARIATIONS. (pa_ prefix is for product attribute).
			if ( 'pa_velicina' === $name && ! $variation ) {

				echo '<div class="universal-sizes-wrap"><div class="universal-sizes">';

				$values = array();
				if ( $attribute->is_taxonomy() ) {
					$attribute_taxonomy = $attribute->get_taxonomy_object();
					$attribute_values   = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'all' ) );

					foreach ( $attribute_values as $attribute_value ) {
						// If attribute term slug starts with "uni".
						if ( 'uni' !== substr( $attribute_value->slug, 0, 3 ) ) {
							continue;
						}

						$value_name = $attribute_value->name;

						if ( $attribute_taxonomy->attribute_public ) {
							$values[] = '<a href="' . esc_url( get_term_link( $attribute_value->term_id, $attribute->get_name() ) ) . '" rel="tag">' . esc_html( $value_name ) . '</a>';
						} else {
							$values[] = esc_html( $value_name );
						}
					}
				} else {
					$values = $attribute->get_options();

					foreach ( $values as &$value ) {
						$value = make_clickable( esc_html( $value ) );
					}
				}

				echo wp_kses_post( apply_filters( 'woocommerce_attribute', wptexturize( implode( ', ', $values ) ), $attribute, $values ) );
				echo '</div></div>';
			}
		}
	}

	/**
	 * Reorder tabs on single product page
	 *
	 * @param array $tabs - array of tabs.
	 * @return $tabs
	 */
	public function haddad_reorder_tabs( $tabs ) {

		$tab_list = array(
			'additional_information' => 10,
			'description'            => 20,
			'reviews'                => 30,
		);
		foreach ( $tab_list as $tab => $priority ) {
			if ( isset( $tabs[ $tab ] ) ) {
				$tabs[ $tab ]['priority'] = $priority;
			}
		}
		return $tabs;
	}

	/**
	 * Show discount in cart on product basis
	 *
	 * @param string $subtotal - html with subtotal price.
	 * @param array  $cart_item - product item.
	 * @param string $cart_item_key - unique cart item key.
	 * @return $subtotal
	 *
	 * Inspired by:
	 * @sourcecode    https://businessbloomer.com/?p=21881
	 * @author        Rodolfo Melogli
	 */
	public function haddad_cart_item_coupon_subtotal( $subtotal, $cart_item, $cart_item_key ) {

		$cart    = WC()->cart;
		$coupons = $cart->get_coupons();

		// If no coupons, just go with subtotal.
		if ( empty( $coupons ) ) {
			return $subtotal;
		}

		// Set defaults.
		$label            = '';
		$discounted_price = 0;
		$amount_prev      = 0;
		$newsubtotal      = '';
		$index            = 1;

		// Sequentially apply coupons is applying next coupon to already disconted price, not original price.
		$apply_seq = 'yes' === get_option( 'woocommerce_calc_discounts_sequentially', 'no' ) ? true : false;
		// Item category ID's.
		$item_categories = $cart_item['data']->get_category_ids();

		// Temporary coupons array for filter with coupon limits.
		// If cart item (product) is NOT in category set for coupon limits, skip it.
		// Prepare cupons to apply limit(s).
		$coupons_after_limits = array();
		foreach ( $coupons as $coupon ) {
			$data = $coupon->get_data();
			// Limit coupon by item (product) categories.
			$cats_limit = ! empty( $data['product_categories'] ) ? $data['product_categories'] : array();
			if ( ! empty( $cats_limit ) ) {
				if ( 0 === count( array_intersect( $item_categories, $cats_limit ) ) ) {
					continue;
				}
			}
			$coupons_after_limits[] = $coupon;
		}

		// Count coupons after applied limits.
		$coupons_count = is_countable( $coupons_after_limits ) ? count( $coupons_after_limits ) : 0;

		// Custom coupon ordering.
		usort( $coupons_after_limits, array( $this, 'compare_coupon_data' ) );

		foreach ( $coupons_after_limits as $coupon ) {
			$data        = $coupon->get_data();
			$coupon_code = $data['code'];
			$amount      = $data['amount'];
			$type        = $data['discount_type'];

			if ( $cart->has_discount( $coupon_code ) && $coupon->is_valid() ) {

				$label = __( 'Coupon: ', 'image-haddad-wp' ) . $coupon_code;

				// Price to be discounted - already discounted or original.
				$price_to_discount = ( $apply_seq && $discounted_price ) ? $discounted_price : $cart_item['data']->get_price();

				// Calculate the discount, depending on the discount type.
				if ( 'percent' === $type ) {
					$discounted_price = $price_to_discount - ( $price_to_discount * ( ( $amount + $amount_prev ) / 100 ) );
				} elseif ( 'fixed_product' === $type ) {
					$discounted_price = $price_to_discount - ( $amount + $amount_prev );
				}

				// Add the currency and html to new subtotal (after multiplied with quantity).
				$wc_html_subtotal = wc_price( $discounted_price * $cart_item['quantity'] );
				// If error in getting the correct formatted wc price.
				if ( is_wp_error( $wc_html_subtotal ) ) {
					return;
				}

				// Background color and font color for coupon label.
				$label_color = get_post_meta( $data['id'], 'coupon_back_color', true );
				$font_color  = get_post_meta( $data['id'], 'coupon_font_color', true );

				// New subtotal for each coupon discount and the final newsubtotal.
				if ( $index < $coupons_count ) {
					$newsubtotal .= sprintf(
						'<div class="discount"><small class="coupon-code" style="background-color:%s; color:%s;">%s</small><s>%s</s></div>',
						$label_color,
						$font_color,
						esc_html( $label ),
						$wc_html_subtotal
					);
				} else {
					$newsubtotal .= sprintf(
						'<div class="discount"><small class="coupon-code" style="background-color:%s; color:%s;">%s</small>%s</div>',
						$label_color,
						$font_color,
						esc_html( $label ),
						$wc_html_subtotal
					);
				}

				// If coupons not applied sequentially, apply all the coupons combined to the original price.
				$amount_prev = ! $apply_seq ? $amount : 0;
				$index++;

			} // endif cart has discount...
		} // end foreach.

		// Subtotal with new subtotal(s) added (applied coupons).
		$subtotal = sprintf( '<s>%s</s> %s', $subtotal, $newsubtotal );

		return $subtotal;
	}
	/**
	 * Helper function - custom sorting coupons.
	 *
	 * @param array $a - $coupons array.
	 * @param array $b - $coupons array.
	 * @return $comparison.
	 */
	public function compare_coupon_data( $a, $b ) {

		if ( is_object( $a ) ) {
			$data_a = $a->get_data();
			$data_b = $b->get_data();

			// Compare using timestamp.
			// $coupon_data_a = $data_a['date_created']->getTimestamp();
			// $coupon_data_b = $data_b['date_created']->getTimestamp();

			// Compare using amount.
			$coupon_data_a = $data_a['amount'];
			$coupon_data_b = $data_b['amount'];

			return $coupon_data_b - $coupon_data_a;
		}
	}

	/**
	 * Exclude "out of stocks" from related products on single product page.
	 *
	 * @param array   $related_posts - array of related product IDs.
	 * @param integer $product_id - current product ID.
	 * @param array   $limit_exclusions - limit and excluded.
	 * @return $related_posts
	 */
	public function haddad_no_out_of_stock_in_related( $related_posts, $product_id, $limit_exclusions ) {

		$outofstocks = (array) wc_get_products(
			array(
				'status'       => 'publish',
				'limit'        => -1,
				'stock_status' => 'outofstock',
				'return'       => 'ids',
			)
		);

		$related_posts = array_diff( $related_posts, $outofstocks );
		return $related_posts;
	}

	/**
	 * Get coupons on products (per product), outside the cart.
	 *
	 * @return array $coupon_data
	 *
	 * Conditions (set in coupon edit page data box):
	 * - custom setting (meta) 'coupon_prices_on_products' - this plugin.
	 * - custom setting (meta) 'apply_automatically_active' - .
	 * Based upon:
	 * https://stackoverflow.com/questions/39745791/woocommerce-check-if-coupon-is-valid
	 * Coupon methods: woocommerce/includes/class-wc-coupon.php
	 * Methods to check in unit-tests:
	 * https://github.com/woocommerce/woocommerce/blob/trunk/plugins/woocommerce/tests/legacy/unit-tests/coupon/data.php
	 */
	private function get_coupon_prices() {

		$coupons     = array();
		$coupon_data = array();
		$options     = $this->ihwp_wc_basic;

		$disable_caching = ( isset( $options['disable_coupon_cache'] ) && 'on' === $options['disable_coupon_cache'] ) ? true : false;
		$transient       = get_transient( 'image_haddad_current_coupon' );

		// Samo za frontend, preskoci query na adminu (Elementor edit).
		// if ( ! is_admin() ) {
		$args    = array(
			'post_type'      => 'shop_coupon',
			'post_status'    => 'publish',
			'posts_per_page' => -1, // -1 za prikaz svih
			'orderby'        => 'meta_value_num', // sortiranje po meta numer. vrijednosti.
			'meta_key'       => 'coupon_amount', // iznos kuponskog popusta.
			'order'          => 'DESC',
		);
		$coupons = wp_get_recent_posts( $args );
		// }

		// Ako transient nije istekao i ako je omoguceno transient cachiranje,
		// prikazi spremljene vrijednosti(array).
		if ( ! empty( $transient ) && ! $disable_caching ) {

			return $transient;

		} elseif ( ! empty( $coupons ) ) { // else, check if there are coupons.

			foreach ( $coupons as $coupon ) {

				$coupon_obj = new WC_Coupon( $coupon['post_title'] );

				// Coupon data. All coupon methods > woocommerce/includes/class-wc-coupon.php.
				$coupon_id       = $coupon_obj->get_id();
				$coupon_code     = $coupon_obj->get_code();
				$type            = $coupon_obj->get_discount_type();
				$amount          = $coupon_obj->get_amount();
				$date_expires    = $coupon_obj->get_date_expires();
				$automatic_apply = get_post_meta( $coupon_id, 'apply_automatically_active', true );
				$setting_enabled = get_post_meta( $coupon_id, 'coupon_prices_on_products', true );
				$cats_limit      = $coupon_obj->get_product_categories(); // or get_post_meta( $coupon_id, 'product_categories', true );
				$back_color      = get_post_meta( $coupon_id, 'coupon_back_color', true );
				$font_color      = get_post_meta( $coupon_id, 'coupon_font_color', true );
				$exclude_cats    = $coupon_obj->get_excluded_product_categories();

				// If no expiry date set, skip.
				if ( $date_expires ) {
					$date_expires = $date_expires->getTimestamp();
				} else {
					continue;
				}
				// If current time (WP function current_time() ) is before the expiry date
				// and coupon is automatically applied - display coupon price,
				// AND coupon price is enabled in coupon settings.
				// Using timestamps.
				if ( time() < $date_expires && 'yes' === $automatic_apply && 'yes' === $setting_enabled ) {
					// Set coupon data (array) end set it to transient.
					$coupon_data[] = array( $coupon_code, $type, $amount, $coupon_id, $cats_limit, $back_color, $font_color, $exclude_cats );
					set_transient( 'image_haddad_current_coupon', $coupon_data, 10 * MINUTE_IN_SECONDS );
				}
			}
		}

		return $coupon_data;
	}

	/**
	 * Kuponska cijena van košarice, nakon regularne cijene
	 *
	 * @return $price $coupon_html
	 *
	 * Koristi get_coupon_prices() iz ovog classa.
	 */
	public function haddad_coupon_after_price() {

		global $product;

		// If $product object is not available (for example, in WC product blocks), abort.
		if ( ! is_object( $product ) ) {
			return;
		}

		// If is admin abort.
		if ( is_admin() && ! wp_doing_ajax() ) {
			return;
		}

		// Prepared coupon(s) array with coupon data.
		$active_coupons = $this->get_coupon_prices();

		// Sequentially apply coupons is applying next coupon to already disconted price, not original price.
		$apply_seq = ( 'yes' === get_option( 'woocommerce_calc_discounts_sequentially', 'no' ) ) ? true : false;

		// Ako ima aktivnih kupona.
		if ( ! empty( $active_coupons ) ) {

			$discounted_price = 0;
			$amount_prev      = 0;
			$newsubtotal      = '';
			$index            = 1;
			$coupons_count    = is_countable( $active_coupons ) ? count( $active_coupons ) : 0;

			$desc_text = __( 'Discount is applied in the cart', 'image-haddad-wp' );

			$coupon_html = '<span class="haddad-coupons">'; // Coupons wrapper.
			foreach ( $active_coupons as $coupon_data ) {

				$coupon_code  = isset( $coupon_data[0] ) ? $coupon_data[0] : '';
				$type         = isset( $coupon_data[1] ) ? $coupon_data[1] : '';
				$amount       = isset( $coupon_data[2] ) ? $coupon_data[2] : '';
				$coupon_id    = isset( $coupon_data[3] ) ? $coupon_data[3] : '';
				$cats_limit   = isset( $coupon_data[4] ) ? $coupon_data[4] : array();   // Limit coupon by product categories.
				$exclude_cats = isset( $coupon_data[7] ) ? $coupon_data[7] : array();   // Exclude coupon to products in categories.

				// Get categories products is in.
				$product_categories = $product->get_category_ids();
				// If coupon has categories limit, and product is not in that category(es), skip.
				if ( ! empty( $cats_limit ) ) {
					if ( 0 === count( array_intersect( $product_categories, $cats_limit ) ) ) {
						continue;
					}
				}

				// Specific categories excluded from the discount.
				if ( count( $exclude_cats ) && count( array_intersect( $product_categories, $exclude_cats ) ) ) {
					continue;
				}

				// Start coupon container.
				$coupon_html .= '<span class="haddad-coupon-price ' . sanitize_title( $coupon_code ) . '">';
				// Final price, if variable, get minimal price in range, else, get final price for simple product.
				$get_price = ( 'variable' === $product->get_type() ) ? $product->get_variation_price( 'min' ) : $product->get_price();

				// Get raw price (without currency).
				$price_to_discount = ( $apply_seq && $discounted_price ) ? $discounted_price : (float) $get_price;
				// Calculate discount for display, depending on discount type.
				if ( 'percent' === $type ) {
					$discounted_price = $price_to_discount - ( $price_to_discount * ( ( $amount + $amount_prev ) / 100 ) );
				} elseif ( 'fixed_product' === $type ) {
					$discounted_price = $price_to_discount - ( $amount + $amount_prev );
				}

				// Add the currency and html.
				$price_coupon_discounted = wc_price( $discounted_price );
				// If error in getting the correct formatted wc price.
				if ( is_wp_error( $price_coupon_discounted ) ) {
					return;
				}
				// Coupon label.
				$options = $this->ihwp_wc_basic;
				$label = '';
				if ( isset( $options['coupon_price_choice'] ) ) {
					if ( 'default' === $options['coupon_price_choice'] ) {
						$label = '<span class="label">' . esc_html__( 'Coupon: ', 'image-haddad-wp' ) . esc_html( $coupon_code ) . '<span class="desc tip-top" title="' . esc_attr( $desc_text ) . '"></span></span>';
					} elseif ( 'custom' === $options['coupon_price_choice'] && $options['coupon_price_custom_label'] ) {
						$label = '<span class="label">' . esc_html( $options['coupon_price_custom_label'] ) . '<span class="desc tip-top" title="' . esc_attr( $desc_text ) . '"></span></span>';
					}
				}
				$coupon_html .= apply_filters( 'haddad_coupon_label', $label );

				$coupon_html .= '<span class="amount-wrapper">';
				// Change attributes markup in WC generated HTML, watch for changes in HTML.
				$coupon_html .= $price_coupon_discounted;

				$coupon_html .= '</span>'; // end amount wrapper.

				// If coupons not applied sequentially, apply all the coupons combined to the original price.
				$amount_prev = ! $apply_seq ? $amount : 0;
				$index++;

				// End coupon container.
				$coupon_html .= '</span>';
			}
			// End of coupons wrapper.
			$coupon_html .= '</span>';

			echo wp_kses_post( $coupon_html );
		}
	}

	/**
	 * Styles for each coupon price label
	 *
	 * @return void
	 */
	public function coupon_styles() {
		$active_coupons = $this->get_coupon_prices();
		foreach ( $active_coupons as $coupon ) {
			$parent_sel  = 'span.haddad-coupon-price.' . $coupon[0];
			$custom_css  = $parent_sel . ' { background: ' . $coupon[5] . ' }';
			$custom_css .= $parent_sel . ' .amount, ' . $parent_sel . ' .label { color: ' . $coupon[6] . ' }';

			wp_add_inline_style( $this->plugin_name, $custom_css );
		}
	}

	/**
	 * Styles for each coupon price label
	 *
	 * @return void
	 */
	public function sale_price_styles() {
		$options    = $this->ihwp_wc_options;
		$font_color = isset( $options['price_font_color'] ) ? $options['price_font_color'] : '';
		$back_color = isset( $options['price_back_color'] ) ? $options['price_back_color'] : '';

		$back_color_selectors = '.products .price ins, .product .price ins, .flickity-slider .price ins';
		$font_color_selectors = '.products .price ins .amount, .product .price ins .amount, .flickity-slider .price ins amount';

		if ( $font_color || $back_color ) {
			$custom_css  = '';
			$custom_css .= $back_color ? ( $back_color_selectors . ' { background: ' . $back_color . '; padding: 0.2em 0.4em; line-height: 1.6; margin-top: 0.3em; }' ) : '';
			$custom_css .= $font_color ? ( $font_color_selectors . ' { color: ' . $font_color . '; }' ) : '';

			wp_add_inline_style( $this->plugin_name, $custom_css );
		}
	}

	/**
	 * Remove coupon form on checkout page
	 *
	 * @return void
	 */
	public function haddad_remove_checkout_coupon_form() {

		$options     = $this->ihwp_wc_options;
		$hide_coupon = isset( $options['hide_coupon_on_checkout'] ) ? $options['hide_coupon_on_checkout'] : 'on';

		// Check if there is a discount. Not neccessary but ...
		if ( ! empty( WC()->cart->applied_coupons ) && 'on' === $hide_coupon ) {
			remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
		}
	}

	/**
	 * STRANICA PLACANJA (CHECKOUT)
	 * Ukupna cijena umanjena za kupon. Prije troskova dostave i ostalog.
	 * Hooked u /public/woocommerce/checkout/review-order.php
	 * Hooked u /public/woocommerce/cart/cart-totals.php
	 * na action 'haddad_coupon_subtotal'
	 * Dodaje red u tabeli .shop_table u kosarici i stranici naplate.
	 *
	 * @param boolean $compound - compound boolean.
	 * @return void
	 */
	public function haddad_coupons_applied_to_subtotal( $compound = false ) {
		$cart = WC()->cart;
		if ( $cart->get_coupons() ) {

			$tax_ex_coupon = array_sum( $cart->get_coupon_discount_totals() );     // coupons without tax.
			$tax_coupon    = array_sum( $cart->get_coupon_discount_tax_totals() ); // tax on coupons.
			$tax_info      = '';

			if ( $compound ) {
				$cart_subtotal = $cart->get_cart_contents_total() + $cart->get_shipping_total() + $cart->get_taxes_total( false, false );

				// Price with included tax.
			} elseif ( $cart->display_prices_including_tax() ) {
				$cart_subtotal = $cart->get_subtotal() + $cart->get_subtotal_tax() - ( $tax_ex_coupon + $tax_coupon );
				if ( $cart->get_subtotal_tax() > 0 && ! wc_prices_include_tax() ) {
					$tax_info = ' <small class="tax_label">' . WC()->countries->inc_tax_or_vat() . '</small>';
				}

				// Price without tax.
			} else {
				$cart_subtotal = $cart->get_subtotal() - $tax_ex_coupon;
				if ( $cart->get_subtotal_tax() > 0 && wc_prices_include_tax() ) {
					$tax_info = ' <small class="tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
				}
			}
			?>
			<tr class="subtotal-applied-coupons">
				<th>
					<span class="label-coupon"><?php esc_html_e( 'Subtotal with discount', 'image-haddad-wp' ); ?></span>
				</th>
				<td>
					<span class="content-coupon"><?php echo wp_kses_post( wc_price( $cart_subtotal ) . $tax_info ); ?></span>
				</td>
			</tr>
			<?php
		}
	}

	/**
	 * Order status custom email notification on admin status change
	 *
	 * @param string $order_id - unique order number.
	 * @param object $order - order object.
	 * @return void
	 *
	 * @snippet       Send Formatted Email @ WooCommerce Custom Order Status
	 * @sourcecode    https://businessbloomer.com/?p=91907
	 */
	public function haddad_notification_status_canceled( $order_id, $order ) {

		$heading = __( 'Order cancelled', 'image-haddad-wp' );
		$subject = __( 'Your order created on {order_date} at {site_title} webshop was cancelled', 'image-haddad-wp' );

		// Get WooCommerce email objects.
		$mailer = WC()->mailer()->get_emails();

		// Use email classes for customers in file includes/class-wc-emails, method WC_Emails->init()
		// replace email heading and subject.
		$mailer['WC_Email_Customer_Completed_Order']->heading             = $heading;
		$mailer['WC_Email_Customer_Completed_Order']->settings['heading'] = $heading;
		$mailer['WC_Email_Customer_Completed_Order']->subject             = $subject;
		$mailer['WC_Email_Customer_Completed_Order']->settings['subject'] = $subject;

		// Send the email with custom heading & subject.
		$mailer['WC_Email_Customer_Completed_Order']->trigger( $order_id );
	}

	/**
	 * Remove reCaptche from no-ContactForm 7 pages (without cf7 shortcode)
	 *
	 * @return void
	 */
	public function haddad_recaptcha_only_cf7() {
		global $post;
		if ( ! has_shortcode( $post->post_content, 'contact-form-7' ) ) {
			wp_dequeue_script( 'google-recaptcha' );
			wp_dequeue_script( 'wpcf7-recaptcha' );
		}
	}

	/**
	 * Start global var for single product summary.
	 *
	 * @return void
	 */
	public function start_product_summary() {
		global $is_summary;
		$is_summary = true;
		remove_action( 'flatsome_product_image_tools_top', 'flatsome_product_wishlist_button', 2 );
	}

	/**
	 * End global var for single product summary.
	 *
	 * @return void
	 */
	public function end_product_summary() {
		global $is_summary;
		$is_summary = false;
	}

	/**
	 * Flatsome YITH Wishlist button.
	 *
	 * @return void
	 */
	public function flatsome_wishlist_icon() {
		flatsome_product_wishlist_button();
	}

	/**
	 * Custom inline CSS for wishlist button.
	 *
	 * @return void
	 */
	public function flatsome_wishlist_icon_css() {
		$css  = '.product-summary .wishlist-icon { position: relative; display: flex; justify-content: flex-end; height: 0; }';
		$css .= '.product-summary .wishlist-icon > button { transform: translateY(-100%); margin-top: -1rem;}';
		$css .= '@media screen and (max-width: 549px) { .product-summary .wishlist-icon { display: inline-flex; top: -7px; left: 10px; } ';
		$css .= '.product-summary .wishlist-icon > button { transform: none; margin: 0 !important }}';
		wp_add_inline_style( $this->plugin_name, $css );
	}

	/**
	 * Haddad Fabriq/IH logo
	 *
	 * @return void
	 */
	public function haddad_fabriq_logo() {
		global $product, $is_summary;

		$has_fabriq = has_term( 'fabriq', 'product_cat', $product->get_id() );

		$options = $this->ihwp_wc_basic;
		if ( isset( $options['fabriq_logo'] ) && '' !== $options['fabriq_logo'] ) {
			$fabriq_logo = $options['fabriq_logo'];
		} else {
			$fabriq_logo = IMAGE_HADDAD_WP_URL . 'public/images/fabriq.png';
		}

		if ( isset( $options['ih_logo'] ) && '' !== $options['ih_logo'] ) {
			$ih_logo = $options['ih_logo'];
		} else {
			$ih_logo = IMAGE_HADDAD_WP_URL . 'public/images/logo-white-dark-back.png';
		}

		printf(
			'<span class="ih-fabriq-logo %s">%s<img src="%s" alt="%s"></span>',
			esc_attr( $has_fabriq ? 'fabriq' : 'ih' ),
			wp_kses_post( (bool) $is_summary ? ( '<span class="collection">' . __( 'Collection: ', 'image-haddad-wp' ) . '</span>' ) : '' ) ,
			esc_url( $has_fabriq ? $fabriq_logo : $ih_logo ),
			esc_attr( $has_fabriq ? 'Fabriq' : 'Image Haddad' )
		);
	}

	/**
	 * Get stock status from remote ERP server.
	 *
	 * @return void
	 */
	public function init_stanje_u_skladistima() {
		// Only for admins [ DODATI KONTROLU ZA DONJI KOD U PLUGIN OPTIONS ].
		// if ( ! current_user_can( 'activate_plugins' ) ) {
		// 	return;
		// }

		global $product;

		// For simple products, create stock table.
		if ( $product->is_type( 'simple' ) ) {

			$this->stanje_u_skladistima( $product->get_sku(), $product->get_stock_status() );

		} elseif ( $product->is_type( 'variable' ) ) {

			// To get array of available variations: $product->get_available_variations().

			// Prepare dom element to populate with ajax data.
			echo '<div class="skladista"></div>';
			// JS bellow based on /woocommerce/assets/js/frontend/add-to-cart-variation.js.
			?>
			<script>
				(function( $ ) {
					// When variations form is changed.
					$(document).on( 'found_variation.wc-variation-form','form.variations_form', function (event, variation) {
						if( ! variation ) {
							return;
						}
						get_stock_by_sku( variation.sku, variation.is_in_stock );
					} );
					// When varitions are reset.
					$(document).on( 'click.wc-variation-form', '.reset_variations', function (event) {
						$('.skladista').empty();
					} );
					// Pass the WC stock status, too.
					function get_stock_by_sku( sku, stock_status ) {
						$('.skladista').css( 'opacity', '0.5' );
						var ajaxUrl;
						if( typeof ihwpJsVars === 'undefined' ) {
							ajaxUrl = "<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>";
						}else {
							ajaxUrl = ihwpJsVars.ajaxurl;
						}
						$.ajax({
							type: "POST",
							dataType: "html",
							url: ajaxUrl,
							data: {
								nonce: ihwpJsVars.nonce,
								action: "stanje_u_skladistima",
								variationSku: sku,
								stock_status: stock_status
							},
							success: function (response) {
								$('.skladista').css( 'opacity', '1' ).replaceWith(response);

							},
							error: function (xhr, status, error) {
								var errorMessage = xhr.status + ": " + xhr.statusText;
								console.error(errorMessage);
							},
						});
					}
				})( jQuery );
			</script>
		<?php
		} // end elseif
	}

	/**
	 * Prepare to get stock data, and call HTML output method.
	 *
	 * @param  string $sku - unique for sku product.
	 * @param  string $stock_status - WooCommerce product setting.
	 *
	 * @return void
	 */
	public function stanje_u_skladistima( $sku, $stock_status = null ) {

		if ( ! is_admin() && wp_doing_ajax() && isset( $_POST['variationSku'] ) ) {
			check_ajax_referer( 'ihwp_nonce', 'nonce' );
		}
		// If $_POST is set method is called for variation, else for single product.
		$sku = isset( $_POST['variationSku'] ) ? esc_html( $_POST['variationSku'] ) : $sku;
		// WC stock status.
		$stock_status = isset( $_POST['stock_status'] ) ? esc_html( $_POST['stock_status'] ) : $stock_status;
		if ( 'true' === $stock_status || 'instock' === $stock_status ) {
			$stock_status = 'wc-in-stock';
		}

		// Get stock data for single product of variation by SKU (remote ERP server).
		$data = $this->remote_data_skladista( $sku );

		// Output simple product or single variation table html.
		$this->html_skladista( $data, $stock_status );

		if ( wp_doing_ajax() ) {
			wp_die();
		}
	}

	/**
	 * Get stock data for single product of variation, from remote ERP server, using SKU.
	 *
	 * @param  string $sku
	 *
	 * @return array $data
	 */
	public function remote_data_skladista( $sku ) {

		// Cleaning the SKU string - ignore slash ( / ) and all before slash.
		if ( ( $pos = strpos( $sku, '/' ) ) !== false ) {
			$sku = substr( $sku, $pos + 1 );
		}
		// Resource to fetch data.
		$url = 'http://haddad.xenios.center:3848/api/Stanje/' . esc_attr( $sku );
		// Get response.
		$response = wp_remote_get( esc_url_raw( $url ) );
		// If WP error in response (invalid or missing URL), or response not array (invalid response),
		// or server error (404).
		if ( is_wp_error( $response ) || ! is_array( $response ) || 404 === $response['response']['code'] ) {
			return false;
		}
		// From here on,everything should be ok to proceed.
		$body     = wp_remote_retrieve_body( $response );
		$body_obj = json_decode( $body );
		// JSON decoded response body should be an object (one more check...).
		if ( ! is_object( $body_obj ) ) {
			return;
		}
		// Prepared data from remote server, as array of objects.
		$data = get_object_vars( $body_obj )['Table'];

		return $data;
	}

	/**
	 * Create HTML output with remote ERP stock data.
	 *
	 * @param  array  $data - data provided from remote ERP for current product.
	 * @param  string $stock_status - WooCommerce stock status for current product.
	 *
	 * @return void
	 */
	public function html_skladista( $data, $stock_status ) {

		if ( ! $data ) {
			return;
		}

		echo '<div class="skladista"><span>Dostupnost po trgovinama</span>';

		foreach ( $data as $skladiste ) {
			$stanje = $skladiste->stanjeTekst;
			switch ( $stanje ) {
				case 'Dostupno':
					$class = ' dostupno';
					break;
				case 'Zadnji komad':
					$class = ' zadnji';
					break;
				case 'Nedostupno':
					$class = ' nedostupno';
					break;
				default:
					$class = '';
			}
			// ONLY WEB SHOP: Always available, if WC stock status (not ERP) is in stock.
			if ( 'web-shop' === sanitize_title( strtolower( $skladiste->trgovina ) ) && 'wc-in-stock' === $stock_status ) {
				$class  = ' dostupno web-shop';
				$stanje = 'Dostupno';
			}

			echo '<div class="lokacija">';
			echo '<div class="naziv' . $class . '">' . esc_html( $skladiste->trgovina ) . '</div>';
			echo current_user_can( 'activate_plugins' ) ? '<div class="kolicina">' . esc_html( $skladiste->kolicina ) . '</div>' : '';
			echo '<div class="stanje">' . esc_html( $stanje ) . '</div>';
			echo '</div>';
		}
		echo '</div>';
	}

	/**
	 * Display subcategories on Woo category page top.
	 *
	 * @return void
	 */
	public function catalog_sub_categories() {
		// List subcategories of current product category.
		if ( is_tax( 'product_cat' ) ) {
			$term_id       = get_queried_object()->term_id;
			$taxonomy_name = 'product_cat';
			$term_children = get_term_children( $term_id, $taxonomy_name );

			if ( ! empty( $term_children ) ) {
				echo '<ul class="product-sub-categories">';
				foreach ( $term_children as $child ) {
					$term      = get_term_by( 'id', $child, $taxonomy_name );
					$term_link = get_term_link( $child, $taxonomy_name );
					if ( isset( $_GET['on_sale'] ) && '' === $_GET['on_sale'] ) {
						$term_link = add_query_arg( 'on_sale', true, $term_link );
					}
					echo '<li><a href="' . esc_url( $term_link ) . '">' . esc_html( $term->name ) . '</a></li>';
				}
				echo '</ul>';
			}
		}
	}

	/**
	 * Cross-sale title change.
	 *
	 * @return string
	 */
	public function cross_sale_title() {
		return __( 'Also recommended', 'image-haddad-wp' );
	}
}
