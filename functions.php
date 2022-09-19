<?php
/**
 * Child theme's function.php file.
 *
 * @package flatsome
 */

defined( 'ABSPATH' ) || exit;

// Check if woocommerce is active.
if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

/**
 * Child Theme Version
 */
define( 'FLATSOME_CHILD_VERSION', '1.0.0' );


/**
 * Include files.
 */
require_once get_stylesheet_directory() . '/includes/helper-functions.php';
require_once get_stylesheet_directory() . '/includes/theme-metaboxes.php';
require_once get_stylesheet_directory() . '/includes/subcategories-filter.php';
require_once get_stylesheet_directory() . '/includes/theme-custom-taxonomies.php';
require_once get_stylesheet_directory() . '/includes/theme-custom-post-types.php';
require_once get_stylesheet_directory() . '/includes/personal-shopper.php';


/**
 * Enqueue style and scripts.
 *
 * @return void
 */
function flatsome_child_scripts() {

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_script( 'flatsome_child_script', get_stylesheet_directory_uri() . "/assets/js/flatsome-child-script{$suffix}.js", array( 'jquery' ), FLATSOME_CHILD_VERSION, true );

	wp_register_script( 'flatsome_child_categories_filter_js', get_stylesheet_directory_uri() . "/assets/js/flatsome-child-categories-filter-script{$suffix}.js", array( 'jquery' ), FLATSOME_CHILD_VERSION, true );

	if ( ! is_shop() && is_product_category() ) {

		wp_localize_script( 'flatsome_child_categories_filter_js', 'flatsomeChildCategoriesParam', apply_filters( 'flatsome_child_categories_filter_params', array() ) );

		$cat_data = get_queried_object();

		if ( 0 !== $cat_data->parent ) {
			wp_enqueue_script( 'flatsome_child_categories_filter_js' );
		}
	}

	// Personal Shopper Form page script.
	wp_register_script( 'flatsome_child_personal_shopper_js', get_stylesheet_directory_uri() . "/assets/js/flatsome-child-personal-shopper-script{$suffix}.js", array( 'jquery' ), FLATSOME_CHILD_VERSION, true );
	if ( is_page( 'personal-shopper-form' ) ) {
		wp_localize_script( 'flatsome_child_personal_shopper_js', 'flatsomeChildPersonalShopperParam', apply_filters( 'personal_shopper_create_post_params', array() ) );
		wp_enqueue_script( 'flatsome_child_personal_shopper_js' );
	}

	// Personal Shopper Myaccount script.
	wp_register_script( 'flatsime_child_personal_shopper_myaccount_js', get_stylesheet_directory_uri() . "/assets/js/flatsome-child-personal-shopper-myaccount-script{$suffix}.js", array( 'jquery' ), FLATSOME_CHILD_VERSION, true );

	if ( is_single() && 'personal_shop' == get_post_type() ) {
		wp_localize_script( 'flatsime_child_personal_shopper_myaccount_js', 'flatsomeChildPersonalMyAccountParam', apply_filters( 'personal_shopper_myaccount_params', array() ) );
		wp_enqueue_script( 'flatsime_child_personal_shopper_myaccount_js' );
	}
}

add_action( 'wp_enqueue_scripts', 'flatsome_child_scripts' );


/**
 * Enqueue scripts and styles in admin pages.
 */
function flatsome_child_admin_scripts() {
	if ( ! function_exists( 'get_current_screen' ) ) {
		require_once ABSPATH . '/wp-admin/includes/screen.php';
	}

	$current_screen = get_current_screen();
	$suffix         = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_register_script( 'flatsome_child_metabox_scripts', get_stylesheet_directory_uri() . "/assets/admin/js/flatsome_child_metabox_scripts{$suffix}.js", array( 'jquery' ), FLATSOME_CHILD_VERSION, true );

	if ( $current_screen && property_exists( $current_screen, 'post_type' ) && 'personal_shop' === $current_screen->post_type ) {
		wp_localize_script( 'flatsome_child_metabox_scripts', 'flatsomeChildMetaboxParams', apply_filters( 'personal_shopper_recommend_products_params', array() ) );
		wp_enqueue_script( 'flatsome_child_metabox_scripts' );
		wp_enqueue_style( 'flatsome_child_metabox_styles', get_stylesheet_directory_uri() . '/assets/admin/css/flatsome-child-metabox.css', array(), FLATSOME_CHILD_VERSION, 'all' );
	}
}
add_action( 'admin_enqueue_scripts', 'flatsome_child_admin_scripts' );

