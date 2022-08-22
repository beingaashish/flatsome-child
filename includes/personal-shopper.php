<?php
/**
 * Child theme's personal-shopper.php file for adding personal shopper functionality.
 *
 * @package flatsome
 */

defined( 'ABSPATH' ) || exit;

/**
 * Outputs personal shopper button.
 *
 * @return void
 */
function myronja_personal_shopper_button_cb( $atts ) {

	if ( is_admin() ) {
		return;
	}

	ob_start();

	extract(
		shortcode_atts(
			array(

				'btn_text'    => esc_html__( 'Få gratis hjælp', 'woocommerce' ),
				'redirect_to' => 'personal-shopper-form',
				'btn_type'    => 'outline',
			),
			$atts
		)
	);

	if ( is_user_logged_in() ) {

		?>
	<a href="
		<?php
		echo esc_url(get_bloginfo( 'url' ) . '/' . $redirect_to);
		?>
		"
		class="<?php echo 'underline' === $btn_type ? 'button primary is-underline' : 'button success is-outline'; ?>">
		<span><?php echo wp_kses_post( $btn_text ); ?></span>
	</a>
		<?php
	} else {
		?>
		<a href="
			<?php
			echo esc_url(
				get_permalink( get_option( 'woocommerce_myaccount_page_id' ) )
			);
			?>
			" class="<?php echo 'underline' === $btn_type ? 'button primary is-underline' : 'button success is-outline'; ?>">
			<span><?php echo wp_kses_post( $btn_text ); ?></span>
		</a>
		<?php
	}
}
add_shortcode( 'myronja_personal_shopper_button', 'myronja_personal_shopper_button_cb' );

/**
 * Outputs Myronja Personal Shopper form.
 *
 * @return void
 */
