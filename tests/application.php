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

class Application extends \PHPUnit_Framework_TestCase
{
    protected $application;

    public function setUp()
    {
        $this->application = Core\Application::instance();
    }

    public function test_name()
    {
        $this->assertEquals($this->application->name, APPLICATION_NAME);
    }

    public function test_base_url()
    {
        $this->assertEquals($this->application->config->base_url, 'http://phrame.loc');
    }

}
