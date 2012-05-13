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

namespace Phrame\Core\Tests;

use Phrame\Core;

class Route extends \PHPUnit_Framework_TestCase
{
    protected $app;

    public function setUp()
    {
        $this->app = Core\Applications::get_instance(APPLICATION_NAME, true);
    }

    public function test_default_route()
    {
        // generate response
        $this->app->get_response('/');

        // check routes
        $this->assertEquals($this->app->route->controller, 'index');
        $this->assertEquals($this->app->route->action, 'index');
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
        $this->app->get_response('main');

        // check routes
        $this->assertEquals($this->app->route->controller, 'index');
        $this->assertEquals($this->app->route->action, 'index');
    }

    public function test_unroutable_route()
    {
        // generate response
        $this->app->get_response('404');

        // check routes
        $this->assertEquals($this->app->route->controller, 'index');
        $this->assertEquals($this->app->route->action, '');
    }

}
