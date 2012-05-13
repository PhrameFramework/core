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

class Asset extends \PHPUnit_Framework_TestCase
{
    protected $app;

    protected $asset;

    public function setUp()
    {
        $this->app    = Core\Applications::get_instance(APPLICATION_NAME, true);
        $this->asset  = new Core\Asset($this->app->name);
    }

    public function test_css()
    {
        $this->assertEquals(
            preg_match(
                "#<link type=\"text\/css\" rel=\"stylesheet\" href=\"http\:\/\/phrame.loc\/assets\/".$this->app->name.'/'.$this->app->config->theme."\/css\/bootstrap.css\?[0-9]+\" \/>#",
                $this->asset->css('bootstrap.css')
            ),
            1
        );
    }

}
