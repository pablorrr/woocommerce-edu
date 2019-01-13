<?php
/**
 * Add WooCommerce support
 *
 * @package understrap
 */
add_action( 'after_setup_theme', 'understrap_woocommerce_support' );
if ( ! function_exists( 'understrap_woocommerce_support' ) ) {
	/**
	 * Declares WooCommerce theme support.
	 */
	function understrap_woocommerce_support() {
		add_theme_support( 'woocommerce' );
		
		// Add New Woocommerce 3.0.0 Product Gallery support
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-slider' );

		// hook in and customizer form fields.
		add_filter( 'woocommerce_form_field_args', 'understrap_wc_form_field_args', 10, 3 );
	}
}
/**
 * Filter hook function monkey patching form classes
 * Author: Adriano Monecchi http://stackoverflow.com/a/36724593/307826
 *
 * @param string $args Form attributes.
 * @param string $key Not in use.
 * @param null   $value Not in use.
 *
 * @return mixed
 */
function understrap_wc_form_field_args( $args, $key, $value = null ) {
	// Start field type switch case.
	switch ( $args['type'] ) {
		/* Targets all select input type elements, except the country and state select input types */
		case 'select' :
			// Add a class to the field's html element wrapper - woocommerce
			// input types (fields) are often wrapped within a <p></p> tag.
			$args['class'][] = 'form-group';
			// Add a class to the form input itself.
			$args['input_class']       = array( 'form-control', 'input-lg' );
			$args['label_class']       = array( 'control-label' );
			$args['custom_attributes'] = array(
				'data-plugin'      => 'select2',
				'data-allow-clear' => 'true',
				'aria-hidden'      => 'true',
				// Add custom data attributes to the form input itself.
			);
			break;
		// By default WooCommerce will populate a select with the country names - $args
		// defined for this specific input type targets only the country select element.
		case 'country' :
			$args['class'][]     = 'form-group single-country';
			$args['label_class'] = array( 'control-label' );
			break;
		// By default WooCommerce will populate a select with state names - $args defined
		// for this specific input type targets only the country select element.
		case 'state' :
			// Add class to the field's html element wrapper.
			$args['class'][] = 'form-group';
			// add class to the form input itself.
			$args['input_class']       = array( '', 'input-lg' );
			$args['label_class']       = array( 'control-label' );
			$args['custom_attributes'] = array(
				'data-plugin'      => 'select2',
				'data-allow-clear' => 'true',
				'aria-hidden'      => 'true',
			);
			break;
		case 'password' :
		case 'text' :
		case 'email' :
		case 'tel' :
		case 'number' :
			$args['class'][]     = 'form-group';
			$args['input_class'] = array( 'form-control', 'input-lg' );
			$args['label_class'] = array( 'control-label' );
			break;
		case 'textarea' :
			$args['input_class'] = array( 'form-control', 'input-lg' );
			$args['label_class'] = array( 'control-label' );
			break;
		case 'checkbox' :
			$args['label_class'] = array( 'custom-control custom-checkbox' );
			$args['input_class'] = array( 'custom-control-input', 'input-lg' );
			break;
		case 'radio' :
			$args['label_class'] = array( 'custom-control custom-radio' );
			$args['input_class'] = array( 'custom-control-input', 'input-lg' );
			break;
		default :
			$args['class'][]     = 'form-group';
			$args['input_class'] = array( 'form-control', 'input-lg' );
			$args['label_class'] = array( 'control-label' );
			break;
	} // end switch ($args).
	return $args;
}

// ---------------------------------------------
// remove WooCommerce css style when is unnecessary     -
// source: https://crunchify.com/how-to-stop-loading-woocommerce-js-javascript-and-css-files-on-all-wordpress-postspages/
// ---------------------------------------------

/* */
add_action( 'wp_enqueue_scripts', 'crunchify_disable_woocommerce_loading_css_js' );
 
function crunchify_disable_woocommerce_loading_css_js() {
 
	// Check if WooCommerce plugin is active
	if( function_exists( 'is_woocommerce' ) ){
 
		// Check if it's any of WooCommerce page
		if(! is_woocommerce() && ! is_cart() && ! is_checkout() ) { 		
			
			## Dequeue WooCommerce styles
			wp_dequeue_style('woocommerce-layout'); 
			wp_dequeue_style('woocommerce-general'); 
			wp_dequeue_style('woocommerce-smallscreen'); 	
 
			## Dequeue WooCommerce scripts
			wp_dequeue_script('wc-cart-fragments');
			wp_dequeue_script('woocommerce'); 
			wp_dequeue_script('wc-add-to-cart'); 
		
		}
	}	
}


