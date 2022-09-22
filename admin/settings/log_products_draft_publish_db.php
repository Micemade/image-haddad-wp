<?php
/**
 * Custom logovi - log promjena tabele (wp_posts) u bazi.
 *
 * @package image-haddad-wp/settings
 */

class Image_Haddad_DB_Log extends Image_Haddad_Json_Log {

	/**
	 * Class constructor
	 */
	public function __construct() {

		$this->pager_queryvar = 'datapage';

		if ( $this->is_ihwp_page() ) {
			$this->get_data();
		}

		// AJAX functions.
		add_action( 'wp_ajax_delete_dblog_entry', array( $this, 'delete_dblog_entry' ) );
		add_action( 'wp_ajax_nopriv_delete_dblog_entry', array( $this, 'delete_dblog_entry' ) );

	}

	/**
	 * Get data from database.
	 *
	 * @return void
	 */
	public function get_data() {
		// Do a database query and save it to the cache if the there is no cache data with this key.
		$cache_key      = 'haddad_db_logged_product_edits';
		$this->data_arr = wp_cache_get( $cache_key );
		if ( false === $this->data_arr ) {
			global $wpdb;
			$this->data_arr = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM hd_mm_product_updates WHERE post_type = %s", 'product' ) );

			wp_cache_set( $cache_key, $this->data_arr );
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
		$log_html .= '<h3 style="width:100%">Log promjene baze podataka, kod ažuriranja proizvoda (skica > objavljeno).</h3><br>';
		$log_html .= $this->container_end();
		$log_html .= $this->container_start();
		$log_html .= '<h4 style="width:100%">Log u tabeli baze se upisuje pomoću MySql triggera koji monitorira upise/promjene posts tabele za product post_type-ove. <br>Ako onemogućite log u bazu (tab "Druge postavke"), MySQL trigger <code>micemade_product_updates_log</code> će biti izbrisan, ali tabela <code>{$db_prefix}mm_product_updates</code> će ostati u bazi.</h4>';
		$log_html .= $this->container_end();

		// Disabled logging notice.
		$log_html .= $this->disabled_notice( 'enable_db_log', __( 'database', 'image-haddad-wp' ) );

		// Notice for admin ability to delete items.
		$log_html .= $this->is_user_admin_notice();

		// Table head HTML.
		$log_html .= $this->container_start( 'container light' );
		$log_html .= $this->container_div( 'equal-width', 'p', 'Vrijeme upisa' );
		$log_html .= $this->container_div( 'equal-width', 'p', 'Ime proizvoda' );
		$log_html .= $this->container_div( 'equal-width', 'p', 'ID' );
		$log_html .= $this->container_div( 'equal-width', 'p', 'Originalni autor' );
		$log_html .= $this->container_end();
		// end table head HTML.

		if ( ! empty( $this->data_arr ) ) {

			// Show newer logs first.
			$data_new_to_old = array_reverse( $this->data_arr );

			// Data to create a pagination.
			$paged_data = $this->pager( $data_new_to_old, $this->pager_queryvar );
			$final_data = $paged_data[0];

			$i = 1;
			foreach ( $final_data as $edit ) {
				// Item enum.
				$item_enum = $i + (int) $paged_data[2];

				// Identifier to delete item.
				$db_entry_id = $edit->id;

				// Delete item only available to admins.
				$del_item = $this->is_user_admin() ? ' <input type="button" class="del-entry" data-dbid="' . esc_attr( $db_entry_id ) . '" value="x" title="Želite li stvarno pobrisati ovu stavku?" />' : '';

				// Time set in MySql - remote server timezone is GMT, so convert it to local.
				if ( defined( 'MICEMADE_DEV_ENV' ) && MICEMADE_DEV_ENV ) {
					$local_time = mysql2date( 'd.m.Y. H:i:s', $edit->timestamp );
				} else {
					$local_time = get_date_from_gmt( $edit->timestamp, 'd.m.Y. H:i:s' );
				}

				// Start HTML.
				$log_html .= $this->container_start();
				$log_html .= $this->container_div( 'equal-width', 'p', '<span class="enum">' . esc_html( (string) $item_enum ) . '</span>' . $local_time );
				$log_html .= $this->container_div( 'equal-width', 'p', esc_html( $edit->post_title ) );
				$log_html .= $this->container_div( 'equal-width', 'p', esc_html( $edit->post_id ) );
				$log_html .= $this->container_div( 'equal-width', 'p', esc_html( $edit->author ) . $del_item );
				$log_html .= $this->container_end();
				// end HTML.
				$i++;
			}

			// Pagination html ( page links from $paged_data loop).
			$log_html .= $this->pager_html( $paged_data[1], $this->pager_queryvar );

		} else {
			$log_html .= $this->container_start();
			$log_html .= $this->container_div( '', 'p', 'Nema stavki u zapisima baze.', 'width: 100%' );
			$log_html .= $this->container_end();
		}// end if not empty $json_arr.

		return $log_html;
	}

	/**
	 * Delete log entry via ajax.
	 *
	 * @return void
	 */
	public function delete_dblog_entry() {

		check_ajax_referer( 'del_entry_nonce', 'nonce' );

		global $wpdb;

		$db_entry_id = ( isset( $_POST['dbid'] ) ) ? sanitize_text_field( wp_unslash( $_POST['dbid'] ) ) : '';

		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM hd_mm_product_updates
				WHERE id = %d",
				$db_entry_id,
			)
		);
		//return $db_entry_id;
	}

}

/**
 * Instantiate the class.
 *
 * @return Image_Haddad_DB_Log()->build_log()
 */
function image_haddad_db_log() {
	$db_log = new Image_Haddad_DB_Log();
	return $db_log->build_log();
}
