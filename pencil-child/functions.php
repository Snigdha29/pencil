<?php
    function pencil_child_style(){
      wp_enqueue_style('parent-style',get_parent_theme_file_uri().'/style.css');
      wp_enqueue_style('child-style', get_theme_file_uri().'style.css',
      array('parent-style'), wp_get_theme()->get('Version'));
    }
    add_action('wp_enqueue_scripts','pencil_child_style');

?>