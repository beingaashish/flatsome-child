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

$thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
$image        = wp_get_attachment_url( $thumbnail_id );

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

	?>
	<div class="col">
		<a href="<?php echo esc_attr( get_category_link( $category->term_id ) ); ?>">
			<figure>
				<img src="<?php echo esc_attr( $image ); ?>" alt="<?php echo esc_attr( $category->name ); ?>">
			</figure>
			<figcaption>

			<?php
			esc_html(
				printf(
					__( 'Se alle produkter til %s', 'flatsome-child' ),
					$category->name
				)
			);
			?>

			</figcaption>
		</a>
	</div>
	<?php

	wp_reset_postdata();
	?>
<?php
