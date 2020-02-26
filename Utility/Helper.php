<?php

namespace WPPluginName\Utility;

/**
 * Class Helper
 * @package WPPluginName\Utility
 */
final class Helper
{
    /**
     * @param string|array|object $data
     */
    public static function errorLog($data): void
    {
        error_log(print_r($data,true));
    }

    /**
     * @param $object
     * @return string
     */
    public static function getClass($object): string
    {
        return end(explode('\\', get_class($object)));
    }
}