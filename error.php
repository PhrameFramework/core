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
 * Error class
 */
class Error
{
    /**
     * Application name
     * 
     * @var  string
     */
    protected $app_name = null;

    /**
     * Constructs Error object
     * 
     * @param  string  $app_name  Application name
     */    
    public function __construct($app_name = null)
    {
        $this->app_name  = $app_name ?: APPLICATION_NAME;
    }

    /**
     * Exception handler
     * 
     * @param   \Exception  $exception  Exception to handle
     * @return  void
     */
    public function exception_handler($exception)
    {
        $app = Applications::instance($this->app_name);

        if (isset($app->log))
        {
            $log_message = 'type: '.get_class($exception).'. ';
            $log_message .= 'code: '.$exception->getCode().'. ';
            $log_message .= 'message: '.$exception->getMessage().'. ';
            $log_message .= 'file: '.$exception->getFile().'. ';
            $log_message .= 'line: '.$exception->getLine();

            $app->log->write($log_message);
        }

        if ($app->config['display_exceptions'])
        {
            $view = new View(
                'errors/exception',
                array(
                    'type'     => get_class($exception),
                    'code'     => $exception->getCode(),
                    'message'  => $exception->getMessage(),
                    'file'     => $exception->getFile(),
                    'line'     => $exception->getLine(),
                    'trace'    => $exception->getTrace()
                ),
                $app
            );

            echo $view;
        }
    }

    /**
     * Error handler
     * 
     * @param   int     $errno    Level of the error
     * @param   string  $errstr   Error message
     * @param   string  $errfile  Filename that the error was raised in
     * @param   int     $errline  Line number the error was raised at
     * @return  void
     */
    public function error_handler($errno, $errstr, $errfile, $errline)
    {
        $app = Applications::instance($this->app_name);

        if (isset($app->log))
        {
            $log_message = 'code: '.$errno.'. ';
            $log_message .= 'message: '.$errstr.'. ';
            $log_message .= 'file: '.$errfile.'. ';
            $log_message .= 'line: '.$errline;

            $app->log->write($log_message);
        }

        if (error_reporting() & $errno)
        {
            $view = new View(
                'errors/error',
                array(
                    'code'     => $errno,
                    'message'  => $errstr,
                    'file'     => $errfile,
                    'line'     => $errline
                ),
                $app
            );

            echo $view;
        }
    }

}
