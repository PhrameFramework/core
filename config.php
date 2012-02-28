<?php
/**
 * Part of the Phrame
 *
 * @package    Core
 * @version    0.0.0
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
     * Contsructs Config object
     * 
     * @param  string  $config_name  Configuration name
     * @param  string  $application  Application object
     * @param  string  $package      Package name
     */
    public function __construct($config_name = null, $application = null, $package = 'core')
    {
        $config_name  = $config_name ?: 'application';
        $application  = $application ?: Application::instance();
        $package      = $package     ?: 'core';

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

        if (is_file(APPLICATIONS_PATH.'/'.$application->name.'/config/'.$config_name.'.php'))
        {
            $this->config = array_merge(
                $this->config,
                include APPLICATIONS_PATH.'/'.$application->name.'/config/'.$config_name.'.php'
            );
        }
        if (is_file(APPLICATIONS_PATH.'/'.$application->name.'/config/'.APPLICATION_ENV.'/'.$config_name.'.php'))
        {
            $this->config = array_merge(
                $this->config,
                include APPLICATIONS_PATH.'/'.$application->name.'/config/'.APPLICATION_ENV.'/'.$config_name.'.php'
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
     */
    public function __set($name, $value)
    {
        $this->config[$name] = $value;
    }

}
