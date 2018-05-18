<?php

namespace WPPluginName;


class Utility
{

    /**
     * @param $templateFile
     * @param array $args
     * @return string
     * @throws \Exception
     */
    public static function populateTemplateFile($templateFile, $args = [])
    {
        ob_start();

        $templateDirectory = dirname(__FILE__) . '/Templates';
        $templateFile = $templateFile . '.template.php';

        if(file_exists($templateDirectory . '/' . $templateFile)){
            extract($args);
            include $templateDirectory . '/' . $templateFile;
        } else {
            error_log('Template file does not exist');
            throw new \Exception('Template file does not exist');
        }

        return ob_get_clean();
    }

    /**
     * @param $imageURL
     * @param bool $alt
     * @param bool $class
     * @return string
     */
    public static function generalImageCreator($imageURL,$alt = false,$class = false)
    {
        return '<img src='.$imageURL.' alt='.$alt.' class='.$class.' />';
    }
}