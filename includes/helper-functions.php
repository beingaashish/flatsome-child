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
			// $all_product_effect_terms = get_terms(
			// 	array(
			// 		'taxonomy'   => 'product_effect',
			// 		'hide_empty' => false,
			// 	)
			// );

			$selected_product_effect_terms = get_the_terms( $product_id, 'product_effect' );

			if ( false == $selected_product_effect_terms ) {
				$selected_product_effect_terms = array();
			}

			foreach ( $selected_product_effect_terms as $term_index => $term_obj ) {
				?>
			<div class="col medium-3 small-6 large-3">
				<div class="col-inner">
					<div class="img has-hover x md-x lg-x y md-y lg-y">
						<div class="img-inner dark">
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
 * Parameters for admin/flatsome_child_external_api_script.js file.
 *
 * @return array
 */
function myronja_external_api_params() {
	$arr                     = array();
	$arr['ajaxUrl']          = admin_url( 'admin-ajax.php' );
	$arr['baseUrl']          = get_home_url();
	$arr['externalAPINonce'] = wp_create_nonce( 'flatsome_child_create_order_in_external_api_nonce' );

	return $arr;
}
add_filter( 'myronja_external_api_params', 'myronja_external_api_params' );

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


/**
 * Registers custom order status post type -
 * which represents the order that has been successfully created in the external api.
 *
 * @param void
 *
 * @return void
 */
function myronja_external_order_status() {
	register_post_status(
		'wc-external-order',
		array(
			'label'                     => 'External order status',
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'External order <span class="count">(%s)</span>', 'External order <span class="count">(%s)</span>' ),
		)
	);
}
// add_action( 'init', 'myronja_external_order_status' );

/**
 * Shows custom order status in dropdown.
 *
 * @param Array $order_statuses - Array of orderstatues.
 *
 * @return Array $order_statues
 */
function myronja_add_external_order_status_to_dropdown( $order_statuses ) {
	$new_order_statuses = array();

	foreach ( $order_statuses as $key => $status ) {

		$new_order_statuses[ $key ] = $status;

		if ( 'wc-processing' === $key ) {
			$new_order_statuses['wc-external-order'] = 'INPROGRESS';
		}
	}

	return $new_order_statuses;
}
// add_filter( 'wc_order_statuses', 'myronja_add_external_order_status_to_dropdown' );

/**
 * Returns Order data in a proper formatted structure for external API call.
 *
 * @param int    $order_id Order ID.
 * @param object $order Instance of order.
 *
 * @return array $order_data
 */
function myronja_get_formatted_order_data( $order_id, $order ) {

	$total_coupons_discount = array();
	$total_discount         = $order->get_total_discount() ? $order->get_total_discount() : 0;

		// For getting each coupon discounts.
	if ( count( $order->get_coupon_codes() ) > 0 ) {
		$coupon_codes = implode( ',', $order->get_coupon_codes() );

		foreach ( $order->get_coupon_codes() as $key => $coupon_code ) {

			// Retrieving the coupon ID.
			$coupon_post_obj = get_page_by_title( $coupon_code, OBJECT, 'shop_coupon' );
			$coupon_id       = $coupon_post_obj->ID;

			// Get an instance of WC_Coupon object in an array(necessary to use WC_Coupon methods).
			$coupon = new WC_Coupon( $coupon_id );

			if ( $coupon->get_discount_type() === 'percent' ) {
				$order_sub_total_before_discount = floatval( $order->get_subtotal() );
				$discount_percentage             = floatval( $coupon->get_amount() );
				$coupon_amount                   = floatval( ( $discount_percentage / 100 ) * $order_sub_total_before_discount );
			} else {
				$coupon_amount = floatval( $coupon->get_amount() );
			}

			array_push( $total_coupons_discount, $coupon_amount );
		}
	}

	$order_data = array(
		'orderNumber'         => (string) $order_id,
		'shippingFirstName'   => $order->get_shipping_first_name(),
		'shippingLastName'    => $order->get_shipping_last_name(),
		'shippingEmail'       => $order->get_billing_email(),
		'shippingAddress'     => $order->get_shipping_address_1(),
		'shippingAddress2'    => $order->get_shipping_address_2(),
		'shippingCity'        => $order->get_shipping_city(),
		'shippingPostalCode'  => $order->get_shipping_postcode(),
		'shippingPhoneNumber' => $order->get_billing_phone(),
		'shippingCountry'     => $order->get_shipping_country(),
		'shippingCountryCode' => 'DK',
		'billingFirstName'    => $order->get_billing_first_name(),
		'billingLastName'     => $order->get_billing_last_name(),
		'billingEmail'        => $order->get_billing_email(),
		'billingAddress'      => $order->get_billing_address_1(),
		'billingAddress2'     => $order->get_billing_address_2(),
		'billingCity'         => $order->get_billing_city(),
		'billingPostalCode'   => $order->get_billing_postcode(),
		'billingPhoneNumber'  => $order->get_billing_phone(),
		'billingCountry'      => $order->get_billing_country(),
		'billingCountryCode'  => 'DK',
		'couponCode'          => isset( $coupon_codes ) ? $coupon_codes : '',
		'voucherCode'         => null,
		'discountAmount'      => $total_discount,
		'voucherDiscount'     => $total_coupons_discount && ! empty( $total_coupons_discount ) ? array_sum( $total_coupons_discount ) : 0,
		'giftCardDiscount'    => 0,
		'subTotal'            => $order->get_subtotal(),
		'orderTotal'          => $order->get_total(),
		'shippingPrice'       => $order->get_shipping_total(),
		'currency'            => $order->get_currency(),
		'locale'              => 'dk',
		'paymentType'         => $order->get_payment_method(),
		'paymentId'           => $order->get_payment_method(),
	);

	return $order_data;

}

