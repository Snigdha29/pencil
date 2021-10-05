<?php 
    //Remove Sidebar
    remove_action('woocommerce_sidebar','woocommerce_get_sidebar');
    
    //Remove Page title on Top
    add_filter('woocommerce_show_page_title', 'toggle_title');    
    function toggle_title($val) {
        $val = false;
        return $val;
    }
    
    //Show Product Excerpt 
    add_action( 'woocommerce_after_shop_loop_item_title', 'the_excerpt' );

    //Remove product count
    remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);

    // Show 20 products on a page
    add_filter('loop_shop_per_page','pencil_products_per_page',20);
    function pencil_products_per_page($num_products){
        return 20;        
    }

    //Show 5 products in a row
    add_filter('loop_shop_columns','pencil_product_columns',20);
    function pencil_product_columns($cols) {
        return 5;
    }

    //Add Empty Cart Button
    add_action('woocommerce_cart_actions','pencil_add_empty_cart_button');
    function pencil_add_empty_cart_button() {
        echo '<a class="button" href="?empty-cart=true">'._('Empty Cart','woocommerce').'</a>';
    }

    //Empty the cart when Empty Cart Button is clicked
    add_action('init','pencil_empty_cart');
    function pencil_empty_cart() {
        global $woocommerce;
        if (isset($_GET['empty-cart'])) {
            $woocommerce->cart->empty_cart();
        }
    }

    //Remove SKU from single product page
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

    //Add Cart to Menu with added products
    function my_wc_cart_count() {
 
        if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
     
            $count = WC()->cart->cart_contents_count;
            ?><a class="cart-contents" href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>"><?php
            if ( $count > 0 ) {
                ?>
                <span class="cart-contents-count"><?php echo esc_html( $count ); ?></span>
                <?php
            }
                    ?></a><?php
        }
     
    }
    add_action( 'woocommerce_before_main_content', 'my_wc_cart_count', 20);

    function my_header_add_to_cart_fragment( $fragments ) {
 
        ob_start();
        $count = WC()->cart->cart_contents_count;
        ?><a class="cart-contents" href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>"><?php
        if ( $count > 0 ) {
            ?>
            <span class="cart-contents-count"><?php echo esc_html( $count ); ?></span>
            <?php            
        }
            ?></a><?php
     
        $fragments['a.cart-contents'] = ob_get_clean();
         
        return $fragments;
    }
    add_filter( 'woocommerce_add_to_cart_fragments', 'my_header_add_to_cart_fragment' );