<div class="row category-page-row">

		<div class="col large-3 hide-for-medium <?php flatsome_sidebar_classes(); ?>">
			<?php flatsome_sticky_column_open( 'category_sticky_sidebar' ); ?>
			<div id="shop-sidebar" class="sidebar-inner col-inner">
				<?php
				if ( ! is_shop() && is_product_category() ) {

					$cat_data       = get_queried_object();
					$thumbnail_id   = get_term_meta( $cat_data->term_id, 'thumbnail_id', true );
					$image          = wp_get_attachment_url( $thumbnail_id );
					$category_title = $cat_data->name;

					if ( $image ) {
						?>

					<img src="<?php echo esc_attr( $image ); ?>" alt="" width="275" height="275" />

					<?php } ?>

					<h1><?php echo esc_html( $category_title ); ?></h1>

					<?php

					/**
					 * Hook: woocommerce_archive_description.
					 *
					 * @hooked woocommerce_taxonomy_archive_description - 10
					 * @hooked woocommerce_product_archive_description - 10
					 */
					do_action( 'woocommerce_archive_description' );

				} elseif ( is_active_sidebar( 'shop-sidebar' ) ) {
					dynamic_sidebar( 'shop-sidebar' );
				} else {
					echo '<p>You need to assign Widgets to <strong>"Shop Sidebar"</strong> in <a href="' . get_site_url() . '/wp-admin/widgets.php">Appearance > Widgets</a> to show anything here</p>';
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