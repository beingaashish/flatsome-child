<?php
/**
 * File for adding custom post types for the theme.
 *
 * @package flatsome
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register Personal Shopper Post type.
 *
 * @return void
 */
function flatsome_child_personal_shopper_post_type() {

	$args = array(
		'labels'             => array(
			'name'          => __( 'Personal Shopper', 'woocommerce' ),
			'singular_name' => __( 'Personal Shopper', 'woocommerce' ),
			'menu_name'     => __( 'Personal Shoppers', 'woocommerce' ),
			'all_items'     => __( 'All Personal Shoppers', 'woocommerce' ),
			'add_new'       => __( 'Add a new', 'woocommerce' ),
			'add_new_item'  => __( 'Add a new', 'woocommerce' ),
		),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'personal-shop' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_icon'          => 'dashicons-smiley',
		'menu_position'      => null,
		'supports'           => array( 'title', 'author' ),
	);

		register_post_type( 'personal_shop', $args );

}
add_action( 'init', 'flatsome_child_personal_shopper_post_type' );