// ---------------------------------------------
// Customizing WooCommerce hooks and filters    -
// source: https://www.youtube.com/watch?v=jV_-4qHaxyA&list=PL9fcHFJHtFaZh9U9BiKlqX7bGdvFkSjro&index=7
// ---------------------------------------------


//remove info about count of displaying products in shop page(archive products)
//remove_action('woocommerce_before_shop_loop','woocommerce_result_count',20);

//remove search filter on right site on a shop page(archive products page)
//remove_action('woocommerce_before_shop_loop','woocommerce_catalog_ordering',30);


/**
 * Change number of products per row to 3
 * source : https://docs.woocommerce.com/document/change-number-of-products-per-row/
 */
 
add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 3; // 3 products per row
	}
}


/**
 * Change number of products per page (pagination)
 * source : https://docs.woocommerce.com/document/storefront-filters-example-change-number-products-displayed-per-page/
 */
 
add_filter('loop_shop_per_page','products_count_per_page' ,20,1);
if (!function_exists('products_count_per_page')) {
	function products_count_per_page ($prod){
	
	return 4;
  }
}

/**
 * Create the section beneath the products tab ( back end) 
 * localhost url:http://localhost/wp49/woocommercetest/wp-admin/admin.php?page=wc-settings&tab=products&section=wcslider
 * woocommerce docs source online :https://docs.woocommerce.com/document/adding-a-section-to-a-settings-tab/
 **/
 
add_filter( 'woocommerce_get_sections_products', 'wcslider_add_section' );
function wcslider_add_section( $sections ) {
	
	$sections['wcslider'] = __( 'WC Slider', 'text-domain' );
	return $sections;
	
}

/**
 * Add settings to the specific section we created before
 */
add_filter( 'woocommerce_get_settings_products', 'wcslider_all_settings', 10, 2 );
function wcslider_all_settings( $settings, $current_section ) {
	/**
	 * Check the current section is what we want
	 **/
	if ( $current_section == 'wcslider' ) {
		$settings_slider = array();
		// Add WARNING THIS IS NOT ORIGINAL CODE , JUST CUSTOM MODIF FOR EDUCATIONAL PURPOSES!!!
		$settings_slider[] = array( 'name' => __( 'WARNING', 'text-domain' ),
		'type' => 'title',
		'desc' => __( 'THIS IS NOT ORIGINAL CODE , JUST CUSTOM MODIF FOR EDUCATIONAL PURPOSES!!!', 'text-domain' ),
		'id' => 'wcslider' );
		
		
		// Add Title to the Settings
		$settings_slider[] = array( 'name' => __( 'WC Slider Settings', 'text-domain' ),
		'type' => 'title',
		'desc' => __( 'The following options are used to configure WC Slider', 'text-domain' ),
		'id' => 'wcslider' );
		// Add first checkbox option
		$settings_slider[] = array(
			'name'     => __( 'Auto-insert into single product page', 'text-domain' ),
			'desc_tip' => __( 'This will automatically insert your slider into the single product page', 'text-domain' ),
			'id'       => 'wcslider_auto_insert',
			'type'     => 'checkbox',
			'css'      => 'min-width:300px;',
			'desc'     => __( 'Enable Auto-Insert', 'text-domain' ),
		);
		// Add second text field option
		$settings_slider[] = array(
			'name'     => __( 'Slider Title', 'text-domain' ),
			'desc_tip' => __( 'This will add a title to your slider', 'text-domain' ),
			'id'       => 'wcslider_title',
			'type'     => 'text',
			'desc'     => __( 'Any title you want can be added to your slider with this option!', 'text-domain' ),
		);
		
		$settings_slider[] = array( 'type' => 'sectionend', 'id' => 'wcslider' );
		return $settings_slider;
	
	/**
	 * If not, return the standard settings
	 **/
	} else {
		return $settings;
	}
}

/**
 * Create the section beneath the products tab ( back end) SAME LIKE HINDI TUTORIAL
 * YT SOURCE:https://www.youtube.com/watch?v=nvDtTlaN45o&list=PL9fcHFJHtFaZh9U9BiKlqX7bGdvFkSjro&index=9
 * localhost url:http://localhost/wp49/woocommercetest/wp-admin/admin.php?page=wc-settings&tab=products&section=wcproddissetup
 * woocommerce docs source online :https://docs.woocommerce.com/document/adding-a-section-to-a-settings-tab/
 **/

add_filter( 'woocommerce_get_sections_products', 'products_display_setup' );
function products_display_setup( $sections ) {
	
	$sections['wcproddissetup'] = __( 'Products display setup', 'text-domain' );
	return $sections;
	
}




