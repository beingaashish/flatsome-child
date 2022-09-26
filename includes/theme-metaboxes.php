<?php
/**
 * Child theme's theme-metaboxes.php file.
 *
 * @package flatsome
 */

defined( 'ABSPATH' ) || exit;


/**
 * Registers metabox in the product edit pages.
 *
 * @return void
 */
function flatsome_child_add_meta_box() {

	// Additional metaboxes for product post.
	add_meta_box(
		'flatsome_child_meta_box',
		__( 'Myronja product\'s additional information', 'woocommerce' ),
		'flatsome_child_display_metabox',
		'product',
	);

	// Additional metaboxes for personal shopper post.
	add_meta_box(
		'personal_shopper_metabox',
		__( 'Personal shopper user entered details', 'woocommerce' ),
		'personal_shopper_display_metabox',
		'personal_shop',
	);

	// Additional metaboxes for personal shopper recommend product.
	add_meta_box(
		'personal_shopper_product_recommend_metabox',
		__( 'Personal shopper recommend products', 'woocommerce' ),
		'personal_shopper_display_product_recommend_metabox',
		'personal_shop',
	);

	// Additional metaboxes for personal shopper admin comments.
	add_meta_box(
		'personal_shopper_admin_comments_metabox',
		__( 'Admin comments based on user inputs', 'woocommerce' ),
		'personal_shopper_display_admin_comments_metabox',
		'personal_shop',
	);

	// Metabox for order edit screen.
	// add_meta_box(
	// 	'external_order__metabox',
	// 	__( 'External API Status.', 'woocommerce' ),
	// 	'external_order_display_metabox_content',
	// 	'shop_order',
	// 	'side',
	// 	'core'
	// );
}
add_action( 'add_meta_boxes', 'flatsome_child_add_meta_box' );

/**
 * Functions for adding metafields in metabox for product post.
 *
 * @param object $post Post object of current post.
 *
 * @return void
 */
function flatsome_child_display_metabox( $post ) {

	$post_id                        = $post->ID;
	$myronja_product_serial_no      = (int) get_post_meta( $post_id, '_myronja_product_serial_no', true );
	$myronja_product_ext_product_id = get_post_meta( $post_id, '_myronja_product_ext_product_id', true );
	$myronja_product_quantity       = get_post_meta( $post_id, '_myronja_product_quantity', true );
	$myronja_product_ingredients    = get_post_meta( $post_id, '_myronja_product_ingredients', true ); // INGREDIENSER.
	$myronja_product_application    = get_post_meta( $post_id, '_myronja_product_application', true ); // ANVENDELSE.
	$myronja_product_evaluation     = get_post_meta( $post_id, '_myronja_product_evaluation', true ); // VORES VURDERING.

	wp_nonce_field( basename( __FILE__ ), 'flatsome_child_meta_box_nonce' );
	?>

	<div class="woocommerce_options_panel">
		<p class="form-field">
			<label for="_myronja_product_serial_no">Product's serial number</label>
			<input type="number" id="_myronja_product_serial_no" name="_myronja_product_serial_no" class="short" value="<?php echo esc_html( $myronja_product_serial_no ); ?>">
		</p>
		<p class="form-field">
			<label for="_myronja_product_ext_product_id">Product's ext Id</label>
			<input type="text" id="_myronja_product_ext_product_id" name="_myronja_product_ext_product_id" class="short" value="<?php echo esc_html( $myronja_product_ext_product_id ); ?>">
		</p>
		<p class="form-field">
			<label for="_myronja_product_quantity">Product's amount</label>
			<input type="text" id="_myronja_product_quantity" name="_myronja_product_quantity" class="short" value="<?php echo esc_html( $myronja_product_quantity ); ?>">
		</p>
		<p class="form-field">
			<label for="_myronja_product_ingredients" class="myronja_meta_field_title">Product's Ingredients (INGREDIENSER)</label>
			<textarea style="min-height: 200px" class="short" name="_myronja_product_ingredients" id="_myronja_product_ingredients"><?php echo esc_html( $myronja_product_ingredients, 'woocommerce' ); ?></textarea>
		</p>
		<p class="form-field">
			<label for="_myronja_product_application" class="myronja_meta_field_title">Product's application (ANVENDELSE)</label>
			<textarea style="min-height: 200px" class="short" name="_myronja_product_application" id="_myronja_product_application"><?php echo esc_html( $myronja_product_application, 'woocommerce' ); ?></textarea>
		</p>
		<p class="form-field">
			<label for="_myronja_product_evaluation" class="myronja_meta_field_title">Product's company take (VORES VURDERING)</label>
			<textarea style="min-height: 200px" class="short" name="_myronja_product_evaluation" id="_myronja_product_evaluation"><?php echo esc_html( $myronja_product_evaluation, 'woocommerce' ); ?></textarea>
		</p>
	</div>

	<?php
}

