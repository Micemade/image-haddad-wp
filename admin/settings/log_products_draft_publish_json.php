<?php
/**
 * Dokumentacija za plugin.
 *
 * @package image-haddad-wp/settings
 */

class Image_Haddad_Json_Log {

	/**
	 * Name for pager query variable.
	 *
	 * @var string
	 */
	private $pager_queryvar;

	/**
	 * JSON file with dir string.
	 *
	 * @var sting
	 */
	private $log_json_file;

	/**
	 * Array of data from JSON or DB.
	 *
	 * @var array
	 */
	private $data_arr = array();

	/**
	 * Class constructor
	 */
	public function __construct() {

		$this->pager_queryvar = 'jsonpage';

		$this->get_data();

		// AJAX functions.
		add_action( 'wp_ajax_delete_json_entry', array( $this, 'delete_json_entry' ) );
		add_action( 'wp_ajax_nopriv_delete_json_entry', array( $this, 'delete_json_entry' ) );

		// Scripts.
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
	}

	/**
	 * Check if we are on this plugin settings page.
	 *
	 * @return boolean
	 * Restricting methods calling on other admin pages.
	 */
	public function is_ihwp_page() {
		$is_ihwp_page = ( isset( $_GET['page'] ) && 'ihwp_settings' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) ? true : false;
		return $is_ihwp_page;
	}

	/**
	 * Check if current user is administrator.
	 *
	 * @return boolean
	 * Used for deleting log entries, allowing only administrators to delete entries.
	 */
	public function is_user_admin() {
		return current_user_can( 'administrator' );
	}

	/**
	 * Administrator notice for items deletion.
	 *
	 * @return boolean
	 */
	public function is_user_admin_notice() {
		$notice_html = '';
		if ( $this->is_user_admin() ) {
			$notice_html  = $this->container_start();
			$notice_html .= '<code>Samo administratori mogu brisati stavke. Koristiti samo za brisanje starijih zapisa.</code>';
			$notice_html .= $this->container_end();
		}
		return $notice_html;
	}

	/**
	 * Get data from JSON file.
	 *
	 * @return void
	 */
	public function get_data() {
		// Read JSON file from "uploads" dir.
		$upload_dir          = wp_upload_dir();
		$this->log_json_file = trailingslashit( $upload_dir['basedir'] ) . 'haddad_product_statuses/log.json';
		if ( file_exists( $this->log_json_file ) ) {
			// Contents of JSON file to array.
			$this->data_arr = json_decode( file_get_contents( $this->log_json_file ), true );
		}
	}

