<div class="row category-page-row">

		<div class="col large-3 hide-for-medium <?php flatsome_sidebar_classes(); ?>">
			<?php flatsome_sticky_column_open( 'category_sticky_sidebar' ); ?>
			<div id="shop-sidebar" class="sidebar-inner col-inner">
				<?php
				$cat_data = get_queried_object();

				if ( ! is_shop() && is_product_category() && 0 !== $cat_data->parent ) {

					// This code runs inside of child categories archive page.
					$parent_cat_slug_arr = array(
						'product-type' => 'produkt-type',
						'hudtype'      => 'hudtype',
						'hudtilstand'  => 'hudtilstand',
					);

					$thumbnail_id   = get_term_meta( $cat_data->term_id, 'thumbnail_id', true );
					$image          = wp_get_attachment_url( $thumbnail_id );
					$category_title = $cat_data->name;

					// Sub Category Image.
					if ( $image ) {
						?>
					<div class="img has-hover x md-x lg-x y md-y lg-y">
						<div class="img-inner dark">
							<img src="<?php echo esc_attr( $image ); ?>" class="" alt="" width="711" height="800" />
						</div>
					</div>
						<?php
					}

					// Sub Category Title.
					if ( $category_title ) {
						?>
						<div class="container section-title-container flatsome-section-title-container">

							<h1 class="section-title">
							<span class="section-title-main">
								<?php echo esc_html( $category_title ); ?>
							</span>
						</h1>
						</div>
						<?php
					}

					// Sub Category Archive Description.
					do_action( 'woocommerce_archive_description' );

					?>
					<section class="flatsome-child-related-product-categories-filter">
						<div class="flatsome-child-related-product-categories-filter-top">
							<h3 class="flatsome-child-related-product-categories-filter-heading"><?php esc_html_e( 'Sortér efter:', 'flatsome' ); ?></h3>
							<button class="flatsome-child-related-product-categories-filter-reset"><?php esc_html_e( 'Nulstil', 'flatsome' ); ?></button>
						</div>
					<?php
					$parent_slug = get_term( $cat_data->parent, 'product_cat' )->slug;

					// If current subcategory parent is product-type list category from hudtype and hudtilstand.
					if ( 'produkt-type' === $parent_slug ) {

						// List Hudtype subcategories.
						$hudtype_cat      = get_term_by( 'slug', 'hudtype', 'product_cat' );
						$hudtype_sub_cats = get_categories(
							array(
								'parent'   => $hudtype_cat->term_id,
								'taxonomy' => 'product_cat',
							)
						);

						?>
						<h4 class="flatsome-child-filter-sub-cat-title"><?php echo esc_html( $hudtype_cat->name ); ?></h4>

						<div class="ux-menu stack stack-col justify-start ux-menu--divider-solid" >
						<?php

						foreach ( $hudtype_sub_cats as $hudtype_sub_cat => $val ) {
							// Logic for hiding those cateogories which when combined to parent category does not have any product within them.
							$args = array(
								'post_type'   => 'product',
								'post_status' => 'publish',
								'tax_query'   => array(
									array(
										'taxonomy' => 'product_cat',
										'field'    => 'term_id',
										'terms'    => array( $val->term_id, $cat_data->term_id ),
										'operator' => 'AND',
									),
								),
							);

							$query = new WP_Query( $args );

							if ( $query->have_posts() ) :
								?>
								<div class="ux-menu-link flex menu-item flatsome-child-related-product-categories-item">
									<a href="#" class="ux-menu-link__link flex current-menu-item flatsome-child-related-product-categories-link" data-category-id="<?php echo esc_attr( $val->term_id ); ?>">
										<?php echo esc_html( $val->name ); ?>
									</a>
								</div>
								<?php
							endif;
							wp_reset_postdata();
						}

						?>
						</div>

						<?php

						// List Hudtilstand subcategories.
						$hudtilstand_cat      = get_term_by( 'slug', 'hudtilstand', 'product_cat' );
						$hudtilstand_sub_cats = get_categories(
							array(
								'parent'   => $hudtilstand_cat->term_id,
								'taxonomy' => 'product_cat',
							)
						);
						?>

						<h4 class="flatsome-child-filter-sub-cat-title"><?php echo esc_html( $hudtilstand_cat->name ); ?></h4>
						<div class="ux-menu stack stack-col justify-start ux-menu--divider-solid" >

						<?php
						foreach ( $hudtilstand_sub_cats as $hudtilstand_sub_cat => $val ) {
							// Logic for hiding those cateogories which when combined to parent category does not have any product within them.
							$args = array(
								'post_type'   => 'product',
								'post_status' => 'publish',
								'tax_query'   => array(
									array(
										'taxonomy' => 'product_cat',
										'field'    => 'term_id',
										'terms'    => array( $val->term_id, $cat_data->term_id ),
										'operator' => 'AND',
									),
								),
							);

							$query = new WP_Query( $args );

							if ( $query->have_posts() ) :
								?>
								<div class="ux-menu-link flex menu-item flatsome-child-related-product-categories-item">
									<a href="#" class="ux-menu-link__link flex current-menu-item flatsome-child-related-product-categories-link" data-category-id="<?php echo esc_attr( $val->term_id ); ?>">
										<?php echo esc_html( $val->name ); ?>
									</a>
								</div>
								<?php
							endif;
							wp_reset_postdata();
						}
						?>
						</div>

						<?php

					} elseif ( 'hudtype' === $parent_slug || 'hudtilstand' === $parent_slug || 'brands' === $parent_slug ) {

						// List Produkt type subcategories.
						$produkt_type_cat      = get_term_by( 'slug', 'produkt-type', 'product_cat' );
						$produkt_type_sub_cats = get_categories(
							array(
								'parent'   => $produkt_type_cat->term_id,
								'taxonomy' => 'product_cat',
							)
						);

						?>
						<h4 class="flatsome-child-filter-sub-cat-title"><?php echo esc_html( $produkt_type_cat->name ); ?></h4>
						<div class="ux-menu stack stack-col justify-start ux-menu--divider-solid" >

						<?php
						foreach ( $produkt_type_sub_cats as $produkt_type_sub_cat => $val ) {

							// Logic for hiding those cateogories which when combined to parent category does not have any product within them.
							$args = array(
								'post_type'   => 'product',
								'post_status' => 'publish',
								'tax_query'   => array(
									array(
										'taxonomy' => 'product_cat',
										'field'    => 'term_id',
										'terms'    => array( $val->term_id, $cat_data->term_id ),
										'operator' => 'AND',
									),
								),
							);

							$query = new WP_Query( $args );

							if ( $query->have_posts() ) :
								?>
								<div class="ux-menu-link flex menu-item flatsome-child-related-product-categories-item">
									<a href="#" class="ux-menu-link__link flex current-menu-item flatsome-child-related-product-categories-link" data-category-id="<?php echo esc_attr( $val->term_id ); ?>">
										<?php echo esc_html( $val->name ); ?>
									</a>
								</div>
								<?php
							endif;
							wp_reset_postdata();
						}
						?>
						</div>
						<?php
					}
					?>
					</section>
					<?php
				}

				if ( is_active_sidebar( 'shop-sidebar' ) && is_shop() && ! is_product_category() ) {
					dynamic_sidebar( 'shop-sidebar' );
				}

				if ( ( is_product_category() && isset( $cat_data->parent ) && 0 === $cat_data->parent ) ) {
					dynamic_sidebar( 'shop-sidebar' );
					echo '<p>Parent page only</p>';
				}
				?>
			</div>
			<?php flatsome_sticky_column_close( 'category_sticky_sidebar' ); ?>
		</div>

		<div class="col large-9">
		<?php
		/**
		 * Hook: woocommerce_before_main_content.
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20 (FL removed)
		 * @hooked WC_Structured_Data::generate_website_data() - 30
		 */
		do_action( 'woocommerce_before_main_content' );

		?>

		<?php

		if ( woocommerce_product_loop() ) {

			/**
			 * Hook: woocommerce_before_shop_loop.
			 *
			 * @hooked wc_print_notices - 10
			 * @hooked woocommerce_result_count - 20 (FL removed)
			 * @hooked woocommerce_catalog_ordering - 30 (FL removed)
			 */
			do_action( 'woocommerce_before_shop_loop' );

			woocommerce_product_loop_start();

			if ( wc_get_loop_prop( 'total' ) ) {
				while ( have_posts() ) {
					the_post();

					/**
					 * Hook: woocommerce_shop_loop.
					 *
					 * @hooked WC_Structured_Data::generate_product_data() - 10
					 */
					do_action( 'woocommerce_shop_loop' );

					wc_get_template_part( 'content', 'product' );
				}
			}

			woocommerce_product_loop_end();

			/**
			 * Hook: woocommerce_after_shop_loop.
			 *
			 * @hooked woocommerce_pagination - 10
			 */
			do_action( 'woocommerce_after_shop_loop' );
		} else {
			/**
			 * Hook: woocommerce_no_products_found.
			 *
			 * @hooked wc_no_products_found - 10
			 */
			do_action( 'woocommerce_no_products_found' );
		}
		?>

		<?php
			/**
			 * Hook: flatsome_products_after.
			 *
			 * @hooked flatsome_products_footer_content - 10
			 */
			do_action( 'flatsome_products_after' );
			/**
			 * Hook: woocommerce_after_main_content.
			 *
			 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
			 */
			do_action( 'woocommerce_after_main_content' );
		?>
		</div>
</div>
