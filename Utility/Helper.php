<?php

namespace WPPluginName\Utility;

/**
 * Class Helper
 * @package WPPluginName\Utility
 */
final class Helper
{
    /**
     * @param $object
     * @return string
     */
    public static function getClass($object): string
    {
        return end(explode('\\', get_class($object)));
    }
}