function myronja_personal_shopper_form_cb() {
	if ( is_admin() ) {
		return;
	}

	if ( is_user_logged_in() ) {
		ob_start();
		?>
	<section class="myronja-personal-shopper-form-container">

		<form action="" method="post" class="myronja-multistep-form">

			<!-- Myronja multistep form step 1 wrapper div -->
			<div class="myronja-form-steps-card" data-step>
				<div class="img has-hover x md-x lg-x y md-y lg-y">
					<div class="img-inner dark">
						<img width="700" height="250" src="<?php echo esc_attr( get_stylesheet_directory_uri() . '/assets/images/focus.png' ); ?>" class="attachment-large size-large" alt="" loading="lazy" sizes="(max-width: 700px) 100vw, 700px">
					</div>
				</div>
				<div class="text myronja-form-step-header">
					<h5 class="uppercase">Hvad ønsker du at din hudpleje skal hjælpe dig med?</h5>
					<p>Vælg gerne mere end 1 da mange produkter kan have positive indvirkninger på flere områder.</p>
				</div>

				<!-- Lists Hudtilstand sub categories. -->
				<div class="myronja-hudtilstand-wrapper">
					<h1 class="section-title section-title-center"><b></b><span class="section-title-main" style="font-size:63%;">Hudtilstand</span><b></b></h1>
					<div class="row">
						<?php
						// Get Hudtilstand subcategories.
						$hudtilstand_cat      = get_term_by( 'slug', 'hudtilstand', 'product_cat' );
						$hudtilstand_sub_cats = get_categories(
							array(
								'parent'   => $hudtilstand_cat->term_id,
								'taxonomy' => 'product_cat',
							)
						);

						foreach ( $hudtilstand_sub_cats as $hudtilstand_sub_cat => $val ) {
							?>
						<div class="col medium-4 small-12 large-4">
							<div class="col-inner">
								<input type="checkbox" name="hudtilstand" id="<?php echo esc_attr( $val->term_id ); ?>" class="myronja-checkbox" value="<?php echo esc_attr( $val->term_id ); ?>">
								<label for="<?php echo esc_attr( $val->term_id ); ?>" class="myronja-label"><?php echo esc_html( $val->name ); ?></label>
							</div>
						</div>
							<?php
						}
						?>
					</div>
				</div>

				<div class="myronja-input-group">
						<label for="myronja-user-goals" class="myronja-label"><?php echo esc_html( 'Uddyb gerne dit mål med produkterne' ); ?></label>
						<textarea name="myronja-user-goals" id="myronja-user-goals" cols="30" rows="10" class="myronja-textarea"></textarea>
				</div>
				<div class="myronja-form-steps-card__footer-buttons">
					<button class="button primary is-underline" type="button" data-next>Next</button>
				</div>
			</div>

			<!-- Myronja multistep form step 2 wrapper div -->
			<div class="myronja-form-steps-card" data-step>
				<div class="img has-hover x md-x lg-x y md-y lg-y">
					<div class="img-inner dark">
						<img width="700" height="250" src="<?php echo esc_attr( get_stylesheet_directory_uri() . '/assets/images/hudtype.png' ); ?>" class="attachment-large size-large" alt="" loading="lazy" sizes="(max-width: 700px) 100vw, 700px">
					</div>
				</div>
				<div class="text myronja-form-step-header">
					<h5 class="uppercase">Hvordan vil du beskrive din hudtype?</h5>
					<p>Vælg den beskrivelse som passer bedst på din hud. I tvivl? du kan få hjælp her.</p>
				</div>

				<!-- Lists Hudtype sub categories. -->
				<div class="myronja-hudtype-wrapper">
					<h1 class="section-title section-title-center"><b></b><span class="section-title-main" style="font-size:63%;">HUDTYPE</span><b></b></h1>
					<div class="row">
						<?php
						// Get Hudtype subcategories.
						$hudtype_cat      = get_term_by( 'slug', 'hudtype', 'product_cat' );
						$hudtype_sub_cats = get_categories(
							array(
								'parent'   => $hudtype_cat->term_id,
								'taxonomy' => 'product_cat',
							)
						);

						foreach ( $hudtype_sub_cats as $hudtype_sub_cat => $val ) {
							?>
						<div class="col medium-4 small-12 large-4">
							<div class="col-inner">
								<input type="checkbox" value="<?php echo esc_attr( $val->term_id ); ?>" name="hudtype" id="<?php echo esc_attr( $val->term_id ); ?>" class="myronja-checkbox">
								<label for="<?php echo esc_attr( $val->term_id ); ?>" class="myronja-label"><?php echo esc_html( $val->name ); ?></label>
							</div>
						</div>
							<?php
						}
						?>
					</div>
				</div>

				<div class="myronja-input-group">
						<label for="myronja-user-skin-expierence" class="myronja-label">
							<?php esc_html_e( 'Uddyb gerne hvordan du oplever din hud.', 'woocommerce' ); ?>
						</label>
						<textarea name="myronja-user-skin-expierence" id="myronja-user-skin-expierence" cols="30" rows="10" class="myronja-textarea"></textarea>
				</div>
				<div class="myronja-form-steps-card__footer-buttons">
					<button type="button" data-previous>Previous</button>
					<button class="button primary is-underline" type="button" data-next>Next</button>
				</div>
			</div>

			<!-- Myronja multistep form step 3 wrapper div -->
			<div class="myronja-form-steps-card" data-step>
				<div class="img has-hover x md-x lg-x y md-y lg-y">
					<div class="img-inner dark">
						<img width="700" height="250" src="<?php echo esc_attr( get_stylesheet_directory_uri() . '/assets/images/ingredients.png' ); ?>" class="attachment-large size-large" alt="" loading="lazy" sizes="(max-width: 700px) 100vw, 700px">
					</div>
				</div>
				<div class="text myronja-form-step-header">
					<h5 class="uppercase">Bruger du i øjeblikket produkter til at behandle din hud?</h5>
					<p>For at sikre at de anbefalede produkter passer til din rutine, er det vigtigt for os vide om du bruger andre behandlingsprodukter.</p>
				</div>

				<!-- Lists Produkt Type sub categories. -->
				<div class="myronja-product-type-wrapper">
					<h1 class="section-title section-title-center"><b></b><span class="section-title-main" style="font-size:63%;">Produkt Type</span><b></b></h1>
					<div class="row">
						<?php
						// Get Product type subcategories.
						$product_type_cat      = get_term_by( 'slug', 'produkt-type', 'product_cat' );
						$product_type_sub_cats = get_categories(
							array(
								'parent'   => $product_type_cat->term_id,
								'taxonomy' => 'product_cat',
							)
						);

						foreach ( $product_type_sub_cats as $product_type_sub_cat => $val ) {
							?>
						<div class="col medium-4 small-12 large-4">
							<div class="col-inner">
								<input type="checkbox" name="produkttype" id="<?php echo esc_attr( $val->term_id ); ?>" class="myronja-checkbox" value="<?php echo esc_attr( $val->term_id ); ?>">
								<label for="<?php echo esc_attr( $val->term_id ); ?>" class="myronja-label"><?php echo esc_html( $val->name ); ?></label>
							</div>
						</div>
							<?php
						}
						?>
					</div>
				</div>

				<div class="myronja-input-group">
						<label for="myronja-product-type-recommendation" class="myronja-label">
							<?php esc_html_e( 'Hvilke produkttyper ønsker du indgår i din anbefaling?', 'woocommerce' ); ?>
						</label>
						<textarea name="myronja-product-type-recommendation" id="myronja-product-type-recommendation" cols="30" rows="10" class="myronja-textarea"></textarea>
				</div>
				<div class="myronja-form-steps-card__footer-buttons">
					<button type="button" data-previous>Previous</button>
					<button class="button primary is-underline" type="button" data-next=>Next</button>
				</div>
			</div>

			<!-- Myronja multistep form step 4 wrapper div -->
			<div class="myronja-form-steps-card" data-step>
				<div class="img has-hover x md-x lg-x y md-y lg-y">
					<div class="img-inner dark">
						<img width="700" height="250" src="<?php echo esc_attr( get_stylesheet_directory_uri() . '/assets/images/skinroutine.png' ); ?>" class="attachment-large size-large" alt="" loading="lazy" sizes="(max-width: 700px) 100vw, 700px">
					</div>
				</div>
				<div class="text myronja-form-step-header">
					<h5 class="uppercase">Bruger du nogle af disse aktive ingredienser i din hudpleje rutine?</h5>
					<br>
				</div>

				<!-- Lists Product Ingredients -->
				<div class="myronja-product-ingredients-wrapper">
					<h1 class="section-title section-title-center"><b></b><span class="section-title-main" style="font-size:63%;">Aktive Ingredienser</span><b></b></h1>
					<div class="row">
						<div class="col medium-4 small-12 large-4">
							<div class="col-inner">
								<input type="checkbox" name="ingredients" id="nej" class="myronja-checkbox">
								<label for="nej" class="myronja-label">Nej</label>
							</div>
						</div>
						<div class="col medium-4 small-12 large-4">
							<div class="col-inner">
								<input type="checkbox" name="ingredients" id="vitamin-c" value="vitamin-c" class="myronja-checkbox">
								<label for="vitamin-c" class="myronja-label">Vitamin C</label>
							</div>
						</div>
						<div class="col medium-4 small-12 large-4">
							<div class="col-inner">
								<input type="checkbox" name="ingredients" id="aha" value="aha" class="myronja-checkbox">
								<label for="aha" class="myronja-label">AHA</label>
							</div>
						</div>
						<div class="col medium-4 small-12 large-4">
							<div class="col-inner">
								<input type="checkbox" name="ingredients" id="bha" values="bha" class="myronja-checkbox">
								<label for="bha" class="myronja-label">BHA</label>
							</div>
						</div>
						<div class="col medium-4 small-12 large-4">
							<div class="col-inner">
								<input type="checkbox" name="ingredients" id="retinolds" value="retinolds" class="myronja-checkbox">
								<label for="retinolds" class="myronja-label">RETINOLDS</label>
							</div>
						</div>
						<div class="col medium-4 small-12 large-4">
							<div class="col-inner">
								<input type="checkbox" name="ingredients" id="det-ved-jeg-ikkie" value="det-ved-jeg-ikkie" class="myronja-checkbox">
								<label for="det-ved-jeg-ikkie" class="myronja-label">DET VED JEG IKKE</label>
							</div>
						</div>
					</div>
				</div>

				<div class="myronja-input-group">
						<label for="myronja-product-user-ingredients" class="myronja-label">
							<?php esc_html_e( 'Hvis ja, hvad er dine erfaringer?', 'woocommerce' ); ?>
						</label>
						<textarea name="myronja-product-user-ingredients" id="myronja-product-user-ingredients" cols="30" rows="10" class="myronja-textarea"></textarea>
				</div>
				<div class="myronja-form-steps-card__footer-buttons">
					<button type="button" data-previous>Previous</button>
					<button class="button primary is-underline" type="button" data-next>Next</button>
				</div>
			</div>

			<!-- Myronja multistep form step 5 wrapper div -->
			<div class="myronja-form-steps-card" data-step>
				<div class="img has-hover x md-x lg-x y md-y lg-y">
					<div class="img-inner dark">
						<img width="700" height="250" src="<?php echo esc_attr( get_stylesheet_directory_uri() . '/assets/images/obs.png' ); ?>" class="attachment-large size-large" alt="" loading="lazy" sizes="(max-width: 700px) 100vw, 700px">
					</div>
				</div>
				<div class="text myronja-form-step-header">
					<h5 class="uppercase">Noget vi skal være opmærksomme på?</h5>
					<p>For at sikre at dine produkter er så effektive som muligt, er det vigtigt for os at vide om du har nogle allergier, tager medicin eller har hudproblemer som vi skal tage hensyn til.</p>
				</div>

				<div class="myronja-input-group">
					<textarea name="myronja-product-user-awareness" id="myronja-product-user-awareness" cols="30" rows="10" class="myronja-textarea"></textarea>
				</div>
				<div class="myronja-form-steps-card__footer-buttons">
					<button type="button" data-previous>Previous</button>
					<button class="button primary is-underline" type="button" data-next>Next</button>
				</div>
			</div>


			<!-- Myronja multistep form step 6 wrapper div -->
			<div class="myronja-form-steps-card" data-step>
				<div class="img has-hover x md-x lg-x y md-y lg-y">
					<div class="img-inner dark">
						<img width="700" height="250" src="<?php echo esc_attr( get_stylesheet_directory_uri() . '/assets/images/price.png' ); ?>" class="attachment-large size-large" alt="" loading="lazy" sizes="(max-width: 700px) 100vw, 700px">
					</div>
				</div>
				<div class="text myronja-form-step-header">
					<h5 class="uppercase">Vælg dit budget</h5>
					<p>Vi sammensætter en pakke med anbefalet produkter ud fra dit budget. Vi glæder os til at modtage dine besvarelser!</p>
				</div>

				<!-- Lists Budget price range wrapper. -->
				<div class="myronja-price-range-wrapper">
					<h1 class="section-title section-title-center"><b></b><span class="section-title-main" style="font-size:63%;">prisklasse</span><b></b></h1>
					<div class="row">
						<div class="col medium-4 small-12 large-4">
							<div class="col-inner">
								<input type="radio" name="pricerange" id="low" value="low" class="myronja-radio">
								<label for="low" class="myronja-label">300-500 kr</label>
							</div>
						</div>
						<div class="col medium-4 small-12 large-4">
							<div class="col-inner">
								<input type="radio" name="pricerange" id="mid" value="mid" class="myronja-radio" checked>
								<label for="mid" class="myronja-label">500-1000 kr</label>
							</div>
						</div>
						<div class="col medium-4 small-12 large-4">
							<div class="col-inner">
								<input type="radio" name="pricerange" value="high" id="high" class="myronja-radio">
								<label for="high" class="myronja-label">1000 &plus; kr</label>
							</div>
						</div>
					</div>
				</div>
				<div class="myronja-form-steps-card__footer-buttons">
					<button type="button" data-previous>Previous</button>
					<button class="button primary is-underline" type="button" data-next>Next</button>
				</div>
			</div>

			<!-- Myronja multistep form step 7 wrapper div -->
						<div class="myronja-form-steps-card" data-step>
				<div class="img has-hover x md-x lg-x y md-y lg-y">
					<div class="img-inner dark">
						<img width="700" height="250" src="<?php echo esc_attr( get_stylesheet_directory_uri() . '/assets/images/thank_you.png' ); ?>" class="attachment-large size-large" alt="" loading="lazy" sizes="(max-width: 700px) 100vw, 700px">
					</div>
				</div>
				<div class="text myronja-form-step-header">
					<h5 class="uppercase">Vil du modtage mails med indhold, der hjælper dig til en bedre og sundere hud?</h5>
					<p>Vi giver dig de bedste hudplejetips fra vores eksperter, lærer dig mere om ingredienser og giver dig besked med spændende events. Du kan selvfølgelig afmelde dig til hver en tid.</p>
				</div>

				<!-- Myronja sent mail request -->
				<div class="myronja-mail-request-wrapper">
					<div class="row">
						<div class="col medium-6 small-12 large-6">
							<div class="col-inner">
								<input type="radio" name="send-mail" id="yes" value="yes" class="myronja-radio" checked>
								<label for="yes" class="myronja-label">JA TAK!</label>
							</div>
						</div>
						<div class="col medium-6 small-12 large-6">
							<div class="col-inner">
								<input type="radio" name="send-mail" id="no" value="no" class="myronja-radio">
								<label for="no" class="myronja-label">NEJ</label>
							</div>
						</div>
					</div>
				</div>

				<div class="myronja-form-steps-card__footer-buttons">
					<button type="button" data-previous>Previous</button>
					<button class="button primary is-underline" type="button" data-next>Next</button>
				</div>
			</div>

			<!-- Myronja multistep form step 8 wrapper div -->
			<div class="myronja-form-steps-card" data-step>
				<div class="img has-hover x md-x lg-x y md-y lg-y">
					<div class="img-inner dark">
						<img width="700" height="250" src="<?php echo esc_attr( get_stylesheet_directory_uri() . '/assets/images/ps-banner.jpg' ); ?>" class="attachment-large size-large" alt="" loading="lazy" sizes="(max-width: 700px) 100vw, 700px">
					</div>
				</div>
				<div class="text myronja-form-step-header">
					<h5 class="uppercase">Tusinde tak!</h5>
					<p>Vi sætter stor pris på den tillid som du viser os og for at betro os med opgaven. Er du tilfreds med dine besvarelser kan du trykke godkend.</p>
				</div>

				<div class="myronja-form-steps-card__footer-buttons myronja-form-steps-card__footer-buttons--submit">
					<button type="button" data-previous>Previous</button>
					<button class="button primary is-underline" type="submit" name="personal-shopper-submit" id="personal-shopper-submit">Approve ⟶</button>
				</div>
				<div class="myronja-form-steps__error-message">
					<p></p>
				</div>
			</div>

		</form>
	</section>

		<?php
	} else {
		// Load 404 template content.
	}
}
add_shortcode( 'myronja_personal_shopper_form', 'myronja_personal_shopper_form_cb' );

