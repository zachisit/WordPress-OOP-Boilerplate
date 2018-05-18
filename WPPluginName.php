<?php

namespace WPPluginName;

use WPPluginName\CPT\CPTName;
use WPPluginName\DashboardWidgets\DashboardWidgets;
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
        DashboardWidgets::registerActions();

        //enqueu scripts hook
        //ajax posting hook
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
        //put db field creation here
    }

    /**
     * Called on uninstall
     */
    public static function uninstall()
    {
    }

    public static function registerAdminStyles()
    {
        //wp_enqueue_style
    }

    public static function registerFrontendScripts()
    {
        //wp_enqueue_script
    }
}