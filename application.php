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
 * Application class
 *
 * @property  string    $name      Application name
 * @property  Config    $config    Config object
 * @property  Request   $request   Request object
 * @property  Route     $route     Route object
 * @property  Response  $response  Response object
 * @property  Asset     $asset     Asset object
 * @property  Lang      $lang      Lang object
 * @property  Error     $error     Error object
 * @property  Log       $log       Log object
 */
class Application
{
    /**
     * Application name
     * 
     * @var  string
     */
    protected $name;

    /**
     * Application configuration
     * 
     * @var  Config
     */
    protected $config;

    /**
     * Request object
     * 
     * @var  Request
     */
    protected $request;

    /**
     * Route object
     * 
     * @var  Route
     */
    protected $route;

    /**
     * Response object
     * 
     * @var  Response
     */
    protected $response;

    /**
     * Asset object
     * 
     * @var  Asset
     */
    protected $asset;

    /**
     * Lang object
     * 
     * @var  Lang
     */
    protected $lang;

    /**
     * Error object
     * 
     * @var  Error
     */
    protected $error;

    /**
     * Log object
     * 
     * @var  Log
     */
    protected $log;

    /**
     * Application constructor
     *
     * @param  string  $name  Application name
     */
    public function __construct($name = '')
    {
        $this->name     = $name ?: APPLICATION_NAME;
        $this->config   = new Config('application', $this->name);

        if ($this->config['use_sessions'] === true and $this->name === APPLICATION_NAME)
        {
            session_start();
        }

        $this->request  = new Request($this->name);

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
        $this->config['base_url'] = $base_url;

        // Error reporting
        if ($this->name === APPLICATION_NAME)
        {
            error_reporting($this->config['error_reporting']);
            ini_set('display_errors', $this->config['display_errors']);

            // error and exception handler
            $this->error   = new Error($this->name);
            set_error_handler(array($this->error, 'error_handler'));
            set_exception_handler(array($this->error, 'exception_handler'));

            // logger
            $this->log     = new Log($this->name);
        }

        // Load packages
        foreach ($this->config['packages'] as $package)
        {
            // Call package's init (\Phrame\Activerecord\Bootstrap::init($this->name) for example)
            call_user_func('\\'.str_replace(' ', '\\', ucwords(str_replace('/', ' ', strtolower($package)))).'\\Bootstrap::init', $this->name);
        }
    }

    /**
     * Magic method for read-only properties
     * 
     * @param   string  $name  Property name
     * @return  mixed
     */
    public function __get($name)
    {
        if (in_array($name, array('name', 'config', 'request', 'route', 'response', 'asset', 'lang', 'error', 'log')))
        {
            if ($name === 'route' and ! isset($this->route))
            {
                $this->route = new Route($this->name);
            }
            elseif ($name === 'response' and ! isset($this->response))
            {
                $this->response = new Response($this->name);
            }
            elseif ($name === 'asset' and ! isset($this->asset))
            {
                $this->asset = new Asset($this->name);

                // Publishing assets
                $this->asset->publish();
            }
            elseif ($name === 'lang' and ! isset($this->lang))
            {
                $this->lang = new Lang($this->name);
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
     * @param   string    $uri  URI to process
     * @return  Response
     */
    public function get_response($uri = null)
    {
        if ( ! empty($uri))
        {
            $this->request->server('request_uri', $uri);
        }

        $this->response  = new Response($this->name);

        return $this->response;
    }

    /**
     * Process default request and renders response
     * 
     * @return  void
     */
    public function run()
    {
        echo $this->get_response()->render();
    }

}
