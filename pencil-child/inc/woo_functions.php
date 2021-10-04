<?php 
    remove_action('woocommerce_sidebar','woocommerce_get_sidebar');
    
    add_filter('woocommerce_show_page_title', 'toggle_title');    
    function toggle_title($val) {
        $val = false;
        return $val;
    }
    
    add_action( 'woocommerce_after_shop_loop_item_title', 'the_excerpt' ); 

    remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);

    add_filter('loop_shop_per_page','mytheme_products_per_page',20);
    function mytheme_products_per_page($num_products){
        return 20;        
    }

    add_filter('loop_shop_columns','mytheme_product_columns',20);
    function mytheme_product_columns($cols) {
        return 5;
    }
?>