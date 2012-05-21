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
 * Route class
 * 
 * @property  string  $application  Application
 * @property  string  $controller   Controller
 * @property  string  $action       Action
 * @property  array   $parameters   Parameters
 */
class Route
{
    /**
     * Application name
     * 
     * @var  string
     */
    protected $app_name;

    /**
     * Application
     * 
     * @var  string
     */
    public $application;

    /**
     * Controller
     * 
     * @var  string
     */
    public $controller;

    /**
     * Action
     * 
     * @var  string
     */
    public $action;

    /**
     * Parameters
     * 
     * @var  array
     */
    public $parameters;

    /**
     * Creates Route object
     * 
     * @param  string  $app_name  Application name
     */
    public function __construct($app_name = null)
    {
        $this->app_name  = $app_name ?: APPLICATION_NAME;

        // Process request_uri
        $request_uri = Applications::get_instance($this->app_name)->request->server('request_uri');
        if (strpos($request_uri, '?') !== false)
        {
            $request_uri = substr($request_uri, 0, strpos($request_uri, '?'));
        }
        $request_uri = trim($request_uri, '/');

        $path = explode('/', $request_uri);

        $applications = array($this->app_name, array_shift($path));
        $uris         = array($request_uri, implode('/', $path));

        $routable = false;

        foreach ($applications as $application)
        {
            if ( ! $routable and ! empty($application))
            {
                $config = new Config('route', $application);

                $request_uri = array_shift($uris);

                // use regexp to choose the appropriate route
                foreach ($config['routes'] as $old_route => $new_route)
                {
                    if (preg_match('#'.$old_route.'#', $request_uri) > 0)
                    {
                        $request_uri = preg_replace('#'.$old_route.'#', $new_route, $request_uri);
                        break;
                    }
                }

                $this->application  = $application;
                $this->controller   = $request_uri ?: $config['default_controller'];
                $this->action       = $config['default_action'];
                $this->parameters   = array();

                while ( ! $routable and ! empty($this->controller))
                {
                    $controller_class = '\\'.ucfirst($this->application).'\\Controllers\\'.str_replace(' ', '\\', ucwords(str_replace('/', ' ', strtolower($this->controller))));
                    $routable = is_file(APPLICATIONS_PATH.'/'.$this->application.'/controllers/'.$this->controller.'.php') && method_exists($controller_class, $this->action);
                    if ( ! $routable)
                    {
                        $controller_class = '\\'.ucfirst($this->application).'\\Controllers\\'.str_replace(' ', '\\', ucwords(str_replace('/', ' ', strtolower($this->controller.'/'.$config['default_controller']))));
                        $routable = is_file(APPLICATIONS_PATH.'/'.$this->application.'/controllers/'.$this->controller.'/'.$config['default_controller'].'.php') && method_exists($controller_class, $this->action);
                        if ( ! $routable)
                        {
                            $path = explode('/', $this->controller);

                            $this->parameters  = array_merge_recursive(array($this->action), $this->parameters);
                            $this->action      = array_pop($path);
                            $this->controller  = implode('/', $path);
                        }
                        else
                        {
                            $this->controller  = $this->controller.'/'.$config['default_controller'];
                        }
                    }
                }
            }
        }

        if ($routable)
        {
            if ($this->application !== APPLICATION_NAME)
            {
                // fix base_url for subapplication
                Applications::get_instance($this->application)->config['base_url'] = trim(Applications::get_instance($this->app_name)->config['base_url'], '/').'/'.$this->application;
            }
        }
        else
        {
            $config = new Config('route', $this->app_name);

            $this->application = $this->app_name;
            $this->controller  = $config['default_controller'];
            $this->action      = '';
        }
    }

}
