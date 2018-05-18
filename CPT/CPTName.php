<?php

namespace WPPluginName\CPT;


class CPTName
{
    protected static $name = 'CPTName';
    protected static $cpt_name = 'cpt_formal_name';
    protected static $tax_name = 'cpt_formal_name_cat';

    private $postVariable;


    public function __construct($postID, $data)
    {
        $this->postVariable = get_post($postID);
    }

    public static function registerActions()
    {
        add_action('init', [__CLASS__, 'createCPT']);
        add_action('init', [__CLASS__, 'registerTax']);
        add_action('add_meta_boxes',[__CLASS__, 'create_meta_box']);
    }

    public static function registerFilters()
    {

    }

    public function __get($name)
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


    public static function createCPT()
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
    protected static function getCPTPostName()
    {
        return self::$cpt_name;
    }

    /**
     * Returns human friendly name of CPT
     *
     * @return string - 'Classified'
     */
    protected static function getCPTName()
    {
        return self::$name;
    }

    /**
     * Gets Taxonomy Name for CPT
     *
     * @return string
     */
    public static function getCPTTaxName()
    {
        return self::$tax_name;
    }

    public function save()
    {
        wp_update_post([]);

        //Save custom meta stuff here.
    }

    public static function create_meta_box()
    {
        add_meta_box('metabox_formal_name', 'Lorem Ipsum Dorem', [__CLASS__, 'create_meta_values'], self::getCPTPostName());
    }

    public static function create_meta_values()
    {
        global $post;

        //noncename needed to verify where the data originated
        wp_nonce_field( plugin_basename(__FILE__), self::getCPTPostName().'_noncename' );

        $string = WPClassifieds::populateTemplateFile( 'Metabox/metabox_template_name',
            get_post_meta ($post->ID)
        );

        echo $string;
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