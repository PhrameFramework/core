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

class Applications
{
    /**
     * Application instances
     * 
     * @var  array
     */
    protected static $instances = array();

    /**
     * Returns Application instance
     *
     * @param   string       $app_name  Application name
     * @return  Application
     */
    public static function instance($app_name = null)
    {
        $app_name = $app_name ?: APPLICATION_NAME;

        if ( ! isset(self::$instances[$app_name]))
        {
            self::$instances[$app_name] = new Application($app_name);
        }

        return self::$instances[$app_name];
    }

}
