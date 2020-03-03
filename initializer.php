<?php
/*
Plugin Name: WordPress OOP Plugin Boilerplate
Plugin URI: https://zachis.it
Description: Description of Plugin here
Version: 0.3.4
Author: Zach Smith
Author URI: https://twitter.com/zachisit
*/

namespace WPPluginName;

require_once 'vendor/autoload.php';

define('PM_ABSPATH',dirname(__FILE__));
define('PLUGIN_ROOT',__FILE__);
define('PLUGIN_NAME','WPPluginName');
define('TEMPLATE_PATH',PM_ABSPATH.'/Templates/');

WPPluginName::init();