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

class Controller
{
    /**
     * Application object
     * 
     * @var  Application
     */
    protected $application = null;

    /**
     * Layout view object
     * 
     * @var  View
     */
    public $layout = null;

    /**
     * Constructs Controller object
     * 
     * @param  Application  $application  Application object
     */
    public function __construct($application = null)
    {
        $this->application = $application ?: Application::instance();
    }

    /**
     * 404 handler
     */
    public function error_404()
    {
        $this->layout->content = '404 Not Found';
    }

    /**
     * Handles unroutable calls
     *
     * @param  string  $method
     * @param  array   $parameters
     */
    public function __call($method, $parameters)
    {
        $this->application->response->status(404);

        $this->error_404();
    }

}
