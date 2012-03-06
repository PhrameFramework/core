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

namespace Phrame\Core;

class Application
{
    /**
     * Application instances
     * 
     * @var  array
     */
    protected static $instances = array();

    /**
     * Application name
     * 
     * @var  string
     */
    protected $name = '';

    /**
     * Application configuration
     * 
     * @var  Config
     */
    protected $config = null;

    /**
     * Request object
     * 
     * @var  Request
     */
    protected $request = null;

    /**
     * Route object
     * 
     * @var  Route
     */
    protected $route = null;

    /**
     * Response object
     * 
     * @var  Response
     */
    protected $response = null;

    /**
     * Application constructor (protected)
     *
     * @param   string  $name  Application name
     * @return  void
     */
    protected function __construct($name = '')
    {
        $this->name    = $name ?: APPLICATION_NAME;
        $this->config  = new Config('application', $this);

        if ($this->config->use_sessions === true)
        {
            session_start();
        }

        // set base_url
        $base_url = '';
        if (isset($_SERVER['HTTP_HOST']))
        {
            $base_url .= 'http';
            if ((isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] != 'off') or ( ! isset($_SERVER['HTTPS']) and isset($_SERVER['SERVER_PORT']) and $_SERVER['SERVER_PORT'] == 443))
            {
                $base_url .= 's';
            }
            $base_url .= '://'.$_SERVER['HTTP_HOST'];
        }
        if (isset($_SERVER['SCRIPT_NAME']))
        {
            $base_url .= rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
        }
        $this->config->base_url = $base_url;

        // Error reporting
        if ($this->name === APPLICATION_NAME)
        {
            error_reporting($this->config->error_reporting);
            ini_set('display_errors', $this->config->display_errors);
        }

        // Load packages
        foreach ($this->config->packages as $package)
        {
            // Call package's init (\Phrame\Activerecord\Bootstrap::init($this) for example)
            call_user_func('\\'.str_replace(' ', '\\', ucwords(str_replace('/', ' ', strtolower($package)))).'\\Bootstrap::init', $this);
        }
    }

    /**
     * Returns Application instance
     *
     * @param   string       $application_name  Application name
     * @return  Application
     */
    public static function instance($application_name = null)
    {
        $application_name = $application_name ?: APPLICATION_NAME;

        if ( ! isset(self::$instances[$application_name]))
        {
            self::$instances[$application_name] = new Application($application_name);
        }

        return self::$instances[$application_name];
    }

    /**
     * Magic method for read-only properties
     * 
     * @param   string  $name  Property name
     * @return  mixed
     */
    public function __get($name)
    {
        if (in_array($name, array('name', 'config', 'request', 'route', 'response')))
        {
            if ($name === 'response' and ! isset($this->response))
            {
                $this->response = $this->response();
            }

            return $this->$name;
        }
        else
        {
            return null;
        }

    }

    /**
     * Process request (or provided uri) and returns response
     * 
     * @param   string  $uri  URI to process
     * @return  Response
     */
    public function response($uri = null)
    {
        $this->request   = new Request($this);

        if ( ! empty($uri))
        {
            $this->request->server('request_uri', $uri);
        }

        $this->route     = new Route($this);
        $this->response  = new Response($this);

        return $this->response;
    }

    /**
     * Process default request and renders response
     * 
     * @return  void
     */
    public function run()
    {
        echo $this->response()->render();
    }

}
