<?php
/**
 * Part of the Phrame
 *
 * @package    Core
 * @version    0.0.0
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
    public $name = '';

    /**
     * Application configuration
     * 
     * @var  Config
     */
    public $config = null;

    /**
     * Request object
     * 
     * @var  Request
     */
    public $request = null;

    /**
     * Route object
     * 
     * @var  Route
     */
    public $route = null;

    /**
     * Response object
     * 
     * @var  Response
     */
    public $response = null;

    /**
     * Application constructor (protected)
     *
     * @param  string  $name  Application name
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
        if ($_SERVER['HTTP_HOST'])
        {
            $base_url .= 'http';
            if ((isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] != 'off') or ( ! isset($_SERVER['HTTPS']) and $_SERVER['SERVER_PORT'] == 443))
            {
                $base_url .= 's';
            }
            $base_url .= '://'.$_SERVER['HTTP_HOST'];
        }
        if ($_SERVER['SCRIPT_NAME'])
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
            call_user_func('\\Phrame\\'.ucfirst(strtolower($package)).'\\Bootstrap::init', $this);
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
     * Process request
     * 
     * @param   Request   $request  Request to process
     * @return  Response
     */
    public function process($request = null)
    {
        $this->request   = $request ?: new Request($this);
        $this->route     = new Route($this);
        $this->response  = new Response($this);

        return $this->response;
    }

    /**
     * Process URI
     * 
     * @param   string  $uri  URI to process
     * @return  Response
     */
    public function process_uri($uri)
    {
        $request = new Request($this);
        $request->server('request_uri', $uri);

        return $this->process($request);
    }

    /**
     * Process default request and renders response
     */
    public function run()
    {
        echo $this->process()->render();
    }

}