/**
 * Displays message for minimum order amount for free shipping.
 *
 * @return void
 */
function myronja_minimum_order_for_free_shipping() {
	global $woocommerce;

	$user_cart_subtotals            = floatval( $woocommerce->cart->subtotal );
	$minimum_order_amount_threshold = 399.00;

	if ( $user_cart_subtotals && $user_cart_subtotals > 0 && $user_cart_subtotals < $minimum_order_amount_threshold ) :
		$amount_require_for_freeshipping = floatval( $minimum_order_amount_threshold - $user_cart_subtotals );
		?>
	<div class="myronja-mini-cart-freeshipping-message">
		<p>Du har <?php echo esc_html( sprintf( '%.2f', $amount_require_for_freeshipping ) ); ?> kr tilbage til gratis levering.</p>
	</div>
		<?php
	endif;
}
add_action( 'woocommerce_widget_shopping_cart_before_buttons', 'myronja_minimum_order_for_free_shipping' );

/**
 * Displays shipping information in checkout form.
 *
 * @return void
 */
function myronja_shipping_detalils_display() {
	?>
	<div class="myronja-shipping_details-wrapper">
		<h3><?php esc_html_e( 'Leveringsmetode', 'woocommerce' ); ?></h3>
		<label><?php esc_html_e( 'Hjemmelevering', 'woocommerce' ); ?></label>
		<div class="myronja-shipping-info">
			<div class="myronja-shipping-info__header">
				<div class="myronja-shipping-info__header-left">
					<img src="<?php echo esc_attr( get_stylesheet_directory_uri() . '/assets/images/ups.svg' ); ?>" alt="Ups shipping logo">
					<div class="myronja-shipping-price"><?php esc_html_e( '49 kr', 'woocommerce' ); ?></div>
				</div>
				<div class="myronja-shipping-info__header-right">
					<div class="myronja-shipping-info__delivery-info"><?php esc_html_e( 'Afsendes mandag', 'woocommerce' ); ?></div>
				</div>
			</div>

			<div class="myronja-shipping-info__content">
				<h5><?php esc_html_e( 'UPS', 'woocommerce' ); ?></h5>
				<p><?php esc_html_e( 'Bestil inden kl. 14:00 , så afsender vi pakken samme dag (man-tors). Bestil fredag og din pakke leveres mandag.', ' woocommerce' ); ?></p>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'woocommerce_before_order_notes', 'myronja_shipping_detalils_display' );


/**
 * Undocumented function.
 *
 * @return void
 */
function myronja_update_info_after_order_placed( $order_id ) {
	$order                  = wc_get_order( $order_id );
	$endpoint               = 'https://myronja.nu:8888/api/myronja/v1/external-order';
	$total_coupons_discount = array();
	$total_discount         = $order->get_total_discount() ? $order->get_total_discount() : 0;

	error_log( print_r( $order->get_status(), true ) );

	var_dump( $order->get_status() );

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

	// Shipping info.
	$order_data = array(
		'orderStatus'         => 'INPROGRESS',
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
		$product_detail_arr['priceAfterDiscount']  = $product_obj->get_sale_price() ? $product_obj->get_sale_price() : 0;
		$product_detail_arr['fromPersonalShopper'] = false;

		array_push( $cart_items, $product_detail_arr );
	}

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

	error_log( print_r( wp_json_encode( $data ), true ) );

	if ( 'processing' === $order->get_status() ) {
		// Also check if the order with same orderNumber already exist.
		// Send request to external API to create an order.
		$response = wp_remote_post( $endpoint, $api_args );
		error_log( print_r( $response, true ) );
	}
}
// add_action( 'woocommerce_thankyou', 'myronja_update_info_after_order_placed', 10, 1 );


/* Code for running crons that updates order status. */

/**
 * Adds custom cron interval of two hours.
 *
 * @param [type] $schedules
 * @return void
 */
function myronja_wpcron_interval( $schedules ) {

	// Two hours custom interval for cron.
	$two_hour = array(
		'interval' => 60,
		'display'  => 'Two Hour',
	);

	$schedules['two_hour'] = $two_hour;

	return $schedules;
}
add_filter( 'cron_schedules', 'myronja_wpcron_interval' );

/**
 * Adds cron event.
 *
 * @return void
 */
