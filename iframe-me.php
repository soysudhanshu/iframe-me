<?php
/**
 * Plugin Name: iFrame Me
 * Description: A simple solution for inserting iFrames.
 * Version: 1.0.0
 * Author: soysudhanshu
 * Author URI: https://soysudhanshu.com
 */

add_shortcode('iframe_me', 'render_iframe_me_shortcode');

function render_iframe_me_shortcode($attributes, $content)
{
    $allowed_protocols = ['http', 'https'];
    $url = esc_url($content, $allowed_protocols);

    if (empty($url)) {
        return <<<HTML
        <div style='color:tomato;
                    padding: 1rem;
                    border-radius:7px;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                    background: white'
        >
            Invalid iFrame URL
        </div>
        HTML;
    }

    return "<iframe src='$url' height='500'></iframe>";
}