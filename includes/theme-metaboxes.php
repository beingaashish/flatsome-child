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

	$post_id                     = $post->ID;
	$myronja_product_serial_no   = (int) get_post_meta( $post_id, '_myronja_product_serial_no', true );
	$myronja_product_quantity    = get_post_meta( $post_id, '_myronja_product_quantity', true );
	$myronja_product_ingredients = get_post_meta( $post_id, '_myronja_product_ingredients', true ); // INGREDIENSER.
	$myronja_product_application = get_post_meta( $post_id, '_myronja_product_application', true ); // ANVENDELSE.
	$myronja_product_evaluation  = get_post_meta( $post_id, '_myronja_product_evaluation', true ); // VORES VURDERING.

	wp_nonce_field( basename( __FILE__ ), 'flatsome_child_meta_box_nonce' );
	?>

	<div class="woocommerce_options_panel">
		<p class="form-field">
			<label for="_myronja_product_serial_no">Product's serial number</label>
			<input type="number" id="_myronja_product_serial_no" name="_myronja_product_serial_no" class="short" value="<?php echo esc_html( $myronja_product_serial_no ); ?>">
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
			<input type="text" id="_personal_shop_order_status" name="_personal_shop_order_status" class="short" value="<?php echo esc_html( $personal_shop_order_status ); ?>" readonly>
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
	// if ( ! ( 'personal_shop' === get_current_screen()->post_type ) ) {
	// 	return;
	// }

	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );

	$is_valid_nonce = false;

	if ( isset( $_POST['flatsome_child_meta_box_nonce'] ) ) {
		if ( wp_verify_nonce( wp_unslash( $_POST['personal_shop_meta_box_nonce'] ), basename( __FILE__ ) ) ) {
			$is_valid_nonce = true;
		}
	}

	if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
		return;
	}

	// Product serial number.
	if ( array_key_exists( '_personal_shop_order_number', $_POST ) ) {

		update_post_meta(
			$post_id,
			'_personal_shop_order_number',
			sanitize_text_field( wp_unslash( $_POST['_personal_shop_order_number'] ) ),
		);
	}
}
add_action( 'save_post', 'flatsome_child_personal_shop_meta' );

/**
 * Callback for product recommendation metabox.
 *
 * @return void
 */
function personal_shopper_display_product_recommend_metabox() {

}
