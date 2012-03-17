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

class Route
{
    /**
     * Application object
     * 
     * @var  Application
     */
    protected $application = null;

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
     * @param   Application  $application  Application object
     * @return  void
     */
    public function __construct($application = null)
    {
        $this->application  = $application ?: Application::instance();
        $this->config       = new Config('route', $this->application);

        // Process request_uri
        $request_uri = trim($this->application->request->server('request_uri'), '/');

        // use regexp to choose the appropriate route
        foreach ($this->config->routes as $old_route => $new_route)
        {
            if (preg_match('#'.$old_route.'#', $request_uri) > 0)
            {
                $request_uri = preg_replace('#'.$old_route.'#', $new_route, $request_uri);
                break;
            }
        }

        $path_info = explode('/', $request_uri);

        $this->controller  = $request_uri ?: $this->config->default_controller;
        $this->action      = $this->config->default_action;
        $this->parameters  = array();

        $routable = false;

        while ( ! $routable and ! empty($this->controller))
        {
            $controller_class = '\\'.ucfirst($this->application->name).'\\Controllers\\'.str_replace(' ', '\\', ucwords(str_replace('/', ' ', strtolower($this->controller))));

            $routable = is_file(APPLICATIONS_PATH.'/'.$this->application->name.'/controllers/'.$this->controller.'.php') && method_exists($controller_class, $this->action);

            if ( ! $routable)
            {
                $path = explode('/', $this->controller);

                $this->parameters  = array_merge_recursive(array($this->action), $this->parameters);
                $this->action      = array_pop($path);
                $this->controller  = implode('/', $path);
            }
        }

        if ( ! $routable)
        {
            $this->controller  = $this->config->default_controller;
            $this->action      = '';
        }
    }

}
