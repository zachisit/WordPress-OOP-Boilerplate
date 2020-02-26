<?php

namespace WPPluginName\CPT;

/**
 * Class PostTypeFactory
 * @package WPPluginName\CPT
 */
final class PostTypeFactory
{
    /** @var array */
    private $postTypes = [];

    /** @var null */
    private static $instance = null;


    /**
     * PostTypeFactory constructor.
     */
    private function __construct()
    {
        add_action('init', [$this, 'createCPTs'],20);
        //add_action('init', [$this, 'registerTaxes'],21);
        //add_action('add_meta_boxes',[__CLASS__, 'createMetaBoxes']);
    }

    /**
     * @return self
     */
    public static function getInstance(): self
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @return PostTypeFactory
     */
    public static function init(): self
    {
        return self::getInstance();
    }

    /**
     * @param string $postType
     * @param array $options
     * @param array $taxonomies
     */
    public static function registerPostType(string $postType, array $options = [], array $taxonomies = []): void
    {
        $factory = self::getInstance();
        if (!isset($factory->postTypes[$postType]) && !$factory->postTypes[$postType]['initialized']) {
            $factory->postTypes[$postType] = [
                'initialized' => false,
                'postType' => $postType,
                'options' => $options,
                'taxonomies' => $taxonomies
            ];
        }

    }

    public function createCPTs(): void
    {
        foreach ($this->postTypes as $postType => $post) {
            if ($post['initialized']) {
                continue;
            }
            $o = $post['options'] ?? [];
            $l = $o['labels'] ?? [];
            $labels = [];
            $labels['name'] = $l['name'] ?? $postType;
            $labels['singular_name'] = $l['singular_name'] ?? $postType;
            $labels['add_new'] = $l['add_new'] ?? 'Add New';
            $labels['add_new_item'] = $l['add_new_item'] ?? 'Add New '.$labels['singular_name'];
            $labels['edit_item'] = $l['edit_item'] ?? 'Edit '.$labels['singular_name'];
            $labels['new_item'] = $l['new_item'] ?? 'New '.$labels['singular_name'];
            $labels['search_items'] = $l['search_items'] ?? 'Search '.$labels['name'];
            $labels['not_found'] = $l['not_found'] ?? 'Search '.$labels['name'];

            $options = [
                'labels' => array_merge($labels,$l),
                'public' => true,
                'supports' => $o['supports'] ?? ['title', 'revisions'],
                'capability_type' => 'post',
                'rewrite' => $o['rewrite'] ?? ["slug" => $postType],
                'menu_position' => (int)($o['menu_position'] ?? 10),
                'menu_icon' => (string)($o['menu_icon'] ?? 'dashicons-id'),
                'has_archive' => (bool)($o['has_archive'] ?? true),
                'show_in_nav_menus' => (bool)($o['show_in_nav_menus'] ?? true),
            ];

            register_post_type(
                $postType,
                $options
            );
            $this->postTypes[$postType]['initialized'] = true;
        }
    }
}