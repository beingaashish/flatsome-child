<?php
/**
 * Child theme's herlper-functions.php file.
 *
 * @package flatsome
 */

defined( 'ABSPATH' ) || exit;

// Remove hooks from the parent themes here, this is done so that we can override certain things.
add_action(
	'init',
	function() {
		remove_filter( 'woocommerce_loop_add_to_cart_link', 'flatsome_woocommerce_loop_add_to_cart_link', 5 );
	}
);

/**
 * Change button sprintf format depending on add_to_cart_icon type.
 *
 * @param string     $link    Add to cart link html.
 * @param WC_Product $product Product object.
 * @param array      $args    Filtered args, see woocommerce_template_loop_add_to_cart().
 *
 * @return string
 */
function flatsome_child_woocommerce_loop_add_to_cart_link( $link, $product, $args ) {

	if ( ! doing_action( 'flatsome_product_box_actions' ) && ! doing_action( 'flatsome_product_box_after' ) ) {
		return $link;
	}

	switch ( get_theme_mod( 'add_to_cart_icon', 'disabled' ) ) {
		case 'show':
			$insert = '<div class="cart-icon tooltip is-small" title="' . esc_html( $product->add_to_cart_text() ) . '"><strong>+</strong></div>';
			$link   = preg_replace( '/(<a.*?>).*?(<\/a>)/', '$1' . $insert . '$2', $link );
			break;
		case 'button':
			$link = '<div class="add-to-cart-button bottom hover-slide-in show-on-hover">' . $link . '</div>';
			break;
		default:
			return $link;
	}

	return $link;
}

add_filter( 'woocommerce_loop_add_to_cart_link', 'flatsome_child_woocommerce_loop_add_to_cart_link', 5, 3 );



/**
 * Overriden function of parent theme to display only sub category of brand in product box.
 */
function flatsome_woocommerce_shop_loop_category() {
	if ( ! flatsome_option( 'product_box_category' ) ) {
		return;
	} ?>
	<p class="category uppercase is-smaller no-text-overflow product-cat op-7">
		<?php
		global $product;

		$product_id   = $product->get_id() ? $product->get_id() : get_the_ID();
		$product_cats = get_the_terms( $product_id, 'product_cat' );

		$brands_cat_obj = get_term_by( 'slug', 'brands', 'product_cat' );
		$brands_cat_id  = $brands_cat_obj->term_id;

		foreach ( $product_cats as $product_cat => $product_cat_obj ) {
			if ( $product_cat_obj->parent === $brands_cat_id ) {
				echo esc_html( $product_cat_obj->name );
			}
		}
}
?>
	</p>
