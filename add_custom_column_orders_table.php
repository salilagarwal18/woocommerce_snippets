<?php
add_filter('manage_edit-shop_order_columns', '_order_items_column' );
function _order_items_column( $order_columns ) {
   $new_columns = array();
    foreach ( $order_columns as $key => $name ) {

        $new_columns[ $key ] = $name;

        // add ship-to after order status column
        if ( 'order_number' === $key ) {
            $new_columns['order_products'] = __( 'Product Name', 'textdomain' );
        }
    }

    return $new_columns;
}
 

add_action( 'manage_shop_order_posts_custom_column' , '_order_items_column_cnt' );
function _order_items_column_cnt( $colname ) {
    global $the_order; // the global order object
 
    if( $colname == 'order_products' ) {
 
        // get items from the order global object
        $order_items = $the_order->get_items();
 
        if ( !is_wp_error( $order_items ) ) {
            foreach( $order_items as $order_item ) {
 
                echo $order_item['quantity'] .' × <a href="' . admin_url('post.php?post=' . $order_item['product_id'] . '&action=edit' ) . '">'. $order_item['name'] .'</a><br />';
                // you can also use $order_item->variation_id parameter
                // by the way, $order_item['name'] will display variation name too
 
            }
        }
 
    }
 
}