/**
 * Parameters for flatsome-child-personal-shopper-script.js file
 *
 * @return array
 */
function personal_shopper_create_post_params() {
	$arr['ajaxUrl']              = admin_url( 'admin-ajax.php' );
	$arr['personalShopperNonce'] = wp_create_nonce( 'flatsome_child_personal_shopper_nonce' );
	$arr['myAccountUrl']         = get_permalink( wc_get_page_id( 'myaccount' ) );

	return $arr;
}
add_filter( 'personal_shopper_create_post_params', 'personal_shopper_create_post_params' );

/**
 * Handles Ajax request for creating a new Personal Shopper Post.
 *
 * @return void
 */
function personal_shopper_create_post() {

	if ( ! is_user_logged_in() ) {
		return;
	}

	if ( check_ajax_referer( 'flatsome_child_personal_shopper_nonce', 'nonce' ) ) {

		$form_fields_data = isset( $_POST['formFields'] ) ? json_decode( sanitize_text_field( wp_json_encode( $_POST['formFields'] ) ), true ) : array();

		$new_personal_shopper_post = array(
			'post_type'   => 'personal_shop',
			'post_status' => 'publish',
			'post_title'  => __( 'Personal User Field Details', 'woocommerce' ),
		);

		// Insert new post.
		$post_id = wp_insert_post( $new_personal_shopper_post, true );

		// Get inserted post id and update personal shopper post meta.
		if ( $post_id ) {

			$price_range_arr  = array(
				'low'  => '300-500 kr',
				'mid'  => '500-1000 kr',
				'high' => '1000 + kr',
			);
			$current_user     = wp_get_current_user();
			$first_name       = $current_user->user_firstname;
			$last_name        = $current_user->user_lastname;
			$user_name        = $first_name . ' ' . $last_name;
			$order_no         = get_current_user_id() . '-' . $post_id;
			$order_date       = get_the_date( 'l F j, Y', $post_id );
			$price_range      = '';
			$hudtilstand      = '';
			$hudtilstand_arr  = $form_fields_data['hudtilstand'];
			$hudtilstand_cats = array();
			$hudtype          = '';
			$hudtype_arr      = $form_fields_data['hudtype'];
			$hudtype_cats     = array();
			$produkttype      = '';
			$produkttype_arr  = $form_fields_data['produkttype'];
			$produkttype_cats = array();
			$ingredients      = '';
			$ingredients_arr  = $form_fields_data['ingredients'];

			if ( $form_fields_data['pricerange'] ) {
				$price_range = $price_range_arr[ $form_fields_data['pricerange'] ] . '';
			}

			if ( ! empty( $hudtilstand_arr ) ) {
				foreach ( $hudtilstand_arr as $key => $value ) {
					$term = get_term_by( 'id', $value, 'product_cat' );
					if ( $term ) {
						array_push( $hudtilstand_cats, $term->name );
					}
				}
				$hudtilstand = implode( ', ', $hudtilstand_cats );

				// Add Hudtilstand ids in meta for better search.
				update_post_meta(
					$post_id,
					'_personal_shop_hudtilstand_ids',
					$hudtilstand_arr
				);
			}

			if ( ! empty( $hudtype_arr ) ) {
				foreach ( $hudtype_arr as $key => $value ) {
					$term = get_term_by( 'id', $value, 'product_cat' );
					if ( $term ) {
						array_push( $hudtype_cats, $term->name );
					}
				}
				$hudtype = implode( ', ', $hudtype_cats );

				// Add Hudtype ids in meta for better search.
				update_post_meta(
					$post_id,
					'_personal_shop_hudtype_ids',
					$hudtype_arr
				);
			}

			if ( ! empty( $produkttype_arr ) ) {
				foreach ( $produkttype_arr as $key => $value ) {
					$term = get_term_by( 'id', $value, 'product_cat' );
					if ( $term ) {
						array_push( $produkttype_cats, $term->name );
					}
				}
				$produkttype = implode( ', ', $produkttype_cats );

				// Add Produkttype ids in meta for better search.
				update_post_meta(
					$post_id,
					'_personal_shop_produkttype_ids',
					$produkttype_arr
				);
			}

			if ( ! empty( $ingredients_arr ) ) {
				$ingredients = implode( ', ', $ingredients_arr );
			}

			// Update personal shop order number.
			update_post_meta(
				$post_id,
				'_personal_shop_order_number',
				$order_no
			);

			// Update personal shop order date.
			update_post_meta(
				$post_id,
				'_personal_shop_order_date',
				$order_date
			);

			// Update personal shop user name.
			update_post_meta(
				$post_id,
				'_personal_shop_user_name',
				$user_name
			);

			// Update personal shop order status.
			update_post_meta(
				$post_id,
				'_personal_shop_order_status',
				__( 'received', 'woocommerce' )
			);

			// Update price range.
			update_post_meta(
				$post_id,
				'_personal_shop_price_range',
				$price_range
			);

			// Update hudtilstand.
			update_post_meta(
				$post_id,
				'_personal_shop_hudtilstand',
				$hudtilstand
			);

			// Update user goals.
			update_post_meta(
				$post_id,
				'_personal_shop_user_goal',
				$form_fields_data['userGoals']
			);

			// Update hudtype.
			update_post_meta(
				$post_id,
				'_personal_shop_hudtype',
				$hudtype
			);

			// Skin experience.
			update_post_meta(
				$post_id,
				'_personal_shop_skin_experience',
				$form_fields_data['userExpierence']
			);

			// Update produkttype.
			update_post_meta(
				$post_id,
				'_personal_shop_produkt_type',
				$produkttype
			);

			// Update produkt type recommendation.
			update_post_meta(
				$post_id,
				'_personal_shop_produkt_recommendation',
				$form_fields_data['userRecommendation']
			);

			// Update Ingredients.
			update_post_meta(
				$post_id,
				'_personal_shop_ingredients',
				$ingredients
			);

			// Update Ingredients preference.
			update_post_meta(
				$post_id,
				'_personal_shop_user_ingredients',
				$form_fields_data['ingredientsPreference']
			);

			// Update User awareness.
			update_post_meta(
				$post_id,
				'_personal_shop_user_awareness',
				$form_fields_data['userAwareness']
			);
		}
	}
}
add_action( 'wp_ajax_personal_shopper_create_post', 'personal_shopper_create_post' );
add_action( 'wp_ajax_nopriv_personal_shopper_create_post', 'personal_shopper_create_post' );

