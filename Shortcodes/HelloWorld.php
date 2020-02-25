<?php

namespace WPPluginName\Shortcodes;

/**
 * Class HelloWorld
 * @package WPPluginName\Shortcodes
 */
class HelloWorld extends Shortcode
{
    /** @var string */
    protected static $shortcodeTag = 'wppluginname_hello_world';

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