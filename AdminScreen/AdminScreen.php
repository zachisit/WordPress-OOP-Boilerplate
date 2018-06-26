<?php

namespace WPPluginName\AdminScreen;


use WPPluginName\Utility;

class AdminScreen
{
    public $page_title;
    public $menu_title;
    public $capability = 'manage_options';
    public $menu_slug;
    public $dashicon;
    public $position = null;
    public $screen_template;

    private static $screens = [];
    private $parent;

    protected function __construct($data)
    {
        foreach ($data as $k => $v) {
            if (property_exists($this, $k)) {
                $this->{$k} = $v;
            }
        }

        $this->registerActions();
    }

    /**
     * @param $data
     * @return static - returns instance of class this was called from
     * @throws \Exception
     */
    public static function add_admin_screen($data)
    {
        $data = json_decode(json_encode($data),true); //data can be object or array

        if ($data
            && $data['menu_slug']
            && !isset(self::$screens[$data['menu_slug']])
        ) {
            self::$screens[$data['menu_slug']] = new static($data);
        } else {
            throw new \Exception('invalid screen');
        }

        return self::$screens[$data['menu_slug']];
    }

    public function registerActions()
    {
        add_action('admin_menu', [$this, 'configure']);
    }

    public function configure()
    {
        if ($this->parent) { //is a submenu page
            add_submenu_page(
                $this->parent,
                $this->get_page_title(),
                $this->get_menu_title(),
                $this->get_capability(),
                $this->get_menu_slug(),
                [$this, 'render_admin_screen']
            );
        } else {
            add_menu_page(
                $this->get_page_title(),
                $this->get_menu_title(),
                $this->get_capability(),
                $this->get_menu_slug(),
                [$this, 'render_admin_screen'],
                $this->get_dashicon()
            );
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function render_admin_screen()
    {
        echo Utility::populateTemplateFile('AdminScreen/'.$this->get_screen_template(),[
            //'data' => $data_here
        ]);
    }

    public function get_dashicon()
    {
        return $this->dashicon;
    }

    public function get_page_title()
    {
        return $this->page_title;
    }

    public function get_menu_title()
    {
        return $this->menu_title;
    }

    public function get_capability()
    {
        return $this->capability;
    }

    public function get_menu_slug()
    {
        return $this->menu_slug;
    }

    public function get_screen_template()
    {
        return $this->screen_template;
    }
}