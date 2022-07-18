<?php
/**
 * Inlcudes code to filter subcategory pages.
 *
 * @package flatsome
 */

// Exist if accessed directly.
defined( 'ABSPATH' ) || exit;


/**
 * Parameters for flatsome-child-categories-filter-script.js file.
 *
 * @return array
 */
function flatsome_child_categories_filter_params() {

	$current_page_cat_id = get_queried_object()->term_id;

	$arr['ajaxUrl']                = admin_url( 'admin-ajax.php' );
	$arr['productCategoriesNonce'] = wp_create_nonce( 'flatsome_child_categories_filter_nonce' );
	$arr['noProductFoundMessage']  = __( 'No Products Found', 'flatsome-child' );
	$arr['queryArgs']              = array(
		'currentPageCatID' => $current_page_cat_id,
	);

	return $arr;
}
add_filter( 'flatsome_child_categories_filter_params', 'flatsome_child_categories_filter_params' );


/**
 * Handle Ajax request to filter products as per products sub categories.
 *
 * @return void
 */
function flatsome_child_categories_filter() {

	check_ajax_referer( 'flatsome_child_categories_filter_nonce', 'nonce' );

	$cat_id_array = array();

	$query_args = isset( $_POST['query_args'] ) ? json_decode( sanitize_text_field( wp_json_encode( $_POST['query_args'] ) ), true ) : array();

	// $parent_cat_id   = get_term( (int) $query_args['currentPageCatID'] )->parent;
	// $parent_cat_slug = get_term( $parent_cat_id )->slug;

	// $operator = 'brands' == $parent_cat_slug ?  'IN' : 'AND';
	$operator = 'AND';

	foreach ( $query_args as $key => $value ) {
		array_push( $cat_id_array, $value );
	}

	$custom_loop_args = new WP_Query(
		array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'orderby'        => 'name',
			'order'          => 'ASC',
			'posts_per_page' => 40,
			'tax_query'      => array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'term_id',
					'terms'    => $cat_id_array,
					'operator' => $operator,
				),
			),
		)
	);

	while ( $custom_loop_args->have_posts() ) :
		$custom_loop_args->the_post();
		wc_get_template_part( 'content', 'product' );
	endwhile;

	wp_reset_postdata();

}
add_action( 'wp_ajax_flatsome_child_categories_filter', 'flatsome_child_categories_filter' );
add_action( 'wp_ajax_nopriv_flatsome_child_categories_filter', 'flatsome_child_categories_filter' );
