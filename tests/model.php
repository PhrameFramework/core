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

class Model extends \PHPUnit_Framework_TestCase
{
    protected $model;

    protected function setUp()
    {

    }

    public function test_create()
    {
        $row = new Core\Model(
            array(
                'id'    => 0,
                'name'  => 'phrame'
            )
        );
        $row->save();

        $this->assertEquals($row->name, 'phrame');

        // cleaning up
        $row->delete();
    }

    public function test_insert()
    {
        $row = new Core\Model();
        $row->id    = 0;
        $row->name  = 'phrame';
        $row->save();

        $this->assertEquals($row->name, 'phrame');

        // cleaning up
        $row->delete();
    }

    public function test_update()
    {
        $row = new Core\Model();
        $row->id    = 0;
        $row->name  = 'phrame';
        $row->save();

        $this->assertEquals($row->name, 'phrame');

        $row->name = 'phrame framework';

        $this->assertEquals($row->name, 'phrame');

        $row->save();

        $this->assertEquals($row->name, 'phrame framework');

        // cleaning up
        $row->delete();
    }

    public function test_delete()
    {
        $row = new Core\Model();
        $row->id    = 0;
        $row->name  = 'phrame';
        $row->save();

        $this->assertEquals($row->name, 'phrame');

        $row->delete();

        $this->assertEquals($row->name, null);
    }

    public function test_find()
    {
        $expected = array(
            0 => 'phrame',
            1 => 'php'
        );

        foreach ($expected as $key => $name)
        {
            $row = new Core\Model();
            $row->id    = $key;
            $row->name  = $name;
            $row->save();
        }

        $all = Core\Model::find('all');

        foreach ($all as $key => $row)
        {
            $this->assertEquals($row->name, $expected[$key]);
        }

        $php = Core\Model::find('name == "php"');

        $this->assertEquals($php->name, 'php');
    }

}
