<?php
/**
 * FacetWP functions +  FWP/Polylang compatiblity
 *
 * @see     https://gist.github.com/mgibbs189/2c394563084ad3fc8c39df1e4b67ead7
 * @author  FacetWP, LLC, Micemade
 * @package Haumea-Child/Inc
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Image_Haddad_FWP
 */
class Image_Haddad_FWP {

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

		// Render FWP outputs if FacetWP is active.
		if ( function_exists( 'FWP' ) ) {
			add_filter( 'facetwp_render_output', array( $this, 'facetwp_render_output_pager' ), 10, 2 );
			add_filter( 'facetwp_shortcode_html', array( $this, 'facetwp_shortcode_html_otuput' ), 100, 2 );
			add_action( 'wp_footer', array( $this, 'facetwp_js' ) );
		}

		// Polylang hooks.
		if ( function_exists( 'FWP' ) && function_exists( 'pll_register_string' ) ) {
			add_action( 'wp_footer', array( $this, 'wp_footer' ), 30 );
			add_action( 'admin_init', array( $this, 'register_strings' ) );
			add_action( 'facetwp_refresh', array( $this, 'set_langcode' ) );

			add_filter( 'facetwp_query_args', array( $this, 'facetwp_query_args' ), 10, 2 );
			add_filter( 'facetwp_indexer_query_args', array( $this, 'facetwp_indexer_query_args' ) );
			add_filter( 'facetwp_render_params', array( $this, 'support_preloader' ) );
			add_filter( 'facetwp_i18n', array( $this, 'facetwp_i18n' ) );
		}

	}

	/**
	 * Output FWP pager.
	 *
	 * @param array $output
	 * @param array $params
	 * @return $output
	 */
	public function facetwp_render_output_pager( $output, $params ) {
		$current_theme = wp_get_theme()->get( 'Template' );
		if ( isset( $output['settings']['pager'] ) ) {
			// Hook za provjeru ako je pager aktivan, koristi se u "archive-product.php".
			add_filter( 'is_facetwp_pager', '__return_true' );

			// Flatsome theme pager output.
			if ( 'flatsome' === $current_theme ) {
				add_action( 'woocommerce_before_main_content', array( $this, 'facet_flatsome_catalog_open_div' ), 11 );
				// Remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination' , 10 );.
				add_action( 'woocommerce_after_main_content', array( $this, 'facet_flatsome_pager_closing_tag' ), 11 );
			}
		}
		return $output;
	}

	/**
	 * Div wrapper for facet.
	 * Hooked to 'woocommerce_before_main_content', priority 11 (after WC opening tag)
	 *
	 * @return void
	 */
	public function facet_flatsome_catalog_open_div() {
		echo '<div class="facetwp-template">';
	}

	/**
	 * Render Facet pager shortcode and closing div tag.
	 * closing div tag is opened with 'woocommerce_before_main_content' hook.
	 * in this class method facet_flatsome_catalog_open_div.
	 *
	 * @return void
	 */
	public function facet_flatsome_pager_closing_tag() {
		echo do_shortcode( '[facetwp facet="pagination"]' );
		echo '</div><!-- Facet wrapper -->';
	}

	/**
	 * Change HTML of facet output
	 *
	 * @param array $output
	 * @param array $atts
	 * @return void
	 */
	public function facetwp_shortcode_html_otuput( $output, $atts ) {
		if ( isset( $atts['categories'] ) ) {
			$output = str_replace( 'facetwp-expand', 'facetwp-expand test', $output );
		}
		return $output;
	}

	/**
	 * Add inline JS for FWP
	 *
	 * @return void
	 */
	public function facetwp_js() {
		wp_enqueue_script( 'jquery-effects-core' );
		?>
		<script>
			(function($) {
				$(function() {

					// Check for FWP object.
					if ( 'object' !== typeof FWP ) {
						return;
					}
					// FacetWP template.
					var template = $('.facetwp-template');

					FWP.hooks.addAction('facetwp/loaded', function() {
						$('.facetwp-type-checkboxes .facetwp-depth.visible').prev('.facetwp-checkbox').toggleClass('expanded');
						$('.facetwp-type-checkboxes').find('.facetwp-expand').addClass('fa fa-angle-down').empty();
					}, 100 );

					// Add CSS classes to pagination (FWP pager).
					$(document).on('facetwp-loaded', function() {
						// Flatsome theme support.
						if( $( 'body' ).hasClass( 'theme-flatsome') ) {
							$('.facetwp-pager').addClass( 'page-numbers nav-pagination links text-center' );
							// Facet WP template products wrapper.
							var template = $('facetwp-template');
							// Remove class loading from template.
							template.removeClass('loading');

							var templatePos = template.offset(),
								stickyHead  = $('.header-wrapper.stuck').outerHeight(true);

							if( undefined !== stickyHead ) {
								$('html, body').animate({ scrollTop: templatePos.top - stickyHead - 50 }, 800 );
							}
						};

						$('.facetwp-page').addClass( 'button icon-button' );
						// if ( '' == FWP.build_query_string() && 'shop' == FWP_HTTP.uri) {
						// 	FWP.is_reset = true;
						// 	FWP.facets['product_categories'] = ['clothing'];
						// 	FWP.refresh();
						// }

						// see if any facets are in use
						var in_use = false;
						$.each(FWP.facets, function(name, val) {
							if (val.length > 0) {
								in_use = true;
							}
						});
						// true = show, false = hide
						$('.facetwp-reset-btn').toggle(in_use);

					});

					$(document).on('click', '.facetwp-reset-btn', function() {
						FWP.reset();
					});

					$(document).on('facetwp-refresh', function() {
						// Flatsome theme support.
						if( $( 'body' ).hasClass( 'theme-flatsome') ) {
							$('.facetwp-template').addClass('loading');
						}
					});

					// Rotate arrow icon for expanded facets.
					$(document).on('click', '.facetwp-expand', function() {
						$(this).toggleClass('expanded fa-rotate-180').empty();
						$(this).parent().toggleClass('expanded');
					});

				});

			})(jQuery);
		</script>
		<?php
	}

	/**
	 * ===================
	 * POLYLANG & FWP
	 * ===================
	 */

	/**
	 * Put the language into FWP_HTTP
	 */
	public function wp_footer() {
		if ( function_exists( 'pll_current_language' ) ) {
			$lang = pll_current_language();
			?>
			<script>if ('undefined' != typeof FWP_HTTP) FWP_HTTP.lang = "<?php echo esc_js( $lang ); ?>";</script>
			<?php
		}
	}

	/**
	 * On ajax refreshes, set the langcode
	 */
	public function set_langcode() {
		if ( isset( FWP()->facet->http_params['lang'] ) ) {
			$_GET['lang'] = FWP()->facet->http_params['lang'];
		}
	}

	/**
	 * Support FacetWP preloading (3.0.4+)
	 *
	 * @param array $params - preloader parameters.
	 * @return $params
	 */
	public function support_preloader( $params ) {
		if ( isset( $params['is_preload'] ) && function_exists( 'pll_current_language' ) ) {
			$params['http_params']['lang'] = pll_current_language();
		}

		return $params;
	}

	/**
	 * Query posts for the current language
	 *
	 * @param array  $args - array of arguments.
	 * @param object $class - FacetWP query class.
	 * @return $args
	 */
	public function facetwp_query_args( $args, $class ) {
		if ( isset( $class->http_params['lang'] ) ) {
			$args['lang'] = $class->http_params['lang'];
		}

		return $args;
	}

	/**
	 * Index all languages
	 *
	 * @param array $args - languages settings array.
	 * @return $args
	 */
	public function facetwp_indexer_query_args( $args ) {
		$args['lang'] = ''; // query posts in all languages
		return $args;
	}

	/**
	 * Register dynamic strings
	 */
	public function register_strings() {
		$facets    = FWP()->helper->get_facets();
		$whitelist = array( 'label', 'label_any', 'placeholder' );

		if ( ! empty( $facets ) ) {
			foreach ( $facets as $facet ) {
				foreach ( $whitelist as $k ) {
					if ( ! empty( $facet[ $k ] ) ) {
						pll_register_string( 'FacetWP', $facet[ $k ] );
					}
				}
			}
		}
	}

	/**
	 * Handle string translations
	 *
	 * @param string $string - translation string.
	 * @return $string
	 */
	public function facetwp_i18n( $string ) {
		$lang    = pll_current_language();
		$default = pll_default_language();

		if ( isset( FWP()->facet->http_params['lang'] ) ) {
			$lang = FWP()->facet->http_params['lang'];
		}

		if ( $lang != $default ) {
			return pll_translate_string( $string, $lang );
		}

		return $string;
	}
}

/**
 * Instantiate the class.
 *
 * @return void
 */
function image_haddad_fwp() {
	return new Image_Haddad_FWP();
}
// Initialize the class.
image_haddad_fwp();
