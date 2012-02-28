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

class Request
{
    /**
     * Application object
     * 
     * @var  Application
     */
    protected $application = null;

    /**
     * Server parameters
     * 
     * @var  array
     */
    protected $server = array();

    /**
     * Get parameters
     * 
     * @var  array
     */
    protected $get = array();

    /**
     * Post parameters
     * 
     * @var  array
     */
    protected $post = array();

    /**
     * Cookie parameters
     * 
     * @var  array
     */
    protected $cookie = array();

    /**
     * Session parameters
     * 
     * @var  array
     */
    protected $session = array();

    /**
     * Constructs Request object
     * 
     * @param  Application  $application  Application object
     * @param  array        $server       Server parameters
     * @param  array        $get          Get parameters
     * @param  array        $post         Post parameters
     * @param  array        $cookie       Cookie parameters
     * @param  array        $session      Session parameters
     */
    public function __construct($application = null, $server = array(), $get = array(), $post = array(), $cookie = array(), $session = array())
    {
        $this->application = $application ?: Application::instance();

        $this->server   = ! empty($server)  ? $server  : $_SERVER;
        $this->get      = ! empty($get)     ? $get     : $_GET;
        $this->post     = ! empty($post)    ? $post    : $_POST;
        $this->cookie   = ! empty($cookie)  ? $cookie  : $_COOKIE;
        $this->session  = ! empty($session) ? $session : ($this->application->config->use_sessions === true ? $_SESSION : array());
    }

    /**
     * Returns protocol
     * 
     * @return  string
     */
    public function protocol()
    {
        return $this->server('server_protocol') ?: 'HTTP/1.1';
    }

    /**
     * Returns server parameter
     * 
     * @param   string  $name   Parameter name
     * @param   mixed   $value  New value
     * @return  string
     */
    public function server($name = null, $value = null)
    {
        if ( ! isset($name))
        {
            return $this->server;
        }

        if (isset($value))
        {
            $this->server[strtoupper($name)] = $value;
        }

        return isset($this->server[strtoupper($name)]) ? $this->server[strtoupper($name)] : null;
    }

    /**
     * Returns get parameter
     * 
     * @param   string  $name   Parameter name
     * @param   mixed   $value  New value
     * @return  string
     */
    public function get($name = null, $value = null)
    {
        if ( ! isset($name))
        {
            return $this->get;
        }

        if (isset($value))
        {
            $this->get[$name] = $value;
        }

        return isset($this->get[$name]) ? $this->get[$name] : null;
    }

    /**
     * Returns post parameter
     * 
     * @param   string  $name   Parameter name
     * @param   mixed   $value  New value
     * @return  string
     */
    public function post($name = null, $value = null)
    {
        if ( ! isset($name))
        {
            return $this->post;
        }

        if (isset($value))
        {
            $this->post[$name] = $value;
        }

        return isset($this->post[$name]) ? $this->post[$name] : null;
    }

    /**
     * Returns cookie parameter
     * 
     * @param   string  $name   Parameter name
     * @param   mixed   $value  New value
     * @return  string
     */
    public function cookie($name = null, $value = null)
    {
        if ( ! isset($name))
        {
            return $this->cookie;
        }

        if (isset($value))
        {
            $this->cookie[$name] = $value;
        }

        return isset($this->cookie[$name]) ? $this->cookie[$name] : null;
    }

    /**
     * Returns session parameter
     * 
     * @param   string  $name   Parameter name
     * @param   mixed   $value  New value
     * @return  string
     */
    public function session($name = null, $value = null)
    {
        if ( ! isset($name))
        {
            return $this->session;
        }

        if (isset($value))
        {
            $this->session[$name] = $value;
        }

        return isset($this->session[$name]) ? $this->session[$name] : null;
    }

}
