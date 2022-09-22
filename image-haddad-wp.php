<?php
/**
 * Ovaj plugin je razvijen za potrebe stranica http://www.haddad.hr i gotovo sve
 * funkcionalnosti mogu se primjeniti samo na tim stranicama. Ukoliko se pojavi
 * potreba za transfer na drugu platformu (hosting, WP tema i sl.) kontaktirajte
 * alen@micemade.com.
 * Baza ovog plugina je napravljena sa https://wppb.me/ boilerplateom.
 *
 * @link              https://micemade.com
 * @since             1.0.0
 * @package           Image_Haddad_Wp
 *
 * @wordpress-plugin
 * Plugin Name:       Image Haddad WP
 * Plugin URI:        https://www.haddad.hr
 * Description:       WP plugin (dodatak) za https://www.haddad.hr stranice.
 * Version:           1.0.0
 * Author:            Micemade
 * Author URI:        mailto:alen@micemade.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       image-haddad-wp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 * https://semver.org
 */
define( 'IMAGE_HADDAD_WP_VERSION', '1.0.0' );

/**
 * Plugin dir constant.
 */
define( 'IMAGE_HADDAD_WP_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Plugin url constant.
 */
define( 'IMAGE_HADDAD_WP_URL', plugin_dir_url( __FILE__ ) );

/**
 * Plugin basename.
 */
define( 'IMAGE_HADDAD_BASENAME', plugin_basename( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-image-haddad-wp-activator.php'
 */
function activate_image_haddad_wp() {
	require_once IMAGE_HADDAD_WP_DIR . 'includes/class-image-haddad-wp-activator.php';
	Image_Haddad_Wp_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-image-haddad-wp-deactivator.php
 */
function deactivate_image_haddad_wp() {
	require_once IMAGE_HADDAD_WP_DIR . 'includes/class-image-haddad-wp-deactivator.php';
	Image_Haddad_Wp_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_image_haddad_wp' );
register_deactivation_hook( __FILE__, 'deactivate_image_haddad_wp' );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require IMAGE_HADDAD_WP_DIR . 'includes/class-image-haddad-wp.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_image_haddad_wp() {

	$plugin = new Image_Haddad_Wp();
	$plugin->run();

}
run_image_haddad_wp();
