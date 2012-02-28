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

class Response
{
    /**
     * Application object
     * 
     * @var  Application
     */
    protected $application = null;

    /**
     * Reaponse status code
     * 
     * @var  int
     */
    protected $status = 200;

    /**
     * Status codes
     * 
     * @var  array
     */
    protected $statuses = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        507 => 'Insufficient Storage',
        509 => 'Bandwidth Limit Exceeded'
    );

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
     * Constructs Response object
     * 
     * @param  Application  $application  Application object
     */
    public function __construct($application = null)
    {
        $this->application  = $application ?: Application::instance();
        $this->session      = $this->application->request->session();
    }

    /**
     * Sets response status
     * 
     * @param  int  $status  Status code
     */
    public function status($status)
    {
        $this->status = $status;
    }

    /**
     * Add header
     * 
     * @param  string  $header  Header
     */
    public function header($header)
    {
        $this->header[] = $header;
    }

    /**
     * Add cookie
     * 
     * @param  string  $name      Cookie name
     * @param  string  $value     Cookie value
     * @param  int     $expire    Expire
     * @param  string  $path      Cookie path
     * @param  string  $domain    Cookie domain
     * @param  bool    $secure    Is the cookie secure?
     * @param  bool    $httponly  Is the cookie http only?
     */
    public function cookie($name, $value, $expire = null, $path = null, $domain = null, $secure = null, $httponly = null)
    {
        $this->cookie[$name] = array(
            'name'      => $name,
            'value'     => $value,
            'expire'    => $expire   ?: time() + 60 * 60,
            'path'      => $path     ?: '/',
            'domain'    => $domain   ?: parse_url($this->application->config->base_url, PHP_URL_HOST),
            'secure'    => $secure   ?: false,
            'httponly'  => $httponly ?: false,
        );
    }

    /**
     * Add session parameter
     * 
     * @param  string  $name   Parameter name
     * @param  string  $value  Parameter value
     */
    public function session($name, $value)
    {
        $this->session[$name] = $value;
    }

    /**
     * Redirects to the url
     * 
     * @param  string  $url  URL
     */
    public function redirect($url)
    {
        header('Location: '.$url, true, 302);
    }

    /**
     * Renders response
     * 
     * @param   bool         $render_layout  Render layout or return View object
     * @return  string|View
     */
    public function render($render_layout = true)
    {
        $controller_name  = 'Applications\\'.ucfirst($this->application->name).'\\Controllers\\'.ucfirst($this->application->route->controller);
        $controller       = new $controller_name($this->application);
        $action           = $this->application->route->action;
        $parameters       = $this->application->route->parameters;

        ob_start();
        if ( ! isset($controller->layout))
        {
            $controller->layout = new View('layout', array(), $this->application);
        }
        call_user_func_array(array($controller, $action), $parameters);
        if (method_exists($controller->layout, 'render'))
        {
            echo $controller->layout->render();
        }
        $output = ob_get_contents();
        ob_end_clean();

        if ( ! $render_layout)
        {
            return $controller->layout;
        }

        if ($this->application->config->use_sessions === true)
        {
            // set session parameters
            $_SESSION = $this->session;
        }

        // send cookies
        foreach ($this->cookie as $cookie)
        {
            setcookie(
                isset($cookie['name'])     ? $cookie['name']     : 'phrame',
                isset($cookie['value'])    ? $cookie['value']    : '',
                isset($cookie['expire'])   ? $cookie['expire']   : time() + 60 * 60,
                isset($cookie['path'])     ? $cookie['path']     : '/',
                isset($cookie['domain'])   ? $cookie['domain']   : null,
                isset($cookie['secure'])   ? $cookie['secure']   : false,
                isset($cookie['httponly']) ? $cookie['httponly'] : false
            );
        }

        // Send status
        if ($this->application->request->server('fcgi_server_version'))
        {
            header('Status: '.$this->status.' '.$this->statuses[$this->status]);
        }
        else
        {
            header($this->application->request->protocol().' '.$this->status.' '.$this->statuses[$this->status]);
        }

        // send headers
        foreach ($this->header as $header)
        {
            header($header, false);
        }

        return $output;
    }

}
