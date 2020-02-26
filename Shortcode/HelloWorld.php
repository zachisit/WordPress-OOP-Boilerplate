<?php

namespace WPPluginName\Shortcode;

use WPPluginName\Utility\Helper;

/**
 * Class HelloWorld
 * @package WPPluginName\Shortcode
 */
class HelloWorld extends Shortcode
{
    /** @var string */
    protected static $shortcodeTag = 'hello_world';

    /**
     * @return string
     */
    protected function getTemplateName(): string
    {
        return 'hello_world';
    }

    /**
     * @param string $atts
     */
    public function doShortcode(string $atts): void
    {
        $this->createView([
            //
        ]);
    }

}