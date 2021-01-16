<?php
/**
 * Plugin Name: Custom Shortcode
 */

add_shortcode( 'varn_vichchhed_iframe', 'render_varn_vichchhed_iframe' );

function render_varn_vichchhed_iframe(){
    $url = 'https://soysudhanshu.com/experiments/varn-vichchhed/';
    return "<iframe src='$url' height='500'></iframe>";
}
