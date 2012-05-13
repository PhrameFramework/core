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
 * Response class
 *
 * @property  View  $body
 */
class Response
{
    /**
     * Application name
     * 
     * @var  string
     */
    protected $app_name;

    /**
     * Application object
     * 
     * @var  Application
     */
    protected $app;

    /**
     * Response status code
     * 
     * @var  int
     */
    protected $status = 200;

    /**
     * Headers
     * 
     * @var  array  Response headers
     */
    protected $header = array();

    /**
     * Cookies
     * 
     * @var  array
     */
    protected $cookie = array();

    /**
     * Session
     * 
     * @var  array
     */
    protected $session = array();

    /**
     * Layout object
     * 
     * @var  View
     */
    protected $body;

    /**
     * Constructs Response object
     * 
     * @param  string  $app_name  Application name
     */
    public function __construct($app_name = null)
    {
        $this->app_name  = $app_name ?: APPLICATION_NAME;
        $this->app       = Applications::get_instance($this->app_name);

        $this->session   = $this->app->request->session();
    }

    /**
     * Magic method for read-only properties
     * 
     * @param   string  $name  Property name
     * @return  mixed
     */
    public function __get($name)
    {
        if (in_array($name, array('body')))
        {
            if ($name === 'body' and ! isset($this->body))
            {
                $this->body = $this->get_body();
            }

            return $this->$name;
        }
        else
        {
            return null;
        }
    }

    /**
     * Sets response status
     * 
     * @param   int   $status  Status code
     * @return  void
     */
    public function set_status($status)
    {
        $this->status = $status;
    }

    /**
     * Add header
     * 
     * @param   string  $header  Header
     * @return  void
     */
    public function set_header($header)
    {
        $this->header[] = $header;
    }

    /**
     * Redirects to the url
     *
     * @param   string  $url  URL
     * @return  void
     */
    public function redirect($url)
    {
        header('Location: '.$url, true, 302);
        exit(0);
    }

    /**
     * Add session parameter
     *
     * @param   string  $name   Parameter name
     * @param   string  $value  Parameter value
     * @return  void
     */
    public function set_session($name, $value)
    {
        $this->session[$name] = $value;
    }

    /**
     * Add cookie
     * 
     * @param   string  $name      Cookie name
     * @param   string  $value     Cookie value
     * @param   int     $expire    Expire
     * @param   string  $path      Cookie path
     * @param   string  $domain    Cookie domain
     * @param   bool    $secure    Is the cookie secure?
     * @param   bool    $httponly  Is the cookie http only?
     * @return  void
     */
    public function set_cookie($name, $value, $expire = null, $path = null, $domain = null, $secure = null, $httponly = null)
    {
        $this->cookie[$name] = array(
            'name'      => $name,
            'value'     => $value,
            'expire'    => $expire   ?: time() + 60 * 60,
            'path'      => $path     ?: '/',
            'domain'    => $domain   ?: parse_url($this->app->config['base_url'], PHP_URL_HOST),
            'secure'    => $secure   ?: false,
            'httponly'  => $httponly ?: true,
        );
    }

    /**
     * Sets session parameters
     *
     * @return  void
     */
    public function send_session()
    {
        if ($this->app->config['use_sessions'] === true)
        {
            // set session parameters
            $_SESSION = $this->session;
        }
    }

    /**
     * Sends cookies
     *
     * @return  void
     */
    public function send_cookies()
    {
        foreach ($this->cookie as $cookie)
        {
            setcookie(
                isset($cookie['name'])     ? $cookie['name']     : 'phrame',
                isset($cookie['value'])    ? $cookie['value']    : '',
                isset($cookie['expire'])   ? $cookie['expire']   : time() + 60 * 60,
                isset($cookie['path'])     ? $cookie['path']     : '/',
                isset($cookie['domain'])   ? $cookie['domain']   : parse_url($this->app->config['base_url'], PHP_URL_HOST),
                isset($cookie['secure'])   ? $cookie['secure']   : false,
                isset($cookie['httponly']) ? $cookie['httponly'] : true
            );
        }
    }

    /**
     * Returns layout object
     *
     * @return  View
     */
    public function get_body()
    {
        $controller_class  = '\\'.ucfirst($this->app->route->application).'\\Controllers\\'.str_replace(' ', '\\', ucwords(str_replace('/', ' ', strtolower($this->app->route->controller))));
        $controller        = new $controller_class($this->app->route->application);
        $action            = $this->app->route->action;
        $parameters        = $this->app->route->parameters;

        if (APPLICATION_ENV === 'production')
        {
            ob_start();
        }

        if ( ! isset($controller->layout))
        {
            $controller->layout = new View('layout', array(), $this->app_name);
        }
        $output = call_user_func_array(array($controller, $action), $parameters);

        if (APPLICATION_ENV === 'production')
        {
            ob_end_clean();
        }

        // Any string data returned by the controller should be treated as a layout's content
        if ( ! empty($output) and is_string($output))
        {
            $controller->layout->content = $output;
        }

        return $controller->layout;
    }

    /**
     * Renders response
     * 
     * @return  string|null
     */
    public function render()
    {
        $body = $this->body ?: $this->get_body();

        // send session
        $this->send_session();

        // send cookies
        $this->send_cookies();

        // Send status
        header('x', true, $this->status);

        // send headers
        foreach ($this->header as $header)
        {
            header($header, false);
        }

        if ($this->app->request->method() !== 'HEAD')
        {
            return $body;
        }

        return null;
    }

}
