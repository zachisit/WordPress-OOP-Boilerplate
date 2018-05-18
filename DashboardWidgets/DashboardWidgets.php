<?php

namespace WPPluginName\DashboardWidgets;

use WPPluginName\WPPluginName;

class DashboardWidgets
{
    private static $widgetsRemove = [
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
    private static $widgetToAdd = [
        'wpclassified_dashboard_widget' => [
            'title' => 'Lorem Ipsum Title Dashboard Widget',
            'callback' => [__CLASS__,'dashboardWidgetContent']
        ]
    ];

    public function __construct()
    {
        //nothing here
    }

    public static function registerActions()
    {
        add_action('wp_dashboard_setup', [__CLASS__, 'removeDashboardWidgets']);
        add_action('wp_dashboard_setup', [__CLASS__, 'addDashboardWidget']);
    }

    public static function removeDashboardWidgets()
    {
        foreach ( static::$widgetsRemove as $widget_id => $options ) {
            remove_meta_box( $widget_id, $options['page'], $options['context'] );
        }
    }

    public static function addDashboardWidget()
    {
        foreach ( static::$widgetToAdd as $widget_id => $options ) {
            wp_add_dashboard_widget(
                $widget_id,
                $options['title'],
                $options['callback']
            );
        }
    }

    public static function dashboardWidgetContent()
    {
        $string = WPClassifieds::populateTemplateFile( 'DashboardWidget/dashboard_widget_template', [
            //nothing here...yet
        ]);

        echo $string;
    }
}