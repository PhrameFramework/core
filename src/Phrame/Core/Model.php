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

namespace Phrame\Core;

/**
 * Model class
 */
class Model
{
    /**
     * Data storage
     * 
     * @var  array
     */
    protected static $data = array();

    /**
     * Current row
     * 
     * @var  array
     */
    protected $row;

    /**
     * The key of the current row
     * 
     * @var  int
     */
    protected $key;

    /**
     * Constructs model
     * 
     * @param  array  $row  Initial data
     */
    public function __construct($row = array())
    {
        $this->row  = $row;
        $this->key  = null;
    }

    /**
     * Returns row
     * 
     * @param   string  $name  Attribute name
     * @return  mixed
     */
    public function __get($name)
    {
        return isset(self::$data[$this->key][$name]) ? self::$data[$this->key][$name] : null;
    }

    /**
     * Sets row
     * 
     * @param   string  $name   Attribute name
     * @param   mixed   $value  Attribute value
     * @return  void
     */
    public function __set($name, $value)
    {
        $this->row[$name] = $value;
    }

    /**
     * Saves row to the storage
     * 
     * @return  void
     */
    public function save()
    {
        if ( ! isset($this->key))
        {
            self::$data[] = $this->row;
            $keys = array_keys(self::$data);
            $this->key = end($keys);
        }
        else
        {
            self::$data[$this->key] = $this->row;
        }
    }

    /**
     * Deletes data from the storage
     * 
     * @return  void
     */
    public function delete()
    {
        $key = array_search($this->row, self::$data);

        if ($key !== false)
        {
            unset(self::$data[$key]);
            unset($this);
        }
    }

    /**
     * Finds data from the storage
     * 
     * @param   string  $condition  Find condition
     * @return  array
     */
    public static function find($condition = 'all')
    {
        $keys = array();

        if ($condition === 'all')
        {
            $keys = array_keys(self::$data);
        }
        else
        {
            $condition_elements   = explode(' ', $condition);
            $condition_field      = array_shift($condition_elements);
            $condition_operation  = array_shift($condition_elements);
            $condition_value      = implode(' ', $condition_elements);

            foreach (self::$data as $key => $item)
            {
                if (eval('return "'.$item[$condition_field].'"'.$condition_operation.$condition_value.';') === true)
                {
                    $keys[] = $key;
                }
            }
        }

        $data = null;
        if (count($keys) === 1)
        {
            $key        = array_shift($keys);
            $item       = new self(self::$data[$key]);
            $item->key  = $key;

            $data = $item;
        }
        elseif (count($keys) > 1)
        {
            $data = array();
            foreach ($keys as $key)
            {
                $item       = new self(self::$data[$key]);
                $item->key  = $key;

                $data[] = $item;
            }
        }

        return $data;
    }

    /**
     * Returns data count
     * 
     * @return  int
     */
    public static function count()
    {
        return count(self::$data);
    }

}
