<?php

namespace WPPluginName\Shortcode;

use WPPluginName\Utility\Helper;
use WPPluginName\Utility\ViewBuilder;

/**
 * Class Shortcode
 * @package WPPluginName\Shortcode
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

    /** @var string */
    protected static $shortcodeTag;
    /** @var array */
    private static $definedShortCodes = [
        'HelloWorld'
    ];
    /** @var array */
    private static $initializedShortCodes = [];

    /**
     * @param string $atts
     * @return mixed
     */
    abstract protected function doShortcode(string $atts);

    /**
     * @return string
     */
    abstract protected function getTemplateName(): string;

    
    /**
     * Shortcode constructor.
     */
    public function __construct()
    {
        //use passed in shortcode tag or the class name
        $tag = static::$shortcodeTag ?? Helper::getClass($this);
        add_shortcode($tag, [$this, 'setUpShortcode']);
        $this->addAjaxAction();
        $this->registerFrontendScripts();

        if (!empty($this->additionalLocalScripts)) {
            $this->addAdditionScriptAction();
            $this->registerAdditionalFrontendScripts();
        }
        //@TODO:below not loading correctly after page load
        if (!empty($this->additionalExternalScripts)) {
            $this->addAdditionalExternalScripts();
            $this->registerAdditionalExternalScripts();
        }
        if (!empty($this->additionalCSS)) {
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
                $shortCodeObject = __NAMESPACE__."\\".$shortCode;
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
            wp_register_script($script, plugins_url($script.'.js',PLUGIN_NAME),null, rand(), true);
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
            wp_enqueue_style($style, PLUGIN_ROOT.'/'.$style.'.css');
        }
    }

    public function registerFrontendScripts(): void
    {
        $scriptName = Helper::getClass($this);
        $url = plugins_url('js/'.$scriptName.'.js',PLUGIN_NAME);
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