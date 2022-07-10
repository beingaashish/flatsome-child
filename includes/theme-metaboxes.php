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

		add_meta_box(
			'flatsome_child_meta_box',
			__( 'Myronja product\'s additional information', 'flatsome' ),
			'flatsome_child_display_metabox',
			'product',
		);

}
add_action( 'add_meta_boxes', 'flatsome_child_add_meta_box' );

/**
 * Functions for adding metafields in metabox.
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
			<textarea style="min-height: 200px" class="short" name="_myronja_product_ingredients" id="_myronja_product_ingredients"><?php echo esc_html( $myronja_product_ingredients, 'woocommerce' )?></textarea>
		</p>
		<p class="form-field">
			<label for="_myronja_product_application" class="myronja_meta_field_title">Product's application (ANVENDELSE)</label>
			<textarea style="min-height: 200px" class="short" name="_myronja_product_application" id="_myronja_product_application"><?php echo esc_html( $myronja_product_application, 'woocommerce' )?></textarea>
		</p>
		<p class="form-field">
			<label for="_myronja_product_evaluation" class="myronja_meta_field_title">Product's company take (VORES VURDERING)</label>
			<textarea style="min-height: 200px" class="short" name="_myronja_product_evaluation" id="_myronja_product_evaluation"><?php echo esc_html( $myronja_product_evaluation, 'woocommerce' )?></textarea>
		</p>
	</div>

	<?php
}

/**
 * Save post meta field data.
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
