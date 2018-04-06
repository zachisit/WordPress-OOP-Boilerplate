<?php
namespace WPPluginName\Shortcodes;

class HelloWorld extends Shortcode
{
    protected static $shortcodeTag = 'wppluginname_hello_world';

    public function doShortcode()
    {
        return 'hello world';
    }

}