/**
 * Save post meta field data for product post type.
 *
 * @param int $post_id Post id.
 * @return void
 */
function flatsome_child_save_meta_box( $post_id ) {

	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );

	$is_valid_nonce = false;

	if ( isset( $_POST['flatsome_child_meta_box_nonce'] ) ) {
		if ( wp_verify_nonce( wp_unslash( $_POST['flatsome_child_meta_box_nonce'] ), basename( __FILE__ ) ) ) {
			$is_valid_nonce = true;
		}
	}

	if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
		return;
	}

	// Product serial number.
	if ( array_key_exists( '_myronja_product_serial_no', $_POST ) ) {

		update_post_meta(
			$post_id,
			'_myronja_product_serial_no',
			sanitize_text_field( wp_unslash( $_POST['_myronja_product_serial_no'] ) ),
		);
	}

	// Product ext id.
	if ( array_key_exists( '_myronja_product_ext_product_id', $_POST ) ) {

		update_post_meta(
			$post_id,
			'_myronja_product_ext_product_id',
			sanitize_text_field( wp_unslash( $_POST['_myronja_product_ext_product_id'] ) ),
		);
	}

	// Product quantity.
	if ( array_key_exists( '_myronja_product_quantity', $_POST ) ) {

		update_post_meta(
			$post_id,
			'_myronja_product_quantity',
			sanitize_text_field( wp_unslash( $_POST['_myronja_product_quantity'] ) ),
		);
	}

	// Product ingredients.
	if ( array_key_exists( '_myronja_product_ingredients', $_POST ) ) {

		update_post_meta(
			$post_id,
			'_myronja_product_ingredients',
			sanitize_text_field( wp_unslash( $_POST['_myronja_product_ingredients'] ) ),
		);
	}

	// Product application.
	if ( array_key_exists( '_myronja_product_application', $_POST ) ) {

		update_post_meta(
			$post_id,
			'_myronja_product_application',
			sanitize_text_field( wp_unslash( $_POST['_myronja_product_application'] ) ),
		);
	}

	// Product evaluation.
	if ( array_key_exists( '_myronja_product_evaluation', $_POST ) ) {

		update_post_meta(
			$post_id,
			'_myronja_product_evaluation',
			sanitize_text_field( wp_unslash( $_POST['_myronja_product_evaluation'] ) ),
		);
	}
}
add_action( 'save_post', 'flatsome_child_save_meta_box' );

/**
 * Undocumented function
 *
 * @param [type] $post
 * @return void
 */
