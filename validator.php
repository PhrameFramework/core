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
     * @param   mixed         $value       Value
     * @param   string        $rules       Rules, separated by |
     * @param   string|array  $parameters  Field name and other parameters
     * @param   string|array  $messages    Messages
     * @return  bool
     */
    public function validate($value, $rules, $parameters = array(), $messages = array())
    {
        $return = true;

        $rules = explode('|', $rules);

        foreach ($rules as $rule)
        {
            $rule = trim($rule);

            $valid = preg_match('#'.$this->config[$rule]['rule'].'#', $value) > 0;

            if ( ! $valid)
            {
                if ( ! is_array($parameters))
                {
                    $parameters = array('name' => $parameters);
                }

                if ( ! is_array($messages))
                {
                    $messages = array($messages);
                }

                $app = Applications::get_instance($this->app_name);
                $message = isset($messages[$rule]) ? $messages[$rule] : $this->config[$rule]['message'];
                $this->errors[] = $app->lang->get($message, $parameters);
            }

            $return = $valid && $return;
        }

        return $return;
    }

}
