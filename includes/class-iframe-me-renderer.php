<?php
if (!class_exists('Iframe_Me_Renderer')) {
    class Iframe_Me_Renderer
    {
        private static $supported_protocols = [
            'http',
            'https'
        ];

        private static array $default_attributes = [
            'height' => '500px',
            'width'  => '100%'
        ];

        private string $url;
        private array $attributes;

        public function __construct(string $url, array $attributes = [])
        {
            $url = esc_url($url, static::$supported_protocols);

            if (empty($url)) {
                throw new Iframe_Me_Exception('Invalid URL');
            }

            $this->url        = $url;
            $this->attributes = shortcode_atts(static::$default_attributes, $attributes);
        }

        /**
         * Generates HTML output
         *
         * @return string
         */
        public function output(): string
        {
            return $this->generate_iframe_html();
        }

        public function generate_iframe_html(): string
        {
            $iframe_attributes      = $this->get_iframe_attributes();
            $iframe_src             = $iframe_attributes['src'];

            /**
             * We will create src attribute manually because
             * SRC attribute is already escaped.
             */
            unset($iframe_attributes['src']);

            /**
             * Generate attributes
             */
            $src_attribute = "src='{$iframe_src}'";
            $iframe_additional_attributes = iframe_me_generate_attributes($iframe_attributes);
            
            $html = "<iframe {$src_attribute} {$iframe_additional_attributes}></iframe>";
            
            return $html;
        }

        private function get_iframe_attributes(): array
        {
            $attributes = [
                'src'    => $this->url,
                'height' => $this->attributes['height'],
                'width'  => $this->attributes['width']
            ];

            return $attributes;
        }
    }
}