/**
 * Returns Order's  data in a proper formatted structure for external API call.
 *
 * @param object $order Order object.
 * @return array $cart_items Order's cart items.
 */
function myronja_get_formatted_order_cart_items_data( $order ) {
	$cart_items = array();

	foreach ( $order->get_items() as $item_id => $item ) {

		$product_id  = $item->get_product_id();
		$product_obj = wc_get_product( $product_id );

		$product_detail_arr = array();

		// Code for retrieving product brand.
		$brand_cat  = get_term_by( 'slug', 'brands', 'product_cat' );
		$taxonomies = array(
			'taxonomy' => 'product_cat',
		);
		$args       = array(
			'child_of'   => $brand_cat->term_id,
			'hide_empty' => true,
			'object_ids' => $product_id,
		);
		$brand_obj  = get_terms( $taxonomies, $args );

		// Code for retrieving image thumbnail.
		$image_src = wp_get_original_image_url( get_post_thumbnail_id( $product_id ) );

		$product_detail_arr['productWpId']         = $product_id;
		$product_detail_arr['productName']         = $item->get_name();
		$product_detail_arr['serialNo']            = get_post_meta( $product_id, '_myronja_product_serial_no', true );
		$product_detail_arr['productId']           = get_post_meta( $product_id, '_myronja_product_ext_product_id', true );
		$product_detail_arr['brand']               = $brand_obj ? $brand_obj[0]->name : '';
		$product_detail_arr['size']                = get_post_meta( $product_id, '_myronja_product_quantity', true );
		$product_detail_arr['imageUrl']            = $image_src ? $image_src : '';
		$product_detail_arr['quantity']            = $item->get_quantity();
		$product_detail_arr['price']               = $product_obj->get_regular_price();
		$product_detail_arr['priceAfterDiscount']  = $product_obj->get_sale_price() == 0 ? $product_obj->get_regular_price() : $product_obj->get_sale_price();
		$product_detail_arr['fromPersonalShopper'] = false;

		array_push( $cart_items, $product_detail_arr );
	}

	return $cart_items;
}

function myronja_handle_order_create_request_in_external_api() {
	if ( ! is_user_logged_in() ) {
		return;
	}

	if ( check_ajax_referer( 'flatsome_child_create_order_in_external_api_nonce', 'nonce' ) ) {

		$order_id = isset( $_POST['orderId'] ) ? json_decode( sanitize_text_field( wp_json_encode( $_POST['orderId'] ) ), true ) : '';

		$order    = wc_get_order( $order_id );
		$endpoint = 'https://myronja.nu:8888/api/myronja/v1/external-order';

		// Get Order Data in a proper formmated structure.
		$order_data = myronja_get_formatted_order_data( $order_id, $order );

		// Get cart items in a proper formatted order.
		$cart_items = myronja_get_formatted_order_cart_items_data( $order );

		$data = array(
			'orderData' => $order_data,
			'cartItems' => $cart_items,
			'userId'    => null,
		);

		$api_args = array(
			'headers'     => array(
				'Content-Type' => 'application/json',
				'apikey'       => 'myronja-wordpress',
				'apisecret'    => 'azQfnPjKhWtAJZTj',
				'shopcountry'  => 'Danmark',
			),

			'data_format' => 'body',
			'body'        => wp_json_encode( $data ),
		);

		if ( 'processing' === $order->get_status() ) {
			$response = wp_remote_post( $endpoint, $api_args );

			$response_code  = wp_remote_retrieve_response_code( $response );
			$order_data_res = json_decode( wp_remote_retrieve_body( $response ) );

			if ( property_exists( $order_data_res, 'message' ) ) {

				if ( 'unique_order_number' === $order_data_res->message->original->constraint ) {
					$message_info     = __( 'Order already exists in External API', );
					$order_status_res = $order->update_status( 'external-order', '', true );

					echo wp_json_encode( $message_info );
					wp_die();
				}
			}

			if ( 200 === $response_code ) {

				if ( 'INPROGRESS' === $order_data_res->status ) {
					$order_status_res = $order->update_status( 'external-order', '', true );

					if ( $order_status_res ) {
						echo wp_json_encode( $response_code );
						wp_die();
					}
				}
			} else {
				echo wp_json_encode( $response_code );
				wp_die();
			}
		}
	}
}
// add_action( 'wp_ajax_myronja_handle_order_create_request_in_external_api', 'myronja_handle_order_create_request_in_external_api' );
// add_action( 'wp_ajax_nopriv_myronja_handle_order_create_request_in_external_api', 'myronja_handle_order_create_request_in_external_api' );


/**
 * Hide shipping rates when free shipping is available.
 * Updated to support WooCommerce 2.6 Shipping Zones.
 *
 * @param array $rates Array of rates found for the package.
 * @return array
 */
function myronja_hide_shipping_when_free_is_available( $rates ) {
	$free = array();
	foreach ( $rates as $rate_id => $rate ) {
		if ( 'free_shipping' === $rate->method_id ) {
			$free[ $rate_id ] = $rate;
			break;
		}
	}
	return ! empty( $free ) ? $free : $rates;
}
add_filter( 'woocommerce_package_rates', 'myronja_hide_shipping_when_free_is_available', 100 );