	/**
	 * Display HTML formatted data from json log file.
	 *
	 * @return $log_html
	 */
	public function build_log() {

		// Header HTML.
		$log_html  = $this->container_start();
		$log_html .= '<h3>Stranica sa zapisom promjena statusa proizvoda (skica > objavljeno).</h3>';
		$log_html .= $this->container_end();
		$log_html .= $this->container_start();
		$log_html .= '<h4 style="width:100%">Ova tablica prikazuje podatke spremljene u <code>/wp-content/uploads/haddad_product_statuses/log.json</code> datoteci.<br>JSON datoteka može brzo postati velika (ako se događa puno promjena proizvoda), zato treba redovito čistiti (brisati) podatke, ili onemogućiti zapisivanje i/ili prikaz logova u tabu "Druge postavke"</h4>';
		$log_html .= $this->container_end();

		// Disabled logging notice.
		$log_html .= $this->disabled_notice( 'enable_json_log', __( 'JSON file', 'image-haddad-wp' ) );

		// Notice for admin ability to delete items.
		$log_html .= $this->is_user_admin_notice();

		// Table head HTML.
		$log_html .= $this->container_start( 'container light' );
		$log_html .= $this->container_div( 'equal-width', 'p', 'Datum izmjene' );
		$log_html .= $this->container_div( 'equal-width', 'p', 'Prethodna izmjena' );
		$log_html .= $this->container_div( 'equal-width', 'p', 'Naslov artikla' );
		$log_html .= $this->container_div( 'equal-width', 'p', 'ID' );
		$log_html .= $this->container_div( 'equal-width', 'p', 'SKU' );
		$log_html .= $this->container_div( 'equal-width', 'p', 'Editor' );
		$log_html .= $this->container_div( 'equal-width', 'p', 'Prethodni editor' );
		$log_html .= $this->container_end();
		// end table head HTML.

		// If json array is not empty.
		if ( ! empty( $this->data_arr ) ) {

			// Show newer logs first.
			$data_new_to_old = array_reverse( $this->data_arr );

			// Data to create a pagination.
			$paged_data = $this->pager( $data_new_to_old, $this->pager_queryvar );

			// Log items in rows (columns: edit time, prev edit time, title, id, sku, current editor, last editor).
			$i = 1;
			foreach ( $paged_data[0] as $item ) {
				// Item enum.
				$item_enum = $i + (int) $paged_data[2];

				// Identify item to delete.
				$tstamp = $this->sanitize_tstamp( $item['curr_time'] );

				// Delete only available to admins.
				$del_item = $this->is_user_admin() ? ' <input type="button" class="del-json" data-tstamp="' . esc_attr( $tstamp ) . '" value="x" title="Želite li stvarno pobrisati ovu stavku?" />' : '';

				// Start HTML.
				$log_html .= $this->container_start();
				$log_html .= $this->container_div( 'equal-width', 'p', '<span class="enum">' . esc_html( (string) $item_enum ) . '</span><input type="checkbox" name="' . esc_attr( $tstamp ) . '" class="del_entry" data-item="delete">' . $item['curr_time'] );
				$log_html .= $this->container_div( 'equal-width', 'p', esc_html( $item['mod_time'] ) );
				$log_html .= $this->container_div( 'equal-width', 'p', esc_html( $item['title'] ) );
				$log_html .= $this->container_div( 'equal-width', 'p', esc_html( $item['id'] ) );
				$log_html .= $this->container_div( 'equal-width', 'p', esc_html( $item['sku'] ) );
				$log_html .= $this->container_div( 'equal-width', 'p', esc_html( $item['curr_edit'] . ' (' . $item['curr_role'] . ')' ) );
				$log_html .= $this->container_div( 'equal-width', 'p', esc_html( $item['last_edit'] . ' (' . $item['last_role'] . ')' ) . $del_item );
				$log_html .= $this->container_end();
				// end HTML.
				$i++;
			}

			// Container for button to delete multiple item, and/or select all checkboxes.
			$log_html .= $this->container_start();
			$log_html .= $this->container_div( '', 'p', '<input type="checkbox" name="select-all-checkboxes" class="del_entry" id="select-all-checkboxes"><span>Označi sve</span>', 'flex-grow: 0' );
			$log_html .= $this->container_div( '', 'p', '<input type="button" name="del_multiple" id="del_multiple" class="button button-primary" value="Brisanje više stavki">', 'calc( 100% - 140px);' );
			$log_html .= $this->container_end();

			// Pagination html ( page links from $paged_data loop).
			$log_html .= $this->pager_html( $paged_data[1], $this->pager_queryvar );

		} else {
			$log_html .= $this->container_start();
			$log_html .= $this->container_div( '', 'p', 'Nema stavki u zapisima JSON datoteke.', 'width: 100%' );
			$log_html .= $this->container_end();
		}// end if not empty $this->data_arr.

		return $log_html;
	}

	/**
	 * Container html tag start
	 *
	 * @param string $class - list of css classes.
	 * @param string $style - inline css style.
	 * @return html
	 */
	public function container_start( $class = 'container', $style = '' ) {
		$styletag = $style ? ' style="' . $style . '"' : '';
		return '<div class="' . $class . '"' . $styletag . '>';
	}

	/**
	 * Container html tag end
	 *
	 * @return html
	 */
	public function container_end() {
		return '</div>';
	}

	/**
	 * HTML "table" row.
	 *
	 * @param string $class - CSS classes.
	 * @param string $inner - inner tag.
	 * @param string $content - html content.
	 * @param string $style - style attribute(s).
	 * @return html
	 */
	public function container_div( $class = '', $inner = '', $content = '', $style = '' ) {
		$classtag = $class ? ' class="' . $class . '"' : '';
		$styletag = $style ? ' style="' . $style . '"' : '';
		$inner_st = $inner ? '<' . $inner . '>' : '';
		$innerend = $inner ? '</' . $inner . '>' : '';
		return '<div' . $classtag . $styletag . '>' . $inner_st . $content . $innerend . '</div>';
	}

	/**
	 * Basic data pager
	 *
	 * @param array   $data - json data in array.
	 * @param string  $pagequery - query variable name.
	 * @return array
	 */
	public function pager( $data, $pagequery = 'stranica' ) {
		// Get option for items per page limit.
		$options = get_option( 'ihwp_other' );

		// Pagination vars.
		$curr_page   = ! isset( $_GET[ $pagequery ] ) ? 1 : sanitize_text_field( wp_unslash( $_GET[ $pagequery ] ) );
		$limit       = isset( $options['limit_log_items'] ) ? $options['limit_log_items'] : 10; // items per page.
		$offset      = ( $curr_page - 1 ) * $limit; // offset.
		$total_items = count( $data ); // total items.
		$total_pages = ceil( $total_items / $limit );
		$final_data  = array_splice( $data, $offset, $limit );
		return array( $final_data, $total_pages, $offset );
	}