function personal_shopper_display_metabox( $post ) {
	$post_id = $post->ID;

	$personal_shop_order_number           = get_post_meta( $post_id, '_personal_shop_order_number', true );
	$personal_shop_order_date             = get_post_meta( $post_id, '_personal_shop_order_date', true );
	$personal_shop_user_name              = get_post_meta( $post_id, '_personal_shop_user_name', true );
	$personal_shop_order_status           = get_post_meta( $post_id, '_personal_shop_order_status', true );
	$personal_shop_price_range            = get_post_meta( $post_id, '_personal_shop_price_range', true );
	$personal_shop_hudtilstand            = get_post_meta( $post_id, '_personal_shop_hudtilstand', true );
	$personal_shop_user_goal              = get_post_meta( $post_id, '_personal_shop_user_goal', true );
	$personal_shop_hudtype                = get_post_meta( $post_id, '_personal_shop_hudtype', true );
	$personal_shop_skin_experience        = get_post_meta( $post_id, '_personal_shop_skin_experience', true );
	$personal_shop_produkt_type           = get_post_meta( $post_id, '_personal_shop_produkt_type', true );
	$personal_shop_produkt_recommendation = get_post_meta( $post_id, '_personal_shop_produkt_recommendation', true );
	$personal_shop_ingredients            = get_post_meta( $post_id, '_personal_shop_ingredients', true );
	$personal_shop_user_ingredients       = get_post_meta( $post_id, '_personal_shop_user_ingredients', true );
	$personal_shop_user_awareness         = get_post_meta( $post_id, '_personal_shop_user_awareness', true );

	wp_nonce_field( basename( __FILE__ ), 'personal_shop_meta_box_nonce' );
	?>
	<div class="personal_shopper_options_panel">
		<p class="form-field">
			<label for="_personal_shop_order_number"><?php echo __( 'Order number', 'woocommerce' ); ?></label>
			<input type="text" id="_personal_shop_order_number" name="_personal_shop_order_number" class="short" value="<?php echo esc_html( $personal_shop_order_number ); ?>" readonly>
		</p>
		<p class="form-field">
			<label for="_personal_shop_order_date"><?php echo __( 'Order date', 'woocommerce' ); ?></label>
			<input type="text" id="_personal_shop_order_date" name="_personal_shop_order_date" class="short" value="<?php echo esc_html( $personal_shop_order_date ); ?>" readonly>
		</p>
		<p class="form-field">
			<label for="_personal_shop_user_name"><?php echo __( 'User name', 'woocommerce' ); ?></label>
			<input type="text" id="_personal_shop_user_name" name="_personal_shop_user_name" class="short" value="<?php echo esc_html( $personal_shop_user_name ); ?>" readonly>
		</p>
		<p class="form-field">
			<label for="_personal_shop_order_status"><?php echo __( 'Order Status', 'woocommerce' ); ?></label>
			<input type="text" id="_personal_shop_order_status" name="_personal_shop_order_status" class="short" value="<?php echo esc_html( $personal_shop_order_status ); ?>">
		</p>
		<p class="form-field">
			<label for="_personal_shop_price_range"><?php echo __( 'Price range', 'woocommerce' ); ?></label>
			<input type="text" id="_personal_shop_price_range" name="_personal_shop_price_range" class="short" value="<?php echo esc_html( $personal_shop_price_range ); ?>" readonly>
		</p>
		<p class="form-field">
			<label for="_personal_shop_hudtilstand" class="myronja_meta_field_title"><?php echo __( 'Hudtilstand', 'woocommerce' ); ?></label>
			<textarea style="min-height: 200px" class="short" name="_personal_shop_hudtilstand" id="_personal_shop_hudtilstand" readonly><?php echo esc_html( $personal_shop_hudtilstand ); ?></textarea>
		</p>
		<p class="form-field">
			<label for="_personal_shop_user_goal" class="myronja_meta_field_title"><?php echo __( 'User\'s goal', 'woocommerce' ); ?></label>
			<textarea style="min-height: 200px" class="short" name="_personal_shop_user_goal" id="_personal_shop_user_goal" readonly><?php echo esc_html( $personal_shop_user_goal ); ?></textarea>
		</p>
		<p class="form-field">
			<label for="_personal_shop_hudtype" class="myronja_meta_field_title"><?php echo __( 'Hudtype', 'woocommerce' ); ?></label>
			<textarea style="min-height: 200px" class="short" name="_personal_shop_hudtype" id="_personal_shop_hudtype" readonly><?php echo esc_html( $personal_shop_hudtype ); ?></textarea>
		</p>
		<p class="form-field">
			<label for="_personal_shop_skin_experience" class="myronja_meta_field_title"><?php echo __( 'Skin experience', 'woocommerce' ); ?></label>
			<textarea style="min-height: 200px" class="short" name="_personal_shop_skin_experience" id="_personal_shop_skin_experience" readonly><?php echo esc_html( $personal_shop_skin_experience ); ?></textarea>
		</p>
		<p class="form-field">
			<label for="_personal_shop_produkt_type" class="myronja_meta_field_title"><?php echo __( 'Produkt type', 'woocommerce' ); ?></label>
			<textarea style="min-height: 200px" class="short" name="_personal_shop_produkt_type" id="_personal_shop_produkt_type" readonly><?php echo esc_html( $personal_shop_produkt_type ); ?></textarea>
		</p>
		<p class="form-field">
			<label for="_personal_shop_produkt_recommendation" class="myronja_meta_field_title"><?php echo __( 'Produkt type recommendation', 'woocommerce' ); ?></label>
			<textarea style="min-height: 200px" class="short" name="_personal_shop_produkt_recommendation" id="_personal_shop_produkt_recommendation" readonly><?php echo esc_html( $personal_shop_produkt_recommendation ); ?></textarea>
		</p>
		<p class="form-field">
			<label for="_personal_shop_ingredients" class="myronja_meta_field_title"><?php echo __( 'Ingredients', 'woocommerce' ); ?></label>
			<textarea style="min-height: 200px" class="short" name="_personal_shop_ingredients" id="_personal_shop_ingredients" readonly><?php echo esc_html( $personal_shop_ingredients ); ?></textarea>
		</p>
		<p class="form-field">
			<label for="_personal_shop_user_ingredients" class="myronja_meta_field_title"><?php echo __( 'User Ingredients', 'woocommerce' ); ?></label>
			<textarea style="min-height: 200px" class="short" name="_personal_shop_user_ingredients" id="_personal_shop_user_ingredients" readonly><?php echo esc_html( $personal_shop_user_ingredients ); ?></textarea>
		</p>
		<p class="form-field">
			<label for="_personal_shop_user_awareness" class="myronja_meta_field_title"><?php echo __( 'Things to know about user', 'woocommerce' ); ?></label>
			<textarea style="min-height: 200px" class="short" name="_personal_shop_user_awareness" id="_personal_shop_user_awareness" readonly><?php echo esc_html( $personal_shop_user_awareness ); ?></textarea>
		</p>
	</div>
	<?php
}

