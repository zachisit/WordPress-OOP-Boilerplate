<?php

namespace WPPluginName\DashboardWidgets;

use WPPluginName\Utility\ViewBuilder;

/**
 * Class DashboardWidgets
 * @package WPPluginName\DashboardWidgets
 */
final class DashboardWidgets
{
    /** @var array */
    protected static $widgetsRemove = [
        'dashboard_incoming_links' => [
            'page' => 'dashboard',
            'context' => 'normal'
        ],
        'dashboard_recent_drafts' => [
            'page' => 'dashboard',
            'context' => 'side'
        ],
        'dashboard_quick_press' => [
            'page' => 'dashboard',
            'context' => 'side'
        ],
        'dashboard_primary' => [
            'page' => 'dashboard',
            'context' => 'side'
        ]
    ];
    /** @var array */
    protected static $widgetToAdd = [
        'wpclassified_dashboard_widget' => [
            'title' => 'Lorem Ipsum Title Dashboard Widget',
            'callback' => [__CLASS__,'dashboardWidgetContent']
        ]
    ];
    /** @var string */
    protected static $template = 'dashboard_widget';

    public function __construct()
    {
        //nothing here
    }

    public static function registerActions(): void
    {
        add_action('wp_dashboard_setup', [__CLASS__, 'removeDashboardWidgets']);
        add_action('wp_dashboard_setup', [__CLASS__, 'addDashboardWidget']);
    }

    public static function removeDashboardWidgets(): void
    {
        foreach ( static::$widgetsRemove as $widget_id => $options ) {
            remove_meta_box( $widget_id, $options['page'], $options['context'] );
        }
    }

    public static function addDashboardWidget(): void
    {
        foreach ( static::$widgetToAdd as $widget_id => $options ) {
            wp_add_dashboard_widget(
                $widget_id,
                $options['title'],
                $options['callback']
            );
        }
    }

    public static function dashboardWidgetContent(): void
    {
        $viewArgs = [];
        try {
            $view = new ViewBuilder(['args' => $viewArgs]);
            $view->setTemplate('DashboardWidget/' .self::$template);
            echo $view->render();
        } catch (\Exception $exception) {
            trigger_error($exception->getMessage(),E_USER_WARNING);
        }
    }
}