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

class Application extends \PHPUnit_Framework_TestCase
{
    protected $app;

    public function setUp()
    {
        $this->app = Core\Applications::instance(APPLICATION_NAME, true);
    }

    public function test_base_url()
    {
        $this->assertEquals($this->app->config->base_url, 'http://phrame.loc');
    }

}
