<?php

namespace WPPluginName\CPT;


class CPTName
{
    protected static $name = 'CPTName';

    private $postVariable;


    public function __construct($postID, $data)
    {
        $this->postVariable = get_post($postID);
    }

    public static function registerActions()
    {
        add_action('init', [__CLASS__, 'createCPT']);
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
            //general CPT register logic here
        );
    }

    protected static function getCPTName()
    {
        return strtolower(self::$name);
    }

    public function save()
    {
        wp_update_post([]);

        //Save custom meta stuff here.
    }

    public static function meta_boxes()
    {

    }
}