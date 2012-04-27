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
 * Validator class
 *
 * @property  array  $errors  Validation errors
 */
class Validator
{
    /**
     * Application name
     *
     * @var  string
     */
    protected $app_name;

    /**
     * Validation configuration
     *
     * @var  Config
     */
    protected $config;

    /**
     * Validation errors
     *
     * @var  array
     */
    public $errors = array();

    /**
     * Constructs Validation object
     *
     * @param  string  $app_name  Application name
     */
    public function __construct($app_name = null)
    {
        $this->app_name = $app_name ?: APPLICATION_NAME;
        $this->config   = new Config('validator', $this->app_name);
    }

    /**
     * Validates value on rule
     *
     * @param   mixed   $value       Value
     * @param   string  $rule_name   Rule name
     * @param   array   $parameters  Field name and other parameters
     * @param   string  $message     Message
     * @return  bool
     */
    public function validate($value, $rule_name, $parameters = array(), $message = '')
    {
        $valid = preg_match('#'.$this->config[$rule_name]['rule'].'#', $value) > 0;

        if ( ! $valid)
        {
            if ( ! is_array($parameters))
            {
                $parameters = array('name' => $parameters);
            }

            $app = Applications::instance($this->app_name);
            $message = $message ?: $this->config[$rule_name]['message'];
            $this->errors[] = $app->lang->get($message, $parameters);
        }

        return $valid;
    }

}
