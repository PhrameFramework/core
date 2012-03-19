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

class Asset extends \PHPUnit_Framework_TestCase
{
    protected $application;

    protected $asset;

    public function setUp()
    {
        $this->application  = Core\Application::instance();
        $this->asset        = new Core\Asset($this->application);
    }

    public function test_css()
    {
        $this->assertEquals(
            preg_match(
                "#<link type=\"text\/css\" rel=\"stylesheet\" href=\"http\:\/\/phrame.loc\/assets\/".$this->application->name.'-'.$this->application->config->theme."\/css\/bootstrap.css\?[0-9]+\" \/>#",
                $this->asset->css('bootstrap.css')
            ),
            1
        );
    }

}
