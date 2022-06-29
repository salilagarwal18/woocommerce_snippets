<?php
/**
 * Plugin Name:       Blue Cloud Services - Shipping Cost
 * Plugin URI:        https://blue-cloud.io
 * Description:       Customize Shipping Rates Based on Cart Weight
 * Version:           1.1
 * Requires at least: 5.2
 * Author:            Salil Agarwal
 * Author URI:        https://blue-cloud.io
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       shipping-rates
 */

// Useful links:
// https://woocommerce.github.io/code-reference/classes/WC-Cart.html
// https://woocommerce.github.io/code-reference/classes/WC-Customer.html
// https://woocommerce.github.io/code-reference/classes/WC-Product-Simple.html

// Exit if accessed directly | For security reasons.
defined( 'ABSPATH' ) || exit;

function bcloud_get_shipping_cost($cart_weight){
	$shipping_city = WC()->customer->get_shipping_city();
	$shipping_city =  strtolower(trim($shipping_city));
	if ($shipping_city == 'mumbai' || $shipping_city == 'thane'){
		if ($cart_weight <= 500.0){
			return 64.9;
		}
		else if($cart_weight > 500.0 && $cart_weight <= 1000.0){
			return 100.3;
		}
		else if($cart_weight > 1000.0 && $cart_weight <= 1500.0){
			return 135.7;
		}
		else if($cart_weight > 1500.0 && $cart_weight <= 2000.0){
			return 171.1;
		}
		else if($cart_weight > 2000.0 && $cart_weight <= 2500.0){
			return 206.5;
		}
		else if($cart_weight > 2500.0 && $cart_weight <= 3000.0){
			return 241.9;
		}
		else if($cart_weight > 3000.0 && $cart_weight <= 3500.0){
			return 277.3;
		}
		else if($cart_weight > 3500.0 && $cart_weight <= 4000.0){
			return 312.7;
		}
		else if($cart_weight > 4000.0 && $cart_weight <= 4500.0){
			return 348.1;
		}
		else if($cart_weight > 4500.0 && $cart_weight <= 5000.0){
			return 383.5;
		}
		else if($cart_weight > 5000.0 && $cart_weight <= 10000.0){
			return 649.0;
		}
		else if($cart_weight > 10000.0 && $cart_weight <= 20000.0){
			return 885.0;
		}
		else {
			return 1500;
		}
	}
	else{
		if ($cart_weight <= 500.0){
			return 76.7;
		}
		else if($cart_weight > 500.0 && $cart_weight <= 1000.0){
			return 112.1;
		}
		else if($cart_weight > 1000.0 && $cart_weight <= 1500.0){
			return 147.5;
		}
		else if($cart_weight > 1500.0 && $cart_weight <= 2000.0){
			return 182.9;
		}
		else if($cart_weight > 2000.0 && $cart_weight <= 2500.0){
			return 218.3;
		}
		else if($cart_weight > 2500.0 && $cart_weight <= 3000.0){
			return 253.7;
		}
		else if($cart_weight > 3000.0 && $cart_weight <= 3500.0){
			return 289.1;
		}
		else if($cart_weight > 3500.0 && $cart_weight <= 4000.0){
			return 324.5;
		}
		else if($cart_weight > 4000.0 && $cart_weight <= 4500.0){
			return 359.9;
		}
		else if($cart_weight > 4500.0 && $cart_weight <= 5000.0){
			return 649.0;
		}
		else if($cart_weight > 5000.0 && $cart_weight <= 10000.0){
			return 649.0;
		}
		else if($cart_weight > 10000.0 && $cart_weight <= 20000.0){
			return 885.0;
		}
		else {
			return 1500;
		}
	}
}


