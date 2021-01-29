<?php
/**
 * Plugin Name: iFrame Me
 * Description: A simple solution for inserting iFrames.
 * Version: 1.1.0
 * Author: soysudhanshu
 * Author URI: https://soysudhanshu.com
 * Requires PHP: 7.4
 * Requires at least: 5.3
 */

if(!defined('ABSPATH')){
    exit('');
} 

require __DIR__ . '/includes/class-iframe-me-exception.php';
require __DIR__ . '/includes/class-iframe-me-renderer.php';
require __DIR__ . '/includes/functions.php';

add_shortcode('iframe_me', 'render_iframe_me_shortcode');

function render_iframe_me_shortcode($attributes, $content)
{
    $url = $content;
    if(empty($attributes)){
        $attributes = [];
    }

    try{

        $iframe_renderer = new Iframe_Me_Renderer($url, $attributes);
        $output = $iframe_renderer->output();
    }catch(Iframe_Me_Exception $e){
        $output = "<div style='color:tomato;
                    padding: 1rem;
                    border-radius:7px;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                    background: white'
        >
            {$e->getMessage()}
        </div>";
    }

    return $output;
}