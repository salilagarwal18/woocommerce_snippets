<?php
// Redirect to Checkout on Add to Cart – WooCommerce
add_filter( 'woocommerce_add_to_cart_redirect', 'bcloud_redirect_checkout_add_cart' );
 
function bcloud_redirect_checkout_add_cart() {
   return wc_get_checkout_url();
}
