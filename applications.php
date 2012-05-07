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
 * Applications class
 */
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
     * @param   string       $app_name  Application name and uri (optional)
     * @return  Application
     */
    public static function instance($app_name = null)
    {
        $app_name = $app_name && is_string($app_name) ? $app_name : APPLICATION_NAME;
        $uri      = explode('/', $app_name);
        $app_name = array_shift($uri) ?: APPLICATION_NAME;
        $uri      = implode('/', $uri);

        if ( ! isset(self::$instances[$app_name]))
        {
            self::$instances[$app_name] = new Application($app_name);
        }

        if ( ! empty($uri))
        {
            self::$instances[$app_name]->request->server('request_uri', $uri);
        }        

        return self::$instances[$app_name];
    }

    /**
     * Returns content of the provided app_name and uri
     * 
     * @param   string  $app_name  Application name and uri (optional)
     * @return  string
     */
    public static function content($app_name = null)
    {
        return self::instance($app_name)->response()->body->content;
    }

    /**
     * Runs application
     * 
     * @param   string  $app_name  Application name and uri (optional)
     * @return  void
     */
    public static function run($app_name = null)
    {
        self::instance($app_name)->run();
    }

}