/**
 * Fires when admin adds recommendation products for user in personal shop.
 *
 * @return void
 */
function personal_shopper_add_user_products_to_meta() {
	if ( ! is_user_logged_in() ) {
		return;
	}

	if ( check_ajax_referer( 'flatsome_child_add_shop_product_nonce', 'nonce' ) ) {
		$product_action        = isset( $_POST['productAction'] ) ? json_decode( sanitize_text_field( wp_json_encode( $_POST['productAction'] ) ), true ) : '';
		$personal_shop_post_id = isset( $_POST['personalShopPostID'] ) ? json_decode( sanitize_text_field( wp_json_encode( $_POST['personalShopPostID'] ) ), true ) : '';
		$product_id            = isset( $_POST['productId'] ) ? json_decode( sanitize_text_field( wp_json_encode( $_POST['productId'] ) ), true ) : '';
		$product_ids           = get_post_meta( $personal_shop_post_id, '_personal_shop_recommended_products_ids', false );

		if ( 'add-product' == $product_action ) {

			if ( ! empty( $product_ids ) && ! in_array( $product_id, $product_ids ) ) {
					add_post_meta(
						$personal_shop_post_id,
						'_personal_shop_recommended_products_ids',
						$product_id,
					);
			}

			if ( empty( $product_ids ) ) {
				add_post_meta(
					$personal_shop_post_id,
					'_personal_shop_recommended_products_ids',
					$product_id,
				);
			}
		} elseif ( 'remove-product' == $product_action ) {
			if ( ! empty( $product_ids ) && in_array( $product_id, $product_ids ) ) {
				delete_post_meta(
					$personal_shop_post_id,
					'_personal_shop_recommended_products_ids',
					$product_id,
				);
			}
		}
	}
}
add_action( 'wp_ajax_personal_shopper_add_user_products_to_meta', 'personal_shopper_add_user_products_to_meta' );
add_action( 'wp_ajax_nopriv_personal_shopper_add_user_products_to_meta', 'personal_shopper_add_user_products_to_meta' );

