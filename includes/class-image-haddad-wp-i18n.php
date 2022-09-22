<?php

/**
 * File to define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://micemade.com
 * @since      1.0.0
 *
 * @package    Image_Haddad_Wp
 * @subpackage Image_Haddad_Wp/includes
 */

/**
 * Class defining the internationalization functionality.
 *
 * @since      1.0.0
 * @package    Image_Haddad_Wp
 * @subpackage Image_Haddad_Wp/includes
 * @author     Micemade <alen@micemade.com>
 */
class Image_Haddad_Wp_I18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'image-haddad-wp',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