/**
 * Save post meta field data for personal shop post type.
 *
 * @param int $post_id Post id.
 * @return void
 */
function flatsome_child_personal_shop_meta( $post_id ) {

	if ( ! function_exists( 'get_current_screen' ) ) {
		require_once ABSPATH . '/wp-admin/includes/screen.php';
	}

	$current_screen = get_current_screen();

	if ( $current_screen && property_exists( $current_screen, 'post_type' ) && 'personal_shop' === $current_screen->post_type ) {

		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );

		$is_valid_nonce = false;

		if ( isset( $_POST['personal_shop_meta_box_nonce'] ) ) {
			if ( wp_verify_nonce( wp_unslash( $_POST['personal_shop_meta_box_nonce'] ), basename( __FILE__ ) ) ) {
				$is_valid_nonce = true;
			}
		}

		if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
			return;
		}

		// Personal shop order status.
		if ( array_key_exists( '_personal_shop_order_status', $_POST ) ) {

			update_post_meta(
				$post_id,
				'_personal_shop_order_status',
				sanitize_text_field( wp_unslash( $_POST['_personal_shop_order_status'] ) ),
			);
		}
	}
}
add_action( 'save_post', 'flatsome_child_personal_shop_meta' );

/**
 * Callback for product recommendation metabox.
 *
 * @return void
 */
