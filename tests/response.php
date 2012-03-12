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

class Response extends \PHPUnit_Framework_TestCase
{
    protected $application;

    public function setUp()
    {
        $this->application  = Core\Application::instance();
    }

    public function test_body()
    {
        $this->assertEquals($this->application->response('/')->body()->content, 'Hello, world');
    }

}
