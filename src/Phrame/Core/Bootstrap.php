<?php
/**
 * Part of the Phrame
 *
 * @package    Core
 * @version    0.4.0
 * @author     Phrame Development Team
 * @license    MIT License
 * @copyright  2012 Phrame Development Team
 * @link       http://phrame.itworks.in.ua/
 */

namespace Phrame\Core;

/**
 * Bootstrap class
 */
class Bootstrap
{
    /**
     * Loads and initializes package
     * 
     * @param   string  $app_name  Application name
     * @return  void
     */
    public static function init($app_name = null)
    {
        defined('APPLICATIONS_PATH')  or define('APPLICATIONS_PATH', realpath(__DIR__.'/../../../applications'));
        defined('PACKAGES_PATH')      or define('PACKAGES_PATH', realpath(__DIR__.'/../..'));
        defined('PUBLIC_PATH')        or define('PUBLIC_PATH', realpath(__DIR__.'/../../../public'));
        defined('APPLICATION_NAME')   or define('APPLICATION_NAME', getenv('APPLICATION_NAME') ?: 'main');
        defined('APPLICATION_ENV')    or define('APPLICATION_ENV', getenv('APPLICATION_ENV') ?: 'development');

        Applications::run($app_name);
    }

}
