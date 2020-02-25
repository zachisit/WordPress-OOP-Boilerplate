<?php

namespace WPPluginName\CPT;


use WPPluginName\Utility\ViewBuilder;

/**
 * Class CPTName
 * @package WPPluginName\CPT
 */
/*
 * currently attaching everything to a single
 * CPT entry including building the CPT itself
 *
 * @TODO
 * - move CPT into abstract class, then additional
 *      CPTs will extend with specific properties
 *      applicable to that CPT
 */
final class CPTName
{
    /** @var string */
    protected static $name = 'CPTName';
    /** @var string */
    protected static $cpt_name = 'cpt_formal_name';
    /** @var string */
    protected static $tax_name = 'cpt_formal_name_cat';

    /** @var \WP_Post */
    private $postVariable;


    /**
     * CPTName constructor.
     * @param int $postID
     * @param array $data
     */
    public function __construct(int $postID, array $data)
    {
        $this->postVariable = get_post($postID);
    }

    public static function registerActions(): void
    {
        add_action('init', [__CLASS__, 'createCPT']);
        add_action('init', [__CLASS__, 'registerTax']);
        add_action('add_meta_boxes',[__CLASS__, 'create_meta_box']);
    }

    public static function registerFilters()
    {
        //
    }

    /**
     * @param string $name
     * @return null
     */
    public function __get(string $name): ?string
    {
        return $this->postVariable->{$name} ?? null;
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->postVariable, $name)) {
            return call_user_func_array([$this->postVariable, $name], $arguments);
        }

        throw new \Exception("Method {$name} is not defined.");
    }


    public static function createCPT(): void
    {
        register_post_type(
            self::getCPTName(),
            [
                'labels' => [
                    'name' => __(self::getCPTName()),
                    'singular_name' => __(self::getCPTName()),
                    'add_new' => __('Add New'),
                    'add_new_item' => __('Add New '.self::getCPTName()),
                    'edit_item' => __('Edit '.self::getCPTName()),
                    'new_item' => __('Add New '.self::getCPTName()),
                    'view_item' => __('View '.self::getCPTName()),
                    'search_items' => __('Search '.self::getCPTName()),
                    'not_found' => __('No '.self::getCPTName().'s found'),
                    'not_found_in_trash' => __('No '.self::getCPTName().' found in trash')
                ],
                'public' => true,
                'supports' => [ 'title', 'thumbnail', 'revisions', 'page-attributes' ],
                'capability_type' => 'post',
                'rewrite' => ["slug" => self::getCPTPostName()],
                'menu_position' => 5,
                'menu_icon' => 'dashicons-id',
                'has_archive' => true,
                'show_in_nav_menus' => false,
            ]
        );
    }

    /**
     * Returns CPT name used in CPT query logic
     *
     * @return string - 'classified'
     */
    protected static function getCPTPostName(): string
    {
        return self::$cpt_name;
    }

    /**
     * Returns human friendly name of CPT
     *
     * @return string - 'Classified'
     */
    protected static function getCPTName(): string
    {
        return self::$name;
    }

    /**
     * Gets Taxonomy Name for CPT
     *
     * @return string
     */
    public static function getCPTTaxName(): string
    {
        return self::$tax_name;
    }

    public function save(): void
    {
        wp_update_post([]);

        //Save custom meta stuff here.
    }

    public static function create_meta_box(): void
    {
        add_meta_box('metabox_formal_name', 'Lorem Ipsum Dorem', [__CLASS__, 'create_meta_values'], self::getCPTPostName());
    }

    public static function create_meta_values(): void
    {
        global $post;

        $viewArgs = [
            'nonce' => wp_nonce_field( plugin_basename(__FILE__), self::getCPTPostName().'_noncename' ),
            'postMeta' => get_post_meta ($post->ID)
        ];

        try {
            $view = new ViewBuilder(['args' => $viewArgs]);
            $view->setTemplate('Metabox/metabox_name');
            echo $view->render();
        } catch (\Exception $exception) {
            trigger_error($exception->getMessage(),E_USER_WARNING);
        }
    }

    public static function registerTax()
    {
        register_taxonomy(self::getCPTTaxName(),
            [self::getCPTPostName()],
            [
                'hierarchical' => true,
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'label' => 'Lorem Ipsum Label',
                'rewrite' => true
            ]
        );
    }
}