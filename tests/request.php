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

class Request extends \PHPUnit_Framework_TestCase
{
    protected $application;

    protected $request;

    public function setUp()
    {
        $_SERVER['SERVER_PROTOCOL']  = 'HTTP/1.1';
        $_SERVER['REQUEST_METHOD']   = 'GET';

        $_SERVER['TEST']   = 'server_test';
        $_GET['test']      = 'get_test';
        $_POST['test']     = 'post_test';
        $_COOKIE['test']   = 'cookie_test';
        $_SESSION['test']  = 'session_test';

        $this->application  = Core\Application::instance();

        $this->application->config->use_sessions = true;
        
        $this->request      = new Core\Request($this->application);
    }

    public function test_escape()
    {
        $this->assertEquals(
            $this->request->escape('<script type="text/javascript>alert("1")</script>"'),
            '&lt;script type=&quot;text/javascript&gt;alert(&quot;1&quot;)&lt;/script&gt;&quot;'
        );
    }

    public function test_protocol()
    {
        $this->assertEquals($this->request->protocol(), 'HTTP/1.1');
    }

    public function test_method()
    {
        $this->assertEquals($this->request->method(), 'GET');
    }

    public function test_server()
    {
        $this->assertEquals($this->request->server('test'), 'server_test');

        $this->request->server('test', 'test');

        $this->assertEquals($this->request->server('test'), 'test');
    }

    public function test_get()
    {
        $this->assertEquals($this->request->get('test'), 'get_test');

        $this->request->get('test', 'test');

        $this->assertEquals($this->request->get('test'), 'test');
    }

    public function test_post()
    {
        $this->assertEquals($this->request->post('test'), 'post_test');

        $this->request->post('test', 'test');

        $this->assertEquals($this->request->post('test'), 'test');
    }

    public function test_cookie()
    {
        $this->assertEquals($this->request->cookie('test'), 'cookie_test');

        $this->request->cookie('test', 'test');

        $this->assertEquals($this->request->cookie('test'), 'test');
    }

    public function test_session()
    {
        $this->assertEquals($this->request->session('test'), 'session_test');

        $this->request->session('test', 'test');

        $this->assertEquals($this->request->session('test'), 'test');
    }

}