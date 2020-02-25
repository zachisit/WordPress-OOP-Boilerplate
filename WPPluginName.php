<?php

namespace WPPluginName;

use WPPluginName\CPT\CPTName;
use WPPluginName\DashboardWidgets\DashboardWidgets;
use WPPluginName\Shortcode\Shortcode;

/**
 * Class WPPluginName
 * @package WPPluginName
 */
final class WPPluginName
{
    private static $wpPluginName = null;

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
    public static function getInstance(): self
    {
        if (!(self::$wpPluginName instanceof self)) {
            self::$wpPluginName = new self();
        }
        return self::$wpPluginName;
    }

    /**
     * Currently an alias of getInstance, eventually this will contain the functions to initialize the plugin
     * @return WPPluginName
     */
    public static function init(): self
    {
        return self::getInstance();
    }

    /**
     * @throws \Exception
     */
    public function addActions()
    {
        CPTName::registerActions();
        DashboardWidgets::registerActions();
//        AdminScreen::add_admin_screen([
//            'page_title' => 'Name',
//            'menu_title' => 'Max',
//            'menu_slug' => 'url_of_screen',
//            'dashicon' => 'dashicons-carrot',
//            'screen_template' => 'admin_screen',
//        ]);

        //enqueu scripts hook

        //ajax posting hook
    }

    public function registerFilters(): void
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