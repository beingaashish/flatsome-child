<?php
/**
 * The blog template file.
 *
 * @package flatsome
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_user_logged_in() ) {
	return;
}

get_header();

// Pesonal Shop Post data.
$personal_shop_id   = get_the_ID();
$product_ids        = get_post_meta( $personal_shop_id, '_personal_shop_recommended_products_ids', false );
$comment_focus_area = get_post_meta( $personal_shop_id, '_personal_shop_comment_focus_area', true );
$comment_skin_type  = get_post_meta( $personal_shop_id, '_personal_shop_comment_skin_type', true );
$comment_general    = get_post_meta( $personal_shop_id, '_personal_shop_comment_general', true );
?>

<div id="content" class="container blog-wrapper blog-single page-wrapper personal-shop-wrapper">
<?php
if ( ! empty( $product_ids ) ) :
	?>
	<form action="" class="personal-shop_cart-form">
		<div class="personal-shop-cart_wrap">
		<table class="personal-shop-cart_table" cellspacing="0">
		<thead>
			<tr>
				<th class="personal-shop__product-select"><?php esc_html_e( 'Vælg', 'woocommerce' ); ?></th>
				<th class="personal-shop__product-name" colspan="2"><?php esc_html_e( 'Produkt navn', 'woocommerce' ); ?></th>
				<th class="personal-shop__product-quantity"><?php esc_html_e( 'Antal', 'woocommerce' ); ?></th>
				<th class="personal-shop__product-subtotal"><?php esc_html_e( 'SubTotal', 'woocommerce' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ( $product_ids as $key => $value ) {
				$personal_shop_product           = wc_get_product( $value );
				$perosnal_shop_product_image_src = wp_get_attachment_image_src( get_post_thumbnail_id( $value ), 'thumbnail' )
				?>
			<tr class="personal-shop__product" id="product-number-<?php echo esc_attr( $value );?>" data-product-price="<?php echo esc_html( $personal_shop_product->get_regular_price() ); ?>">
				<td class="personal-shop__product-select">
					<input type="checkbox" name="personal-shop-product" id="<?php echo esc_attr( $value ); ?>" class="myronja-checkbox" value="<?php echo esc_attr( $value ); ?>" data-product-quantity="1">
					<label for="<?php echo esc_attr( $value ); ?>" class="myronja-label"></label>
				</td>
				<td class="personal-shop__product-thumbnail">
				<figure class="personal-shop__product-image"><img src="<?php echo esc_attr( $perosnal_shop_product_image_src[0] ); ?>"></figure>
				</td>
				<td class="personal-shop__product-name">
					<p><?php echo esc_html( $personal_shop_product->get_title() ); ?></p>
					<div class="show-for-small mobile-product-price">
						<span class="mobile-product-price__qty"><span class="quantity">1</span> x </span>
						<span class="woocommerce-Price-amount amount">
							<span class="amount-currency-symbol"></span>
							<span class="amount-number"><?php echo esc_html( $personal_shop_product->get_regular_price() ); ?></span>
						</span>
					</div>
				</td>
				<td class="product-quantity">
					<div class="quantity buttons_added">
						<input type="button" value="-" class="minus button is-form" data-product-id="<?php echo esc_attr( $value ); ?>"><label class="screen-reader-text" for="product-<?php echo esc_html( $value ); ?>-amount"></label>
						<input type="number" name="personal-shop-product-qty" id="product-amount-<?php echo esc_html( $value ); ?>" class="input-text qty text" step="1" min="1" max="<?php echo esc_html( $personal_shop_product->get_stock_quantity() ); ?>" name="" value="1" title="Stk." size="4" placeholder="" inputmode="numeric">
						<input type="button" value="+" class="plus button is-form" data-product-id="<?php echo esc_attr( $value ); ?>">
					</div>
				</td>
				<td class="personal-shop__product-subtotal">
					<span class="woocommerce-Price-amount amount"><span class="amount-currency-symbol"></span><span class="amount-number"><?php echo esc_html( $personal_shop_product->get_regular_price() ); ?></span></span>
				</td>
			</tr>
				<?php
			}
			?>
			<tr>
				<td class="actions clear" colspan="5">
					<button type="submit" id="personal-shop_form-submit-btn" class="button primary is-outline pull-right personal-shop_form-submit-btn"><?php esc_html_e( 'Tilføj valgte produkter til kurv &#10230;', 'woocommerce' ); ?></button>
				</td>
			</tr>
		</tbody>
		</table>
		</div>
	</form>
	<?php if ( $comment_focus_area ) : ?>
	<section class="personal-shop-recommendation">
			<h2><?php esc_html_e( 'På baggrund af fokusområde', 'woocommerce' ); ?></h2>
			<textarea style="min-height: 200px" class="short" name="" id="" readonly><?php echo esc_html( $comment_focus_area ); ?></textarea>
	</section>
	<?php endif; ?>
	<?php if ( $comment_skin_type ) : ?>
	<section class="personal-shop-recommendation">
			<h2><?php esc_html_e( 'På baggrund af hudtype', 'woocommerce' ); ?></h2>
			<textarea style="min-height: 200px" class="short" name="" id="" readonly><?php echo esc_html( $comment_skin_type ); ?></textarea>
	</section>
	<?php endif; ?>
	<?php if ( $comment_general ) : ?>
	<section class="personal-shop-recommendation">
			<h2><?php esc_html_e( 'Kommentar', 'woocommerce' ); ?></h2>
			<textarea style="min-height: 200px" class="short" name="" id="" readonly><?php echo esc_html( $comment_general ); ?></textarea>
	</section>
	<?php endif; ?>

	<div class="personal-shop-user-answers__toggle">
		<p><?php esc_html_e( 'Ønsker du at se dine besvarelser kan du trykke på "Vis besvarelse" herunder"', 'woocommerce' ); ?></p>
		<a href="#" class="answer-toggle-button" id="answer-toggle-button"><?php esc_html_e( 'Vis besvarelse', 'woocommerce' ); ?></a>
	</div>
	<section class="personal-shop-user-answers">
		<div class="answer-section">
			<p>Hvad ønsker du at din hudpleje skal hjælpe dig med?</p>
			<div class="row">
				<?php
					$personal_shop_hudtilstand = get_post_meta( $personal_shop_id, '_personal_shop_hudtilstand', true );
					$personal_shop_user_goal   = get_post_meta( $personal_shop_id, '_personal_shop_user_goal', true );

				if ( $personal_shop_hudtilstand ) {
					$personal_shop_hudtilstand_arr = explode( ',', $personal_shop_hudtilstand );

					foreach ( $personal_shop_hudtilstand_arr as $key => $val ) {
						?>
				<div class="col medium-4 small-12 large-4">
					<div class="col-inner">
						<input type="checkbox" name="hudtilstand" id="<?php echo esc_attr( $val ); ?>" class="myronja-checkbox" value="<?php echo esc_attr( $val ); ?>" disabled checked>
						<label for="<?php echo esc_attr( $val ); ?>" class="myronja-label"><?php echo esc_html( $val ); ?></label>
					</div>
				</div>
							<?php
					}
				}
				?>
				</div>
		</div>
		<div class="answer-section">
			<p><?php esc_html_e( 'Uddyb gerne dit mål med produkterne.', 'woocommerce' ); ?></p>
			<textarea style="min-height: 200px" class="short" name="_personal_shop_user_goal" id="_personal_shop_user_goal" readonly><?php echo esc_html( $personal_shop_user_goal ); ?></textarea>
		</div>
		<div class="answer-section">
			<p>Hvordan vil du beskrive din hudtype?</p>
			<div class="row">
				<?php
					$personal_shop_hudtype         = get_post_meta( $personal_shop_id, '_personal_shop_hudtype', true );
					$personal_shop_skin_experience = get_post_meta( $personal_shop_id, '_personal_shop_skin_experience', true );

				if ( $personal_shop_hudtype ) {
					$personal_shop_hudtype_arr = explode( ',', $personal_shop_hudtype );

					foreach ( $personal_shop_hudtype_arr as $key => $val ) {
						?>
				<div class="col medium-4 small-12 large-4">
					<div class="col-inner">
						<input type="checkbox" name="hudtype" id="<?php echo esc_attr( $val ); ?>" class="myronja-checkbox" value="<?php echo esc_attr( $val ); ?>" disabled checked>
						<label for="<?php echo esc_attr( $val ); ?>" class="myronja-label"><?php echo esc_html( $val ); ?></label>
					</div>
				</div>
							<?php
					}
				}
				?>
				</div>
		</div>
		<div class="answer-section">
			<p><?php esc_html_e( 'Uddyb gerne hvordan du oplever din hud.', 'woocommerce' ); ?></p>
			<textarea style="min-height: 200px" class="short" name="_personal_shop_skin_experience" id="_personal_shop_skin_experience" readonly><?php echo esc_html( $personal_shop_skin_experience ); ?></textarea>
		</div>
		<div class="answer-section">
			<p>Bruger du i øjeblikket produkter til at behandle din hud?</p>
			<div class="row">
				<?php
				$personal_shop_produkt_type           = get_post_meta( $personal_shop_id, '_personal_shop_produkt_type', true );
				$personal_shop_produkt_recommendation = get_post_meta( $personal_shop_id, '_personal_shop_produkt_recommendation', true );

				if ( $personal_shop_produkt_type ) {
					$personal_shop_produkt_type_arr = explode( ',', $personal_shop_produkt_type );

					foreach ( $personal_shop_produkt_type_arr as $key => $val ) {
						?>
				<div class="col medium-4 small-12 large-4">
					<div class="col-inner">
						<input type="checkbox" name="hudtype" id="<?php echo esc_attr( $val ); ?>" class="myronja-checkbox" value="<?php echo esc_attr( $val ); ?>" disabled checked>
						<label for="<?php echo esc_attr( $val ); ?>" class="myronja-label"><?php echo esc_html( $val ); ?></label>
					</div>
				</div>
							<?php
					}
				}
				?>
				</div>
		</div>
		<div class="answer-section">
			<p><?php esc_html_e( 'Hvilke produkttyper ønsker du indgår i din anbefaling?', 'woocommerce' ); ?></p>
			<textarea style="min-height: 200px" class="short" name="_personal_shop_produkt_recommendation" id="_personal_shop_produkt_recommendation" readonly><?php echo esc_html( $personal_shop_produkt_recommendation ); ?></textarea>
		</div>
		<div class="answer-section">
			<p>Bruger du nogle af disse aktive ingredienser i din hudpleje rutine?</p>
			<div class="row">
				<?php
				$personal_shop_ingredients      = get_post_meta( $personal_shop_id, '_personal_shop_ingredients', true );
				$personal_shop_user_ingredients = get_post_meta( $personal_shop_id, '_personal_shop_user_ingredients', true );

				if ( $personal_shop_ingredients ) {
					$personal_shop_ingredients_arr = explode( ',', $personal_shop_ingredients );

					foreach ( $personal_shop_ingredients_arr as $key => $val ) {
						?>
				<div class="col medium-4 small-12 large-4">
					<div class="col-inner">
						<input type="checkbox" name="hudtype" id="<?php echo esc_attr( $val ); ?>" class="myronja-checkbox" value="<?php echo esc_attr( $val ); ?>" disabled checked>
						<label for="<?php echo esc_attr( $val ); ?>" class="myronja-label"><?php echo esc_html( $val ); ?></label>
					</div>
				</div>
							<?php
					}
				}
				?>
				</div>
		</div>
		<div class="answer-section">
			<p><?php esc_html_e( 'Hvis ja, hvad er dine erfaringer?', 'woocommerce' ); ?></p>
			<textarea style="min-height: 200px" class="short" name="_personal_shop_user_ingredients" id="_personal_shop_user_ingredients" readonly><?php echo esc_html( $personal_shop_user_ingredients ); ?></textarea>
		</div>
		<div class="answer-section">
			<p>Vælg dit budget</p>
			<div class="row">
				<?php
				$personal_shop_price_range    = get_post_meta( $personal_shop_id, '_personal_shop_price_range', true );
				$personal_shop_user_awareness = get_post_meta( $personal_shop_id, '_personal_shop_user_awareness', true );

				if ( $personal_shop_price_range ) {
					$personal_shop_price_range_arr = explode( ',', $personal_shop_price_range );

					foreach ( $personal_shop_price_range_arr as $key => $val ) {
						?>
				<div class="col medium-4 small-12 large-4">
					<div class="col-inner">
						<input type="checkbox" name="hudtype" id="<?php echo esc_attr( $val ); ?>" class="myronja-checkbox" value="<?php echo esc_attr( $val ); ?>" disabled checked>
						<label for="<?php echo esc_attr( $val ); ?>" class="myronja-label"><?php echo esc_html( $val ); ?></label>
					</div>
				</div>
							<?php
					}
				}
				?>
				</div>
		</div>
		<div class="answer-section">
			<p><?php esc_html_e( 'Noget vi skal være opmærksomme på?', 'woocommerce' ); ?></p>
			<textarea style="min-height: 200px" class="short" name="_personal_shop_user_awareness" id="_personal_shop_user_awareness" readonly><?php echo esc_html( $personal_shop_user_awareness ); ?></textarea>
		</div>
	</section>
<?php endif; ?>
</div>

<?php
get_footer();
