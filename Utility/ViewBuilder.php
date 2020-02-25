<?php

namespace WPPluginName\Utility;


/**
 * Class ViewBuilder
 * @package WPPluginName\Utility
 */
final class ViewBuilder
{
    /** @var array  */
    protected $params = [];
    /** @var array  */
    protected $getterParams = [];
    /** @var \Twig_Environment */
    protected $twig;
    /** @var \Twig_Loader_Filesystem */
    protected $twigLoader;
    /** @var string */
    protected $template = '';


    /**
     * ViewBuilder constructor.
     * @param array $data
     * @param null $loader
     * @param null $twig
     * @throws \Exception
     */
    public function __construct(array $data, $loader = null, $twig = null)
    {
        if (is_array($data)) {
            $this->params = $data;
        }
        if ($_GET) {
            $this->getterParams = $_GET;
        }
        if (!($twig instanceof \Twig_Environment)) {
            $this->createTemplateEngine();
        } else {
            $this->twigLoader = $loader;
            $this->twig = $twig;
        }
    }

    /**
     * Return a JavaScript friendly view used in frontend DOM painting
     *
     * @param array $viewArray
     * @param string $templateName
     * @return array
     */
    public static function buildJavaScriptViewContents(array $viewArray, string $templateName): array
    {
        $renderArray = [];

        try {
            $view = new self($viewArray);
            $view->setTemplate($templateName);
            $renderArray = [ 'view' => $view->render() ];
        } catch (\Exception $exception) {
            trigger_error($exception->getMessage(),E_USER_WARNING);
        }

        return $renderArray;
    }

    /**
     * @throws \Exception
     */
    private function createTemplateEngine(): void
    {
        try {
            $loader = new \Twig_Loader_Filesystem();
            $loader->addPath(PM_ABSPATH . '/Templates');
            $twig = new \Twig_Environment($loader);
            $this->twigLoader = $loader;
            $this->twig = $twig;

            $this->twig->addGlobal('home_url',home_url());
            $this->twig->addGlobal('logoutURL',home_url('/wp-login.php?action=logout'));
        } catch (\Twig_Error_Loader $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * @return array
     */
    private function buildTemplateParams(): array
    {
        return $this->params;
    }

    /**
     * @param bool $echo
     * @return null|string
     * @throws \Exception
     */
    public function render(bool $echo = false): ?string
    {
        try {
            $this->buildTemplateParams();
            $template = $this->twig->load($this->template);
        } catch (\Twig_Error_Loader $exception) {
            throw new \Exception('The requested template ('.$this->template.') could not be found: '. $exception->getMessage());
        }

        $content = $template->render($this->params);
        if ($echo) {
            echo $content;
        } else {
            return $content;
        }
    }

    /**
     * @param $template
     */
    public function setTemplate($template)
    {
        $fullTemplate = $template.'.html.twig';
        $fileName = PM_ABSPATH.'/Templates/'.$fullTemplate;
        Helper::errorLog('looking for template '.$fileName);

        if (file_exists($fileName)) {
            $this->template = $fullTemplate;
        } else {
            error_log('setTemplate: File template does not exist');
        }
    }
}