function personal_shopper_display_product_recommend_metabox( $post ) {
	$post_id = $post->ID;
	?>
	<div class="personal_shopper_options_panel--seach">
		<p class="form-field">
			<label for="myronja-search-term"><?php echo __( 'Search products to recommend', 'woocommerce' ); ?></label>
			<div class="myronja-search-wrapper">
				<span class="myronja-search-icon dashicons dashicons-search"></span>
				<input type="text" id="myronja-search-term" name="" class="myronja-search-term" placeholder="Search..." autocomplete="off">
				<div class="myronja-search-results" id="myronja-search-results">
				</div>
			</div>
			<div class="myronja-products-display">
				<ul class="myronja-products-list">
					<h2><?php esc_html_e( 'Personal shop recommended products', 'woocommerce' ); ?></h2>
					<?php
					$products_ids = get_post_meta( $post_id, '_personal_shop_recommended_products_ids', false );

					if ( $products_ids && is_array( $products_ids ) && ! empty( $products_ids ) ) {
						foreach ( $products_ids as $key => $value ) {
							$product   = wc_get_product( $value );
							$image_src = wp_get_attachment_image_src( get_post_thumbnail_id( $value ), 'thumbnail' );
							?>
						<li class="myronja-products-item" id="product-item-<?php echo esc_html( $value ); ?>">
							<figure class="myronja-products-item__product-image"><img src="<?php echo esc_attr( $image_src[0] ); ?>"></figure>
							<p class="myronja-products-item__product-title"><?php echo esc_html( $product->get_name() ); ?></p>
							<p class="myronja-products-item__product-amount"><?php echo esc_html( get_post_meta( $value, '_myronja_product_quantity', true ) ); ?></p>
							<p class="myronja-products-item__product-price"><?php echo esc_html( $product->get_price() ); ?></p>
							<div class="myronja-products-item__action-wrapper">
								<a href="#" button class="myronja-remove-from-shop button button-primary" id="<?php echo esc_attr( 'myronja-remove-from-shop-' . $value ); ?>"
								data-product-id="<?php echo esc_html( $value ); ?>"
								data-product-thumbnail="<?php echo esc_attr( $image_src[0] ); ?>"
								data-product-title="<?php echo esc_html( $product->get_name() ); ?>"
								data-product-price="<?php echo esc_html( $product->get_price() ); ?>"
								data-product-amount="<?php echo esc_html( get_post_meta( $value, '_myronja_product_quantity', true ) ); ?>"
								data-product-action="remove-product">
									<?php esc_html_e( 'Remove Product', 'woocommerce' ); ?>
								</a>
							</div>
						</li>
							<?php
						}
					} else {
						?>
						<p><?php esc_html_e( 'Recommended products will be shown here', 'woocommerce' ); ?></p>
						<?php
					}
					?>
				</ul>
				</div>
		</p>
	</div>
		<?php
}

/**
 * Callback for admin comments metabox.
 *
 * @return void
 */
