<?php

class Test_Iframe_Me_Rendered extends WP_UnitTestCase
{

    public function test_iframe_me_empty_url_init()
    {
        $this->expectException(Iframe_Me_Exception::class);
        new Iframe_Me_Renderer('');
    }

    public function test_iframe_me_successful_init()
    {
        $renderer = new Iframe_Me_Renderer('https://localhost');
        $this->assertInstanceOf(Iframe_Me_Renderer::class, $renderer);

        /**
         * Test relative url init
         */
        $renderer = new Iframe_Me_Renderer('subhkamnaye!');
        $this->assertInstanceOf(Iframe_Me_Renderer::class, $renderer);
    }


    public function test_iframe_me_output_html()
    {
        // Must have iframe tag at starting with src attribute
        $renderer = new Iframe_Me_Renderer('https://localhost');
        $output   = $renderer->output();
        $prefix   = '<iframe src=\'https://localhost\'';
        $this->assertStringStartsWith(
            $prefix,
            $output,
            'Output HTML must start with iframe tag followed by src attribute'
        );

        // Output src url is properly escaped
        $renderer = new Iframe_Me_Renderer('http://localhost?q=<h3>Hello from XSS"</h3>');
        $output   = $renderer->output();
        $escaped_url   = esc_url('http://localhost?q=<h3>Hello from XSS"</h3>');
        $this->assertStringStartsWith(
            "<iframe src='{$escaped_url}'",
            $output,
            'Src attribute not escaped using esc_url'
        );

        // Attributes are properly escaped
        $renderer = new Iframe_Me_Renderer('http://localhost', [
            'height' => '<script>alert(\'hacked\')</script>'
        ]);

        $output   = $renderer->output();
        $expected_output = 'height=\'&lt;script&gt;alert(&#039;hacked&#039;)&lt;/script&gt;\'';
        $escaped_attribute_found = strpos($output, $expected_output) !== false;
        $this->assertTrue($escaped_attribute_found, 'Attributes not escaped using esc_attr.');
    }

    public function test_class_attribute(){
        $renderer = new Iframe_Me_Renderer('https://localhost');
        $output   = $renderer->output();
        $pattern = "/class='(.|)+'/";
        $this->assertEquals(1, preg_match($pattern, $output), 'Class attribute not found on iframe');

        $pattern = "/class='iframe-me'/";
        $this->assertEquals(1, preg_match($pattern, $output), 'Default Class value invalid');

        $renderer = new Iframe_Me_Renderer('https://localhost', [
            'class' => 'new class'
        ]);
        $output   = $renderer->output();
        $pattern = "/class='.+ new class'/";
        $this->assertEquals(1, preg_match($pattern, $output), 'Custom class value not reflected in output');

        $renderer = new Iframe_Me_Renderer('https://localhost', [
            'class' => ' new class '
        ]);
        $output   = $renderer->output();
        $pattern = "/class='.+ new class'/";
        $this->assertEquals(1, preg_match($pattern, $output), 'Class attribute value not trimmed');
    }

    public function testIdAttributeOutput()
    {
        /**
         * No default ID attribute
         */
        $render = new Iframe_Me_Renderer('https://localhost');
        $output = $render->output();
        $pattern = '/<iframe.+id=\'.+\'/';
        $this->assertEquals(
            0,
            preg_match($pattern, $output),
            'ID attribute should not be added by default'
        );

        /**
         * No ID attribute with empty value
         */
        $render = new Iframe_Me_Renderer('https://localhost', [
            'id' => ''
        ]);
        $output = $render->output();
        $pattern = '/<iframe.+id=\'.+\'/';
        $this->assertEquals(
            0,
            preg_match($pattern, $output),
            'ID attribute must not be added with empty value'
        );

        /**
         * No id attribute with just whitespace
         */
        $render = new Iframe_Me_Renderer('https://localhost', [
            'id' => '    '
        ]);
        $output = $render->output();
        $pattern = '/<iframe.+id=\'.+\'/';
        $this->assertEquals(
            0,
            preg_match($pattern, $output),
            'ID attribute must not be added when only containing whitespaces'
        );

        /**
         * No id attribute with just whitespace
         */
        $render = new Iframe_Me_Renderer('https://localhost', [
            'id' => '   भालू    '
        ]);
        $output = $render->output();
        $pattern = '/<iframe.+id=\'भालू\'/';
        $this->assertEquals(
            1,
            preg_match($pattern, $output),
            'ID attribute must be trimmed'
        );
    }
}
