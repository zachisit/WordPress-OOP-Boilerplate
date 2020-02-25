<?php

namespace WPPluginName\Shortcodes;

use WPPluginName\Utility\Helper;
use WPPluginName\Utility\ViewBuilder;

/**
 * Class Shortcode
 * @package WPPluginName\Shortcodes
 */
abstract class Shortcode
{
    /** @var bool  */
    protected $hasAjax = false;
    /** @var bool  */
    protected $hasAdditionalCSS = false;
    /** @var array  */
    protected $additionalLocalScripts = [];
    /** @var array  */
    protected $additionalExternalScripts = [];
    /** @var array  */
    protected $additionalCSS = [];

    /** @var array */
    private static $definedShortCodes = [
        'HelloWorld'
    ];
    /** @var array */
    private static $initializedShortCodes = [];

    abstract protected function doShortcode(string $atts);

    abstract protected function getTemplateName();


    public function __construct()
    {
        if (Helper::getClass($this)) {
            add_shortcode(Helper::getClass($this), [$this, 'setUpShortcode']);
            $this->addAjaxAction();
            $this->registerFrontendScripts();
        }
        if (!empty($this->additionalLocalScripts)) {
            $this->addAdditionScriptAction();
            $this->registerAdditionalFrontendScripts();
        }
        //@TODO:below not loading correctly after page load
        if (!empty($this->additionalExternalScripts)) {
            $this->addAdditionalExternalScripts();
            $this->registerAdditionalExternalScripts();
        }
        if ($this->hasAdditionalCSS) {
            $this->registerAdditionalFrontendCSS();
        }
    }

    /**
     * @param string $atts
     * @return mixed
     */
    public function setUpShortcode(string $atts)
    {
        if ($this->hasAjax) {
            $scriptName = Helper::getClass($this);
            wp_enqueue_script($scriptName);
            //PetManagement::localizedAjaxURL($scriptName);
        }
        if (!empty($this->additionalLocalScripts)) {
            foreach ($this->additionalLocalScripts as $scriptName) {
                wp_enqueue_script($scriptName);
            }
        }

        return $this->doShortcode($atts);
    }

    public static function addShortcodes(): void
    {
        foreach (self::$definedShortCodes as $shortCode) {
            if (!isset(self::$initializedShortCodes[$shortCode])) {
                $shortCodeObject = "\\".PLUGIN_NAME."\Shortcodes\\".$shortCode;
                self::$initializedShortCodes[$shortCode] = new $shortCodeObject;
            }
        }
    }

    public function addAjaxAction(): void
    {
        $scriptName = Helper::getClass($this);

        add_action('wp_ajax_'.$scriptName, [self::class, 'doAjax']);
        //add_action('wp_ajax_nopriv_'.$scriptName, [$this, $scriptName]);
        add_action('wp_enqueue_scripts', [$this, 'registerFrontendScripts']);
    }

    public static function doAjax(): void
    {
        $class = $_REQUEST['action'];
        $fullClassName = __NAMESPACE__.'\\'.$class;

        if (class_exists($fullClassName)) {
            /** @var self $instance */
            $instance = new $fullClassName;
            $instance::validateNonce($_POST['security']);
            method_exists($instance,$class) and $instance->{$class}();
        }
    }

    public function addAdditionScriptAction(): void
    {
        add_action('wp_enqueue_scripts',[$this, 'registerAdditionalFrontendScripts']);
    }

    public function addAdditionalExternalScripts(): void
    {
        add_action('wp_enqueue_scripts',[$this, 'registerAdditionalExternalScripts']);
    }

    public function registerAdditionalFrontendScripts(): void
    {
        foreach ($this->additionalLocalScripts as $script) {
            wp_register_script($script, plugins_url($script.'.js',PLUGIN_ROOT),null, rand(), true);
        }
    }

    public function registerAdditionalExternalScripts(): void
    {
        foreach ($this->additionalExternalScripts as $script) {
            wp_register_script($script, $script, [], rand());
        }
    }

    public function registerAdditionalFrontendCSS(): void
    {
        foreach ($this->additionalCSS as $style) {
            wp_enqueue_style($style, plugins_url($style.'.css',PLUGIN_ROOT));
        }
    }

    public function registerFrontendScripts(): void
    {
        $scriptName = Helper::getClass($this);
        $url = plugins_url('js/'.$scriptName.'.js',PLUGIN_ROOT);
        $path = PM_ABSPATH.'/js/'.$scriptName.'.js';

        if (file_exists($path)) {
            wp_register_script($scriptName, $url,['jquery'],rand(),true);
        } else {
            $this->hasAjax = false;
        }
    }

    /**
     * @param array $args
     */
    public function createView(array $args = []): void
    {
        try {
            $view = new ViewBuilder(['args' => $args]);
            $view->setTemplate('Shortcode/' . $this->getTemplateName());
            echo $view->render();
        } catch (\Exception $exception) {
            trigger_error($exception->getMessage(),E_USER_WARNING);
        }
    }
}