function personal_shopper_display_admin_comments_metabox( $post ) {
	$post_id                          = $post->ID;
	$personal_shop_comment_focus_area = get_post_meta( $post_id, '_personal_shop_comment_focus_area', true );
	$personal_shop_comment_skin_type  = get_post_meta( $post_id, '_personal_shop_comment_skin_type', true );
	$personal_shop_comment_general    = get_post_meta( $post_id, '_personal_shop_comment_general', true );

	wp_nonce_field( basename( __FILE__ ), 'personal_shop_admin_comments_metabox' );
	?>
	<div class="personal_shopper_options_panel">
		<p class="form-field">
			<label for="_personal_shop_comment_focus_area" class="myronja_meta_field_title"><?php echo __( 'På baggrund af fokusområde', 'woocommerce' ); ?></label>
			<textarea style="min-height: 200px" class="short" name="_personal_shop_comment_focus_area" id="_personal_shop_comment_focus_area"><?php echo esc_html( $personal_shop_comment_focus_area ); ?></textarea>
		</p>
		<p class="form-field">
			<label for="_personal_shop_comment_skin_type" class="myronja_meta_field_title"><?php echo __( 'På baggrund af hudtype', 'woocommerce' ); ?></label>
			<textarea style="min-height: 200px" class="short" name="_personal_shop_comment_skin_type" id="_personal_shop_comment_skin_type"><?php echo esc_html( $personal_shop_comment_skin_type ); ?></textarea>
		</p>
		<p class="form-field">
			<label for="_personal_shop_comment_general" class="myronja_meta_field_title"><?php echo __( 'Kommentar', 'woocommerce' ); ?></label>
			<textarea style="min-height: 200px" class="short" name="_personal_shop_comment_general" id="_personal_shop_comment_general"><?php echo esc_html( $personal_shop_comment_general ); ?></textarea>
		</p>
	</div>
	<?php
}

/**
 * Saves personal_shopper_display_admin_comments_metabox contents.
 *
 * @return void
 */
function personal_shopper_admin_comments_metabox( $post_id ) {

	if ( ! function_exists( 'get_current_screen' ) ) {
		require_once ABSPATH . '/wp-admin/includes/screen.php';
	}

	$current_screen = get_current_screen();

	if ( $current_screen && property_exists( $current_screen, 'post_type' ) && 'personal_shop' === $current_screen->post_type ) {

		$is_autosave    = wp_is_post_autosave( $post_id );
		$is_revision    = wp_is_post_revision( $post_id );
		$is_valid_nonce = false;

		if ( isset( $_POST['personal_shop_admin_comments_metabox'] ) ) {
			if ( wp_verify_nonce( wp_unslash( $_POST['personal_shop_admin_comments_metabox'] ), basename( __FILE__ ) ) ) {
				$is_valid_nonce = true;
			}
		}

		if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
			return;
		}

		// Comment on focus area.
		if ( array_key_exists( '_personal_shop_comment_focus_area', $_POST ) ) {

			update_post_meta(
				$post_id,
				'_personal_shop_comment_focus_area',
				sanitize_text_field( wp_unslash( $_POST['_personal_shop_comment_focus_area'] ) ),
			);
		}

		// Comment on skin type.
		if ( array_key_exists( '_personal_shop_comment_skin_type', $_POST ) ) {

			update_post_meta(
				$post_id,
				'_personal_shop_comment_skin_type',
				sanitize_text_field( wp_unslash( $_POST['_personal_shop_comment_skin_type'] ) ),
			);
		}

		// General comments.
		if ( array_key_exists( '_personal_shop_comment_general', $_POST ) ) {

			update_post_meta(
				$post_id,
				'_personal_shop_comment_general',
				sanitize_text_field( wp_unslash( $_POST['_personal_shop_comment_general'] ) ),
			);
		}
	}
}
add_action( 'save_post', 'personal_shopper_admin_comments_metabox' );


/**
 * Displays a button for creating order in external API given that
 * order staus must be 'processing.
 *
 * @param object $order_post Order object.
 *
 * @return void
 */
function external_order_display_metabox_content( $order_post ) {

	if ( 'wc-processing' === $order_post->post_status ) :
		$order_id = $order_post->ID;
		?>

		<a
		href="#"
		button class="myronja-create-external-order button button-primary"
		id="<?php echo esc_attr( 'myronja-create-order-' . $order_id ); ?>"
		style="display: block; text-align: center;"
		data-order-id="<?php echo esc_attr( $order_id ); ?>">
			<?php esc_html_e( 'Update this Order in External API', 'woocommerce' ); ?>
		</a>

		<?php else : ?>
			<p><?php esc_html_e( 'Order details updated External API', 'woocommerce' ); ?></p>
			<?php
	endif;
}
