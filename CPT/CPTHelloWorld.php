<?php

namespace WPPluginName\CPT;

/**
 * Class Test
 * @package WPPluginName\CPT
 */
final class CPTHelloWorld
{
    public static function init(): void
    {
        PostTypeFactory::registerPostType('HelloWorld');
    }
}