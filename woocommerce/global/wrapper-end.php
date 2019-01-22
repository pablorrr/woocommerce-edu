<?php
/**
 * Content wrappers
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/wrapper-end.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version 	3.3.0
 */
echo '<div class="col-12">';
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if (!is_cart()){
   echo '<a style="font-size:1.2em;" href="'.esc_url(wc_get_cart_url()).'" >Go to Cart page<i class="fa fa-shopping-cart"></i></a>';}
if (!is_shop()){
	echo'<a style="font-size:1.2em;"  href="'.esc_url(get_permalink(wc_get_page_id( 'shop' ) )).'" >Go to Shop page<i class="fa fa-shopping-bag"></i></a>';
		 
}
echo '</div>';

echo '<div class="col-12">';
if ( is_user_logged_in() ) :?>
 	<a style="font-size:1.2em;" href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('My Account','woothemes'); ?>"><?php _e('My Account','woothemes'); ?></a>
 
 <?php else : ?>
 	<a style="font-size:1.2em;" href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('Login / Register','woothemes'); ?>"><?php _e('Login / Register','woothemes'); ?></a>
<?php endif; 
echo '</div>';?>
</div></div></div>