/** Code for adding Personal Shop in My Account Page. */
/**
 * Register New Endpoint.
 *
 * @return void.
 */
function personal_shopper_endpoint() {
	add_rewrite_endpoint( 'myronja-myaccount-personal-shopper', EP_ROOT | EP_PAGES );
}
add_action( 'init', 'personal_shopper_endpoint' );

/**
 * Add new query var.
 *
 * @param array $vars vars.
 *
 * @return array An array of items.
 */
function personal_shopper_query_vars( $vars ) {
	$vars[] = 'myronja-myaccount-personal-shopper';
	return $vars;
}
add_filter( 'query_vars', 'personal_shopper_query_vars' );

/**
 * Add Personal Shop in my account page.
 *
 * @param array $items myaccount Items.
 *
 * @return array Items including Personal Shop.
 */
function myronja_myaccount_new_item_tab( $items ) {
	$items['myronja-myaccount-personal-shopper'] = 'Personal Shopper';
	return $items;
}
add_filter( 'woocommerce_account_menu_items', 'myronja_myaccount_new_item_tab' );

/**
 * Add content to the new tab.
 *
 * @return  string.
 */
function myronja_myaccount_personal_shopper_content() {
	?>
	<h1><?php esc_html_e( 'Personal Shop Orders', 'woocommerce' ); ?></h1>
	<ul class="myronja-myaccount-personal-shop-table-headings">
		<li class="myronja-myaccount-personal-shop-table-heading">
			<div class="myronja-myaccount-personal-shop-table-heading__order-number">
			<?php esc_html_e( 'Ordre Nr', 'woocommerce' ); ?>
			</div>
			<div class="myronja-myaccount-personal-shop-table-heading__order-date">
			<?php esc_html_e( 'Ordre Dato', 'woocommerce' ); ?>
			</div>
			<div class="myronja-myaccount-personal-shop-table-heading__order-status">
			<?php esc_html_e( 'Status', 'woocommerce' ); ?>
			</div>
			<div class="myronja-myaccount-personal-shop-table-heading__price-range">
			<?php esc_html_e( 'Pakke', 'woocommerce' ); ?>
			</div>
		</li>
	</ul>
	<ul class="myronja-myaccount-personal-shop-list">
		<?php

		$args = array(
			'post_type' => 'personal_shop',
			'order'     => 'ASC',
			'author'    => get_current_user_id(),
		);

		$the_query = new WP_Query( $args );

		if ( $the_query->have_posts() ) :

			while ( $the_query->have_posts() ) :
				$the_query->the_post();

				$post_id = get_the_ID();

				$order_number = get_post_meta( $post_id, '_personal_shop_order_number', true );
				$order_date   = get_post_meta( $post_id, '_personal_shop_order_date', true );
				$order_status = get_post_meta( $post_id, '_personal_shop_order_status', true );
				$order_price  = get_post_meta( $post_id, '_personal_shop_price_range', true );
				?>

				<li class="myronja-myaccount-personal-shop-item">
					<div class="myronja-myaccount-personal-shop-item__order-number">
						<a href="<?php the_permalink(); ?>"><?php echo esc_html( $order_number ); ?></a>
					</div>
					<div class="myronja-myaccount-personal-shop-item__order-date">
						<?php echo esc_html( $order_date ); ?>
					</div>
					<div class="myronja-myaccount-personal-shop-item__order-status">
						<?php echo esc_html( $order_status ); ?>
					</div>
					<div class="myronja-myaccount-personal-shop-item__price-range">
					<?php echo esc_html( $order_price ); ?>
					</div>
				</li>
				<?php
				endwhile;
				wp_reset_postdata();
		else :
		endif;
		?>

	</ul>
	<?php
}
add_action( 'woocommerce_account_myronja-myaccount-personal-shopper_endpoint', 'myronja_myaccount_personal_shopper_content' );