function myronja_wpcron_activation() {
	if ( ! wp_next_scheduled( 'myronja_update_order_status' ) ) {
		wp_schedule_event( time(), 'two_hour', 'myronja_update_order_status' );
	}
}
// add_action( 'wp', 'myronja_wpcron_activation' );


/**
 * Call back of myronja_update_order_status event.
 *
 * @return void
 */
function myronja_update_order_status_cb() {
	if ( ! defined( 'DOING_CRON' ) ) {
		return;
	}

	error_log( print_r( 'Cron just ran', true ) );

	$orders_id_arr = array();

	$orders = wc_get_orders( array( 'numberposts' => -1 ) );

	if ( $orders ) {
		// Loop through each WC_Order object
		foreach ( $orders as $order ) {
			// (Important) Also add condition, if the order already exist in the external API if it doesnot exists create a new order in the API.
			// if ( orderDoesNotExistInExternalAPI ) {
			//
			// }
			if ( $order->get_id() && $order->get_status() === 'processing' ) {
				$order_id_str = (string) $order->get_id();
				array_push( $orders_id_arr, $order_id_str );
			}
		}
	}

	if ( count( $orders_id_arr ) > 0 ) {

			$data = array(
				'orderNumbers' => $orders_id_arr,
			);

			$endpoint = 'https://myronja.nu:8888/api/myronja/v1/external-order/order-status';

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

			// Send request to external API.
			$response        = wp_remote_post( $endpoint, $api_args );
			$response_status = wp_remote_retrieve_response_code( $response );

			if ( 200 == $response_status ) {
				$order_status_map_arr = array(
					'RETURNED'  => 'refunded',
					'REFUND'    => 'refunded',
					'DELIVERED' => 'completed',
				);
				$order_data_res = json_decode( wp_remote_retrieve_body( $response ) );
				error_log( print_r( $order_data_res, true ) );

				if ( is_array( $order_data_res ) && count( $order_data_res ) > 0 ) {
					foreach ( $order_data_res as $key => $value ) {

						$wc_order = wc_get_order( $value->orderNumber );

						if ( $wc_order ) {
							if ( array_key_exists( $value->status, $order_status_map_arr ) ) {

								error_log( print_r( $order_status_map_arr[ $value->status ], true ) );
								$wc_order->update_status( $order_status_map_arr[ $value->status ], '', true );

							}
						}
					}
				}
			}
	}
}
// add_action( 'myronja_update_order_status', 'myronja_update_order_status_cb' );



add_filter( 'woocommerce_account_menu_items', 'misha_rename_downloads' );

function misha_rename_downloads( $menu_links ){
	
	$menu_links[ 'edit-account' ] = 'Profil';
	$menu_links[ 'edit-address' ] = 'Adresse';
	$menu_links[ 'orders' ] = 'Ordre';
	$menu_links[ 'customer-logout' ] = 'Log af';

	return $menu_links;
}




// remove unneccesery woocommerce tabs 
add_filter( 'woocommerce_account_menu_items', 'misha_remove_my_account_links' );
function misha_remove_my_account_links( $menu_links ){
	unset( $menu_links[ 'dashboard' ] ); // Disable Dashboard
	unset( $menu_links[ 'payment-methods' ] ); // Disable Payment Methods
	unset( $menu_links[ 'downloads' ] ); // Disable Downloads
	unset($items['edit-address']); //disable edit-address
	
	return $menu_links;
	
}

// merge edit address tab to edit-account tab
add_action( 'woocommerce_account_edit-account_endpoint', 'woocommerce_account_edit_address' );


// change order of show woocommerce tabs
add_filter( 'woocommerce_account_menu_items', 'misha_menu_links_reorder' );

function misha_menu_links_reorder( $menu_links ){
	
	return array(
		'edit-account' => __( 'Profil', 'woocommerce' ),
		'edit-address' => __( 'Adresse', 'woocommerce' ),
		'orders' => __( 'Ordre', 'woocommerce' ),
		'return-eringer' => __( 'Returneringer', 'woocommerce' ),
		'ronja-coins' => __( 'Ronjacoins', 'woocommerce' ),
		'myronja-myaccount-personal-shopper' => __( 'personal-shopper', 'woocommerce' ),
		'customer-logout' => __( 'Logout', 'woocommerce' )
	);

}



// add Returneringer to woocommerce tab

