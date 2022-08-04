<?php
/**
 * Inlcudes code related to custom taxonomies.
 *
 * @package flatsome
 */

// Exist if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Function to add custom taxonomies.
 *
 * @return void
 */
function flatsome_child_product_effect_taxonomy() {

	/**
	 * Taxonomy: Produkt effekts.
	 */

	$labels = array(
		'name'          => __( 'Produkt effekts', 'woocommerce' ),
		'singular_name' => __( 'Produkt effekt', 'woocommerce' ),
		'add_new_item'  => __( 'Add new Produkt effekt', 'woocommerce' ),
	);

	$args = array(
		'labels'             => $labels,
		'hierarchical'       => true,
		'public'             => true,
		'show_in_quick_edit' => true,
		'show_in_rest'       => true,
	);

	$post_types = array( 'product' );
	register_taxonomy( 'product_effect', $post_types, $args );

}
add_action( 'init', 'flatsome_child_product_effect_taxonomy' );

/**
 * Function to add custom taxonomies.
 *
 * @return void
 */
function flatsome_child_product_sustainability_taxonomy() {

	$labels = array(
		'name'          => __( 'Bæredygtighed', 'woocommerce' ),
		'singular_name' => __( 'Bæredygtighed', 'woocommerce' ),
		'add_new_item'  => __( 'Add new Bæredygtighed', 'woocommerce' ),
	);

	$args = array(
		'labels'             => $labels,
		'hierarchical'       => true,
		'public'             => true,
		'show_in_quick_edit' => true,
		'show_in_rest'       => true,
	);

	$post_types = array( 'product' );
	register_taxonomy( 'product_sustainability', $post_types, $args );

}
add_action( 'init', 'flatsome_child_product_sustainability_taxonomy' );
