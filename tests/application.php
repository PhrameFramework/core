<?php
/**
 * Part of the Phrame
 *
 * @package    Core
 * @version    0.2.0
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

    protected $config;

    public function setUp()
    {
        $this->application  = Core\Application::instance();
        $this->config       = include APPLICATIONS_PATH.'/'.APPLICATION_NAME.'/config/application.php';
    }

    public function test_name()
    {
        $this->assertTrue($this->application->name === APPLICATION_NAME);

    }

    public function test_config()
    {
        $this->assertTrue($this->application->config->base_url === 'http://phrame.loc');
        $this->assertTrue($this->application->config->error_reporting === $this->config['error_reporting']);
        $this->assertTrue($this->application->config->display_errors === $this->config['display_errors']);
        $this->assertTrue($this->application->config->use_sessions === $this->config['use_sessions']);
        $this->assertTrue($this->application->config->theme === $this->config['theme']);
        $this->assertTrue($this->application->config->packages === $this->config['packages']);

    }

}
