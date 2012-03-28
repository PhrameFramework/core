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

class View extends \PHPUnit_Framework_TestCase
{
    protected $app;

    protected $view;

    public function setUp()
    {
        $this->app   = Core\Applications::instance();
        $this->view  = new Core\View('home', array(), $this->app);
    }

    public function test_data()
    {
        $this->view->content = 'Hello, world';
        $this->assertEquals($this->view->content, 'Hello, world');
    }

    public function test_render()
    {
        $this->view->content = 'Hello, world';
        $this->assertEquals($this->view->render(), '<div>Hello, world</div>');
    }

    public function test_layout()
    {
        $this->assertEquals($this->app->response('about')->body->render(), '<html><body>About Phrame</body></html>');
    }

}