/**
 * Parameters for flatsome-child-personal-shopper-myaccount-script.js file
 *
 * @return array
 */
function personal_shopper_myaccount_params() {
	$arr['ajaxUrl']                = admin_url( 'admin-ajax.php' );
	$arr['personalMyAccountNonce'] = wp_create_nonce( 'flatsome_child_personal_myaccount_nonce' );

	return $arr;
}
add_filter( 'personal_shopper_myaccount_params', 'personal_shopper_myaccount_params' );


/**
 * Undocumented function.
 *
 * @return void
 */
function personal_shopper_myaccount () {
	if ( ! is_user_logged_in() || ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	if ( check_ajax_referer( 'flatsome_child_personal_myaccount_nonce', 'nonce' ) ) {
		global $woocommerce;

		$form_fields_data = isset( $_POST['formFields'] ) ? json_decode( sanitize_text_field( wp_json_encode( $_POST['formFields'] ) ), true ) : array();
		foreach ( $form_fields_data as $product_id => $qty ) {

			// Validate.
			$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $qty );
			$product_status    = get_post_status( $product_id );

			if ( $passed_validation && false !== $woocommerce->cart->add_to_cart( $product_id, $qty ) && 'publish' === $product_status ) {

			}
		}
	}
}
add_action( 'wp_ajax_personal_shopper_myaccount', 'personal_shopper_myaccount' );
add_action( 'wp_ajax_nopriv_personal_shopper_myaccount', 'personal_shopper_myaccount' );
