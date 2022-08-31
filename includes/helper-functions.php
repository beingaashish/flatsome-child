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
		?>
	</p>

	<?php
}

/**
 * Add Product Ingredients to tabs in single product page.
 *
 * @param array $tabs Tabs title and content array.
 *
 * @return array $tabs.
 */
function flatsome_child_add_extra_tabs_on_single_product_page( $tabs ) {
	global $post;

	unset( $tabs['description'] );

	$post_id                     = $post->ID;
	$myronja_product_ingredients = get_post_meta( $post_id, '_myronja_product_ingredients', true );
	$myronja_product_application = get_post_meta( $post_id, '_myronja_product_application', true );
	$myronja_product_evaluation  = get_post_meta( $post_id, '_myronja_product_evaluation', true );

	if ( ! empty( $myronja_product_ingredients ) ) {
		$tabs['ingredients_tab'] = array(
			'title'    => __( 'INGREDIENSER', 'woocommerce' ),
			'priority' => 9,
			'callback' => 'flatsome_child_ingredients_product_tab_content',
		);
	}

	if ( ! empty( $myronja_product_application ) ) {
		$tabs['application_tab'] = array(
			'title'    => __( 'ANVENDELSE', 'woocommerce' ),
			'priority' => 8,
			'callback' => 'flatsome_child_application_product_tab_content',
		);
	}

	if ( ! empty( $myronja_product_evaluation ) ) {
		$tabs['evaluation_tab'] = array(
			'title'    => __( 'VORES VURDERING', 'woocommerce' ),
			'priority' => 10,
			'callback' => 'flatsome_child_evaluation_product_tab_content',
		);
	}

	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'flatsome_child_add_extra_tabs_on_single_product_page' );


/**
 * Callback content for adding ingredient content.
 *
 * @return void
 */
function flatsome_child_ingredients_product_tab_content() {
	global $post;

	$post_id                     = $post->ID;
	$myronja_product_ingredients = get_post_meta( $post_id, '_myronja_product_ingredients', true );

	if ( ! empty( $myronja_product_ingredients ) ) {
		echo wp_kses_post( apply_filters( 'the_content', $myronja_product_ingredients ) );
	}
}

/**
 * Callback content for adding application content.
 *
 * @return void
 */
function flatsome_child_application_product_tab_content() {
	global $post;

	$post_id                     = $post->ID;
	$myronja_product_application = get_post_meta( $post_id, '_myronja_product_application', true );

	if ( ! empty( $myronja_product_application ) ) {
		echo wp_kses_post( apply_filters( 'the_content', $myronja_product_application ) );
	}
}

/**
 * Callback content for adding evaluation content.
 *
 * @return void
 */
function flatsome_child_evaluation_product_tab_content() {
	global $post;

	$post_id                    = $post->ID;
	$myronja_product_evaluation = get_post_meta( $post_id, '_myronja_product_evaluation', true );

	if ( ! empty( $myronja_product_evaluation ) ) {
		echo wp_kses_post( apply_filters( 'the_content', $myronja_product_evaluation ) );
	}
}

/**
 * Displays product quantity in single product page.
 *
 * @return void
 */
function flatsome_child_product_quantity() {
	global $post;

	$post_id                  = $post->ID;
	$myronja_product_quantity = get_post_meta( $post_id, '_myronja_product_quantity', true );

	if ( $myronja_product_quantity ) {
		echo '<p class="flatsome-child-product-quantity">' . esc_html( $myronja_product_quantity ) . '</h1>';
	}
}
add_action( 'woocommerce_single_product_summary', 'flatsome_child_product_quantity', 6 );

/**
 * Displays product description in single product page.
 *
 * @return void
 */
function flatsome_child_product_description() {
	global $post;

	echo '<p>' . wp_kses_post( $post->post_content ) . '</p>';
}
add_action( 'woocommerce_single_product_summary', 'flatsome_child_product_description', 29 );

/**
 * Displays
 *
 * @return void
 */
