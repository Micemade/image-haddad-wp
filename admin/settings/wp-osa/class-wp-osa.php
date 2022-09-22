<?php
/**
 * Main Class file for `WP_OSA`
 *
 * Main class that deals with all other classes.
 *
 * @since   1.0.0
 * @package image-haddad-wp/settings
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'WP_OSA' ) ) :

	/**
	 * WP_OSA.
	 *
	 * WP Settings API Class.
	 *
	 * @since 1.0.0
	 */
	class WP_OSA {

		/**
		 * Sections array.
		 *
		 * @var   array
		 * @since 1.0.0
		 */
		private $sections_array = array();

		/**
		 * Fields array.
		 *
		 * @var   array
		 * @since 1.0.0
		 */
		private $fields_array = array();

		/**
		 * To discard submit button or not.
		 *
		 * @var boolean
		 */
		private $no_submit = array();

		/**
		 * Constructor.
		 *
		 * @since  1.0.0
		 */
		public function __construct() {
			// Enqueue the admin scripts.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			// Hook it up.
			add_action( 'admin_init', array( $this, 'admin_init' ) );
			// Menu.
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		}

		/**
		 * Admin Scripts.
		 *
		 * @since 1.0.0
		 */
		public function admin_scripts() {
			// jQuery is needed.
			wp_enqueue_script( 'jquery' );
			// Color Picker.
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );
			// Media Uploader.
			wp_enqueue_media();
		}

		/**
		 * Set Sections.
		 *
		 * @param array $sections - settings sections array.
		 * @since 1.0.0
		 */
		public function set_sections( $sections ) {
			// Bail if not array.
			if ( ! is_array( $sections ) ) {
				return false;
			}

			// Assign to the sections array.
			$this->sections_array = $sections;

			return $this;
		}

		/**
		 * Add a single section.
		 *
		 * @param array $section - array.
		 * @since 1.0.0
		 */
		public function add_section( $section ) {
			// Bail if not array.
			if ( ! is_array( $section ) ) {
				return false;
			}

			// Assign the section to sections array.
			$this->sections_array[] = $section;

			return $this;
		}

		/**
		 * Setting the fields.
		 *
		 * @param array $fields - array of fields for settings.
		 * @return $this
		 */
		public function set_fields( $fields ) {
			// Bail if not array.
			if ( ! is_array( $fields ) ) {
				return false;
			}

			// Assign the fields.
			$this->fields_array = $fields;

			return $this;
		}

		/**
		 * Add a single field.
		 *
		 * @param array $section - sections.
		 * @param array $field_array  - fields.
		 * @return $this
		 *
		 * @since 1.0.0
		 */
		public function add_field( $section, $field_array ) {
			// Set the defaults.
			$defaults = array(
				'id'   => '',
				'name' => '',
				'desc' => '',
				'type' => 'text',
			);

			// Combine the defaults with user's arguements.
			$arg = wp_parse_args( $field_array, $defaults );

			// Each field is an array named against its section.
			$this->fields_array[ $section ][] = $arg;

			return $this;
		}

		/**
		 * Initialize API.
		 *
		 * Initializes and registers the settings sections and fields.
		 * Usually this should be called at `admin_init` hook.
		 *
		 * @since  1.0.0
		 */
		public function admin_init() {
			/**
			 * Register the sections.
			 *
			 * Sections array is like this:
			 *
			 * $sections_array = array (
			 *   $section_array,
			 *   $section_array,
			 *   $section_array,
			 * );
			 *
			 * Section array is like this:
			 *
			 * $section_array = array (
			 *   'id'    => 'section_id',
			 *   'title' => 'Section Title'
			 * );
			 *
			 * @since 1.0.0
			 */
			foreach ( $this->sections_array as $section ) {
				if ( false == get_option( $section['id'] ) ) {
					// Add a new field as section ID.
					add_option( $section['id'] );
				}

				// Deals with sections description.
				if ( isset( $section['desc'] ) && ! empty( $section['desc'] ) ) {
					// Build HTML.
					$section['desc'] = '<div class="inside">' . $section['desc'] . '</div>';

					// Create the callback for description.
					$callback = function() use ( $section ) {
						echo $section['desc'];
					};

				} elseif ( isset( $section['callback'] ) ) {
					$callback = $section['callback'];
				} else {
					$callback = null;
				}

				/**
				 * Add a new section to a settings page.
				 *
				 * @param string $id
				 * @param string $title
				 * @param callable $callback
				 * @param string $page | Page is same as section ID.
				 * @since 1.0.0
				 */
				add_settings_section( $section['id'], $section['title'], $callback, $section['id'] );
			} // foreach ended.

			/**
			 * Register settings fields.
			 *
			 * Fields array is like this:
			 *
			 * $fields_array = array (
			 *   $section => $field_array,
			 *   $section => $field_array,
			 *   $section => $field_array,
			 * );
			 *
			 *
			 * Field array is like this:
			 *
			 * $field_array = array (
			 *   'id'   => 'id',
			 *   'name' => 'Name',
			 *   'type' => 'text',
			 * );
			 *
			 * @since 1.0.0
			 */
			foreach ( $this->fields_array as $section => $field_array ) {
				foreach ( $field_array as $field ) {
					// ID.
					$id = isset( $field['id'] ) ? $field['id'] : false;

					// Type.
					$type = isset( $field['type'] ) ? $field['type'] : 'text';

					// Name.
					$name = isset( $field['name'] ) ? $field['name'] : 'No Name Added';

					// Disabled (input).
					$disabled = isset( $field['disabled'] ) ? $field['disabled'] : false;

					// Label for.
					$label_for = "{$section}[{$field['id']}]";

					// Description.
					$description = isset( $field['desc'] ) ? $field['desc'] : '';

					// Size.
					$size = isset( $field['size'] ) ? $field['size'] : null;

					// Options.
					$options = isset( $field['options'] ) ? $field['options'] : '';

					// Standard default value.
					$default = isset( $field['default'] ) ? $field['default'] : '';

					// Standard default placeholder.
					$placeholder = isset( $field['placeholder'] ) ? $field['placeholder'] : '';

					// Sanitize Callback.
					$sanitize_callback = isset( $field['sanitize_callback'] ) ? $field['sanitize_callback'] : '';

					$args = array(
						'id'                => $id,
						'type'              => $type,
						'name'              => $name,
						'disabled'          => $disabled,
						'label_for'         => $label_for,
						'desc'              => $description,
						'section'           => $section,
						'size'              => $size,
						'options'           => $options,
						'std'               => $default,
						'placeholder'       => $placeholder,
						'sanitize_callback' => $sanitize_callback,
					);

					/**
					 * Add a new field to a section of a settings page.
					 *
					 * @param string   $id
					 * @param string   $title
					 * @param callable $callback
					 * @param string   $page
					 * @param string   $section = 'default'
					 * @param array    $args = array()
					 * @since 1.0.0
					 */

					// @param string 	$id
					$field_id = $section . '[' . $field['id'] . ']';

					add_settings_field(
						$field_id,
						$name,
						array( $this, 'callback_' . $type ),
						$section,
						$section,
						$args
					);
				} // foreach ended.
			} // foreach ended.

			// Creates our settings in the fields table.
			foreach ( $this->sections_array as $section ) {
				/**
				 * Registers a setting and its sanitization callback.
				 *
				 * @param string $field_group   | A settings group name.
				 * @param string $field_name    | The name of an option to sanitize and save.
				 * @param callable  $sanitize_callback = ''
				 * @since 1.0.0
				 */
				register_setting( $section['id'], $section['id'], array( $this, 'sanitize_fields' ) );
			} // foreach ended.

		} // admin_init() ended.


		/**
		 * Sanitize callback for Settings API fields.
		 *
		 * @since 1.0.0
		 */
		public function sanitize_fields( $fields ) {
			foreach ( $fields as $field_slug => $field_value ) {
				$sanitize_callback = $this->get_sanitize_callback( $field_slug );

				// If callback is set, call it.
				if ( $sanitize_callback ) {
					$fields[ $field_slug ] = call_user_func( $sanitize_callback, $field_value );
					continue;
				}
			}

			return $fields;
		}


		/**
		 * Get sanitization callback for given option slug
		 *
		 * @param string $slug option slug.
		 * @return mixed string | bool false
		 * @since  1.0.0
		 */
		public function get_sanitize_callback( $slug = '' ) {
			if ( empty( $slug ) ) {
				return false;
			}

			// Iterate over registered fields and see if we can find proper callback.
			foreach ( $this->fields_array as $section => $field_array ) {
				foreach ( $field_array as $field ) {
					if ( $field['name'] != $slug ) {
						continue;
					}

					// Return the callback name.
					return isset( $field['sanitize_callback'] ) && is_callable( $field['sanitize_callback'] ) ? $field['sanitize_callback'] : false;
				}
			}

			return false;
		}


		/**
		 * Get field description for display
		 *
		 * @param array $args settings field args.
		 */
		public function get_field_description( $args ) {
			if ( ! empty( $args['desc'] ) ) {
				$desc = sprintf( '<p class="description">%s</p>', $args['desc'] );
			} else {
				$desc = '';
			}

			return $desc;
		}


		/**
		 * Displays a title field for a settings field
		 *
		 * @param array $args settings field args
		 */
		public function callback_title( $args ) {
			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			if ( '' !== $args['name'] ) {
				$name = $args['name'];
			} else {
			};
			$type = isset( $args['type'] ) ? $args['type'] : 'title';

			$html = '';
			echo $html;
		}


		/**
		 * Displays a text field for a settings field
		 *
		 * @param array $args settings field args
		 */
		public function callback_text( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'], $args['placeholder'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
			$type  = isset( $args['type'] ) ? $args['type'] : 'text';

			$html  = sprintf( '<input type="%1$s" class="%2$s-text" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s"placeholder="%6$s"/>', $type, $size, $args['section'], $args['id'], $value, $args['placeholder'] );
			$html .= $this->get_field_description( $args );

			echo $html;
		}


		/**
		 * Displays a url field for a settings field
		 *
		 * @param array $args settings field args
		 */
		public function callback_url( $args ) {
			$this->callback_text( $args );
		}

		/**
		 * Displays a number field for a settings field
		 *
		 * @param array $args settings field args.
		 */
		public function callback_number( $args ) {
			$this->callback_text( $args );
		}

		/**
		 * Displays a checkbox for a settings field
		 *
		 * @param array $args settings field args.
		 */
		public function callback_checkbox( $args ) {

			$value    = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			$disabled = ( isset( $args['disabled'] ) && 'yes' === $args['disabled'] ) ? ' disabled' : '';

			$html  = '<fieldset>';
			$html .= sprintf( '<label for="wposa-%1$s[%2$s]">', $args['section'], $args['id'] );
			$html .= sprintf( '<input type="hidden" name="%1$s[%2$s]" value="off" />', $args['section'], $args['id'] );
			$html .= sprintf( '<input type="checkbox" class="checkbox" id="wposa-%1$s[%2$s]" name="%1$s[%2$s]" value="on" %3$s  %4$s/>', $args['section'], $args['id'], checked( $value, 'on', false ), $disabled );
			$html .= sprintf( '%1$s</label>', $args['desc'] );
			$html .= '</fieldset>';

			echo $html;
		}

		/**
		 * Displays a multicheckbox a settings field
		 *
		 * @param array $args settings field args.
		 */
		public function callback_multicheck( $args ) {

			$value = $this->get_option( $args['id'], $args['section'], $args['std'] );

			$html = '<fieldset>';
			foreach ( $args['options'] as $key => $label ) {
				$checked = isset( $value[ $key ] ) ? $value[ $key ] : '0';
				$html   .= sprintf( '<label for="wposa-%1$s[%2$s][%3$s]">', $args['section'], $args['id'], $key );
				$html   .= sprintf( '<input type="checkbox" class="checkbox" id="wposa-%1$s[%2$s][%3$s]" name="%1$s[%2$s][%3$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, checked( $checked, $key, false ) );
				$html   .= sprintf( '%1$s</label><br>', $label );
			}
			$html .= $this->get_field_description( $args );
			$html .= '</fieldset>';

			echo $html;
		}

		/**
		 * Displays a multicheckbox a settings field
		 *
		 * @param array $args settings field args
		 */
		public function callback_radio( $args ) {

			$value = $this->get_option( $args['id'], $args['section'], $args['std'] );

			$html = '<fieldset>';
			foreach ( $args['options'] as $key => $label ) {
				$html .= sprintf( '<label for="wposa-%1$s[%2$s][%3$s]">', $args['section'], $args['id'], $key );
				$html .= sprintf( '<input type="radio" class="radio" id="wposa-%1$s[%2$s][%3$s]" name="%1$s[%2$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, checked( $value, $key, false ) );
				$html .= sprintf( '%1$s</label><br>', $label );
			}
			$html .= $this->get_field_description( $args );
			$html .= '</fieldset>';

			echo $html;
		}

		/**
		 * Displays a selectbox for a settings field
		 *
		 * @param array $args settings field args.
		 */
		public function callback_select( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

			$html = sprintf( '<select class="%1$s" name="%2$s[%3$s]" id="%2$s[%3$s]">', $size, $args['section'], $args['id'] );
			foreach ( $args['options'] as $key => $label ) {
				$html .= sprintf( '<option value="%s"%s>%s</option>', $key, selected( $value, $key, false ), $label );
			}
			$html .= sprintf( '</select>' );
			$html .= $this->get_field_description( $args );

			echo $html;
		}

		/**
		 * Displays a textarea for a settings field
		 *
		 * @param array $args settings field args.
		 */
		public function callback_textarea( $args ) {

			$value = esc_textarea( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

			$html  = sprintf( '<textarea rows="5" cols="55" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]">%4$s</textarea>', $size, $args['section'], $args['id'], $value );
			$html .= $this->get_field_description( $args );

			echo $html;
		}

		/**
		 * Displays a textarea for a settings field
		 *
		 * @param array $args settings field args.
		 */
		public function callback_html( $args ) {
			echo $this->get_field_description( $args );
		}

		/**
		 * Displays a rich text textarea for a settings field
		 *
		 * @param array $args settings field args.
		 */
		public function callback_wysiwyg( $args ) {

			$value = $this->get_option( $args['id'], $args['section'], $args['std'] );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : '500px';

			echo '<div style="max-width: ' . $size . ';">';

			$editor_settings = array(
				'teeny'         => true,
				'textarea_name' => $args['section'] . '[' . $args['id'] . ']',
				'textarea_rows' => 10,
			);
			if ( isset( $args['options'] ) && is_array( $args['options'] ) ) {
				$editor_settings = array_merge( $editor_settings, $args['options'] );
			}

			wp_editor( $value, $args['section'] . '-' . $args['id'], $editor_settings );

			echo '</div>';

			echo $this->get_field_description( $args );
		}

		/**
		 * Displays a file upload field for a settings field
		 *
		 * @param array $args settings field args.
		 */
		public function callback_file( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
			$id    = $args['section'] . '[' . $args['id'] . ']';
			$label = isset( $args['options']['button_label'] ) ? $args['options']['button_label'] : __( 'Choose File', 'image-haddad-wp' );

			$html  = sprintf( '<input type="text" class="%1$s-text wpsa-url" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value );
			$html .= '<input type="button" class="button wpsa-browse file" value="' . $label . '" />';
			$html .= $this->get_field_description( $args );

			echo $html;
		}

		/**
		 * Displays an image upload field with a preview
		 *
		 * @param array $args settings field args.
		 */
		public function callback_image( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
			$id    = $args['section'] . '[' . $args['id'] . ']';
			$label = isset( $args['options']['button_label'] ) ? $args['options']['button_label'] : __( 'Choose Image', 'image-haddad-wp' );

			$html  = sprintf( '<input type="text" class="%1$s-text wpsa-url" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value );
			$html .= '<input type="button" class="button wpsa-browse image" value="' . $label . '" />';
			$html .= $this->get_field_description( $args );
			$html .= '<p class="wpsa-image-preview"><img src=""/></p>';

			echo $html;
		}

		/**
		 * Displays a password field for a settings field
		 *
		 * @param array $args settings field args
		 */
		public function callback_password( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

			$html  = sprintf( '<input type="password" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value );
			$html .= $this->get_field_description( $args );

			echo $html;
		}

		/**
		 * Displays a color picker field for a settings field
		 *
		 * @param array $args settings field args
		 */
		public function callback_color( $args ) {

			$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'], $args['placeholder'] ) );
			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

			$html  = sprintf( '<input type="text" class="%1$s-text color-picker" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s" data-default-color="%5$s" placeholder="%6$s" />', $size, $args['section'], $args['id'], $value, $args['std'], $args['placeholder'] );
			$html .= $this->get_field_description( $args );

			echo $html;
		}


		/**
		 * Displays a separator field for a settings field
		 *
		 * @param array $args settings field args
		 */
		public function callback_separator( $args ) {
			$type = isset( $args['type'] ) ? $args['type'] : 'separator';

			$html  = '';
			$html .= '<div class="wpsa-settings-separator"></div>';
			echo $html;
		}


		/**
		 * Get the value of a settings field
		 *
		 * @param string $option  settings field name.
		 * @param string $section the section name this field belongs to.
		 * @param string $default default text if it's not found.
		 * @return string
		 */
		public function get_option( $option, $section, $default = '' ) {

			$options = get_option( $section );

			if ( isset( $options[ $option ] ) ) {
				return $options[ $option ];
			}

			return $default;
		}

		/**
		 * Add submenu page to the Settings main menu.
		 *
		 * @return void
		 */
		public function admin_menu() {
			/**
			 * Add menu page WP function.
			 *
			 * @param string   $page_title Title of admin page
			 * @param string   $menu_title shown in WP admin menu
			 * @param string   $capability - user access control.
			 * @param string   $menu_slug link to admin page.
			 * @param callable $callable Output the settings.
			 * @param string   $icon - icon in admin menu.
			 * @param string   $postition - position in admin menu.
			 * @author Ahmad Awais
			 * @since  [version]
			 */
			add_menu_page(
				'Image Haddad WP',
				'Image Haddad WP',
				'manage_options',
				'ihwp_settings',
				array( $this, 'plugin_page' ),
				'dashicons-products',
				3
			);
		}

		/**
		 * Output the plugin settings page.
		 *
		 * @return void
		 */
		public function plugin_page() {
			echo '<div class="wrap">';
			echo '<h1>Image Haddad WP settings</h1>';
			$this->show_navigation();
			$this->show_forms();
			echo '</div>';
		}

		/**
		 * Show navigations as tab
		 *
		 * Shows all the settings section labels as tab
		 */
		public function show_navigation() {
			$html = '<h2 class="nav-tab-wrapper">';

			foreach ( $this->sections_array as $tab ) {
				$html .= sprintf( '<a href="#%1$s" class="nav-tab" id="%1$s-tab">%2$s</a>', $tab['id'], $tab['title'] );
			}

			$html .= '</h2>';

			echo wp_kses_post( $html );
		}

		/**
		 * Show the section settings forms
		 *
		 * This function displays every sections in a different form
		 */
		public function show_forms() {
			?>
			<div class="metabox-holder">
				<?php foreach ( $this->sections_array as $form ) { ?>
					<!-- style="display: none;" -->
					<div id="<?php echo $form['id']; ?>" class="group" >
						<form method="post" action="options.php">
							<?php
							do_action( 'wsa_form_top_' . $form['id'], $form );
							settings_fields( $form['id'] );
							do_settings_sections( $form['id'] );
							do_action( 'wsa_form_bottom_' . $form['id'], $form );
							?>
							<?php if ( 'yes' !== $form['no_submit'] ) { ?>

							<div class="submit-wrapper">
								<?php submit_button( null, 'primary wposa-submit', 'submit_' . $form['id'], '', array( 'data-id' => $form['id'] ) ); ?>
								<p class="<?php echo esc_attr( 'notice_' . $form['id'] ); ?> notification"></p>
							</div>

							<?php } ?>

						</form>
					</div>
				<?php } ?>
				<?php
				// echo '<pre class="mm-debug">';
				// print_r(wp_load_alloptions());
				// echo '</pre>';
				?>
			</div>
			<?php
			$this->script();
		}

		/**
		 * Tabbable JavaScript codes & Initiate Color Picker
		 *
		 * This code uses localstorage for displaying active tabs
		 * use localStorage.clear() in case of localStorage errors.
		 */
		public function script() {
			?>
			<script>
			(function( $ ) {
				'use strict';
				$( document ).ready( function() {

					// localStorage.clear() <-- in case of errors, uncomment.
					//Initiate Color Picker.
					$('.color-picker').wpColorPicker();

					// Switches option sections
					$( '.group' ).hide();
					var activetab = '';
					if ( 'undefined' != typeof localStorage ) {
						activetab = localStorage.getItem( 'activetab' );
					}
					if ( '' != activetab && $( activetab ).length ) {
						$( activetab ).fadeIn();
					} else {
						$( '.group:first' ).fadeIn();
					}
					$( '.group .collapsed' ).each( function() {
						$( this )
							.find( 'input:checked' )
							.parent()
							.parent()
							.parent()
							.nextAll()
							.each( function() {
								if ( $( this ).hasClass( 'last' ) ) {
									$( this ).removeClass( 'hidden' );
									return false;
								}
								$( this )
									.filter( '.hidden' )
									.removeClass( 'hidden' );
							});
					});

					if ( '' != activetab && $( activetab + '-tab' ).length ) {
						$( activetab + '-tab' ).addClass( 'nav-tab-active' );
					} else {
						$( '.nav-tab-wrapper a:first' ).addClass( 'nav-tab-active' );
					}
					$( document ).on('click','.nav-tab-wrapper a', function( evt ) {
						$( '.nav-tab-wrapper a' ).removeClass( 'nav-tab-active' );
						$( this )
							.addClass( 'nav-tab-active' )
							.blur();

						var clicked_group = $( this ).attr( 'href' );
						if ( 'undefined' != typeof localStorage ) {
							localStorage.setItem( 'activetab', $( this ).attr( 'href' ) );
						}
						$( '.group' ).hide();
						$( clicked_group ).fadeIn();
						evt.preventDefault();
					});

					$( '.wpsa-browse' ).on( 'click', function( event ) {
						event.preventDefault();

						var browseButton = $( this );

						// Create the media frame.
						var file_frame = ( wp.media.frames.file_frame = wp.media({
							title: browseButton.data( 'uploader_title' ),
							button: {
								text: browseButton.data( 'uploader_button_text' )
							},
							multiple: false
						}) );

						file_frame.on( 'select', function() {
							var attachment = file_frame
								.state()
								.get( 'selection' )
								.first()
								.toJSON();

							browseButton
								.prev( '.wpsa-url' )
								.val( browseButton.hasClass('file') ? attachment.title : attachment.url )
								.change();
								//console.log(attachment);
						});

						// Finally, open the modal
						file_frame.open();
					});

					$( 'input.wpsa-url' )
						.on( 'change keyup paste input', function() {
							var self = $( this );
							self
								.next()
								.parent()
								.children( '.wpsa-image-preview' )
								.children( 'img' )
								.attr( 'src', self.val() );
						})
						.change();

					// Update textfield(s) before saving changes.
					$('.wrap').on('mouseenter', '.wposa-submit, a.nav-tab',function(e) {
						get_wyswig();
					});

					// Form saving.
					window.wposa_settings_changed = false;
					$('form').on('click', '.wposa-submit',function(e) {
						e.preventDefault();
						var submitButton = $(this),
							formId   = submitButton.data('id'),
							notice   = submitButton.next('.notice_' + formId),
							formData = submitButton.closest('form').serializeArray();
						// Notify that saving process started.
						notice.css('display', 'block').text( "<?php echo esc_js( __( 'Saving data.', 'image-haddad-wp' ) ); ?>" );

						$.ajax({
							type:'POST',
							url:'options.php',
							data: formData,
							success:function(data) {
								notice.text( "<?php echo esc_js( __( 'Settings saved.', 'image-haddad-wp' ) ); ?>").delay(2000).fadeOut();
								window.wposa_settings_changed = false;
							},
							error: function() {
								alert( "<?php echo esc_js( __( 'Error saving changes. Please contact your administrator.', 'image-haddad-wp' ) ); ?>");
							}
						});
					});
					// // Form validating.
					$('form').on('keyup change paste', 'input, select, textarea', function(el){
						// Skip '.del_entry'
						if( el.target.className === 'del_entry' ) return;

						console.log( "<?php echo esc_js( __( 'Form changed!', 'image-haddad-wp' ) ); ?>" );
						window.wposa_settings_changed = true;
					});
					$('a.nav-tab').click(function() {
						if ( ! window.wposa_settings_changed ) return;
						var r = confirm( "<?php echo esc_js( __( 'Settings have been changed and will be automatically saved.', 'image-haddad-wp' ) ); ?>" );
						if ( r == true ) {
							window.wposa_settings_changed = false; // Reset state.
							$(".wposa-submit").trigger("click"); // Trigger saving changes.
						} else {
							window.wposa_settings_changed = true;
							console.log( "<?php echo esc_js( __( 'Settings still changed and not saved', 'image-haddad-wp' ) ); ?>" );
						}
					});

					function get_wyswig() {
						var wpEditorContainers = $('.wp-editor-container');
						wpEditorContainers.each(
							function() {
								var container = $(this),
									textarea  = container.find('.wp-editor-area'),
									iframe    = container.find('iframe').contents(),
									content   = iframe.find('.mce-content-body').html();
								if( content === '<p><br data-mce-bogus=\"1\"></p>' ) {
									content = '';
								}
								textarea.html( content );
							}
						);
					}

				});

			})(jQuery);
			</script>

			<style type="text/css">

				.wrap .notice, .wrap .error { display: none;}

				/** WordPress 3.8 Fix **/
				.form-table th {
					padding: 20px 10px;
				}
				#wpbody-content .metabox-holder {
					padding-top: 5px;
				}
				.wpsa-image-preview img {
					height: auto;
					width: auto;
					max-height: 50px;
				}

				.wpsa-settings-separator {
					background: #ccc;
					border: 0;
					color: #ccc;
					height: 1px;
					position: absolute;
					left: 0;
					width: 99%;
				}
				.group .form-table input.color-picker {
					max-width: 100px;
				}
				.submit-wrapper {
					padding: 10px;
					display: flex;
					align-items: center;
				}
				.notification {
					display: none;
					background-color: rgb(152, 247, 138);
					padding: 5px 10px;
					margin: 0 10px;
					border-radius: 2px;
				}
			</style>
			<?php
		}
	} // WP_OSA ended.

endif;
