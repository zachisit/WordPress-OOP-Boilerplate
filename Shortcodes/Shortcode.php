<?php
namespace WPPluginName\Shortcodes;

abstract class Shortcode
{
    protected static $shortcodeTag = false;
    private static $definedShortCodes = [
        'SearchBar'
    ];

    private static $initializedShortCodes = [];

    abstract public function doShortcode();

    public function __construct()
    {
        if (static::$shortcodeTag) {
            add_shortcode(static::$shortcodeTag, [$this, 'doShortcode']);
        }
    }

    public static function addShortcodes()
    {
        foreach (self::$definedShortCodes as $shortCode) {
            if (!isset(self::$initializedShortCodes[$shortCode])) {
                $shortCodeObject = "\\WPPluginName\Shortcodes\\" . $shortCode;
                self::$initializedShortCodes[$shortCode] = new $shortCodeObject;
            }
        }
    }
}