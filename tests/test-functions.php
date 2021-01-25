<?php

class Test_Functions extends WP_UnitTestCase
{
    public function test_iframe_me_generate_attributes()
    {
        // Output is trimmed
        $output = iframe_me_generate_attributes(['data-x' => 'y']);
        $this->assertStringStartsNotWith(' ', $output, 'Attribute string has starting whitespace.');
        $this->assertStringEndsNotWith(' ', $output, 'Attribute string has trailing whitespace.');

        // Empty array must return empty string
        $output = iframe_me_generate_attributes([]);
        $this->assertEquals('', $output, 'Empty array must return empty attribute string');

        // Key in asssociative array is attribute is name
        $output = iframe_me_generate_attributes(['data-x' => 'y']);
        $this->assertStringStartsWith('data-x', $output, 'Key is not used as attribute name');

        // Attributes are in lower case
        $output = iframe_me_generate_attributes(['DaTa-X' => 'y']);
        $this->assertStringStartsWith('data-x', $output, 'Attribute name not in lowercase');

        // Values are properly escaped
        $script = '<script>alert(\'hacked\');</script>';
        $output = iframe_me_generate_attributes(['data-danger' => $script]);
        $expected_output = 'data-danger=\'&lt;script&gt;alert(&#039;hacked&#039;);&lt;/script&gt;\'';
        $this->assertEquals($expected_output, $output, 'Attribute values not escaped.');

        // Attribute name is properly escaped
        $script = '<script>alert(\'hacked\');</script>';
        $output = iframe_me_generate_attributes([$script => 'data-danger']);
        $expected_output = '&lt;script&gt;alert(&#039;hacked&#039;);&lt;/script&gt;=\'data-danger\'';
        $this->assertEquals($expected_output, $output, 'Attribute name not escaped.');
    }
}