function flatsome_child_product_effects() {
	global $post, $product;

	$product_id = $product->get_id() ? $product->get_id() : $post->ID;
	?>
	<section class="flatsome-child-product-effect">
		<div class="container section-title-container">
			<h3 class="section-title section-title-center">
				<b></b>
				<span class="section-title-main" style="font-size:78%;">produkt Effekt</span>
				<b></b>
			</h3>
		</div>
		<div class="row">
			<?php
			$all_product_effect_terms = get_terms(
				array(
					'taxonomy'   => 'product_effect',
					'hide_empty' => false,
				)
			);

			$selected_product_effect_terms = get_the_terms( $product_id, 'product_effect' );

			if ( false == $selected_product_effect_terms ) {
				$selected_product_effect_terms = array();
			}

			foreach ( $all_product_effect_terms as $term_index => $term_obj ) {
				?>
			<div class="col medium-3 small-6 large-3">
				<div class="col-inner">
					<div class="img has-hover x md-x lg-x y md-y lg-y">
						<div class="img-inner dark <?php echo esc_attr( ! in_array( $term_obj, $selected_product_effect_terms ) ? 'flatsome-child-dim-image' : '' ); ?>">
							<img width="216" height="253" src="<?php echo esc_attr( get_stylesheet_directory_uri() . '/assets/images/' . $term_obj->slug . '.png' ); ?>" class="attachment-large size-large" alt="" loading="lazy">
						</div>
					</div>
				</div>
			</div>
				<?php
			}
			?>

		</div>
	</section>
	<section class="flatsome-child-product-sustainability">
		<div class="container section-title-container">
			<h3 class="section-title section-title-center">
				<b></b>
				<span class="section-title-main" style="font-size:78%;">Bæredygtighed</span>
				<b></b>
			</h3>
		</div>
		<div class="row">
			<?php
			$all_sustainability_terms = get_terms(
				array(
					'taxonomy'   => 'product_sustainability',
					'hide_empty' => false,
				)
			);

			$selected_sustainability_terms = get_the_terms( $product_id, 'product_sustainability' );

			if ( false == $selected_sustainability_terms ) {
				$selected_sustainability_terms = array();
			}

			foreach ( $all_sustainability_terms as $term_index => $term_obj ) {
				?>
			<div class="col medium-3 small-6 large-3">
				<div class="col-inner">
					<div class="img has-hover x md-x lg-x y md-y lg-y">
						<div class="img-inner dark <?php echo esc_attr( ! in_array( $term_obj, $selected_sustainability_terms ) ? 'flatsome-child-dim-image' : '' ); ?>">
							<img width="216" height="253" src="<?php echo esc_attr( get_stylesheet_directory_uri() . '/assets/images/' . $term_obj->slug . '.png' ); ?>" class="attachment-large size-large" alt="" loading="lazy">
						</div>
					</div>
				</div>
			</div>
				<?php
			}
			?>

		</div>
	</section>
	<?php
}
add_action( 'woocommerce_single_product_summary', 'flatsome_child_product_effects', 70 );

/**
 * Parameters for admin/flatsom_child_metabox_scripts.js file
 *
 * @return array
 */
function personal_shopper_recommend_products_params() {
	global $post;

	if ( ! function_exists( 'get_current_screen' ) ) {
		require_once ABSPATH . '/wp-admin/includes/screen.php';
	}

	$current_screen = get_current_screen();
	$arr            = array();

	if ( $current_screen && property_exists( $current_screen, 'post_type' ) && 'personal_shop' === $current_screen->post_type ) {
		$arr['ajaxUrl']                = admin_url( 'admin-ajax.php' );
		$arr['baseUrl']                = get_home_url();
		$arr['addShopProductNonce']    = wp_create_nonce( 'flatsome_child_add_shop_product_nonce' );
		$arr['personalShopPostID']     = $post->ID;
		$arr['personalShopProductIDS'] = get_post_meta( $post->ID, '_personal_shop_recommended_products_ids', false );
	}
	return $arr;
}
add_filter( 'personal_shopper_recommend_products_params', 'personal_shopper_recommend_products_params' );

/**
 * Adds extra fields to the JSON response.
 *
 * @return void
 */
function flatsome_child_custom_rest() {
	register_rest_field(
		'product',
		'myronjaProductPrice',
		array(
			'get_callback' => function () {
				$cur_product = wc_get_product();
				return $cur_product->get_price();
			},
		)
	);

	register_rest_field(
		'product',
		'myronjaProductAmount',
		array(
			'get_callback' => function () {
				$cur_product = wc_get_product();
				return get_post_meta( $cur_product->get_id(), '_myronja_product_quantity', true );
			},
		)
	);

	register_rest_field(
		'product',
		'myronjaProductBrand',
		array(
			'get_callback' => function () {
				$cur_product = wc_get_product();
				$brand_cat   = get_term_by( 'slug', 'brands', 'product_cat' );

				$taxonomies = array(
					'taxonomy' => 'product_cat',
				);

				$args = array(
					'child_of'   => $brand_cat->term_id,
					'hide_empty' => true,
					'object_ids' => $cur_product->get_id(),
				);

				$brand_obj = get_terms( $taxonomies, $args );

				return $brand_obj[0]->name;
			},
		)
	);

	register_rest_field(
		'product',
		'myronjaProductThumbnailSrc',
		array(
			'get_callback' => function () {
				$cur_product = wc_get_product();
				$image_src   = wp_get_attachment_image_src( get_post_thumbnail_id( $cur_product->get_id() ), 'thumbnail' );
				return $image_src;
			},
		),
	);

	register_rest_field(
		'product',
		'myronjaProductStockStatus',
		array(
			'get_callback' => function () {
				$cur_product = wc_get_product();
				return $cur_product->is_in_stock();
			},
		),
	);
}
add_action( 'rest_api_init', 'flatsome_child_custom_rest' );
