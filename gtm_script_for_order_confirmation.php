<?php
add_action('woocommerce_thankyou', 'bcloud_gtm_script_for_order_confirmation');

function bcloud_gtm_script_for_order_confirmation($order_id){
	$order = wc_get_order( $order_id );
	if (!$order){
		return;
	}
	?>
<script>
	jQuery('document').ready(function(){
		dataLayer.push({
		'event':'purchase',
		'order_value':'<?php echo $order->get_total() ?>',
		'order_id':'<?php echo $order->get_id() ?>',
		'enhanced_conversion_data': {
		  "email": '<?php echo $order->get_billing_email() ?>',   
		  "phone_number": '<?php echo $order->get_billing_phone() ?>',
		  "first_name": '<?php echo $order->get_shipping_first_name() ?>',
		  "last_name": '<?php echo $order->get_shipping_last_name() ?>',
		  "street": '<?php echo $order->get_shipping_address_1() ?>',
		  "city": '<?php echo $order->get_shipping_city() ?>',
		  "region": '<?php echo $order->get_shipping_state() ?>',
		  "postal_code": '<?php echo $order->get_shipping_postcode() ?>',
		  "country": '<?php echo $order->get_shipping_country() ?>'
		}
		})
	});

</script>
<?php
}
