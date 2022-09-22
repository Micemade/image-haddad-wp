<?php
/**
 * Cross-sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cross-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Cart related products - custom, taxonomy related cross-sells.
$haddad_cross_sells = apply_filters( 'haddad_cross_sells', '' );

// Set heading and wrapper for either cross-sells or cart related.
$wrapper_start = '<div class="cross-sells"><h2>' . apply_filters( 'woocommerce_product_cross_sells_products_heading', __( 'You may be interested in&hellip;', 'image-haddad-wp' ) ) . '</h2>';
$wrapper_end   = '</div>';

// Revised cross-sells - force skipping the out of stock products.
$cross_sells_revised = array();
if ( $cross_sells ) {
	foreach ( $cross_sells as $cross_sell ) {
		if ( 'outofstock' === $cross_sell->get_stock_status() ) {
			continue;
		}
		$cross_sells_revised[] = $cross_sell;
	}
}

/*
 * Check for if cross-sells set in cart products, else,
 * use cross-sells from this plugin (related based on taxonomies).
 */
$cross_sells = ! empty( $cross_sells_revised ) ? $cross_sells_revised : $haddad_cross_sells;

if ( $cross_sells ) :
	?>

	<?php echo wp_kses_post( $wrapper_start ); ?>

		<?php woocommerce_product_loop_start(); ?>

			<?php foreach ( $cross_sells as $cross_sell ) : ?>

				<?php
				$post_object = get_post( $cross_sell->get_id() );
				setup_postdata( $GLOBALS['post'] =& $post_object );
				wc_get_template_part( 'content', 'product' );
				?>

			<?php endforeach; ?>

		<?php woocommerce_product_loop_end(); ?>

	<?php echo wp_kses_post( $wrapper_end ); ?>

	<?php

endif;

wp_reset_postdata();
