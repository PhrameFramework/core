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
 * Request class
 */
class Request
{
    /**
     * Application name
     * 
     * @var  string
     */
    protected $app_name;

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
     * @param  string  $app_name  Application name
     * @param  array   $server    Server parameters
     * @param  array   $get       Get parameters
     * @param  array   $post      Post parameters
     * @param  array   $cookie    Cookie parameters
     * @param  array   $session   Session parameters
     */
    public function __construct($app_name = null, $server = array(), $get = array(), $post = array(), $cookie = array(), $session = array())
    {
        $this->app_name  = $app_name ?: APPLICATION_NAME;

        $this->server   = ! empty($server)  ? $server  : $_SERVER;
        $this->get      = ! empty($get)     ? $get     : $_GET;
        $this->post     = ! empty($post)    ? $post    : $_POST;
        $this->cookie   = ! empty($cookie)  ? $cookie  : $_COOKIE;
        $this->session  = ! empty($session) ? $session : (isset($_SESSION) ? $_SESSION : array());
    }

    /**
     * Escapes data
     * 
     * @param   string  $data  Data to escape
     * @return  string
     */
    public function escape($data)
    {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
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
     * Returns request method
     * 
     * @return  string
     */
    public function method()
    {
        return $this->server('request_method') ?: 'GET';
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

    /**
     * Returns true if method is GET
     *
     * @return  bool
     */
    public function is_get()
    {
        return $this->method() === 'GET';
    }

    /**
     * Returns true if method is POST
     *
     * @return  bool
     */
    public function is_post()
    {
        return $this->method() === 'POST';
    }

    /**
     * Returns true if the request is ajax
     *
     * @return  bool
     */
    public function is_ajax()
    {
        return $this->server('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest';
    }

}
