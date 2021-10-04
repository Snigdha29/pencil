<?php
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
    $parenthandle = 'parent-style';
    $theme = wp_get_theme();
    wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css',
        array(), $theme->parent()->get('Version'));

    wp_enqueue_style( 'child-style', get_stylesheet_uri().'style.css',
        array( $parenthandle ), $theme->get('Version'));

   // wp_enqueue_style( 'woo-style', get_template_directory_uri().'/inc/woo_style.css', array( ), $theme->get('Version'));
}

function my_theme_woocommerce_setup(){
    add_theme_support('woocommerce');
}
add_action( 'after_setup_theme', 'my_theme_woocommerce_setup' );

include_once 'inc/woo_functions.php';
?>