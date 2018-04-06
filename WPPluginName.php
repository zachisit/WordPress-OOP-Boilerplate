<?php

namespace WPPluginName;

use WPPluginName\CPT\CPTName;
use WPPluginName\Shortcodes\Shortcode;

class WPPluginName
{
    private static $classified = null;

    public function __construct()
    {
        register_activation_hook(__FILE__, [__CLASS__, 'install']);
        register_deactivation_hook(__FILE__, [__CLASS__, 'uninstall']);

        $this->addActions();
        $this->registerFilters();

        Shortcode::addShortcodes();
    }

    /**
     * @return self
     */
    public static function getInstance()
    {
        if (!(self::$classified instanceof self)) {
            self::$classified = new self();
        }
        return self::$classified;
    }

    /**
     * Currently an alias of getInstance, eventually this will contain the functions to initialize the plugin
     * @return WPPluginName
     */
    public static function init()
    {
        return self::getInstance();
    }

    public function addActions()
    {
        CPTName::registerActions();
    }

    public function registerFilters()
    {
        CPTName::registerFilters();
    }

    /**
     * Called on install
     */
    public static function install()
    {
    }

    /**
     * Called on uninstall
     */
    public static function uninstall()
    {
    }

    public static function populateTemplateFile()
    {

    }
}