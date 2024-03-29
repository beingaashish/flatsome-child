<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || false === wc_get_loop_product_visibility( $product->get_id() ) || ! $product->is_visible() ) {
	return;
}



// Check stock status.
$out_of_stock = ! $product->is_in_stock();

// Check if product is about to go out of stock.
$available_quantity = null != $product->get_stock_quantity() ? (int) $product->get_stock_quantity() : null;
$global_product_threshold = esc_html( get_option( 'woocommerce_notify_low_stock_amount', '' ) );
$product_threshold  = null != $product->get_low_stock_amount() ? (int) $product->get_low_stock_amount() : $global_product_threshold;

// Extra post classes.
$classes   = array();
$classes[] = 'product-small';
$classes[] = 'col';
$classes[] = 'has-hover';

// Products meta.
$myronja_product_quantity = get_post_meta( $product->get_id(), '_myronja_product_quantity', true ) ? get_post_meta( $product->get_id(), '_myronja_product_quantity', true ) : '';

if ( $out_of_stock ) {
	$classes[] = 'out-of-stock';
}

?><div <?php wc_product_class( $classes, $product ); ?>>
	<div class="col-inner">
	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
	<div class="product-small box <?php echo flatsome_product_box_class(); ?>">
		<div class="box-image">
			<div class="<?php echo flatsome_product_box_image_class(); ?>">
				<a href="<?php echo get_the_permalink(); ?>" aria-label="<?php echo esc_attr( $product->get_title() ); ?>">
					<?php
						/**
						 *
						 * @hooked woocommerce_get_alt_product_thumbnail - 11
						 * @hooked woocommerce_template_loop_product_thumbnail - 10
						 */
						do_action( 'flatsome_woocommerce_shop_loop_images' );
					?>
				</a>
			</div>
			<div class="image-tools is-small top right show-on-hover">
				<?php do_action( 'flatsome_product_box_tools_top' ); ?>
			</div>
			<div class="image-tools is-small hide-for-small bottom left show-on-hover">
				<?php do_action( 'flatsome_product_box_tools_bottom' ); ?>
			</div>
			<div class="image-tools <?php echo flatsome_product_box_actions_class(); ?>">
				<?php do_action( 'flatsome_product_box_actions' ); ?>
			</div>
			<?php
			if ( $out_of_stock ) {
				?>
				<div class="out-of-stock-label"><?php _e( 'Kommer Snart', 'woocommerce' ); ?></div>
				<?php
			} elseif ( ( $available_quantity && $product_threshold ) && ( $available_quantity < $product_threshold )  ) {
				?>
				<div class="out-of-stock-label nearly-out-of-stock"><?php _e( 'NÆSTEN UDSOLGT', 'woocommerce' ); ?></div>
				<?php
			}
			?>
			<?php
			// This hook displays product box add to cart button.
			do_action( 'flatsome_product_box_after' );
			?>
		</div>

		<div class="box-text <?php echo flatsome_product_box_text_class(); ?>">
			<?php
				do_action( 'woocommerce_before_shop_loop_item_title' );

				echo '<div class="title-wrapper">';
				do_action( 'woocommerce_shop_loop_item_title' );
				echo '</div>';
				echo '<div class="price-wrapper">';
				if ( $myronja_product_quantity ) {
					echo '<p class="flatsome-child-product-quantity">' . $myronja_product_quantity . '</p>';
				}
				do_action( 'woocommerce_after_shop_loop_item_title' );
				echo '</div>';
			?>
		</div>
	</div>
	<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
	</div>
</div><?php /* empty PHP to avoid whitespace */ ?>
