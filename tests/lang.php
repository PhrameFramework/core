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

class Lang extends \PHPUnit_Framework_TestCase
{
    protected $app;

    protected $lang;

    public function setUp()
    {
        $this->app   = Core\Applications::instance(APPLICATION_NAME, true);
        $this->lang  = new Core\Lang($this->app->name);
    }

    /**
     * Lang test
     * 
     * See config in sandbox/applications/main/config/lang.php
     * 
     * @return  void
     */
    public function test_translation()
    {
        $this->assertEquals($this->lang->get('Home'), 'Главная');
        $this->assertEquals($this->lang->get('Test'), 'Test');
    }

}
