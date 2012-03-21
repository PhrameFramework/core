<?php
/**
 * Part of the Phrame
 *
 * @package    Core
 * @version    0.3.0
 * @author     Phrame Development Team
 * @license    MIT License
 * @copyright  2012 Phrame Development Team
 * @link       http://phrame.itworks.in.ua/
 */

namespace Phrame\Core;

class Bootstrap
{
    /**
     * Loads and initializes package
     * 
     * @param   Core\Application  $application  Application object
     * @return  void
     */
    public static function init($application = null)
    {
        defined('APPLICATIONS_PATH')  or define('APPLICATIONS_PATH', __DIR__.'/../../../applications');
        defined('PACKAGES_PATH')      or define('PACKAGES_PATH', __DIR__.'/../..');
        defined('PUBLIC_PATH')        or define('PUBLIC_PATH', __DIR__.'/../../../public');
        defined('APPLICATION_NAME')   or define('APPLICATION_NAME', getenv('APPLICATION_NAME') ?: 'main');
        defined('APPLICATION_ENV')    or define('APPLICATION_ENV', getenv('APPLICATION_ENV') ?: 'development');

        set_exception_handler('Phrame\Core\Error::exception_handler');
        set_error_handler('Phrame\Core\Error::error_handler');

        $application = $application ?: Application::instance();

        $application->run();
    }

}
