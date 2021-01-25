<?php

if (!function_exists('iframe_me_generate_attributes')) {
    /**
     * Generates a HTML attribute string using an
     * associative array.
     *
     * @param array $attributes Associative array
     * @return string
     */
    function iframe_me_generate_attributes(array $attributes): string
    {
        $attributes_html = '';
        foreach($attributes as $attribute => $value){
            $attribute = strtolower($attribute);
            $attributes_html .= esc_attr( $attribute ) . '=\'' . esc_attr( $value ) . '\' ';  
        }

        return trim($attributes_html);
    }
}
