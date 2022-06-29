<?php
//Removing undefined from email field in checkout form
add_action('wp_footer', 'bcloud_remove_undefined_from_email');

function bcloud_remove_undefined_from_email(){
	?>
<script>
	jQuery('document').ready(function(){
		setTimeout(function(){
			if (jQuery('#billing_email').length){
				if (jQuery('#billing_email').val() == 'undefined'){
					jQuery('#billing_email').val(' ');
				}
			}
		}, 2000);
	});

</script>
<?php

}
