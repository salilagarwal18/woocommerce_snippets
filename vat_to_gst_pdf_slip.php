<?php
//Change VAT to GST in PDF slip - used with 	WooCommerce PDF Invoices & Packing Slips plugin
add_filter('wpo_wcpdf_woocommerce_totals', 'bcloud_change_vat_to_gst'); 

// to debug edit woocommerce-pdf-invoices-packing-slips/templates/Simple/invoice.php lines 145 to 152
function bcloud_change_vat_to_gst($totals){
	foreach ( $totals as $key => $total ){
		$totals[$key]['value'] = str_replace("VAT","GST", $total['value']);
	}
	return $totals;
}
