<?php
/**
 * Plugin compatibility
 *
 * @package dkjensen/wc-cart-pdf
 */

/**
 * TM Extra Product Options
 *
 * @see https://codecanyon.net/item/woocommerce-extra-product-options/7908619
 * @return void
 */
function wc_cart_pdf_compatibility_tm_extra_product_options() {
	add_filter( 'wc_epo_no_edit_options', '__return_true' ); // Hide "Edit options" link on product title.
}
add_action( 'wc_cart_pdf_before_process', 'wc_cart_pdf_compatibility_tm_extra_product_options' );

/**
 * Gravity PDF
 *
 * @return void
 */
function wc_cart_pdf_compatibility_gravity_pdf() {
	// phpcs:ignore
	if ( class_exists( 'GFPDF_Major_Compatibility_Checks' ) && isset( $GLOBALS['gravitypdf'] ) && isset( $_GET['cart-pdf'] ) ) {
		remove_action( 'plugins_loaded', array( $GLOBALS['gravitypdf'], 'plugins_loaded' ) );
	}
}
add_action( 'plugins_loaded', 'wc_cart_pdf_compatibility_gravity_pdf', 0 );

/**
 * Visual Products Configurator
 *
 * @return void
 */
function wc_cart_pdf_compatibility_visual_products_configurator() {
	add_filter(
		'vpc_get_config_data',
		function( $thumbnail_code ) {
			$edit_i18n = __( 'Edit', 'wc-cart-pdf' );

			$thumbnail_code = preg_replace( '/<\s*a[^>]*>' . $edit_i18n . '<\s*\/\s*a>/', '', $thumbnail_code ); // Hide the "Edit" link.

			return $thumbnail_code;
		}
	);
}
add_action( 'wc_cart_pdf_before_process', 'wc_cart_pdf_compatibility_visual_products_configurator' );

/**
 * Try removing product thumbnails filters if not rendering properly
 *
 * @return void
 */
function child_wc_cart_pdf_remove_thumbnail_filters() {
	if ( defined( 'WC_CART_PDF_THUMBNAIL_COMPATIBILITY' ) && constant( 'WC_CART_PDF_THUMBNAIL_COMPATIBILITY' ) ) {
		remove_all_filters( 'wp_get_attachment_image_src' );
		remove_all_filters( 'wp_get_attachment_image' );
		remove_all_filters( 'woocommerce_cart_item_thumbnail' );
		remove_all_filters( 'woocommerce_product_get_image' );
	}
}
add_action( 'wc_cart_pdf_before_process', 'child_wc_cart_pdf_remove_thumbnail_filters' );
