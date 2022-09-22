<?php
/**
 * Settings in sections and tabs.
 * based on https://github.com/ahmadawais/WP-OOP-Settings-API
 *
 * @package image-haddad-wp/settings
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define global constants.
 *
 * @since 1.0.0
 */
if ( ! defined( 'WPOSA_NAME' ) ) {
	define( 'WPOSA_NAME', trim( dirname( plugin_basename( __FILE__ ) ), '/' ) );
}

if ( ! defined( 'WPOSA_DIR' ) ) {
	define( 'WPOSA_DIR', WP_PLUGIN_DIR . '/' . WPOSA_NAME );
}

if ( ! defined( 'WPOSA_URL' ) ) {
	define( 'WPOSA_URL', WP_PLUGIN_URL . '/' . WPOSA_NAME );
}

/**
 * WP-OOP-Settings-API Initializer
 *
 * Initializes the WP-OOP-Settings-API.
 *
 * @since   1.0.0
 */


/**
 * Class `WP_OOP_Settings_API`.
 *
 * @since 1.0.0
 */
require_once WPOSA_DIR . '/wp-osa/class-wp-osa.php';


/**
 * Actions/Filters
 *
 * Related to all settings API.
 *
 * @since  1.0.0
 */
if ( class_exists( 'WP_OSA' ) ) {


	class settingsInit {

		/**
		 * Object Instantiation.
		 * Object for the class `WP_OSA`.
		 *
		 * @var string
		 */
		public $wposa_obj;

		/**
		 * Get current theme info.
		 *
		 * @var string
		 */
		public $current_theme;

		/**
		 * If JSON log should be displayed.
		 *
		 * @var string - values off or on.
		 */
		public $show_json_log;

		/**
		 * If DB log should be displayed.
		 *
		 * @var string - values off or on.
		 */
		public $show_db_log;

		public function __construct() {

			$this->wposa_obj = new WP_OSA();

			// Select the type od social icons (theme, plugin, or none).
			$this->current_theme = wp_get_theme()->get( 'Template' );

			// Include logs.
			require_once IMAGE_HADDAD_WP_DIR . 'admin/settings/log_products_draft_publish_json.php';
			require_once IMAGE_HADDAD_WP_DIR . 'admin/settings/log_products_draft_publish_db.php';

			$options             = get_option( 'ihwp_other' );
			$this->show_json_log = isset( $options['show_json_log'] ) ? $options['show_json_log'] : '';
			$this->show_db_log   = isset( $options['show_db_log'] ) ? $options['show_db_log'] : '';

		}

		/**
		 * Start with creating sections to populate with fields.
		 *
		 * @return void
		 */
		public function build_plugin_settings_sections() {

			// Section: WooCommerce IMPORTANT Settings.
			$this->wposa_obj->add_section(
				array(
					'id'        => 'ihwp_wc_basic',
					'title'     => __( 'WooCommerce Basic', 'image-haddad-wp' ),
					'no_submit' => 'no',
				)
			);

			// Section: WooCommerce Other.
			$this->wposa_obj->add_section(
				array(
					'id'        => 'ihwp_wc',
					'title'     => __( 'WooCommerce Other', 'image-haddad-wp' ),
					'no_submit' => 'no',
				)
			);

			// Section: Custom CSS Settings.
			$this->wposa_obj->add_section(
				array(
					'id'        => 'ihwp_css',
					'title'     => __( 'Custom CSS', 'image-haddad-wp' ),
					'no_submit' => 'no',
				)
			);

			// Section: Other Settings.
			$this->wposa_obj->add_section(
				array(
					'id'        => 'ihwp_other',
					'title'     => __( 'Other Settings', 'image-haddad-wp' ),
					'no_submit' => 'no',
				)
			);

			// Section: Log.
			if ( 'on' === $this->show_json_log ) {
				$this->wposa_obj->add_section(
					array(
						'id'        => 'ihwp_log',
						'title'     => __( 'Log WP JSON', 'image-haddad-wp' ),
						'desc'      => image_haddad_json_log(),
						'no_submit' => 'yes',
					)
				);
			}

			// Section: Log.
			if ( 'on' === $this->show_db_log ) {
				$this->wposa_obj->add_section(
					array(
						'id'        => 'ihwp_log_db',
						'title'     => __( 'Log DB', 'image-haddad-wp' ),
						'desc'      => image_haddad_db_log(),
						'no_submit' => 'yes',
					)
				);
			}

			// Section: Documentation.
			require_once IMAGE_HADDAD_WP_DIR . 'admin/settings/doc.php';
			$this->wposa_obj->add_section(
				array(
					'id'        => 'ihwp_doc',
					'title'     => __( 'Documentation', 'image-haddad-wp' ),
					'desc'      => wp_kses_post( $doc_html ),
					'no_submit' => 'yes',
				)
			);
		}
		/**
		 * Build plugin settings page.
		 *
		 * @return void
		 */
		public function build_plugin_settings() {

			// Repeating text strings.
			$setting_reload_text = '<br><i>' . __( 'Change in this setting will require a page reload.', 'image-haddad-wp' ) . '</i>';
			$disabled_log_text   = '<br><strong>' . __( 'Disabled logging will produce gaps in logged records (if products are updated after disabling this option).', 'image-haddad-wp' ) . '<br>' . __( 'Once enabled, will start logging only from the moment enabled.', 'image-haddad-wp' ) . '</strong>';

			// SECTIONS #############################.

			$this->build_plugin_settings_sections();

			// #######################################################################
			// WOOCOMMERCE BASIC SETTINGS SECTION FIELDS #############################.
			// #######################################################################

			// Field: Checkbox.
			$this->wposa_obj->add_field(
				'ihwp_wc_basic',
				array(
					'id'      => 'enable_ga',
					'type'    => 'checkbox',
					'name'    => __( 'Enable Custom Google Analytics', 'image-haddad-wp' ),
					'desc'    => __( 'Custom GA is added in code. Disable this if you add GA plugin, such as "WooCommerce Google Analytics"', 'image-haddad-wp' ),
					'default' => 'on',
				)
			);

			// Field: Checkbox.
			$this->wposa_obj->add_field(
				'ihwp_wc_basic',
				array(
					'id'      => 'enable_gtm',
					'type'    => 'checkbox',
					'name'    => __( 'Enable Custom Google Tag Manager', 'image-haddad-wp' ),
					'desc'    => __( 'Custom GTM is added in code.', 'image-haddad-wp' ),
					'default' => 'on',
				)
			);

			// Field: Separator.
			$this->wposa_obj->add_field(
				'ihwp_wc_basic',
				array(
					'id'   => 'sep_00',
					'type' => 'separator',
				)
			);

			// Field: Checkbox.
			$this->wposa_obj->add_field(
				'ihwp_wc_basic',
				array(
					'id'      => 'disable_reviews',
					'type'    => 'checkbox',
					'name'    => __( 'Disable product reviews', 'image-haddad-wp' ),
					'desc'    => __( 'no product reviews on this shop ...', 'image-haddad-wp' ),
					'default' => 'on',
				)
			);

			// Field: Checkbox.
			$this->wposa_obj->add_field(
				'ihwp_wc_basic',
				array(
					'id'   => 'disable_coupon_cache',
					'type' => 'checkbox',
					'name' => __( 'Disable coupon prices cache', 'image-haddad-wp' ),
					'desc' => __( 'if you experience issues with coupon prices in catalog/categories (products in loop), activate this temporarily.<br>Don\'t. forget to deactivate this after you confirm correct coupon prices display.', 'image-haddad-wp' ),
				)
			);

			// Field: Select.
			$this->wposa_obj->add_field(
				'ihwp_wc_basic',
				array(
					'id'      => 'coupon_price_choice',
					'type'    => 'select',
					'name'    => __( 'Label on coupon prices', 'image-haddad-wp' ),
					'options' => array(
						'none'    => __( 'No label', 'image-haddad-wp' ),
						'default' => __( 'Coupon: [couponcode]', 'image-haddad-wp' ),
						'custom'  => __( 'Custom label', 'image-haddad-wp' ),
					),
					'default' => 'default',
				)
			);

			// Field: Text.
			$this->wposa_obj->add_field(
				'ihwp_wc_basic',
				array(
					'id'          => 'coupon_price_custom_label',
					'type'        => 'text',
					'name'        => __( 'Coupon price custom label', 'image-haddad-wp' ),
					'default'     => __( 'Special discount price', 'image-haddad-wp' ),
					'placeholder' => __( 'Enter your custom text here.', 'image-haddad-wp' ),
				)
			);

			// Field: File.
			$this->wposa_obj->add_field(
				'ihwp_wc_basic',
				array(
					'id'      => 'terms_pdf',
					'type'    => 'file',
					'name'    => __( 'Terms and conditions PDF', 'image-haddad-wp' ),
					'desc'    => __( 'Upload or choose a PDF file for terms and conditions. PDF with terms and conditions will be attached to order emails.', 'image-haddad-wp' ),
					'options' => array(
						'button_label' => __( 'Choose file', 'image-haddad-wp' ),
					),
				)
			);

			// Field: Image.
			$this->wposa_obj->add_field(
				'ihwp_wc_basic',
				array(
					'id'      => 'fabriq_logo',
					'type'    => 'image',
					'name'    => __( 'Fabriq Logo', 'image-haddad-wp' ),
					'desc'    => __( 'Fabriq logo placement is theme dependent.. Fabriq logo is used for products in "Fabriq" category. In case of theme change contact developer', 'image-haddad-wp' ),
					'options' => array(
						'button_label' => __( 'Choose image', 'image-haddad-wp' ),
					),
				)
			);

			// Field: Image.
			$this->wposa_obj->add_field(
				'ihwp_wc_basic',
				array(
					'id'      => 'ih_logo',
					'type'    => 'image',
					'name'    => __( 'Image Haddad Logo', 'image-haddad-wp' ),
					'desc'    => __( 'Image Haddad logo placement is theme dependent. Image Haddad logo is used for all products NOT in "Fabriq" category. In case of theme change contact developer. ', 'image-haddad-wp' ),
					'options' => array(
						'button_label' => __( 'Choose image', 'image-haddad-wp' ),
					),
				)
			);

			// #######################################################################
			// WOOCOMMERCE OTHER SETTINGS SECTION FIELDS #############################.
			// #######################################################################

			$this->wposa_obj->add_field(
				'ihwp_wc',
				array(
					'id'                => 'price_font_color',
					'type'              => 'color',
					'name'              => __( 'Sale price font color', 'image-haddad-wp' ),
					'desc'              => __( 'applies in catalog and single product.', 'image-haddad-wp' ),
					'default'           => '',
				)
			);

			$this->wposa_obj->add_field(
				'ihwp_wc',
				array(
					'id'                => 'price_back_color',
					'type'              => 'color',
					'name'              => __( 'Sale price back color', 'image-haddad-wp' ),
					'desc'              => __( 'applies in catalog and single product.', 'image-haddad-wp' ),
					'default'           => '',
				)
			);

			// Field: Separator.
			$this->wposa_obj->add_field(
				'ihwp_wc',
				array(
					'id'   => 'sep_01',
					'type' => 'separator',
				)
			);

			$this->wposa_obj->add_field(
				'ihwp_wc',
				array(
					'id'   => 'title_wc_settings_single',
					'type' => 'title',
					'name' => '<h3>' . __( 'Single product settings', 'image-haddad-wp' ) . '</h3>',
				)
			);
			// SINGLE PRODUCT NOTE 1: Field: WP_Editor WYSIWYG.
			$this->wposa_obj->add_field(
				'ihwp_wc',
				array(
					'id'   => 'sp_custom_content_1',
					'type' => 'wysiwyg',
					'name' => __( 'Custom content on single product 1', 'image-haddad-wp' ),
					'desc' => __( 'Displayed in product summary', 'image-haddad-wp' ),
				)
			);
			$this->wposa_obj->add_field(
				'ihwp_wc',
				array(
					'id'                => 'ccsp_priority_1',
					'type'              => 'number',
					'name'              => __( 'Priority for Custom content 1', 'image-haddad-wp' ),
					'desc'              => __( 'Priority sets the element place in product summmary.', 'image-haddad-wp' ),
					'default'           => 36,
					'sanitize_callback' => 'intval',
				)
			);

			// SINGLE PRODUCT NOTE 2: Field: WP_Editor WYSIWYG.
			$this->wposa_obj->add_field(
				'ihwp_wc',
				array(
					'id'   => 'sp_custom_content_2',
					'type' => 'wysiwyg',
					'name' => __( 'Custom content on single product 2', 'image-haddad-wp' ),
					'desc' => __( 'Displayed in product summary', 'image-haddad-wp' ),
				)
			);
			$this->wposa_obj->add_field(
				'ihwp_wc',
				array(
					'id'                => 'ccsp_priority_2',
					'type'              => 'number',
					'name'              => __( 'Priority for Custom content 2', 'image-haddad-wp' ),
					'desc'              => __( 'Priority sets the element place in product summmary.', 'image-haddad-wp' ),
					'default'           => 35,
					'sanitize_callback' => 'intval',
				)
			);

			$social_icons = array(
				'none'        => __( 'None', 'image-haddad-wp' ),
				'ihwp_plugin' => __( 'From this plugin', 'image-haddad-wp' ),
			);

			// Flatsome WP theme support - add Flatsome social icons option.
			$social_icons = array_merge( $social_icons, ( 'flatsome' === $this->current_theme ) ? array( 'flatsome' => 'Flatsome theme' ) : array() );
			// Field: Select.
			$this->wposa_obj->add_field(
				'ihwp_wc',
				array(
					'id'      => 'single_product_social',
					'type'    => 'select',
					'name'    => __( 'Social sharing icons', 'image-haddad-wp' ),
					'options' => $social_icons,
					'default' => 'ihwp_plugin',
				)
			);

			// Field: Checkbox.
			$this->wposa_obj->add_field(
				'ihwp_wc',
				array(
					'id'      => 'hide_upsells',
					'type'    => 'checkbox',
					'name'    => __( 'Hide upsell products.', 'image-haddad-wp' ),
					'desc'    => __( 'Upsell products are added manually on singular products.', 'image-haddad-wp' ),
					'default' => 'on',
				)
			);

			// Field: Separator.
			$this->wposa_obj->add_field(
				'ihwp_wc',
				array(
					'id'   => 'sep_02',
					'type' => 'separator',
				)
			);

			$this->wposa_obj->add_field(
				'ihwp_wc',
				array(
					'id'   => 'title_wc_settings_cart_checkout',
					'type' => 'title',
					'name' => '<h3>' . __( 'Cart/Checkout settings', 'image-haddad-wp' ) . '</h3>',
				)
			);
			// ORDER CONFIRMATION PAGE TEXT - Field: Textarea.
			$this->wposa_obj->add_field(
				'ihwp_wc',
				array(
					'id'   => 'order_received',
					'type' => 'textarea',
					'name' => __( 'Order received text', 'image-haddad-wp' ),
					'desc' => __( 'Custom text on "order/received" page. If empty, default text will be used.', 'image-haddad-wp' ),
				)
			);

			// Field: Checkbox.
			$this->wposa_obj->add_field(
				'ihwp_wc',
				array(
					'id'      => 'hide_coupon_on_checkout',
					'type'    => 'checkbox',
					'name'    => __( 'Hide coupon field on checkout', 'image-haddad-wp' ),
					'desc'    => __( 'to show coupon on checkout, uncheck this.', 'image-haddad-wp' ),
					'default' => 'on',
				)
			);

			// Field: Separator.
			$this->wposa_obj->add_field(
				'ihwp_wc',
				array(
					'id'   => 'sep_03',
					'type' => 'separator',
				)
			);

			$this->wposa_obj->add_field(
				'ihwp_wc',
				array(
					'id'   => 'title_wc_settings_widgets',
					'type' => 'title',
					'name' => '<h3>' . __( 'Widgets and other.', 'image-haddad-wp' ) . '</h3>',
				)
			);
			// Exclude wc categories from Product categories Widget - Field: Textarea.
			$this->wposa_obj->add_field(
				'ihwp_wc',
				array(
					'id'      => 'exclude_wc_cats',
					'type'    => 'textarea',
					'name'    => __( 'Exclude categories from product category widget.', 'image-haddad-wp' ),
					'desc'    => __( 'Use category slugs. Add each product category in new line', 'image-haddad-wp' ),
					'default' => 'arhiva-proizvoda',
				)
			);

			// ################################################################
			// CUSTOM CSS SETTINGS SECTION FIELDS #############################.
			// ################################################################

			// Field: Textarea.
			$this->wposa_obj->add_field(
				'ihwp_css',
				array(
					'id'   => 'custom_css_desk',
					'type' => 'textarea',
					'name' => __( 'Custom CSS - all screens', 'image-haddad-wp' ),
					'desc' => __( 'add custom CSS code which will apply to all screen resolutions.', 'image-haddad-wp' ),
				)
			);
			$this->wposa_obj->add_field(
				'ihwp_css',
				array(
					'id'   => 'custom_css_tab',
					'type' => 'textarea',
					'name' => __( 'Custom CSS - tablet', 'image-haddad-wp' ),
					'desc' => __( 'add custom CSS for max. tablet screen sizes.', 'image-haddad-wp' ),
				)
			);
			$this->wposa_obj->add_field(
				'ihwp_css',
				array(
					'id'   => 'custom_css_mob',
					'type' => 'textarea',
					'name' => __( 'Custom CSS - mobile', 'image-haddad-wp' ),
					'desc' => __( 'add custom CSS for max. mobile screen sizes.', 'image-haddad-wp' ),
				)
			);

			// ###########################################################
			// OTHER SETTINGS SECTION FIELDS #############################.
			// ###########################################################

			// Legacy for Haumea theme.
			if ( 'haumea' === $this->current_theme ) {
				// STICKER NOTE - Field: Text.
				$this->wposa_obj->add_field(
					'ihwp_other',
					array(
						'id'      => 'sticker_note',
						'type'    => 'textarea',
						'name'    => __( 'Sticker note', 'image-haddad-wp' ),
						'desc'    => __( 'A note in sticker bar - Haumea theme.', 'image-haddad-wp' ),
						'default' => 'Default Text',
					)
				);

				// Field: Checkbox.
				$this->wposa_obj->add_field(
					'ihwp_other',
					array(
						'id'   => 'display_languages',
						'type' => 'checkbox',
						'name' => __( 'Display languages', 'image-haddad-wp' ),
						'desc' => __( 'Show languages selection element in header.', 'image-haddad-wp' ),
					)
				);
			}

			// Field: Checkbox.
			$this->wposa_obj->add_field(
				'ihwp_other',
				array(
					'id'      => 'hide_to_seo',
					'type'    => 'checkbox',
					'default' => 'on',
					'name'    => __( 'Temporary hide non HR pages from SE robots', 'image-haddad-wp' ),
					'desc'    => __( 'Uncheck this when site is mutlingual', 'image-haddad-wp' ),
				)
			);

			// Field: Checkbox.
			$this->wposa_obj->add_field(
				'ihwp_other',
				array(
					'id'      => 'no_comments',
					'type'    => 'checkbox',
					'default' => 'on',
					'name'    => __( 'Remove comments support', 'image-haddad-wp' ),
					'desc'    => __( 'this disables default comments support (posts, pages)', 'image-haddad-wp' ),
				)
			);

			// Field: Separator.
			$this->wposa_obj->add_field(
				'ihwp_other',
				array(
					'id'   => 'sep_logs',
					'type' => 'separator',
				)
			);

			// Field: Title.
			$this->wposa_obj->add_field(
				'ihwp_other',
				array(
					'id'   => 'title_logs',
					'type' => 'title',
					'name' => '<h3>' . __( 'Admin logs settings', 'image-haddad-wp' ) . '</h3>',
				)
			);

			// Field: Checkbox.
			$this->wposa_obj->add_field(
				'ihwp_other',
				array(
					'id'      => 'enable_json_log',
					'type'    => 'checkbox',
					'default' => 'on',
					'name'    => __( 'Enable logging product changes (JSON)', 'image-haddad-wp' ),
					'desc'    => __( 'products changes are logged in JSON file (when product status changes from draft to publish).', 'image-haddad-wp' ) . $disabled_log_text,
				)
			);

			// Field: Checkbox.
			$this->wposa_obj->add_field(
				'ihwp_other',
				array(
					'id'      => 'show_json_log',
					'type'    => 'checkbox',
					'default' => 'on',
					'name'    => __( 'Show log (JSON)', 'image-haddad-wp' ),
					'desc'    => __( 'will display JSON file logged changes in products (when product status changes from draft to publish).', 'image-haddad-wp' ) . $setting_reload_text,
				)
			);


			// Field: Separator.
			$this->wposa_obj->add_field(
				'ihwp_other',
				array(
					'id'   => 'sep_between_logs',
					'type' => 'separator',
				)
			);


			// Field: HTML.
			$this->wposa_obj->add_field(
				'ihwp_other',
				array(
					'id'      => 'db_log_notice',
					'type'    => 'html',
					'name'    => '<strong style="color:red">'. __( 'BUG!', 'image-haddad-wp' ) . '</strong>',
					'desc'    => '<strong style="color:red">Kod za log zapise u bazu podataka ima bug. TO DO: Ili riješiti bug, ili maknuti opciju</strong>.<br>Checkboxevi ispod su trenutno onemogućeni sa argumentom "disabled" => "yes"',
				)
			);
			// Field: Checkbox.
			$this->wposa_obj->add_field(
				'ihwp_other',
				array(
					'id'      => 'enable_db_log',
					'type'    => 'checkbox',
					'default' => 'on',
					'name'    => __( 'Enable log product changes (DB)', 'image-haddad-wp' ),
					'desc'    => __( 'products changes are logged in database (when product status changes from draft to publish).', 'image-haddad-wp' ) . $disabled_log_text,
					'disabled' => 'yes',
				)
			);

			// Field: Checkbox.
			$this->wposa_obj->add_field(
				'ihwp_other',
				array(
					'id'      => 'show_db_log',
					'type'    => 'checkbox',
					'default' => 'on',
					'name'    => __( 'Show log (DB)', 'image-haddad-wp' ),
					'desc'    => __( 'will display database logged changes in products (when product status changes from draft to publish).', 'image-haddad-wp' ) . $setting_reload_text,
					'disabled' => 'yes',
				)
			);

			// Field: Separator.
			$this->wposa_obj->add_field(
				'ihwp_other',
				array(
					'id'   => 'sep_logs_limit',
					'type' => 'separator',
				)
			);


			$this->wposa_obj->add_field(
				'ihwp_other',
				array(
					'id'                => 'limit_log_items',
					'type'              => 'number',
					'name'              => __( 'Logged items per page', 'image-haddad-wp' ),
					'desc'              => __( 'How many logged product changes will be shown on one page of logs (applies to both JSON and DB logs).', 'image-haddad-wp' ) . $setting_reload_text,
					'default'           => 10,
					'sanitize_callback' => 'intval',
				)
			);
		}

		private function ihwp_kses( $output ) {
			$allowed_html = array(
				'a'     => array(
					'class' => array(),
					'style' => array(),
					'href'  => array(),
				),
				'p'     => array(
					'class' => array(),
					'style' => array(),
				),
				'div'   => array(
					'class'     => array(),
					'style'     => array(),
				),
				'input' => array(
					'class'     => array(),
					'type'      => array(),
					'data-dbid' => array(),
					'data-tstamp' => array(),
					'title'     => array(),
					'value'     => array(),
				),
				'code'  => array(),
				'h2'  => array(
					'class' => array(),
					'style' => array(),
				),
				'h3'  => array(
					'class' => array(),
					'style' => array(),
				),
				'h4'  => array(
					'class' => array(),
					'style' => array(),
				),
				'span'  => array(
					'class' => array(),
					'style' => array(),
				),
			);
			return wp_kses( $output, $allowed_html );
		}


	}

	/**
	 * Instantiate the class.
	 *
	 * @return void
	 */
	function image_haddad_display_settings() {
		$settings = new settingsInit();
		return $settings->build_plugin_settings();
	}

	image_haddad_display_settings();

}
