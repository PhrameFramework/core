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
 * Form class
 */
class Form extends View
{
    /**
     * Application object
     *
     * @var  Application
     */
    protected $app;

    /**
     * Validator object
     *
     * @var  Validator
     */
    protected $validator;

    /**
     * Creates Form object
     *
     * @param  string  $view_name  View name
     * @param  array   $data       Form attributes
     * @param  string  $app_name   Application name
     */
    public function __construct($view_name, $data = array(), $app_name = null)
    {
        parent::__construct($view_name, $data, $app_name);

        $this->app        = Applications::instance($this->app_name);
        $this->validator  = new Validator($this->app_name);
    }

    /**
     * Validates form attributes
     *
     * @return  bool
     */
    public function valid()
    {
        return true;
    }

    /**
     * Renders form
     *
     * @return  string
     */
    public function render()
    {
        $this->data['errors'] = $this->validator->errors;

        return parent::render();
    }

}
