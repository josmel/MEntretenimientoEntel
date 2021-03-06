<?php
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
		//realpath(APPLICATION_PATH . '/../library'),
    realpath('/var/library'),
		get_include_path(),
)));

require '/var/library/Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();

$config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/private.ini');

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : $config->env));



/** Zend_Application */
require_once '/var/library/Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();
