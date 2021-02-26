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
            'width'  => '100%',
            'class'  => 'iframe-me'
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
            $this->verify_remote_response();
            return $this->generate_iframe_html();
        }

        private function verify_remote_response()
        {
            $request = wp_remote_get($this->url);

            if (is_wp_error($request)) {
                $message = $request->get_error_message();
                throw new Iframe_Me_Request_Exception($message);
            }

            $status_code = wp_remote_retrieve_response_code($request);

            if ($status_code >= 300) {
                $message = __('Page returned unsuccessful error code.', 'iframe-me');
                throw new Iframe_Me_Request_Exception($message);
            }
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
                'width'  => $this->attributes['width'],
                'class'  => $this->get_iframe_classes()
            ];

            return $attributes;
        }

        /**
         * iFrame's class attribute value
         *
         * @return string
         */
        private function get_iframe_classes(): string
        {
            $classes = trim($this->attributes['class']);
            $default_classes = static::$default_attributes['class'];

            if($classes !== $default_classes){
                $classes = $default_classes . ' ' . $classes;
            }

            return $classes;
        }
    }
}
