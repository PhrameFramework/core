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
 * Controller class
 *
 * @property  View  $layout
 */
class Controller
{
    /**
     * Application name
     * 
     * @var  string
     */
    protected $app_name;

    /**
     * Application object
     * 
     * @var  Application
     */
    protected $app;

    /**
     * Layout view object
     * 
     * @var  View
     */
    public $layout;

    /**
     * Constructs Controller object
     * 
     * @param  string  $app_name  Application name
     */
    public function __construct($app_name = null)
    {
        $this->app_name  = $app_name ?: APPLICATION_NAME;
        $this->app       = Applications::get_instance($this->app_name);
    }

    /**
     * 404 handler
     * 
     * @return  void
     */
    public function error_404()
    {
        $this->layout->content = new View('404');
    }

    /**
     * Handles unroutable calls
     *
     * @param   string  $method
     * @param   array   $parameters
     * @return  void
     */
    public function __call($method, $parameters)
    {
        $this->app->response->status(404);
        $this->error_404();
    }

}
