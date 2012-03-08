<?php
/**
 * Part of the Phrame
 *
 * @package    Core
 * @version    0.2.0
 * @author     Phrame Development Team
 * @license    MIT License
 * @copyright  2012 Phrame Development Team
 * @link       http://phrame.itworks.in.ua/
 */

defined('APPLICATIONS_PATH')  or define('APPLICATIONS_PATH', __DIR__.'/../../../../applications');
defined('PACKAGES_PATH')      or define('PACKAGES_PATH', __DIR__.'/../../..');
defined('PUBLIC_PATH')        or define('PUBLIC_PATH', __DIR__.'/../../../../public');
defined('APPLICATION_NAME')   or define('APPLICATION_NAME', getenv('APPLICATION_NAME') ?: 'main');
defined('APPLICATION_ENV')    or define('APPLICATION_ENV', getenv('APPLICATION_ENV') ?: 'development');

/**
 * Registering autoloader
 */
spl_autoload_register(
    function ($class_name)
    {
        $file = str_replace('\\', '/', strtolower($class_name)).'.php';
        
        require_once is_file(APPLICATIONS_PATH.'/'.$file) ? APPLICATIONS_PATH.'/'.$file : PACKAGES_PATH.'/'.$file;
    }
);
