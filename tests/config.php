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

class Config extends \PHPUnit_Framework_TestCase
{
    protected $config;

    protected $config_array;

    public function setUp()
    {
        $this->config       = new Core\Config('application');
        $this->config_array = include APPLICATIONS_PATH.'/'.APPLICATION_NAME.'/config/application.php';
    }

    /**
     * Config test
     * 
     * See config in sandbox/applications/main/config/application.php
     * 
     * @return  void
     */
    public function test_config()
    {
        $this->assertEquals($this->config->error_reporting,  $this->config_array['error_reporting']);
        $this->assertEquals($this->config->display_errors,   $this->config_array['display_errors']);
        $this->assertEquals($this->config->use_sessions,     $this->config_array['use_sessions']);
        $this->assertEquals($this->config->theme,            $this->config_array['theme']);
        $this->assertEquals($this->config->packages,         $this->config_array['packages']);
    }

    public function test_get_set()
    {
        $option_name   = 'option';
        $option_value  = 'value';

        $this->config->$option_name = $option_value;

        $this->assertEquals($this->config->$option_name, $option_value);
    }

}