	/**
	 * Create pager HTML.
	 *
	 * @param array  $data - json data in array.
	 * @param string $pager_queryvar - query variable name.
	 * @return $pager_html
	 */
	public function pager_html( $data = array(), $pager_queryvar = 'stranica' ) {
		$pager_html = $this->container_start( 'container pagination', 'justify-content: center;' );
		for ( $x = 1; $x <= $data; $x++ ) :
			$current = ( isset( $_GET[ $pager_queryvar ] ) && $x === (int) $_GET[ $pager_queryvar ] ) ? ' current' : '';
			$query_args = array(
				'page'          => 'ihwp_settings',
				$pager_queryvar => $x,
			);
			$url         = add_query_arg( $query_args, admin_url( 'admin.php' ) );
			$pager_html .= $this->container_div( 'equal-width' . esc_attr( $current ), 'p', '<a href="' . esc_url( $url ) . '">' . esc_html( $x ) . '</a>' );
		endfor;
		$pager_html .= $this->container_end();
		return $pager_html;
	}

	/**
	 * Disabled log notice.
	 *
	 * @param string $option_name - option for log type.
	 * @param string $log_type - name for log type.
	 * @return $notice_html
	 */
	public function disabled_notice( $option_name = 'enable_json_log', $log_type = 'JSON file' ) {
		$options     = get_option( 'ihwp_other' );
		$log_enabled = ( isset( $options[ $option_name ] ) && 'on' === $options[ $option_name ] ) ? true : false;
		if ( ! $log_enabled ) {
			$notice_html  = $this->container_start();
			$notice_html .= $this->container_div( '', 'p', __( 'Logging product changes in', 'image-haddad-wp' ) . ' ' . esc_html( $log_type ) . ' ' . __( 'is currently not active. Product changes will not be logged until enabled again.', 'image-haddad-wp' ), 'width=100%; color: red; font-weight: bold' );
			$notice_html .= $this->container_end();
		} else {
			$notice_html = '';
		}
		return $notice_html;
	}

	/**
	 * Delete item from JSON data.
	 *
	 * @return void
	 */
	public function delete_json_entry() {

		// Don't do anything if the $_POST var isn't populated.
		if ( empty( $_POST ) ) {
			return false;
		}

		// Noonce check.
		check_ajax_referer( 'del_entry_nonce', 'nonce' );

		// Get POST ajax data - tstamp (unique item identifier) values in string.
		$tstamps = ( isset( $_POST['tstamp'] ) ) ? sanitize_text_field( wp_unslash( $_POST['tstamp'] ) ) : array();
		// Explode string to array.
		$tstamps = explode( ',', $tstamps );

		// Prepare array of items to delete.
		$items_to_delete = array();
		// Fetch data array from JSON file.
		$json_arr = $this->data_arr;
		foreach ( $json_arr as $key => $value ) {
			// Sanitized "curr_time" item variable.
			$curr_time = $this->sanitize_tstamp( $value['curr_time'] );
			// If timestamp value from $_POST is same as item curr_time value.
			foreach ( $tstamps as $key => $ts ) {
				if ( $ts === $curr_time ) {
					$items_to_delete[] = $key;
				}
			}
		}
		// Delete items by unseting them from fetched data array.
		foreach ( $items_to_delete as $i ) {
			unset( $json_arr[ $i ] );
		}
		// Rebase array.
		$json_arr = array_values( $json_arr );

		// Rewrite the json file cleared of deleted items.
		file_put_contents( $this->log_json_file, wp_json_encode( $json_arr ) );
	}

	/**
	 * Enqueue scripts.
	 *
	 * @return void
	 */
	public function scripts() {
		wp_enqueue_script(
			'log_js',
			IMAGE_HADDAD_WP_URL . 'admin/settings/log_js/index.js',
			array( 'jquery' ),
			true
		);
		wp_localize_script(
			'log_js',
			'logAjax',
			array(
				'url'   => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'del_entry_nonce' ),
			)
		);
	}

	/**
	 * Sanitize tstamp variable.
	 *
	 * @param string $string / tstamp variable.
	 * @return $string
	 */
	public function sanitize_tstamp( $string ) {
		return str_replace( str_split( '\.: ' ), '', $string );
	}

}

/**
 * Instantiate the class.
 *
 * @return Image_Haddad_Json_Log()->build_log()
 */
function image_haddad_json_log() {
	$json_log = new Image_Haddad_Json_Log();
	if ( $json_log->is_ihwp_page() ) {
		return $json_log->build_log();
	}
}
