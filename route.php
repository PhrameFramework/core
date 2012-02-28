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

        $this->controller  = strtolower( ! empty($path_info[0]) ? $path_info[0] : $this->config->default_controller);
        $this->action      = strtolower( ! empty($path_info[1]) ? $path_info[1] : $this->config->default_action);

        unset($path_info[0]);
        unset($path_info[1]);
        $this->parameters  = $path_info;

        $routable = is_file(APPLICATIONS_PATH.'/'.$this->application->name.'/controllers/'.$this->controller.'.php') && method_exists('Applications\\'.ucfirst($this->application->name).'\\Controllers\\'.ucfirst($this->controller), $this->action);

        if ( ! $routable)
        {
            $this->controller  = $this->config->default_controller;
            $this->action      = '';
        }
    }

}
