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

class Response extends \PHPUnit_Framework_TestCase
{
    protected $app;

    public function setUp()
    {
        $this->app = Core\Applications::get_instance(APPLICATION_NAME, true);
    }

    public function test_body()
    {
        $this->assertEquals($this->app->get_response('/')->body->content, 'Hello, world');
    }

}
