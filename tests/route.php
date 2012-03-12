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

namespace Phrame\Core\Tests;

use Phrame\Core;

class Route extends \PHPUnit_Framework_TestCase
{
    protected $application;

    public function setUp()
    {
        $this->application  = Core\Application::instance();
    }

    public function test_default_route()
    {
        // generate response
        $this->application->response();

        // check routes
        $this->assertEquals($this->application->route->controller, 'home');
        $this->assertEquals($this->application->route->action, 'index');
    }

    /**
     * Tests custom route
     * 
     * See config in sandbox/applications/main/config/route.php
     * 
     * @return void
     */
    public function test_custom_route()
    {
        // generate response
        $this->application->response('main');

        // check routes
        $this->assertEquals($this->application->route->controller, 'home');
        $this->assertEquals($this->application->route->action, 'index');
    }

    public function test_unroutable_route()
    {
        // generate response
        $this->application->response('404');

        // check routes
        $this->assertEquals($this->application->route->controller, 'home');
        $this->assertEquals($this->application->route->action, '');
    }

}
