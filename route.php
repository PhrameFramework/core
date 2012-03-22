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

class Route
{
    /**
     * Application object
     * 
     * @var  Application
     */
    protected $app = null;

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
     * Routing config
     * 
     * @var  Config
     */
    protected $config = null;

    /**
     * Creates Route object
     * 
     * @param   Application  $app  Application object
     * @return  void
     */
    public function __construct($app = null)
    {
        $this->app     = $app ?: Application::instance();
        $this->config  = new Config('route', $this->app);

        // Process request_uri
        $request_uri = trim($this->app->request->server('request_uri'), '/');

        // use regexp to choose the appropriate route
        foreach ($this->config->routes as $old_route => $new_route)
        {
            if (preg_match('#'.$old_route.'#', $request_uri) > 0)
            {
                $request_uri = preg_replace('#'.$old_route.'#', $new_route, $request_uri);
                break;
            }
        }

        $path = explode('/', $request_uri);

        $applications = array($this->app->name, array_shift($path));
        $uris         = array($request_uri, implode('/', $path));

        $routable = false;

        foreach ($applications as $key => $application)
        {
            if ( ! $routable and ! empty($application))
            {
                $this->application  = $application;
                $this->controller   = $uris[$key] ?: $this->config->default_controller;
                $this->action       = $this->config->default_action;
                $this->parameters   = array();

                while ( ! $routable and ! empty($this->controller))
                {
                    $controller_class = '\\'.ucfirst($this->application).'\\Controllers\\'.str_replace(' ', '\\', ucwords(str_replace('/', ' ', strtolower($this->controller))));

                    $routable = is_file(APPLICATIONS_PATH.'/'.$this->application.'/controllers/'.$this->controller.'.php') && method_exists($controller_class, $this->action);

                    if ( ! $routable)
                    {
                        $path = explode('/', $this->controller);

                        $this->parameters  = array_merge_recursive(array($this->action), $this->parameters);
                        $this->action      = array_pop($path);
                        $this->controller  = implode('/', $path);
                    }
                }
            }
        }

        if ( ! $routable)
        {
            $this->application = $this->app->name;
            $this->controller  = $this->config->default_controller;
            $this->action      = '';
        }
    }

}
