<?php
/**
 * The template is for product category's archive page overriden from flatsome which is override from woocommerce.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cat_style = get_theme_mod( 'cat_style', 'badge' );
$color     = '';
$text_pos  = '';

if ( $cat_style == 'overlay' || $cat_style == 'shade' ) {
	$color = 'dark';
}
if ( $cat_style == 'overlay' ) {
	$text_pos = 'box-text-middle text-shadow-5';
}
if ( $cat_style == 'badge' ) {
	$text_pos .= ' hover-dark';
}


$query = new WP_Query(
	array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => 7,
		'tax_query'      => array(
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => array( $category->term_id ),
			),
		),
	)
);
?>
	<h5 class="uppercase header-title">
		<?php echo esc_html( $category->name ); ?>
	</h5>
	<?php

	while ( $query->have_posts() ) :
		$query->the_post();
		wc_get_template_part( 'content', 'product' );
	endwhile;

	wp_reset_postdata();
	?>
<?php