function bcloud_hide_shipping_when_free_is_available( $rates ) {
	$current_user_id = get_current_user_id();
	//echo 'user id ' . $current_user_id;
	//echo "<br>";
	$is_free_shipping = get_field('blcoud_free_shipping', 'user_' . $current_user_id);
	//echo "<br>";
	//echo 'shipping ' . $is_free_shipping;
	//echo "<br>";
	if ($is_free_shipping){
		return bcloud_set_rates_array($rates, 0);
	}
	foreach($rates as $x => $x_value) {
		/*echo "<br>";
		print_r($x_value);
		echo "<br>";
		print_r($x);
  		//echo "Key=" . ($x) . ", Value=" . ($x_value->get_cost());
		//$x_value->set_cost(40);
  		echo "<br>";*/
	}
	$affiliate_percentage = get_field('bcloud_affiliate_percentage', 'option');
	$margin_percentage = get_field('bcloud_margin_percentage', 'option');
	$total_cost = 0;

	//$shopping_cart = WC()->cart;
	$cart_content = WC()->cart->get_cart_contents();
	$cart_weight = WC()->cart->get_cart_contents_weight();
	$shipping_cost = bcloud_get_shipping_cost($cart_weight);
	//echo "<br>";
	//echo '$shipping_cost  ' . $shipping_cost;
	$total_cost = $shipping_cost;
	foreach ($cart_content as $product_cart_key => $product_details){
		//echo "<br>";
		$base_price = get_field('bcloud_base_price', $product_details['product_id']);
		if (!$base_price){
			$base_price = ((float)$product_details['data']->get_price()*0.65); // if base price is not set, take it as 65% of the selling price.
		}
		$bcloud_price_reduction = get_field('bcloud_price_reduction', $product_details['product_id']);
		if ($bcloud_price_reduction){
			$base_price = $base_price + $bcloud_price_reduction;
		}
		//echo 'base_price ' . $base_price . '<br>';
		$total_cost += ($base_price  * $product_details['quantity']);
		$affiliate_cost = ((float)$product_details['data']->get_price()*((float)$affiliate_percentage*.01)) * $product_details['quantity'];
		//echo $affiliate_cost;
		//echo "<br>";
		$total_cost += $affiliate_cost;
		$margin_cost = ((float)$product_details['data']->get_price()*((float)$margin_percentage*.01)) * $product_details['quantity'];
		//echo $margin_cost;
		//echo "<br>";
		$total_cost += $margin_cost;
		//echo $total_cost;
		/*echo "<br>";
		echo "Key=" . ($x) . ", Value=" . gettype($x_value);
		echo "<br>";
		echo get_field('bcloud_base_price', $product_details['product_id']);
		echo "<br>";
		print_r(array_keys($product_details));
		
		echo "<br>";
		print_r($product_details);
		echo "<br>";*/
		
	}
	$required_shipping_cost = $total_cost - WC()->cart->get_cart_contents_total();
	$final_shipping_cost = min($required_shipping_cost, $shipping_cost);
	if ($final_shipping_cost < 10){
		$final_shipping_cost = 0;
	}
	else{
		$final_shipping_cost = roundUpToAny($final_shipping_cost);
	}
	
	return bcloud_set_rates_array($rates, $final_shipping_cost);
}


function bcloud_set_rates_array($rates, $shipping_cost){
	$ids_to_unset = [];
	foreach ( $rates as $rate_id => $rate ) {
		$rate_cost = $rate->get_cost();
		if ($shipping_cost > 10){
			if ($rate_cost > 0){
				$rate->set_cost($shipping_cost);
			}
			else{
				array_push($ids_to_unset, $rate->get_id());
			}
		}
		else{
			// for free shipping option
			if ($rate_cost > 0){
				array_push($ids_to_unset, $rate->get_id());
			}
		}
	}
	foreach($ids_to_unset as $id_to_unset){
		unset($rates[$id_to_unset]);
	}
	return $rates;
}


function roundUpToAny($n,$x=5) {
	//echo(($n+($x/2))/$x . "<br>");
    //echo(($n+($x/2)) . "<br>");
    return (round($n)%$x === 0) ? round($n) : floor(($n+$x/2)/$x)*$x;
}


add_filter( 'woocommerce_package_rates', 'bcloud_hide_shipping_when_free_is_available', 100 );

-// to show default Custom field option in wordpress
add_filter('acf/settings/remove_wp_meta_box', '__return_false');


add_filter( 'woocommerce_available_payment_gateways', 'bcloud_show_gateway_for_certain_user' );

function bcloud_show_gateway_for_certain_user( $available_gateways ) {
	$current_user_id = get_current_user_id();
	$is_cod = get_field('bcloud_cash_on_delivery', 'user_' . $current_user_id);
	if (!$is_cod){
		unset($available_gateways['cod']);
	}
	/*if ($is_cod){
		$ids_to_unset = [];
		foreach ( $available_gateways as $gateway_id => $gateway_obj ) {
			if ($gateway_id != 'cod'){
				array_push($ids_to_unset, $gateway_id);
			}
		}
		foreach($ids_to_unset as $id_to_unset){
			unset($available_gateways[$id_to_unset]);
		}
	}
	else{
		unset($available_gateways['cod']);
	}
	/*print_r($available_gateways);
	echo "<br>";
	print_r(array_keys($available_gateways));*/
	return $available_gateways;
}


if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page();
	
}


add_action( 'woocommerce_after_checkout_validation', 'bcloud_deny_checkout_if_weight' );
 
function bcloud_deny_checkout_if_weight( $posted ) {
	$max_weight = 20000;
	if ( WC()->cart->cart_contents_weight > $max_weight ) { // we have used above get_cart_contents_weight() function to get cart weight. 
	 //$notice = 'Sorry, your cart has exceeded the maximum allowed weight of ' . $max_weight . get_option( 'woocommerce_weight_unit' );
	   $notice = 'Sorry, your cart has exceeded the maximum allowed weight of 20Kg.';
	   wc_add_notice( $notice, 'error' );
	}
}
