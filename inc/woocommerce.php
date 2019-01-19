<?php
/**
 * Add WooCommerce support
 *
 * @package understrap
 */
 
 /**
 * 
 * Add custom CSS
 *  Taken from: https://docs.woocommerce.com/document/disable-the-default-stylesheet/
 * Enqueue your own stylesheet
 */
function wp_enqueue_woocommerce_style(){
	wp_register_style( 'css-woocommerce', get_template_directory_uri() . '/css/woocommerce.css' );
	
	if ( class_exists( 'woocommerce' ) ) {
		wp_enqueue_style( 'css-woocommerce' );
	}
}
add_action( 'wp_enqueue_scripts', 'wp_enqueue_woocommerce_style' );
 
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

/*removing sidebar from woo pages taken from:https://stackoverflow.com/questions/25700650/how-do-i-remove-woocommerce-sidebar-from-cart-checkout-and-single-product-pages*/
if(is_cart){
	
remove_action( 'woocommerce_sidebar',  'woocommerce_get_sidebar', 50 ); 
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
 * Notice that number of products per row cant be greather than products number per page!!!!!! fix that!!!
 */
 
add_filter('loop_shop_columns', 'loop_columns',20, 1);
if (!function_exists('loop_columns')) {
	function loop_columns($prod_per_row) {
		$prod_per_row = get_option('prdt_count_per_row');
		return $prod_per_row ? $prod_per_row : 3 ;
	}
}


/**
 * Change number of products per page (pagination)
 * source : https://docs.woocommerce.com/document/storefront-filters-example-change-number-products-displayed-per-page/
 */
 
add_filter('loop_shop_per_page','products_count_per_page' ,30,1);
if (!function_exists('products_count_per_page')) {
	function products_count_per_page ($prod_per_page){
	$prod_per_page = get_option('prdt_count_per_page');
	return $prod_per_page ? $prod_per_page : 4 ;
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


/**
 * Add settings to the specific section we created before
 * To retrive option val use:get_option( 'id_name_of_field' )
 */
add_filter( 'woocommerce_get_settings_products', 'wcslider_all_settings', 10, 2 );
function wcslider_all_settings( $settings, $current_section ) {
	/**
	 * Check the current section is what we want
	 **/
	if ( $current_section == 'wcproddissetup' ) {
		$settings_display_products = array();
		
		// Add Title to the Settings
		$settings_display_products[] = array( 
		'name' => __( 'Display Products Settings', 'text-domain' ),
		'type' => 'title',
		'desc' => __( 'The following options are used to configure display products on Shop Page', 'text-domain' ),
		'id' => 'wcproddissetup' );
		// Add text field option - Display products number per row
		$settings_display_products[] = array(
			'name'     => __( 'Display products number per row', 'text-domain' ),
			'desc_tip' => __( 'Type number of products to display per row ', 'text-domain' ),
			'id'       => 'prdt_count_per_row',
			'type'     => 'text',
			'css'      => 'min-width:300px;',
			'desc'     => __( 'Number of products per row', 'text-domain' ),
		);
		// Add text field option - Display products number per page
		$settings_display_products[] = array(
			'name'     => __( 'Display products number per page', 'text-domain' ),
			'desc_tip' => __( 'Type number of products to display per page ', 'text-domain' ),
			'id'       => 'prdt_count_per_page',
			'type'     => 'text',
			'css'      => 'min-width:300px;',
			'desc'     => __( 'Number of products per page', 'text-domain' ),
		);
		
		// Add text field option - Switch on/off display related products
		$settings_display_products[] = array(
			'name'     => __( 'Switch on/off display related products', 'text-domain' ),
			'desc_tip' => __( 'Check to displaY OFF, uncheck to to display ON', 'text-domain' ),
			'id'       => 'rel_prod',
			'type'     => 'checkbox',
		);
		

$settings_display_products[] = array( 'type' => 'sectionend', 'id' => 'wcproddissetup' );

		return $settings_display_products;
	
	/**
	 * If not, return the standard settings
	 **/
	} else {
		return $settings;
	}
}
/**
 * Display category image on category archive
 * source :https://docs.woocommerce.com/document/woocommerce-display-category-image-on-category-archive/ 
 */
remove_action('woocommerce_archive_description','woocommerce_taxonomy_archive_description',10);
remove_action('woocommerce_archive_description','woocommerce_product_archive_description',10);
add_action( 'woocommerce_archive_description', 'woocommerce_category_image', 2 );
function woocommerce_category_image() {
    if ( is_product_category() ){
	    global $wp_query;
		/////////////taken from wc-template-function (woocommerce plugin) line no 818
		if ( is_product_taxonomy() && 0 === absint( get_query_var( 'paged' ) ) ) {
			$term = get_queried_object();

			if ( $term && ! empty( $term->description ) ) {
				echo '<div class="term-description">' . wc_format_content( $term->description ) . '</div>'; // WPCS: XSS ok.
			}
		}
		/////////
	    $cat = $wp_query->get_queried_object();
	    $thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
	    $image = wp_get_attachment_url( $thumbnail_id );
	    if ( $image ) {
		    echo '<div class="cat_details">   <img src="' . $image . '" alt="' . $cat->name . '" /></div>';
		}
	}
}
/**
 * Change default looking of shop page inspired by  hindi tuts :https://youtu.be/Lidkp3l3Awo?list=PL9fcHFJHtFaZh9U9BiKlqX7bGdvFkSjro&t=770
 * 
 */
// this hook has been taken from woo pluginn woocommerce/template-tags/content-product.php 
//add_action( 'woocommerce_before_shop_loop_item_title','custom_change_start',15 );
//add_action( 'woocommerce_after_shop_loop_item','custom_change_end',15 );
/*function custom_change_start (){
	
	echo '<p>TEST function(possibility to put add html,css content)';
	
}

function custom_change_end (){
	
	echo '</p>';
	
}
*/
add_action( 'woocommerce_before_shop_loop_item_title','shop_page_excerpt',15 );
/**
 * display excerpt on shop products
 *inpired by hindi : https://youtu.be/Lidkp3l3Awo?list=PL9fcHFJHtFaZh9U9BiKlqX7bGdvFkSjro&t=1033
 * 
 */
function shop_page_excerpt(){
	
	$text = get_the_excerpt();
	$text = substr ($text , 0, 65);
	echo '<p>'.$text.'</p>';
	}

/**
 * modify default single product page display below a product
 *inpired by hindi :https://www.youtube.com/watch?v=3aNY0OiNPZQ&index=13&list=PL9fcHFJHtFaZh9U9BiKlqX7bGdvFkSjro
 * 
 */	
//it cause vannish dispalying text with description below product	
//remove_action('woocommerce_after_single_product_summary','woocommerce_output_product_data_tabs',10);

if (!function_exists('_custom_tabs_display')){
	
		add_filter( 'woocommerce_product_tabs', '_custom_tabs_display',10 ,1);
		
		function _custom_tabs_display($tabs){
			unset ($tabs['reviews']);//removing opinion tabs
			$tabs['my_custom_tabs'] = array (
			
					'title' => 'Custom test title',//add tab title
					'priority' => 10,
					'callback' => '_custom_tab_display',//callback to display what is inside tab
					
			);
			
			return $tabs;
		}

		function _custom_tab_display(){
			
			echo '<iframe width="768" height="480" src="https://www.youtube.com/embed/3aNY0OiNPZQ?list=PL9fcHFJHtFaZh9U9BiKlqX7bGdvFkSjro" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';

		}
}
/**
 * dealing with Related product section in single product page below main content
 *inpired by hindi :https://www.youtube.com/watch?v=-KtbgAcAQ4E&index=14&list=PL9fcHFJHtFaZh9U9BiKlqX7bGdvFkSjro
 * 
 */	

 //below code line cause disapear realted products (single product page)
//remove_action( 'woocommerce_after_single_product_summary','woocommerce_output_related_products', 20);

//customize how many products should be displayed in related products section
add_filter('woocommerce_output_related_products_args','_custom_related_products');

function _custom_related_products($args){
	
	$args = array(
            'posts_per_page' => 2,
            'columns'        => 2,
            'orderby'        => 'rand', // @codingStandardsIgnoreLine.
        );
	}
	
	
//////////////////


add_action('woocommerce_after_single_product_summary','_custom_function_rel_prod', 15);

function _custom_function_rel_prod ( ){
	
	$check_val = get_option('rel_prod', false);
	
	if (isset($check_val) && $check_val == 'yes'){
		
		remove_action('woocommerce_after_single_product_summary','woocommerce_output_related_products' , 20);
	}
}

//change default image when no thumbanil in single product image

remove_filter( 'woocommerce_placeholder_img_src', WC()->plugin_url() . '/assets/images/placeholder.png' );
add_filter('woocommerce_placeholder_img_src',  '_wc_default_image' );
function _wc_default_image(){
    return get_template_directory_uri().'/img/no.imagez.png';
    
}
//remove sidebar from cart page

function _remove_sidebar_cart() {
	if (is_cart()){//use phhp html bufer obget clean
    ?>
        <style>
          #right-sidebar,#footerfull,#left-sidebar,#statichero ,#hero{display:none;}
		  
        </style>
    <?php }
}
add_action('wp_head', '_remove_sidebar_cart');

//customize cart(page) fee- DOESNT WORK

/*add_action('woocommerce_cart_calculate_fees','_customize_fee_cart');
function _customize_fee_cart(){
	
	global $woocommerce;
	if(is_admin() && !defined('WP_DOING_AJAX'))
		return;
	
	$price =2;
	$fee_countries =  array('US','PL','DE');
	$customer_country = $woocommerce -> customer -> get_shipping_country();
	
	if(in_array($customer_country,$fee_countries)){
		
		$woocommerce ->cart -> add_fee(_e('additional fee','theme-slug'),$price, true);
	}
	
}

add_action('woocommerce_cart_calculate_fees','_customize_fee_cart_based');
function _customize_fee_cart_based(){
	
	global $woocommerce;
	if(is_admin() && !defined('WP_DOING_AJAX'))
		return;
	
	$price =2.5;
	$fee_countries =  array('US','PL','DE');
	$customer_country = $woocommerce -> customer -> get_shipping_country();
	if(in_array($fee_countries,$customer_country)){
		
		$woocommerce ->cart -> add_fee(_e('additional fee','theme-slug'),$price, true);
	}
	
	
	
}*/
       
   