add_filter ( 'woocommerce_account_menu_items', 'misha_return_eringer_link', 40 );
function misha_return_eringer_link( $menu_links ){
	
	$menu_links = array_slice( $menu_links, 0, 5, true ) 
	+ array( 'return-eringer' => 'Returneringer' )
	+ array_slice( $menu_links, 5, NULL, true );
	
	return $menu_links;

}
// register permalink endpoint
add_action( 'init', 'misha_add_endpoint' );
function misha_add_endpoint() {

	add_rewrite_endpoint( 'return-eringer', EP_PAGES );

}
// content for the new page in My Account, woocommerce_account_{ENDPOINT NAME}_endpoint
add_action( 'woocommerce_account_return-eringer_endpoint', 'misha_my_account_endpoint_content' );
function misha_my_account_endpoint_content() {

	// of course you can print dynamic content here, one of the most useful functions here is get_current_user_id()
	echo 'Vi er igang med at forbedre vores retur side. Beklager ulejligheden. Skriv en mail til info@myronja.com med dit ordre nummer og de produkter du ønsker returneret, så sender vi dig en retur label.';

}




// add Ronjacoins to woocommerce tab
add_filter ( 'woocommerce_account_menu_items', 'misha_ronja_coins_link', 40 );
function misha_ronja_coins_link( $menu_links ){
	
	$menu_links = array_slice( $menu_links, 0, 5, true ) 
	+ array( 'ronja-coins' => 'Ronjacoins' )
	+ array_slice( $menu_links, 5, NULL, true );
	
	return $menu_links;

}
// register permalink endpoint
add_action( 'init', 'misha_adds_endpoint' );
function misha_adds_endpoint() {

	add_rewrite_endpoint( 'ronja-coins', EP_PAGES );

}
// content for the new page in My Account, woocommerce_account_{ENDPOINT NAME}_endpoint
add_action( 'woocommerce_account_ronja-coins_endpoint', 'misha_my_account_endpoints_content' );
function misha_my_account_endpoints_content() {

	// of course you can print dynamic content here, one of the most useful functions here is get_current_user_id()
	echo 'Vi er igang med at forbedre vores Ronjacoins. Beklager ulejligheden. Som kompentation kan du bruge denne rabatkode MY10';

}


// disable log-out confirmation and redirect user to home page
add_action( 'template_redirect', 'logout_confirmation' );

function logout_confirmation() {

    global $wp;

    if ( isset( $wp->query_vars['customer-logout'] ) ) {

        wp_redirect( str_replace( '&amp;', '&', wp_logout_url( site_url() ) ) );

        exit;

    }

}

// Add field
function action_woocommerce_edit_account_form_start() {
    ?>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="image"><?php esc_html_e( 'Image', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
        <input type="file" class="woocommerce-Input" name="image" accept="image/x-png,image/gif,image/jpeg">
    </p>
    <?php
}
add_action( 'woocommerce_edit_account_form_start', 'action_woocommerce_edit_account_form_start' );

// Validate
function action_woocommerce_save_account_details_errors( $args ){
    if ( isset($_POST['image']) && empty($_POST['image']) ) {
        $args->add( 'image_error', __( 'Please provide a valid image', 'woocommerce' ) );
    }
}
add_action( 'woocommerce_save_account_details_errors','action_woocommerce_save_account_details_errors', 10, 1 );

// Save
function action_woocommerce_save_account_details( $user_id ) {  
    if ( isset( $_FILES['image'] ) ) {
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );

        $attachment_id = media_handle_upload( 'image', 0 );

        if ( is_wp_error( $attachment_id ) ) {
            update_user_meta( $user_id, 'image', $_FILES['image'] . ": " . $attachment_id->get_error_message() );
        } else {
            update_user_meta( $user_id, 'image', $attachment_id );
        }
   }
}
add_action( 'woocommerce_save_account_details', 'action_woocommerce_save_account_details', 10, 1 );

// Add enctype to form to allow image upload
function action_woocommerce_edit_account_form_tag() {
    echo 'enctype="multipart/form-data"';
} 
add_action( 'woocommerce_edit_account_form_tag', 'action_woocommerce_edit_account_form_tag' );

// Display
function action_woocommerce_edit_account_form() {
    // Get current user id
    $user_id = get_current_user_id();

    // Get attachment id
    $attachment_id = get_user_meta( $user_id, 'image', true );

    // True
    if ( $attachment_id ) {
        $original_image_url = wp_get_attachment_url( $attachment_id );

        // Display Image instead of URL
        echo wp_get_attachment_image( $attachment_id, 'full');
    }
} 
add_action( 'woocommerce_edit_account_form', 'action_woocommerce_edit_account_form' );
