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

class Config
{
    /**
     * Configuration array
     * 
     * @var  array
     */
    protected $config = array();

    /**
     * Contstructs Config object
     * 
     * @param   string  $config_name  Configuration name
     * @param   string  $app_name     Application name
     * @param   string  $package      Package name
     * @return  void
     */
    public function __construct($config_name = null, $app_name = null, $package = null)
    {
        $config_name  = $config_name ?: 'application';
        $app_name     = $app_name    ?: APPLICATION_NAME;
        $package      = $package     ?: 'phrame/core';

        $this->config = array();

        // Process config files

        if (is_file(PACKAGES_PATH.'/'.$package.'/config/'.$config_name.'.php'))
        {
            $this->config = array_merge(
                $this->config,
                include PACKAGES_PATH.'/'.$package.'/config/'.$config_name.'.php'
            );
        }
        if (is_file(PACKAGES_PATH.'/'.$package.'/config/'.APPLICATION_ENV.'/'.$config_name.'.php'))
        {
            $this->config = array_merge(
                $this->config,
                include PACKAGES_PATH.'/'.$package.'/config/'.APPLICATION_ENV.'/'.$config_name.'.php'
            );
        }

        if (is_file(APPLICATIONS_PATH.'/'.$app_name.'/config/'.$config_name.'.php'))
        {
            $this->config = array_merge(
                $this->config,
                include APPLICATIONS_PATH.'/'.$app_name.'/config/'.$config_name.'.php'
            );
        }
        if (is_file(APPLICATIONS_PATH.'/'.$app_name.'/config/'.APPLICATION_ENV.'/'.$config_name.'.php'))
        {
            $this->config = array_merge(
                $this->config,
                include APPLICATIONS_PATH.'/'.$app_name.'/config/'.APPLICATION_ENV.'/'.$config_name.'.php'
            );
        }
    }

    /**
     * Returns configuration option
     * 
     * @param   string  $name  Option name
     * @return  mixed
     */
    public function __get($name)
    {
        return isset($this->config[$name]) ? $this->config[$name] : null;
    }

    /**
     * Sets configuration option
     * 
     * @param   string  $name   Option name
     * @param   mixed   $value  Option value
     * @return  void
     */
    public function __set($name, $value)
    {
        $this->config[$name] = $value;
    }

}
