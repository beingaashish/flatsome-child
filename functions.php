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

//For example, you can paste this into your theme functions.php file

function meks_which_template_is_loaded() {
	if ( is_super_admin() ) {
		global $template;
		error_log( print_r( $template, true ) );
	}
}

add_action( 'wp_footer', 'meks_which_template_is_loaded' );
